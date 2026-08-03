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
      height: 42px;
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
      padding: 5px 6px;
      font-size: 10px;
    }

    table.liste th {
      background: #f0f0f0;
      text-align: center;
    }

    .col-num {
      width: 24px;
      text-align: center;
    }

    .col-photo {
      width: 34px;
      text-align: center;
    }

    .col-photo img {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      object-fit: cover;
    }

    .col-type,
    .col-statut {
      width: 70px;
      text-align: center;
    }

    .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 10px;
      font-size: 9px;
      color: #fff;
    }

    .badge-primary { background: #0d6efd; }
    .badge-warning { background: #ffc107; color: #222; }
    .badge-success { background: #198754; }
    .badge-secondary { background: #6c757d; }

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
          <?php
            $logoPath = (!empty($compagnie->logo)) ? ROOT . '/public/images/logos/' . $compagnie->logo : null;
          ?>
          <?php if ($logoPath && file_exists($logoPath)): ?>
            <img src="file://<?= realpath($logoPath) ?>" alt="Logo">
          <?php endif; ?>
        </td>
        <td>
          <h2><?= htmlspecialchars($compagnie->nom_compagnie ?? 'Compagnie') ?></h2>
        </td>
      </tr>
    </table>
  </header>

  <h3>Liste des employés — <?= count($employes) ?> au total — Générée le <?= date('d/m/Y à H:i') ?></h3>

  <table class="liste">
    <thead>
      <tr>
        <th class="col-num">#</th>
        <th class="col-photo">Photo</th>
        <th>Nom &amp; prénom</th>
        <th>Fonction</th>
        <th>Contact</th>
        <th>Téléphone</th>
        <th>Affectation</th>
        <th class="col-type">Type</th>
        <th class="col-statut">Statut</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; ?>
      <?php foreach ($employes as $employe): ?>
        <?php
          $photoPath = !empty($employe['photo']) ? ROOT . '/public/uploads/profiles/' . $employe['photo'] : null;
        ?>
        <tr>
          <td class="col-num"><?= $i++ ?></td>
          <td class="col-photo">
            <?php if ($photoPath && file_exists($photoPath)): ?>
              <img src="file://<?= realpath($photoPath) ?>" alt="">
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($employe['nom']) ?></td>
          <td><?= htmlspecialchars($employe['fonction']) ?></td>
          <td><?= htmlspecialchars($employe['contact']) ?></td>
          <td><?= htmlspecialchars($employe['telephone']) ?></td>
          <td><?= htmlspecialchars($employe['affectation']) ?></td>
          <td class="col-type">
            <span class="badge <?= $employe['type'] === 'Chauffeur' ? 'badge-warning' : 'badge-primary' ?>">
              <?= htmlspecialchars($employe['type']) ?>
            </span>
          </td>
          <td class="col-statut">
            <span class="badge <?= $employe['statut'] === 'Actif' ? 'badge-success' : 'badge-secondary' ?>">
              <?= htmlspecialchars($employe['statut']) ?>
            </span>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($employes)): ?>
        <tr>
          <td colspan="9" style="text-align:center;">Aucun employé trouvé pour cette compagnie.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <footer>
    Document généré le <?= date('d/m/Y à H:i') ?>.
  </footer>

</body>
</html>
