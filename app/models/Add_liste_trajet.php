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
                            "INSERT INTO ligneTrajet (id_escales, id_trajets) VALUES(:id_escales, :id_trajets)",
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

    return $errors;
}

}
