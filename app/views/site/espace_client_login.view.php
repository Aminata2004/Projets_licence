<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Client - TransGest</title>
    <link href="<?= BASE_URL ?>/assets_site/css/inter.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets_site/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f3b5e 0%, #1a5276 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        :root {
            --primary: #0f3b5e;
            --secondary: #e67e22;
            --radius: 8px;
        }
        .header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 16px 24px;
        }
        .header-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo img { height: 55px; }
        .back-link {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            opacity: 0.85;
            transition: opacity 0.2s;
        }
        .back-link:hover { opacity: 1; }

        .login-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 48px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        }
        .login-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #0f3b5e, #1a5276);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .login-icon i { color: white; font-size: 1.8rem; }
        h2 {
            text-align: center;
            color: #0f3b5e;
            font-size: 1.5rem;
            margin-bottom: 8px;
        }
        .subtitle {
            text-align: center;
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 32px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.875rem;
            margin-bottom: 8px;
        }
        .form-group label i { margin-right: 6px; color: #0f3b5e; }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e8ecf0;
            border-radius: var(--radius);
            font-size: 0.95rem;
            transition: border-color 0.2s;
            outline: none;
        }
        .form-group input:focus { border-color: #0f3b5e; }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0f3b5e, #1a5276);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(15,59,94,0.35);
        }
        .alert-error {
            background: #fef0f0;
            border: 1px solid #fca5a5;
            color: #c0392b;
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: 0.875rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .help-text {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            text-align: center;
            color: #7f8c8d;
            font-size: 0.82rem;
        }
        .help-text a { color: #0f3b5e; text-decoration: none; font-weight: 600; }
        @media (max-width: 480px) {
            .login-card { padding: 32px 24px; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <a href="<?= BASE_URL ?>/site/Accueil" class="logo">
                <img src="<?= BASE_URL ?>/images/logos/transgest_logo.png" alt="TransGest">
            </a>
            <a href="<?= BASE_URL ?>/site/Accueil" class="back-link">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    </header>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <h2>Espace Client</h2>
            <p class="subtitle">Consultez vos réservations, paiements et épargne</p>

            <?php if (!empty($error)): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-ticket-alt"></i>Numéro de billet</label>
                    <input type="text" name="numeroBillets" placeholder="Ex: SMT20250701..." required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i>Numéro de téléphone</label>
                    <input type="tel" name="numeroClient" placeholder="Ex: 7X XXX XX XX" required>
                </div>
                <button type="submit" name="login_client" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Accéder à mon espace
                </button>
            </form>

            <div class="help-text">
                Votre numéro de billet figure dans l'email de confirmation reçu après votre réservation.<br><br>
                <a href="<?= BASE_URL ?>/site/Accueil">Faire une réservation</a>
            </div>
        </div>
    </div>
</body>
</html>
