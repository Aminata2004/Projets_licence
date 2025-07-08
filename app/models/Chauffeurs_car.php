 <?php
    class Chauffeurs_car extends Model
    {

        public function saveChauffeur()
        {
            // Récupération sécurisée des données du formulaire
            // Récupération sécurisée des données du formulaire
            extract($_POST);
            $errors = [];
            $id_compagnie = $_SESSION["id_compagnie"];
            // Vérification des champs requis
            if (empty($nom_prenom)) {
                $errors[] = "Le nom du chauffeur est obligatoire.";
            }

            if (empty($numero)) {
                $errors[] = "Le numero est obligatoire.";
            }

            if (empty($id_car)) {
                $errors[] = "Le car qu il conduit est obligatoire.";
            }
            
            // Si aucune erreur, on procède à l'insertion
            if (count($errors) === 0) {
                $insertion = $this->insertion_update_simples(
                    "INSERT INTO chauffeur (nom_prenom, numero, id_car,id_compagnie) 
        VALUES (:nom_prenom, :numero, :id_car,:id_compagnie)",
                    [
                        ":nom_prenom" => $nom_prenom,
                        ":numero" => $numero,
                        ":id_car"  => $id_car,
                        ":id_compagnie"=>$id_compagnie
                    ]
                );

                if ($insertion) {
                    $this->set_flash("Car ajouté avec succès", "info");
                } else {
                    $this->set_flash("Erreur : le car n'a pas pu être ajouté");
                }
            } else {
                // Affichage des erreurs
                foreach ($errors as $error) {
                    $this->set_flash($error, "danger");
                }
            }
        }
    }
