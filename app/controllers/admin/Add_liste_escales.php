<?php
class Add_liste_escales extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }


  public  function  index()
  {
    // instanciation 
    $add_liste_escale = new Add_liste_escale();
    // enregistrement des escales
    if (isset($_POST["save"])) {
      // var_dump($_POST);exit;
      $add_liste_escale->savescale();
    }

    $liste = [];

    if (isset($_SESSION['droit']) && $_SESSION['droit'] === 'Admin' && isset($_SESSION['id_compagnie'])) {
      $id_compagnie = $_SESSION['id_compagnie'];

      // On récupère uniquement les escales liées à cette compagnie
      $liste = $add_liste_escale->FetchSelectWheres(
        '*',
        'escale',
        'id_compagnie = :id_compagnie',
        ['id_compagnie' => $id_compagnie]
      );
    } else {
      // SuperAdmin ou autres rôles → voir toutes les escales
      $liste = $add_liste_escale->SelectAllData('*', 'escale');
    }

    // Envoi à la vue
    $this->view('admin/add_liste_escale', ["liste" => $liste]);
  }
}
