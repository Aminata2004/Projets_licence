
<?php
class Programmation_car extends Model
{

    public function Programmer_car()
    {
        // Récupération sécurisée des données du formulaire
        extract($_POST);
        $errors = [];

        // Vérification des champs requis
        if (empty($id_car)) {
            $errors[] = "Le numéro du car est obligatoire.";
        }

        if (empty($idTrajet)) {
            $errors[] = "Le trajet est obligatoire.";
        }
        // Si aucune erreur, on procède à l'insertion
        if (count($errors) === 0) {
            $insertion = $this->insertion_update_simple(
                "INSERT INTO reference_car(id_car) VALUES( :id_car)",
                [":id_car" => $id_car]
            );
            // Vérifier si l'insertion dans reference_car a réussi
            if ($insertion !== false) {
                //  Mettre à jour la colonne programme_car dans la table care avec la valeur "on"
                $bdd = $this->connect();
                $sql_update_care = "UPDATE car SET programmer_car = 'on' WHERE id_car = :id_car";
                $stmt_update_care = $bdd->prepare($sql_update_care);
                $stmt_update_care->bindParam(':id_car', $id_car);
                // Exécuter la requête de mise à jour
                $stmt_update_care->execute();
            }
            // si la mis a jours est effectue alors enregistre les ligne de trajets 
            if ($stmt_update_care !== false) {

                for ($i = 0; $i < count($_POST['idTrajet']); $i++) {
                    $id_trajets = $_POST['idTrajet'][$i]; // Assumption: les index 

                    $Save_ligne_chauffeur = $this->insertion_update_simples(
                        "INSERT INTO  liaison_car_trajet(id_car , id_trajets) VALUES (:id_car,:id_trajets)",
                        [
                            ":id_car" => $id_car,
                            ":id_trajets" => $id_trajets
                        ]
                    );
                }

                if ($Save_ligne_chauffeur == true) {
                    $this->set_flash('Gare ajouter avec succes', 'info');
                } else {
                    $this->set_flash('Gare non ajouter');
                }
            }
        } else {
            // Affichage des erreurs
            foreach ($errors as $error) {
                $this->set_flash($error, "danger");
            }
        }
    }
}
