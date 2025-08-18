<?php
class Compagnies extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }


  public function index()
  {
    // Instanciation du modèle
    $compagnie = new Compagnie();

    // Traitement du formulaire
    if (isset($_POST["save"])) {
      // Appel de la méthode d'enregistrement
      $compagnie->saveCompagnie($_FILES['logo'] ?? null);
    }

    // Récupération des données à afficher
    $liste = $compagnie->SelectAllData('*', "compagnie");

    // Affichage de la vue
    $this->view('admin/compagnies', ['liste' => $liste]);
  }


  // fonction pour la modification des compagnie
  public function edit()
  {
    $compagnie = new Compagnie();

    if (isset($_POST['edit'])) {

      extract($_POST);
      $nom_compagnie = $_POST["nom_compagnie"];
      $libele = $_POST["libele"];
      $slogant = $_POST["slogant"];
      $id_compagnie = $_POST["id_compagnie"];
      $compagnie->editCompagnie(['id_compagnie' => $id_compagnie, 'nom_compagnie' => $nom_compagnie, 'libele' => $libele, 'slogant' => $slogant]);
      header("Location: " . BASE_URL . "/admin/Compagnies/index");
      exit;
    }
  }

  public function delete($id)
  {
    $compagnie = new Compagnie();
    // Définir la requête de suppression et les paramètres
    $sql = 'DELETE FROM compagnie WHERE id_compagnie = :id';
    $params = [':id' => $id];
    $result = $compagnie->insertion_update_simples($sql, $params);
    if ($result->rowCount() > 0) {
      //$compagnie->set_flash("Suppression réussie", 'success');
      //     header("Location: " . ROOT . "/compagnies/index");
      // exit;
    }
    header("Location: " . BASE_URL . "/admin/Compagnies/index");
    exit;
  }

  // limitation de place 
  public function place_limite()
  {
    // Instanciation du modèle
    $compagnie = new Compagnie();
    $liste_place = $compagnie->SelectAllData('*', "place_minumale");

    $this->view('admin/place_limite', ['liste_place' => $liste_place]);
  }

  public function edit1()
  {
    $compagnie = new Compagnie();

    if (isset($_POST['edit'])) {
      $place_minumale = $_POST["place_minumale"];
      $id = $_POST["id_place_minumale"]; // Récupération de l’ID depuis le formulaire

      $compagnie->editPlace([
        'id_place_minumale' => $id,
        'place_minumale' => $place_minumale
      ]);

      header("Location: " . BASE_URL . "/admin/Compagnies/place_limite");
      exit;
    }
  }
}
