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
}
