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
    } elseif (isset($_POST["update"])) {
      $errors = $add_liste_trajet->updateTrajet();
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
        $add_liste_trajet->redirect("admin/Login/index");
        return;
      }
    } else {
      $add_liste_trajet->set_flash("Vous devez vous connecter", "warning");
      $add_liste_trajet->redirect("admin/Login/index");
      return;
    }

    if ($liste) {
      foreach ($liste as $trajet) {
          $escales = $add_liste_trajet->getEscalesByTrajet($trajet->idTrajet);
          $noms_escales = [];
          $ids_escales = [];
          foreach ($escales as $e) {
              $noms_escales[] = $e->escales;
              $ids_escales[] = $e->id_escale;
          }
          $trajet->escales_names = implode(" ➔ ", $noms_escales);
          $trajet->escales_ids = $ids_escales;
      }
    }

    // Envoi des données à la vue
    $this->view('admin/add_liste_trajet', [
      'errors' => $errors,
      'listeEscale' => $listeEscale,
      'liste' => $liste,
      'liste_agence' => $liste_agence
    ]);
  }

  public function delete($id)
  {
      $add_liste_trajet = new Add_liste_trajet();
      
      // Sécurité : vérifier que l'id n'est pas vide
      if (!empty($id)) {
          if ($add_liste_trajet->deleteTrajet($id)) {
              $add_liste_trajet->set_flash("Le trajet a été supprimé avec succès.", "success");
          } else {
              $add_liste_trajet->set_flash("Erreur lors de la suppression du trajet.", "danger");
          }
      }
      
      header("Location: " . BASE_URL . "/admin/Add_liste_trajets/index");
      exit;
  }
 
}
