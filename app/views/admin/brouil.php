 public function saveBillets(): bool
        {
            extract($_POST);
            $pdo = $this->connect();

            // Validation basique
            if (empty($Client) || empty($destinationId) || empty($jourVoyage) || empty($programme)) {
                $this->set_flash("Tous les champs obligatoires doivent être remplis.", "danger");
                return false;
            }

            $aujourdhui = date('Y-m-d');
            $demain = date('Y-m-d', strtotime('+1 day'));
            if (!in_array($jourVoyage, [$aujourdhui, $demain])) {
                $this->set_flash("Date invalide (aujourd’hui ou demain).", "danger");
                return false;
            }

            // Recherche du car programmé avec destinationId (pas escale)
            $sql = "SELECT id_car_programmer
            FROM programmation_voyage
            WHERE id_horaire = :id_horaire
              AND date_enregistre = :date_enregistre
              AND id_trajet = :id_trajet
              AND localite_user = :localite_user
              AND id_compagnie= :id_compagnie
            LIMIT 1";

            $idCarProg = $this->fetchOne($sql, [
                ':id_horaire'      => $programme,
                ':date_enregistre' => $jourVoyage,
                ':id_trajet'       => $destinationId,
                ':localite_user'   => $_SESSION['ville'],
                ':id_compagnie'    => $_SESSION['id_compagnie']
            ]);

            if (!$idCarProg) {
                $this->set_flash("Programme introuvable.", "danger");
                return false;
            }

            // Récupérer info du car
            $car = $this->fetchOne(
                "SELECT nbr_place, nbr_place_reserve FROM car WHERE numero_car = :num LIMIT 1",
                [':num' => $idCarProg['id_car_programmer']]
            );

            if (!$car) {
                $this->set_flash("Car introuvable.", "danger");
                return false;
            }

            $dispo = $car['nbr_place'] - $car['nbr_place_reserve'];
            if ($nombrePassages > $dispo) {
                $this->set_flash("Places insuffisantes : $dispo restantes.", "danger");
                return false;
            }

            $start = (int)$car['nbr_place_reserve'] + 1;
            $nombrePassagesInt = (int)$nombrePassages;
            $end = $start + $nombrePassagesInt - 1;
            $numero_place = ($nombrePassagesInt == 1) ? "$start" : "$start-$end";


            // Choix du prix et destination finale selon escale
            if (!empty($escaleNom)) {
                // Prix manuel venant du champ montants_payers (note: vérifier nom exact du champ dans formulaire)
                $prixUtilise = !empty($montant_payers) ? $montant_payers : 0;
                $destFinale = $escaleNom; // Enregistre nom escale à la place de destinationId
            } else {
                $prixUtilise = $montant_payer; // Prix normal
                $destFinale = $destinationId;  // Destination normale
            }

            try {
                $pdo->beginTransaction();

                // Insertion client
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

                // Insertion billet
                $stmt = $pdo->prepare(
                    "INSERT INTO billets (id_client, numeroBillets, jourVoyage, Heur_departs,
                                  nombrePassages, destinationId, departId, date_expiration,
                                  numeroPlace, date_reservation, status_reservation)
             VALUES (:cl, :num, :j, :h, :n, :dest, :dep, :exp, :place, :res, :stat)"
                );
                $stmt->execute([
                    ':cl'   => $idClient,
                    ':num'  => $numeroBillets,
                    ':j'    => $jourVoyage,
                    ':h'    => $programme,
                    ':n'    => $nombrePassages,
                    ':dest' => $destFinale,
                    ':dep'  => $_SESSION['id_agence'],
                    ':exp'  => date('Y-m-d', strtotime($jourVoyage . ' +1 week')),
                    ':place' => $numero_place,
                    ':res'  => date('Ymd'),
                    ':stat' => 'presentiel'
                ]);

                // Mise à jour du car
                $stmt = $pdo->prepare(
                    "UPDATE car
             SET nbr_place_reserve = nbr_place_reserve + :n
             WHERE numero_car = :num"
                );
                $stmt->execute([
                    ':n'   => (int)$nombrePassages,
                    ':num' => trim($idCarProg['id_car_programmer'])
                ]);
                if ($stmt->rowCount() === 0) {
                    $pdo->rollBack();
                    $this->set_flash("Mise à jour du car échouée ou aucune modification détectée.", "danger");
                    return false;
                }

                $pdo->commit();
                $this->set_flash("Réservation enregistrée avec succès.", "info");
                return true;
            } catch (Throwable $e) {
                $pdo->rollBack();
                $this->set_flash("Erreur SQL : " . $e->getMessage(), "danger");
                return false;
            }
        }


        http://localhost:8080/Gestion_compagnie_mcv/index.php?url=admin/loguins/index