<?php
class Liste_du_jours extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }


  public function index()
  {
    $id_compagnie = $_SESSION['id_compagnie'];
    $idDepart = $_SESSION['ville'];
    $model = new Liste_du_jour();

    $resultats = $model->FetchSelectWheres(
      '*',
      'billets inner join client on billets.id_client = client.idClient',
      'billets.id_compagnie = :id_compagnie ORDER BY billets.idBillets DESC LIMIT 10',
      ['id_compagnie' => $id_compagnie]
    );
    $liste_horaires = $model->SelectAllData(
      '*',
      'horaire',
      'id_compagnie = :id_compagnie',
      ['id_compagnie' => $id_compagnie]
    );
    $destinations = $model->getDestinations($idDepart, $id_compagnie);

    $this->view('liste_du_jours', [
      'liste_du_jour' => $resultats,
      'liste_horaires' => $liste_horaires,
      'destinations' => $destinations
    ]);
  }
}
