<?php
class Add_liste_trajets extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }


  public  function  index()
  {
    // instanciation 
    $add_liste_trajet = new Add_liste_trajet();

    if (isset($_POST["save"])) {
      $errors = $add_liste_trajet->saveTrajet();
    } else {
      $errors = [];
    }

    $liste = [];
    $liste_agence = [];
    $listeEscale = [];

    if (isset($_SESSION['droit'])) {
      $role = $_SESSION['droit'];

      if ($role === 'super_admin') {
        // SuperAdmin : voit tout
        $liste = $add_liste_trajet->SelectAllData('*', "trajet");
        $liste_agence = $add_liste_trajet->SelectAllData('*', "agence");
        $listeEscale = $add_liste_trajet->SelectAllData("*", "escale");
      } elseif ($role === 'Admin' && isset($_SESSION['id_compagnie'])) {
        // Admin : voit seulement ce qui est lié à sa compagnie
        $id_compagnie = $_SESSION['id_compagnie'];

        // Trajets liés à cette compagnie (si id_compagnie existe dans trajet)
        $liste = $add_liste_trajet->FetchSelectWheres(
          '*',
          'trajet',
          'id_compagnie = :id_compagnie',
          ['id_compagnie' => $id_compagnie]
        );

        // Agences liées à cette compagnie
        $liste_agence = $add_liste_trajet->FetchSelectWheres(
          '*',
          'agence',
          'id_compagnie = :id_compagnie',
          ['id_compagnie' => $id_compagnie]
        );

        // Escales liées à cette compagnie
        $listeEscale = $add_liste_trajet->FetchSelectWheres(
          '*',
          'escale',
          'id_compagnie = :id_compagnie',
          ['id_compagnie' => $id_compagnie]
        );
      } else {
        $add_liste_trajet->set_flash("Accès refusé ou session invalide", "danger");
        $add_liste_trajet->redirect("Login/index");
        return;
      }
    } else {
      $add_liste_trajet->set_flash("Vous devez vous connecter", "warning");
      $add_liste_trajet->redirect("Login/index");
      return;
    }

    // Envoi des données à la vue
    $this->view('add_liste_trajet', [
      'errors' => $errors,
      'listeEscale' => $listeEscale,
      'liste' => $liste,
      'liste_agence' => $liste_agence
    ]);
  }
}
