<?php
class Chauffeurs_cars extends  Controller
{
  public function __construct()
  {
    $this->requirePermission('Configuration_gestion_car/chauffeur');
  }

  public  function  index()
  {
    // instanciation 
    $chauffeurs_car = new Chauffeurs_car();
    // insertion 
    if (isset($_POST["save"])) {
      $errors = $chauffeurs_car->saveChauffeur();
    } else {
      $errors = [];
    }
    // la recuperation
    // recuperation des cars et camions
    // LEFT JOIN double (et non INNER JOIN) : un chauffeur affecte a un camion n'a
    // pas de ligne `car` a joindre (id_car est NULL pour lui), et inversement.
    // Avec un INNER JOIN, ces chauffeurs disparaitraient silencieusement de la liste.
    $selectChauffeur = "chauffeur.*, car.numero_car AS numero_car, camion.numero_camion AS numero_camion";
    $joinChauffeur = "chauffeur
        LEFT JOIN car ON chauffeur.type_vehicule = 'car' AND chauffeur.id_car = car.id_car
        LEFT JOIN camion ON chauffeur.type_vehicule = 'camion' AND chauffeur.id_camion = camion.id_camion";

    if (isset($_SESSION['droit']) && in_array($_SESSION['droit'], ['Admin', 'PDG', 'secretaire'], true) && isset($_SESSION['id_compagnie'])) {
      $id_compagnie = $_SESSION['id_compagnie'];

      // Admin → uniquement les cars de sa compagnie
      $listeCar = $chauffeurs_car->FetchSelectWhereS(
        "*",
        "car",
        "id_compagnie = :id_compagnie",
        [":id_compagnie" => $id_compagnie]
      );

      // Admin → uniquement les camions de sa compagnie
      $listeCamion = $chauffeurs_car->FetchSelectWheres(
        "*",
        "camion",
        "id_compagnie = :id_compagnie",
        [":id_compagnie" => $id_compagnie]
      );

      // Admin → uniquement les chauffeurs de sa compagnie (filtre desormais sur
      // chauffeur.id_compagnie directement, plus via car.id_compagnie -- un
      // chauffeur de camion n'a pas de ligne `car` a filtrer)
      $listeChaufeur = $chauffeurs_car->FetchSelectWheres(
        $selectChauffeur,
        $joinChauffeur,
        "chauffeur.id_compagnie = :id_compagnie",
        [":id_compagnie" => $id_compagnie]
      );
    } else {
      // SuperAdmin ou autres → toutes les données
      $listeCar = $chauffeurs_car->SelectAllData('*', "car");
      $listeCamion = $chauffeurs_car->SelectAllData('*', "camion");
      $listeChaufeur = $chauffeurs_car->SelectAllData($selectChauffeur, $joinChauffeur);
    }

    $this->view('admin/chauffeur_cars', [
      'errors' => $errors ?? [],
      'listeCar' => $listeCar,
      'listeCamion' => $listeCamion,
      'listeChaufeur' => $listeChaufeur
    ]);
  }

  public function update()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id_chauffeur'];
      
      $chauffeurs_car = new Chauffeurs_car();
      
      // Validation du numéro
      $numero = trim($_POST['numero']);
      if (strlen($numero) !== 8 || !preg_match('/^[6789]\d{7}$/', $numero)) {
          $chauffeurs_car->set_flash('Le numéro de téléphone doit contenir exactement 8 chiffres et commencer par 6, 7, 8 ou 9.', 'danger');
          header("Location: " . BASE_URL . "/admin/Chauffeurs_cars/index");
          exit;
      }

      $estCamion = isset($_POST['est_camion']) && $_POST['est_camion'] === '1';
      $id_car = $estCamion ? null : ($_POST['id_car'] ?? null);
      $id_camion = $estCamion ? ($_POST['id_camion'] ?? null) : null;

      if ($estCamion && empty($id_camion)) {
        $chauffeurs_car->set_flash('Le camion est obligatoire.', 'danger');
        header("Location: " . BASE_URL . "/admin/Chauffeurs_cars/index");
        exit;
      }
      if (!$estCamion && empty($id_car)) {
        $chauffeurs_car->set_flash('Le car est obligatoire.', 'danger');
        header("Location: " . BASE_URL . "/admin/Chauffeurs_cars/index");
        exit;
      }

      $data = [
        'nom_prenom' => $_POST['nom_prenom'],
        'numero'     => $_POST['numero'],
        'id_car'     => $id_car,
        'id_camion'  => $id_camion,
        'type_vehicule' => $estCamion ? 'camion' : 'car'
      ];

      $chauffeurs_car = new Chauffeurs_car();
      $chauffeurs_car->updateChauffeur($id, $data);
      $chauffeurs_car->set_flash('Chauffeur mise à jour avec succès', 'info');
      header("Location: " . BASE_URL . "/admin/Chauffeurs_cars/index");
      exit;
    }
  }

  public function delete($id)
  {
    $chauffeurs_car = new Chauffeurs_car();

    if ($chauffeurs_car->deleteChauffeur($id)) {

      $chauffeurs_car->set_flash("Chauffeur supprimé avec succès.", 'info');
    } else {

      $chauffeurs_car->set_flash("Erreur lors de la suppression du chauffeur.", 'info');
    }

    header("Location: " . BASE_URL . "/admin/Chauffeurs_cars/index");
    exit;
  }
}
