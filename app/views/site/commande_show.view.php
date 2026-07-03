<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Commande - TransGest</title>
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
            max-width: 960px;
            margin: 0 auto;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo img { height: 55px; }
        .back-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .back-link:hover { text-decoration: underline; }

        .container { max-width: 960px; margin: 0 auto; padding: 32px 24px; }

        .page-title {
            font-size: 1.4rem;
            color: var(--primary);
            margin-bottom: 6px;
        }
        .breadcrumb {
            color: #888;
            font-size: 0.82rem;
            margin-bottom: 28px;
        }
        .breadcrumb a { color: var(--primary); text-decoration: none; }

        /* STATUS BANNER */
        .status-banner {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }
        .status-banner.attente {
            background: #fff8e1;
            border: 1px solid #f39c12;
            color: #856404;
        }
        .status-banner.valide {
            background: #d4edda;
            border: 1px solid #27ae60;
            color: #155724;
        }
        .status-banner i { font-size: 1.3rem; }

        /* ALERT */
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }
        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        /* CARD */
        .card {
            background: white;
            border-radius: 14px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f0f0f0;
            background: #f8fafc;
        }
        .card-header h3 {
            font-size: 0.95rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-body { padding: 24px; }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }
        .detail-item label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .detail-item .value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2c3e50;
        }
        .detail-item .value.highlight {
            color: var(--primary);
            font-size: 1.05rem;
        }

        /* TRAJET VISUEL */
        .trajet-visual {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px 0;
        }
        .city-block { text-align: center; flex: 1; }
        .city-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary);
        }
        .city-label { font-size: 0.75rem; color: #888; margin-top: 2px; }
        .arrow-line {
            flex: 2;
            display: flex;
            align-items: center;
            gap: 0;
        }
        .arrow-line::before, .arrow-line::after {
            content: '';
            flex: 1;
            height: 2px;
            background: #ddd;
        }
        .arrow-line i { color: var(--secondary); font-size: 1.4rem; }

        /* BOUTON VALIDER */
        .valider-section {
            background: linear-gradient(135deg, #0f3b5e, #1a5276);
            border-radius: 14px;
            padding: 28px 24px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }
        .valider-section h3 { font-size: 1.1rem; margin-bottom: 8px; }
        .valider-section p { font-size: 0.85rem; opacity: 0.8; margin-bottom: 20px; }
        .btn-valider {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 14px 36px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-valider:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info    { background: #d1ecf1; color: #0c5460; }

        @media (max-width: 600px) {
            .detail-grid { grid-template-columns: 1fr 1fr; }
            .trajet-visual { flex-direction: column; }
            .arrow-line { transform: rotate(90deg); width: 40px; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <a href="<?= BASE_URL ?>/site/Accueil">
                <img src="<?= BASE_URL ?>/images/logos/transgest_logo.png" alt="TransGest" style="height:55px;">
            </a>
            <a href="<?= BASE_URL ?>/site/EspaceClient/dashboard" class="back-link">
                <i class="fas fa-arrow-left"></i> Mes réservations
            </a>
        </div>
    </header>

    <div class="container">
        <h1 class="page-title">Détail de la commande</h1>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/site/EspaceClient/dashboard">Mes réservations</a>
            &rsaquo; <?= htmlspecialchars($commande->numeroBillets) ?>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'success'): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    Votre réservation a été validée avec succès !
                </div>
            <?php else: ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    Une erreur s'est produite. Votre réservation est peut-être déjà validée.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php
        $estEnAttente = ($commande->validation_billets === 'en_attente');
        $estValidee   = ($commande->validation_billets === 'valider');
        ?>

        <!-- Bannière statut -->
        <?php if ($estEnAttente): ?>
        <div class="status-banner attente">
            <i class="fas fa-clock"></i>
            <div>
                <div>Réservation en attente de validation</div>
                <div style="font-weight:400;font-size:0.82rem;margin-top:2px;">Vérifiez les détails ci-dessous puis cliquez sur "Valider ma réservation"</div>
            </div>
        </div>
        <?php elseif ($estValidee): ?>
        <div class="status-banner valide">
            <i class="fas fa-check-circle"></i>
            <div>Réservation confirmée et validée</div>
        </div>
        <?php endif; ?>

        <!-- Trajet -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-route"></i> Trajet</h3>
            </div>
            <div class="card-body">
                <div class="trajet-visual">
                    <div class="city-block">
                        <div class="city-name"><?= htmlspecialchars($commande->departId) ?></div>
                        <div class="city-label">Départ</div>
                    </div>
                    <div class="arrow-line"><i class="fas fa-long-arrow-alt-right"></i></div>
                    <div class="city-block">
                        <div class="city-name"><?= htmlspecialchars($commande->destinationId) ?></div>
                        <div class="city-label">Destination</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations du voyage -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Informations du voyage</h3>
            </div>
            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>N° de billet</label>
                        <div class="value highlight"><?= htmlspecialchars($commande->numeroBillets) ?></div>
                    </div>
                    <div class="detail-item">
                        <label>Date de voyage</label>
                        <div class="value"><?= date('d/m/Y', strtotime($commande->jourVoyage)) ?></div>
                    </div>
                    <div class="detail-item">
                        <label>Heure de départ</label>
                        <div class="value"><?= substr($commande->Heur_departs, 0, 5) ?></div>
                    </div>
                    <div class="detail-item">
                        <label>Nombre de passagers</label>
                        <div class="value"><?= $commande->nombrePassages ?></div>
                    </div>
                    <?php if (!empty($commande->numeroPlace) && $commande->numeroPlace !== '-'): ?>
                    <div class="detail-item">
                        <label>Numéro(s) de place</label>
                        <div class="value"><?= htmlspecialchars($commande->numeroPlace) ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="detail-item">
                        <label>Statut</label>
                        <div class="value">
                            <?php if ($estValidee): ?>
                                <span class="badge badge-success"><i class="fas fa-check"></i> Validée</span>
                            <?php elseif ($estEnAttente): ?>
                                <span class="badge badge-warning"><i class="fas fa-clock"></i> En attente</span>
                            <?php else: ?>
                                <span class="badge badge-info"><i class="fas fa-check"></i> Confirmée</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($commande->date_expiration)): ?>
                    <div class="detail-item">
                        <label>Expiration</label>
                        <div class="value"><?= date('d/m/Y', strtotime($commande->date_expiration)) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Informations client et paiement -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user"></i> Informations personnelles & paiement</h3>
            </div>
            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Nom complet</label>
                        <div class="value"><?= htmlspecialchars($commande->Client) ?></div>
                    </div>
                    <div class="detail-item">
                        <label>Téléphone</label>
                        <div class="value"><?= htmlspecialchars($commande->numeroClient) ?></div>
                    </div>
                    <?php if (!empty($commande->emailClient)): ?>
                    <div class="detail-item">
                        <label>Email</label>
                        <div class="value"><?= htmlspecialchars($commande->emailClient) ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($commande->numeroPaiement)): ?>
                    <div class="detail-item">
                        <label>N° de paiement</label>
                        <div class="value"><?= htmlspecialchars($commande->numeroPaiement) ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($commande->montant_payer)): ?>
                    <div class="detail-item">
                        <label>Montant payé</label>
                        <div class="value highlight"><?= number_format((float)$commande->montant_payer, 0, ',', ' ') ?> FCFA</div>
                    </div>
                    <?php endif; ?>
                    <div class="detail-item">
                        <label>Date de réservation</label>
                        <div class="value">
                            <?= $commande->date_reservation ? date('d/m/Y', strtotime($commande->date_reservation)) : '-' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOUTON VALIDER (uniquement si en attente) -->
        <?php if ($estEnAttente): ?>
        <div class="valider-section">
            <h3><i class="fas fa-shield-alt"></i> Confirmer votre réservation</h3>
            <p>Après validation, votre billet sera définitivement enregistré et votre place réservée.</p>
            <form method="POST" action="<?= BASE_URL ?>/site/EspaceClient/valider"
                  onsubmit="return confirm('Confirmer la validation de cette réservation ?')">
                <input type="hidden" name="idBillets" value="<?= $commande->idBillets ?>">
                <button type="submit" class="btn-valider">
                    <i class="fas fa-check-double"></i> Valider ma réservation
                </button>
            </form>
        </div>
        <?php endif; ?>

    </div>

    <footer style="text-align:center;padding:32px;color:#aaa;font-size:0.82rem;">
        &copy; <?= date('Y') ?> TransGest — Espace Client
    </footer>
</body>
</html>
