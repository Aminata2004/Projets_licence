<?php
date_default_timezone_set('Africa/Bamako');

$statusLabels = [
  'enregistre' => 'Prise en charge',
  'en_cours'   => 'En cours',
  'recu'       => 'Colis reçu',
  'livre'      => 'Colis livré',
];

$triLabels = [
  'date'        => 'Date',
  'nom'         => 'Nom du colis',
  'destination' => 'Destination',
  'statut'      => 'Statut',
];
$triLabel = $triLabels[$tri] ?? 'Date';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <style>
    @page {
      margin: 12mm 10mm;
    }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 10px;
      margin: 0;
      padding: 0;
      color: #222;
    }

    header {
      border-bottom: 2px solid #333;
      padding-bottom: 8px;
      margin-bottom: 10px;
    }

    header table {
      width: 100%;
    }

    header img {
      height: 40px;
    }

    h2 {
      margin: 4px 0;
      font-size: 15px;
      text-align: center;
      text-transform: uppercase;
      color: #2c3e50;
    }

    h3 {
      text-align: center;
      font-size: 12px;
      margin: 4px 0 12px;
      color: #333;
      font-weight: normal;
    }

    table.liste {
      width: 100%;
      border-collapse: collapse;
    }

    table.liste th,
    table.liste td {
      border: 1px solid #999;
      padding: 4px 6px;
      font-size: 10px;
    }

    table.liste th {
      background: #f0f0f0;
      text-align: center;
    }

    .col-num {
      width: 25px;
      text-align: center;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    footer {
      margin-top: 12px;
      font-size: 9px;
      text-align: center;
      color: #777;
    }
  </style>
</head>
<body>

  <header>
    <table>
      <tr>
        <td width="55">
          <?php if (!empty($logoPath) && file_exists($logoPath)): ?>
            <img src="file://<?= realpath($logoPath) ?>" alt="Logo">
          <?php endif; ?>
        </td>
        <td>
          <h2><?= htmlspecialchars($compagnie['nom'] ?? 'Nom Compagnie') ?></h2>
        </td>
      </tr>
    </table>
  </header>

  <h3>
    Liste des colis — Triée par : <?= htmlspecialchars($triLabel) ?>
    — Date d'export : <?= date('d/m/Y à H:i') ?>
    — Total : <?= count($liste_colis) ?> colis
  </h3>

  <table class="liste">
    <thead>
      <tr>
        <th class="col-num">#</th>
        <th>Code</th>
        <th>Nom du colis</th>
        <th>Nature</th>
        <th>Provenance</th>
        <th>Destination</th>
        <th>Valeur</th>
        <th>Frais</th>
        <th>Statut</th>
        <th>Date</th>
        <th>Expéditeur</th>
        <th>Destinataire</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; $totalValeur = 0; $totalFrais = 0; ?>
      <?php foreach ($liste_colis as $colis): ?>
        <?php $totalValeur += (float)($colis['valeur'] ?? 0); $totalFrais += (float)($colis['fraix_transaction'] ?? 0); ?>
        <tr>
          <td class="col-num"><?= $i++ ?></td>
          <td><?= htmlspecialchars($colis['code_colis'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['nom_colis'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['nature'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['provient_de'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['destination'] ?? '-') ?></td>
          <td class="text-right"><?= number_format((float)($colis['valeur'] ?? 0), 0, ',', ' ') ?></td>
          <td class="text-right"><?= number_format((float)($colis['fraix_transaction'] ?? 0), 0, ',', ' ') ?></td>
          <td class="text-center"><?= htmlspecialchars($statusLabels[$colis['status'] ?? ''] ?? 'En attente') ?></td>
          <td><?= !empty($colis['date_enregistrement']) ? date('d/m/Y', strtotime($colis['date_enregistrement'])) : '-' ?></td>
          <td><?= htmlspecialchars($colis['expediteur'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['destinataire'] ?? '-') ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($liste_colis)): ?>
        <tr>
          <td colspan="12" class="text-center">Aucun colis enregistré.</td>
        </tr>
      <?php endif; ?>
    </tbody>
    <?php if (!empty($liste_colis)): ?>
      <tfoot>
        <tr>
          <th colspan="6" class="text-right">Totaux</th>
          <th class="text-right"><?= number_format($totalValeur, 0, ',', ' ') ?></th>
          <th class="text-right"><?= number_format($totalFrais, 0, ',', ' ') ?></th>
          <th colspan="4"></th>
        </tr>
      </tfoot>
    <?php endif; ?>
  </table>

  <footer>
    Document généré le <?= date('d/m/Y à H:i') ?>.
  </footer>

</body>
</html>
