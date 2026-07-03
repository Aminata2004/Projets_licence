<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion · TransGest</title>
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
            overflow-x: hidden;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ════════════════════════════════
           PANNEAU GAUCHE — 100% CSS
        ════════════════════════════════ */
        .left-panel {
            flex: 1.3;
            background: linear-gradient(145deg, #071525 0%, #0f2d54 55%, #071525 100%);
            position: relative;
            display: none;
            overflow: hidden;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 992px) {
            .left-panel { display: flex; }
        }

        /* — Formes géométriques flottantes (CSS pur) — */
        .geo-bg { position: absolute; inset: 0; z-index: 1; pointer-events: none; }

        .geo {
            position: absolute;
            animation: geoFloat ease-in-out infinite;
        }

        .g1 { width:110px; height:110px; border:2px solid rgba(245,158,11,.18); border-radius:22px;
              top:7%;  left:8%;  animation-duration:13s; animation-delay:0s;   transform:rotate(15deg); }
        .g2 { width:65px;  height:65px;  border:2px solid rgba(255,255,255,.10); border-radius:50%;
              top:16%; right:10%; animation-duration:17s; animation-delay:-4s; }
        .g3 { width:45px;  height:45px;  background:rgba(245,158,11,.12); border-radius:10px;
              top:52%; left:5%;  animation-duration:10s; animation-delay:-7s; transform:rotate(40deg); }
        .g4 { width:85px;  height:85px;  border:2px solid rgba(245,158,11,.15); border-radius:50%;
              bottom:20%; right:7%; animation-duration:15s; animation-delay:-2s; }
        .g5 { width:36px;  height:36px;  background:rgba(255,255,255,.06); border-radius:8px;
              bottom:10%; left:18%; animation-duration:12s; animation-delay:-9s; transform:rotate(25deg); }
        .g6 { width:150px; height:150px; border:1px solid rgba(255,255,255,.05); border-radius:50%;
              top:36%; right:4%;  animation-duration:19s; animation-delay:-5s; }
        .g7 { width:28px;  height:28px;  background:rgba(245,158,11,.18); border-radius:50%;
              top:70%; right:22%; animation-duration:9s;  animation-delay:-3s; }
        .g8 { width:50px;  height:50px;  border:2px solid rgba(245,158,11,.12); border-radius:12px;
              top:28%; left:16%; animation-duration:14s; animation-delay:-11s; transform:rotate(55deg); }
        .g9 { width:190px; height:190px; border:1px solid rgba(245,158,11,.06); border-radius:50%;
              bottom:-55px; left:-55px; animation-duration:21s; animation-delay:-8s; }

        @keyframes geoFloat {
            0%,100% { transform: translateY(0px)   scale(1);    }
            40%      { transform: translateY(-20px) scale(1.04); }
            70%      { transform: translateY(10px)  scale(0.97); }
        }

        /* — Anneaux pulsants autour du logo — */
        .rings-wrap {
            position: absolute;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .ring {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(245,158,11,.28);
            animation: ringPulse 3.8s ease-out infinite;
        }
        .r1 { width:190px; height:190px; animation-delay:0s;   }
        .r2 { width:290px; height:290px; animation-delay:1s;   }
        .r3 { width:400px; height:400px; animation-delay:2s;   }
        .r4 { width:510px; height:510px; animation-delay:3s;   }

        @keyframes ringPulse {
            0%   { transform: scale(0.82); opacity: 0.8; }
            100% { transform: scale(1.18); opacity: 0;   }
        }

        /* — Contenu centré — */
        .left-content {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 48px 40px;
            animation: fadeUp 1s ease-out;
        }

        .left-logo {
            width: 220px;
            max-width: 78%;
            object-fit: contain;
            filter: drop-shadow(0 0 22px rgba(245,158,11,.4));
            margin-bottom: 36px;
            animation: logoPulse 4s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%,100% { filter: drop-shadow(0 0 18px rgba(245,158,11,.35)); }
            50%      { filter: drop-shadow(0 0 38px rgba(245,158,11,.65)); }
        }

        .left-content h2 {
            font-size: 1.95rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.35;
            margin-bottom: 12px;
        }
        .left-content h2 em { font-style: normal; color: var(--primary); }

        .left-content p {
            font-size: .97rem;
            color: rgba(255,255,255,.6);
            max-width: 340px;
            font-weight: 300;
            line-height: 1.8;
        }

        .left-stats {
            display: flex;
            gap: 40px;
            margin-top: 36px;
        }
        .stat { text-align: center; }
        .stat-n { font-size: 1.55rem; font-weight: 700; color: var(--primary); }
        .stat-l { font-size: .7rem; color: rgba(255,255,255,.45); text-transform: uppercase; letter-spacing: 1.2px; margin-top: 4px; }

        /* ════════════════════════════════
           PANNEAU DROIT — Formulaire
        ════════════════════════════════ */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            padding: 40px 20px;
            position: relative;
            overflow: hidden;
        }

        /* ── Bulles montantes CSS ── */
        .bubbles { position: absolute; inset: 0; pointer-events: none; z-index: 0; }

        .bubble {
            position: absolute;
            bottom: -80px;
            border-radius: 50%;
            opacity: 0;
            animation: riseUp ease-in infinite;
        }

        /* Couleurs alternées orange / bleu marine */
        .b1  { width:18px; height:18px; left:8%;   background:rgba(245,158,11,.18); animation-duration:9s;  animation-delay:0s;   }
        .b2  { width:10px; height:10px; left:18%;  background:rgba(11,31,58,.12);   animation-duration:12s; animation-delay:1.5s; }
        .b3  { width:24px; height:24px; left:28%;  background:rgba(245,158,11,.13); animation-duration:10s; animation-delay:3s;   }
        .b4  { width:8px;  height:8px;  left:38%;  background:rgba(11,31,58,.10);   animation-duration:8s;  animation-delay:0.8s; }
        .b5  { width:20px; height:20px; left:48%;  background:rgba(245,158,11,.15); animation-duration:11s; animation-delay:2s;   }
        .b6  { width:14px; height:14px; left:58%;  background:rgba(11,31,58,.09);   animation-duration:13s; animation-delay:4s;   }
        .b7  { width:30px; height:30px; left:66%;  background:rgba(245,158,11,.10); animation-duration:14s; animation-delay:1s;   }
        .b8  { width:12px; height:12px; left:76%;  background:rgba(11,31,58,.13);   animation-duration:9s;  animation-delay:3.5s; }
        .b9  { width:16px; height:16px; left:84%;  background:rgba(245,158,11,.17); animation-duration:11s; animation-delay:0.5s; }
        .b10 { width:22px; height:22px; left:92%;  background:rgba(11,31,58,.08);   animation-duration:15s; animation-delay:2.5s; }
        .b11 { width:9px;  height:9px;  left:13%;  background:rgba(245,158,11,.12); animation-duration:10s; animation-delay:6s;   }
        .b12 { width:26px; height:26px; left:53%;  background:rgba(11,31,58,.10);   animation-duration:12s; animation-delay:5s;   }

        @keyframes riseUp {
            0%   { transform: translateY(0)    scale(1)    rotate(0deg);   opacity: 0;   }
            10%  { opacity: 1; }
            80%  { opacity: .6; }
            100% { transform: translateY(-110vh) scale(1.3) rotate(180deg); opacity: 0; }
        }

        .login-box {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 2;
        }

        .glass-card {
            background: var(--card-bg);
            border: 1px solid rgba(0,0,0,.07);
            border-radius: 24px;
            box-shadow: 0 20px 48px -12px rgba(0,0,0,.14), inset 0 1px 0 rgba(255,255,255,.9);
            padding: 44px 40px;
            animation: cardIn .65s ease-out;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0);    }
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(22px) scale(.98); }
            to   { opacity: 1; transform: translateY(0)    scale(1);   }
        }

        /* — En-tête carte — */
        .card-header-block {
            text-align: center;
            margin-bottom: 28px;
        }
        .card-logo {
            width: 180px;
            max-width: 80%;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .card-sub { color: var(--text-muted); font-size: .88rem; margin-top: 2px; }

        /* — Champs — */
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
            transition: color .3s;
            pointer-events: none;
            z-index: 2;
        }

        .input-wrap input {
            width: 100%;
            padding: 12px 46px 12px 42px;
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
        .input-wrap input:focus + .icon-l { color: var(--primary); }

        .pwd-toggle {
            position: absolute;
            right: 15px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.05rem;
            cursor: pointer;
            transition: color .3s;
            z-index: 2;
        }
        .pwd-toggle:hover { color: var(--text-main); }

        /* — Options — */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
            font-size: .88rem;
        }
        .checkbox-label {
            display: flex; align-items: center; gap: 7px;
            cursor: pointer; color: var(--text-muted); user-select: none;
        }
        .checkbox-label input[type="checkbox"] {
            accent-color: var(--primary); width: 16px; height: 16px;
        }
        .link-forgot {
            color: var(--text-muted); text-decoration: none;
            font-weight: 500; transition: color .3s;
        }
        .link-forgot:hover { color: var(--primary); }

        /* — Bouton — */
        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none; border-radius: 13px;
            color: #fff; font-size: 1.02rem; font-weight: 600;
            font-family: inherit; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 10px 20px -8px rgba(234,88,12,.58);
            transition: all .3s ease; letter-spacing: .3px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 14px 26px -8px rgba(234,88,12,.72); }
        .btn-submit:active { transform: translateY(0); }

        /* — Pied de carte — */
        .card-footer-text {
            display: flex; align-items: center; justify-content: center;
            gap: 7px; margin-top: 26px;
            color: var(--text-muted); font-size: .8rem; opacity: .65;
        }
        .card-footer-text i { color: #10b981; }

        /* Autofill */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 30px #f9f9f9 inset !important;
            -webkit-text-fill-color: var(--text-main) !important;
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- ═══ PANNEAU GAUCHE ═══ -->
    <div class="left-panel">

        <!-- Formes géométriques flottantes -->
        <div class="geo-bg">
            <div class="geo g1"></div>
            <div class="geo g2"></div>
            <div class="geo g3"></div>
            <div class="geo g4"></div>
            <div class="geo g5"></div>
            <div class="geo g6"></div>
            <div class="geo g7"></div>
            <div class="geo g8"></div>
            <div class="geo g9"></div>
        </div>

        <!-- Anneaux pulsants -->
        <div class="rings-wrap">
            <div class="ring r1"></div>
            <div class="ring r2"></div>
            <div class="ring r3"></div>
            <div class="ring r4"></div>
        </div>

        <!-- Logo + texte -->
        <div class="left-content">
            <img src="<?= BASE_URL ?>/images/logos/transgest_logo.png" alt="TransGest" class="left-logo">
            <h2>Gérez vos <em>voyages</em><br>en toute simplicité</h2>
            <p>Accédez à votre espace d'administration pour piloter compagnies, trajets, billets et colis en temps réel.</p>
            <div class="left-stats">
                <div class="stat">
                    <div class="stat-n">100%</div>
                    <div class="stat-l">Sécurisé</div>
                </div>
                <div class="stat">
                    <div class="stat-n">24/7</div>
                    <div class="stat-l">Disponible</div>
                </div>
                <div class="stat">
                    <div class="stat-n">Fast</div>
                    <div class="stat-l">Performant</div>
                </div>
            </div>
        </div>

    </div>

    <!-- ═══ PANNEAU DROIT — Formulaire ═══ -->
    <div class="right-panel">

        <!-- Bulles montantes (CSS pur) -->
        <div class="bubbles">
            <div class="bubble b1"></div>
            <div class="bubble b2"></div>
            <div class="bubble b3"></div>
            <div class="bubble b4"></div>
            <div class="bubble b5"></div>
            <div class="bubble b6"></div>
            <div class="bubble b7"></div>
            <div class="bubble b8"></div>
            <div class="bubble b9"></div>
            <div class="bubble b10"></div>
            <div class="bubble b11"></div>
            <div class="bubble b12"></div>
        </div>

        <div class="login-box">
            <div class="glass-card">

                <div class="card-header-block">
                    <img src="<?= BASE_URL ?>/images/logos/transgest_logo.png" alt="TransGest" class="card-logo">
                    <div class="card-sub">Connectez-vous à votre espace</div>
                </div>

                <?php $this->view("admin/set_flash"); ?>

                <form method="post" action="">

                    <div class="form-group">
                        <label>Adresse e-mail</label>
                        <div class="input-wrap">
                            <input type="email" name="emailUser" placeholder="exemple@transgest.com" required autofocus>
                            <i class="bi bi-envelope-fill icon-l"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mot de passe</label>
                        <div class="input-wrap">
                            <input type="password" name="motPasse" id="pwd" placeholder="••••••••" required>
                            <i class="bi bi-lock-fill icon-l"></i>
                            <i class="bi bi-eye pwd-toggle" id="togglePwd"></i>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" id="remember">
                            <span>Se souvenir de moi</span>
                        </label>
                        <a href="<?= BASE_URL ?>/admin/Profils/mot_passe_oublie" class="link-forgot">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <button type="submit" name="connexion" class="btn-submit">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Se connecter
                    </button>

                </form>

                <div class="card-footer-text">
                    <i class="bi bi-shield-check"></i>
                    <span>Connexion sécurisée &bull; &copy; <?= date('Y') ?> TransGest</span>
                </div>

            </div>
        </div>
    </div>

</div>

<script>
    document.getElementById('togglePwd').addEventListener('click', function () {
        const pwd = document.getElementById('pwd');
        const isText = pwd.type === 'text';
        pwd.type = isText ? 'password' : 'text';
        this.classList.toggle('bi-eye',       isText);
        this.classList.toggle('bi-eye-slash', !isText);
    });
</script>

</body>
</html>
