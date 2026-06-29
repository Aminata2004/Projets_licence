<?php
class Chauffeurs_cars extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
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
    // recuperation des cars
    if (isset($_SESSION['droit']) && $_SESSION['droit'] === 'Admin' && isset($_SESSION['id_compagnie'])) {
      $id_compagnie = $_SESSION['id_compagnie'];

      // Admin → uniquement les cars de sa compagnie
      $listeCar = $chauffeurs_car->FetchSelectWhereS(
        "*",
        "car",
        "id_compagnie = :id_compagnie",
        [":id_compagnie" => $id_compagnie]
      );

      // Admin → uniquement les chauffeurs liés aux cars de sa compagnie
      $listeChaufeur = $chauffeurs_car->FetchSelectWheres(
        "*",
        "chauffeur INNER JOIN car ON chauffeur.id_car = car.id_car",
        "car.id_compagnie = :id_compagnie",
        [":id_compagnie" => $id_compagnie]
      );
    } else {
      // SuperAdmin ou autres → toutes les données
      $listeCar = $chauffeurs_car->SelectAllData('*', "car");
      $listeChaufeur = $chauffeurs_car->SelectAllData('*', "chauffeur INNER JOIN car ON chauffeur.id_car = car.id_car");
    }

    $this->view('admin/chauffeur_cars', [
      'errors' => $errors ?? [],
      'listeCar' => $listeCar,
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

      $data = [
        'nom_prenom' => $_POST['nom_prenom'],
        'numero'     => $_POST['numero'],
        'id_car'     => $_POST['id_car']
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
