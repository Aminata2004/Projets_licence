 <?php
    class Programmer_voyage extends Model
    {

        public function saveProgrammer()
        {
            $errors = [];

            extract($_POST); // Récupère les données du formulaire
            $id_compagnie = $_SESSION["id_compagnie"];

            if ($idDepart == $idDestination) {
                $errors[] = "Impossible d'enregistrer ce trajet : départ et destination identiques.";
            }

            if (count($errors) == 0) {
                if (empty($_POST['idEscale'])) {
                    // Cas sans escale
                    $insertion = $this->insertion_update_simple(
                        "INSERT INTO programmer (idDepart, idDestination, heureDepart, rdv, prix, id_compagnie) VALUES(:idDepart, :idDestination, :heureDepart, :rdv,  :prix,:id_compagnie)",
                        [
                            ":idDepart" => $idDepart,
                            ":idDestination" => $idDestination,
                            ":heureDepart" => $heureDepart,
                            ":rdv" => $rdv,
                            ":prix" => $prix,
                            ":id_compagnie" => $id_compagnie
                        ]
                    );

                    if ($insertion) {
                        $this->set_flash("L'enregistrement a été fait avec succès !", "primary");
                    } else {
                        $errors[] = "Une erreur est survenue lors de l'enregistrement.";
                    }
                } else {
                    // Cas avec escales
                    $id_trajet = $this->insertion_update_simple(
                        "INSERT INTO programmer (idDepart, idDestination, heureDepart, rdv, prix, id_compagnie) VALUES(:idDepart, :idDestination, :heureDepart, :rdv,  :prix,:id_compagnie)",
                        [
                            ":idDepart" => $idDepart,
                            ":idDestination" => $idDestination,
                            ":heureDepart" => $heureDepart,
                            ":rdv" => $rdv,
                            ":prix" => $prix,
                            ":id_compagnie" => $id_compagnie
                        ]
                    );

                    if ($id_trajet) {
                        if (isset($_POST['idEscale']) && is_array($_POST['idEscale'])) {
                            foreach ($_POST['idEscale'] as $escale) {
                                $programmer = $this->insertion_update_simple(
                                    "INSERT INTO ligneTrajet (id_escales, id_trajets) VALUES(:id_escales, :id_trajets)",
                                    [
                                        ":id_escales" => $escale,
                                        ":id_trajets" => $id_trajet
                                    ]
                                );
                            }
                        }
                        if ($programmer) {
                            $this->set_swal(
                                "🕒 Programme enregistré !",
                                "Le programme a été ajouté avec succès.",
                                "success",
                                "#0d6efd", // couleur primary
                                BASE_URL . "/Programmer_voyages" // redirection après confirmation
                            );
                        } else {
                            $this->set_swal(
                                "Erreur",
                                "Échec de l'ajout de l'utilisateur.",
                                "error",
                                "#dc3545" // rouge
                            );
                        }
                    } else {
                        $errorsHtml = implode("<br>", array_map('htmlspecialchars', $errors));
                        $this->set_swal(
                            "Erreurs détectées",
                            $errorsHtml,
                            "warning",
                            "#ffc107" // jaune warning
                        );
                    }
                }
            }

            return $errors;
        }
    }
