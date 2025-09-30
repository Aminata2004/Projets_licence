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
                <img src="<?= BASE_URL ?>/assets/images/error/ChatGPT Image 11 juin 2025, 09_36_22.png"
                  class="img-fluid rounded-3 shadow" alt="Illustration de connexion">
              </div>

              <!-- Partie formulaire -->
              <div class="col-lg-6 bg-white">
                <div class="card-body p-4 p-sm-5">
                  <h3 class="card-title text-center mb-3 fw-bold">Connexion</h3>
                  <p class="card-text mb-4 text-center text-muted">
                    Accédez à votre espace et suivez vos activités facilement.
                  </p>

                  <form class="form-body" method="post">

                    <!-- Séparateur -->
                    <div class="login-separater text-center mb-4">
                      <span class="fw-semibold text-uppercase small text-muted">

                      </span>
                      <hr>
                    </div>

                    <div class="row g-3">
                      <!-- Email -->
                      <div class="col-12">
                        <label for="inputEmailAddress" class="form-label">Adresse e-mail</label>
                        <div class="ms-auto position-relative">
                          <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                            <i class="bi bi-envelope-fill"></i>
                          </div>
                          <input type="email" class="form-control radius-30 ps-5"
                            id="inputEmailAddress"
                            placeholder="Entrez votre adresse e-mail"
                            name="emailUser" required>
                        </div>
                      </div>

                      <!-- Mot de passe -->
                      <div class="col-12">
                        <label for="inputChoosePassword" class="form-label">Mot de passe</label>
                        <div class="ms-auto position-relative">
                          <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                            <i class="bi bi-lock-fill"></i>
                          </div>
                          <input type="password" class="form-control radius-30 ps-5"
                            id="inputChoosePassword"
                            placeholder="Entrez votre mot de passe"
                            name="motPasse" required>
                        </div>
                      </div>

                      <!-- Bouton connexion -->
                      <div class="col-12">
                        <div class="d-grid">
                          <button type="submit" class="btn btn-primary radius-30 fw-semibold" name="connexion">
                            Se connecter
                          </button>
                        </div>
                      </div>

                      <!-- Lien inscription -->
                      <div class="col-12 text-center">
                        <p class="mb-0 mt-3">

                          <a href="<?= BASE_URL?>/admin/Profils/mot_passe_oublie" class="fw-semibold text-primary">
                                mot de passe oublié ?
                          </a>
                        </p>
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