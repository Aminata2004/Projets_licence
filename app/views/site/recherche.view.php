<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Résultats de recherche - TransGest</title>
    <link href="<?= BASE_URL ?>/assets_site/css/inter.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets_site/css/all.min.css">
    <link href="<?= BASE_URL ?>/assets_site/css/aos.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6fa;
            color: #1a1f2e;
            line-height: 1.5;
        }
        :root {
            --primary: #0f3b5e;
            --primary-dark: #0a2a44;
            --primary-light: #1a5276;
            --secondary: #e67e22;
            --gray-light: #ecf0f1;
            --gray: #7f8c8d;
            --dark: #2c3e50;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 5px 20px rgba(0, 0, 0, 0.08);
            --radius: 8px;
            --radius-lg: 12px;
        }
        h1, h2, h3, h4 { font-weight: 700; }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        section { padding: 40px 0; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 28px; border-radius: var(--radius); font-weight: 600; font-size: 0.9rem;
            transition: all 0.3s; cursor: pointer; border: none; text-decoration: none;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); }
        .btn-block { width: 100%; }

        .page-header {
            background: var(--primary);
            color: white;
            padding: 40px 0;
        }
        .page-header h1 { font-size: 1.8rem; margin-bottom: 8px; }
        .page-header p { opacity: 0.85; font-size: 0.9rem; }

        .search-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 28px;
            margin-top: -32px;
            position: relative;
            z-index: 5;
        }
        .search-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            align-items: end;
        }
        .form-group label {
            display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 8px;
            color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px;
        }
        .form-control, .form-select {
            width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: var(--radius);
            font-size: 0.9rem;
        }
        .form-control:focus, .form-select:focus { outline: none; border-color: var(--secondary); }

        .results-count { margin: 32px 0 16px; color: var(--gray); font-size: 0.9rem; }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .result-card {
            background: white;
            border-radius: var(--radius-lg);
            border-left: 4px solid var(--primary);
            box-shadow: var(--shadow);
            padding: 22px;
            transition: all 0.3s;
        }
        .result-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-left-color: var(--secondary);
        }
        .result-card-top {
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;
        }
        .result-compagnie {
            display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; color: var(--gray);
        }
        .result-compagnie img { width: 24px; height: 24px; object-fit: contain; border-radius: 4px; }
        .result-price {
            font-weight: 700; font-size: 0.8rem; color: white; background: var(--secondary);
            padding: 5px 14px; border-radius: 20px; white-space: nowrap;
        }
        .result-route {
            font-size: 1.05rem; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; color: var(--dark);
        }
        .result-route i { color: var(--gray); font-size: 0.8rem; }
        .result-heure {
            font-size: 0.85rem; color: var(--gray); display: flex; align-items: center; gap: 6px; margin-bottom: 16px;
        }
        .result-book {
            display: block; text-align: center; background: var(--gray-light); color: var(--primary);
            font-weight: 600; font-size: 0.85rem; padding: 10px; border-radius: var(--radius);
            text-decoration: none; transition: all 0.2s;
        }
        .result-book:hover { background: var(--primary); color: white; }

        .no-results {
            text-align: center; padding: 60px 20px; background: white; border-radius: var(--radius-lg);
            box-shadow: var(--shadow); color: var(--gray);
        }
        .no-results i { font-size: 2.5rem; color: var(--gray-light); margin-bottom: 16px; display: block; }

        .footer { background: #1a1f2e; color: #94a3b8; padding: 48px 0 24px; margin-top: 40px; }
        .footer-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 48px; margin-bottom: 48px; }
        .footer h4 { color: white; font-size: 1rem; margin-bottom: 20px; }
        .footer a { color: #94a3b8; text-decoration: none; display: block; margin-bottom: 12px; font-size: 0.85rem; }
        .footer a:hover { color: var(--secondary); }
        .footer-bottom { text-align: center; padding-top: 24px; border-top: 1px solid #334155; font-size: 0.75rem; }

        @media (max-width: 992px) {
            .search-grid { grid-template-columns: repeat(3, 1fr); }
            .results-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .search-grid { grid-template-columns: 1fr; }
            .results-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<?php $this->view('site/partials/nav') ?>

<section class="page-header">
    <div class="container">
        <h1>Résultats de recherche</h1>
        <p>
            <?= !empty($depart) ? htmlspecialchars($depart) : 'Toutes les villes' ?>
            <i class="fas fa-long-arrow-alt-right"></i>
            <?= !empty($destination) ? htmlspecialchars($destination) : 'Toutes les destinations' ?>
        </p>
    </div>
</section>

<section style="padding-top: 0;">
    <div class="container">
        <div class="search-card" data-aos="fade-up">
            <form action="<?= BASE_URL ?>/site/Recherche" method="GET" class="search-grid">
                <div class="form-group">
                    <label>Départ</label>
                    <select name="depart" class="form-select">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= htmlspecialchars($ville) ?>" <?= $depart === $ville ? 'selected' : '' ?>><?= htmlspecialchars($ville) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Destination</label>
                    <select name="destination" class="form-select">
                        <option value="">Toutes les destinations</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= htmlspecialchars($ville) ?>" <?= $destination === $ville ? 'selected' : '' ?>><?= htmlspecialchars($ville) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Compagnie</label>
                    <select name="compagnie" class="form-select">
                        <option value="">Toutes les compagnies</option>
                        <?php if (!empty($listecompagnie)): foreach ($listecompagnie as $c): ?>
                            <option value="<?= $c->id_compagnie ?>" <?= (string)$id_compagnie === (string)$c->id_compagnie ? 'selected' : '' ?>><?= htmlspecialchars($c->nom_compagnie) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>" min="<?= date('Y-m-d') ?>">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Rechercher</button>
                </div>
            </form>
        </div>

        <p class="results-count"><?= count($resultats) ?> trajet(s) trouvé(s)</p>

        <?php if (!empty($resultats)): ?>
            <div class="results-grid">
                <?php foreach ($resultats as $r): ?>
                    <div class="result-card" data-aos="fade-up">
                        <div class="result-card-top">
                            <span class="result-compagnie">
                                <?php if (!empty($r->logo)): ?>
                                    <img src="<?= BASE_URL ?>/images/logos/<?= htmlspecialchars($r->logo) ?>" alt="">
                                <?php endif; ?>
                                <?= htmlspecialchars($r->nom_compagnie) ?>
                            </span>
                            <span class="result-price"><?= number_format((float)$r->prix, 0, ',', ' ') ?> FCFA</span>
                        </div>
                        <div class="result-route">
                            <?= htmlspecialchars($r->departLocalite) ?> <i class="fas fa-long-arrow-alt-right"></i> <?= htmlspecialchars($r->destinationLocalite) ?>
                        </div>
                        <p class="result-heure"><i class="far fa-clock"></i> Départ à <?= htmlspecialchars(substr($r->heureDepart, 0, 5)) ?></p>
                        <a href="<?= BASE_URL ?>/site/Reservation_formulaire?id=<?= $r->idProgrammer ?>" class="result-book">Réserver <i class="fas fa-arrow-right"></i></a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-route"></i>
                Aucun trajet ne correspond à votre recherche.<br>Essayez une autre ville ou une autre compagnie.
            </div>
        <?php endif; ?>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <h4>TransGest</h4>
                <p style="font-size: 0.85rem;">La plateforme N°1 de réservation de billets de bus et suivi de colis au Mali.</p>
            </div>
            <div>
                <h4>Liens rapides</h4>
                <a href="<?= BASE_URL ?>/site/Accueil">Accueil</a>
                <a href="<?= BASE_URL ?>/site/compagnies">Compagnies</a>
                <a href="<?= BASE_URL ?>/site/Contact">Contact</a>
            </div>
            <div>
                <h4>Support</h4>
                <a href="<?= BASE_URL ?>/site/Contact">Contact</a>
            </div>
            <div>
                <h4>Contact</h4>
                <a href="#"><i class="fas fa-map-marker-alt"></i> Bamako, Mali</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 TransGest - Tous droits réservés</p>
        </div>
    </div>
</footer>

<script src="<?= BASE_URL ?>/assets_site/js/aos.js"></script>
<script>AOS.init({ duration: 600, once: true, offset: 50 });</script>
</body>
</html>
