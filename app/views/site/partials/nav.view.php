
<?php
// Détecte la page courante pour surligner le bon lien de menu ("active"),
// que ce soit sur le site public ("/") ou explicitement sous "/site/...".
$currentUrl = strtolower(trim($_GET['url'] ?? '', '/'));
$currentController = preg_replace('#^site/#', '', $currentUrl);
$currentController = explode('/', $currentController)[0];
if ($currentController === '') {
    $currentController = 'accueil';
}
$navActive = function (...$controllers) use ($currentController) {
    return in_array($currentController, $controllers, true) ? ' active' : '';
};
?>
<!-- Mobile Nav Menu (partagé par toutes les pages) -->
<header class="header" style="position:sticky;top:0;z-index:1000;background:white;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
    <div class="container" style="max-width:1280px;margin:0 auto;padding:0 24px;">
        <div class="header-inner" style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;">
            <a href="<?= BASE_URL ?>/site/Accueil" class="logo" style="display:flex;align-items:center;text-decoration:none;"><img src="<?= BASE_URL ?>/images/logos/transgest_logo.png" alt="TransGest" style="height:70px;width:auto;object-fit:contain;"></a>

            <!-- Desktop Navigation -->
            <div class="nav" style="display:flex;gap:32px;align-items:center;">
                <a href="<?= BASE_URL ?>/site/Accueil" class="nav-link<?= $navActive('accueil') ?>" style="text-decoration:none;color:#2c3e50;font-weight:500;transition:color 0.3s;">Accueil</a>
                <a href="<?= BASE_URL ?>/site/compagnies" class="nav-link<?= $navActive('compagnies') ?>" style="text-decoration:none;color:#2c3e50;font-weight:500;transition:color 0.3s;">Reservation</a>
                <a href="<?= BASE_URL ?>/site/Suivis_colis" class="nav-link<?= $navActive('suivis_colis') ?>" style="text-decoration:none;color:#2c3e50;font-weight:500;transition:color 0.3s;">Suivis de colis</a>
                <a href="<?= BASE_URL ?>/site/Contact" class="nav-link<?= $navActive('contact') ?>" style="text-decoration:none;color:#2c3e50;font-weight:500;transition:color 0.3s;">Contact</a>
            </div>

            <!-- Mobile Menu Button (Hamburger) -->
            <button id="menuToggle" style="display:none;background:none;border:none;font-size:1.8rem;color:#0f3b5e;cursor:pointer;padding:4px;">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>

<!-- Panneau mobile -->
<div id="mobileNav" style="position:fixed;top:0;right:-100%;width:80%;max-width:320px;height:100vh;background:white;box-shadow:-5px 0 30px rgba(0,0,0,0.15);z-index:2000;padding:80px 30px 30px;transition:right 0.35s ease;display:flex;flex-direction:column;gap:0;">
    <button id="closeMenu" style="position:absolute;top:20px;right:20px;background:none;border:none;font-size:1.8rem;cursor:pointer;color:#2c3e50;"><i class="fas fa-times"></i></button>
    <a href="<?= BASE_URL ?>/site/Accueil" class="nav-link<?= $navActive('accueil') ?>" style="display:block;padding:15px 0;text-decoration:none;color:#2c3e50;font-weight:600;border-bottom:1px solid #eee;font-size:1rem;">Accueil</a>
    <a href="<?= BASE_URL ?>/site/compagnies" class="nav-link<?= $navActive('compagnies') ?>" style="display:block;padding:15px 0;text-decoration:none;color:#2c3e50;font-weight:600;border-bottom:1px solid #eee;font-size:1rem;">Reservation</a>
    <a href="#destinations" style="display:block;padding:15px 0;text-decoration:none;color:#2c3e50;font-weight:600;border-bottom:1px solid #eee;font-size:1rem;">Destinations</a>
    <a href="<?= BASE_URL ?>/site/Suivis_colis" class="nav-link<?= $navActive('suivis_colis') ?>" style="display:block;padding:15px 0;text-decoration:none;color:#2c3e50;font-weight:600;border-bottom:1px solid #eee;font-size:1rem;">Suivis de colis</a>
    <a href="<?= BASE_URL ?>/site/Contact" class="nav-link<?= $navActive('contact') ?>" style="display:block;padding:15px 0;text-decoration:none;color:#2c3e50;font-weight:600;border-bottom:1px solid #eee;font-size:1rem;">Contact</a>
</div>
<div id="overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1500;display:none;"></div>

<style>
    @media (max-width: 768px) {
        #menuToggle { display: block !important; }
        .nav { display: none !important; }
    }
    .nav-link.active {
        color: #e67e22 !important;
        font-weight: 700 !important;
    }
    .nav .nav-link.active {
        border-bottom: 2px solid #e67e22;
        padding-bottom: 6px;
    }
    /* Le panneau de menu mobile (#mobileNav) est positionné hors écran via "right:-100%".
       Sans ceci, il élargit quand même le scroll horizontal de la page sur mobile,
       même fermé (bug affectant toutes les pages via ce partiel partagé). */
    html {
        overflow-x: hidden;
        max-width: 100%;
    }
</style>

<script>
(function() {
    var toggle   = document.getElementById('menuToggle');
    var mobileNav= document.getElementById('mobileNav');
    var closeBtn = document.getElementById('closeMenu');
    var overlay  = document.getElementById('overlay');

    function openMenu() {
        mobileNav.style.right = '0';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function closeMenuFunc() {
        mobileNav.style.right = '-100%';
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    if (toggle)   toggle.addEventListener('click', openMenu);
    if (closeBtn) closeBtn.addEventListener('click', closeMenuFunc);
    if (overlay)  overlay.addEventListener('click', closeMenuFunc);

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) closeMenuFunc();
    });
})();
</script>