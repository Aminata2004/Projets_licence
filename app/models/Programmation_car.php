
<?php
class Programmation_car extends Model
{

    // Le formulaire permet de cocher plusieurs cars à la fois (au lieu d'un seul) : les
    // mêmes trajets sélectionnés une fois sont assignés à chaque car coché, pour ne pas
    // avoir à rouvrir le modal et resaisir les trajets pour chaque car.
    public function Programmer_car()
    {
        $errors = [];

        $idCars = $_POST['id_car'] ?? [];
        if (!is_array($idCars)) {
            $idCars = [$idCars];
        }
        $idCars = array_values(array_unique(array_filter(
            $idCars,
            fn($id) => trim((string) $id) !== ''
        )));

        $idTrajet = $_POST['idTrajet'] ?? [];

        if (empty($idCars)) {
            $errors[] = "Veuillez cocher au moins un car.";
        }
        if (empty($idTrajet)) {
            $errors[] = "Le trajet est obligatoire.";
        }

        // Un Admin ne peut programmer que les cars de sa propre compagnie (IDOR sinon)
        foreach ($idCars as $id_car) {
            if (!$this->carAppartientCompagnie($id_car)) {
                $errors[] = "Le car #$id_car n'appartient pas à votre compagnie.";
            }
        }

        if (!empty($errors)) {
            // set_swal() (popup SweetAlert) plutôt que set_flash() (bandeau discret en haut
            // de page) : après une soumission depuis la modal, un bandeau qui apparaît
            // derrière la modal qui se ferme passe facilement inaperçu. La popup, elle,
            // s'affiche par-dessus tout et oblige à cliquer "OK" pour la fermer.
            $errorsHtml = implode("<br>", array_map('htmlspecialchars', $errors));
            $this->set_swal("Erreur", $errorsHtml, "warning", "#ffc107");
            return;
        }

        $id_compagnie = $_SESSION['id_compagnie'];
        $nbProgrammes = 0;

        foreach ($idCars as $id_car) {
            $insertion = $this->insertion_update_simple(
                "INSERT INTO reference_car(id_car) VALUES(:id_car)",
                [":id_car" => $id_car]
            );
            if ($insertion === false) {
                continue;
            }

            $bdd = $this->connect();
            $stmt_update_car = $bdd->prepare("UPDATE car SET programmer_car = 'on' WHERE id_car = :id_car");
            $stmt_update_car->bindParam(':id_car', $id_car);
            $stmt_update_car->execute();

            $this->linkTrajetsToCar($id_car, $idTrajet, $id_compagnie);

            $nbProgrammes++;
        }

        if ($nbProgrammes > 0) {
            $nbTrajets = count($idTrajet);
            $message = "$nbProgrammes car" . ($nbProgrammes > 1 ? 's' : '') . " programmé" . ($nbProgrammes > 1 ? 's' : '')
                . " avec $nbTrajets trajet" . ($nbTrajets > 1 ? 's' : '') . " chacun (aller-retour inclus automatiquement).";
            $this->set_swal(
                "🚌 Car" . ($nbProgrammes > 1 ? 's' : '') . " programmé" . ($nbProgrammes > 1 ? 's' : '') . " !",
                $message,
                "success",
                "#0d6efd",
                BASE_URL . "/admin/Programmation_cars/index"
            );
        } else {
            $this->set_swal("Erreur", "Aucun car n'a pu être programmé.", "error", "#dc3545");
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

        // Un Admin ne peut modifier que les cars de sa propre compagnie (IDOR sinon)
        if (empty($errors) && !$this->carAppartientCompagnie($id_car)) {
            $errors[] = "Ce car n'appartient pas à votre compagnie.";
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
        // Un Admin ne peut déprogrammer que les cars de sa propre compagnie (IDOR sinon)
        if (!$this->carAppartientCompagnie($id_car)) {
            return false;
        }

        $this->insertion_update_simples("DELETE FROM liaison_car_trajet WHERE id_car = :id_car", [":id_car" => $id_car]);
        $this->insertion_update_simples("DELETE FROM reference_car WHERE id_car = :id_car", [":id_car" => $id_car]);
        $stmt = $this->insertion_update_simples("UPDATE car SET programmer_car = 'off' WHERE id_car = :id_car", [":id_car" => $id_car]);

        return $stmt ? true : false;
    }

    // Vérifie que le car appartient à la compagnie de l'utilisateur connecté (super_admin exempté)
    private function carAppartientCompagnie($id_car)
    {
        if (($_SESSION['droit'] ?? null) === 'super_admin') {
            return true;
        }
        $car = $this->FetchSelectWhere(
            "id_car",
            "car",
            "id_car = :id_car AND id_compagnie = :id_compagnie",
            [":id_car" => $id_car, ":id_compagnie" => $_SESSION['id_compagnie'] ?? null]
        );
        return (bool) $car;
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
