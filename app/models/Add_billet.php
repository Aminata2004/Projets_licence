 <?php
    class Add_billet extends Model
    {

        // public function getDestinationsWithHeuresAndEscales()
        // {
        //     $idDepart    = $_SESSION['ville'];
        //     $idCompagnie = $_SESSION['id_compagnie'];

        //     $sql = "SELECT DISTINCT p.idProgrammer,
        //                 p.idDestination AS destination_nom,
        //                 p.heureDepart,
        //                 p.prix
        //         FROM programmer p
        //         WHERE p.idDepart = :idDepart
        //           AND p.id_compagnie = :idCompagnie
        //         ORDER BY p.idDestination, p.heureDepart";

        //     $rows = $this->fetchAll($sql, [
        //         ':idDepart' => $idDepart,
        //         ':idCompagnie' => $idCompagnie
        //     ]);

        //     $result = [];

        //     foreach ($rows as $row) {
        //         $destNom = $row['destination_nom'];
        //         $progId  = $row['idProgrammer'];

        //         if (!isset($result[$destNom])) {
        //             $result[$destNom] = [
        //                 'nom' => $destNom,
        //                 'programmes' => []
        //             ];
        //         }

        //         $escales = $this->fetchAll(
        //             "SELECT e.id_escale, prix_escale, e.escales AS escale_nom
        //          FROM ligneTrajet lt
        //          JOIN escale e ON e.id_escale = lt.id_escales
        //          WHERE lt.id_trajets = :progId",
        //             [':progId' => $progId]
        //         );

        //         $result[$destNom]['programmes'][] = [
        //             'idProgrammer' => $progId,
        //             'heureDepart' => $row["heureDepart"],
        //             'prix'        => $row['prix'],
        //             'escales'     => $escales
        //         ];
        //     }

        //     return array_values($result);
        // }


        public function getDestinationsWithHeuresAndEscales()
        {
            $idDepart    = $_SESSION['ville'] ?? null;
            $idCompagnie = $_SESSION['id_compagnie'] ?? null;

            if (!$idDepart || !$idCompagnie) return [];

            // Récupère les programmes avec les noms de localité
            $sql = "SELECT 
                p.idProgrammer,
                p.heureDepart,
                p.prix,
                a1.localite AS departLocalite,
                 a1.numeroGare AS departnumeroGare,
                a2.localite AS destinationLocalite
            FROM programmer p
            LEFT JOIN agence a1 ON p.idDepart = a1.idAgence
            LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
            WHERE a1.localite = :departLocalite
              AND p.id_compagnie = :idCompagnie
           ";

            $rows = $this->fetchAll($sql, [
                ':departLocalite' => $idDepart,
                ':idCompagnie' => $idCompagnie
            ]);

            $result = [];

            foreach ($rows as $row) {
                $destNom = $row['destinationLocalite'] ?? $row['idDestination'];
                $progId  = $row['idProgrammer'];
                $departnumeroGare = $row['departnumeroGare'] ?? null;

                if (!isset($result[$destNom])) {
                    $result[$destNom] = [
                        'nom' => $destNom,
                        'departLocalite' => $row['departLocalite'],
                        'departnumeroGare' => $departnumeroGare,
                        'programmes' => []
                    ];
                }

                $escales = $this->fetchAll(
                    "SELECT e.id_escale, prix_escale, e.escales AS escale_nom
                 FROM ligneTrajet lt
                 JOIN escale e ON e.id_escale = lt.id_escales
                 WHERE lt.id_trajets = :progId AND lt.type_trajet = 'programmer'",
                    [':progId' => $progId]
                );

                $result[$destNom]['programmes'][] = [
                    'idProgrammer' => $progId,
                    'heureDepart'  => $row["heureDepart"],
                    'prix'         => $row['prix'],
                    'depart'       => $row['departLocalite'],
                    'destination'  => $row['destinationLocalite'],
                    'escales'      => $escales
                ];
            }

            // Retourne un tableau indexé pour le foreach
            return array_values($result);
        }


        // public function saveBillets(): bool
        // {
        //     extract($_POST);
        //     $pdo = $this->connect();

        //     $jourVoyage = date('Y-m-d', strtotime($jourVoyage));
        //     $aujourdhui = date('Y-m-d');
        //     $demain     = date('Y-m-d', strtotime('+1 day'));

        //     if (!in_array($jourVoyage, [$aujourdhui, $demain])) {
        //         $this->set_flash("Date invalide : choisissez aujourd’hui ou demain.", "danger");
        //         return false;
        //     }

        //     if (empty($Client) || empty($destinationId) || empty($programme) || empty($nombrePassages)) {
        //         $this->set_flash("Tous les champs obligatoires doivent être remplis.", "danger");
        //         return false;
        //     }

        //     $car             = null;
        //     $idCarProgrammer = null;
        //     $numPlace        = '-'; // par défaut
        //     $escaleNom = $_POST['escale'] ?? '';    // récupère le hidden
        //     $destinationId = $_POST['destinationId'] ?? '';

        //     $destFinale = !empty($escaleNom) ? $escaleNom : $destinationId;

        //     $prixUtilise     = $_POST['montant_payer'] ?? '';

        //     if ($jourVoyage == $aujourdhui) {
        //         $rowProg = $this->fetchOne(
        //             "SELECT id_car_programmer FROM programmation_voyage
        //      WHERE id_horaire = :h AND date_enregistre = :d AND id_trajet = :t
        //      AND localite_user = :l AND id_compagnie = :c LIMIT 1",
        //             [
        //                 ':h' => $programme,
        //                 ':d' => $jourVoyage,
        //                 ':t' => $destinationId,
        //                 ':l' => $_SESSION['ville'],
        //                 ':c' => $_SESSION['id_compagnie']
        //             ]
        //         );

        //         if (!$rowProg) {
        //             $this->set_flash("Aucun car programmé pour cette heure et ce trajet.", "danger");
        //             return false;
        //         }

        //         $idCarProgrammer = $rowProg['id_car_programmer'];

        //         $car = $this->fetchOne(
        //             "SELECT nbr_place, nbr_place_reserve FROM car WHERE numero_car = :num LIMIT 1",
        //             [':num' => $idCarProgrammer]
        //         );

        //         if (!$car) {
        //             $this->set_flash("Car introuvable.", "danger");
        //             return false;
        //         }

        //         $placesDispo = $car['nbr_place'] - $car['nbr_place_reserve'];
        //         if ($nombrePassages > $placesDispo) {
        //             $this->set_flash("Places insuffisantes : $placesDispo restantes.", "danger");
        //             return false;
        //         }

        //         $start    = (int)$car['nbr_place_reserve'] + 1;
        //         $end      = $start + (int)$nombrePassages - 1;
        //         $numPlace = ($nombrePassages == 1) ? "$start" : "$start-$end";
        //     }

        //     try {
        //         $pdo->beginTransaction();

        //         // Insertion client
        //         $stmt = $pdo->prepare("INSERT INTO client (Client, montant_payer, date_enregistrement, id_compagnie)
        //     VALUES (:c, :m, :d, :ic )");
        //         $stmt->execute([
        //             ':c'  => $Client,
        //             ':m'  => $prixUtilise,
        //             ':d'  => date('Ymd'),
        //             ':ic' => $_SESSION['id_compagnie']

        //         ]);
        //         $idClient = $pdo->lastInsertId();

        //         if ($jourVoyage == $demain) {
        //             // Récupérer la place minimale dynamique
        //             $stmt = $pdo->prepare("SELECT place_minumale FROM place_minumale LIMIT 1");
        //             $stmt->execute();
        //             $rowPlace = $stmt->fetch();
        //             $placeTotale = $rowPlace ? (int)$rowPlace['place_minumale'] : 0;

        //             if ($placeTotale <= 0) {
        //                 $pdo->rollBack();
        //                 $this->set_flash("Erreur : nombre de places minimales non défini.", "danger");
        //                 return false;
        //             }

        //             // Vérifier les places disponibles pour demain
        //             $stmt = $pdo->prepare(
        //                 "SELECT idSuivis, place_totals, place_reserve FROM suivis
        //          WHERE depart = :dep AND destination = :dest AND heur_depart = :h
        //          AND date_reservation = :jr AND id_compagnie = :id_compagnie LIMIT 1"
        //             );
        //             $stmt->execute([
        //                 ':dep'  => $_SESSION['ville'],
        //                 ':dest' => $destFinale,
        //                 ':h'    => $programme,
        //                 ':jr'   => $jourVoyage,
        //                 ':id_compagnie' => $_SESSION['id_compagnie']
        //             ]);
        //             $suivi = $stmt->fetch();

        //             if ($suivi) {
        //                 $placesDispo = $suivi['place_totals'] - $suivi['place_reserve'];
        //                 if ($nombrePassages > $placesDispo) {
        //                     $pdo->rollBack();
        //                     $this->set_flash("Places insuffisantes pour demain : $placesDispo restantes.", "danger");
        //                     return false;
        //                 }
        //                 $stmt = $pdo->prepare("UPDATE suivis SET place_reserve = place_reserve + :n WHERE idSuivis = :id");
        //                 $stmt->execute([
        //                     ':n'  => (int)$nombrePassages,
        //                     ':id' => $suivi['idSuivis']
        //                 ]);
        //             } else {
        //                 if ($nombrePassages > $placeTotale) {
        //                     $pdo->rollBack();
        //                     $this->set_flash("Places insuffisantes pour demain : $placeTotale restantes.", "danger");
        //                     return false;
        //                 }
        //                 $stmt = $pdo->prepare(
        //                     "INSERT INTO suivis (place_reserve, place_totals, depart, destination, heur_depart, date_reservation, id_compagnie)
        //              VALUES (:n, :total, :dep, :dest, :h, :jr, :id_compagnie)"
        //                 );
        //                 $stmt->execute([
        //                     ':n'     => (int)$nombrePassages,
        //                     ':total' => $placeTotale,
        //                     ':dep'   => $_SESSION['ville'],
        //                     ':dest'  => $destFinale,
        //                     ':h'     => $programme,
        //                     ':jr'    => $jourVoyage,
        //                     ':id_compagnie' => $_SESSION['id_compagnie']
        //                 ]);
        //                 $suivi['idSuivis'] = $pdo->lastInsertId();
        //             }

        //             // Calcul des numéros de places
        //             $stmt = $pdo->prepare("SELECT numeroPlace FROM billets
        //         WHERE jourVoyage = :j AND Heur_departs = :h AND departId = :dep AND destinationId = :dest AND id_compagnie = :id_compagnie");
        //             $stmt->execute([
        //                 ':j'   => $jourVoyage,
        //                 ':h'   => $programme,
        //                 ':dep' => $_SESSION['id_agence'],
        //                 ':dest' => $destFinale,
        //                 ':id_compagnie' => $_SESSION['id_compagnie']
        //             ]);

        //             $placesPrises = [];
        //             while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //                 foreach (explode('-', $row['numeroPlace']) as $p) {
        //                     $placesPrises[] = (int)$p;
        //                 }
        //             }

        //             $start = 1;
        //             $numPlacesAttribues = [];
        //             while (count($numPlacesAttribues) < $nombrePassages) {
        //                 if (!in_array($start, $placesPrises)) {
        //                     $numPlacesAttribues[] = $start;
        //                 }
        //                 $start++;
        //             }

        //             $numPlace = implode('-', $numPlacesAttribues);
        //         }

        //         // Insertion du billet
        //         $stmt = $pdo->prepare("INSERT INTO billets (id_client,idUser, numeroBillets, jourVoyage, Heur_departs,
        //                       nombrePassages, destinationId, departId, date_expiration, numeroPlace,
        //                       date_reservation, status_reservation, validation_billets ,id_compagnie)
        //     VALUES (:cl, :idUser, :num, :j, :h, :n, :dest, :dep, :exp, :place, :res, :stat, :validation_billets, :id_compagnie)");
        //         $stmt->execute([
        //             ':cl'    => $idClient,
        //             ':idUser'  => $_SESSION['id_utilisateur'],
        //             ':num'   => $numeroBillets,
        //             ':j'     => $jourVoyage,
        //             ':h'     => $programme,
        //             ':n'     => $nombrePassages,
        //             ':dest'  => $destFinale,
        //             ':dep'   => $_SESSION['ville'],
        //             ':exp'   => date('Y-m-d', strtotime($jourVoyage . ' +1 week')),
        //             ':place' => $numPlace,
        //             ':res'   => date('Ymd'),
        //             ':stat'  => 'presentiel',
        //             ':validation_billets' => 'valider',
        //             ':id_compagnie' => $_SESSION['id_compagnie']
        //         ]);

        //         // Màj car pour aujourd'hui
        //         if ($jourVoyage == $aujourdhui) {
        //             $stmt = $pdo->prepare("UPDATE car SET nbr_place_reserve = nbr_place_reserve + :n WHERE numero_car = :num");
        //             $stmt->execute([
        //                 ':n'   => (int)$nombrePassages,
        //                 ':num' => $idCarProgrammer
        //             ]);

        //             if ($stmt->rowCount() === 0) {
        //                 $pdo->rollBack();
        //                 $this->set_flash("Échec mise à jour du car.", "danger");
        //                 return false;
        //             }
        //         }

        //         $pdo->commit();
        //         $this->set_flash("Réservation enregistrée avec succès.", "info");
        //         header("Location: " . $_SERVER['REQUEST_URI']);
        //         exit;
        //     } catch (Throwable $e) {
        //         $pdo->rollBack();
        //         $this->set_flash("Erreur SQL : " . $e->getMessage(), "danger");
        //         return false;
        //     }
        // }
        public function saveBillets(): bool
        {
            extract($_POST);
            $pdo = $this->connect();

            $jourVoyage = date('Y-m-d', strtotime($jourVoyage));
            $aujourdhui = date('Y-m-d');
            $demain     = date('Y-m-d', strtotime('+1 day'));

            // Vérification date
            if (!in_array($jourVoyage, [$aujourdhui, $demain])) {
                $this->set_flash("Date invalide : choisissez aujourd’hui ou demain.", "danger");
                return false;
            }

            // Champs obligatoires
            if (empty($Client) || empty($destinationId) || empty($programme) || empty($nombrePassages)) {
                $this->set_flash("Tous les champs obligatoires doivent être remplis.", "danger");
                return false;
            }

            $car             = null;
            $idCarProgrammer = null;
            $numPlace        = '-';
            $escaleNom       = $_POST['escale'] ?? '';
            $destinationId   = $_POST['destinationId'] ?? '';
            $destFinale      = !empty($escaleNom) ? $escaleNom : $destinationId;
            $prixUtilise = $_POST['montant_payer'] ?? '';
            // Supprimer les espaces, espaces insécables et "FCFA"
            $prixUtilise = str_replace([' ', ' ', 'FCFA'], '', $prixUtilise);
            $prixUtilise = floatval($prixUtilise); // Convertir en nombre


            try {
                $pdo->beginTransaction();

                // Insertion client
                $stmt = $pdo->prepare("INSERT INTO client (Client, montant_payer, date_enregistrement, id_compagnie)
            VALUES (:c, :m, :d, :ic)");
                $stmt->execute([
                    ':c'  => $Client,
                    ':m'  => $prixUtilise,
                    ':d'  => date('Ymd'),
                    ':ic' => $_SESSION['id_compagnie']
                ]);
                $idClient = $pdo->lastInsertId();

                // Gestion des places
                if ($jourVoyage == $aujourdhui) {
                    // Aujourd'hui → réservation sur car
                    $rowProg = $this->fetchOne(
                        "SELECT id_car_programmer FROM programmation_voyage
                 WHERE id_horaire = :h AND date_enregistre = :d AND id_trajet = :t
                 AND localite_user = :l AND id_compagnie = :c LIMIT 1",
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
                        "SELECT nbr_place, nbr_place_reserve, id_car FROM car WHERE id_car = :num LIMIT 1",
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
                } else {
                    // Demain → gestion via suivi et place minimale
                    $stmt = $pdo->prepare("SELECT place_minumale FROM place_minumale LIMIT 1");
                    $stmt->execute();
                    $rowPlace = $stmt->fetch();
                    $placeTotale = $rowPlace ? (int)$rowPlace['place_minumale'] : 0;

                    if ($placeTotale <= 0) {
                        $pdo->rollBack();
                        $this->set_flash("Erreur : nombre de places minimales non défini.", "danger");
                        return false;
                    }

                    $stmt = $pdo->prepare(
                        "SELECT idSuivis, place_totals, place_reserve FROM suivis
                 WHERE depart = :dep AND destination = :dest AND heur_depart = :h
                 AND date_reservation = :jr AND id_compagnie = :id_compagnie LIMIT 1"
                    );
                    $stmt->execute([
                        ':dep'          => $_SESSION['ville'],
                        ':dest'         => $destFinale,
                        ':h'            => $programme,
                        ':jr'           => $jourVoyage,
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
                        $stmt = $pdo->prepare("UPDATE suivis SET place_reserve = place_reserve + :n WHERE idSuivis = :id");
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
                            "INSERT INTO suivis (place_reserve, place_totals, depart, destination, heur_depart, date_reservation, id_compagnie)
                     VALUES (:n, :total, :dep, :dest, :h, :jr, :id_compagnie)"
                        );
                        $stmt->execute([
                            ':n'     => (int)$nombrePassages,
                            ':total' => $placeTotale,
                            ':dep'   => $_SESSION['ville'],
                            ':dest'  => $destFinale,
                            ':h'     => $programme,
                            ':jr'    => $jourVoyage,
                            ':id_compagnie' => $_SESSION['id_compagnie']
                        ]);
                        $suivi['idSuivis'] = $pdo->lastInsertId();
                    }

                    // Numéros de places pour demain
                    $stmt = $pdo->prepare("SELECT numeroPlace FROM billets
                WHERE jourVoyage = :j AND Heur_departs = :h AND departId = :dep AND destinationId = :dest AND id_compagnie = :id_compagnie");
                    $stmt->execute([
                        ':j'            => $jourVoyage,
                        ':h'            => $programme,
                        ':dep'          => $_SESSION['ville'],
                        ':dest'         => $destFinale,
                        ':id_compagnie' => $_SESSION['id_compagnie']
                    ]);

                    $placesPrises = [];
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        foreach (explode('-', $row['numeroPlace']) as $p) {
                            $placesPrises[] = (int)$p;
                        }
                    }

                    $start = 1;
                    $numPlacesAttribues = [];
                    while (count($numPlacesAttribues) < $nombrePassages) {
                        if (!in_array($start, $placesPrises)) {
                            $numPlacesAttribues[] = $start;
                        }
                        $start++;
                    }

                    $numPlace = implode('-', $numPlacesAttribues);
                }

                // Insertion du billet
                $stmt = $pdo->prepare("INSERT INTO billets (id_client,idUser, numeroBillets, jourVoyage, Heur_departs,
                              nombrePassages, destinationId, departId, date_expiration, numeroPlace,
                              date_reservation, status_reservation, validation_billets ,id_compagnie,num_gare)
            VALUES (:cl, :idUser, :num, :j, :h, :n, :dest, :dep, :exp, :place, :res, :stat, :validation_billets, :id_compagnie,:num_gare)");
                $stmt->execute([
                    ':cl'    => $idClient,
                    ':idUser' => $_SESSION['id_utilisateur'],
                    ':num'   => $numeroBillets,
                    ':j'     => $jourVoyage,
                    ':h'     => $programme,
                    ':n'     => $nombrePassages,
                    ':dest'  => $destFinale,
                    ':dep'   => $_SESSION['ville'],
                    ':exp'   => date('Y-m-d', strtotime($jourVoyage . ' +1 week')),
                    ':place' => $numPlace,
                    ':res'   => date('Ymd'),
                    ':stat'  => 'presentiel',
                    ':validation_billets' => 'valider',
                    ':id_compagnie' => $_SESSION['id_compagnie'],
                    ':num_gare' => $_SESSION['numero_gare']
                ]);

                // Màj car pour aujourd'hui
                if ($jourVoyage == $aujourdhui) {
                    $stmt = $pdo->prepare("UPDATE car SET nbr_place_reserve = nbr_place_reserve + :n WHERE id_car = :num");
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

                // === Alimentation de la caisse (si une caisse active est ouverte) ===
                $stmt = $pdo->prepare("
                    SELECT c.id_caisse, c.montant_billets
                    FROM caisse c
                    INNER JOIN agence a ON c.id_agence = a.idAgence
                    WHERE c.id_compagnie = :id_compagnie
                      AND a.localite     = :ville
                      AND a.numeroGare   = :numeroGare
                      AND c.status_caisse = 1
                    LIMIT 1
                ");
                $stmt->execute([
                    ':id_compagnie' => $_SESSION['id_compagnie'],
                    ':ville'        => $_SESSION['ville'],
                    ':numeroGare'   => $_SESSION['numero_gare']
                ]);
                $caisse = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($caisse) {
                    // Caisse ouverte → alimenter
                    $stmtUpdate = $pdo->prepare("
                        UPDATE caisse
                        SET montant_billets = montant_billets + :montant
                        WHERE id_caisse = :id_caisse
                    ");
                    $stmtUpdate->execute([
                        ':montant'   => $prixUtilise,
                        ':id_caisse' => $caisse['id_caisse']
                    ]);
                } else {
                    $pdo->rollBack();
                    $this->set_flash("Opération bloquée : Aucune caisse ouverte pour cette gare. Veuillez ouvrir une caisse d'abord.", "danger");
                    return false;
                }

                $pdo->commit();
                $this->set_flash("Réservation enregistrée avec succès et caisse alimentée.", "info");
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            } catch (Throwable $e) {
                $pdo->rollBack();
                $this->set_flash("Erreur SQL : " . $e->getMessage(), "danger");
                return false;
            }
        }
    }
