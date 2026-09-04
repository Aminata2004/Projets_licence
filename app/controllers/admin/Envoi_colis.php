<?php
class Envoi_colis extends  Controller
{
  public function __construct()
  {
    $this->requirePermission('colis_envoi');
  }


  public  function  index()
  {
    date_default_timezone_set('Africa/Bamako');
    // recuperation des colis
    $envoie_colis = new Envoie_colis();
    $id_compagnie = $_SESSION['id_compagnie'];
    $ville_user = $_SESSION['ville'] ?? null;
    $id_agence_user = $_SESSION['id_agence'] ?? null;
    $isAdmin = in_array($_SESSION['droit'] ?? null, ['Admin', 'PDG', 'secretaire'], true);
    $liste_colis = $envoie_colis->FetchSelectcolis();

    // Filtre par id_agence (pas seulement localite_user) : deux gares d'une même compagnie
    // peuvent partager la même ville (ex. "Segou" Gare I et Gare II), auquel cas le simple
    // nom de ville ne suffit pas à distinguer "le car de MA gare" (cf.
    // ajout_id_agence_programmation_voyage.sql, même correctif déjà appliqué ailleurs :
    // Add_billet.php, Liste_du_jour.php). Un Admin n'a pas de gare fixe : il voit tous les
    // cars de la compagnie, comme déjà le cas dans Envoie_colis::getCarsDisponiblesAujourdhui().
    $condition = "programmation_voyage.id_compagnie = :id_compagnie
   AND TIMESTAMPDIFF(HOUR, programmation_voyage.date_enregistre, NOW()) < 24
   AND programmation_voyage.statut = 'active'";
    $params = [":id_compagnie" => $id_compagnie];

    if (!$isAdmin) {
      $condition .= " AND programmation_voyage.localite_user = :ville AND programmation_voyage.id_agence = :id_agence";
      $params[":ville"] = $ville_user;
      $params[":id_agence"] = $id_agence_user;
    }

    // horaire.id_heure est un entier (1, 2, 3...), pas une heure : la jointure doit se faire
    // sur horaire.heuredepart (vraie valeur TIME, comparable à programmation_voyage.id_horaire),
    // comme le fait déjà correctement Envoie_colis::getCarsDisponiblesAujourdhui(). Avec
    // id_heure, la jointure ne matchait quasiment jamais, donc listeprogrammer restait vide.
    $listeprogrammer = $envoie_colis->FetchWheresJoin(
      "*",
      "programmation_voyage
   INNER JOIN horaire ON horaire.heuredepart = programmation_voyage.id_horaire
                      AND horaire.id_compagnie = programmation_voyage.id_compagnie",
      $condition,
      $params
    );

    // envoi des colis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoi'])) {
      $colis_ids = $_POST['selected_colis'] ?? [];
      $id_car = $_POST['id_car_selectionner'] ?? null;

      if (!empty($colis_ids) && $id_car) {
        $envoie_colis->traiterEnvoi($colis_ids, $id_car);
        $envoie_colis->set_flash("Colis envoyés avec succès", "primary");

        header("Location: " . BASE_URL . "/admin/Envoi_colis/index");
        exit;
      } else {
        $envoie_colis->set_flash("Veuillez sélectionner au moins un colis et un car.", "danger");

        header("Location: " . BASE_URL . "/admin/Envoi_colis/index");
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
    // Discriminant calculé : numero_car/numero_camion stockent en réalité un
    // id_car/id_camion (nom trompeur, convention historique) ; l'un des deux est
    // toujours NULL selon que le lot a été envoyé par car ou par camion.
    $liste_colis_envoyer = $envoie_colis->FetchSelectWheres(
      "*, CASE WHEN numero_car IS NOT NULL THEN 'car' ELSE 'camion' END AS type_vehicule,
          COALESCE(numero_car, numero_camion) AS id_vehicule",
      'ligne_envoi',
      'id_compagnie = :id_compagnie ORDER BY id_ligne_envoi DESC LIMIT 10',
      [':id_compagnie' => $id_compagnie]
    );

    $this->view('admin/liste_colis_envoyer', ['liste_colis_envoyer' => $liste_colis_envoyer]);
  }

  public function details_colis_envoyer()
  {
    $model = new Envoie_colis();
    // type=camion pour un lot camion ; repli sur 'car' par defaut (compat. avec
    // d'anciens liens ?id_car=...&date=... generes avant l'ajout des camions).
    $type = $_GET['type'] ?? 'car';
    $id_vehicule = $_GET['id_vehicule'] ?? ($_GET['id_car'] ?? null);
    $date_envoi = $_GET['date'] ?? null;

    if ($id_vehicule && $date_envoi) {
      if ($type === 'camion') {
        $liste_colis = $model->getColisParCamionEtDate($id_vehicule, $date_envoi);
        $liste_vehicules = $model->getCamionsActifs();
      } else {
        $liste_colis = $model->getColisParCarEtDate($id_vehicule, $date_envoi);
        $liste_vehicules = $model->getCarsDisponiblesAujourdhui();
      }

      $this->view("admin/details_colis_envoyer", [
        "liste_colis" => $liste_colis,
        "type_vehicule" => $type,
        "id_vehicule" => $id_vehicule,
        "date_envoi" => $date_envoi,
        "liste_vehicules" => $liste_vehicules
      ]);
    } else {
      $model->set_flash("Aucun véhicule sélectionné", "danger");
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

  // Déplace un colis déjà envoyé vers un autre camion (miroir de changer_car()).
  // Le changement reste intra-type : un envoi camion ne peut être réaffecté qu'à
  // un autre camion, pas basculé vers un car (et inversement).
  public function changer_camion()
  {
    $model = new Envoie_colis();

    if (
      $_SERVER['REQUEST_METHOD'] === 'POST'
      && isset($_POST['id_colis'], $_POST['ancien_id_camion'], $_POST['ancienne_date'], $_POST['nouveau_id_camion'])
    ) {
      if ($model->changerCamionColis(
        $_POST['id_colis'],
        $_POST['ancien_id_camion'],
        $_POST['ancienne_date'],
        $_POST['nouveau_id_camion']
      )) {
        $model->set_flash("Le camion d'envoi du colis a été modifié avec succès.", "success");
      } else {
        $model->set_flash("Erreur lors du changement de camion.", "danger");
      }
    } else {
      $model->set_flash("Données invalides pour le changement de camion.", "danger");
    }

    header("Location: " . BASE_URL . "/admin/Envoi_colis/liste_colis_envoyer");
    exit;
  }

  // Annule un envoi complet : les colis redeviennent disponibles
  public function annuler_envoi()
  {
    $model = new Envoie_colis();
    $type = $_GET['type'] ?? 'car';
    $id_vehicule = $_GET['id_vehicule'] ?? ($_GET['id_car'] ?? null);
    $date_envoi = $_GET['date'] ?? null;

    if ($id_vehicule && $date_envoi) {
      $ok = $type === 'camion'
        ? $model->annulerEnvoiCamion($id_vehicule, $date_envoi)
        : $model->annulerEnvoi($id_vehicule, $date_envoi);

      if ($ok) {
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
    $camion_selectionne = null;

    // Liste de tous les colis disponibles
    $liste_colis = $envoie_colis->FetchSelectcolis();



    // ✅ Récupérer la liste des cars programmés sans doublons.
    // Un chef d'escale ne doit voir que les cars dont le départ est sa propre ville ;
    // l'Admin voit tous les cars programmés de la compagnie, toutes villes confondues.
    $liste_cars = $envoie_colis->getCarsDisponiblesAujourdhui();

    // Camions actifs de la compagnie, sans notion de programmation du jour
    // (contrairement aux cars ci-dessus) : un camion de colis n'a pas de trajet
    // passager, il est simplement disponible tant qu'il est actif.
    $liste_camions = $envoie_colis->getCamionsActifs();


    // Si un car est sélectionné depuis un GET ou POST
    $id_car = $_GET['id_car'] ?? ($_POST['id_car_selectionner'] ?? null);
    if ($id_car) {
      $car_selectionne = $envoie_colis->getCarById($id_car);
    }

    $id_camion = $_GET['id_camion'] ?? ($_POST['id_camion_selectionner'] ?? null);
    if ($id_camion) {
      $camion_selectionne = $envoie_colis->getCamionById($id_camion);
    }

    // Traitement de l'envoi des colis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
      $colis_ids = $_POST['selected_colis'] ?? [];
      $id_car = $_POST['id_car_selectionner'] ?? null;
      $id_camion = $_POST['id_camion_selectionner'] ?? null;

      if (!empty($colis_ids) && !empty($id_camion)) {
        $envoie_colis->traiterEnvoiCamion($colis_ids, $id_camion);
        $envoie_colis->set_flash("Colis envoyés avec succès", "primary");
        header("Location: " . BASE_URL . "/admin/Envoi_colis/envoi_colis");
        exit;
      } elseif (!empty($colis_ids) && !empty($id_car)) {
        $envoie_colis->traiterEnvoi1($colis_ids, $id_car);
        $envoie_colis->set_flash("Colis envoyés avec succès", "primary");
        header("Location: " . BASE_URL . "/admin/Envoi_colis/envoi_colis");
        exit;
      } else {
        $envoie_colis->set_flash("Veuillez sélectionner au moins un colis et un véhicule (car ou camion).", "danger");
        header("Location: " . BASE_URL . "/admin/Envoi_colis/envoi_colis");
        exit;
      }
    }


    // Envoi à la vue
    $this->view("admin/ajouter_colis_envoi", [
      'liste_colis' => $liste_colis,
      'liste_cars' => $liste_cars, // ✅ On envoie la liste des cars
      'liste_camions' => $liste_camions,
      'car_selectionne' => $car_selectionne,
      'camion_selectionne' => $camion_selectionne
    ]);
  }
}
