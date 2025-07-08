<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;                 // ← énumération 5.x
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\RoundBlockSizeMode;   // ← au lieu du namespace\RoundBlockSizeModeMargin

class Colis_prise_en_charges extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }

  public  function  index()
  {
    $colis_prise_en_charge =  new Colis_prise_en_charge();
    $liste_colis = $colis_prise_en_charge->FetchSelectcolis();

    $this->view('liste_colis_prise', ['liste_colis' => $liste_colis]);
  }
  public function ajouter_colis()
  {

    $colis_prise_en_charge =  new Colis_prise_en_charge();
    $code_colis = $this->genererCodeUnique($colis_prise_en_charge); // générer un code unique

    if (isset($_POST['envoi'])) {
      $errors =  $colis_prise_en_charge->saveColis();
    } else {
      $errors = [];
    }
    // liste 
    $id_compagnie = $_SESSION['id_compagnie'];

    $listes =  $colis_prise_en_charge->FetchSelectWheres(
      '*',
      'agence',
      'id_compagnie = :id_compagnie',
      ['id_compagnie' => $id_compagnie]
    );
    $this->view('ajouter_colis', ['listes' => $listes,'code_colis'=>$code_colis]);
  }

  private function genererCodeColis($longueur = 6)
  {
    $caracteres = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $code = '';
    for ($i = 0; $i < $longueur; $i++) {
      $code .= $caracteres[random_int(0, strlen($caracteres) - 1)];
    }
    return $code;
  }

  private function genererCodeUnique($model)
  {
       $colis_prise_en_charge =  new Colis_prise_en_charge();
    do {
      $code = $this->genererCodeColis();
      $existe = $colis_prise_en_charge->existe('colis', 'code_colis', $code); // suppose que tu as cette méthode dans ton modèle
    } while ($existe);
    return $code;
  }

  public function imprimer_recu(int $id_colis): void
  {
    /* ---------- 1. Récupération DB ---------- */
    $colisModel = new Livraisons_colis();

    $colis = $colisModel->getById($id_colis);
    $compagnie = $colisModel->infoCompagnie($_SESSION['id_compagnie'] ?? 0);

    if (!$colis || !$compagnie) {
      $colisModel->set_flash('Colis ou compagnie introuvable.', 'danger');
      header('Location: ' . BASE_URL . '/livraison_colis');
      exit;
    }

    // Préparation du chemin vers le logo pour Dompdf
    $logoPath = null;
    if (!empty($compagnie['logo'])) {
      $logoPath = '/public/images/logos/' . $compagnie['logo']; // relatif à ROOT (chroot)
    }

    /* ---------- 2. Génération du QR Code ---------- */
    $qrData = "Nom du colis : {$colis['nom_colis']}\n" .
      "Nature       : {$colis['nature']}\n" .
      "Code         : {$colis['code_colis']}\n" .
      "Destination  : {$colis['localite']}\n" .
      "Valeur       : " . number_format($colis['valeur'], 0, ',', ' ') . " FCFA\n" .
      "Frais        : " . number_format($colis['fraix_transaction'], 0, ',', ' ') . " FCFA";

    $qrPath = ROOT . "/public/qrcodes/qrcode_{$colis['id_colis']}.png";

    Builder::create()
      ->writer(new PngWriter())
      ->data($qrData)
      ->encoding(new Encoding('UTF-8'))
      ->errorCorrectionLevel(ErrorCorrectionLevel::High)
      ->size(200)
      ->margin(6)
      ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
      ->build()
      ->saveToFile($qrPath);

    // Vérifier que le QR code a bien été généré
    if (!file_exists($qrPath) || filesize($qrPath) === 0) {
      throw new \RuntimeException("QR code non généré ou fichier vide : $qrPath");
    }

    /* ---------- 3. Construction HTML ---------- */
    ob_start();
    include ROOT . '/app/views/pdf/recu_colis.php'; // la vue utilise $colis, $compagnie, $qrPath, $logoPath
    $html = ob_get_clean();

    /* ---------- 4. Génération PDF avec Dompdf ---------- */
    $opt = new \Dompdf\Options();
    $opt->setChroot(ROOT);
    $opt->setIsRemoteEnabled(true);

    $dompdf = new \Dompdf\Dompdf($opt);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A6', 'portrait'); // Taille réduite du reçu
    $dompdf->render();



    // Affichage dans le navigateur
    $dompdf->stream("recu_colis_{$colis['id_colis']}.pdf", ['Attachment' => false]);
    exit;
  }
}
