 <?php
    class Programmer_voyage extends Model
    {

        public function saveProgrammer()
        {
            $errors = [];
            extract($_POST);
            $id_compagnie = $_SESSION["id_compagnie"];

            // Le chef d'escale ne peut créer un programme qu'au départ de sa propre gare
            // (protection côté serveur en plus du filtrage du menu déroulant).
            if (($_SESSION['droit'] ?? null) === 'chef_d_escale' && (string)$idDepart !== (string)($_SESSION['id_agence'] ?? '')) {
                $errors[] = "Vous ne pouvez créer un programme qu'au départ de votre propre gare.";
            }

            // Vérification de cohérence départ/destination
            if ($idDepart == $idDestination) {
                $errors[] = "Impossible d'enregistrer ce trajet : départ et destination identiques.";
            }

            // Vérification qu'on n'enregistre pas un voyage interne (départ et destination dans la même localité)
            $departAgence = $this->FetchSelectWhere("localite", "agence", "idAgence = :id", [":id" => $idDepart]);
            $destinationAgence = $this->FetchSelectWhere("localite", "agence", "idAgence = :id", [":id" => $idDestination]);
            if ($departAgence && $destinationAgence && $departAgence->localite === $destinationAgence->localite) {
                $errors[] = "Impossible d'enregistrer ce trajet : le départ et la destination sont dans la même localité (voyage interne).";
            }

            // Si pas d'erreurs
            if (count($errors) === 0) {
                // Insertion dans programmer
                $id_trajet = $this->insertion_update_simple(
                    "INSERT INTO programmer (idDepart, idDestination, heureDepart, rdv, prix, id_compagnie)
             VALUES(:idDepart, :idDestination, :heureDepart, :rdv, :prix, :id_compagnie)",
                    [
                        ":idDepart" => $idDepart,
                        ":idDestination" => $idDestination,
                        ":heureDepart" => $heureDepart,
                        ":rdv" => $rdv,
                        ":prix" => $prix,
                        ":id_compagnie" => $id_compagnie
                    ]
                );

                // Si insertion réussie
                if ($id_trajet) {
                    $programmer = true;

                    // Gestion des escales (s'il y en a)
                    if (!empty($_POST['idEscale']) && is_array($_POST['idEscale'])) {
                        foreach ($_POST['idEscale'] as $escale) {
                            $prixEscale = isset($_POST['prix_escale'][$escale])
                                ? (float)$_POST['prix_escale'][$escale]
                                : 0;

                            $programmer = $this->insertion_update_simple(
                                "INSERT INTO ligneTrajet (id_escales, id_trajets, type_trajet, prix_escale)
                         VALUES(:id_escales, :id_trajets, 'programmer', :prix_escale)",
                                [
                                    ":id_escales" => $escale,
                                    ":id_trajets" => $id_trajet,
                                    ":prix_escale" => $prixEscale
                                ]
                            );
                        }
                    }

                    // Crée automatiquement le trajet retour (destination -> depart) s'il n'existe pas déjà,
                    // pour qu'une ligne soit toujours disponible dans les deux sens.
                    $this->ensureReverseProgrammer(
                        $idDepart,
                        $idDestination,
                        $heureDepart,
                        $rdv,
                        $prix,
                        $id_compagnie,
                        !empty($_POST['idEscale']) && is_array($_POST['idEscale']) ? $_POST['idEscale'] : [],
                        $_POST['prix_escale'] ?? []
                    );

                    // Message de succès
                    if ($programmer) {
                        $this->set_swal(
                            "🕒 Programme enregistré !",
                            "Le programme a été ajouté avec succès (aller-retour créé automatiquement).",
                            "success",
                            "#0d6efd",
                            BASE_URL . "/admin/Programmer_voyages/add_programmer"
                        );
                    } else {
                        $this->set_swal(
                            "Erreur",
                            "Échec de l'ajout des escales au trajet.",
                            "error",
                            "#dc3545"
                        );
                    }
                } else {
                    $errors[] = "Une erreur est survenue lors de l'enregistrement du trajet.";
                }
            }

            // Affichage des erreurs si présentes
            if (!empty($errors)) {
                $errorsHtml = implode("<br>", array_map('htmlspecialchars', $errors));
                $this->set_swal(
                    "Erreurs détectées",
                    $errorsHtml,
                    "warning",
                    "#ffc107"
                );
            }

            return $errors;
        }

        // Garantit qu'un trajet et son sens inverse existent toujours ensemble,
        // pour qu'un car ne se retrouve jamais bloqué sans destination valide au retour.
        private function ensureReverseProgrammer($idDepart, $idDestination, $heureDepart, $rdv, $prix, $id_compagnie, $idEscales = [], $prixEscales = [])
        {
            $existingReverse = $this->FetchSelectWhere(
                "idProgrammer",
                "programmer",
                "idDepart = :idDepart AND idDestination = :idDestination AND id_compagnie = :id_compagnie",
                [
                    ":idDepart" => $idDestination,
                    ":idDestination" => $idDepart,
                    ":id_compagnie" => $id_compagnie
                ]
            );

            if ($existingReverse) {
                return $existingReverse->idProgrammer;
            }

            $id_reverse = $this->insertion_update_simple(
                "INSERT INTO programmer (idDepart, idDestination, heureDepart, rdv, prix, id_compagnie)
                 VALUES(:idDepart, :idDestination, :heureDepart, :rdv, :prix, :id_compagnie)",
                [
                    ":idDepart" => $idDestination,
                    ":idDestination" => $idDepart,
                    ":heureDepart" => $heureDepart,
                    ":rdv" => $rdv,
                    ":prix" => $prix,
                    ":id_compagnie" => $id_compagnie
                ]
            );

            if ($id_reverse) {
                foreach ($idEscales as $escale) {
                    $prixEscale = isset($prixEscales[$escale]) ? (float) $prixEscales[$escale] : 0;
                    $this->insertion_update_simple(
                        "INSERT INTO ligneTrajet (id_escales, id_trajets, type_trajet, prix_escale)
                         VALUES(:id_escales, :id_trajets, 'programmer', :prix_escale)",
                        [
                            ":id_escales" => $escale,
                            ":id_trajets" => $id_reverse,
                            ":prix_escale" => $prixEscale
                        ]
                    );
                }
            }

            return $id_reverse;
        }

        public function FetchSelectCustom($query, $params = [])
        {
            $stmt = $this->connect()->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }


        public function editPrix($data)
        {
            $req = "UPDATE programmer 
            SET prix = :prix 
            WHERE idProgrammer = :idProgrammer";

            $params = [
                ":prix" => $data['prix'],
                ":idProgrammer" => $data['idProgrammer'],
            ];

            $modification = $this->insertion_update_simples($req, $params);
            if ($modification == true) {
                $this->set_flash("Modification faite avec succès", "primary");
            }
        }

        public function editHoraire($idProgrammer, $heureDepart, $rdv)
        {
            $req = "UPDATE programmer
            SET heureDepart = :heureDepart, rdv = :rdv
            WHERE idProgrammer = :idProgrammer";

            $params = [
                ":heureDepart" => $heureDepart,
                ":rdv" => $rdv,
                ":idProgrammer" => $idProgrammer,
            ];

            return $this->insertion_update_simples($req, $params);
        }

        public function editPrixEscales($idProgrammer, $prixEscales)
        {
            foreach ($prixEscales as $idEscale => $prix) {
                $this->insertion_update_simples(
                    "UPDATE ligneTrajet SET prix_escale = :prix WHERE id_trajets = :idProgrammer AND id_escales = :idEscale AND type_trajet = 'programmer'",
                    [
                        ":prix" => (float) $prix,
                        ":idProgrammer" => $idProgrammer,
                        ":idEscale" => $idEscale
                    ]
                );
            }
        }

        public function deleteProgrammer($id)
        {
            // Supprimer d'abord les dépendances : escales du trajet, puis affectations aux cars
            $this->insertion_update_simples("DELETE FROM ligneTrajet WHERE id_trajets = :id AND type_trajet = 'programmer'", [":id" => $id]);
            $this->insertion_update_simples("DELETE FROM liaison_car_trajet WHERE id_trajets = :id", [":id" => $id]);

            // Supprimer ensuite le programme lui-même
            $stmt = $this->insertion_update_simples("DELETE FROM programmer WHERE idProgrammer = :id", [":id" => $id]);
            return $stmt ? true : false;
        }
    }
