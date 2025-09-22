<!--<< All JS Plugins >>-->
<?php $this->view('site/partials/header') ?>

<style>
    /* Animation hover card */
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
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
        height: 300px;
    }

    .destination-card img {
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .destination-card:hover img {
        transform: scale(1.05);
    }

    .destination-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.85), transparent);
        color: white;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: end;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .destination-card:hover .destination-overlay {
        opacity: 1;
    }
</style>

<body>
    <!-- Preloader Start -->
    <div id="preloader" class="preloader">
        <div class="animation-preloader">
            <div class="spinner">
            </div>
            <div class="txt-loading">
                <span data-text-preloader="T" class="letters-loading">
                    T
                </span>
                <span data-text-preloader="R" class="letters-loading">
                    R
                </span>
                <span data-text-preloader="A" class="letters-loading">
                    A
                </span>
                <span data-text-preloader="V" class="letters-loading">
                    V
                </span>
                <span data-text-preloader="O" class="letters-loading">
                    O
                </span>
            </div>
            <p class="text-center">Loading</p>
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
                            <a href="index.html">
                                <img src="<?= BASE_URL ?>/assets_site/img/logo/black-logo.svg" alt="logo-img">
                            </a>
                        </div>
                        <div class="offcanvas__close">
                            <button>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text d-none d-xl-block">
                        Nullam dignissim, ante scelerisque the is euismod fermentum odio sem semper the is erat, a
                        feugiat leo urna eget eros. Duis Aenean a imperdiet risus.
                    </p>
                    <div class="mobile-menu fix mb-3"></div>
                    <div class="offcanvas__contact">
                        <h4>Contact Info</h4>
                        <ul>
                            <li class="d-flex align-items-center">
                                <div class="offcanvas__contact-icon">
                                    <i class="fal fa-map-marker-alt"></i>
                                </div>
                                <div class="offcanvas__contact-text">
                                    <a target="_blank" href="#">Main Street, Melbourne, Australia</a>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="offcanvas__contact-icon mr-15">
                                    <i class="fal fa-envelope"></i>
                                </div>
                                <div class="offcanvas__contact-text">
                                    <a href="mailto:info@example.com"><span
                                            class="mailto:info@example.com">info@example.com</span></a>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="offcanvas__contact-icon mr-15">
                                    <i class="fal fa-clock"></i>
                                </div>
                                <div class="offcanvas__contact-text">
                                    <a target="_blank" href="#">Mod-friday, 09am -05pm</a>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="offcanvas__contact-icon mr-15">
                                    <i class="far fa-phone"></i>
                                </div>
                                <div class="offcanvas__contact-text">
                                    <a href="tel:+11002345909">+11002345909</a>
                                </div>
                            </li>
                        </ul>
                        <div class="header-button mt-4">
                            <a href="contact.html" class="theme-btn"> Request A Quote <i
                                    class="fa-sharp fa-regular fa-arrow-right"></i></a>
                        </div>
                        <div class="social-icon d-flex align-items-center">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
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

    <!-- Hero-Section Start -->
<section class="hero-section position-relative d-flex align-items-center justify-content-center text-center text-dark" 
         style="min-height: 100vh; 
                background-image: url('https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1600&q=80');
                background-position: center center;
                background-size: cover;
                background-repeat: no-repeat;">

    <!-- Overlay léger -->
    <div style="position:absolute; top:0; left:0; width:100%; height:100%; background-color: rgba(255,255,255,0.15);"></div>

    <!-- Contenu central -->
    <div class="container position-relative" style="font-family: 'Poppins', sans-serif;">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <span class="sub-title text-warning fw-semibold mb-3 d-block" style="font-size: 1.5rem; letter-spacing: 1px;">
                    ✈ Plateforme de Réservation & Suivi
                </span>
                <h1 class="fw-bold mb-4 display-4" style="line-height: 1.2;">
                    Réservez vos <span class="text-warning">billets</span> facilement<br>
                    et suivez vos <span class="text-primary">colis</span> en un clic
                </h1>
                <p class="lead mb-5" style="max-width: 700px; margin:auto; font-size:1.2rem;">
                    Une solution simple, rapide et sécurisée pour gérer vos réservations et vos envois.
                </p>

                <a href="#reservation" class="btn btn-warning btn-lg px-5 me-3 shadow rounded-pill">
                    Réserver maintenant
                </a>
                <a href="#colis" class="btn btn-outline-dark btn-lg px-5 rounded-pill">
                    Suivre un colis
                </a>
            </div>
        </div>
    </div>
