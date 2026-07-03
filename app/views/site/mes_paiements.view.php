<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Paiements - TransGest</title>
    <link href="<?= BASE_URL ?>/assets_site/css/inter.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets_site/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fb; color: #1a1f2e; }
        :root {
            --primary: #0f3b5e;
            --secondary: #e67e22;
            --success: #27ae60;
            --radius: 8px;
            --shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .header {
            background: white;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo img { height: 55px; }
        .btn-logout {
            background: #fef2f2;
            color: #e74c3c;
            border: 1px solid #fca5a5;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 32px 24px; }

        .nav-tabs {
            display: flex;
            gap: 4px;
            background: white;
            padding: 6px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 28px;
            flex-wrap: wrap;
        }
        .nav-tab {
            flex: 1;
            min-width: 120px;
            padding: 11px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            color: #555;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: all 0.2s;
        }
        .nav-tab:hover { background: #f0f4f8; color: var(--primary); }
        .nav-tab.active { background: var(--primary); color: white; }

        /* Total banner */
        .total-banner {
            background: linear-gradient(135deg, #0f3b5e, #1a5276);
            border-radius: 14px;
            padding: 24px 28px;
            color: white;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .total-banner .label { font-size: 0.85rem; opacity: 0.8; margin-bottom: 4px; }
        .total-banner .amount { font-size: 2rem; font-weight: 800; }
        .total-banner .nb    { font-size: 0.9rem; opacity: 0.75; margin-top: 2px; }
        .total-banner i { font-size: 3rem; opacity: 0.25; }

        /* Table */
        .card {
            background: white;
            border-radius: 14px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header h2 { font-size: 1rem; color: var(--primary); }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: #f8fafc;
            padding: 12px 16px;
            text-align: left;
            font-size: 0.78rem;
            font-weight: 700;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #eee;
        }
        tbody td {
            padding: 14px 16px;
            font-size: 0.88rem;
            border-bottom: 1px solid #f5f5f5;
            color: #333;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafbfc; }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info    { background: #d1ecf1; color: #0c5460; }

        .montant-value {
            font-weight: 700;
            color: var(--success);
            font-size: 0.95rem;
        }
        .empty-state {
            text-align: center;
            padding: 60px 24px;
            color: #aaa;
        }
        .empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }

        @media (max-width: 600px) {
            .nav-tab span { display: none; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <a href="<?= BASE_URL ?>/site/Accueil">
                <img src="<?= BASE_URL ?>/images/logos/transgest_logo.png" alt="TransGest" style="height:55px;">
            </a>
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="font-size:0.9rem;color:#555;">Bonjour, <strong style="color:var(--primary);"><?= htmlspecialchars($_SESSION['client_nom']) ?></strong></span>
                <a href="<?= BASE_URL ?>/site/EspaceClient/logout" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="nav-tabs">
            <a href="<?= BASE_URL ?>/site/EspaceClient/dashboard" class="nav-tab">
                <i class="fas fa-ticket-alt"></i> <span>Mes Commandes</span>
            </a>
            <a href="<?= BASE_URL ?>/site/EspaceClient/mesPaiements" class="nav-tab active">
                <i class="fas fa-credit-card"></i> <span>Mes Paiements</span>
            </a>
            <a href="<?= BASE_URL ?>/site/EspaceClient/monEpargne" class="nav-tab">
                <i class="fas fa-piggy-bank"></i> <span>Mon Épargne</span>
            </a>
        </div>

        <?php
        $totalMontant = 0;
        foreach ($paiements as $p) {
            $totalMontant += (float)($p->montant_payer ?? 0);
        }
        ?>

        <!-- Bannière total -->
        <div class="total-banner">
            <div>
                <div class="label">Total des paiements effectués</div>
                <div class="amount"><?= number_format($totalMontant, 0, ',', ' ') ?> FCFA</div>
                <div class="nb"><?= count($paiements) ?> paiement(s) enregistré(s)</div>
            </div>
            <i class="fas fa-credit-card"></i>
        </div>

        <!-- Liste des paiements -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-list" style="margin-right:6px;"></i>Historique des paiements</h2>
                <span style="font-size:0.82rem;color:#888;"><?= count($paiements) ?> entrée(s)</span>
            </div>
            <div class="table-wrap">
                <?php if (empty($paiements)): ?>
                    <div class="empty-state">
                        <i class="fas fa-credit-card"></i>
                        <p>Aucun paiement enregistré.</p>
                    </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>N° Billet</th>
                            <th>Trajet</th>
                            <th>Passagers</th>
                            <th>N° Paiement</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paiements as $p): ?>
                        <tr>
                            <td><?= $p->date_enregistrement ? date('d/m/Y', strtotime($p->date_enregistrement)) : '-' ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/site/EspaceClient/show/<?= $p->idBillets ?? '' ?>"
                                   style="color:var(--primary);font-weight:600;text-decoration:none;">
                                    <?= htmlspecialchars($p->numeroBillets) ?>
                                </a>
                            </td>
                            <td>
                                <?= htmlspecialchars($p->departId) ?>
                                <i class="fas fa-arrow-right" style="color:#ccc;margin:0 4px;font-size:0.75rem;"></i>
                                <?= htmlspecialchars($p->destinationId) ?>
                            </td>
                            <td style="text-align:center;"><?= $p->nombrePassages ?></td>
                            <td><?= htmlspecialchars($p->numeroPaiement ?? '-') ?></td>
                            <td class="montant-value">
                                <?= number_format((float)($p->montant_payer ?? 0), 0, ',', ' ') ?> FCFA
                            </td>
                            <td>
                                <?php
                                $v = $p->validation_billets ?? '';
                                $s = $p->status_reservation ?? '';
                                if ($v === 'valider') {
                                    echo '<span class="badge badge-success"><i class="fas fa-check"></i> Validé</span>';
                                } elseif ($v === 'en_attente') {
                                    echo '<span class="badge badge-warning"><i class="fas fa-clock"></i> En attente</span>';
                                } else {
                                    echo '<span class="badge badge-info"><i class="fas fa-check"></i> Confirmé</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer style="text-align:center;padding:32px;color:#aaa;font-size:0.82rem;">
        &copy; <?= date('Y') ?> TransGest — Espace Client
    </footer>
</body>
</html>
