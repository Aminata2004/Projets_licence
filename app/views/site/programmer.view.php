<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Tous les voyages - TransGest</title>
    <link href="<?= BASE_URL ?>/assets_site/css/inter.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets_site/css/all.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets_site/css/aos.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            color: #1a1f2e;
            overflow-x: hidden;
        }

        :root {
            --primary: #0f3b5e;
            --primary-dark: #0a2a44;
            --primary-light: #1a5276;
            --secondary: #e67e22;
            --secondary-dark: #c0392b;
            --accent: #3498db;
            --success: #27ae60;
            --warning: #f39c12;
            --gray-light: #ecf0f1;
            --gray: #7f8c8d;
            --dark: #2c3e50;
            --white: #ffffff;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 5px 20px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 15px 35px rgba(0, 0, 0, 0.1);
            --radius: 8px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        /* ========== HEADER ========== */
        .header {
            background: white;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
        }
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
        }
        .logo span {
            color: var(--secondary);
        }
        .nav {
            display: flex;
            gap: 32px;
            align-items: center;
        }
        .nav a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav a:hover, .nav a.active {
            color: var(--secondary);
        }
        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 8px 20px;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--primary);
            cursor: pointer;
        }
        .mobile-nav {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 350px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
            z-index: 2000;
            padding: 80px 30px 30px;
            transition: right 0.3s ease;
        }
        .mobile-nav.active { right: 0; }
        .mobile-nav a {
            display: block;
            padding: 15px 0;
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            border-bottom: 1px solid #eee;
        }
        .close-menu {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.8rem;
            cursor: pointer;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1500;
            display: none;
        }
        .overlay.active { display: block; }

        /* ========== PAGE HEADER ========== */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 16px;
        }
        .page-header p {
            font-size: 1rem;
            opacity: 0.85;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ========== FILTRES ========== */
        .filters-section {
            margin-top: -30px;
            position: relative;
            z-index: 10;
        }
        .filters-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 25px 30px;
            box-shadow: var(--shadow-md);
        }
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            align-items: end;
        }
        .filter-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .filter-select, .filter-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: var(--radius);
            font-size: 0.9rem;
            background: #f8fafc;
            transition: all 0.3s;
        }
        .filter-select:focus, .filter-input:focus {
            outline: none;
            border-color: var(--secondary);
        }
        .btn-filter {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }
        .btn-filter:hover {
            background: var(--secondary-dark);
            transform: translateY(-2px);
        }

        /* ========== GRILLE DES VOYAGES ========== */
        .trips-section {
            padding: 60px 0;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .section-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
        }
        .result-count {
            color: var(--gray);
            font-size: 0.9rem;
        }
        .trips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }
        .trip-card {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            position: relative;
        }
        .trip-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        .trip-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--secondary);
            color: white;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            z-index: 2;
        }
        .trip-image {
            height: 180px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .trip-company {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px 0 20px;
        }
        .company-avatar {
            width: 40px;
            height: 40px;
            background: var(--gray-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--primary);
        }
        .company-name {
            font-weight: 700;
            font-size: 1rem;
        }
        .trip-details {
            padding: 16px 20px 20px;
        }
        .trip-route {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        .route-point {
            flex: 1;
        }
        .route-city {
            font-weight: 700;
            font-size: 1.1rem;
        }
        .route-time {
            font-size: 0.75rem;
            color: var(--gray);
        }
        .route-arrow {
            color: var(--secondary);
            font-size: 1.2rem;
        }
        .trip-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-top: 12px;
            border-top: 1px solid #eef2f6;
        }
        .info-item {
            text-align: center;
        }
        .info-label {
            font-size: 0.7rem;
            color: var(--gray);
        }
        .info-value {
            font-weight: 700;
            font-size: 0.9rem;
        }
        .trip-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid #eef2f6;
        }
        .price {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--secondary);
        }
        .btn-book {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-book:hover {
            background: var(--primary-dark);
            transform: scale(1.02);
        }

        /* ========== PAGINATION ========== */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 50px;
        }
        .page-link {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: var(--radius);
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: all 0.3s;
        }
        .page-link:hover, .page-link.active {
            background: var(--secondary);
            color: white;
        }

        /* ========== FOOTER ========== */
        .footer {
            background: #1a1f2e;
            color: #94a3b8;
            padding: 48px 0 24px;
            margin-top: 60px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 48px;
            margin-bottom: 48px;
        }
        .footer h4 {
            color: white;
            font-size: 1rem;
            margin-bottom: 20px;
        }
        .footer a {
            color: #94a3b8;
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            font-size: 0.85rem;
        }
        .footer a:hover { color: var(--secondary); }
        .footer-bottom {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid #334155;
            font-size: 0.75rem;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .filters-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 768px) {
            .nav { display: none; }
            .menu-toggle { display: block; }
            .filters-grid {
                grid-template-columns: 1fr;
            }
            .trips-grid {
                grid-template-columns: 1fr;
            }
            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .footer-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .page-header h1 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<?php $this->view('site/partials/nav') ?>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">
            <?php if (!empty($compagnie)): ?>
                <i class="fas fa-bus" style="font-size:1.8rem;margin-right:10px;"></i>
                <?= htmlspecialchars($compagnie->nom_compagnie ?? 'La Compagnie') ?>
            <?php else: ?>
                Tous nos voyages
            <?php endif; ?>
        </h1>
        <p data-aos="fade-up" data-aos-delay="100">
            <?php if (!empty($compagnie) && !empty($compagnie->slogant)): ?>
                <?= htmlspecialchars($compagnie->slogant) ?>
            <?php else: ?>
                Découvrez tous les trajets disponibles vers vos destinations préférées
            <?php endif; ?>
        </p>
    </div>
</section>

<!-- FILTRES -->
<section class="filters-section">
    <div class="container">
        <div class="filters-card" data-aos="fade-up">
            <div class="filters-grid">
                <div class="filter-group">
                    <label><i class="fas fa-map-marker-alt"></i> Départ</label>
                    <select class="filter-select">
                        <option value="">Toutes les villes</option>
                        <option>Bamako</option>
                        <option>Ségou</option>
                        <option>Mopti</option>
                        <option>Kayes</option>
                        <option>Sikasso</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-flag-checkered"></i> Destination</label>
                    <select class="filter-select">
                        <option value="">Toutes les destinations</option>
                        <option>Bamako</option>
                        <option>Ségou</option>
                        <option>Mopti</option>
                        <option>Kayes</option>
                        <option>Sikasso</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-calendar-alt"></i> Date</label>
                    <input type="date" class="filter-input" value="2024-12-25">
                </div>
                <div class="filter-group">
                    <button class="btn-filter"><i class="fas fa-search"></i> Rechercher</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LISTE DES VOYAGES -->
<section class="trips-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Programmes disponibles</h2>
            <span class="result-count"><?= !empty($programmes) ? count($programmes) . ' voyage(s) trouvé(s)' : 'Aucun voyage disponible' ?></span>
        </div>

        <?php if (!empty($programmes)): ?>
        <?php
        // Regrouper les programmes par ville de départ
        $groupedByDepart = [];
        foreach ($programmes as $programme) {
            $depart = htmlspecialchars(($programme->departLocalite ?? $programme->idDepart) . ' (' . ($programme->numeroGare1 ?? '') . ')');
            $groupedByDepart[$depart][] = $programme;
        }
        ?>

        <?php foreach ($groupedByDepart as $depart => $programmesDepart): ?>
        <div class="depart-group" style="margin-bottom:40px;">
            <div style="background:linear-gradient(135deg,var(--primary),var(--primary-light));color:white;padding:12px 20px;border-radius:var(--radius);margin-bottom:20px;display:flex;align-items:center;gap:10px;font-weight:700;font-size:1rem;" data-aos="fade-right">
                <i class="fas fa-map-marker-alt"></i>
                Départ depuis : <?= $depart ?>
            </div>

            <div class="trips-grid">
                <?php foreach ($programmesDepart as $index => $programme): ?>
                <div class="trip-card" data-aos="fade-up" data-aos-delay="<?= ($index % 3) * 100 ?>">
                    <div class="trip-badge">
                        <i class="fas fa-clock"></i> <?= htmlspecialchars($programme->heureDepart ?? '--') ?>
                    </div>
                    <div class="trip-company" style="padding:20px 20px 0;">
                        <div class="company-avatar"><i class="fas fa-bus"></i></div>
                        <div>
                            <div class="company-name"><?= htmlspecialchars($compagnie->nom_compagnie ?? 'Compagnie') ?></div>
                            <?php if (!empty($programme->rdv)): ?>
                            <div style="font-size:0.75rem;color:var(--gray);">RDV : <?= htmlspecialchars($programme->rdv) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="trip-details">
                        <div class="trip-route">
                            <div class="route-point">
                                <div class="route-city"><?= htmlspecialchars($programme->departLocalite ?? 'Départ') ?></div>
                                <div class="route-time"><?= htmlspecialchars($programme->heureDepart ?? '--') ?></div>
                            </div>
                            <div class="route-arrow"><i class="fas fa-arrow-right"></i></div>
                            <div class="route-point">
                                <div class="route-city"><?= htmlspecialchars($programme->destinationLocalite ?? $programme->idDestination ?? 'Destination') ?></div>
                                <div class="route-time"><?= !empty($programme->numeroGare2) ? 'Gare : ' . htmlspecialchars($programme->numeroGare2) : '' ?></div>
                            </div>
                        </div>

                        <?php if (!empty($programme->escales_avec_frais)): ?>
                        <div style="padding:10px 0;border-top:1px solid #eef2f6;margin-top:10px;">
                            <div style="font-size:0.75rem;color:var(--gray);margin-bottom:6px;"><i class="fas fa-code-branch"></i> Escales disponibles</div>
                            <?php
                            $escales = explode(', ', $programme->escales_avec_frais);
                            foreach ($escales as $escale): ?>
                                <span style="display:inline-block;background:#e8f4fd;color:var(--primary);padding:3px 10px;border-radius:50px;font-size:0.72rem;font-weight:600;margin:2px;"><?= htmlspecialchars($escale) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <div class="trip-price">
                            <span class="price"><?= number_format($programme->prix ?? 0, 0, ',', ' ') ?> FCFA</span>
                            <a href="<?= BASE_URL ?>/site/Reservation_formulaire?id=<?= $programme->idProgrammer ?>" class="btn-book">
                                Réserver <i class="fas fa-arrow-right" style="margin-left:5px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php else: ?>
        <div style="text-align:center;padding:80px 20px;">
            <i class="fas fa-bus" style="font-size:4rem;color:#ddd;margin-bottom:20px;"></i>
            <h3 style="color:var(--gray);margin-bottom:10px;">Aucun programme disponible</h3>
            <p style="color:#aaa;">Cette compagnie n'a pas encore de trajets programmés.</p>
            <a href="<?= BASE_URL ?>/site/compagnies" style="display:inline-block;margin-top:20px;padding:12px 24px;background:var(--secondary);color:white;border-radius:var(--radius);text-decoration:none;font-weight:600;">Voir d'autres compagnies</a>
        </div>
        <?php endif; ?>
    </div>
</section>


    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div><h4>TransGest</h4><p style="font-size: 0.85rem;">La plateforme N°1 de réservation de billets de bus et suivi de colis au Mali.</p></div>
            <div><h4>Liens rapides</h4>
                <a href="<?= BASE_URL ?>/site/Accueil">Accueil</a>
                <a href="<?= BASE_URL ?>/site/compagnies">Compagnies</a>
                <a href="<?= BASE_URL ?>/site/Contact">Contact</a>
            </div>
            <div><h4>Support</h4><a href="#">FAQ</a><a href="#">Conditions générales</a><a href="#">Politique de confidentialité</a></div>
            <div><h4>Contact</h4><a href="#"><i class="fas fa-phone"></i> +223 20 00 00 00</a><a href="#"><i class="fas fa-envelope"></i> contact@transgest.com</a><a href="#"><i class="fas fa-map-marker-alt"></i> Bamako, Mali</a></div>
        </div>
        <div class="footer-bottom"><p>&copy; 2024 TransGest - Tous droits réservés</p></div>
    </div>
</footer>

<script src="<?= BASE_URL ?>/assets_site/js/aos.js"></script>
<script>
    AOS.init({ duration: 600, once: true, offset: 50 });
</script>
</body>
</html>