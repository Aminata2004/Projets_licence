<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon-32x32.png" type="image/png" />
    <title>Connexion · TransGest</title>
    
    <!-- Google Font + Bootstrap Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            /* Image de réservation de bus / gare */
            background: url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.5) 100%);
            z-index: 0;
        }
        .auth-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 460px;
            padding: 1.5rem;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 3rem 2.5rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .brand-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .brand-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #f59e0b, #ea580c);
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: white;
            margin-bottom: 1.2rem;
            box-shadow: 0 12px 25px -5px rgba(234, 88, 12, 0.6);
        }
        .brand-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }
        .brand-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }
        .input-group {
            margin-bottom: 1.5rem;
        }
        .input-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.6rem;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper i {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            font-size: 1.2rem;
            transition: color 0.3s;
        }
        .input-wrapper input {
            width: 100%;
            padding: 1rem 1rem 1rem 3.2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            color: white;
            font-size: 1.05rem;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        .input-wrapper input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
        .input-wrapper input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
        }
        .input-wrapper input:focus + i, .input-wrapper input:focus ~ i {
            color: #f59e0b;
        }
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.8);
            user-select: none;
        }
        .checkbox-label input {
            accent-color: #f59e0b;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .link {
            color: #fbd38d;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .link:hover {
            color: #f59e0b;
        }
        .btn-submit {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, #f59e0b, #ea580c);
            border: none;
            border-radius: 18px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 10px 20px -5px rgba(234, 88, 12, 0.5);
            margin-bottom: 1.5rem;
        }
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(234, 88, 12, 0.6);
        }
        .footer-text {
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
        .footer-text i {
            color: #10b981;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand-header">
            <div class="brand-icon">
                <i class="bi bi-bus-front-fill"></i>
            </div>
            <h1>Connexion</h1>
            <p>Accédez à votre espace d'administration TransGest</p>
        </div>

        <?php $this->view("admin/set_flash"); ?>

        <form method="post" action="">
            <div class="input-group">
                <label>Adresse e-mail</label>
                <div class="input-wrapper">
                    <input type="email" name="emailUser" placeholder="exemple@transgest.com" required autofocus>
                    <i class="bi bi-envelope-fill"></i>
                </div>
            </div>

            <div class="input-group">
                <label>Mot de passe</label>
                <div class="input-wrapper">
                    <input type="password" name="motPasse" placeholder="••••••••" required>
                    <i class="bi bi-lock-fill"></i>
                </div>
            </div>

            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" id="remember"> <span>Se souvenir de moi</span>
                </label>
                <a href="<?= BASE_URL ?>/admin/Profils/mot_passe_oublie" class="link">Mot de passe oublié ?</a>
            </div>

            <button type="submit" name="connexion" class="btn-submit">
                <i class="bi bi-box-arrow-in-right"></i> Se connecter
            </button>

            <div class="footer-text">
                <i class="bi bi-shield-check"></i> Données chiffrées &bull; Plateforme sécurisée
            </div>
        </form>
    </div>
</div>

</body>
</html>