<?php

// Transfert de passagers entre deux gares d'une même localité : quand le car d'une gare
// n'est pas complet, ses passagers réservés peuvent être consolidés sur le car d'une gare
// voisine (même destination, même heure, même jour) plutôt que de faire partir deux cars
// à moitié vides. Tout ou rien : le transfert n'a lieu que si la gare cible peut accueillir
// la totalité des passagers de la gare source. Voir ajout_transfert_gare.sql.
class Transfert_gare extends Model
{
    // Gares dont le créneau (même destination/heure/jour/compagnie/localité) est compatible
    // avec celui de $id_programmation_source, pour proposer une cible de transfert.
    public function getGaresCompatibles($id_programmation_source)
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        $droit        = $_SESSION['droit'] ?? null;

        $source = $this->getProgrammationAvecAgence($id_programmation_source, $id_compagnie);
        if (!$source || $source['statut'] !== 'active') {
            return [];
        }

        // Un chef d'escale ne peut consulter les alternatives que pour sa propre gare,
        // jamais pour le compte d'une autre (même si l'id_programmation posté est valide).
        if ($droit === 'chef_d_escale' && (int)$source['id_agence'] !== (int)($_SESSION['id_agence'] ?? 0)) {
            return [];
        }

        return $this->fetchAll(
            "SELECT pv2.id_programmation, a2.idAgence, a2.numeroGare,
                    c.nbr_place, c.nbr_place_reserve,
                    (c.nbr_place - c.nbr_place_reserve) AS places_libres
             FROM programmation_voyage pv2
             JOIN agence a2 ON pv2.id_agence = a2.idAgence
             JOIN car c ON c.id_car = pv2.id_car_programmer
             WHERE pv2.id_horaire = :h AND pv2.id_trajet = :t AND pv2.date_enregistre = :d
               AND pv2.id_compagnie = :ic AND pv2.statut = 'active'
               AND a2.localite = :localite AND a2.idAgence != :id_agence_source
             ORDER BY a2.numeroGare",
            [
                ':h'                 => $source['id_horaire'],
                ':t'                 => $source['id_trajet'],
                ':d'                 => $source['date_enregistre'],
                ':ic'                => $id_compagnie,
                ':localite'          => $source['localite'],
                ':id_agence_source'  => $source['id_agence']
            ]
        );
    }

    // Aperçu (lecture seule, sans verrou) du montant et du nombre de passagers qui seraient
    // transférés, pour affichage avant confirmation par le chef d'escale.
    public function getApercu($id_programmation_source, $id_programmation_destination)
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;

        $source = $this->getProgrammationAvecAgence($id_programmation_source, $id_compagnie);
        $dest   = $this->getProgrammationAvecAgenceEtCar($id_programmation_destination, $id_compagnie);

        if (!$source || !$dest) {
            return false;
        }

        [$sql, $params] = $this->requeteBilletsDuCreneau($source, $id_compagnie);
        $billets = $this->fetchAll($sql, $params);

        $totalPassagers   = (int)array_sum(array_column($billets, 'nombrePassages'));
        $totalMontant     = (float)array_sum(array_column($billets, 'montant_payer'));
        $placesLibresDest = (int)$dest['nbr_place'] - (int)$dest['nbr_place_reserve'];

        return [
            'nombre_billets'     => count($billets),
            'nombre_passagers'   => $totalPassagers,
            'montant_total'      => $totalMontant,
            'places_libres_dest' => $placesLibresDest,
            'numero_gare_dest'   => $dest['numeroGare'],
            'possible'           => $totalPassagers > 0 && $totalPassagers <= $placesLibresDest,
        ];
    }

    // Exécute le transfert : tout ou rien, transactionnel, verrouille les deux cars et les
    // billets concernés pour empêcher toute vente/annulation concurrente de fausser les compteurs.
    public function transfererPassagers($id_programmation_source, $id_programmation_destination)
    {
        if (!csrf_verify()) {
            $this->set_flash("Session expirée, veuillez réessayer.", "danger");
            return false;
        }

        $droit = $_SESSION['droit'] ?? null;
        if (!in_array($droit, ['chef_d_escale', 'Admin', 'super_admin'], true)) {
            $this->set_flash("Accès refusé.", "danger");
            return false;
        }

        if ($id_programmation_source == $id_programmation_destination) {
            $this->set_flash("Impossible de transférer une gare vers elle-même.", "danger");
            return false;
        }

        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        $pdo = $this->connect();

        try {
            $pdo->beginTransaction();

            // 1) Programmation + car source, verrouillés.
            $stmt = $pdo->prepare(
                "SELECT pv.*, c.id_car, c.status_car, c.nbr_place, c.nbr_place_reserve, a.localite, a.numeroGare
                 FROM programmation_voyage pv
                 JOIN car c ON c.id_car = pv.id_car_programmer
                 JOIN agence a ON a.idAgence = pv.id_agence
                 WHERE pv.id_programmation = :id AND pv.id_compagnie = :ic
                 FOR UPDATE"
            );
            $stmt->execute([':id' => $id_programmation_source, ':ic' => $id_compagnie]);
            $source = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$source) {
                $pdo->rollBack();
                $this->set_flash("Programmation source introuvable.", "danger");
                return false;
            }

            if ($source['statut'] !== 'active') {
                $pdo->rollBack();
                $this->set_flash("Ce voyage est déjà annulé ou a déjà fait l'objet d'un transfert.", "danger");
                return false;
            }

            if ($source['status_car'] !== null && strpos($source['status_car'], 'En_transit_') === 0) {
                $pdo->rollBack();
                $this->set_flash("Ce car est déjà parti : impossible de transférer ses passagers.", "danger");
                return false;
            }

            // Un chef d'escale ne peut transférer que les passagers de sa propre gare, même si
            // l'id_programmation posté correspond à une gare valide (protection IDOR).
            if ($droit === 'chef_d_escale' && (int)$source['id_agence'] !== (int)($_SESSION['id_agence'] ?? 0)) {
                $pdo->rollBack();
                $this->set_flash("Vous ne pouvez transférer que les passagers de votre propre gare.", "danger");
                return false;
            }

            // 2) Programmation + car destination, verrouillés. Revalidée intégralement côté
            // serveur (même créneau exact, même localité, gare différente, voyage actif) :
            // jamais faire confiance à une simple correspondance postée par le client.
            $stmt = $pdo->prepare(
                "SELECT pv.*, c.id_car, c.status_car, c.nbr_place, c.nbr_place_reserve, a.localite, a.numeroGare
                 FROM programmation_voyage pv
                 JOIN car c ON c.id_car = pv.id_car_programmer
                 JOIN agence a ON a.idAgence = pv.id_agence
                 WHERE pv.id_programmation = :id AND pv.id_compagnie = :ic
                 FOR UPDATE"
            );
            $stmt->execute([':id' => $id_programmation_destination, ':ic' => $id_compagnie]);
            $dest = $stmt->fetch(PDO::FETCH_ASSOC);

            $compatible = $dest
                && $dest['statut'] === 'active'
                && (int)$dest['id_agence'] !== (int)$source['id_agence']
                && $dest['id_horaire'] === $source['id_horaire']
                && $dest['id_trajet'] === $source['id_trajet']
                && $dest['date_enregistre'] === $source['date_enregistre']
                && $dest['localite'] === $source['localite'];

            if (!$compatible) {
                $pdo->rollBack();
                $this->set_flash("Cette gare n'est plus une cible valide pour ce transfert.", "danger");
                return false;
            }

            // 3) Billets actifs de la source, verrouillés (destination finale + escales du créneau).
            [$sql, $params] = $this->requeteBilletsDuCreneau($source, $id_compagnie);
            $stmt = $pdo->prepare($sql . " FOR UPDATE");
            $stmt->execute($params);
            $billets = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalPassagers = (int)array_sum(array_column($billets, 'nombrePassages'));
            $totalMontant   = (float)array_sum(array_column($billets, 'montant_payer'));

            if ($totalPassagers === 0) {
                $pdo->rollBack();
                $this->set_flash("Aucun passager à transférer sur cette gare.", "danger");
                return false;
            }

            // 4) Tout ou rien : le transfert n'a lieu que si la gare cible peut accueillir
            // la totalité des passagers de la source (objectif : économiser un car, pas
            // déplacer une partie seulement).
            $placesLibresDest = (int)$dest['nbr_place'] - (int)$dest['nbr_place_reserve'];
            if ($totalPassagers > $placesLibresDest) {
                $pdo->rollBack();
                $this->set_flash("Transfert impossible : places insuffisantes à la gare cible ($placesLibresDest restantes pour $totalPassagers passagers).", "danger");
                return false;
            }

            // 5) Une caisse ouverte est indispensable des deux côtés : la recette doit toujours
            // avoir une caisse d'origine à débiter et une caisse d'accueil à créditer.
            $caisseSource = $this->fetchOne(
                "SELECT id_caisse FROM caisse WHERE id_agence = :a AND status_caisse = 1 LIMIT 1",
                [':a' => $source['id_agence']]
            );
            if (!$caisseSource) {
                $pdo->rollBack();
                $this->set_flash("Transfert impossible : aucune caisse ouverte pour votre gare.", "danger");
                return false;
            }

            $caisseDest = $this->fetchOne(
                "SELECT id_caisse FROM caisse WHERE id_agence = :a AND status_caisse = 1 LIMIT 1",
                [':a' => $dest['id_agence']]
            );
            if (!$caisseDest) {
                $pdo->rollBack();
                $this->set_flash("Transfert impossible : aucune caisse ouverte à la gare cible pour recevoir la recette.", "danger");
                return false;
            }

            // 6) Enregistrement de l'en-tête du transfert (avant les lignes détail, pour l'id_transfert).
            $stmt = $pdo->prepare(
                "INSERT INTO transferts_gare (
                    id_compagnie, id_agence_source, id_agence_destination,
                    id_programmation_source, id_programmation_destination,
                    id_caisse_source, id_caisse_destination,
                    nombre_billets, nombre_passagers, montant_total, id_utilisateur
                ) VALUES (
                    :ic, :aS, :aD, :pS, :pD, :cS, :cD, :nb, :np, :m, :u
                )"
            );
            $stmt->execute([
                ':ic' => $id_compagnie,
                ':aS' => $source['id_agence'],
                ':aD' => $dest['id_agence'],
                ':pS' => $id_programmation_source,
                ':pD' => $id_programmation_destination,
                ':cS' => $caisseSource['id_caisse'],
                ':cD' => $caisseDest['id_caisse'],
                ':nb' => count($billets),
                ':np' => $totalPassagers,
                ':m'  => $totalMontant,
                ':u'  => $_SESSION['id_utilisateur']
            ]);
            $idTransfert = $pdo->lastInsertId();

            // 7) Déplacement des billets : gare + numéro de place recalculé par rapport aux places
            // déjà occupées côté destination (même logique d'attribution que reporte_voyage()).
            $prochainePlace = (int)$dest['nbr_place_reserve'];
            foreach ($billets as $b) {
                $start = $prochainePlace + 1;
                $end   = $start + (int)$b['nombrePassages'] - 1;
                $numPlace = ((int)$b['nombrePassages'] === 1) ? "$start" : "$start-$end";
                $prochainePlace = $end;

                $pdo->prepare("UPDATE billets SET num_gare = :ng, numeroPlace = :np WHERE idBillets = :id")
                    ->execute([':ng' => $dest['numeroGare'], ':np' => $numPlace, ':id' => $b['idBillets']]);

                $pdo->prepare(
                    "INSERT INTO transfert_billets (id_transfert, idBillets, ancien_num_gare, nouveau_num_gare)
                     VALUES (:t, :b, :anc, :nouv)"
                )->execute([
                    ':t'    => $idTransfert,
                    ':b'    => $b['idBillets'],
                    ':anc'  => $source['numeroGare'],
                    ':nouv' => $dest['numeroGare']
                ]);
            }

            // 8) Compteurs de places : tout part du côté destination, plus rien côté source
            // (puisque la totalité des passagers a été transférée).
            $pdo->prepare("UPDATE car SET nbr_place_reserve = nbr_place_reserve + :n WHERE id_car = :id")
                ->execute([':n' => $totalPassagers, ':id' => $dest['id_car']]);

            $pdo->prepare("UPDATE car SET nbr_place_reserve = 0, status_car = :loc WHERE id_car = :id")
                ->execute([':loc' => $source['localite_user'], ':id' => $source['id_car']]);

            // 9) Le voyage de la gare source est annulé : aucun car ne part de son côté.
            $pdo->prepare("UPDATE programmation_voyage SET statut = 'annulee' WHERE id_programmation = :id")
                ->execute([':id' => $id_programmation_source]);

            // 10) Mouvement de caisse A -> B, jamais négatif côté source.
            $pdo->prepare("UPDATE caisse SET montant_billets = GREATEST(0, montant_billets - :m) WHERE id_caisse = :id")
                ->execute([':m' => $totalMontant, ':id' => $caisseSource['id_caisse']]);

            $pdo->prepare("UPDATE caisse SET montant_billets = montant_billets + :m WHERE id_caisse = :id")
                ->execute([':m' => $totalMontant, ':id' => $caisseDest['id_caisse']]);

            $pdo->commit();
            $this->set_flash(
                "Transfert effectué : $totalPassagers passager(s) et " . number_format($totalMontant, 0, ',', ' ') . " FCFA déplacés vers la gare {$dest['numeroGare']}.",
                "success"
            );
            return true;
        } catch (Throwable $e) {
            $pdo->rollBack();
            $this->set_flash("Erreur lors du transfert : " . $e->getMessage(), "danger");
            return false;
        }
    }

    // Historique des transferts, visible selon le rôle : le chef d'escale ne voit que les
    // transferts où sa gare est source ou destination, l'Admin/super_admin voit tout.
    public function getHistorique()
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        $droit        = $_SESSION['droit'] ?? null;

        $condition = 't.id_compagnie = :id_compagnie';
        $params    = ['id_compagnie' => $id_compagnie];

        if ($droit === 'chef_d_escale') {
            $condition .= ' AND (t.id_agence_source = :id_agence OR t.id_agence_destination = :id_agence)';
            $params['id_agence'] = $_SESSION['id_agence'] ?? null;
        }

        // Alias agSrc/agDst (et non aS/aD) : "aS" est interprété par MySQL comme le mot-clé
        // AS (insensible à la casse) quand il est utilisé comme alias de table sans guillemets,
        // ce qui casse la requête ("LEFT JOIN agence aS ON ..." -> erreur de syntaxe).
        return $this->FetchSelectWheres(
            't.*,
             agSrc.localite AS localite_source, agSrc.numeroGare AS numeroGare_source,
             agDst.localite AS localite_destination, agDst.numeroGare AS numeroGare_destination,
             u.utilisateurs AS agent',
            'transferts_gare t
                LEFT JOIN agence agSrc ON t.id_agence_source = agSrc.idAgence
                LEFT JOIN agence agDst ON t.id_agence_destination = agDst.idAgence
                LEFT JOIN utilisateur u ON t.id_utilisateur = u.idUser',
            $condition . ' ORDER BY t.date_transfert DESC',
            $params
        );
    }

    // Programmation + gare (localite/numeroGare), sans jointure car : utilisé pour la détection
    // et l'aperçu (lecture seule).
    private function getProgrammationAvecAgence($id_programmation, $id_compagnie)
    {
        return $this->fetchOne(
            "SELECT pv.id_horaire, pv.id_trajet, pv.date_enregistre, pv.id_agence, pv.statut,
                    a.localite, a.numeroGare
             FROM programmation_voyage pv
             JOIN agence a ON a.idAgence = pv.id_agence
             WHERE pv.id_programmation = :id AND pv.id_compagnie = :ic",
            [':id' => $id_programmation, ':ic' => $id_compagnie]
        );
    }

    // Idem, avec en plus la capacité du car (pour l'aperçu de la gare cible).
    private function getProgrammationAvecAgenceEtCar($id_programmation, $id_compagnie)
    {
        return $this->fetchOne(
            "SELECT pv.id_agence, a.numeroGare, c.nbr_place, c.nbr_place_reserve
             FROM programmation_voyage pv
             JOIN agence a ON a.idAgence = pv.id_agence
             JOIN car c ON c.id_car = pv.id_car_programmer
             WHERE pv.id_programmation = :id AND pv.id_compagnie = :ic",
            [':id' => $id_programmation, ':ic' => $id_compagnie]
        );
    }

    // Requête (SQL + params) des billets actifs d'un créneau (destination finale + escales),
    // réutilisée telle quelle pour l'aperçu (fetchAll) et l'exécution (FOR UPDATE dans la transaction).
    private function requeteBilletsDuCreneau($programmation, $id_compagnie)
    {
        $programmationVoyage = new Programmation_voyage();
        $destinations = $programmationVoyage->getDestinationsPourCreneau(
            $programmation['id_horaire'],
            $programmation['id_trajet'],
            $programmation['localite'],
            $id_compagnie,
            $programmation['id_agence']
        );

        $placeholders = implode(',', array_fill(0, count($destinations), '?'));
        // client.montant_payer est un varchar : certains anciens billets peuvent contenir une
        // valeur formatée ("3 000 FCFA") plutôt qu'un nombre pur. Même cast défensif que
        // Depense::getBenefice() pour ne jamais fausser le total transféré.
        $sql = "SELECT b.idBillets, b.nombrePassages,
                       CAST(REPLACE(REPLACE(cl.montant_payer, ' ', ''), 'FCFA', '') AS DECIMAL(12,2)) AS montant_payer
                FROM billets b
                JOIN client cl ON cl.idClient = b.id_client
                WHERE b.jourVoyage = ? AND b.Heur_departs = ? AND b.departId = ? AND b.num_gare = ? AND b.id_compagnie = ?
                  AND b.destinationId IN ($placeholders)
                  AND (b.status_billets IS NULL OR b.status_billets != 'annule')";

        $params = array_merge(
            [$programmation['date_enregistre'], $programmation['id_horaire'], $programmation['localite'], $programmation['numeroGare'], $id_compagnie],
            $destinations
        );

        return [$sql, $params];
    }
}
