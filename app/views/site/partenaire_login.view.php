<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Espace partenaire - TransGest</title>
    <link href="<?= BASE_URL ?>/assets_site/css/inter.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets_site/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f4f6fa; color: #1a1f2e; line-height: 1.5; }
        :root {
            --primary: #0f3b5e; --primary-dark: #0a2a44; --secondary: #e67e22;
            --gray-light: #ecf0f1; --gray: #7f8c8d; --dark: #2c3e50;
            --shadow: 0 2px 10px rgba(0,0,0,0.05); --shadow-md: 0 5px 20px rgba(0,0,0,0.08); --radius: 8px; --radius-lg: 12px;
        }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        section { padding: 60px 0; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 28px; border-radius: var(--radius); font-weight: 600; font-size: 0.9rem; transition: all 0.3s; cursor: pointer; border: none; text-decoration: none; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-block { width: 100%; }

        .auth-card { max-width: 460px; margin: 0 auto; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); padding: 36px; }
        .auth-tabs { display: flex; gap: 8px; margin-bottom: 28px; background: var(--gray-light); border-radius: var(--radius); padding: 4px; }
        .auth-tab { flex: 1; text-align: center; padding: 10px; border-radius: var(--radius); font-weight: 600; font-size: 0.85rem; cursor: pointer; color: var(--gray); }
        .auth-tab.active { background: white; color: var(--primary); box-shadow: var(--shadow); }
        .auth-panel { display: none; }
        .auth-panel.active { display: block; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 6px; color: var(--dark); }
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: var(--radius); font-size: 0.9rem; font-family: inherit; }
        .form-control:focus { outline: none; border-color: var(--secondary); }
        h1 { text-align: center; margin-bottom: 8px; font-size: 1.6rem; }
        .auth-subtitle { text-align: center; color: var(--gray); font-size: 0.85rem; margin-bottom: 28px; }
    </style>
</head>
<body>

<?php $this->view('site/partials/nav') ?>

<section>
    <div class="container">
        <h1>Espace partenaire</h1>
        <p class="auth-subtitle">Discutez directement avec notre équipe pour rejoindre TransGest</p>

        <div class="auth-card">
            <div class="auth-tabs">
                <div class="auth-tab active" data-tab="connexion">Se connecter</div>
                <div class="auth-tab" data-tab="inscription">Créer un compte</div>
            </div>

            <div class="auth-panel active" id="panel-connexion">
                <form method="POST">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="mot_de_passe" class="form-control" required>
                    </div>
                    <button type="submit" name="connexion" class="btn btn-primary btn-block">Se connecter</button>
                </form>
            </div>

            <div class="auth-panel" id="panel-inscription">
                <form method="POST">
                    <div class="form-group">
                        <label>Nom de la compagnie *</label>
                        <input type="text" name="nom_compagnie" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email_inscription" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="telephone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Mot de passe *</label>
                        <input type="password" name="mot_de_passe_inscription" class="form-control" required>
                    </div>
                    <button type="submit" name="inscription" class="btn btn-primary btn-block">Créer mon compte</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    document.querySelectorAll('.auth-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.auth-panel').forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('panel-' + tab.getAttribute('data-tab')).classList.add('active');
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['toast'])): ?>
    <script>
        Swal.fire({
            icon: "<?= addslashes($_SESSION['toast']['icon'] ?? 'info') ?>",
            title: "<?= addslashes($_SESSION['toast']['title']); ?>",
            text: "<?= addslashes($_SESSION['toast']['text']); ?>",
            confirmButtonText: "OK"
        });
    </script>
    <?php unset($_SESSION['toast']); ?>
<?php endif; ?>
</body>
</html>
