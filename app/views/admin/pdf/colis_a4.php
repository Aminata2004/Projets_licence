<?php
date_default_timezone_set('Africa/Bamako');

$statusLabels = [
  'enregistre' => 'Prise en charge',
  'en_cours'   => 'En cours',
  'recu'       => 'Colis reçu',
  'livre'      => 'Colis livré',
];
$statusLabel = $statusLabels[$colis['status'] ?? ''] ?? 'En attente';
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
      font-size: 12px;
      margin: 0;
      padding: 0;
      color: #222;
    }

    header {
      border-bottom: 2px solid #333;
      padding-bottom: 8px;
      margin-bottom: 14px;
    }

    header table {
      width: 100%;
    }

    header img {
      height: 45px;
    }

    h2 {
      margin: 4px 0;
      font-size: 16px;
      text-align: center;
      text-transform: uppercase;
      color: #2c3e50;
    }

    h3 {
      text-align: center;
      font-size: 13px;
      margin: 4px 0 16px;
      color: #333;
      font-weight: normal;
    }

    .section {
      margin-bottom: 14px;
    }

    .section h4 {
      font-size: 12px;
      text-transform: uppercase;
      margin: 0 0 4px;
      padding-bottom: 2px;
      border-bottom: 1px solid #999;
      color: #2c3e50;
    }

    table.infos {
      width: 100%;
      border-collapse: collapse;
    }

    table.infos td {
      border: 1px solid #ccc;
      padding: 5px 8px;
      font-size: 12px;
    }

    table.infos td.label {
      width: 35%;
      font-weight: bold;
      background: #f5f5f5;
    }

    table.cols {
      width: 100%;
    }

    table.cols td {
      width: 50%;
      vertical-align: top;
      padding: 0 6px;
    }

    table.cols td:first-child {
      padding-left: 0;
    }

    table.cols td:last-child {
      padding-right: 0;
    }

    .qr {
      text-align: center;
      margin-top: 16px;
    }

    footer {
      margin-top: 16px;
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
          <?php if (!empty($compagnie['slogant'])): ?>
            <div style="text-align:center;font-size:11px;font-style:italic;"><?= htmlspecialchars($compagnie['slogant']) ?></div>
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </header>

  <h3>Fiche colis — Code : <?= htmlspecialchars($colis['code_colis'] ?? '-') ?></h3>

  <div class="section">
    <h4>Colis</h4>
    <table class="infos">
      <tr>
        <td class="label">Nom du colis</td>
        <td><?= htmlspecialchars($colis['nom_colis'] ?? '-') ?></td>
        <td class="label">Nature</td>
        <td><?= htmlspecialchars($colis['nature'] ?? '-') ?></td>
      </tr>
      <tr>
        <td class="label">Valeur déclarée</td>
        <td><?= htmlspecialchars(number_format((float)($colis['valeur'] ?? 0), 0, ',', ' ')) ?> FCFA</td>
        <td class="label">Frais de transport</td>
        <td><?= htmlspecialchars(number_format((float)($colis['fraix_transaction'] ?? 0), 0, ',', ' ')) ?> FCFA</td>
      </tr>
      <tr>
        <td class="label">Statut</td>
        <td><?= htmlspecialchars($statusLabel) ?></td>
        <td class="label">Date d'enregistrement</td>
        <td><?= !empty($colis['date_enregistrement']) ? date('d/m/Y à H:i', strtotime($colis['date_enregistrement'])) : '-' ?></td>
      </tr>
      <tr>
        <td class="label">Gare de départ</td>
        <td><?= htmlspecialchars($colis['provient_de'] ?? '-') ?></td>
        <td class="label">Gare de destination</td>
        <td><?= htmlspecialchars($colis['localite'] ?? '-') ?></td>
      </tr>
      <tr>
        <td class="label">Enregistré par</td>
        <td colspan="3"><?= htmlspecialchars($colis['agent_nom'] ?? '-') ?></td>
      </tr>
    </table>
  </div>

  <div class="section">
    <table class="cols">
      <tr>
        <td>
          <h4>Expéditeur</h4>
          <table class="infos">
            <tr>
              <td class="label">Nom</td>
              <td><?= htmlspecialchars($colis['expediteur'] ?? '-') ?></td>
            </tr>
            <tr>
              <td class="label">Téléphone</td>
              <td><?= htmlspecialchars($colis['numero_exp'] ?? '-') ?></td>
            </tr>
            <tr>
              <td class="label">WhatsApp</td>
              <td><?= htmlspecialchars($colis['whatsapp_exp'] ?? '-') ?></td>
            </tr>
          </table>
        </td>
        <td>
          <h4>Destinataire</h4>
          <table class="infos">
            <tr>
              <td class="label">Nom</td>
              <td><?= htmlspecialchars($colis['destinataire'] ?? '-') ?></td>
            </tr>
            <tr>
              <td class="label">Téléphone</td>
              <td><?= htmlspecialchars($colis['numero_dest'] ?? '-') ?></td>
            </tr>
            <tr>
              <td class="label">WhatsApp</td>
              <td><?= htmlspecialchars($colis['whatsapp_dest'] ?? '-') ?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>

  <div class="qr">
    <img src="<?= $qrPath ?>" width="110">
  </div>

  <footer>
    Document généré le <?= date('d/m/Y à H:i') ?>.
  </footer>

</body>
</html>
