<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace - TransGest</title>
    <link href="<?= BASE_URL ?>/assets_site/css/inter.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets_site/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fb; color: #1a1f2e; }
        :root {
            --primary: #0f3b5e;
            --secondary: #e67e22;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --radius: 8px;
            --shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        /* HEADER */
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
        .header-actions { display: flex; align-items: center; gap: 12px; }
        .client-greeting {
            font-size: 0.9rem;
            color: #555;
            font-weight: 500;
        }
        .client-greeting strong { color: var(--primary); }
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
            transition: all 0.2s;
        }
        .btn-logout:hover { background: #fee2e2; }

        /* MAIN */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        .page-header {
            padding: 32px 0 20px;
        }
        .page-header h1 { font-size: 1.6rem; color: var(--primary); }
        .page-header p { color: #7f8c8d; font-size: 0.9rem; margin-top: 4px; }

        /* NAV TABS */
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

        /* ORDERS TABLE */
        .card {
            background: white;
            border-radius: 12px;
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
            font-size: 0.8rem;
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
        .badge-danger  { background: #f8d7da; color: #721c24; }

        .btn-detail {
            background: var(--primary);
            color: white;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: opacity 0.2s;
        }
        .btn-detail:hover { opacity: 0.85; }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #aaa;
        }
        .empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }

        footer { text-align: center; padding: 32px; color: #aaa; font-size: 0.82rem; }

        @media (max-width: 600px) {
            .nav-tab span { display: none; }
            .header-inner { flex-wrap: wrap; gap: 10px; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <a href="<?= BASE_URL ?>/site/Accueil">
                <img src="<?= BASE_URL ?>/images/logos/transgest_logo.png" alt="TransGest" style="height:55px;">
            </a>
            <div class="header-actions">
                <span class="client-greeting">Bonjour, <strong><?= htmlspecialchars($_SESSION['client_nom']) ?></strong></span>
                <a href="<?= BASE_URL ?>/site/EspaceClient/logout" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-user-circle" style="color:var(--primary);margin-right:8px;"></i>Mon Espace Client</h1>
            <p>Gérez vos réservations, consultez vos paiements et votre épargne</p>
        </div>

        <!-- Onglets de navigation -->
        <div class="nav-tabs">
            <a href="<?= BASE_URL ?>/site/EspaceClient/dashboard" class="nav-tab active">
                <i class="fas fa-ticket-alt"></i> <span>Mes Commandes</span>
            </a>
            <a href="<?= BASE_URL ?>/site/EspaceClient/mesPaiements" class="nav-tab">
                <i class="fas fa-credit-card"></i> <span>Mes Paiements</span>
            </a>
            <a href="<?= BASE_URL ?>/site/EspaceClient/monEpargne" class="nav-tab">
                <i class="fas fa-piggy-bank"></i> <span>Mon Épargne</span>
            </a>
        </div>

        <!-- Liste des commandes -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-list-ul" style="margin-right:6px;"></i>Mes Réservations</h2>
                <span style="font-size:0.82rem;color:#888;"><?= count($commandes) ?> commande(s)</span>
            </div>
            <div class="table-wrap">
                <?php if (empty($commandes)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Aucune réservation trouvée.</p>
                    </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>N° Billet</th>
                            <th>Trajet</th>
                            <th>Date voyage</th>
                            <th>Heure</th>
                            <th>Passagers</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commandes as $c): ?>
                        <tr>
                            <td><strong style="color:var(--primary);"><?= htmlspecialchars($c->numeroBillets) ?></strong></td>
                            <td>
                                <?= htmlspecialchars($c->departId) ?>
                                <i class="fas fa-long-arrow-alt-right" style="color:#aaa;margin:0 4px;"></i>
                                <?= htmlspecialchars($c->destinationId) ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($c->jourVoyage)) ?></td>
                            <td><?= substr($c->Heur_departs, 0, 5) ?></td>
                            <td><?= $c->nombrePassages ?></td>
                            <td>
                                <?php
                                $status = $c->validation_billets ?? $c->status_reservation;
                                if ($status === 'valider') {
                                    echo '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Validée</span>';
                                } elseif ($status === 'en_attente') {
                                    echo '<span class="badge badge-warning"><i class="fas fa-clock"></i> En attente</span>';
                                } elseif ($c->status_reservation === 'en_ligne') {
                                    echo '<span class="badge badge-info"><i class="fas fa-globe"></i> En ligne</span>';
                                } else {
                                    echo '<span class="badge badge-info"><i class="fas fa-check"></i> Confirmée</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/site/EspaceClient/show/<?= $c->idBillets ?>" class="btn-detail">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        &copy; <?= date('Y') ?> TransGest — Espace Client
    </footer>
</body>
</html>
