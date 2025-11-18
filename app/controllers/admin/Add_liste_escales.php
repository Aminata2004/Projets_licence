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

  public function update()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id_escale'] ?? null;
      $nom = trim($_POST['escales'] ?? '');
      $add_liste_escale = new Add_liste_escale();

      if ($id && $nom !== '') {

        $add_liste_escale->updateEscale($id, $nom);
        $add_liste_escale->set_flash('Escale mise à jour avec succès', 'info');
      } else {
        $add_liste_escale->set_flash('veuillez remplir tous les champs', 'info');
      }

      header("Location: " . BASE_URL . "/admin/Add_liste_escales/index");
      exit;
    }
  }

  public function delete($id)
  {
    $add_liste_escale = new Add_liste_escale();
    if ($add_liste_escale->deleteEscale($id)) {

      $add_liste_escale->set_flash('Escale supprimée avec succès', 'info');
    } else {

      $add_liste_escale->set_flash('Erreur lors de la suppression', 'info');
    }
    header("Location: " . BASE_URL . "/admin/Add_liste_escales/index");
    exit;
  }
}
