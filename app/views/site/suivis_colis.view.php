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
    <section class="breadcrumb-wrapper fix bg-cover"
        style="background-image: url('<?= BASE_URL ?>/assets_site/img/Suividecolis.png');
          background-size: cover;
           background-position: center;
           width: 100%;
           height: 700px; 
          display: flex;
         align-items: center; 
         ">

        <div class="container">
            <div class="row">
                <div class="page-heading">
                    <h2>Suivis Colis</h2>
                    <ul class="breadcrumb-list">
                        <li><a href="index.html">Accueil</a></li>
                        <li><i class="fa-solid fa-chevrons-right"></i></li>
                        <li class="active">reserver</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>



    <!-- Destination Section 2 Start -->
    <section class="section-padding bg-light">
        <div class="container">
            <!-- Titre principal -->
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary" style="font-size: 2.5rem;">📦 Suivi de colis</h2>
                <p class="text-muted" style="font-size: 1.1rem;">
                    Suivez en temps réel l’état de votre envoi en entrant le code de suivi.
                </p>
            </div>
            <!-- Formulaire de recherche -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="p-4 bg-white shadow rounded-4">
                        <form action="<?= BASE_URL ?>/site/Suivis_colis" method="GET">
                            <!-- Sélection de la compagnie -->
                            <div class="mb-3">
                                <label for="compagnie" class="form-label text-muted fw-semibold">Choisissez la compagnie</label>
                                <select id="compagnie" name="id_compagnie">
                                    <option value="" disabled>-- Choisissez une compagnie --</option>
                                    <?php foreach ($compagnies as $compagnie): ?>
                                        <option value="<?= $compagnie->id_compagnie ?>">
                                            <?= $compagnie->nom_compagnie ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="code" class="form-label text-muted fw-semibold">Code de suivi</label>
                                <input type="text" name="code_colis" id="code" class="form-control form-control-lg rounded-pill"
                                    placeholder="Ex : COLIS123456" required
                                    value="<?= isset($_GET['code_colis']) ? htmlspecialchars($_GET['code_colis']) : '' ?>"
                                    disabled>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill" disabled id="submitBtn">
                                    🔍 Rechercher
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Message d'alerte en haut -->
            <?php if (isset($colis) && !empty($colis) && !empty($colis->code_colis)): ?>
                <div class="alert alert-success text-center mt-4">
                    ✅ Colis trouvé avec succès
                </div>
            <?php elseif (!empty($erreur)): ?>
                <div class="alert alert-danger text-center mt-4">
                    ⚠️ <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <!-- Affichage des détails du colis -->
            <?php if (isset($colis) && !empty($colis) && !empty($colis->code_colis)): ?>
                <div class="card shadow-sm mt-3 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 text-white">📦 Détails du colis</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>🔖 Code :</strong> <?= htmlspecialchars($colis->code_colis) ?></p>

                        <p><strong>👤 Expéditeur :</strong> <?= htmlspecialchars($colis->expediteur) ?>
                            (📞 <?= htmlspecialchars($colis->numero_exp) ?>)</p>

                        <p><strong>🎯 Destinataire :</strong> <?= htmlspecialchars($colis->destinataire) ?>
                            (📞 <?= htmlspecialchars($colis->numero_dest) ?>)</p>

                        <p><strong>🏁 Provenance :</strong> <?= htmlspecialchars($colis->provient_de) ?></p>
                        <p><strong>🚩 Destination :</strong> <?= htmlspecialchars($colis->localite) ?></p>
                        <p><strong>📌 Statut :</strong>
                            <?php
                            $status = $colis->status;
                            switch ($status) {
                                case 'enregistre':
                                    echo '<span class="badge bg-secondary">📦 Colis prêt à être expédié</span>';
                                    break;
                                case 'en cours':
                                    echo '<span class="badge bg-warning text-dark">🚚 En cours de livraison</span>';
                                    break;
                                case 'livré':
                                    echo '<span class="badge bg-success">✅ Colis livré</span>';
                                    break;
                                default:
                                    echo '<span class="badge bg-info">' . htmlspecialchars($status) . '</span>';
                            }
                            ?>
                        </p>

                    </div>
                </div>
            <?php endif; ?>



        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const compagnieSelect = document.getElementById('compagnie');
            const codeInput = document.getElementById('code');
            const submitBtn = document.getElementById('submitBtn');

            if (!compagnieSelect || !codeInput || !submitBtn) {
                console.error('Un ou plusieurs éléments introuvables dans le DOM.');
                return;
            }

            function toggleCodeInput() {
                console.log("Valeur compagnieSelect:", compagnieSelect.value.trim());

                if (compagnieSelect.value.trim() !== "") {
                    codeInput.removeAttribute("disabled");
                    submitBtn.removeAttribute("disabled");
                    console.log("✅ Champs activés");
                } else {
                    codeInput.setAttribute("disabled", true);
                    submitBtn.setAttribute("disabled", true);
                    codeInput.value = '';
                    console.log("🚫 Champs désactivés");
                }
            }

            compagnieSelect.addEventListener('change', toggleCodeInput);

            // Appel initial au chargement
            toggleCodeInput();
        });
    </script>
    <!-- Footer-Section Start -->
    <?php $this->view('site/partials/footer') ?>
    <!--<< All JS Plugins >>-->
    <?php $this->view('site/partials/foot') ?>