<?php
class Liste_de_demains extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }
  public  function  index()
  {
    date_default_timezone_set('Africa/Bamako');
    $id_compagnie = $_SESSION['id_compagnie'];
    // Admin n'a pas de gare fixe en session : il voit les billets de toute la compagnie.
    // numeroGare précise la gare exacte (une ville peut avoir plusieurs gares).
    $isAdmin = ($_SESSION['droit'] ?? null) === 'Admin';
    $idDepart = $isAdmin ? null : ($_SESSION['ville'] ?? null);
    $numeroGare = $isAdmin ? null : ($_SESSION['numero_gare'] ?? null);
    $model = new Liste_du_jour();
    $liste_horaires = $model->FetchSelectWheres(
      '*',
      'horaire',
      'id_compagnie = :id_compagnie',
      ['id_compagnie' => $id_compagnie]
    );
    $destinations = $model->getDestinations($idDepart, $id_compagnie, $numeroGare);

    $where = 'billets.id_compagnie = :id_compagnie';
    $params = ['id_compagnie' => $id_compagnie];
    if ($idDepart !== null) {
      $where .= ' AND billets.departId = :depart';
      $params['depart'] = $idDepart;
    }
    if ($numeroGare !== null) {
      $where .= ' AND billets.num_gare = :numeroGare';
      $params['numeroGare'] = $numeroGare;
    }

    $resultats = $model->FetchSelectWheres(
      '*',
      'billets inner join client on billets.id_client = client.idClient',
      $where,
      $params
    );

    $this->view('admin/liste_de_demain', [
      'liste_demain' => $resultats,
      'liste_horaires' => $liste_horaires,
      'destinations' => $destinations
    ]);
  }
}
