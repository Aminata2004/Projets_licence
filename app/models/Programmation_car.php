
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

                $Save_ligne_chauffeur = $this->linkTrajetsToCar($id_car, $_POST['idTrajet'], $_SESSION['id_compagnie']);

                if ($Save_ligne_chauffeur == true) {
                    $this->set_flash('Car programmer avec succès', 'info');
                } else {
                    $this->set_flash('Car non programmer', 'danger');
                }
            }
        } else {
            // Affichage des erreurs
            foreach ($errors as $error) {
                $this->set_flash($error, "danger");
            }
        }
    }

    // Ajoute un ou plusieurs trajets supplémentaires à un car déjà programmé
    public function ajouterTrajet()
    {
        extract($_POST);
        $errors = [];

        if (empty($id_car)) {
            $errors[] = "Le numéro du car est obligatoire.";
        }

        if (empty($idTrajet)) {
            $errors[] = "Le trajet est obligatoire.";
        }

        if (count($errors) === 0) {
            $success = $this->linkTrajetsToCar($id_car, $_POST['idTrajet'], $_SESSION['id_compagnie']);

            if ($success) {
                $this->set_flash('Trajet ajouté au car avec succès', 'info');
            } else {
                $this->set_flash("Erreur lors de l'ajout du trajet", 'danger');
            }
        } else {
            foreach ($errors as $error) {
                $this->set_flash($error, "danger");
            }
        }
    }

    // Supprime la programmation d'un car : ses trajets affectés, sa référence,
    // et remet le car en disponible pour une nouvelle programmation.
    public function supprimerProgrammation($id_car)
    {
        $this->insertion_update_simples("DELETE FROM liaison_car_trajet WHERE id_car = :id_car", [":id_car" => $id_car]);
        $this->insertion_update_simples("DELETE FROM reference_car WHERE id_car = :id_car", [":id_car" => $id_car]);
        $stmt = $this->insertion_update_simples("UPDATE car SET programmer_car = 'off' WHERE id_car = :id_car", [":id_car" => $id_car]);

        return $stmt ? true : false;
    }

    // Relie un ou plusieurs trajets (et leur sens inverse) à un car, sans dupliquer les liaisons existantes.
    private function linkTrajetsToCar($id_car, array $idsTrajet, $id_compagnie)
    {
        $success = true;

        foreach ($idsTrajet as $id_trajets) {
            // On assigne aussi le trajet retour (sens inverse) pour que le car
            // ait toujours une destination valide une fois arrivé.
            $idsToLink = [$id_trajets];
            $reverseId = $this->getReverseTrajetId($id_trajets, $id_compagnie);
            if ($reverseId && !in_array($reverseId, $idsToLink)) {
                $idsToLink[] = $reverseId;
            }

            foreach ($idsToLink as $idToLink) {
                $dejaAffecte = $this->FetchSelectWhere(
                    "id_car",
                    "liaison_car_trajet",
                    "id_car = :id_car AND id_trajets = :id_trajets",
                    [":id_car" => $id_car, ":id_trajets" => $idToLink]
                );

                if (!$dejaAffecte) {
                    $success = $this->insertion_update_simples(
                        "INSERT INTO  liaison_car_trajet(id_car , id_trajets,id_compagnie) VALUES (:id_car,:id_trajets,:id_compagnie)",
                        [
                            ":id_car" => $id_car,
                            ":id_trajets" => $idToLink,
                            ":id_compagnie" => $id_compagnie
                        ]
                    );
                }
            }
        }

        return $success ? true : false;
    }
    public function FetchSelectCustom($query, $params = [])
    {
        $stmt = $this->connect()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Trouve le trajet-programme qui fait le sens inverse (destination -> depart) d'un trajet donné.
    private function getReverseTrajetId($id_trajet, $id_compagnie)
    {
        $trajet = $this->FetchSelectWhere(
            "idDepart, idDestination",
            "programmer",
            "idProgrammer = :id AND id_compagnie = :id_compagnie",
            [":id" => $id_trajet, ":id_compagnie" => $id_compagnie]
        );

        if (!$trajet) {
            return null;
        }

        $reverse = $this->FetchSelectWhere(
            "idProgrammer",
            "programmer",
            "idDepart = :idDepart AND idDestination = :idDestination AND id_compagnie = :id_compagnie",
            [
                ":idDepart" => $trajet->idDestination,
                ":idDestination" => $trajet->idDepart,
                ":id_compagnie" => $id_compagnie
            ]
        );

        return $reverse ? $reverse->idProgrammer : null;
    }
}
