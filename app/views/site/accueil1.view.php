<!--<< All JS Plugins >>-->
<?php $this->view('site/partials/header') ?>
<!-- Bootstrap CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
        color: #1e293b;
    }

    /* Animation hover card - Premium */
    .card {
        border: none;
        transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .card:hover {
        transform: translateY(-10px) scale(1.01);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .city-block_one {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
    }

    .city-block_one-image {
        position: relative;
    }

    .city-block_one-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: flex-end;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .destination-card {
        position: relative;
        height: 340px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .destination-card img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .destination-card:hover img {
        transform: scale(1.1);
    }

    .destination-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 50%, transparent 100%);
        color: white;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        opacity: 0;
        transition: opacity 0.5s ease, transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        transform: translateY(30px);
        padding: 25px !important;
        backdrop-filter: blur(2px);
    }

    .destination-card:hover .destination-overlay {
        opacity: 1;
        transform: translateY(0);
    }
</style>
<body>
    <!-- Preloader Start -->
    <div id="preloader" class="preloader">
        <div class="animation-preloader">
            <div class="spinner">
            </div>
            <div class="txt-loading">
                <span data-text-preloader="B" class="letters-loading">B</span>
                <span data-text-preloader="U" class="letters-loading">U</span>
                <span data-text-preloader="S" class="letters-loading">S</span>
                <span data-text-preloader="L" class="letters-loading" style="color:#fbbf24;">L</span>
                <span data-text-preloader="I" class="letters-loading" style="color:#fbbf24;">I</span>
                <span data-text-preloader="N" class="letters-loading" style="color:#fbbf24;">N</span>
                <span data-text-preloader="K" class="letters-loading" style="color:#fbbf24;">K</span>
            </div>
            <p class="text-center" style="letter-spacing:3px; font-size:0.8rem; opacity:0.7;">Chargement en cours...</p>
        </div>
        <div class="loader">
            <div class="row">
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
            </div>
        </div>
    </div>
    <!--<< Mouse Cursor Start >>-->
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>
    <!--<< Back-Top Start >>-->
    <button id="back-top" class="back-to-top">
        <i class="fa-regular fa-arrow-up"></i>
    </button>
    <!-- Offcanvas Area Start -->
    <div class="fix-area">
        <div class="offcanvas__info">
            <div class="offcanvas__wrapper">
                <div class="offcanvas__content">
                    <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                        <div class="offcanvas__logo">
                            <a href="<?= BASE_URL ?>/site/Accueil" class="text-decoration-none">
                                <h2 class="fw-bold text-primary mb-0" style="font-family: 'Outfit', sans-serif;">Trans<span class="text-warning">Gest</span></h2>
                            </a>
                        </div>
                        <div class="offcanvas__close">
                            <button>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                 
                    <div class="mobile-menu fix mb-3"></div>
                 
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas__overlay"></div>
    <!-- Search Area Start -->
    <div class="search-wrap">
        <div class="search-inner">
            <i class="fas fa-times search-close" id="search-close"></i>
            <div class="search-cell">
                <form method="get">
                    <div class="search-field-holder">
                        <input type="search" class="main-search-input" placeholder="Search...">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- header-top -->
    <?php $this->view('site/partials/nav') ?>


<section class="hero-premium">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
            <!-- Indicateurs stylés -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">
                <!-- Slide 1 - Voyage -->
                <div class="carousel-item active">
                    <div class="hero-slide">
                        <div class="hero-bg" style="background-image: url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=1600&q=90&auto=format&fit=crop');"></div>
                        <div class="hero-overlay"></div>
                        <div class="hero-pattern"></div>

                        <div class="container h-100">
                            <div class="row h-100 align-items-center">
                                <div class="col-lg-7 text-white" data-aos="fade-right" data-aos-duration="1000">
                                    <div class="hero-badge">
                                        <i class="fas fa-bus me-2"></i> Transport de qualité
                                    </div>
                                    <h1 class="hero-title">
                                        Voyagez avec <span class="text-gradient">Trans<span style="color:#fbbf24;">Gest</span></span>
                                    </h1>
                                    <p class="hero-description">
                                        Réservez vos billets de bus en quelques clics. Des trajets confortables, sécurisés
                                        et abordables à travers toute la région.
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="#recherche" class="btn-hero-primary">
                                            <i class="fas fa-ticket-alt me-2"></i> Réserver maintenant
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                        <a href="#compagnies" class="btn-hero-secondary">
                                            <i class="fas fa-building me-2"></i> Voir les compagnies
                                        </a>
                                    </div>
                                    <div class="hero-stats">
                                        <div class="stat-item">
                                            <div class="stat-number">50+</div>
                                            <div class="stat-label">Destinations</div>
                                        </div>
                                        <div class="stat-divider"></div>
                                        <div class="stat-item">
                                            <div class="stat-number">200+</div>
                                            <div class="stat-label">Trajets/jour</div>
                                        </div>
                                        <div class="stat-divider"></div>
                                        <div class="stat-item">
                                            <div class="stat-number">10K+</div>
                                            <div class="stat-label">Clients</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 text-center d-none d-lg-block" data-aos="fade-left" data-aos-duration="1000">
                                    <div class="hero-image-wrapper">
                                        <div class="hero-image-bg"></div>
                                        <img src="https://cdn-icons-png.flaticon.com/512/3082/3082383.png" alt="Bus"
                                            class="hero-image floating">
                                        <div class="hero-image-shape"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 - Confort -->
                <div class="carousel-item">
                    <div class="hero-slide">
                        <div class="hero-bg" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1600&q=90&auto=format&fit=crop');"></div>
                        <div class="hero-overlay"></div>
                        <div class="hero-pattern"></div>

                        <div class="container h-100">
                            <div class="row h-100 align-items-center">
                                <div class="col-lg-7 text-white" data-aos="fade-right" data-aos-duration="1000">
                                    <div class="hero-badge">
                                        <i class="fas fa-shield-alt me-2"></i> Confort & Sécurité
                                    </div>
                                    <h1 class="hero-title">
                                        Voyagez en toute <span class="text-gradient">sérénité</span>
                                    </h1>
                                    <p class="hero-description">
                                        Des bus modernes climatisés avec WiFi gratuit. Des chauffeurs expérimentés pour
                                        garantir votre sécurité à chaque étape du voyage.
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="#recherche" class="btn-hero-primary">
                                            <i class="fas fa-ticket-alt me-2"></i> Réserver maintenant
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                        <a href="#compagnies" class="btn-hero-secondary">
                                            <i class="fas fa-building me-2"></i> Voir les compagnies
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-5 text-center d-none d-lg-block" data-aos="fade-left" data-aos-duration="1000">
                                    <div class="hero-image-wrapper">
                                        <div class="hero-image-bg"></div>
                                        <img src="https://cdn-icons-png.flaticon.com/512/940/940836.png" alt="Confort"
                                            class="hero-image floating">
                                        <div class="hero-image-shape"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 - Évasion & Découverte -->
                <div class="carousel-item">
                    <div class="hero-slide">
                        <div class="hero-bg" style="background-image: url('https://images.unsplash.com/photo-1499696010180-025ef6e1a8f9?w=1600&q=90&auto=format&fit=crop');"></div>
                        <div class="hero-overlay-light"></div>
                        <div class="hero-pattern"></div>

                        <div class="container h-100">
                            <div class="row h-100 align-items-center">
                                <div class="col-lg-7 text-dark-custom" data-aos="fade-right" data-aos-duration="1000">
                                    <div class="hero-badge badge-light">
                                        <i class="fas fa-sun me-2"></i> Évasion & Découverte
                                    </div>
                                    <h1 class="hero-title">
                                        Vivez des moments <span class="text-gradient">inoubliables</span>
                                    </h1>
                                    <p class="hero-description">
                                        Laissez la routine derrière vous. Partez à la découverte des plus belles régions avec un confort absolu et des paysages à couper le souffle.
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="#recherche" class="btn-hero-primary">
                                            <i class="fas fa-ticket-alt me-2"></i> Réserver maintenant
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                        <a href="#compagnies" class="btn-hero-secondary btn-secondary-light">
                                            <i class="fas fa-compass me-2"></i> Explorer
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-5 text-center d-none d-lg-block" data-aos="fade-left" data-aos-duration="1000">
                                    <div class="hero-image-wrapper">
                                        <div class="hero-image-bg bg-light-variant"></div>
                                        <img src="https://cdn-icons-png.flaticon.com/512/2060/2060284.png" alt="Voyage"
                                            class="hero-image floating">
                                        <div class="hero-image-shape border-light"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contrôles -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>
    <!-- FORMULAIRE DE RECHERCHE FLOTTANT -->
    <section class="container" id="recherche" style="margin-top: -80px;">
        <div class="search-card card shadow-lg border-0 rounded-4" data-aos="fade-up">
            <div class="card-body p-4">
                <h3 class="text-center mb-4 fw-bold">
                    <i class="fas fa-search text-primary me-2"></i> Trouvez votre trajet
                </h3>
                <form action="<?= BASE_URL ?>/site/Recherche" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold"><i class="fas fa-map-marker-alt text-primary me-1"></i>
                            Départ</label>
                        <select name="depart" class="form-select form-select-lg rounded-pill" required>
                            <option value="">📍 Choisissez la ville</option>
                            <option value="bamako">🚌 Bamako</option>
                            <option value="segou">🚌 Ségou</option>
                            <option value="mopti">🚌 Mopti</option>
                            <option value="kayes">🚌 Kayes</option>
                            <option value="sikasso">🚌 Sikasso</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold"><i class="fas fa-flag-checkered text-primary me-1"></i>
                            Destination</label>
                        <select name="destination" class="form-select form-select-lg rounded-pill" required>
                            <option value="">🏁 Choisissez la destination</option>
                            <option value="bamako">🏙️ Bamako</option>
                            <option value="segou">🏞️ Ségou</option>
                            <option value="mopti">⛵ Mopti</option>
                            <option value="kayes">⛰️ Kayes</option>
                            <option value="sikasso">🌴 Sikasso</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold"><i class="fas fa-calendar-alt text-primary me-1"></i>
                            Date</label>
                        <input type="date" name="date" class="form-control form-control-lg rounded-pill"
                            value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary-custom btn-lg w-100 rounded-pill">
                            <i class="fas fa-search me-2"></i> Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- NOS COMPAGNIES -->
    <section class="container py-5" id="compagnies">
        <div class="section-title text-center" data-aos="fade-up">
            <span class="sub-title bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill d-inline-block mb-3">
                <i class="fas fa-handshake me-2"></i> Nos partenaires
            </span>
            <h2 class="fw-bold mb-3">Compagnies de transport</h2>
            <p class="text-muted">Des compagnies fiables pour vos déplacements au Mali</p>
        </div>

        <div class="row g-4">
            <?php $i = 0; if(!empty($listecompagnie)): foreach($listecompagnie as $c): ?>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i + 1) * 100 ?>">
                <div class="premium-company-card h-100 shadow-hover">
                    <!-- Top Gradient Decoration -->
                    <div class="card-gradient-top" style="background: linear-gradient(135deg, #2563eb, #7c3aed);"></div>
                    
                    <div class="card-body p-4 pt-0 text-center">
                        <!-- Logo Wrapper -->
                        <div class="logo-wrapper shadow-lg">
                            <?php if($c->logo): ?>
                                <img src="<?= BASE_URL ?>/images/logos/<?= htmlspecialchars($c->logo) ?>" alt="<?= htmlspecialchars($c->nom_compagnie) ?>" class="company-logo-img">
                            <?php else: ?>
                                <div class="default-logo">
                                    <i class="fas fa-bus text-white fa-2x"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Content Section -->
                        <div class="mt-4 pt-3">
                            <h3 class="company-name h5 fw-bold mb-1"><?= htmlspecialchars($c->nom_compagnie) ?></h3>
                            <p class="company-slogan text-muted small mb-0"><?= htmlspecialchars($c->slogant ?? 'Voyagez en toute sécurité') ?></p>
                            
                            <!-- Badges -->
                            <div class="d-flex justify-content-center gap-2 my-3 flex-wrap">
                                <div class="premium-badge py-1 px-3" style="font-size: 0.7rem;">
                                    <i class="fas fa-route me-1 text-primary"></i>
                                    <span><?= rand(8, 20) ?> Trajets</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a href="<?= BASE_URL ?>/site/Programmer/show/<?= base64_encode($c->id_compagnie) ?>" class="btn btn-premium-action w-100 rounded-pill py-2" style="font-size: 0.85rem;">
                                <span>Voir les trajets</span>
                                <i class="fas fa-chevron-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php $i++; endforeach; else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Aucune compagnie disponible pour le moment.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?= BASE_URL ?>/compagnies" class="btn btn-primary-custom btn-lg px-5 rounded-pill">
                Voir toutes les compagnies <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </section>

    <!-- TOP DESTINATIONS -->
    <section class="py-5 bg-light" id="destinations">
        <div class="container">
            <div class="section-title text-center" data-aos="fade-up">
                <span class="sub-title bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill d-inline-block mb-3">
                    <i class="fas fa-star me-2"></i> Explorez
                </span>
                <h2 class="fw-bold mb-3">Destinations populaires</h2>
                <p class="text-muted">Les trajets les plus réservés par nos clients</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="dest-card position-relative overflow-hidden rounded-4 shadow">
                        <img src="https://images.unsplash.com/photo-1444723121867-7a241cacace9?w=600&q=85&auto=format&fit=crop" alt="Bamako"
                            class="w-100" style="height: 250px; object-fit: cover;">
                        <div class="dest-overlay">
                            <span class="badge bg-warning mb-2">⭐ 4.8</span>
                            <h5 class="text-white fw-bold mb-1">Bamako → Ségou</h5>
                            <p class="text-white-50 small mb-2">3h30 • 3 500 FCFA</p>
                            <a href="#" class="btn btn-light btn-sm rounded-pill">Réserver</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="dest-card position-relative overflow-hidden rounded-4 shadow">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=85&auto=format&fit=crop" alt="Mopti"
                            class="w-100" style="height: 250px; object-fit: cover;">
                        <div class="dest-overlay">
                            <span class="badge bg-warning mb-2">⭐ 4.9</span>
                            <h5 class="text-white fw-bold mb-1">Bamako → Mopti</h5>
                            <p class="text-white-50 small mb-2">7h • 7 500 FCFA</p>
                            <a href="#" class="btn btn-light btn-sm rounded-pill">Réserver</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="dest-card position-relative overflow-hidden rounded-4 shadow">
                        <img src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=600&q=85&auto=format&fit=crop" alt="Kayes" class="w-100"
                            style="height: 250px; object-fit: cover;">
                        <div class="dest-overlay">
                            <span class="badge bg-warning mb-2">⭐ 4.7</span>
                            <h5 class="text-white fw-bold mb-1">Bamako → Kayes</h5>
                            <p class="text-white-50 small mb-2">12h • 12 000 FCFA</p>
                            <a href="#" class="btn btn-light btn-sm rounded-pill">Réserver</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="dest-card position-relative overflow-hidden rounded-4 shadow">
                        <img src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=600&q=85&auto=format&fit=crop" alt="Sikasso"
                            class="w-100" style="height: 250px; object-fit: cover;">
                        <div class="dest-overlay">
                            <span class="badge bg-warning mb-2">⭐ 4.8</span>
                            <h5 class="text-white fw-bold mb-1">Bamako → Sikasso</h5>
                            <p class="text-white-50 small mb-2">5h • 5 500 FCFA</p>
                            <a href="#" class="btn btn-light btn-sm rounded-pill">Réserver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- COMMENT ÇA MARCHE -->
    <section class="container py-5">
        <div class="section-title text-center" data-aos="fade-up">
            <span class="sub-title bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill d-inline-block mb-3">
                <i class="fas fa-question-circle me-2"></i> Guide simple
            </span>
            <h2 class="fw-bold mb-3">Comment ça marche ?</h2>
            <p class="text-muted">Réservez votre billet en 4 étapes simples</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="step-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="step-number mx-auto mb-3">1</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-search fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Recherchez</h5>
                    <p class="text-muted small">Trouvez votre trajet et votre compagnie</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="step-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="step-number mx-auto mb-3">2</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-ticket-alt fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Réservez</h5>
                    <p class="text-muted small">Choisissez vos places et réservez</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="step-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="step-number mx-auto mb-3">3</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-credit-card fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Payez</h5>
                    <p class="text-muted small">Payez en ligne ou en agence</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="step-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="step-number mx-auto mb-3">4</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-bus fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Voyagez</h5>
                    <p class="text-muted small">Présentez votre billet et embarquez</p>
                </div>
            </div>
        </div>
    </section>

    <!-- BANNIÈRE STATISTIQUES -->
    <section class="stats-section py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%); position: relative; overflow: hidden;">
        <!-- Decorative circles -->
        <div style="position:absolute;top:-60px;right:-60px;width:250px;height:250px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>
        <div style="position:absolute;bottom:-80px;left:-40px;width:300px;height:300px;background:rgba(251,191,36,0.05);border-radius:50%;"></div>
        <div class="container" style="position:relative;z-index:1;">
            <div class="text-center mb-5" data-aos="fade-up">
                <span style="background:rgba(251,191,36,0.15);border:1px solid rgba(251,191,36,0.3);color:#fbbf24;padding:6px 20px;border-radius:50px;font-size:0.8rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;">Nos chiffres</span>
                <h2 class="text-white fw-bold mt-3 mb-0">TransGest en quelques chiffres</h2>
            </div>
            <div class="row text-center text-white g-4">
                <div class="col-md-3" data-aos="zoom-in" data-aos-delay="0">
                    <div style="background:rgba(255,255,255,0.06);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:35px 20px;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-8px)';this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.transform='';this.style.background='rgba(255,255,255,0.06)'">
                        <div style="width:64px;height:64px;background:rgba(251,191,36,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                            <i class="fas fa-map-marked-alt fa-2x" style="color:#fbbf24;"></i>
                        </div>
                        <h2 class="fw-bold mb-1 counter" style="font-size:2.5rem;">50</h2>
                        <p class="mb-0" style="opacity:0.8;">Destinations</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-aos-delay="100">
                    <div style="background:rgba(255,255,255,0.06);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:35px 20px;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-8px)';this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.transform='';this.style.background='rgba(255,255,255,0.06)'">
                        <div style="width:64px;height:64px;background:rgba(251,191,36,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                            <i class="fas fa-bus fa-2x" style="color:#fbbf24;"></i>
                        </div>
                        <h2 class="fw-bold mb-1 counter" style="font-size:2.5rem;">200</h2>
                        <p class="mb-0" style="opacity:0.8;">Trajets par jour</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-aos-delay="200">
                    <div style="background:rgba(255,255,255,0.06);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:35px 20px;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-8px)';this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.transform='';this.style.background='rgba(255,255,255,0.06)'">
                        <div style="width:64px;height:64px;background:rgba(251,191,36,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                            <i class="fas fa-users fa-2x" style="color:#fbbf24;"></i>
                        </div>
                        <h2 class="fw-bold mb-1 counter" style="font-size:2.5rem;">10000</h2>
                        <p class="mb-0" style="opacity:0.8;">Clients satisfaits</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-aos-delay="300">
                    <div style="background:rgba(255,255,255,0.06);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:35px 20px;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-8px)';this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.transform='';this.style.background='rgba(255,255,255,0.06)'">
                        <div style="width:64px;height:64px;background:rgba(251,191,36,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                            <i class="fas fa-headset fa-2x" style="color:#fbbf24;"></i>
                        </div>
                        <h2 class="fw-bold mb-0" style="font-size:2.5rem;">24/7</h2>
                        <p class="mb-0" style="opacity:0.8;">Support client</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Hero Slider */
        .hero-premium {
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }

        .hero-slide {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            transition: transform 8s ease;
        }

        .carousel-item.active .hero-bg {
            transform: scale(1.05);
        }

        /* Overlay sombre pour lisibilité sur les photos */
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                105deg,
                rgba(10, 20, 50, 0.92) 0%,
                rgba(10, 20, 50, 0.75) 45%,
                rgba(10, 20, 50, 0.30) 100%
            );
            z-index: 1;
        }

        /* Overlay clair pour le slide 3 */
        .hero-overlay-light {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                105deg,
                rgba(255, 255, 255, 0.92) 0%,
                rgba(255, 255, 255, 0.75) 45%,
                rgba(255, 255, 255, 0.10) 100%
            );
            z-index: 1;
        }

        /* Texte foncé pour l'overlay clair */
        .text-dark-custom .hero-title {
            color: #0f172a;
            text-shadow: none;
        }
        
        .text-dark-custom .hero-description {
            color: #334155;
            text-shadow: none;
            font-weight: 400;
        }

        .badge-light {
            background: rgba(251, 191, 36, 0.15) !important;
            border-color: rgba(251, 191, 36, 0.5) !important;
            color: #b45309 !important;
        }

        .btn-secondary-light {
            background: rgba(15, 23, 42, 0.05);
            border: 1px solid rgba(15, 23, 42, 0.1);
            color: #0f172a;
        }

        .btn-secondary-light:hover {
            background: rgba(15, 23, 42, 0.1);
            color: #0f172a;
        }

        .bg-light-variant {
            background: radial-gradient(circle, rgba(15, 23, 42, 0.08) 0%, rgba(15, 23, 42, 0) 70%);
        }

        .border-light {
            border-color: rgba(15, 23, 42, 0.1) !important;
        }

        .hero-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.08;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(251, 191, 36, 0.15);
            backdrop-filter: blur(12px);
            padding: 10px 24px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 28px;
            border: 1px solid rgba(251, 191, 36, 0.4);
            color: #fbbf24;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            animation: fadeInDown 0.8s cubic-bezier(0.165, 0.84, 0.44, 1) both;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .hero-title {
            font-size: 4.2rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 24px;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
            font-family: 'Outfit', sans-serif;
            letter-spacing: -1px;
        }

        /* Make container & row fill hero height */
        .hero-slide > .container,
        .hero-slide > .container > .row {
            position: relative;
            z-index: 3;
        }

        .text-gradient {
            background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
        }

        .hero-description {
            font-size: 1.25rem;
            font-weight: 300;
            line-height: 1.6;
            letter-spacing: 0.5px;
            color: #f8fafc;
            margin-bottom: 40px;
            max-width: 550px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #0f172a;
            padding: 14px 34px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: inline-flex;
            align-items: center;
            box-shadow: 0 10px 30px rgba(251, 191, 36, 0.35);
        }

        .btn-hero-primary:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px rgba(251, 191, 36, 0.45);
            color: #0f172a;
        }

        /* Generic primary custom button */
        .btn-primary-custom {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border: none;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.4);
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
            color: white;
        }

        .hero-stats {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fbbf24;
        }

        .stat-label {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
        }

        .hero-image-wrapper {
            position: relative;
            display: inline-block;
        }

        .hero-image {
            position: relative;
            z-index: 2;
            max-height: 350px;
            filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.3));
        }

        .hero-image-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            z-index: 1;
        }

        .hero-image-shape {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: morph 8s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes morph {
            0% {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            }

            50% {
                border-radius: 70% 30% 30% 70% / 60% 40% 60% 40%;
            }

            100% {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            }
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .floating {
            animation: float 4s ease-in-out infinite;
        }

        /* Carousel Controls */
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: all 0.3s;
        }

        .hero-premium:hover .carousel-control-prev,
        .hero-premium:hover .carousel-control-next {
            opacity: 1;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .carousel-indicators {
            bottom: 30px;
        }

        .carousel-indicators button {
            width: 40px;
            height: 4px;
            border-radius: 4px;
            margin: 0 5px;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s;
        }

        .carousel-indicators button.active {
            background: #fbbf24;
            width: 60px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-image {
                max-height: 250px;
                margin-top: 50px;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-stats {
                gap: 20px;
            }

            .stat-number {
                font-size: 1.2rem;
            }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* Search Card */
        .search-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Premium Card Design */
        .premium-company-card {
            background: #ffffff;
            border-radius: 30px;
            overflow: hidden;
            position: relative;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        .card-gradient-top {
            height: 80px;
            width: 100%;
            margin-bottom: -40px;
        }

        .logo-wrapper {
            width: 90px;
            height: 90px;
            background: #ffffff;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            padding: 12px;
            position: relative;
            z-index: 2;
            transition: all 0.5s ease;
        }

        .company-logo-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .default-logo {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .company-name {
            color: #0f172a;
            font-weight: 700;
            letter-spacing: 0;
            font-size: 1.25rem;
        }

        .premium-badge {
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #475569;
            display: flex;
            align-items: center;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .btn-premium-action {
            background: #0f172a;
            color: #ffffff;
            padding: 10px 15px;
            border: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s ease;
        }

        /* Hover Effects */
        .premium-company-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #2563eb;
        }

        .premium-company-card:hover .logo-wrapper {
            transform: scale(1.1) rotate(5deg);
        }

        .btn-premium-action:hover {
            background: #2563eb;
            color: #ffffff;
        }

        /* Destination Cards */
        .dest-card {
            cursor: pointer;
        }

        .dest-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.5) 60%, transparent 100%);
            padding: 25px 20px;
            color: white;
            transform: translateY(15px);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .dest-overlay h5 {
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.6);
            color: #ffffff;
        }

        .dest-overlay p {
            font-weight: 300;
            font-size: 0.95rem;
            opacity: 0.9;
            margin-bottom: 0;
            color: #e2e8f0;
        }

        .dest-card:hover .dest-overlay {
            transform: translateY(0);
        }

        .dest-card img {
            transition: transform 0.5s;
        }

        .dest-card:hover img {
            transform: scale(1.1);
        }

        /* Step Cards */
        .step-card {
            transition: all 0.3s;
        }

        .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .step-icon {
            color: #2563eb;
        }

        /* Carousel Controls */
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: all 0.3s;
        }

        .hero-slider:hover .carousel-control-prev,
        .hero-slider:hover .carousel-control-next {
            opacity: 1;
        }

        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 5px;
        }

        .carousel-indicators button.active {
            background-color: #2563eb;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content {
                text-align: center;
                padding: 100px 0;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-stats {
                justify-content: center;
            }

            .search-card {
                margin-top: -40px;
            }
        }
    </style>

    <script>
        // Animation des compteurs
        document.addEventListener('DOMContentLoaded', function () {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.innerText);
                let current = 0;
                const increment = target / 50;
                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.innerText = Math.ceil(current);
                        setTimeout(updateCounter, 30);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCounter();
            });
        });
    </script>

    <?php $this->view('site/partials/footer') ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });
    </script>
    <?php $this->view('site/partials/foot') ?>
