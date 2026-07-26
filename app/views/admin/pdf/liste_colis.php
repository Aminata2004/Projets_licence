<?php
date_default_timezone_set('Africa/Bamako');
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
      margin-bottom: 12px;
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
      padding: 5px 6px;
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
        <th>Nature</th>
        <th>Expéditeur</th>
        <th>Tél. expéditeur</th>
        <th>Destinataire</th>
        <th>Tél. destinataire</th>
        <th>Gare de départ</th>
        <th>Gare de destination</th>
        <th>Enregistré par</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; ?>
      <?php foreach ($liste_colis as $colis): ?>
        <tr>
          <td class="col-num"><?= $i++ ?></td>
          <td><?= htmlspecialchars($colis['code_colis'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['nom_colis'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['nature'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['expediteur'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['numero_exp'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['destinataire'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['numero_dest'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['provient_de'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['destination'] ?? '-') ?></td>
          <td><?= htmlspecialchars($colis['agent_nom'] ?? '-') ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($liste_colis)): ?>
        <tr>
          <td colspan="11" style="text-align:center;">Aucun colis ne correspond à ce filtre.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <footer>
    Document généré le <?= date('d/m/Y à H:i') ?>.
  </footer>

</body>
</html>
