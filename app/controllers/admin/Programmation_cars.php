
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
      $listeTrajet = $programmation_car->customQuery("
            SELECT MIN(idProgrammer) AS idProgrammer, idDepart, idDestination
            FROM programmer
            WHERE id_compagnie = :id_compagnie
            GROUP BY idDepart, idDestination
        ", [':id_compagnie' => $id_compagnie]);


      // $listeTrajet = $programmation_car->SelectAllData(
      //   'MIN(idProgrammer) as idProgrammer, idDestination, idDepart',
      //   'programmer',
      //   'id_compagnie = :id_compagnie GROUP BY idDestination, idDepart',
      //   [':id_compagnie' => $id_compagnie]
      // );

      // Admin : référence des cars appartenant à sa compagnie
      $Select_car1 = $programmation_car->FetchSelectWheres(
        '*',
        'reference_car INNER JOIN car ON reference_car.id_car = car.id_car',
        'car.id_compagnie = :id_compagnie',
        [':id_compagnie' => $id_compagnie]
      );
    } else {
      // SuperAdmin ou autre : voit tout
      $listeCar = $programmation_car->FetchAllSelectWhere(
        '*',
        'car',
        'programmer_car = :programmer_car',
        [':programmer_car' => 'off']
      );

      $listeTrajet = $programmation_car->SelectAllData('*', "programmer");

      $Select_car1 = $programmation_car->SelectAllData("*", "reference_car INNER JOIN car ON reference_car.id_car = car.id_car");
    }

    $this->view('admin/programmation_car', [
      'listeTrajet' => $listeTrajet,
      'listeCar' => $listeCar,
      'Select_car1' => $Select_car1
    ]);
  }
}
// End of file Programmation_cars.php