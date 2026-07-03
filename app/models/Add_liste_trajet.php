<?php
class Add_liste_trajet extends Model
{
   public function saveTrajet()
{
    $errors = [];

    extract($_POST); // Récupère les données du formulaire
    $id_compagnie = $_SESSION["id_compagnie"];

    if ($depart == $destination) {
        $errors[] = "Impossible d'enregistrer ce trajet : départ et destination identiques.";
    }

    // Vérification si le trajet existe déjà
    $existingTrajet = $this->FetchSelectWhere(
        "idTrajet",
        "trajet",
        "depart = :depart AND destination = :destination AND id_compagnie = :id_compagnie",
        [
            ":depart" => $depart,
            ":destination" => $destination,
            ":id_compagnie" => $id_compagnie
        ]
    );

    if ($existingTrajet) {
        $errors[] = "Ce trajet existe déjà.";
    }

    if (count($errors) == 0) {
        if (empty($_POST['idEscale'])) {
            // Cas sans escale
            $insertion = $this->insertion_update_simple(
                "INSERT INTO trajet (depart, destination, id_compagnie) VALUES(:depart, :destination, :id_compagnie)",
                [
                    ":depart" => $depart,
                    ":destination" => $destination,
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
                "INSERT INTO trajet (depart, destination, id_compagnie) VALUES(:depart, :destination, :id_compagnie)",
                [
                    ":depart" => $depart,
                    ":destination" => $destination,
                    ":id_compagnie" => $id_compagnie
                ]
            );

            if ($id_trajet) {
                if (isset($_POST['idEscale']) && is_array($_POST['idEscale'])) {
                    foreach ($_POST['idEscale'] as $escale) {
                        $this->insertion_update_simple(
                            "INSERT INTO ligneTrajet (id_escales, id_trajets, type_trajet) VALUES(:id_escales, :id_trajets, 'trajet')",
                            [
                                ":id_escales" => $escale,
                                ":id_trajets" => $id_trajet
                            ]
                        );
                    }
                }
                $this->set_flash("L'enregistrement avec escales a été fait avec succès !", "info");
            } else {
                $errors[] = "Une erreur est survenue lors de l'enregistrement du trajet.";
            }
        }
    }

    if (count($errors) > 0) {
        $this->set_flash(implode(" ", $errors), "danger");
    }

    return $errors;
}

    public function deleteTrajet($id)
    {
        // Supprimer d'abord les liaisons avec les escales
        $this->insertion_update_simples("DELETE FROM ligneTrajet WHERE id_trajets = :id AND type_trajet = 'trajet'", [":id" => $id]);

        // Supprimer ensuite le trajet
        $stmt = $this->insertion_update_simples("DELETE FROM trajet WHERE idTrajet = :id", [":id" => $id]);
        return $stmt ? true : false;
    }

    public function getEscalesByTrajet($id)
    {
        $stmt = $this->connect()->prepare("
            SELECT e.id_escale, e.escales
            FROM escale e
            INNER JOIN ligneTrajet lt ON e.id_escale = lt.id_escales
            WHERE lt.id_trajets = :id AND lt.type_trajet = 'trajet'
        ");
        $stmt->execute([":id" => $id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function updateTrajet()
    {
        $errors = [];
        extract($_POST);
        
        if ($depart == $destination) {
            $errors[] = "Départ et destination ne peuvent pas être identiques.";
            $this->set_flash(implode(" ", $errors), "danger");
            return $errors;
        }
        
        // Mettre à jour le trajet
        $this->insertion_update_simples("UPDATE trajet SET depart = :depart, destination = :destination WHERE idTrajet = :id", [
            ":depart" => $depart,
            ":destination" => $destination,
            ":id" => $idTrajet
        ]);
        
        // Supprimer les anciennes escales
        $this->insertion_update_simples("DELETE FROM ligneTrajet WHERE id_trajets = :id AND type_trajet = 'trajet'", [":id" => $idTrajet]);

        // Insérer les nouvelles
        if (!empty($_POST['idEscale']) && is_array($_POST['idEscale'])) {
            foreach ($_POST['idEscale'] as $escale) {
                $this->insertion_update_simples(
                    "INSERT INTO ligneTrajet (id_escales, id_trajets, type_trajet) VALUES(:id_escales, :id_trajets, 'trajet')",
                    [
                        ":id_escales" => $escale,
                        ":id_trajets" => $idTrajet
                    ]
                );
            }
        }
        $this->set_flash("Le trajet a été modifié avec succès !", "success");
        return [];
    }
}
