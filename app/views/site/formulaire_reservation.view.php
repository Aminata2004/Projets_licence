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
    <?php
    $t = $trajet ?? [];
    ?>
    <?php $this->view("admin/helpers") ?>
    <section class="tour-section section-padding fix bg-light">
        <div class="container">
            <div class="row align-items-center shadow rounded-4 overflow-hidden bg-white">
                <!-- Image à gauche -->
                <div class="col-md-6 p-0">
                    <div class="ratio ratio-1x1 ratio-md-16x9">
                        <img src="<?= BASE_URL ?>/assets_site/formulaire.png"
                            alt="Voyageur"
                            class="img-fluid w-100 h-100"
                            style="object-fit: cover;">
                    </div>
                </div>
                <!-- Formulaire de réservation -->
                <div class="col-md-6 p-5">
                    <div class="card border-0 shadow" style="box-shadow: 0 4px 20px rgba(0, 123, 255, 0.2);">
                        <div class="card-body">
                            <h3 class="text-primary fw-bold mb-4">Planifiez votre prochaine aventure</h3>
                            <form method="post" action="">
                                <div class="row g-3">
                                    <!-- Départ -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Départ</label>
                                        <input type="text" name="departId" value="<?= htmlspecialchars($t['depart'] ?? '') ?>" class="form-control rounded-pill" readonly>
                                    </div>

                                    <!-- Destination & Escales -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Destination & Escales</label>
                                        <input type="text" name="destinationId" value="<?= htmlspecialchars($t['destination'] ?? '') ?>" class="form-control mb-2 rounded-pill" readonly>
                                        <input type="hidden" name="id_compagnie" value="<?= htmlspecialchars($t['id_compagnie'] ?? '') ?>">
                                        <p class="text-muted mb-2" style="font-size: 0.85rem;">Sélectionnez une escale optionnelle :</p>
                                        <?php
                                        if (!empty($t['escales_avec_frais'])) {
                                            $escales = explode(' - ', $t['escales_avec_frais']);
                                            foreach ($escales as $index => $escale) {
                                                if (preg_match('/^(.*?)\s*\((\d+)\s*FCFA\)/', $escale, $matches)) {
                                                    $ville = trim($matches[1]);
                                                    $prix = intval($matches[2]);
                                                } else {
                                                }
                                        ?>
                                                <div class="form-check mb-1">
                                                    <input class="form-check-input escale-radio" type="radio" name="escale_finale" id="escale_<?= $index ?>" value="<?= htmlspecialchars($ville) ?>" data-prix="<?= $prix ?>">
                                                    <label class="form-check-label" for="escale_<?= $index ?>"><?= htmlspecialchars($ville) ?> (<?= number_format($prix) ?> FCFA)</label>
                                                </div>
                                        <?php
                                            }
                                        } else {
                                            echo '<p class="text-muted">Pas d\'escale disponible</p>';
                                        }
                                        ?>

                                    </div>

                                    <!-- Jour et heure -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jour du voyage</label>
                                        <input type="date" name="jourVoyage" class="form-control rounded-pill">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Heure de départ</label>
                                        <input type="text" name="Heur_departs" value="<?= htmlspecialchars($t['heure_depart'] ?? '') ?>" class="form-control rounded-pill" readonly>
                                    </div>

                                    <!-- Infos client -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nom & Prénom</label>
                                        <input type="text" name="Client" class="form-control rounded-pill" placeholder="Ex: Jean Dupont">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nombre de passagers</label>
                                        <input type="number" name="nombrePassages" id="nombrePassages"
                                            class="form-control rounded-pill" min="1" value="1" placeholder="1">
                                    </div>

                                    <div class="col-12 col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Téléphone</label>
                                        <input type="text" id="numeroClient" name="numeroClient"
                                            class="form-control rounded-pill border-primary"
                                            placeholder="77 78 88 88" required>
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" name="emailClient" class="form-control rounded-pill" placeholder="exemple@mail.com">
                                    </div>

                                    <!-- Paiement -->




                                    <div class="col-12 col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Numéro de paiement</label>
                                        <input type="text" id="numeroPaiement" name="numeroPaiement"
                                            class="form-control rounded-pill border-primary"
                                            placeholder="66 77 88 99" required>
                                    </div>




                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Code marchand</label>
                                        <input type="text" name="code_marchand" class="form-control rounded-pill" placeholder="#123456">
                                    </div>

                                    <!-- Infos supplémentaires -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Prix total</label>
                                        <input type="text" name="montant_payer" id="montant_payer"
                                            value="<?= htmlspecialchars($t['prix'] ?? '') ?>"
                                            class="form-control rounded-pill" readonly>
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Numero de billets</label>
                                        <input type="text" name="numeroBillets" class="form-control rounded-pill" value="<?= genererNumeroBillet() ?>" placeholder="#123456" readonly>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 mt-4 rounded-pill" name="reserver">
                                    Réserver maintenant
                                    <img src="<?= BASE_URL ?>/assets_site/img/icon/white-arrow.svg" alt="→" class="ms-2">
                                </button>
                            </form>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section>

    <!-- Footer-Section Start -->
    <script>
        const radios = document.querySelectorAll('.escale-radio');
        const prixInput = document.getElementById('montant_payer');
        const prixDeBase = <?= intval($t['prix'] ?? 0) ?>;

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                const prixEscale = parseInt(this.dataset.prix);
                if (!isNaN(prixEscale)) {
                    prixInput.value = prixEscale; // met à jour le prix selon l’escale
                } else {
                    prixInput.value = prixDeBase;
                }
            });
        });
    </script>




    <!-- SweetAlert2 -->
    <?php if (isset($_SESSION['toast'])): ?>
        <script>
            Swal.fire({
                position: "top-end",
                icon: "info",
                title: "<?= addslashes($_SESSION['toast']['title']); ?>",
                text: "<?= addslashes($_SESSION['toast']['text']); ?>",
                showConfirmButton: true,
                confirmButtonText: "OK",
                background: "<?= $_SESSION['toast']['bg'] ?: '#fff'; ?>",
                color: "#000"
            }).then(() => {
                <?php if (!empty($_SESSION['toast']['url'])): ?>
                    window.location.href = "<?= $_SESSION['toast']['url']; ?>";
                <?php endif; ?>
            });
        </script>
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>




    <script>
        function formatMaliPhone(inputId) {
            document.getElementById(inputId).addEventListener('input', function() {
                let val = this.value.replace(/\D/g, ''); // Supprime tout sauf les chiffres

                // Limiter à 8 chiffres (format standard au Mali)
                val = val.slice(0, 8);

                // Formatage : XX XX XX XX
                let formatted = val;
                if (val.length > 2) formatted = val.slice(0, 2) + ' ' + val.slice(2);
                if (val.length > 4) formatted = formatted.slice(0, 5) + ' ' + formatted.slice(5);
                if (val.length > 6) formatted = formatted.slice(0, 8) + ' ' + formatted.slice(8);

                this.value = formatted;
            });
        }

        // Appliquer sur les deux champs
        formatMaliPhone('numeroClient');
        formatMaliPhone('numeroPaiement');


        document.addEventListener('DOMContentLoaded', function() {
            // Récupère le prix de base (prix unitaire) depuis le champ "montant_payer"
            const prixUnitaire = parseFloat(document.getElementById('montant_payer').value) || 0;
            const nombrePassagesInput = document.getElementById('nombrePassages');
            const montantPayerInput = document.getElementById('montant_payer');

            // Ajoute un écouteur d'événement sur le champ nombre de passagers
            nombrePassagesInput.addEventListener('input', function() {
                let nombre = parseInt(this.value) || 0;
                let total = prixUnitaire * nombre;
                montantPayerInput.value = total.toLocaleString('fr-FR'); // Format : 12 000
            });
        });
    </script>



    <?php $this->view('site/partials/footer') ?>
    <!--<< All JS Plugins >>-->
    <?php $this->view('site/partials/foot') ?>