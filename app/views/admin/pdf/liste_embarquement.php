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

    .col-embarque {
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
    Liste d'embarquement — Départ : <?= htmlspecialchars($_SESSION['ville'] ?? '-') ?>
    — Destination : <?= htmlspecialchars($destination !== '' ? $destination : 'Toutes') ?>
    — Heure : <?= htmlspecialchars($heure !== '' ? $heure : 'Toutes') ?>
    — Date : <?= date('d/m/Y') ?>
  </h3>

  <table class="liste">
    <thead>
      <tr>
        <th class="col-num">#</th>
        <th>Client</th>
        <th>Destination</th>
        <th>N° de place</th>
        <th>Heure de départ</th>
        <th class="col-embarque">Embarqué</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; ?>
      <?php foreach ($billets as $item): ?>
        <tr>
          <td class="col-num"><?= $i++ ?></td>
          <td><?= htmlspecialchars($item->Client) ?></td>
          <td><?= htmlspecialchars($item->destinationId) ?></td>
          <td><?= htmlspecialchars($item->numeroPlace) ?></td>
          <td><?= htmlspecialchars($item->Heur_departs) ?></td>
          <td class="col-embarque"><span class="box"></span></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($billets)): ?>
        <tr>
          <td colspan="6" style="text-align:center;">Aucun billet ne correspond à ce filtre.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <footer>
    Document généré le <?= date('d/m/Y à H:i') ?>.
  </footer>

</body>
</html>
