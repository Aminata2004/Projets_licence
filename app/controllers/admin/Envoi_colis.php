<?php
class Envoi_colis extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }


  public  function  index()
  {
    date_default_timezone_set('Africa/Bamako');
    // recuperation des colis
    $envoie_colis = new Envoie_colis();
    $id_compagnie = $_SESSION['id_compagnie'];
    $ville_user = $_SESSION['ville'] ?? null;
    $liste_colis = $envoie_colis->FetchSelectcolis();

    $listeprogrammer = $envoie_colis->FetchWheresJoin(
      "*",
      "programmation_voyage 
   INNER JOIN horaire ON horaire.id_heure = programmation_voyage.id_horaire",
      "programmation_voyage.id_compagnie = :id_compagnie 
   AND TIMESTAMPDIFF(HOUR, programmation_voyage.date_enregistre, NOW()) < 24 
   AND programmation_voyage.localite_user = :ville",
      [
        ":id_compagnie" => $id_compagnie,
        ":ville" => $ville_user
      ]
    );

    // envoi des colis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoi'])) {
      $colis_ids = $_POST['selected_colis'] ?? [];
      $id_car = $_POST['id_car_selectionner'] ?? null;

      if (!empty($colis_ids) && $id_car) {
        $envoie_colis->traiterEnvoi($colis_ids, $id_car);
        $envoie_colis->set_flash("Colis envoyés avec succès", "primary");

        header("Location: " . ROOT . "/admin/Envoi_colis/index");
        exit;
      } else {
        $envoie_colis->set_flash("Veuillez sélectionner au moins un colis et un car.", "danger");

        header("Location: " . ROOT . "/admin/Envoi_colis/index");
        exit;
      }
    }
date_default_timezone_set('Africa/Bamako');
    // les partie views
    $this->view('admin/envoi_colis', ['listeprogrammer' => $listeprogrammer, 'liste_colis' => $liste_colis]);

    // $this->view('envoi_colis', ['listeprogrammer' => $listeprogrammer]);
  }

  public function liste_colis_envoyer()
  {
    // recuperation des colis
    $envoie_colis = new Envoie_colis();
    $id_compagnie = $_SESSION['id_compagnie'];
    // Agences liées à cette compagnie
    $liste_colis_envoyer = $envoie_colis->FetchSelectWheres(
      '*',
      'ligne_envoi',
      'id_compagnie = :id_compagnie ORDER BY id_ligne_envoi DESC LIMIT 10',
      [':id_compagnie' => $id_compagnie]
    );

    $this->view('admin/liste_colis_envoyer', ['liste_colis_envoyer' => $liste_colis_envoyer]);
  }

  public function details_colis_envoyer()
  {
    $model = new Envoie_colis();
    if (isset($_GET['id_car']) && isset($_GET['date'])) {
      $id_car = $_GET['id_car'];
      $date_envoi = $_GET['date'];


      $liste_colis = $model->getColisParCarEtDate($id_car, $date_envoi);

      $this->view("admin/details_colis_envoyer", [
        "liste_colis" => $liste_colis,
        "id_car" => $id_car,
        "date_envoi" => $date_envoi
      ]);
    } else {
      $model->set_flash("Aucun car sélectionné", "danger");
      $model->redirect("admin/envoi_colis/index");
    }
  }

  // public function envoi_colis()
  // {
  //   $id_car = isset($_GET['id_car']) ? $_GET['id_car'] : null;

  //   $envoie_colis = new Envoie_colis();
  //   $car_selectionne = null;
  //   $liste_colis = $envoie_colis->FetchSelectcolis();

  //   if ($id_car) {
  //     $car_selectionne = $envoie_colis->getCarById($id_car);
  //   }



  //   // ajouter les colis envoyer 
  //   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  //     $colis_ids = $_POST['selected_colis'] ?? [];
  //     $id_car = $_POST['id_car_selectionner'] ?? null;

  //     if (!empty($colis_ids) && $id_car) {
  //       $envoie_colis->traiterEnvoi1($colis_ids, $id_car);
  //       $envoie_colis->set_flash("Colis envoyés avec succès", "primary");

  //       header("Location: " . ROOT . "/admin/Envoi_colis/envoi_colis");
  //       exit;
  //     } else {
  //       $envoie_colis->set_flash("Veuillez sélectionner au moins un colis et un car.", "danger");

  //       header("Location: " . ROOT . "/admin/Envoi_colis/envoi_colis");
  //       exit;
  //     }
  //   }


  //   $this->view("admin/ajouter_colis_envoi", [
  //     'liste_colis' => $liste_colis,
  //     'car_selectionne' => $car_selectionne
  //   ]);
  // }

  public function envoi_colis()
  {
    $envoie_colis = new Envoie_colis();
    $car_selectionne = null;

    // Liste de tous les colis disponibles
    $liste_colis = $envoie_colis->FetchSelectcolis();

  

    // ✅ Récupérer la liste des cars programmés sans doublons
    $liste_cars = $envoie_colis->FetchSelectWhere1(
      "DISTINCT
        programmation_voyage.id_programmation,
        programmation_voyage.id_car_programmer,
        programmation_voyage.id_horaire,
        programmation_voyage.id_trajet,
        programmation_voyage.localite_user,
        programmation_voyage.date_enregistre,
        programmation_voyage.id_compagnie AS compagnie_prog,
        horaire.heuredepart,
        horaire.id_compagnie AS compagnie_horaire",
      "programmation_voyage
     INNER JOIN horaire 
        ON horaire.heuredepart = programmation_voyage.id_horaire
        AND horaire.id_compagnie = programmation_voyage.id_compagnie",
      "programmation_voyage.date_enregistre = :today
     AND programmation_voyage.id_compagnie = :id_compagnie",
      [
        ":today" => date('Y-m-d'),
        ":id_compagnie" => $_SESSION['id_compagnie']
      ]
    );


    // Si un car est sélectionné depuis un GET ou POST
    $id_car = $_GET['id_car'] ?? ($_POST['id_car_selectionner'] ?? null);
    if ($id_car) {
      $car_selectionne = $envoie_colis->getCarById($id_car);
    }

    // Traitement de l'envoi des colis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
      $colis_ids = $_POST['selected_colis'] ?? [];
      $id_car = $_POST['id_car_selectionner'] ?? null;

      if (!empty($colis_ids) && $id_car) {
        $envoie_colis->traiterEnvoi1($colis_ids, $id_car);
        $envoie_colis->set_flash("Colis envoyés avec succès", "primary");
        header("Location: " . BASE_URL . "/admin/Envoi_colis/envoi_colis");
        exit;
      } else {
        $envoie_colis->set_flash("Veuillez sélectionner au moins un colis et un car.", "danger");
        header("Location: " . BASE_URL . "/admin/Envoi_colis/envoi_colis");
        exit;
      }
    }


    // Envoi à la vue
    $this->view("admin/ajouter_colis_envoi", [
      'liste_colis' => $liste_colis,
      'liste_cars' => $liste_cars, // ✅ On envoie la liste des cars
      'car_selectionne' => $car_selectionne
    ]);
  }
}
