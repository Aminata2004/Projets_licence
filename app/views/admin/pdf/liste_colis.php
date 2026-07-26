<?php
date_default_timezone_set('Africa/Bamako');

$statusLabels = [
  'enregistre' => 'Prise en charge',
  'en_cours'   => 'En cours',
  'recu'       => 'Colis reçu',
  'livre'      => 'Colis livré',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <style>
    @page {
      margin: 15mm 10mm;
    }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 11px;
      margin: 0;
      padding: 0;
      color: #222;
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
      height: 45px;
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
      font-size: 13px;
      margin: 4px 0 14px;
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
      padding: 6px 8px;
      font-size: 11px;
    }

    table.liste th {
      background: #f0f0f0;
      text-align: center;
    }

    .col-num {
      width: 30px;
      text-align: center;
    }

    .col-remis {
      width: 70px;
      text-align: center;
    }

    .box {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 1px solid #333;
    }

    footer {
      margin-top: 15px;
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
        <td width="60">
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
    Liste des colis — Destination : <?= htmlspecialchars($destinationNom !== '' ? $destinationNom : 'Toutes') ?>
    — Statut : <?= htmlspecialchars($statutNom !== '' ? $statutNom : 'Tous') ?>
    — Date : <?= date('d/m/Y') ?>
  </h3>

  <table class="liste">
    <thead>
      <tr>
        <th class="col-num">#</th>
        <th>Code</th>
        <th>Nom du colis</th>
        <th>Destinataire</th>
        <th>Téléphone</th>
        <th>Destination</th>
        <th>Statut</th>
        <th class="col-remis">Remis</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; ?>
      <?php foreach ($liste_colis as $colis): ?>
        <tr>
          <td class="col-num"><?= $i++ ?></td>
          <td><?= htmlspecialchars($colis['code_colis'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['nom_colis'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['destinataire'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['numero_dest'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['destination'] ?? '-') ?></td>
          <td><?= htmlspecialchars($statusLabels[$colis['status'] ?? ''] ?? 'En attente') ?></td>
          <td class="col-remis"><span class="box"></span></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($liste_colis)): ?>
        <tr>
          <td colspan="8" style="text-align:center;">Aucun colis ne correspond à ce filtre.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <footer>
    Document généré le <?= date('d/m/Y à H:i') ?>.
  </footer>

</body>
</html>
