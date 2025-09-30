<!-- header-main -->
<div id="header-sticky" class="header-1">
    <div class="container">
        <div class="mega-menu-wrapper">
            <div class="header-main">
                <div class="logo">
                    <a href="index.html" class="header-logo">
                        <img src="<?= BASE_URL ?>/assets_site/img/logo/black-logo.svg" alt="logo-img">
                    </a>
                </div>
                <div class="header-right d-flex justify-content-end align-items-center">
                    <div class="mean__menu-wrapper">
                        <div class="main-menu">
                            <nav id="mobile-menu">
                                <ul>
                                    <li >
                                        <a href="<?= BASE_URL ?>/site/Accueil">
                                            Accueil
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" onclick="alerteReservation(event)">Reservation</a>
                                    </li>


                                    <li>
                                        <a href="<?= BASE_URL ?>/site/Suivis_colis">
                                            Suivis de colis
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= BASE_URL ?>/site/Reservation_formulaire">Contact nous</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <a href="#0" class="search-trigger search-icon"><i
                            class="fa-regular fa-magnifying-glass"></i></a>
                    <div class="header__hamburger my-auto">
                        <div class="sidebar__toggle">
                            <img src="<?= BASE_URL ?>/assets_site/img/icon/bars.svg" alt="img">
                        </div>
                    </div>
                    <a href="#" class="theme-btn bg-primary"> Request A Quote
                        <img src="<?= BASE_URL ?>/assets_site/img/icon/white-arrow.svg" alt="img">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function alerteReservation(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Réservation impossible',
            text: 'Veuillez d\'abord sélectionner une compagnie avant de continuer.',
            icon: 'error', // icône : croix rouge animée
            confirmButtonText: 'Choisir une compagnie',
            confirmButtonColor: '#dc3545', // rouge Bootstrap
            backdrop: true,
            allowOutsideClick: true,
            allowEscapeKey: true,
        }).then((result) => {
            if (result.isConfirmed) {
                const section = document.getElementById('compagnies');
                if (section) {
                    section.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    }
</script>