<?php
class Add_liste_horaire extends  Controller
{
  public function __construct()
  {
    // add_permission() gère la création de permissions elle-même (écran méta), pas les
    // horaires : réservée à Admin/super_admin comme l'écran d'assignation (Permissions.php),
    // pas à Configuration_gestion_horaire qui protège les vraies actions horaire.
    $segments = isset($_GET['url']) ? explode('/', rtrim($_GET['url'], '/')) : [];
    if (in_array('add_permission', $segments, true)) {
      $this->requireLogin();
      if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'super_admin'], true)) {
        (new Configuration())->set_flash("Accès refusé.", "danger");
        header("Location: " . BASE_URL . "/admin/Homes/home");
        exit;
      }
    } else {
      $this->requirePermission('Configuration_gestion_horaire');
    }
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

    if (isset($_SESSION['droit']) && in_array($_SESSION['droit'], ['Admin', 'PDG', 'secretaire'], true) && isset($_SESSION['id_compagnie'])) {
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
  public function edit()
  {
    $add_liste_horaire = new Add_liste_horaires();
    if (isset($_POST['edit'])) {
      $add_liste_horaire->editHoraire([
        'id_heure' => $_POST['id_heure'],
        'heuredepart' => $_POST['heuredepart'],
      ]);
    }
    header("Location: " . BASE_URL . "/admin/Add_liste_horaire");
    exit;
  }

  public function delete($id_heure = null)
  {
    if ($id_heure) {
      $add_liste_horaire = new Add_liste_horaires();
      $add_liste_horaire->deleteHoraire($id_heure);
    }
    header("Location: " . BASE_URL . "/admin/Add_liste_horaire");
    exit;
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

    // Complète le catalogue avec les permissions par défaut manquantes (ex: table
    // restée incomplète depuis une ancienne version) avant d'afficher la liste : cet
    // écran est justement celui qui doit refléter le catalogue complet.
    (new Permission())->seedPermissionsParDefautSiVide();

     $liste = $add_liste_horaire->SelectAllData(
        "*","permision" );

    $this->view('admin/add_permission',["liste"=>$liste]);
  }
  
}
