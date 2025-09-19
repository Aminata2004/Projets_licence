<?php
class Add_liste_horaire extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }

  public  function  index()
  {
    // instanciation 
    $add_liste_horaire = new Add_liste_horaires();
    // enregistrement des escales
    if (isset($_POST["save"])) {
      // var_dump($_POST);exit;
      $add_liste_horaire->saveHoraire();
    }

    // Récupération de l'identité de l'utilisateur

    $id_compagnie = $_SESSION["id_compagnie"] ?? null;

    if (isset($_SESSION['droit']) && $_SESSION['droit'] === 'Admin' && isset($_SESSION['id_compagnie'])) {
      $id_compagnie = $_SESSION['id_compagnie'];

      // On récupère uniquement les escales liées à cette compagnie
      $liste = $add_liste_horaire->FetchSelectWhereS(
        "*",
        "horaire",
        "id_compagnie = :id_compagnie",
        [":id_compagnie" => $id_compagnie]
      );
    } else {
      // L'administrateur voit tous les horaires
      $liste = $add_liste_horaire->SelectAllData('*', "horaire");
    }

    $this->view('admin/add_liste_horaire', ['liste' => $liste]);
  }
  public function add_permission()
  {
     // instanciation 
    $add_liste_horaire = new Add_liste_horaires();
    // enregistrement des escales
    if (isset($_POST["enregistre"])) {
      // var_dump($_POST);exit;
      $add_liste_horaire->savePermission();
    }

     $liste = $add_liste_horaire->SelectAllData(
        "*","permision" );

    $this->view('admin/add_permission',["liste"=>$liste]);
  }
  
}
