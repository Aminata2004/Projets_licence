<!--<< All JS Plugins >>-->
<?php $this->view('site/partials/header') ?>
<style>
    .object-fit-cover {
        object-fit: cover;
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
    <!-- Breadcrumb-Section Start -->
    <section class="breadcrumb-wrapper fix bg-cover"
        style="background-image: url(<?= BASE_URL ?>/assets_site/img/breadcrumb/breadcrumb.jpg);">
        <div class="container">
            <div class="row">
                <div class="page-heading">
                    <h2>Espace de reservation des billiets </h2>
                    <ul class="breadcrumb-list">
                        <li>
                            <a href="index.html">Accueil</a>
                        </li>
                        <li><i class="fa-solid fa-chevrons-right"></i></li>
                        <li class="active">reserver</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- Tour-Details Section Start -->
    <?php $t = $trajet ?? []; ?>
    <section class="tour-section section-padding fix bg-light">
        <div class="container">
            <div class="row align-items-center shadow rounded-4 overflow-hidden bg-white">
                <!-- Image à gauche -->
                <div class="col-md-6 p-0">
                    <img src="<?= BASE_URL ?>/assets_site/formulaire.png" alt="Voyageur" class="img-fluid w-100 h-100 object-fit-cover" style="min-height: 100%;">
                </div>
                <!-- Formulaire à droite -->
                <div class="col-md-6 p-5 ">
                    <h3 class="text-primary fw-bold mb-4"> Réservez votre voyage</h3>

                    <form method="post" action="">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Départ</label>
                                <input type="text" name="depart" value="<?= htmlspecialchars($t['depart'] ?? '') ?>" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Destination</label>
                                <input type="text" name="destination" value="<?= htmlspecialchars($t['destination'] ?? '') ?>" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Jour du voyage</label>
                                <input type="date" name="date_voyage" class="form-control">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Heure de départ</label>
                                <input type="text" name="heure_depart" value="<?= htmlspecialchars($t['heure_depart'] ?? '') ?>" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Nom & Prénom</label>
                                <input type="text" name="nom_prenom" class="form-control" placeholder="Ex: Jean Dupont">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Nombre de passagers</label>
                                <input type="number" name="nb_passagers" class="form-control" min="1" placeholder="1">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="telephone" class="form-control" placeholder="+221 77 000 00 00">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="exemple@mail.com">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Prix total</label>
                                <input type="text" name="prix" value="<?= htmlspecialchars($t['prix'] ?? '') ?>" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Code marchand</label>
                                <input type="text" name="code_marchand" class="form-control" placeholder="Ex: #123456">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 mt-4">
                            Réserver maintenant
                            <img src="<?= BASE_URL ?>/assets_site/img/icon/white-arrow.svg" alt="→" class="ms-2">
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <!-- Footer-Section Start -->

    <?php $this->view('site/partials/footer') ?>
    <!--<< All JS Plugins >>-->
    <?php $this->view('site/partials/foot') ?>