 <?php
    class Programmer_voyage extends Model
    {

        public function saveProgrammer()
        {
            $errors = [];
            extract($_POST);
            $id_compagnie = $_SESSION["id_compagnie"];

            // Vérification de cohérence départ/destination
            if ($idDepart == $idDestination) {
                $errors[] = "Impossible d'enregistrer ce trajet : départ et destination identiques.";
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
                                "INSERT INTO ligneTrajet (id_escales, id_trajets, prix_escale)
                         VALUES(:id_escales, :id_trajets, :prix_escale)",
                                [
                                    ":id_escales" => $escale,
                                    ":id_trajets" => $id_trajet,
                                    ":prix_escale" => $prixEscale
                                ]
                            );
                        }
                    }

                    // Message de succès
                    if ($programmer) {
                        $this->set_swal(
                            "🕒 Programme enregistré !",
                            "Le programme a été ajouté avec succès.",
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
    }
