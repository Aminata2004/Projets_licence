<?php
class Liste_de_demains extends Controller
{
  public function __construct()
  {
    $this->requirePermission('Billets_apercue');
  }

  public function index()
  {
    date_default_timezone_set('Africa/Bamako');
    $id_compagnie = $_SESSION['id_compagnie'];
    // Admin n'a pas de gare fixe en session : il voit les billets de toute la compagnie.
    // numeroGare précise la gare exacte (une ville peut avoir plusieurs gares).
    $isAdmin    = in_array($_SESSION['droit'] ?? null, ['Admin', 'PDG', 'secretaire'], true);
    $idDepart   = $isAdmin ? null : ($_SESSION['ville'] ?? null);
    $numeroGare = $isAdmin ? null : ($_SESSION['numero_gare'] ?? null);
    $model = new Liste_du_jour();

    $liste_horaires = $model->FetchSelectWheres(
      '*',
      'horaire',
      'id_compagnie = :id_compagnie',
      ['id_compagnie' => $id_compagnie]
    );
    $destinations = $model->getDestinations($idDepart, $id_compagnie, $numeroGare);

    // "Liste à venir" : tous les billets dont le jourVoyage est strictement futur
    // (ou aujourd'hui s'ils ne sont pas encore embarqués) et qui ne sont pas annulés.
    // Cela couvre toute la fenêtre de réservation étendue à 7 jours.
    $where  = 'billets.id_compagnie = :id_compagnie'
            . ' AND billets.jourVoyage >= CURDATE()'
            . " AND (billets.status_billets IS NULL OR billets.status_billets NOT IN ('annule', 'annulation_en_cours'))"
            . " AND (billets.statut_embarquement IS NULL OR billets.statut_embarquement != 'embarque')";
    $params = ['id_compagnie' => $id_compagnie];

    if ($idDepart !== null) {
      $where .= ' AND billets.departId = :depart';
      $params['depart'] = $idDepart;
    }
    if ($numeroGare !== null) {
      $where .= ' AND billets.num_gare = :numeroGare';
      $params['numeroGare'] = $numeroGare;
    }

    // Filtres optionnels par destination / heure (GET)
    if (!empty($_GET['destination'])) {
      $where .= ' AND billets.destinationId = :destination';
      $params['destination'] = $_GET['destination'];
    }
    if (!empty($_GET['heure'])) {
      $where .= ' AND billets.Heur_departs = :heure';
      $params['heure'] = $_GET['heure'];
    }

    $resultats = $model->FetchSelectWheres(
      '*',
      'billets inner join client on billets.id_client = client.idClient',
      $where . ' ORDER BY billets.jourVoyage ASC, billets.Heur_departs ASC',
      $params
    );

    $this->view('admin/liste_de_demain', [
      'liste_demain'   => $resultats,
      'liste_horaires' => $liste_horaires,
      'destinations'   => $destinations,
    ]);
  }
}
