<?php
class Cars_chauffeurs extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }

  public  function  index()
  {
    // instanciation 
    $cars_chauffeur = new Cars_chauffeur();
    // insertion 
    if (isset($_POST["save"])) {
      $errors = $cars_chauffeur->saveCare();
    } else {
      $errors = [];
    }
    if (isset($_SESSION['droit']) && $_SESSION['droit'] === 'Admin' && isset($_SESSION['id_compagnie'])) {
      $id_compagnie = $_SESSION['id_compagnie'];

      // Admin → uniquement les cars liés à sa compagnie
      $listeCar = $cars_chauffeur->FetchSelectWheres(
        "*",
        "car",
        "id_compagnie = :id_compagnie",
        [":id_compagnie" => $id_compagnie]
      );
    } else {
      // SuperAdmin ou autre → tous les cars
      $listeCar = $cars_chauffeur->SelectAllData('*', "car");
    }

    $this->view('cars_chauffeur', [
      'errors' => $errors ?? [],
      'listeCar' => $listeCar
    ]);
  }
}
