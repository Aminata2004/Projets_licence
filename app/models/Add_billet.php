 <?php
    class Add_billet extends Model
    {

        public function getDestinationsWithHeuresAndEscales()
        {
            $idDepart    = $_SESSION['ville'];
            $idCompagnie = $_SESSION['id_compagnie'];

            $sql = "SELECT DISTINCT p.idProgrammer,
                        p.idDestination AS destination_nom,
                        p.heureDepart,
                        p.prix
                FROM programmer p
                WHERE p.idDepart = :idDepart
                  AND p.id_compagnie = :idCompagnie
                ORDER BY p.idDestination, p.heureDepart";

            $rows = $this->fetchAll($sql, [
                ':idDepart' => $idDepart,
                ':idCompagnie' => $idCompagnie
            ]);

            $result = [];

            foreach ($rows as $row) {
                $destNom = $row['destination_nom'];
                $progId  = $row['idProgrammer'];

                if (!isset($result[$destNom])) {
                    $result[$destNom] = [
                        'nom' => $destNom,
                        'programmes' => []
                    ];
                }

                $escales = $this->fetchAll(
                    "SELECT e.id_escale, e.escales AS escale_nom
                 FROM ligneTrajet lt
                 JOIN escale e ON e.id_escale = lt.id_escales
                 WHERE lt.id_trajets = :progId",
                    [':progId' => $progId]
                );

                $result[$destNom]['programmes'][] = [
                    'idProgrammer' => $progId,
                    'heureDepart' => $row["heureDepart"],
                    'prix'        => $row['prix'],
                    'escales'     => $escales
                ];
            }

            return array_values($result);
        }

        public function saveBillets(): bool
        {
            extract($_POST);
            $pdo = $this->connect();

            // ──────────────
            // 1. FORMAT & VALIDATION DES DATES
            // ──────────────
            $jourVoyage = date('Y-m-d', strtotime($jourVoyage)); // on force le bon format

            $aujourdhui = date('Y-m-d');
            $demain     = date('Y-m-d', strtotime('+1 day'));

            if (!in_array($jourVoyage, [$aujourdhui, $demain])) {
                $this->set_flash("Date invalide : choisissez aujourd’hui ou demain.", "danger");
                return false;
            }

            if (empty($Client) || empty($destinationId) || empty($programme) || empty($nombrePassages)) {
                $this->set_flash("Tous les champs obligatoires doivent être remplis.", "danger");
                return false;
            }

            // ──────────────
            // 2. VALEURS COMMUNES
            // ──────────────
            $car             = null;
            $idCarProgrammer = null;
            $numPlace        = '-'; // Par défaut pour demain
            $destFinale      = !empty($escaleNom) ? $escaleNom : $destinationId;
            $prixUtilise     = !empty($escaleNom)
                ? (!empty($montant_payers) ? $montant_payers : 0)
                : $montant_payer;

            // ──────────────
            // 3. SI VOYAGE AUJOURD’HUI → vérifier car programmé
            // ──────────────
            if ($jourVoyage == $aujourdhui) {
                $rowProg = $this->fetchOne(
                    "SELECT id_car_programmer
             FROM programmation_voyage
             WHERE id_horaire = :h
               AND date_enregistre = :d
               AND id_trajet = :t
               AND localite_user = :l
               AND id_compagnie = :c
             LIMIT 1",
                    [
                        ':h' => $programme,
                        ':d' => $jourVoyage,
                        ':t' => $destinationId,
                        ':l' => $_SESSION['ville'],
                        ':c' => $_SESSION['id_compagnie']
                    ]
                );

                if (!$rowProg) {
                    $this->set_flash("Aucun car programmé pour cette heure et ce trajet.", "danger");
                    return false;
                }

                $idCarProgrammer = $rowProg['id_car_programmer'];

                $car = $this->fetchOne(
                    "SELECT nbr_place, nbr_place_reserve
             FROM car
             WHERE numero_car = :num
             LIMIT 1",
                    [':num' => $idCarProgrammer]
                );

                if (!$car) {
                    $this->set_flash("Car introuvable.", "danger");
                    return false;
                }

                $placesDispo = $car['nbr_place'] - $car['nbr_place_reserve'];
                if ($nombrePassages > $placesDispo) {
                    $this->set_flash("Places insuffisantes : $placesDispo restantes.", "danger");
                    return false;
                }

                $start    = (int)$car['nbr_place_reserve'] + 1;
                $end      = $start + (int)$nombrePassages - 1;
                $numPlace = ($nombrePassages == 1) ? "$start" : "$start-$end";
            }

            // ──────────────
            // 4. DÉBUT TRANSACTION
            // ──────────────
            try {
                $pdo->beginTransaction();

                // a. Client
                $stmt = $pdo->prepare(
                    "INSERT INTO client (Client, montant_payer, date_enregistrement, id_compagnie, idUser)
             VALUES (:c, :m, :d, :ic, :u)"
                );
                $stmt->execute([
                    ':c'  => $Client,
                    ':m'  => $prixUtilise,
                    ':d'  => date('Ymd'),
                    ':ic' => $_SESSION['id_compagnie'],
                    ':u'  => $_SESSION['id_utilisateur']
                ]);
                $idClient = $pdo->lastInsertId();

                // b. Billet
                $stmt = $pdo->prepare(
                    "INSERT INTO billets (id_client, numeroBillets, jourVoyage, Heur_departs,
                                  nombrePassages, destinationId, departId,
                                  date_expiration, numeroPlace, date_reservation,
                                  status_reservation,id_compagnie)
             VALUES (:cl, :num, :j, :h, :n, :dest, :dep,
                     :exp, :place, :res, :stat,:id_compagnie)"
                );
                $stmt->execute([
                    ':cl'    => $idClient,
                    ':num'   => $numeroBillets,
                    ':j'     => $jourVoyage,
                    ':h'     => $programme,
                    ':n'     => $nombrePassages,
                    ':dest'  => $destFinale,
                    ':dep'   => $_SESSION['id_agence'],
                    ':exp'   => date('Y-m-d', strtotime($jourVoyage . ' +1 week')),
                    ':place' => $numPlace,
                    ':res'   => date('Ymd'),
                    ':stat'  => 'presentiel',
                    ':id_compagnie' => $_SESSION['id_compagnie']
                ]);

                // ──────────────
                // 5. MISE À JOUR SELON LA DATE
                // ──────────────

                if ($jourVoyage == $aujourdhui) {
                    // Màj du car
                    $stmt = $pdo->prepare(
                        "UPDATE car
                 SET nbr_place_reserve = nbr_place_reserve + :n
                 WHERE numero_car = :num"
                    );
                    $stmt->execute([
                        ':n'   => (int)$nombrePassages,
                        ':num' => $idCarProgrammer
                    ]);

                    if ($stmt->rowCount() === 0) {
                        $pdo->rollBack();
                        $this->set_flash("Échec mise à jour du car.", "danger");
                        return false;
                    }
                }
                // ...existing code...
                elseif ($jourVoyage == $demain) {
                    // Récupérer la place minimale dynamique
                    $stmt = $pdo->prepare("SELECT place_minumale FROM place_minumale LIMIT 1");
                    $stmt->execute();
                    $rowPlace = $stmt->fetch();
                    $placeTotale = $rowPlace ? (int)$rowPlace['place_minumale'] : 0;

                    if ($placeTotale <= 0) {
                        $pdo->rollBack();
                        $this->set_flash("Erreur : nombre de places minimales non défini.", "danger");
                        return false;
                    }

                    // Vérifier les places disponibles pour demain
                    $stmt = $pdo->prepare(
                        "SELECT idSuivis, place_totals, place_reserve
                        FROM suivis
                        WHERE depart = :dep
                        AND destination = :dest
                        AND heur_depart = :h
                        AND date_reservation = :jr
                        AND id_compagnie = :id_compagnie
                        LIMIT 1"
                    );
                    $stmt->execute([
                        ':dep'  => $_SESSION['id_agence'],
                        ':dest' => $destFinale,
                        ':h'    => $programme,
                        ':jr'   => $jourVoyage,
                        ':id_compagnie' => $_SESSION['id_compagnie']
                    ]);
                    $suivi = $stmt->fetch();

                    if ($suivi) {
                        $placesDispo = $suivi['place_totals'] - $suivi['place_reserve'];
                        if ($nombrePassages > $placesDispo) {
                            $pdo->rollBack();
                            $this->set_flash("Places insuffisantes pour demain : $placesDispo restantes.", "danger");
                            return false;
                        }
                        $stmt = $pdo->prepare(
                            "UPDATE suivis
             SET place_reserve = place_reserve + :n
             WHERE idSuivis = :id"
                        );
                        $stmt->execute([
                            ':n'  => (int)$nombrePassages,
                            ':id' => $suivi['idSuivis']
                        ]);
                    } else {
                        if ($nombrePassages > $placeTotale) {
                            $pdo->rollBack();
                            $this->set_flash("Places insuffisantes pour demain : $placeTotale restantes.", "danger");
                            return false;
                        }
                        $stmt = $pdo->prepare(
                            "INSERT INTO suivis (place_reserve, place_totals, depart,
                                 destination, heur_depart, date_reservation,id_compagnie)
             VALUES (:n, :total, :dep, :dest, :h, :jr, :id_compagnie)"
                        );
                        $stmt->execute([
                            ':n'     => (int)$nombrePassages,
                            ':total' => $placeTotale,
                            ':dep'   => $_SESSION['id_agence'],
                            ':dest'  => $destFinale,
                            ':h'     => $programme,
                            ':jr'    => $jourVoyage,
                            ':id_compagnie' => $_SESSION['id_compagnie']
                        ]);
                    }
                    // ...existing code...
                }

                // Commit final
                $pdo->commit();
                $this->set_flash("Réservation enregistrée avec succès.", "info");
                return true;
            } catch (Throwable $e) {
                $pdo->rollBack();
                $this->set_flash("Erreur SQL : " . $e->getMessage(), "danger");
                return false;
            }
        }
    }
