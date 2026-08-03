<?php
/**
 * Impression de badges employé.
 * $employes : tableau d'employés (1 élément = impression individuelle centrée,
 *             plusieurs éléments = planche A4 de 4 badges avec pagination auto).
 * $format   : 1 (seul format actif pour l'instant)
 * $compagnie: objet compagnie courante (logo, nom) ou null.
 */
$employes = $employes ?? (isset($employe) ? [$employe] : []);
$format   = (int)($format ?? 1);

$compNom = $compagnie ? htmlspecialchars($compagnie->nom_compagnie) : 'Ann Express';
$logoSrc = ($compagnie && !empty($compagnie->logo))
    ? BASE_URL . '/images/logos/' . htmlspecialchars($compagnie->logo)
    : '';

// Construit le HTML d'un seul badge pour le format demandé. Fonction partagée entre
// l'impression individuelle et l'impression groupée pour ne définir chaque gabarit
// qu'une seule fois.
function ann_render_badge($format, array $employe, $compNom, $logoSrc)
{
    $photoSrc = !empty($employe['photo'])
        ? BASE_URL . '/uploads/profiles/' . htmlspecialchars($employe['photo'])
        : '';

    $location = '';
    if (!empty($employe['affectation']) && $employe['affectation'] !== '—') {
        $location = htmlspecialchars($employe['affectation']);
        if (!empty($employe['localite'])) {
            $location .= ' – ' . htmlspecialchars($employe['localite']);
        }
    } elseif (!empty($employe['localite'])) {
        $location = htmlspecialchars($employe['localite']);
    }

    $nomDisplay  = htmlspecialchars($employe['nom']);
    $roleDisplay = htmlspecialchars($employe['fonction'] ?? $employe['type'] ?? '');
    $typeDisplay = htmlspecialchars($employe['type'] ?? '—');
    $telDisplay  = htmlspecialchars($employe['telephone'] ?? '—');
    $mailDisplay = (!empty($employe['contact']) && $employe['contact'] !== '—')
        ? htmlspecialchars($employe['contact']) : '';

    ob_start();
    ?>

    <?php if ($format === 1): /* ══ FORMAT 1 : CORPORATE HORIZONTAL ══ */ ?>

    <div class="badge-h">
        <div class="bh-left">
            <?php if ($logoSrc): ?>
                <div class="bh-logo-chip"><img src="<?= $logoSrc ?>" alt="Logo" onerror="this.parentElement.style.display='none'"></div>
            <?php endif; ?>
            <div class="bh-brand"><?= strtoupper($compNom) ?></div>
            <div class="bh-gold"></div>
            <div class="bh-tag">Voyagez en<br>toute confiance</div>
        </div>
        <div class="bh-right">
            <div class="bh-photo">
                <?php if ($photoSrc): ?>
                    <img src="<?= $photoSrc ?>" alt="Photo">
                <?php else: ?>
                    <i class="bi bi-person-fill ph"></i>
                <?php endif; ?>
            </div>
            <div class="bh-name"><?= $nomDisplay ?></div>
            <div class="bh-role"><?= $roleDisplay ?></div>
            <div class="bh-grid">
                <div class="bh-item">
                    <div class="bh-ic"><i class="bi bi-person-badge"></i></div>
                    <div class="bh-dt"><span class="bh-lbl">Type</span><span class="bh-val"><?= $typeDisplay ?></span></div>
                </div>
                <div class="bh-item">
                    <div class="bh-ic"><i class="bi bi-telephone-fill"></i></div>
                    <div class="bh-dt"><span class="bh-lbl">Téléphone</span><span class="bh-val"><?= $telDisplay ?></span></div>
                </div>
                <?php if ($mailDisplay): ?>
                <div class="bh-item">
                    <div class="bh-ic"><i class="bi bi-envelope-fill"></i></div>
                    <div class="bh-dt"><span class="bh-lbl">Email</span><span class="bh-val"><?= $mailDisplay ?></span></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($location)): ?>
                <div class="bh-item">
                    <div class="bh-ic"><i class="bi bi-geo-alt-fill"></i></div>
                    <div class="bh-dt"><span class="bh-lbl">Affectation</span><span class="bh-val"><?= $location ?></span></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="bh-foot"></div>
    </div>

    <?php endif; ?>

    <?php
    return ob_get_clean();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Pro – <?= htmlspecialchars($employes[0]['nom'] ?? '') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Oswald:wght@500;600;700&family=Montserrat:wght@500;600;700;800&family=Poppins:wght@400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>

    <style>
        :root{
            --navy:   #0d2149;
            --navy-2: #16306b;
            --red:    #e11b22;
            --red-2:  #a80e14;
            --gold:   #d8a63a;
            --gold-2: #f2cd7a;
            --muted:  #6b7280;
            --hair:   #e5e9f0;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { background: #7c8aa0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 28px 16px 60px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── on-screen toolbar ── */
        .no-print { display: flex; flex-direction: column; align-items: center; gap: 12px; margin-bottom: 22px; }
        .print-btn {
            background: linear-gradient(120deg, var(--navy) 0%, var(--navy-2) 100%);
            color: #fff; border: none; cursor: pointer;
            padding: 6px 30px 6px 6px; border-radius: 50px;
            font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 700;
            letter-spacing: .3px; text-transform: uppercase;
            display: flex; align-items: center; gap: 13px;
            box-shadow: 0 10px 26px rgba(13,33,73,.45), 0 0 0 1px rgba(216,166,58,.45) inset;
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .print-btn-ic {
            width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, var(--red) 0%, var(--red-2) 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: #fff;
            box-shadow: 0 4px 12px rgba(225,27,34,.55);
        }
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(13,33,73,.55), 0 0 0 1px rgba(216,166,58,.65) inset;
        }
        .print-btn:active { transform: translateY(0); }
        .print-btn:disabled { opacity: .65; cursor: not-allowed; transform: none; }
        .print-btn.pdf-btn {
            background: linear-gradient(120deg, var(--red) 0%, var(--red-2) 100%);
            box-shadow: 0 10px 26px rgba(168,14,20,.45), 0 0 0 1px rgba(216,166,58,.45) inset;
        }
        .print-btn.pdf-btn .print-btn-ic {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-2) 100%);
            box-shadow: 0 4px 12px rgba(13,33,73,.55);
        }
        .btn-row { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; }
        .no-print .hint { font-size: 12.5px; color: #eef1f6; opacity: .85; letter-spacing: .2px; }

        @page { size: A4; margin: 0; }
        @media print {
            html, body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
        }

        /* ══════════════════════════════════════════════
           MISE EN PAGE : impression seule vs planche A4
        ══════════════════════════════════════════════ */
        .single-wrap {
            width: 100%; min-height: 80vh;
            display: flex; align-items: center; justify-content: center;
        }
        .batch-page {
            width: 210mm;
            padding: 8mm;
            display: grid;
            grid-template-columns: repeat(2, 92mm);
            justify-content: center;
            gap: 8mm 6mm;
            background: #fff;
        }
        .batch-page .slot {
            display: flex; align-items: center; justify-content: center;
            position: relative; padding: 3mm;
        }
        .batch-page .slot::before {
            content: '';
            position: absolute; inset: 0;
            outline: 0.4pt dashed #c7cedb; outline-offset: -2mm;
            pointer-events: none;
        }
        @media print {
            .batch-page { box-shadow: none; break-after: page; }
            .batch-page:last-of-type { break-after: auto; }
        }
        @media screen {
            .batch-page { box-shadow: 0 2px 10px rgba(0,0,0,.15), 0 20px 55px rgba(0,0,0,.30); margin-bottom: 10mm; }
        }

        /* ══════════════════════════════════════════════
           FORMAT 1 — CORPORATE HORIZONTAL  (92 × 56 mm)
        ══════════════════════════════════════════════ */
        .badge-h {
            width: 92mm; height: 56mm;
            border-radius: 3.2mm; overflow: hidden; position: relative;
            display: flex; background: #fff;
            box-shadow: 0 1mm 3mm rgba(0,0,0,.10), 0 3mm 8mm rgba(13,33,73,.16);
        }
        @media print { .badge-h { box-shadow: none; border: 0.15mm solid #dde2ec; } }

        .bh-left {
            position: relative; z-index: 2; width: 36%;
            background: linear-gradient(170deg, var(--navy) 0%, var(--navy-2) 100%);
            border-radius: 0 60% 60% 0 / 0 12mm 12mm 0;
            border-right: 0.8mm solid var(--red);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 4mm 4.5mm; text-align: center; overflow: hidden;
        }
        .bh-left::before {
            content: ''; position: absolute; inset: 0;
            background: repeating-linear-gradient(135deg, rgba(255,255,255,.05) 0 2mm, transparent 2mm 4mm);
        }
        .bh-logo-chip {
            position: relative; z-index: 2;
            background: #fff; border-radius: 2mm; padding: 1.6mm 2.2mm;
            max-width: 24mm; max-height: 15mm; margin-bottom: 2.4mm;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 1mm 3mm rgba(0,0,0,.25);
        }
        .bh-logo-chip img { max-width: 100%; max-height: 12mm; object-fit: contain; }
        .bh-brand {
            position: relative; z-index: 2;
            font-family: 'Oswald', sans-serif; font-size: 9.5pt; font-weight: 700;
            color: #fff; text-transform: uppercase; line-height: 1.08; letter-spacing: .4px;
        }
        .bh-gold { position: relative; z-index: 2; width: 8mm; height: 0.6mm; background: var(--gold); margin: 2mm auto; border-radius: 1mm; }
        .bh-tag {
            position: relative; z-index: 2;
            font-size: 5.6pt; font-weight: 600; font-style: italic; color: rgba(255,255,255,.85);
            text-transform: uppercase; letter-spacing: .4px; line-height: 1.45;
        }

        .bh-right { position: relative; z-index: 2; width: 64%; padding: 3.5mm 5mm; display: flex; flex-direction: column; justify-content: center; gap: 3mm; }
        .bh-photo {
            position: absolute; right: 3.5mm; top: 3.5mm;
            width: 16mm; height: 16mm; border-radius: 50%;
            border: 0.8mm solid var(--red); box-shadow: 0 0 0 0.6mm var(--navy), 0 1.5mm 4mm rgba(0,0,0,.22);
            background: #d0d9ea; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .bh-photo img { width: 100%; height: 100%; object-fit: cover; }
        .bh-photo .ph { font-size: 7mm; color: #fff; }
        .bh-name {
            font-family: 'Oswald', sans-serif; font-size: 11pt; font-weight: 700;
            color: var(--navy); text-transform: uppercase; line-height: 1.08;
            max-width: 60%; overflow-wrap: break-word;
        }
        .bh-role {
            align-self: flex-start; background: var(--red); color: #fff;
            font-size: 6.1pt; font-weight: 800; letter-spacing: .8px; text-transform: uppercase;
            padding: 1mm 3mm; border-radius: 3mm;
            max-width: 78%; white-space: normal; line-height: 1.3; text-align: center;
        }
        .bh-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 1.8mm 2.5mm;
            border-top: 0.2mm solid var(--hair); padding-top: 2mm;
        }
        .bh-item { display: flex; align-items: flex-start; gap: 1.6mm; }
        .bh-ic {
            width: 4.4mm; height: 4.4mm; border-radius: 50%; background: var(--red); color: #fff; font-size: 5.6pt;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 0.2mm;
        }
        .bh-dt { display: flex; flex-direction: column; min-width: 0; }
        .bh-lbl { font-size: 4.8pt; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; font-weight: 700; }
        .bh-val { font-size: 6.9pt; font-weight: 800; color: var(--navy); line-height: 1.15; word-break: break-word; }
        .bh-foot { position: absolute; bottom: 0; right: 0; left: 36%; height: 1mm; background: linear-gradient(90deg, var(--gold), var(--gold-2), var(--gold)); z-index: 5; }

    </style>
</head>
<body>

<div class="no-print">
    <div class="btn-row">
        <button class="print-btn" onclick="window.print()">
            <span class="print-btn-ic"><i class="bi bi-printer-fill"></i></span>
            <span class="print-btn-tx"><?= count($employes) > 1 ? 'Imprimer la planche (' . count($employes) . ' badges)' : 'Imprimer le badge' ?></span>
        </button>
        <button type="button" class="print-btn pdf-btn" id="btnTelechargerPdf">
            <span class="print-btn-ic"><i class="bi bi-file-earmark-pdf-fill"></i></span>
            <span class="print-btn-tx">Télécharger en PDF</span>
        </button>
    </div>
    <?php if (count($employes) > 1): ?>
        <div class="hint">Format A4 · 2 badges par ligne · 4 par page · découpez selon les pointillés</div>
    <?php endif; ?>
</div>

<?php if (count($employes) === 1): ?>

    <div class="single-wrap">
        <?= ann_render_badge($format, $employes[0], $compNom, $logoSrc) ?>
    </div>

<?php else: ?>

    <?php foreach (array_chunk($employes, 4) as $groupe): ?>
        <div class="batch-page">
            <?php foreach ($groupe as $e): ?>
                <div class="slot"><?= ann_render_badge($format, $e, $compNom, $logoSrc) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var btnPdf = document.getElementById('btnTelechargerPdf');
        if (!btnPdf) return;

        var estPlanche = <?= count($employes) > 1 ? 'true' : 'false' ?>;
        var nomFichier = <?= json_encode($employes[0]['nom'] ?? 'employe') ?>;

        function slugify(txt) {
            var sansAccents = txt.normalize('NFD').replace(/[̀-ͯ]/g, '');
            return sansAccents.replace(/[^a-zA-Z0-9]+/g, '_').toLowerCase();
        }

        btnPdf.addEventListener('click', async function () {
            var libelle = btnPdf.querySelector('.print-btn-tx');
            var texteOriginal = libelle.textContent;
            btnPdf.disabled = true;
            libelle.textContent = 'Génération en cours...';

            try {
                var jsPDF = window.jspdf.jsPDF;

                if (!estPlanche) {
                    var carte = document.querySelector('.badge-h');
                    var canvas = await html2canvas(carte, { scale: 3, useCORS: true, backgroundColor: '#ffffff' });
                    var pdf = new jsPDF({ orientation: 'landscape', unit: 'mm', format: [92, 56] });
                    pdf.addImage(canvas.toDataURL('image/jpeg', 0.95), 'JPEG', 0, 0, 92, 56);
                    pdf.save('carte_' + slugify(nomFichier) + '.pdf');
                } else {
                    var pages = document.querySelectorAll('.batch-page');
                    var pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
                    for (var i = 0; i < pages.length; i++) {
                        var canvasPage = await html2canvas(pages[i], { scale: 2.5, useCORS: true, backgroundColor: '#ffffff' });
                        var largeurImg = 210;
                        var hauteurImg = canvasPage.height * (largeurImg / canvasPage.width);
                        if (i > 0) pdf.addPage();
                        pdf.addImage(canvasPage.toDataURL('image/jpeg', 0.95), 'JPEG', 0, (297 - hauteurImg) / 2, largeurImg, hauteurImg);
                    }
                    pdf.save('cartes_employes.pdf');
                }
            } catch (e) {
                console.error(e);
                alert('Une erreur est survenue lors de la génération du PDF.');
            } finally {
                btnPdf.disabled = false;
                libelle.textContent = texteOriginal;
            }
        });
    });
</script>
</body>
</html>