</section>






    <!-- Destination Section Start -->
    <section class="destination-section section-padding fix bg-white" id="compagnies">
        <div class="container">
            <div class="section-title text-center mb-5">
                <span class="sub-title text-primary fw-semibold" style="letter-spacing: 2px;">Réserve une place</span>
                <h2 class="mt-2">Nos Compagnies partenaires</h2>
            </div>

            <div class="swiper destination-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($listecompagnie as $compagnie): ?>
                        <div class="swiper-slide">
                            <div class="card border rounded-4 shadow-sm h-100 overflow-hidden"
                                style="transition: transform 0.3s ease, box-shadow 0.3s ease;">

                                <!-- Header image propre et arrondie -->
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light rounded-top-4"
                                    style="height: 200px; padding: 10px;">
                                    <img src="<?= BASE_URL ?>/images/logos/<?= htmlspecialchars($compagnie->logo) ?>"
                                        alt="<?= htmlspecialchars($compagnie->nom_compagnie) ?>"
                                        style="max-height: 90%; max-width: 90%; object-fit: contain;">
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($compagnie->nom_compagnie) ?></h5>
                                    <p class="card-text text-muted mb-3">
                                        <i class="fa-solid fa-comment-dots me-2"></i>
                                        <?= htmlspecialchars($compagnie->slogant ?? '') ?>
                                    </p>
                                    <a href="<?= BASE_URL ?>/site/Programmer/show/<?= base64_encode($compagnie->id_compagnie) ?>"
                                        class="btn btn-outline-primary mt-auto">
                                        Réserver <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


                <!-- Swiper nav -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>

            <div class="row mt-5">
                <div class="col text-center">
                    <a href="<?= BASE_URL ?>/compagnies" class="btn btn-primary btn-lg rounded-pill shadow">
                        Voir toutes les compagnies
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Team-Section Start -->
    <section class="top-destination-section py-5 bg-light">
        <div class="container">
            <!-- Section Title -->
            <div class="text-center mb-5">
                <span class="text-primary fw-semibold text-uppercase">Top Destination</span>
                <h2 class="fw-bold display-6">Explorez les destinations populaires</h2>
                <p class="text-muted">Des lieux incontournables à visiter avec nos compagnies partenaires</p>
            </div>

            <!-- Grid of Destinations -->
            <div class="row g-4">
                <!-- Card 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="destination-card position-relative overflow-hidden rounded-4 shadow">
                        <img src="<?= BASE_URL ?>/assets_site/img/image12.jpeg" class="w-100 object-fit-cover" style="height: 300px;" alt="Somatra voyage">
                        <div class="destination-overlay p-3">
                            <h6 class="text-white mb-1">Somatra Voyage</h6>
                            <h5 class="fw-bold"><a href="reservation.php" class="text-white text-decoration-none">Une aventure en vacances</a></h5>
                            <p class="text-white-50 small">Vos meilleures vacances de tous les temps</p>
                            <a href="reservation.php" class="btn btn-light btn-sm">Réserver</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="destination-card position-relative overflow-hidden rounded-4 shadow">
                        <img src="<?= BASE_URL ?>/assets_site/img/image13.jpeg" class="w-100 object-fit-cover" style="height: 300px;" alt="Voyage de rêve">
                        <div class="destination-overlay p-3">
                            <h6 class="text-white mb-1">Somatra Voyage</h6>
                            <h5 class="fw-bold"><a href="reservation.php" class="text-white text-decoration-none">Le voyage de vos rêves</a></h5>
                            <p class="text-white-50 small">Vue imprenable, confort et sécurité</p>
                            <a href="reservation.php" class="btn btn-light btn-sm">Réserver</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="destination-card position-relative overflow-hidden rounded-4 shadow">
                        <img src="<?= BASE_URL ?>/assets_site/img/image14.jpeg" class="w-100 object-fit-cover" style="height: 300px;" alt="Bamako">
                        <div class="destination-overlay p-3">
                            <h6 class="text-white mb-1">Somatra Voyage</h6>
                            <h5 class="fw-bold"><a href="reservation.php" class="text-white text-decoration-none">Bamako, la capitale</a></h5>
                            <p class="text-white-50 small">Votre aventure vers Bamako commence ici</p>
                            <a href="reservation.php" class="btn btn-light btn-sm">Réserver</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="destination-card position-relative overflow-hidden rounded-4 shadow">
                        <img src="<?= BASE_URL ?>/assets_site/img/image15.jpeg" class="w-100 object-fit-cover" style="height: 300px;" alt="Ségou">
                        <div class="destination-overlay p-3">
                            <h6 class="text-white mb-1">Somatra Voyage</h6>
                            <h5 class="fw-bold"><a href="reservation.php" class="text-white text-decoration-none">Ségou, terre d'artisanat</a></h5>
                            <p class="text-white-50 small">Histoire, culture et hospitalité</p>
                            <a href="reservation.php" class="btn btn-light btn-sm">Réserver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service-Section Start -->
    <!-- Destination Section Start -->
    <section class="destination-section section-padding fix">
        <div class="container">
            <div class="section-title text-center">
                <span class="sub-title wow fadeInUp text-primary" style="visibility: visible; animation-name: fadeInUp;">
                    Comment ça marche
                </span>
            </div>

            <div class="row justify-content-center mt-4">
                <!-- Étape 1 : Réservation -->
                <div class="col-lg-3 col-md-6 text-center mb-4 wow fadeInUp" data-wow-delay=".1s">
                    <div class="card h-100 border-0 shadow rounded p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-ticket fa-3x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Réservez vos billets</h5>
                        <p class="text-muted">Choisissez votre trajet et réservez votre billet en quelques clics.</p>
                    </div>
                </div>

                <!-- Étape 2 : Suivi colis -->
                <div class="col-lg-3 col-md-6 text-center mb-4 wow fadeInUp" data-wow-delay=".2s">
                    <div class="card h-100 border-0 shadow rounded p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-box-open fa-3x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Suivez vos colis</h5>
                        <p class="text-muted">Envoyez et suivez vos colis en toute sécurité et transparence.</p>
                    </div>
                </div>

                <!-- Étape 3 : Paiement -->
                <div class="col-lg-3 col-md-6 text-center mb-4 wow fadeInUp" data-wow-delay=".3s">
                    <div class="card h-100 border-0 shadow rounded p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-credit-card fa-3x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Payez facilement</h5>
                        <p class="text-muted">Paiement sécurisé via mobile money ou carte bancaire.</p>
                    </div>
                </div>

                <!-- Étape 4 : Voyager -->
                <div class="col-lg-3 col-md-6 text-center mb-4 wow fadeInUp" data-wow-delay=".4s">
                    <div class="card h-100 border-0 shadow rounded p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-bus fa-3x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Voyagez tranquille</h5>
                        <p class="text-muted">Présentez votre billet et embarquez dans le bus de votre choix.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team-Section Start -->
    <!-- Footer-Section Start -->
    <?php $this->view('site/partials/footer') ?>
    <!--<< All JS Plugins >>-->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.destination-slider', {
            slidesPerView: 4,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                576: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 25
                },
                992: {
                    slidesPerView: 4,
                    spaceBetween: 30
                },
            },
        });
    </script>

    <?php $this->view('site/partials/foot') ?>