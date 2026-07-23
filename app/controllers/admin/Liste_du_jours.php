<?php

use Dompdf\Dompdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;                 // ← énumération 5.x
use Endroid\QrCode\RoundBlockSizeMode;

class Liste_du_jours extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }


  public function index()
  {
    date_default_timezone_set('Africa/Bamako');
    $id_compagnie = $_SESSION['id_compagnie'];
    // Admin n'a pas de gare fixe en session : il voit les billets de toute la compagnie.
    // Pour les autres rôles, numeroGare précise la gare exacte : une ville peut avoir
    // plusieurs gares (ex. "Segou" Gare I et Gare II), le nom de ville seul ne suffit pas.
    $isAdmin = ($_SESSION['droit'] ?? null) === 'Admin';
    $idDepart = $isAdmin ? null : ($_SESSION['ville'] ?? null);
    $numeroGare = $isAdmin ? null : ($_SESSION['numero_gare'] ?? null);
    $model = new Liste_du_jour();

    $resultats = $model->listeBillets($idDepart, $numeroGare);
    $liste_horaires = $model->FetchSelectWheres(
      '*',
      'horaire',
      'id_compagnie = :id_compagnie',
      ['id_compagnie' => $id_compagnie]
    );
    $destinations = $model->getDestinations($idDepart, $id_compagnie, $numeroGare);

    $this->view('admin/liste_du_jours', [
      'liste_du_jour' => $resultats,
      'liste_horaires' => $liste_horaires,
      'destinations' => $destinations
    ]);
  }

  // public function recu($idBillets)
  // {
  //   $colisModel = new Livraisons_colis();
  //   $billets = new Liste_du_jour();

  //   $Billets = $billets->listeBillets();
  //   $compagnie = $colisModel->infoCompagnie($_SESSION['id_compagnie'] ?? 0);

  //   if (!$Billets || !$compagnie) {
  //     $colisModel->set_flash('bi ou compagnie introuvable.', 'danger');
  //     header('Location: ' . BASE_URL . '/livraison_colis');
  //     exit;
  //   }

  //   // Préparation du chemin vers le logo pour Dompdf
  //   $logoPath = null;
  //   if (!empty($compagnie['logo'])) {
  //     $logoPath = '/public/images/logos/' . $compagnie['logo']; // relatif à ROOT (chroot)
  //   }

  //   /* ---------- 3. Construction HTML ---------- */
  //   ob_start();
  //   include ROOT . '/app/views/pdf/ticket.php'; // la vue utilise $colis, $compagnie, $qrPath, $logoPath
  //   $html = ob_get_clean();

  //   /* ---------- 4. Génération PDF avec Dompdf ---------- */
  //   $opt = new \Dompdf\Options();
  //   $opt->setChroot(ROOT);
  //   $opt->setIsRemoteEnabled(true);

  //   $dompdf = new \Dompdf\Dompdf($opt);
  //   $dompdf->loadHtml($html);
  //   $dompdf->setPaper('A6', 'portrait'); // Taille réduite du reçu
  //   $dompdf->render();



  //   // Affichage dans le navigateur
  //   $dompdf->stream("ticket{$Billets['id_colis']}.pdf", ['Attachment' => false]);
  //   exit;
  // }

  public function recu($idBillets)
  {
    $colisModel = new Livraisons_colis();
    $billets = new Liste_du_jour();

    $billet = $billets->getBilletById($idBillets); // ✅ récupère un billet spécifique
    $compagnie = $colisModel->infoCompagnie($_SESSION['id_compagnie'] ?? 0);

    if (!$billet || !$compagnie) {
      $colisModel->set_flash('Billet ou compagnie introuvable.', 'danger');
      header('Location: ' . BASE_URL . '/admin/livraison_colis');
      exit;
    }

    // Chemin vers le logo
    $logoPath = null;
    if (!empty($compagnie['logo'])) {
      $logoPath = ROOT . '/public/images/logos/' . $compagnie['logo'];
    }

    // Construction HTML
    ob_start();
    include ROOT . '/app/views/admin/pdf/ticket.php';
    $html = ob_get_clean();

    // Génération PDF (imprimante thermique 80mm)
    $this->streamThermalPdf($html, "ticket_{$idBillets}.pdf");
  }

  // Renvoie les données du billet en JSON pour impression thermique ESC/POS.
  //
  // Le site est hébergé sur un serveur distant (LWS) : il ne peut pas atteindre
  // l'imprimante du comptoir (IP locale type 192.168.1.x, ou USB branchée sur le poste).
  // Le navigateur récupère donc les données ici, puis les transmet en JS au pont
  // d'impression local (local-print-bridge/), qui tourne sur le poste du comptoir
  // et qui seul peut réellement parler à l'imprimante (cf. ThermalPrinter::printBillet()).
  public function donneesTicketThermique($idBillets)
  {
    $colisModel = new Livraisons_colis();
    $billets = new Liste_du_jour();

    $billet = $billets->getBilletById($idBillets);
    $compagnie = $colisModel->infoCompagnie($_SESSION['id_compagnie'] ?? 0);

    header('Content-Type: application/json; charset=utf-8');

    if (!$billet || !$compagnie) {
      echo json_encode(['error' => 'Billet ou compagnie introuvable.']);
      exit;
    }

    $heureTs = !empty($billet->Heur_departs) ? strtotime($billet->Heur_departs) : false;
    $montantNet = preg_replace('/[^\d.]/', '', $billet->montant_payer ?? '');

    echo json_encode([
      'compagnie' => $compagnie['nom'] ?? 'Nom Compagnie',
      'slogan' => $compagnie['slogant'] ?? '',
      'numero' => $billet->numeroBillets ?? '-',
      'client' => $billet->Client ?? '-',
      'date' => !empty($billet->jourVoyage) ? date('d/m/Y', strtotime($billet->jourVoyage)) : '-',
      'depart' => $_SESSION['ville'] ?? '-',
      'heure' => $heureTs !== false ? date('H\hi', $heureTs) : (string)($billet->Heur_departs ?? '-'),
      'destination' => $billet->destinationId ?? '-',
      'places' => $billet->numeroPlace ?? '-',
      'montant' => !empty($montantNet) ? number_format((float)$montantNet, 0, ',', ' ') : '-',
      'emisPar' => $billet->utilisateurs ?? '-',
    ]);
    exit;
  }

  public function imprimerListe()
  {
    date_default_timezone_set('Africa/Bamako');
    $id_compagnie = $_SESSION['id_compagnie'];
    // Admin n'a pas de gare fixe en session : il voit les billets de toute la compagnie.
    $isAdmin      = ($_SESSION['droit'] ?? null) === 'Admin';
    $idDepart     = $isAdmin ? null : ($_SESSION['ville'] ?? null);
    $numeroGare   = $isAdmin ? null : ($_SESSION['numero_gare'] ?? null);
    $destination  = trim($_GET['destination'] ?? '');
    $heure        = trim($_GET['heure'] ?? '');
    $aujourdhui   = date('Y-m-d');

    $model = new Liste_du_jour();
    $colisModel = new Livraisons_colis();

    $where = 'billets.id_compagnie = :id_compagnie AND billets.jourVoyage = :jour';
    $params = [
      'id_compagnie' => $id_compagnie,
      'jour'         => $aujourdhui
    ];

    if ($idDepart !== null) {
      $where .= ' AND billets.departId = :depart';
      $params['depart'] = $idDepart;
    }

    if ($numeroGare !== null) {
      $where .= ' AND billets.num_gare = :numeroGare';
      $params['numeroGare'] = $numeroGare;
    }

    if ($destination !== '') {
      $where .= ' AND billets.destinationId = :destination';
      $params['destination'] = $destination;
    }
    if ($heure !== '') {
      $where .= ' AND billets.Heur_departs = :heure';
      $params['heure'] = $heure;
    }

    $billets = $model->FetchSelectWheres(
      '*',
      'billets INNER JOIN client ON billets.id_client = client.idClient',
      $where,
      $params
    );

    $compagnie = $colisModel->infoCompagnie($id_compagnie);

    $logoPath = null;
    if (!empty($compagnie['logo'])) {
      $logoPath = ROOT . '/public/images/logos/' . $compagnie['logo'];
    }

    ob_start();
    include ROOT . '/app/views/admin/pdf/liste_embarquement.php';
    $html = ob_get_clean();

    $opt = new \Dompdf\Options();
    $opt->setChroot(ROOT);
    $opt->setIsRemoteEnabled(true);

    $dompdf = new \Dompdf\Dompdf($opt);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream('liste_embarquement_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
    exit;
  }

  public function getHeuresDisponibles()
  {
    $destinationId = $_POST['destination_id'];
    // Admin n'a pas de gare fixe en session : il voit les heures de toute la compagnie.
    $isAdmin = ($_SESSION['droit'] ?? null) === 'Admin';
    $villeDepart = $isAdmin ? null : ($_SESSION['ville'] ?? null);
    $numeroGare  = $isAdmin ? null : ($_SESSION['numero_gare'] ?? null);
    $billets = new Liste_du_jour();

    $heures = $billets->getHeures($destinationId, $villeDepart, $numeroGare);

    echo json_encode($heures);
  }

  public function reporter()
  {
    $billets = new Liste_du_jour();

    if (isset($_POST['edit'])) {
      if (!csrf_verify()) {
        $billets->set_flash("Session expirée, veuillez réessayer.", "danger");
        header("Location: " . BASE_URL . "/admin/Liste_du_jours/index");
        exit;
      }

      date_default_timezone_set('Africa/Bamako');
      $idBillets = $_POST['idClient'];
      $billetActuel = $billets->getBilletById($idBillets);

      if (!$billetActuel) {
        $billets->set_flash("Billet introuvable.", "danger");
        header("Location: " . BASE_URL . "/admin/Liste_du_jours/index");
        exit;
      }

      // Le client peut avoir raté son départ initial : on autorise quand même le report
      // (l'agent est prévenu côté interface), tant que le billet n'est pas expiré et que
      // la nouvelle date n'est pas elle-même dans le passé.

      // Sans heure de départ valide, l'UPDATE plante (colonne TIME "" invalide en SQL strict).
      if (empty($_POST['nouvelle_date']) || empty($_POST['heure_depart'])) {
        $billets->set_flash("Veuillez choisir une nouvelle date et une heure de départ valides.", "danger");
        header("Location: " . BASE_URL . "/admin/Liste_du_jours/index");
        exit;
      }

      // Le billet a une date de validité : au-delà, il ne peut plus être reporté.
      $expiration = strtotime($billetActuel->date_expiration . ' 23:59:59');
      if ($expiration !== false && $expiration < time()) {
        $billets->set_flash("Ce billet a expiré, impossible de le reporter.", "danger");
        header("Location: " . BASE_URL . "/admin/Liste_du_jours/index");
        exit;
      }

      // Un report ne peut viser qu'aujourd'hui ou demain, exactement comme une vente initiale
      // (Add_billet::saveBillets) : au-delà, reporte_voyage() ne sait pas réserver de place
      // (ni sur un car programmé, ni dans "suivis"), ce qui rendrait le billet invisible pour
      // le contrôle de capacité au jour venu.
      $nouveauJour = date('Y-m-d', strtotime($_POST['nouvelle_date']));
      $aujourdhui  = date('Y-m-d');
      $demain      = date('Y-m-d', strtotime('+1 day'));
      if (!in_array($nouveauJour, [$aujourdhui, $demain], true)) {
        $billets->set_flash("Le report n'est possible que vers aujourd'hui ou demain.", "danger");
        header("Location: " . BASE_URL . "/admin/Liste_du_jours/index");
        exit;
      }

      // La nouvelle date/heure de départ ne peut pas être dans le passé.
      $nouveauDepart = strtotime($_POST['nouvelle_date'] . ' ' . $_POST['heure_depart']);
      if ($nouveauDepart === false || $nouveauDepart <= time()) {
        $billets->set_flash("La nouvelle date/heure de départ doit être dans le futur.", "danger");
        header("Location: " . BASE_URL . "/admin/Liste_du_jours/index");
        exit;
      }

      $data = [
        "jourVoyage" => $_POST['nouvelle_date'],
        "Heur_departs" => $_POST['heure_depart'],
        "idBillets" => $idBillets,
      ];

      $billets->reporte_voyage($data);
      header("Location: " . BASE_URL . "/admin/Liste_du_jours/index");
      exit;
    }
  }

  public function annuler()
  {
    if (isset($_POST['annuler_billet'])) {
      $billets = new Liste_du_jour();
      $idBillets = $_POST['idBillets'] ?? null;
      $motif = trim($_POST['motif_annulation'] ?? '');

      if ($idBillets) {
        $billets->annulerBillet($idBillets, $motif);
      }
    }

    header("Location: " . BASE_URL . "/admin/Liste_du_jours/index");
    exit;
  }
}
