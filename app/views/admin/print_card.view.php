<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Pro – <?= htmlspecialchars($employe['nom']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Oswald:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #c9d6e3 0%, #e8edf5 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── print button ── */
        .no-print {
            display: flex;
            gap: 12px;
            margin-bottom: 32px;
        }
        .print-btn {
            background: #082B63;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 18px rgba(8,43,99,.35);
            transition: background .2s, transform .15s;
        }
        .print-btn:hover { background: #c01118; transform: translateY(-1px); }

        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
            .v-card  { box-shadow: none !important; }
            .h-card  { box-shadow: none !important; }
        }

        /* ══════════════════════════════════════════════
           VERTICAL CARD  –  Format 1
           Dimensions : 360 × 580 px  (≈ 54 × 86 mm)
        ══════════════════════════════════════════════ */
        .v-card {
            width: 360px;
            border-radius: 26px;
            overflow: hidden;
            box-shadow:
                0 2px  6px rgba(0,0,0,.07),
                0 10px 28px rgba(0,0,0,.14),
                0 28px 64px rgba(0,0,0,.16);
            background: #ffffff;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* ── TOP: logo fills the whole white area as background ── */
        .vc-top {
            height: 210px;
            position: relative;
            overflow: hidden;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Logo used as a large, centered, slightly zoomed-in background fill */
        .vc-top .vc-logo-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;          /* fills the zone like a background */
            object-position: center;
        }

        /* Text fallback when no logo */
        .vc-top .vc-logo-text {
            position: relative;
            z-index: 2;
            font-family: 'Oswald', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: #082B63;
            text-align: center;
            line-height: 1.15;
            padding: 0 20px;
        }
        .vc-top .vc-logo-text em {
            color: #D71920;
            font-style: normal;
        }

        /* ── STRIPE SEPARATOR  (red thick / white thin / navy thin) ── */
        .vc-stripe {
            width: 100%;
            flex-shrink: 0;
        }
        .vc-stripe .s-red   { height: 16px; background: #D71920; }
        .vc-stripe .s-white { height: 5px;  background: #ffffff; }
        .vc-stripe .s-navy  { height: 5px;  background: #082B63; }

        /* ── PHOTO CIRCLE overlapping the stripe ── */
        .vc-photo-wrap {
            position: absolute;
            /* centre horizontally, pin top to 210px (end of white zone) */
            left: 50%;
            top: 210px;
            transform: translate(-50%, -50%);
            z-index: 10;
        }
        .vc-photo-outer {
            width: 148px;
            height: 148px;
            border-radius: 50%;
            border: 5px solid #D71920;
            box-shadow: 0 0 0 5px #ffffff, 0 8px 28px rgba(0,0,0,.22);
            background: #c8d4e4;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .vc-photo-outer img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .vc-photo-placeholder {
            font-size: 68px;
            color: #ffffff;
        }

        /* ── BOTTOM NAVY ZONE ── */
        .vc-bottom {
            background: #082B63;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            /* push content below the overlapping photo */
            padding: 82px 30px 30px;
            position: relative;
        }

        /* gold bottom bar */
        .vc-bottom::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, #082B63 0%, #D8A63A 30%, #f5d580 50%, #D8A63A 70%, #082B63 100%);
        }

        /* Name */
        .vc-name {
            font-family: 'Oswald', sans-serif;
            font-size: 38px;
            font-weight: 700;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-align: center;
            line-height: 1;
            margin-bottom: 10px;
        }

        /* Role pill */
        .vc-role {
            background: #D71920;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 6px 24px;
            border-radius: 22px;
            margin-bottom: 24px;
        }

        /* Divider line */
        .vc-divider {
            width: 100%;
            height: 1px;
            background: rgba(255,255,255,.15);
            margin-bottom: 20px;
        }

        /* Info rows */
        .vc-infos {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .vc-info-row {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .vc-info-row:last-child {
            border-bottom: none;
        }
        /* Red circle icon */
        .vc-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #D71920;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
            color: #ffffff;
            box-shadow: 0 3px 10px rgba(215,25,32,.45);
        }
        .vc-info-text {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: .3px;
        }


        /* ══════════════════════════════════════════
           HORIZONTAL CARD  – Formats 2 & 3
        ══════════════════════════════════════════ */
        .h-card {
            width: 620px;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,.18), 0 2px 8px rgba(0,0,0,.1);
            display: flex;
            position: relative;
            background: #ffffff;
        }
        .h-swoosh {
            position: absolute;
            top: -60%; left: -42%;
            width: 84%; height: 220%;
            background: #082B63;
            border-radius: 50%;
            border-right: 10px solid #D71920;
            z-index: 1;
        }
        .h-left {
            position: relative; z-index: 2;
            width: 38%; height: 100%;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 24px 18px;
            overflow: hidden;
        }
        /* Logo fills the left panel */
        .h-left .h-logo-bg {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            object-fit: cover; object-position: center;
            opacity: .35;
        }
        .h-left .h-logo-overlay {
            position: relative; z-index: 2;
            max-width: 160px; max-height: 75px;
            object-fit: contain;
            filter: drop-shadow(0 3px 6px rgba(0,0,0,.4)) brightness(10);
        }
        .h-left .h-logo-text {
            position: relative; z-index: 2;
            font-family: 'Oswald', sans-serif;
            font-size: 20px; font-weight: 700;
            color: #fff; text-align: center;
            text-transform: uppercase; line-height: 1.1;
        }
        .h-right {
            position: relative; z-index: 2;
            width: 62%; padding: 24px 28px;
            display: flex; flex-direction: column;
        }
        .h-right .hr-logo {
            max-width: 150px; max-height: 50px;
            object-fit: contain; margin-bottom: 10px;
        }
        .h-right .hr-photo {
            position: absolute; right: 22px; top: 20px;
            width: 110px; height: 110px; border-radius: 50%;
            border: 4px solid #D71920;
            box-shadow: 0 0 0 3px #082B63;
            background: #d0d9ea; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .h-right .hr-photo img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
        .h-right .hr-photo .ph { font-size:48px; color:#fff; }
        .h-right .hr-name {
            font-family: 'Oswald', sans-serif;
            font-size: 28px; font-weight: 700;
            color: #082B63; text-transform: uppercase; margin-bottom: 5px;
        }
        .h-right .hr-role {
            display: inline-block;
            background: #D71920; color: #fff;
            font-size: 11px; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            padding: 4px 14px; border-radius: 14px; margin-bottom: 18px;
        }
        .h-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 12px 10px;
            border-top: 1px solid #e5e9f0; padding-top: 14px; margin-top: auto;
            padding-right: 120px;
        }
        .h-item { display: flex; align-items: flex-start; gap: 9px; }
        .h-item .h-ic {
            width: 22px; height: 22px; border-radius: 50%;
            background: #D71920; color: #fff; font-size: 11px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-top: 2px;
        }
        .h-item .h-dt { display: flex; flex-direction: column; }
        .h-item .h-dt .h-lbl {
            font-size: 9px; color: #6b7280;
            text-transform: uppercase; letter-spacing: .5px; font-weight: 600;
        }
        .h-item .h-dt .h-val { font-size: 13px; font-weight: 700; color: #082B63; }
        .h-card::after {
            content: ''; position: absolute;
            bottom: 0; right: 0; width: 65%;
            height: 4px;
            background: linear-gradient(90deg, #D8A63A, #f5d580, #D8A63A);
            z-index: 5;
        }
    </style>
</head>
<body>

<div class="no-print">
    <button class="print-btn" onclick="window.print()">
        <i class="bi bi-printer-fill"></i>&nbsp; Imprimer la carte
    </button>
</div>

<?php
    $compNom   = $compagnie ? htmlspecialchars($compagnie->nom_compagnie) : 'Ann Express';
    $photoSrc  = !empty($employe['photo'])
                    ? BASE_URL . '/uploads/profiles/' . htmlspecialchars($employe['photo'])
                    : '';
    $logoSrc   = ($compagnie && !empty($compagnie->logo))
                    ? BASE_URL . '/images/logos/' . htmlspecialchars($compagnie->logo)
                    : '';

    // Location line
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
?>

<?php if ($format === 1): /* ══ VERTICAL ══ */ ?>

<div class="v-card">

    <!-- TOP ZONE: logo as background fill -->
    <div class="vc-top">
        <?php if ($logoSrc): ?>
            <img src="<?= $logoSrc ?>" alt="Logo <?= $compNom ?>" class="vc-logo-bg"
                 onerror="this.style.display='none';document.getElementById('lftxt').style.display='flex'">
            <div id="lftxt" class="vc-logo-text" style="display:none">
                <?= $compNom ?>
            </div>
        <?php else: ?>
            <div class="vc-logo-text"><?= $compNom ?></div>
        <?php endif; ?>
    </div>

    <!-- STRIPE SEPARATOR -->
    <div class="vc-stripe">
        <div class="s-red"></div>
        <div class="s-white"></div>
        <div class="s-navy"></div>
    </div>

    <!-- PHOTO CIRCLE — absolutely centred on the stripe boundary -->
    <div class="vc-photo-wrap">
        <div class="vc-photo-outer">
            <?php if ($photoSrc): ?>
                <img src="<?= $photoSrc ?>" alt="Photo de <?= $nomDisplay ?>">
            <?php else: ?>
                <i class="bi bi-person-fill vc-photo-placeholder"></i>
            <?php endif; ?>
        </div>
    </div>

    <!-- BOTTOM NAVY ZONE -->
    <div class="vc-bottom">

        <div class="vc-name"><?= $nomDisplay ?></div>
        <div class="vc-role"><?= $roleDisplay ?></div>

        <div class="vc-divider"></div>

        <div class="vc-infos">

            <!-- ID / Type -->
            <div class="vc-info-row">
                <div class="vc-icon"><i class="bi bi-person-badge-fill"></i></div>
                <div class="vc-info-text">ID : <?= $typeDisplay ?></div>
            </div>

            <!-- Téléphone -->
            <div class="vc-info-row">
                <div class="vc-icon"><i class="bi bi-telephone-fill"></i></div>
                <div class="vc-info-text"><?= $telDisplay ?></div>
            </div>

            <!-- Localisation / Affectation -->
            <?php if (!empty($location)): ?>
            <div class="vc-info-row">
                <div class="vc-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="vc-info-text"><?= $location ?></div>
            </div>
            <?php endif; ?>

        </div>
    </div>

</div><!-- .v-card -->


<?php elseif ($format === 2 || $format === 3): /* ══ HORIZONTAL ══ */ ?>

<div class="h-card">
    <div class="h-swoosh"></div>

    <!-- Left navy panel -->
    <div class="h-left">
        <?php if ($logoSrc): ?>
            <!-- logo as faded background layer -->
            <img src="<?= $logoSrc ?>" alt="" class="h-logo-bg"
                 onerror="this.style.display='none'">
            <!-- logo sharp overlay -->
            <img src="<?= $logoSrc ?>" alt="Logo" class="h-logo-overlay"
                 onerror="this.style.display='none';document.getElementById('hlt').style.display='block'">
            <div id="hlt" class="h-logo-text" style="display:none"><?= $compNom ?></div>
        <?php else: ?>
            <div class="h-logo-text"><?= $compNom ?></div>
        <?php endif; ?>
    </div>

    <!-- Right white panel -->
    <div class="h-right">

        <!-- Small logo top-left -->
        <?php if ($logoSrc): ?>
            <img src="<?= $logoSrc ?>" alt="Logo" class="hr-logo"
                 onerror="this.style.display='none'">
        <?php endif; ?>

        <!-- Photo top-right -->
        <div class="hr-photo">
            <?php if ($photoSrc): ?>
                <img src="<?= $photoSrc ?>" alt="Photo">
            <?php else: ?>
                <i class="bi bi-person-fill ph"></i>
            <?php endif; ?>
        </div>

        <div class="hr-name"><?= $nomDisplay ?></div>
        <div class="hr-role"><?= $roleDisplay ?></div>

        <div class="h-grid">
            <!-- Type -->
            <div class="h-item">
                <div class="h-ic"><i class="bi bi-person-badge"></i></div>
                <div class="h-dt">
                    <span class="h-lbl">Type de compte</span>
                    <span class="h-val"><?= $typeDisplay ?></span>
                </div>
            </div>
            <!-- Téléphone -->
            <div class="h-item">
                <div class="h-ic"><i class="bi bi-telephone-fill"></i></div>
                <div class="h-dt">
                    <span class="h-lbl">Téléphone</span>
                    <span class="h-val"><?= $telDisplay ?></span>
                </div>
            </div>
            <!-- Email -->
            <?php if ($mailDisplay): ?>
            <div class="h-item">
                <div class="h-ic"><i class="bi bi-envelope-fill"></i></div>
                <div class="h-dt">
                    <span class="h-lbl">Email</span>
                    <span class="h-val"><?= $mailDisplay ?></span>
                </div>
            </div>
            <?php endif; ?>
            <!-- Affectation -->
            <?php if (!empty($location)): ?>
            <div class="h-item">
                <div class="h-ic"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="h-dt">
                    <span class="h-lbl">Affectation</span>
                    <span class="h-val"><?= $location ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div><!-- .h-card -->

<?php endif; ?>

<script>
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 900);
    });
</script>
</body>
</html>
