<?php

class Programmation_voyages extends Controller
{

    public function __construct()
    {
        $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
    }

    // Affiche la page avec la table, les horaires, etc.
    public function index()
    {
        $programmation_voyage = new Programmation_voyage();

        // Récupération des horaires
        $listehoraire = $programmation_voyage->getHoraires();

        // Récupération des programmations des cars
        $programmations = $programmation_voyage->getProgrammationCars();

        // Groupement des programmations par numéro de car
        $cars_destinations = [];
        foreach ($programmations as $ligne) {
            $cars_destinations[$ligne->numero_car][] = $ligne;
        }
        // Méthode pour traiter le formulaire de programmation
        // if (isset($_POST['programmer']) && !empty($_POST['select_car'])) {
        //     $model = new Programmation_voyage();
        //     $localite_user = $_SESSION['ville'] ?? 'non-defini';
        //     $date_enregistre = date('Ymd');

        //     foreach ($_POST['select_car'] as $index => $val) {
        //         $id_care = $_POST['id_care'][$index] ?? null;
        //         $id_horaire = $_POST['id_horaire'][$index] ?? null;
        //         $id_destination = $_POST['id_destination'][$index] ?? null;

        //         if ($id_care && $id_horaire && $id_destination) {
        //             $insert_result = $model->insertProgrammation($id_care, $id_horaire, $id_destination, $localite_user, $date_enregistre);
        //             if ($insert_result) {
        //                 $update_result = $model->updateCareStatus($id_care, $id_destination);
        //                 if (!$update_result) {
        //                     $model->set_flash("Erreur lors de la mise à jour du statut du car $id_care.", "danger");

        //                 }
        //             } else {
        //                 $model->set_flash("Erreur lors de l'insertion de la programmation pour le car $id_care.", "danger") ;

        //             }
        //         }
        //     }
        //    $model->set_flash("Programmation générée avec succès !", "success");


        // } 


        if (isset($_POST['programmer']) && !empty($_POST['select_car'])) {
            $model = new Programmation_voyage();
            $localite_user = $_SESSION['ville'] ?? 'non-defini';
          $date_enregistre = date('Y-m-d');

            $errors = [];  // tableau pour collecter les erreurs

            foreach ($_POST['select_car'] as $index => $val) {
                $id_care = $_POST['id_care'][$index] ?? null;
                $id_horaire = $_POST['id_horaire'][$index] ?? null;
                $id_destination = $_POST['id_destination'][$index] ?? null;

                if (!$id_care || !$id_horaire || !$id_destination) {
                    $errors[] = "Veuillez remplir tous les champs pour la ligne #" . ($index + 1) . ".";
                    continue; // passe à la ligne suivante sans insérer
                }

                $insert_result = $model->insertProgrammation($id_care, $id_horaire, $id_destination, $localite_user, $date_enregistre);
                if ($insert_result) {
                    $update_result = $model->updateCareStatus($id_care, $id_destination);
                    if (!$update_result) {
                        $errors[] = "Erreur lors de la mise à jour du statut du car $id_care.";
                    }
                } else {
                    $errors[] = "Erreur lors de l'insertion de la programmation pour le car $id_care.";
                }
            }

            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $model->set_flash($error, "danger");
                }
            } else {
                $model->set_flash("Programmation générée avec succès !", "info");
                header("Location: " . BASE_URL . "/admin/Programmation_voyages/index");
                exit;
            }
        }

        // Envoi à la vue
        $this->view('admin/programmation_voyage', [
            'listehoraire' => $listehoraire,
            'cars_destinations' => $cars_destinations
        ]);
    }

    public function liste_programmer_voyage()
    {
        // Récupérer les données des filières
        $programmation_voyage = new Programmation_voyage();
        $id_compagnie= $_SESSION['id_compagnie'];

       $listeProgrammer =  $programmation_voyage->FetchSelectWheres(
          '*',
          'programmation_voyage',
          'id_compagnie = :id_compagnie',
          ['id_compagnie' => $id_compagnie]
        );
        $this->view('admin/programmer_voyage_journalier', [

            'listeProgrammer' => $listeProgrammer

        ]);
    }
}
