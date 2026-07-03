
<?php
class Programmation_cars extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }

  public  function  index()
  {
    // instanciation 
    $programmation_car = new Programmation_car();
    // // enregistrement des escales
    if (isset($_POST["programmer_car"])) {

      $programmation_car->Programmer_car();
    }
    if (isset($_SESSION['droit']) && $_SESSION['droit'] === 'Admin' && isset($_SESSION['id_compagnie'])) {
      $id_compagnie = $_SESSION['id_compagnie'];

      // Admin : liste des cars non programmés appartenant à sa compagnie
      $listeCar = $programmation_car->FetchSelectWheres(
        '*',
        'car',
        'programmer_car = :programmer_car AND id_compagnie = :id_compagnie',
        [':programmer_car' => 'off', ':id_compagnie' => $id_compagnie]
      );

      // Admin : liste des trajets appartenant à sa compagnie
      $listeTrajet = $programmation_car->FetchSelectWheres(
        "programmer.idProgrammer,
     depart.localite AS depart,
     depart.numeroGare AS gareDepart,
     destination.localite AS destination,
     destination.numeroGare AS gareDestination",
        "programmer
     INNER JOIN agence AS depart ON programmer.idDepart = depart.idAgence
     INNER JOIN agence AS destination ON programmer.idDestination = destination.idAgence",
        "programmer.id_compagnie = :id_compagnie
     GROUP BY programmer.idDepart, programmer.idDestination",
        [':id_compagnie' => $id_compagnie]
      );

      // Admin : référence des cars appartenant à sa compagnie
      $Select_car1 = $programmation_car->FetchSelectWheres(
        '*',
        'reference_car INNER JOIN car ON reference_car.id_car = car.id_car',
        'car.id_compagnie = :id_compagnie',
        [':id_compagnie' => $id_compagnie]
      );
    } else {
      // SuperAdmin ou autre : voit tout
      $listeCar = $programmation_car->FetchSelectWheres(
        '*',
        'car',
        'programmer_car = :programmer_car',
        [':programmer_car' => 'off']
      );
      $id_compagnie = $_SESSION['id_compagnie'];



      $listeTrajet = $programmation_car->FetchSelectWheres(
        "programmer.idProgrammer,
     depart.localite AS depart,
     depart.numeroGare AS gareDepart,
     destination.localite AS destination,
     destination.numeroGare AS gareDestination",
        "programmer
     INNER JOIN agence AS depart ON programmer.idDepart = depart.idAgence
     INNER JOIN agence AS destination ON programmer.idDestination = destination.idAgence",
        "programmer.id_compagnie = :id_compagnie
     GROUP BY programmer.idDepart, programmer.idDestination",
        [':id_compagnie' => $id_compagnie]
      );



      $Select_car1 = $programmation_car->SelectAllData("*", "reference_car INNER JOIN car ON reference_car.id_car = car.id_car");
    }


    $this->view('admin/programmation_car', [
      'listeTrajet' => $listeTrajet,
      'listeCar' => $listeCar,
      'Select_car1' => $Select_car1
    ]);
  }

  function details($id_car)
  {
    $programmation_car = new Programmation_car();

    // Récupérer les détails du car programmé
    $details = $programmation_car->FetchSelectWheres(
      "car.numero_car, car.nbr_place, programmer.idProgrammer, depart.localite AS depart, destination.localite AS destination",
      "car
       INNER JOIN liaison_car_trajet ON car.id_car = liaison_car_trajet.id_car
        INNER JOIN programmer ON liaison_car_trajet.id_trajets = programmer.idProgrammer
       INNER JOIN agence AS depart ON programmer.idDepart = depart.idAgence
       INNER JOIN agence AS destination ON programmer.idDestination = destination.idAgence",
      "car.id_car = :id_car",
      [':id_car' => $id_car]
    );

    $this->view('admin/details_programmation_car', ['details' => $details]);
  }

  // Ajoute un ou plusieurs trajets supplémentaires à un car déjà programmé
  function ajouter_trajet()
  {
    $programmation_car = new Programmation_car();

    if (isset($_POST['ajouter_trajet'])) {
      $programmation_car->ajouterTrajet();
    }

    header("Location: " . BASE_URL . "/admin/Programmation_cars/index");
    exit;
  }

  // Supprime la programmation d'un car (déprogramme le car et retire ses trajets affectés)
  function supprimer($id_car)
  {
    $programmation_car = new Programmation_car();

    if (!empty($id_car)) {
      if ($programmation_car->supprimerProgrammation($id_car)) {
        $programmation_car->set_flash("La programmation du car a été supprimée avec succès.", "success");
      } else {
        $programmation_car->set_flash("Erreur lors de la suppression de la programmation.", "danger");
      }
    }

    header("Location: " . BASE_URL . "/admin/Programmation_cars/index");
    exit;
  }
}
// End of file Programmation_cars.php