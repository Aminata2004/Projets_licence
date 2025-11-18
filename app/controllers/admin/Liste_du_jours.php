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
    $idDepart = $_SESSION['ville'];
    $model = new Liste_du_jour();

    $resultats = $model->listeBillets();
    $liste_horaires = $model->FetchSelectWheres(
      '*',
      'horaire',
      'id_compagnie = :id_compagnie',
      ['id_compagnie' => $id_compagnie]
    );
    $destinations = $model->getDestinations($idDepart, $id_compagnie);

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

    // Dompdf
    $opt = new \Dompdf\Options();
    $opt->setChroot(ROOT); // Important pour le chemin du logo
    $opt->setIsRemoteEnabled(true);

    $dompdf = new \Dompdf\Dompdf($opt);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A6', 'portrait');
    $dompdf->render();

    $dompdf->stream("ticket_{$idBillets}.pdf", ['Attachment' => false]);
    exit;
  }

  public function getHeuresDisponibles()
  {
    $destinationId = $_POST['destination_id'];
    $villeDepart = $_SESSION['ville']; // Session de l’utilisateur
    $billets = new Liste_du_jour();

    $heures = $billets->getHeures($destinationId, $villeDepart);

    echo json_encode($heures);
  }

  public function reporter()
  {
    $billets = new Liste_du_jour();

    if (isset($_POST['edit'])) {
      $data = [
        "jourVoyage" => $_POST['nouvelle_date'],
        "Heur_departs" => $_POST['heure_depart'],
        "idBillets" => $_POST['idClient'],
      ];

      $billets->reporte_voyage($data);
      header("Location: " . BASE_URL . "/admin/Liste_du_jours/index");
      exit;
    }
  }
}
