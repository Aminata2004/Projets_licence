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
                                <img src="<?= BASE_URL ?>/<?= BASE_URL ?>/<?=BASE_URL?>/assets_site_site_site/img/logo/black-logo.svg" alt="logo-img">
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
        style="background-image: url(<?=BASE_URL?>/assets_site/img/breadcrumb/breadcrumb.jpg);">
        <div class="container">
            <div class="row">
                <div class="page-heading">
                    <h2>Contact Us</h2>
                    <ul class="breadcrumb-list">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li><i class="fa-solid fa-chevrons-right"></i></li>
                        <li class="active">Contact Us</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- contact-info-section start -->
    <section class="contact-info-section section-padding fix">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="info-items">
                        <div class="icon">
                            <img src="<?=BASE_URL?>/assets_site/img/icon/19.svg" alt="">
                        </div>
                        <h3>Office Address</h3>
                        <p>Cedar Street, Chicago,60601, USA</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-items">
                        <div class="icon">
                            <img src="<?=BASE_URL?>/assets_site/img/icon/20.svg" alt="">
                        </div>
                        <h3>Call Us For Support:</h3>
                        <p>+4800 45 678 900</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-items">
                        <div class="icon">
                            <img src="<?=BASE_URL?>/assets_site/img/icon/21.svg" alt="">
                        </div>
                        <h3>Email Us Anytime:</h3>
                        <p>contact@example.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact-Section Start -->
    <section class="contact-section section-padding fix section-bg bg-cover"
        style="background-image: url(<?=BASE_URL?>/assets_site/img/contact/bg.png);">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="contact-wrapper">
                        <div class="section-title">
                            <span class="sub-title wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                Contact Us
                            </span>
                            <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                Let's Build An Awesome Project Together
                            </h2>
                        </div>
                        <div class="contact-thumb">
                            <img src="<?=BASE_URL?>/assets_site/img/contact/1.jpg" class="ex" alt="img">
                            <h4><img src="<?=BASE_URL?>/assets_site/img/icon/phone.svg" alt="img"> +12 608 (3456) 789</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-form style-2">
                        <h3>Fill The Contact Form</h3>
                        <p>Feel free to contact with us, we don't spam your email</p>
                        <form action="contact.php" method="post">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-clt">
                                        <input type="text" name="name" id="name" placeholder="Your name">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-clt">
                                        <input type="tel" name="phone" id="phone" placeholder="Phone number">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-clt">
                                        <input type="email" name="email" id="email" placeholder="Email address">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-clt">
                                        <textarea name="message" id="message"
                                            placeholder="Write your message"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="theme-btn style-2">
                                        Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- map section start -->
    <div class="map-section">
        <div class="map-items">
            <div class="googpemap">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6678.7619084840835!2d144.9618311901502!3d-37.81450084255415!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642b4758afc1d%3A0x3119cc820fdfc62e!2sEnvato!5e0!3m2!1sen!2sbd!4v1641984054261!5m2!1sen!2sbd"
                    style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>


    <!-- Footer-Section Start -->
    <?php $this->view('site/partials/footer') ?>
    <!--<< All JS Plugins >>-->
    <?php $this->view('site/partials/foot') ?>