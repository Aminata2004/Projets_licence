<!doctype html>
<html lang="en" class="minimal-theme">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon-32x32.png" type="image/png" />
        <!-- Bootstrap CSS -->
        <link href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?= BASE_URL ?>/assets/css/bootstrap-extended.css" rel="stylesheet" />
        <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet" />
        <link href="<?= BASE_URL ?>/assets/css/icons.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

        <!-- loader-->
        <link href="<?= BASE_URL ?>/assets/css/pace.min.css" rel="stylesheet" />

        <title>G-compagnie</title>
    </head>

    <body>
        <!--start wrapper-->
        <div class="wrapper">

            <!--start content-->
            <main class="authentication-content">
            <div class="container-fluid">
                    <div class="authentication-card">
                        <div class="card shadow-lg rounded-4 overflow-hidden border-0">
                            <?php $this->view("admin/set_flash"); ?>
                            <div class="row g-0">

                                <!-- Partie image -->
                                <div class="col-lg-6 bg-login d-flex align-items-center justify-content-center p-4">
                                    <img src="<?= BASE_URL ?>/assets/images/error/forgot-password-frent-img.jpg"
                                        class="img-fluid rounded-3 shadow" alt="Réinitialisation mot de passe">
                                </div>

                                <!-- Partie formulaire -->
                                <div class="col-lg-6 bg-white">
                                    <div class="card-body p-4 p-sm-5">
                                        <h3 class="card-title text-center mb-3 fw-bold">🔑 Nouveau mot de passe</h3>
                                        <p class="card-text mb-4 text-center text-muted">
                                            Veuillez entrer votre nouveau mot de passe
                                        </p>

                                        <!-- Messages -->
                                        <?php if (!empty($data['error'])): ?>
                                            <div class="alert alert-danger"><?= $data['error'] ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($data['success'])): ?>
                                            <div class="alert alert-success"><?= $data['success'] ?></div>
                                        <?php endif; ?>

                                        <!-- Formulaire -->
                                        <form method="post">
                                            <div class="row g-3">
                                                <!-- Nouveau mot de passe -->
                                                <div class="col-12">
                                                    <label for="inputNewPassword" class="form-label">Nouveau mot de passe</label>
                                                    <div class="ms-auto position-relative">
                                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                            <i class="bi bi-lock-fill"></i>
                                                        </div>
                                                        <input type="password"
                                                            class="form-control radius-30 ps-5"
                                                            id="inputNewPassword"
                                                            name="new_password"
                                                            placeholder="Entrer le nouveau mot de passe" required>
                                                    </div>
                                                </div>

                                                <!-- Confirmation mot de passe -->
                                                <div class="col-12">
                                                    <label for="inputConfirmPassword" class="form-label">Confirmer le mot de passe</label>
                                                    <div class="ms-auto position-relative">
                                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                            <i class="bi bi-lock-fill"></i>
                                                        </div>
                                                        <input type="password"
                                                            class="form-control radius-30 ps-5"
                                                            id="inputConfirmPassword"
                                                            name="confirm_password"
                                                            placeholder="Confirmer le mot de passe" required>
                                                    </div>
                                                </div>

                                                <!-- Boutons -->
                                                <div class="col-12">
                                                    <div class="d-grid gap-3">
                                                        <button type="submit" class="btn btn-primary radius-30 fw-semibold">
                                                            Changer le mot de passe
                                                        </button>
                                                        <a href="<?= BASE_URL ?>/admin/Loguins"
                                                            class="btn btn-light border radius-30 fw-semibold">
                                                            Retour à la connexion
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </div>

            </main>

            <!--end page main-->

        </div>
        <!--end wrapper-->
        <!--plugins-->
        <script src="<?= BASE_URL ?>/assets/js/jquery.min.js"></script>
        <script src="<?= BASE_URL ?>/assets/js/pace.min.js"></script>
    </body>
</html>