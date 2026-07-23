<?php

// Dépôt en banque : en fin de journée, le chef d'escale demande le dépôt d'une partie
// de sa caisse dans un compte banque de la compagnie. Workflow à deux temps : la demande
// (en_attente) ne débite la caisse et ne crédite la banque qu'une fois confirmée par un
// Admin/super_admin (confirme) ; l'admin peut aussi la rejeter (rejete), auquel cas rien
// ne bouge financièrement. Voir ajout_banque.sql.
class Banque extends Model
{
    // Comptes banque actifs de la compagnie, proposés au chef d'escale lors d'une demande.
    // customQuery() (et non fetchAll()) : les vues accèdent aux résultats en syntaxe objet.
    public function getBanquesActives($id_compagnie)
    {
        return $this->customQuery(
            "SELECT * FROM banques WHERE id_compagnie = :ic AND statut = 'active' ORDER BY nom",
            [':ic' => $id_compagnie]
        );
    }

    // Tous les comptes banque (actifs + inactifs), pour l'écran de gestion Admin/super_admin.
    public function getBanques($id_compagnie)
    {
        return $this->customQuery(
            "SELECT * FROM banques WHERE id_compagnie = :ic ORDER BY nom",
            [':ic' => $id_compagnie]
        );
    }

    public function creerBanque()
    {
        if (!csrf_verify()) {
            $this->set_flash("Session expirée, veuillez réessayer.", "danger");
            return false;
        }

        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'super_admin'], true)) {
            $this->set_flash("Accès refusé.", "danger");
            return false;
        }

        $nom = trim($_POST['nom'] ?? '');
        if ($nom === '') {
            $this->set_flash("Le nom du compte banque est obligatoire.", "danger");
            return false;
        }

        $this->insertion_update_simples(
            "INSERT INTO banques (id_compagnie, nom, numero_compte) VALUES (:ic, :nom, :num)",
            [
                ':ic'  => $_SESSION['id_compagnie'],
                ':nom' => $nom,
                ':num' => trim($_POST['numero_compte'] ?? '') ?: null
            ]
        );

        $this->set_flash("Compte banque ajouté.", "success");
        return true;
    }

    public function modifierBanque()
    {
        if (!csrf_verify()) {
            $this->set_flash("Session expirée, veuillez réessayer.", "danger");
            return false;
        }

        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'super_admin'], true)) {
            $this->set_flash("Accès refusé.", "danger");
            return false;
        }

        $nom = trim($_POST['nom'] ?? '');
        $statut = $_POST['statut'] ?? '';
        if ($nom === '' || !in_array($statut, ['active', 'inactive'], true)) {
            $this->set_flash("Données invalides.", "danger");
            return false;
        }

        $this->insertion_update_simples(
            "UPDATE banques SET nom = :nom, numero_compte = :num, statut = :statut
             WHERE id_banque = :id AND id_compagnie = :ic",
            [
                ':nom'    => $nom,
                ':num'    => trim($_POST['numero_compte'] ?? '') ?: null,
                ':statut' => $statut,
                ':id'     => $_POST['id_banque'] ?? null,
                ':ic'     => $_SESSION['id_compagnie']
            ]
        );

        $this->set_flash("Compte banque mis à jour.", "success");
        return true;
    }

    // Solde actuellement disponible dans la caisse d'une gare : même formule que
    // "Total actuel" dans app/views/admin/caisse.view.php (revenus - remboursements - dépenses).
    private function soldeCaisse($caisse)
    {
        return (float)$caisse['montant_billets'] + (float)$caisse['montant_colis']
            - (float)$caisse['montant_rembourse'] - (float)$caisse['montant_depense'];
    }

    // Crée une demande de dépôt pour la caisse ouverte de la gare de l'utilisateur connecté.
    // L'argent ne bouge pas à ce stade : seul un Admin/super_admin peut la confirmer.
    public function creerDemande()
    {
        if (!csrf_verify()) {
            $this->set_flash("Session expirée, veuillez réessayer.", "danger");
            return false;
        }

        $droit = $_SESSION['droit'] ?? null;
        if (!in_array($droit, ['chef_d_escale', 'Admin'], true)) {
            $this->set_flash("Accès refusé.", "danger");
            return false;
        }

        $id_compagnie = $_SESSION['id_compagnie'];

        // Un chef d'escale ne peut viser que sa propre gare ; un Admin choisit la gare dans
        // le formulaire (comme pour une dépense locale, Depense::saveDepense()), mais elle
        // doit appartenir à sa compagnie (protection IDOR).
        if ($droit === 'chef_d_escale') {
            $id_agence = $_SESSION['id_agence'] ?? null;
        } else {
            $id_agence = $_POST['id_agence'] ?? null;
            $agenceValide = $this->fetchOne(
                "SELECT idAgence FROM agence WHERE idAgence = :a AND id_compagnie = :ic",
                [':a' => $id_agence, ':ic' => $id_compagnie]
            );
            if (!$agenceValide) {
                $this->set_flash("Cette gare n'appartient pas à votre compagnie.", "danger");
                return false;
            }
        }
        if (!$id_agence) {
            $this->set_flash("Veuillez choisir la gare concernée par ce dépôt.", "danger");
            return false;
        }

        $montant = $_POST['montant'] ?? null;
        if (!is_numeric($montant) || (float)$montant <= 0) {
            $this->set_flash("Le montant du dépôt doit être un nombre positif.", "danger");
            return false;
        }

        // La banque choisie doit être active et appartenir à la compagnie de l'utilisateur.
        $banque = $this->fetchOne(
            "SELECT id_banque FROM banques WHERE id_banque = :id AND id_compagnie = :ic AND statut = 'active'",
            [':id' => $_POST['id_banque'] ?? null, ':ic' => $id_compagnie]
        );
        if (!$banque) {
            $this->set_flash("Compte banque introuvable ou inactif.", "danger");
            return false;
        }

        $caisse = $this->fetchOne(
            "SELECT * FROM caisse WHERE id_agence = :a AND status_caisse = 1 LIMIT 1",
            [':a' => $id_agence]
        );
        if (!$caisse) {
            $this->set_flash("Aucune caisse ouverte pour votre gare.", "danger");
            return false;
        }

        if ((float)$montant > $this->soldeCaisse($caisse)) {
            $this->set_flash("Le montant demandé dépasse le solde actuel de votre caisse.", "danger");
            return false;
        }

        $this->insertion_update_simples(
            "INSERT INTO depots_banque
                (id_compagnie, id_agence, id_caisse, id_banque, montant, reference, id_utilisateur_demandeur)
             VALUES (:ic, :ia, :icaisse, :ib, :m, :ref, :u)",
            [
                ':ic'      => $id_compagnie,
                ':ia'      => $id_agence,
                ':icaisse' => $caisse['id_caisse'],
                ':ib'      => $banque['id_banque'],
                ':m'       => $montant,
                ':ref'     => trim($_POST['reference'] ?? '') ?: null,
                ':u'       => $_SESSION['id_utilisateur']
            ]
        );

        $this->set_flash("Demande de dépôt envoyée, en attente de confirmation.", "success");
        return true;
    }

    // Un compte banque précis, pour vérifier son appartenance à la compagnie avant
    // d'en afficher les mouvements (protection IDOR).
    public function getBanque($id_banque, $id_compagnie)
    {
        return $this->fetchOne(
            "SELECT * FROM banques WHERE id_banque = :id AND id_compagnie = :ic",
            [':id' => $id_banque, ':ic' => $id_compagnie]
        );
    }

    // Mouvements (entrées/sorties) d'un compte banque précis : pour l'instant uniquement
    // des entrées (dépôts confirmés), le retrait banque -> caisse n'étant pas encore
    // implémenté. La colonne "type" est déjà prévue pour distinguer les deux quand ce
    // sera le cas.
    public function getMouvementsBanque($id_banque, $id_compagnie)
    {
        return $this->customQuery(
            "SELECT d.*, a.localite, a.numeroGare, uD.utilisateurs AS demandeur, uV.utilisateurs AS validateur,
                    'entree' AS type
             FROM depots_banque d
             JOIN agence a ON d.id_agence = a.idAgence
             JOIN utilisateur uD ON d.id_utilisateur_demandeur = uD.idUser
             LEFT JOIN utilisateur uV ON d.id_utilisateur_validateur = uV.idUser
             JOIN banques b ON d.id_banque = b.id_banque
             WHERE d.id_banque = :ib AND d.id_compagnie = :ic AND d.statut = 'confirme'
             ORDER BY d.date_validation DESC",
            [':ib' => $id_banque, ':ic' => $id_compagnie]
        );
    }

    // Demandes en attente de toute la compagnie, à valider par un Admin/super_admin.
    public function getDemandesEnAttente($id_compagnie)
    {
        return $this->customQuery(
            "SELECT d.*, a.localite, a.numeroGare, b.nom AS nom_banque, u.utilisateurs AS demandeur
             FROM depots_banque d
             JOIN agence a ON d.id_agence = a.idAgence
             JOIN banques b ON d.id_banque = b.id_banque
             JOIN utilisateur u ON d.id_utilisateur_demandeur = u.idUser
             WHERE d.id_compagnie = :ic AND d.statut = 'en_attente'
             ORDER BY d.date_demande",
            [':ic' => $id_compagnie]
        );
    }

    // Historique des demandes (tous statuts), filtré selon le rôle : le chef d'escale ne
    // voit que les demandes de sa propre gare, l'Admin/super_admin voit toute la compagnie.
    public function getHistorique()
    {
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        $droit        = $_SESSION['droit'] ?? null;

        $condition = 'd.id_compagnie = :ic';
        $params    = [':ic' => $id_compagnie];

        if ($droit === 'chef_d_escale') {
            $condition .= ' AND d.id_agence = :ia';
            $params[':ia'] = $_SESSION['id_agence'] ?? null;
        }

        return $this->customQuery(
            "SELECT d.*, a.localite, a.numeroGare, b.nom AS nom_banque,
                    uD.utilisateurs AS demandeur, uV.utilisateurs AS validateur
             FROM depots_banque d
             JOIN agence a ON d.id_agence = a.idAgence
             JOIN banques b ON d.id_banque = b.id_banque
             JOIN utilisateur uD ON d.id_utilisateur_demandeur = uD.idUser
             LEFT JOIN utilisateur uV ON d.id_utilisateur_validateur = uV.idUser
             WHERE $condition
             ORDER BY d.date_demande DESC",
            $params
        );
    }

    // Confirme une demande : débite la caisse (montant_depense, comme toute sortie de
    // cash physique) et crédite le compte banque. Transactionnel avec verrous, pour éviter
    // qu'une confirmation concurrente ou une dépense entretemps ne rende la caisse négative.
    public function confirmerDemande($id_depot)
    {
        if (!csrf_verify()) {
            $this->set_flash("Session expirée, veuillez réessayer.", "danger");
            return false;
        }

        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'super_admin'], true)) {
            $this->set_flash("Accès refusé.", "danger");
            return false;
        }

        $id_compagnie = $_SESSION['id_compagnie'];
        $pdo = $this->connect();

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                "SELECT * FROM depots_banque WHERE id_depot = :id AND id_compagnie = :ic FOR UPDATE"
            );
            $stmt->execute([':id' => $id_depot, ':ic' => $id_compagnie]);
            $depot = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$depot) {
                $pdo->rollBack();
                $this->set_flash("Demande introuvable.", "danger");
                return false;
            }

            if ($depot['statut'] !== 'en_attente') {
                $pdo->rollBack();
                $this->set_flash("Cette demande a déjà été traitée.", "danger");
                return false;
            }

            $stmt = $pdo->prepare("SELECT * FROM caisse WHERE id_caisse = :id FOR UPDATE");
            $stmt->execute([':id' => $depot['id_caisse']]);
            $caisse = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$caisse || $this->soldeCaisse($caisse) < (float)$depot['montant']) {
                $pdo->rollBack();
                $this->set_flash("Solde de caisse insuffisant pour confirmer ce dépôt.", "danger");
                return false;
            }

            $pdo->prepare("UPDATE caisse SET montant_depense = montant_depense + :m WHERE id_caisse = :id")
                ->execute([':m' => $depot['montant'], ':id' => $caisse['id_caisse']]);

            $pdo->prepare("UPDATE banques SET solde = solde + :m WHERE id_banque = :id")
                ->execute([':m' => $depot['montant'], ':id' => $depot['id_banque']]);

            $pdo->prepare(
                "UPDATE depots_banque
                 SET statut = 'confirme', id_utilisateur_validateur = :u, date_validation = NOW()
                 WHERE id_depot = :id"
            )->execute([':u' => $_SESSION['id_utilisateur'], ':id' => $id_depot]);

            $pdo->commit();
            $this->set_flash("Dépôt confirmé : " . number_format($depot['montant'], 0, ',', ' ') . " FCFA transférés en banque.", "success");
            return true;
        } catch (Throwable $e) {
            $pdo->rollBack();
            $this->set_flash("Erreur lors de la confirmation : " . $e->getMessage(), "danger");
            return false;
        }
    }

    // Rejette une demande : aucun mouvement financier, juste une trace avec motif.
    public function rejeterDemande($id_depot)
    {
        if (!csrf_verify()) {
            $this->set_flash("Session expirée, veuillez réessayer.", "danger");
            return false;
        }

        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'super_admin'], true)) {
            $this->set_flash("Accès refusé.", "danger");
            return false;
        }

        $id_compagnie = $_SESSION['id_compagnie'];

        $depot = $this->fetchOne(
            "SELECT id_depot FROM depots_banque WHERE id_depot = :id AND id_compagnie = :ic AND statut = 'en_attente'",
            [':id' => $id_depot, ':ic' => $id_compagnie]
        );
        if (!$depot) {
            $this->set_flash("Demande introuvable ou déjà traitée.", "danger");
            return false;
        }

        $this->insertion_update_simples(
            "UPDATE depots_banque
             SET statut = 'rejete', motif_rejet = :motif, id_utilisateur_validateur = :u, date_validation = NOW()
             WHERE id_depot = :id",
            [
                ':motif' => trim($_POST['motif_rejet'] ?? '') ?: null,
                ':u'     => $_SESSION['id_utilisateur'],
                ':id'    => $id_depot
            ]
        );

        $this->set_flash("Demande rejetée.", "success");
        return true;
    }
}
