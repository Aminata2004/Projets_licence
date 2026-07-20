<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon-32x32.png" type="image/png" />
    <title>Nouveau mot de passe · TransGest</title>
    
    <!-- Google Font + Bootstrap Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
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
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: white;
            margin-bottom: 1.2rem;
            box-shadow: 0 12px 25px -5px rgba(16, 185, 129, 0.6);
        }
        .brand-header h1 {
            font-size: 2rem;
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
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }
        .input-wrapper input:focus + i, .input-wrapper input:focus ~ i {
            color: #10b981;
        }
        .btn-submit {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            border-radius: 18px;
            color: white;
            font-size: 1.05rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.5);
            margin-bottom: 1rem;
            margin-top: 2rem;
        }
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(16, 185, 129, 0.6);
        }
        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 1.1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 18px;
            color: white;
            font-size: 1.05rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }
        .alert {
            padding: 1rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            text-align: center;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand-header">
            <div class="brand-icon">
                <i class="bi bi-key-fill"></i>
            </div>
            <h1>Nouveau mot de passe</h1>
            <p>Veuillez entrer votre nouveau mot de passe sécurisé</p>
        </div>

        <?php $this->view("admin/set_flash"); ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="hidden" name="emailUser" value="<?= htmlspecialchars($emailUser ?? '') ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
            <div class="input-group">
                <label>Nouveau mot de passe</label>
                <div class="input-wrapper">
                    <input type="password" name="new_password" placeholder="••••••••" required autofocus>
                    <i class="bi bi-lock-fill"></i>
                </div>
            </div>

            <div class="input-group">
                <label>Confirmer le mot de passe</label>
                <div class="input-wrapper">
                    <input type="password" name="confirm_password" placeholder="••••••••" required>
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-check-circle-fill"></i> Confirmer
            </button>
            <a href="<?= BASE_URL ?>/admin/Loguins" class="btn-back">
                Retour à la connexion
            </a>
        </form>
    </div>
</div>

</body>
</html>