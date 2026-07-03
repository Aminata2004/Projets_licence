<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Épargne - TransGest</title>
    <link href="<?= BASE_URL ?>/assets_site/css/inter.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets_site/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fb; color: #1a1f2e; }
        :root {
            --primary: #0f3b5e;
            --secondary: #e67e22;
            --success: #27ae60;
            --danger: #e74c3c;
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

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-icon.depot    { background: #d4edda; color: var(--success); }
        .stat-icon.retrait  { background: #f8d7da; color: var(--danger); }
        .stat-icon.solde    { background: #d1ecf1; color: #0c5460; }
        .stat-label { font-size: 0.78rem; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-value { font-size: 1.35rem; font-weight: 800; margin-top: 2px; }
        .stat-value.green  { color: var(--success); }
        .stat-value.red    { color: var(--danger); }
        .stat-value.blue   { color: var(--primary); }

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
        .amount-depot  { color: var(--success); font-weight: 700; }
        .amount-retrait { color: var(--danger); font-weight: 700; }
        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .type-depot   { background: #d4edda; color: #155724; }
        .type-retrait { background: #f8d7da; color: #721c24; }
        .empty-state {
            text-align: center;
            padding: 60px 24px;
            color: #aaa;
        }
        .empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }

        @media (max-width: 700px) {
            .stats-grid { grid-template-columns: 1fr; }
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
            <a href="<?= BASE_URL ?>/site/EspaceClient/mesPaiements" class="nav-tab">
                <i class="fas fa-credit-card"></i> <span>Mes Paiements</span>
            </a>
            <a href="<?= BASE_URL ?>/site/EspaceClient/monEpargne" class="nav-tab active">
                <i class="fas fa-piggy-bank"></i> <span>Mon Épargne</span>
            </a>
        </div>

        <!-- Résumé épargne -->
        <?php
        $totalDepot   = $solde ? (float)$solde->total_depot  : 0;
        $totalRetrait = $solde ? (float)$solde->total_retrait : 0;
        $soldeNet     = $totalDepot - $totalRetrait;
        ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon depot"><i class="fas fa-arrow-down"></i></div>
                <div>
                    <div class="stat-label">Total Dépôts</div>
                    <div class="stat-value green"><?= number_format($totalDepot, 0, ',', ' ') ?> F</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon retrait"><i class="fas fa-arrow-up"></i></div>
                <div>
                    <div class="stat-label">Total Retraits</div>
                    <div class="stat-value red"><?= number_format($totalRetrait, 0, ',', ' ') ?> F</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon solde"><i class="fas fa-wallet"></i></div>
                <div>
                    <div class="stat-label">Solde Disponible</div>
                    <div class="stat-value blue"><?= number_format($soldeNet, 0, ',', ' ') ?> F</div>
                </div>
            </div>
        </div>

        <!-- Historique -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-history" style="margin-right:6px;"></i>Historique des opérations</h2>
                <span style="font-size:0.82rem;color:#888;"><?= count($epargne) ?> opération(s)</span>
            </div>
            <div class="table-wrap">
                <?php if (empty($epargne)): ?>
                    <div class="empty-state">
                        <i class="fas fa-piggy-bank"></i>
                        <p>Aucune opération d'épargne enregistrée.</p>
                    </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Référence</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($epargne as $op): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($op->date_operation)) ?></td>
                            <td>
                                <?php if ($op->type_operation === 'depot'): ?>
                                    <span class="type-badge type-depot">
                                        <i class="fas fa-arrow-down"></i> Dépôt
                                    </span>
                                <?php else: ?>
                                    <span class="type-badge type-retrait">
                                        <i class="fas fa-arrow-up"></i> Retrait
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="<?= $op->type_operation === 'depot' ? 'amount-depot' : 'amount-retrait' ?>">
                                <?= $op->type_operation === 'depot' ? '+' : '-' ?>
                                <?= number_format((float)$op->montant, 0, ',', ' ') ?> FCFA
                            </td>
                            <td><?= htmlspecialchars($op->reference_operation ?? '-') ?></td>
                            <td><?= htmlspecialchars($op->description ?? '-') ?></td>
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
