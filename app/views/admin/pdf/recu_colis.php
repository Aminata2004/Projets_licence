<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
    body{font-family:DejaVu Sans,sans-serif;font-size:11px;margin:0 18px}
    header{border-bottom:1px solid #555;margin-bottom:12px;padding-bottom:6px;text-align:center}
    header img{height:55px}
    h2{margin:0;font-size:18px;text-transform:uppercase}
    small{font-size:9px;color:#555}
    .bloc{margin-bottom:10px}
    .bloc h3{background:#eee;padding:3px 6px;font-size:12px;margin:0 0 4px}
    table{width:100%;border-collapse:collapse}
    td{padding:2px 4px;vertical-align:top}
    footer{border-top:1px solid #555;margin-top:12px;padding-top:5px;font-size:9px;text-align:center}
    .qr{margin-top:6px;text-align:right}
</style>
</head>
<body>
<?php date_default_timezone_set('Africa/Bamako');?>
<header>
    <table style="width: 100%;">
        <tr>
            <td style="width: 70px;">
                <?php if (!empty($logoPath)): ?>
                    <img src="<?= $logoPath ?>" alt="Logo" style="height: 60px;">
                <?php endif; ?>
            </td>
            <td style="text-align: center;">
                <h2 style="margin: 0;"><?= htmlspecialchars($compagnie['nom']) ?></h2>
                <?php if (!empty($compagnie['slogant'])): ?>
                    <small><em><?= htmlspecialchars($compagnie['slogant']) ?></em></small><br>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <hr>
</header>


<div class="bloc">
    <h3>Expéditeur</h3>
    <table>
        <tr><td>Nom :</td><td><?= htmlspecialchars($colis['expediteur']) ?></td></tr>
        <tr><td>Téléphone :</td><td><?= htmlspecialchars($colis['numero_exp']) ?></td></tr>
    </table>
</div>

<div class="bloc">
    <h3>Destinataire</h3>
    <table>
        <tr><td>Nom :</td><td><?= htmlspecialchars($colis['destinataire']) ?></td></tr>
        <tr><td>Téléphone :</td><td><?= htmlspecialchars($colis['numero_dest']) ?></td></tr>
    </table>
</div>



<div class="qr">
    <img src="<?= $qrPath ?>" width="100">
</div>

<footer>
    Reçu généré le <?= date('d/m/Y à H:i') ?> — Merci d’avoir choisi <?= htmlspecialchars($compagnie['nom']) ?>.
</footer>

</body>
</html>
