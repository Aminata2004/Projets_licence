
<!-- Mobile Nav Menu (partagé par toutes les pages) -->
<header class="header" style="position:sticky;top:0;z-index:1000;background:white;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
    <div class="container" style="max-width:1280px;margin:0 auto;padding:0 24px;">
        <div class="header-inner" style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;">
            <a href="<?= BASE_URL ?>/site/Accueil" class="logo" style="display:flex;align-items:center;text-decoration:none;"><img src="<?= BASE_URL ?>/images/logos/transgest_logo.png" alt="TransGest" style="height:70px;width:auto;object-fit:contain;"></a>
            
            <!-- Desktop Navigation -->
            <div class="nav" style="display:flex;gap:32px;align-items:center;">
                <a href="<?= BASE_URL ?>/site/Accueil" style="text-decoration:none;color:#2c3e50;font-weight:500;transition:color 0.3s;">Accueil</a>
                <a href="<?= BASE_URL ?>/site/compagnies" style="text-decoration:none;color:#2c3e50;font-weight:500;transition:color 0.3s;">Compagnies</a>
                <a href="#destinations" style="text-decoration:none;color:#2c3e50;font-weight:500;transition:color 0.3s;">Destinations</a>
                <a href="<?= BASE_URL ?>/site/Contact" style="text-decoration:none;color:#2c3e50;font-weight:500;transition:color 0.3s;">Contact</a>
                <a href="<?= BASE_URL ?>/login" style="background:transparent;border:2px solid #0f3b5e;color:#0f3b5e;padding:8px 20px;border-radius:8px;font-weight:600;text-decoration:none;transition:all 0.3s;">Espace pro</a>
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
    <a href="<?= BASE_URL ?>/site/Accueil" style="display:block;padding:15px 0;text-decoration:none;color:#2c3e50;font-weight:600;border-bottom:1px solid #eee;font-size:1rem;">Accueil</a>
    <a href="<?= BASE_URL ?>/site/compagnies" style="display:block;padding:15px 0;text-decoration:none;color:#2c3e50;font-weight:600;border-bottom:1px solid #eee;font-size:1rem;">Compagnies</a>
    <a href="#destinations" style="display:block;padding:15px 0;text-decoration:none;color:#2c3e50;font-weight:600;border-bottom:1px solid #eee;font-size:1rem;">Destinations</a>
    <a href="<?= BASE_URL ?>/site/Contact" style="display:block;padding:15px 0;text-decoration:none;color:#2c3e50;font-weight:600;border-bottom:1px solid #eee;font-size:1rem;">Contact</a>
    <a href="<?= BASE_URL ?>/login" style="display:block;margin-top:20px;padding:14px 20px;background:linear-gradient(135deg,#0f3b5e,#1a5276);color:white;border-radius:8px;text-decoration:none;font-weight:700;text-align:center;">Espace pro</a>
</div>
<div id="overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1500;display:none;"></div>

<style>
    @media (max-width: 768px) {
        #menuToggle { display: block !important; }
        .nav { display: none !important; }
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