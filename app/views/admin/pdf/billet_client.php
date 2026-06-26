<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <style>
    @page {
      size: 110mm 170mm;
      margin: 5mm 7mm;
    }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 11px;
      margin: 0;
      padding: 0;
      color: #222;
    }

    .ticket {
      width: 100%;
      max-width: 580px;
      margin: auto;
      border: 1px solid #333;
      border-radius: 6px;
      padding: 8px;
    }

    header {
      border-bottom: 2px solid #333;
      padding-bottom: 8px;
      margin-bottom: 12px;
    }

    header table {
      width: 100%;
    }

    header img {
      height: 50px;
    }

    h2 {
      margin: 4px 0;
      font-size: 16px;
      color: #2c3e50;
      text-transform: uppercase;
      text-align: center;
    }

    em {
      display: block;
      text-align: center;
      font-size: 10px;
      color: #666;
    }

    .section {
      margin-bottom: 15px;
    }

    .section h3 {
      font-size: 12px;
      margin-bottom: 6px;
      border-left: 4px solid #3498db;
      background: #f0f0f0;
      padding: 4px 8px;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td {
      padding: 4px 6px;
      vertical-align: top;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .qr {
      text-align: center;
      margin-top: 12px;
    }

    footer {
      border-top: 1px dashed #aaa;
      margin-top: 20px;
      padding-top: 6px;
      font-size: 9px;
      text-align: center;
      color: #777;
    }
  </style>
</head>
<body>

<?php date_default_timezone_set('Africa/Bamako'); ?>

<div class="ticket">

  <header>
    <table>
      <tr>
        <td width="60">
          <?php if (!empty($logoPath) && file_exists($logoPath)): ?>
            <img src="file://<?= realpath($logoPath) ?>" alt="Logo">
          <?php endif; ?>
        </td>
        <td>
          <h2><?= htmlspecialchars($compagnie['nom_compagnie'] ?? 'Nom Compagnie') ?></h2>
          <?php if (!empty($compagnie['slogant'])): ?>
            <em><?= htmlspecialchars($compagnie['slogant']) ?></em>
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </header>

  <div class="section">
    <h3>Informations du client</h3>
    <table>
      <tr>
        <td><strong>Nom du client :</strong></td>
        <td><?= htmlspecialchars($billet->Client ?? '-') ?></td>
      </tr>
      <tr>
        <td><strong>Agent :</strong></td>
        <td><?= htmlspecialchars($billet->agent ?? '-') ?></td>
      </tr>
      <tr>
        <td><strong>Numéro du billet :</strong></td>
        <td><?= htmlspecialchars($billet->numeroBillets ?? '-') ?></td>
      </tr>
    </table>
  </div>

  <div class="section">
    <h3>Détails du voyage</h3>
    <table>
      <tr>
        <td><strong>Date :</strong></td>
        <td><?= !empty($billet->jourVoyage) ? date('d/m/Y', strtotime($billet->jourVoyage)) : '-' ?></td>
      </tr>
      <tr>
        <td><strong>Heure de départ :</strong></td>
        <td><?= htmlspecialchars($billet->Heur_departs ?? '-') ?></td>
      </tr>
      <tr>
        <td><strong>Départ :</strong></td>
        <td><?= htmlspecialchars($_SESSION['ville'] ?? '-') ?></td>
      </tr>
      <tr>
        <td><strong>Destination :</strong></td>
        <td><?= htmlspecialchars($billet->destinationId ?? '-') ?></td>
      </tr>
      <tr>
        <td><strong>Place(s) :</strong></td>
        <td><?= htmlspecialchars($billet->numeroPlace ?? '-') ?></td>
      </tr>
      <tr>
        <td><strong>Montant payé :</strong></td>
        <td>
          <?php
            $montantNet = preg_replace('/[^\d.]/', '', $billet->montant_payer ?? '');
            echo !empty($montantNet)
                ? number_format((float)$montantNet, 0, ',', ' ') . ' FCFA'
                : '-';
          ?>
        </td>
      </tr>
    </table>
  </div>

  <?php if (!empty($qrPath)): ?>
    <div class="qr">
      <img src="<?= $qrPath ?>" alt="QR Code Billet">
    </div>
  <?php endif; ?>

  <div class="section">
    <h3>Conditions</h3>
    <p style="margin: 0; line-height: 1.5;">
      Merci d’être à la gare <strong>45 minutes avant</strong> l’heure de départ.<br>
      Ce billet est <strong>valable pendant 1 semaine</strong> après sa date d’émission.
    </p>
  </div>

  <footer>
    Reçu généré le <?= date('d/m/Y à H:i') ?>.<br>
    Merci d’avoir choisi <?= htmlspecialchars($compagnie['nom'] ?? 'notre compagnie') ?>.
  </footer>

</div>

</body>
</html>
