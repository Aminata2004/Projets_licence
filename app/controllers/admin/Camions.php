<?php
class Camions extends Controller
{
  public function __construct()
  {
    $this->requirePermission('Configuration_gestion_car/chauffeur');
  }

  public function index()
  {
    $camion = new Camion();
    if (isset($_POST["save"])) {
      $errors = $camion->saveCamion();
    } else {
      $errors = [];
    }

    if (isset($_SESSION['droit']) && in_array($_SESSION['droit'], ['Admin', 'PDG', 'secretaire'], true) && isset($_SESSION['id_compagnie'])) {
      $id_compagnie = $_SESSION['id_compagnie'];

      // Admin → uniquement les camions liés à sa compagnie
      $listeCamion = $camion->FetchSelectWheres(
        "*",
        "camion",
        "id_compagnie = :id_compagnie",
        [":id_compagnie" => $id_compagnie]
      );
    } else {
      // SuperAdmin ou autre → tous les camions
      $listeCamion = $camion->SelectAllData('*', "camion");
    }

    $this->view('admin/camions', [
      'errors' => $errors ?? [],
      'listeCamion' => $listeCamion
    ]);
  }

  public function update() {
    $camion = new Camion();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id_camion'];
      $data = [
        'numero_camion' => $_POST['numero_camion'],
        'matriculle'    => $_POST['matriculle'],
        'actif'         => $_POST['actif'] ?? 'on'
      ];

      $camion->updateCamion($id, $data);
      $camion->set_flash('Camion mis à jour avec succès', 'info');

      header("Location: " . BASE_URL . "/admin/Camions/index");
      exit;
    }
  }

  public function delete($id) {
    $camion = new Camion();

    if ($camion->deleteCamion($id)) {
      $camion->set_flash("Camion supprimé avec succès.", 'info');
    } else {
      $camion->set_flash("Erreur lors de la suppression du camion.", 'info');
    }

    header("Location: " . BASE_URL . "/admin/Camions/index");
    exit;
  }

}
