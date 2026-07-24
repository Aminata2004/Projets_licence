<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mot de passe oublié · TransGest</title>
    <link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon-32x32.png" type="image/png" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary:      #f59e0b;
            --primary-dark: #ea580c;
            --navy:         #0B1F3A;
            --bg:           #f4f6f9;
            --card-bg:      #ffffff;
            --text-main:    #0B1F3A;
            --text-muted:   #64748b;
            --input-bg:     #f9f9f9;
            --input-border: #d1d5db;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            background: var(--bg);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-box { width: 100%; max-width: 440px; }

        .glass-card {
            background: var(--card-bg);
            border: 1px solid rgba(0,0,0,.07);
            border-radius: 24px;
            box-shadow: 0 20px 48px -12px rgba(0,0,0,.14), inset 0 1px 0 rgba(255,255,255,.9);
            padding: 44px 40px;
            animation: cardIn .65s ease-out;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(22px) scale(.98); }
            to   { opacity: 1; transform: translateY(0)    scale(1);   }
        }

        .card-header-block { text-align: center; margin-bottom: 28px; }

        .card-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #fff;
            margin-bottom: 16px;
            box-shadow: 0 10px 20px -8px rgba(234,88,12,.5);
        }

        .card-header-block h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 6px; }
        .card-sub { color: var(--text-muted); font-size: .88rem; line-height: 1.5; }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            font-size: .76rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            margin-bottom: 7px;
        }

        .input-wrap { position: relative; }

        .input-wrap .icon-l {
            position: absolute;
            left: 15px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.05rem;
            pointer-events: none;
            z-index: 2;
        }

        .input-wrap input {
            width: 100%;
            padding: 12px 15px 12px 42px;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 13px;
            color: var(--text-main);
            font-size: .98rem;
            font-family: inherit;
            transition: all .3s ease;
        }
        .input-wrap input::placeholder { color: #94a3b8; }
        .input-wrap input:focus {
            outline: none;
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(245,158,11,.13);
        }

        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none; border-radius: 13px;
            color: #fff; font-size: 1.02rem; font-weight: 600;
            font-family: inherit; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 10px 20px -8px rgba(234,88,12,.58);
            transition: all .3s ease;
            margin-top: 8px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 14px 26px -8px rgba(234,88,12,.72); }

        .btn-back {
            display: flex; align-items: center; justify-content: center;
            width: 100%; padding: 13px; margin-top: 10px;
            background: transparent;
            border: 1.5px solid var(--input-border);
            border-radius: 13px;
            color: var(--text-muted);
            font-size: .95rem; font-weight: 500;
            text-decoration: none;
            transition: all .3s ease;
        }
        .btn-back:hover { border-color: var(--primary); color: var(--primary); }

        .alert {
            padding: 12px 16px;
            border-radius: 13px;
            margin-bottom: 18px;
            font-size: .9rem;
            text-align: center;
        }
        .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
    </style>
</head>
<body>

<div class="auth-box">
    <div class="glass-card">
        <div class="card-header-block">
            <div class="card-icon"><i class="bi bi-shield-lock-fill"></i></div>
            <?php if (!empty($emailValide)): ?>
                <h1>Nouveau mot de passe</h1>
                <p class="card-sub">Compte : <strong><?= htmlspecialchars($emailValide) ?></strong></p>
            <?php else: ?>
                <h1>Mot de passe oublié</h1>
                <p class="card-sub">Saisissez votre adresse email pour définir un nouveau mot de passe.</p>
            <?php endif; ?>
        </div>

        <?php $this->view("admin/set_flash"); ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($emailValide)): ?>
            <!-- Étape 2 : email déjà validé, on définit le nouveau mot de passe -->
            <form method="post" action="">
                <?= csrf_field() ?>
                <input type="hidden" name="emailUser" value="<?= htmlspecialchars($emailValide) ?>">

                <div class="form-group">
                    <label>Nouveau mot de passe</label>
                    <div class="input-wrap">
                        <input type="password" name="new_password" placeholder="••••••••" required autofocus>
                        <i class="bi bi-lock-fill icon-l"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirmer le mot de passe</label>
                    <div class="input-wrap">
                        <input type="password" name="confirm_password" placeholder="••••••••" required>
                        <i class="bi bi-shield-check icon-l"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle-fill"></i> Confirmer
                </button>
                <a href="<?= BASE_URL ?>/admin/Loguins" class="btn-back">Retour à la connexion</a>
            </form>
        <?php else: ?>
            <!-- Étape 1 : saisie de l'email -->
            <form method="post" action="">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Adresse e-mail</label>
                    <div class="input-wrap">
                        <input type="email" name="emailUser" placeholder="exemple@transgest.com" required autofocus>
                        <i class="bi bi-envelope-fill icon-l"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-arrow-right-circle-fill"></i> Continuer
                </button>
                <a href="<?= BASE_URL ?>/admin/Loguins" class="btn-back">Retour à la connexion</a>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
