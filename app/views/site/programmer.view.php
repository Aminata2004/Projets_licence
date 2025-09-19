<!--<< All JS Plugins >>-->
<?php $this->view('site/partials/header') ?>

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
                                <img src="<?= BASE_URL ?>/<?= BASE_URL ?>/assets_site_site/img/logo/black-logo.svg" alt="logo-img">
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
                    <h2>Programme du voyage</h2>
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
    <!-- Destination Section 2 Start -->
    <section class="destination-section2 section-padding fix">
        <div class="container">
            <div class="row g-4">
                <?php if (!empty($programmes)) : ?>
                    <?php
                    // Regrouper les programmes par ville de départ
                    $groupedByDepart = [];
                    foreach ($programmes as $programme) {
                        $depart = htmlspecialchars($programme->idDepart);
                        $groupedByDepart[$depart][] = $programme;
                    }
                    ?>

                    <?php foreach ($groupedByDepart as $depart => $programmesDepart) : ?>
                        <div class="col-12">
                            <div class="mb-4">
                                <div class="progress shadow-sm" style="height: 24px;">
                                    <div class="progress-bar bg-primary fw-bold" role="progressbar"
                                        style="width: 100%; font-size: 16px;">
                                        Départ : <?= $depart ?>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <!-- Tableau des programmes -->
                                <table class="table table-hover table-bordered shadow-sm rounded text-center align-middle" style="cursor: pointer;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Destination</th>
                                            <th>Convocation</th>
                                            <th>Horaire</th>
                                            <th>Tarifs</th>
                                            <th>Escale</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($programmesDepart as $programme) : ?>
                                            <tr class="clickable-row"
                                                data-id="<?= $programme->idProgrammer ?>"
                                                data-depart="<?= htmlspecialchars($programme->depart ?? '') ?>"
                                                data-destination="<?= htmlspecialchars($programme->idDestination) ?>"
                                                data-horaire="<?= htmlspecialchars($programme->heureDepart) ?>"
                                                data-prix="<?= htmlspecialchars($programme->prix) ?>"
                                                data-compagnie="<?= htmlspecialchars($programme->id_compagnie) ?>"
                                                data-escales="<?= htmlspecialchars($programme->escales_avec_frais) ?>">

                                                <td><?= htmlspecialchars($programme->idDestination) ?></td>
                                                <td><?= htmlspecialchars($programme->rdv) ?></td>
                                                <td><?= htmlspecialchars($programme->heureDepart) ?></td>
                                                <td><span class="badge bg-primary"><?= htmlspecialchars($programme->prix) ?> FCFA</span></td>
                                                <td>
                                                    <?php
                                                    if (!empty($programme->escales_avec_frais)) {
                                                        $escales = explode(' - ', $programme->escales_avec_frais);
                                                        foreach ($escales as $escale) {
                                                            echo '<span class="badge bg-primary text-white mb-1 d-block">' . htmlspecialchars($escale) . '</span>';
                                                        }
                                                    } else {
                                                        echo '<span class="text-muted">Aucune escale</span>';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>


                            </div>
                            <br>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="text-center py-5">
                        <h5 class="text-muted">Aucun programme disponible pour le moment.</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>


    <!-- Cta-bg-Section Start -->

    <?php $this->view('site/partials/footer') ?>
    <!--<< All JS Plugins >>-->
    <?php $this->view('site/partials/foot') ?>
    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let rows = document.querySelectorAll(".clickable-row");

            rows.forEach(row => {
                row.addEventListener("click", function() {
                    const id = this.getAttribute("data-id");

                    // Redirection vers la page du formulaire
                    window.location.href = "<?= BASE_URL ?>/site/Reservation_formulaire?id=" + id;
                });
            });
        });
    </script>

    <!-- Un peu de style -->
    <style>
        .clickable-row:hover {
            background-color: #f9f9f9;
            cursor: pointer;
        }
    </style>

 
