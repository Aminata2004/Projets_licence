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


        // Liste des gares (agences) de la compagnie, utilisée pour le select "Départ"
        // affiché uniquement à l'Admin (les autres rôles ont une gare fixe en session).
        public function getAgencesByCompagnie()
        {
            $idCompagnie = $_SESSION['id_compagnie'] ?? null;
            if (!$idCompagnie) return [];

            return $this->fetchAll(
                "SELECT idAgence, localite, numeroGare FROM agence WHERE id_compagnie = :ic ORDER BY localite, numeroGare",
                [':ic' => $idCompagnie]
            );
        }

        // Détermine la gare de départ effective pour la vente en cours.
        // - Admin : gare choisie dans le formulaire (idDepart posté/GET), revalidée contre
        //   sa propre compagnie pour empêcher toute sélection d'une gare d'une autre compagnie.
        // - Autres rôles (chef_d_escale, Utilisateur) : toujours leur gare de session, jamais
        //   influencée par une valeur postée par le client (empêche de créditer la caisse d'une
        //   autre gare que la sienne).
        private function resolveDepart(): array
        {
            if (($_SESSION['droit'] ?? null) === 'Admin') {
                $idAgencePoste = $_POST['idDepart'] ?? $_GET['idDepart'] ?? null;
                if (!$idAgencePoste) {
                    return [null, null, null];
                }
                $agence = $this->fetchOne(
                    "SELECT idAgence, localite, numeroGare FROM agence WHERE idAgence = :id AND id_compagnie = :ic LIMIT 1",
                    [':id' => $idAgencePoste, ':ic' => $_SESSION['id_compagnie'] ?? null]
                );
                return $agence
                    ? [$agence['idAgence'], $agence['localite'], $agence['numeroGare']]
                    : [null, null, null];
            }

            return [$_SESSION['id_agence'] ?? null, $_SESSION['ville'] ?? null, $_SESSION['numero_gare'] ?? null];
        }

        public function getDestinationsWithHeuresAndEscales()
        {
            [$idAgenceDepart, , ] = $this->resolveDepart();
            $idCompagnie = $_SESSION['id_compagnie'] ?? null;

            if (!$idAgenceDepart || !$idCompagnie) return [];

            // Filtre sur l'ID de la gare (et non son nom de ville) : plusieurs gares d'une
            // même compagnie peuvent partager la même localité (ex. "Segou" Gare I et Gare II),
            // un filtre par nom mélangerait alors les trajets des deux gares.
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
            WHERE p.idDepart = :idAgenceDepart
              AND p.id_compagnie = :idCompagnie
           ";

            $rows = $this->fetchAll($sql, [
                ':idAgenceDepart' => $idAgenceDepart,
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


        // Recalcule le prix de référence côté serveur (ne jamais faire confiance au prix posté par le client)
        private function getPrixReference($heureDepart, $destinationLocalite, $escaleNom)
        {
            [$idAgenceDepart, , ] = $this->resolveDepart();
            $idCompagnie = $_SESSION['id_compagnie'] ?? null;

            $row = $this->fetchOne(
                "SELECT p.idProgrammer, p.prix
                 FROM programmer p
                 LEFT JOIN agence a1 ON p.idDepart = a1.idAgence
                 LEFT JOIN agence a2 ON p.idDestination = a2.idAgence
                 WHERE p.idDepart = :idAgenceDepart
                   AND a2.localite = :destLocalite
                   AND p.heureDepart = :heure
                   AND p.id_compagnie = :idCompagnie
                 LIMIT 1",
                [
                    ':idAgenceDepart' => $idAgenceDepart,
                    ':destLocalite'   => $destinationLocalite,
                    ':heure'          => $heureDepart,
                    ':idCompagnie'    => $idCompagnie
                ]
            );

            if (!$row) {
                return null;
            }

            $prixUnitaire = (float)$row['prix'];

            if (!empty($escaleNom)) {
                $escaleRow = $this->fetchOne(
                    "SELECT prix_escale FROM ligneTrajet lt
                     JOIN escale e ON e.id_escale = lt.id_escales
                     WHERE lt.id_trajets = :progId AND lt.type_trajet = 'programmer' AND e.escales = :escaleNom
                     LIMIT 1",
                    [':progId' => $row['idProgrammer'], ':escaleNom' => $escaleNom]
                );
                if ($escaleRow) {
                    $prixUnitaire = (float)$escaleRow['prix_escale'];
                }
            }

            return $prixUnitaire;
        }

        // Génère un numeroBillets et vérifie son unicité en base avant de le retenir : le
        // numéro affiché dans le formulaire (genererNumeroBillet() côté vue) n'est qu'un
        // aperçu — on ne lui fait jamais confiance côté serveur, car il est modifiable
        // côté client et deux ventes dans la même seconde peuvent produire la même valeur
        // (timestamp + rand(100,999), 1 chance sur ~900 de collision).
        private function genererNumeroBilletUnique(): string
        {
            for ($i = 0; $i < 5; $i++) {
                $numero = 'SMT' . date('Hismd') . random_int(100, 999);
                $existe = $this->fetchOne(
                    "SELECT 1 FROM billets WHERE numeroBillets = :num LIMIT 1",
                    [':num' => $numero]
                );
                if (!$existe) {
                    return $numero;
                }
            }
            // Filet de sécurité si les 5 tentatives collisionnent toutes (extrêmement improbable) :
            // uniqid garantit l'unicité par construction.
            return 'SMT' . uniqid();
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
            if (!csrf_verify()) {
                $this->set_flash("Session expirée, veuillez réessayer.", "danger");
                return false;
            }

            extract($_POST);
            $pdo = $this->connect();

            [$idAgenceDepart, $departLocalite, $numeroGareDepart] = $this->resolveDepart();
            if (!$departLocalite) {
                $this->set_flash("Veuillez choisir la gare de départ.", "danger");
                return false;
            }

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

            $nombrePassages = (int)$nombrePassages;
            if ($nombrePassages <= 0) {
                $this->set_flash("Le nombre de places doit être supérieur à zéro.", "danger");
                return false;
            }

            $car             = null;
            $idCarProgrammer = null;
            $numPlace        = '-';
            $escaleNom       = $_POST['escale'] ?? '';
            $destinationId   = $_POST['destinationId'] ?? '';
            $destFinale      = !empty($escaleNom) ? $escaleNom : $destinationId;

            // Le prix ne vient jamais du client : il est recalculé côté serveur à partir du trajet programmé.
            $prixUnitaireServeur = $this->getPrixReference($programme, $destinationId, $escaleNom);
            if ($prixUnitaireServeur === null) {
                $this->set_flash("Trajet ou tarif introuvable pour cette destination/heure.", "danger");
                return false;
            }
            $prixUtilise = $prixUnitaireServeur * $nombrePassages;


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
                    // Filtre sur id_agence (gare précise) en plus de la localité : sans ça, deux
                    // gares d'une même ville programmées sur le même créneau se disputeraient le
                    // même car de façon arbitraire (LIMIT 1). Voir ajout_id_agence_programmation_voyage.sql.
                    $rowProg = $this->fetchOne(
                        "SELECT id_car_programmer FROM programmation_voyage
                 WHERE id_horaire = :h AND date_enregistre = :d AND id_trajet = :t
                 AND localite_user = :l AND id_agence = :a AND id_compagnie = :c LIMIT 1",
                        [
                            ':h' => $programme,
                            ':d' => $jourVoyage,
                            ':t' => $destinationId,
                            ':l' => $departLocalite,
                            ':a' => $idAgenceDepart,
                            ':c' => $_SESSION['id_compagnie']
                        ]
                    );

                    if (!$rowProg) {
                        $pdo->rollBack();
                        $this->set_flash("Aucun car programmé pour cette heure et ce trajet.", "danger");
                        return false;
                    }

                    $idCarProgrammer = $rowProg['id_car_programmer'];

                    // SELECT ... FOR UPDATE sur la connexion transactionnelle : verrouille la ligne
                    // jusqu'au commit/rollback pour empêcher deux ventes simultanées de survendre les places.
                    $stmt = $pdo->prepare(
                        "SELECT nbr_place, nbr_place_reserve, id_car FROM car WHERE id_car = :num LIMIT 1 FOR UPDATE"
                    );
                    $stmt->execute([':num' => $idCarProgrammer]);
                    $car = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$car) {
                        $pdo->rollBack();
                        $this->set_flash("Car introuvable.", "danger");
                        return false;
                    }

                    $placesDispo = $car['nbr_place'] - $car['nbr_place_reserve'];
                    if ($nombrePassages > $placesDispo) {
                        $pdo->rollBack();
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

                    // FOR UPDATE : verrouille la ligne de suivi jusqu'au commit/rollback pour éviter
                    // que deux ventes simultanées ne dépassent le quota de places de demain.
                    $stmt = $pdo->prepare(
                        "SELECT idSuivis, place_totals, place_reserve FROM suivis
                 WHERE depart = :dep AND destination = :dest AND heur_depart = :h
                 AND date_reservation = :jr AND id_compagnie = :id_compagnie LIMIT 1 FOR UPDATE"
                    );
                    $stmt->execute([
                        ':dep'          => $departLocalite,
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
                            ':dep'   => $departLocalite,
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
                        ':dep'          => $departLocalite,
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

                // Le numéro de billet ne vient jamais du client : régénéré et vérifié côté
                // serveur (cf. genererNumeroBilletUnique) pour éviter tout doublon.
                $numeroBillets = $this->genererNumeroBilletUnique();

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
                    ':dep'   => $departLocalite,
                    ':exp'   => date('Y-m-d', strtotime($jourVoyage . ' +1 week')),
                    ':place' => $numPlace,
                    ':res'   => date('Ymd'),
                    ':stat'  => 'presentiel',
                    ':validation_billets' => 'valider',
                    ':id_compagnie' => $_SESSION['id_compagnie'],
                    ':num_gare' => $numeroGareDepart
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
                    ':ville'        => $departLocalite,
                    ':numeroGare'   => $numeroGareDepart
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
                $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?');
                if (($_SESSION['droit'] ?? null) === 'Admin' && $idAgenceDepart) {
                    $redirectUrl .= '?idDepart=' . $idAgenceDepart;
                }
                header("Location: " . $redirectUrl);
                exit;
            } catch (Throwable $e) {
                $pdo->rollBack();
                $this->set_flash("Erreur SQL : " . $e->getMessage(), "danger");
                return false;
            }
        }
    }
