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

    $this->view('admin/cars_chauffeur', [
      'errors' => $errors ?? [],
      'listeCar' => $listeCar
    ]);
  }
  public function update() {
     $cars_chauffeur = new Cars_chauffeur();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_car'];
            $data = [
                'numero_car' => $_POST['numero_car'],
                'matricule'  => $_POST['matricule'],
                'nbr_place'  => $_POST['nbr_place']
            ];

            $cars_chauffeur->updateCar($id, $data);
        $cars_chauffeur->set_flash('Car mise à jour avec succès', 'info');


            header("Location: " . BASE_URL . "/admin/Cars_chauffeurs/index");
            exit;
        }
    }


    public function delete($id) {
          $cars_chauffeur = new Cars_chauffeur();

        if ($cars_chauffeur->deleteCar($id)) {
            $cars_chauffeur->set_flash( "Véhicule supprimé avec succès.", 'info'); 
        } else {
           $cars_chauffeur->set_flash( "Erreur lors de la suppression du véhicule.", 'info'); 
           
        }

        header("Location: " . BASE_URL . "/admin/Cars_chauffeurs/index");
        exit;
    }


}
