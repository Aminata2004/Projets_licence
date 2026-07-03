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
      $liste_cars = $model->getCarsDisponiblesAujourdhui();

      $this->view("admin/details_colis_envoyer", [
        "liste_colis" => $liste_colis,
        "id_car" => $id_car,
        "date_envoi" => $date_envoi,
        "liste_cars" => $liste_cars
      ]);
    } else {
      $model->set_flash("Aucun car sélectionné", "danger");
      $model->redirect("admin/envoi_colis/index");
    }
  }

  // Déplace un colis déjà envoyé vers un autre car
  public function changer_car()
  {
    $model = new Envoie_colis();

    if (
      $_SERVER['REQUEST_METHOD'] === 'POST'
      && isset($_POST['id_colis'], $_POST['ancien_id_car'], $_POST['ancienne_date'], $_POST['nouveau_id_car'])
    ) {
      if ($model->changerCarColis(
        $_POST['id_colis'],
        $_POST['ancien_id_car'],
        $_POST['ancienne_date'],
        $_POST['nouveau_id_car']
      )) {
        $model->set_flash("Le car d'envoi du colis a été modifié avec succès.", "success");
      } else {
        $model->set_flash("Erreur lors du changement de car.", "danger");
      }
    } else {
      $model->set_flash("Données invalides pour le changement de car.", "danger");
    }

    header("Location: " . BASE_URL . "/admin/Envoi_colis/liste_colis_envoyer");
    exit;
  }

  // Annule un envoi complet : les colis redeviennent disponibles
  public function annuler_envoi()
  {
    $model = new Envoie_colis();

    if (isset($_GET['id_car'], $_GET['date'])) {
      if ($model->annulerEnvoi($_GET['id_car'], $_GET['date'])) {
        $model->set_flash("L'envoi a été annulé, les colis sont de nouveau disponibles.", "success");
      } else {
        $model->set_flash("Erreur lors de l'annulation de l'envoi.", "danger");
      }
    } else {
      $model->set_flash("Aucun envoi sélectionné.", "danger");
    }

    header("Location: " . BASE_URL . "/admin/Envoi_colis/liste_colis_envoyer");
    exit;
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

  

    // ✅ Récupérer la liste des cars programmés sans doublons.
    // Un chef d'escale ne doit voir que les cars dont le départ est sa propre ville ;
    // l'Admin voit tous les cars programmés de la compagnie, toutes villes confondues.
    $liste_cars = $envoie_colis->getCarsDisponiblesAujourdhui();


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
