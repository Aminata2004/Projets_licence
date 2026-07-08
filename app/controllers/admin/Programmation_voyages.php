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
        //         $cars_destinations = [];

        // foreach ($programmations as $ligne) {
        //     $id_car = $ligne->id_car;          // 👈 ID réel
        //     $numero_car = $ligne->numero_car;  // 👁️ affiché

        //     if (!isset($cars_destinations[$id_car])) {
        //         $cars_destinations[$id_car] = [
        //             'numero_car' => $numero_car,
        //             'destinations' => []
        //         ];
        //     }

        //     $cars_destinations[$id_car]['destinations'][] = $ligne;
        // }

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

            foreach ($_POST['select_car'] as $val) {
                $index = $val;
                $id_care = $_POST['id_care'][$index] ?? null;
                $id_horaire = $_POST['id_horaire'][$index] ?? null;
                $id_destination = $_POST['id_destination'][$index] ?? null;
                // Départ choisi dans le formulaire (Admin uniquement) ; pour un chef d'escale,
                // le modèle retombe sur sa propre gare de session.
                $id_depart = $_POST['id_depart'][$index] ?? null;

                if (!$id_care || !$id_horaire || !$id_destination) {
                    $errors[] = "Veuillez remplir tous les champs pour la ligne choisie.";
                    continue; // passe à la ligne suivante sans insérer
                }

                if ($_SESSION['droit'] === 'Admin' && empty($id_depart)) {
                    $errors[] = "Veuillez choisir une destination pour renseigner le départ de la ligne choisie.";
                    continue;
                }

                $insert_result = $model->insertProgrammation($id_care, $id_horaire, $id_destination, $localite_user, $date_enregistre, $id_depart);
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

        // Gestion de la validation d'arrivée
        if (isset($_POST['valider_arrivee']) && !empty($_POST['id_car_arrivee'])) {
            $id_car = $_POST['id_car_arrivee'];
            if ($programmation_voyage->validerArrivee($id_car)) {
                $programmation_voyage->set_flash("L'arrivée du car a été validée avec succès !", "success");
            } else {
                $programmation_voyage->set_flash("Erreur lors de la validation de l'arrivée.", "danger");
            }
            header("Location: " . BASE_URL . "/admin/Programmation_voyages/index");
            exit;
        }

        // Récupération des cars en transit
        $cars_en_transit = $programmation_voyage->getCarsInTransit();

        // Dernière programmation existante (pour pré-remplissage à la demande, cf. bouton "Reproduire").
        // Admin : toute la compagnie (le départ varie ligne par ligne). Chef d'escale : sa propre gare.
        // On reprend la DERNIÈRE date disponible (pas forcément hier) pour couvrir les jours sans
        // activité ou le tout début d'utilisation du système.
        $aujourdhui = date('Y-m-d');
        $localite_filtre = $_SESSION['droit'] === 'chef_d_escale' ? ($_SESSION['ville'] ?? null) : null;
        $derniere_date = $programmation_voyage->getDerniereDateProgrammation(
            $_SESSION['id_compagnie'],
            $aujourdhui,
            $localite_filtre
        );
        $programmation_veille = $derniere_date
            ? $programmation_voyage->getProgrammationParDate($_SESSION['id_compagnie'], $derniere_date, $localite_filtre)
            : [];

        // Envoi à la vue
        $this->view('admin/programmation_voyage', [
            'listehoraire' => $listehoraire,
            'cars_destinations' => $cars_destinations,
            'cars_en_transit' => $cars_en_transit,
            'programmation_veille' => $programmation_veille,
            'derniere_date' => $derniere_date
        ]);
    }

    public function liste_programmer_voyage()
    {
        // Récupérer les données des filières
        $programmation_voyage = new Programmation_voyage();
        $id_compagnie = $_SESSION['id_compagnie'];

        $listeProgrammer = $programmation_voyage->FetchSelectWheres(
            'pv.*, 
     c.numero_car,
     c.nbr_place,
     c.nbr_place_reserve,
     (c.nbr_place - c.nbr_place_reserve) AS place_disponible',
            'programmation_voyage pv
     INNER JOIN car c ON pv.id_car_programmer = c.id_car',
            'pv.id_compagnie = :id_compagnie',
            ['id_compagnie' => $id_compagnie]
        );


        $this->view('admin/programmer_voyage_journalier', [

            'listeProgrammer' => $listeProgrammer

        ]);
    }

    public function edit($id_programmation)
    {
        $programmation_voyage = new Programmation_voyage();

        // Traitement de la soumission du formulaire de modification
        if (isset($_POST['modifier'])) {
            $id_horaire = $_POST['id_horaire'][0] ?? null;
            $id_destination = $_POST['id_destination'][0] ?? null;
            $id_care = $_POST['id_care'][0] ?? null;

            if ($id_horaire && $id_destination && $id_care) {
                $programmation_voyage->updateProgrammation($id_programmation, $id_horaire, $id_destination);
                $programmation_voyage->updateCareStatus($id_care, $id_destination);
                $programmation_voyage->set_flash("La programmation a été modifiée avec succès !", "success");
            } else {
                $programmation_voyage->set_flash("Veuillez remplir tous les champs.", "danger");
            }

            header("Location: " . BASE_URL . "/admin/Programmation_voyages/liste_programmer_voyage");
            exit;
        }

        // Récupérer la programmation
        $programmation = $programmation_voyage->FetchSelectWheres(
            'pv.*, c.numero_car, c.nbr_place, c.nbr_place_reserve',
            'programmation_voyage pv
         INNER JOIN car c ON pv.id_car_programmer = c.id_car',
            'pv.id_programmation = :id_programmation',
            ['id_programmation' => $id_programmation]
        );

        if (empty($programmation)) {
            $programmation_voyage->set_flash("Programmation introuvable !", "danger");
            header("Location: " . BASE_URL . "/admin/Programmation_voyages/liste_programmer_voyage");
            exit;
        }

        // Récupérer les horaires disponibles
        $id_compagnie= $_SESSION['id_compagnie'] ;
        $listehoraire = $programmation_voyage->FetchSelectWheres(
            '*',
            'horaire',
            "id_compagnie = :id_compagnie",
            [":id_compagnie" => $id_compagnie],
            '1=1'
        );

        // Récupérer les destinations réellement assignées à ce car, au départ de la
        // localité d'origine de cette programmation (pour proposer un changement cohérent).
        $destinations = $programmation_voyage->getDestinationsForCar(
            $programmation[0]->id_car_programmer,
            $programmation[0]->localite_user,
            $id_compagnie
        );

        $cars_destinations = [];
        $cars_destinations[$programmation[0]->numero_car] = $destinations;


        $this->view('admin/programmer_voyage_modifier', [
            'programmation' => $programmation[0],
            'cars_destinations' => $cars_destinations,
            'listehoraire' => $listehoraire
        ]);
    }
}
