<?php
class Programmer_voyages extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }

  public  function  index()
  {
    $programmer_voyage = new Programmer_voyage();

    if (isset($_SESSION['droit'])) {
      $role = $_SESSION['droit'];
      if ($role === 'Admin' || $role === 'Admin_regionale' && isset($_SESSION['id_compagnie'])) {
        // Admin : voit seulement ce qui est lié à sa compagnie
        $id_compagnie = $_SESSION['id_compagnie'];

        // Escales liées à cette compagnie
        $listeProgrammer = $programmer_voyage->FetchSelectWheres(
          '*',
          'programmer',
          'id_compagnie = :id_compagnie',
          ['id_compagnie' => $id_compagnie]
        );
      } else {
        $programmer_voyage->set_flash("Accès refusé ou session invalide", "danger");
        $programmer_voyage->redirect("admin/Login/index");
        return;
      }
    } else {
      $programmer_voyage->set_flash("Vous devez vous connecter", "warning");
      $programmer_voyage->redirect("admin/Login/index");
      return;
    }
    $this->view('admin/programmer_voyage', ['listeProgrammer' => $listeProgrammer]);
  }



  // enregistrement du programmer de voyage
  public function add_programmer()
  {
    // instanciation 
    $add_liste_trajet = new Add_liste_trajet();
    $programmer_voyage = new Programmer_voyage();

    if (isset($_POST['enregistre'])) {
      $programmer_voyage->saveProgrammer();
    }
$id_compagnie = $_SESSION['id_compagnie'] ;
    $liste_agence = [];
    $listeEscale = [];
    $listehoraire = [];
    $listehoraire = $programmer_voyage->FetchSelectWhereS(
        "*",
        "horaire",
        "id_compagnie = :id_compagnie",
        [":id_compagnie" => $id_compagnie]
      );
    if (isset($_SESSION['droit'])) {
      $role = $_SESSION['droit'];

      if ($role === 'super_admin') {
        // SuperAdmin : voit tout

        $liste_agence = $add_liste_trajet->SelectAllData('*', "agence");
        $listeEscale = $add_liste_trajet->SelectAllData("*", "escale");
      } elseif ($role === 'Admin' || $role === 'Admin_regionale' && isset($_SESSION['id_compagnie'])) {
        // Admin : voit seulement ce qui est lié à sa compagnie
        $id_compagnie = $_SESSION['id_compagnie'];

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
      $add_liste_trajet->redirect("admin/Login/index");
      return;
    }

    // Envoi des données à la vue
    $this->view('admin/add_programmer', [

      'listeEscale' => $listeEscale,
      'listehoraire' => $listehoraire,
      'liste_agence' => $liste_agence
    ]);
  }

  public function edit()
  {
    $programmer_voyage = new Programmer_voyage();

    if (isset($_POST['edit'])) {
      $data = [
        "prix" => $_POST['prix'],
        "idProgrammer" => $_POST['idProgrammer']
      ];

      $programmer_voyage->editPrix($data);
      header("Location: " . BASE_URL . "/admin/Programmer_voyages/index");
      exit;
    }
  }
}
