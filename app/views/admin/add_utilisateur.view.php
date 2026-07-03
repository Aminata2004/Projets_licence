<?php $this->view('admin/partials/header') ?>
<?php $user = new Configuration($_SESSION['id_utilisateur']) ?>

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        <?php $this->view('admin/partials/navbar') ?>
        <!--end top header-->

        <!--start sidebar -->
        <?php $this->view('admin/partials/sidebar') ?>
        <!--end sidebar -->

        <!--start content-->
        <main class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Configuration</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Utilisateur</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="d-flex gap-2">

                        <a href="javascript:history.back()"
                            class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                            <i class="bx bx-left-arrow-alt fs-5"></i> Retour
                        </a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                                        <div class="col-xxl-3">
          <div class="card config-card">
            <div class="card-header">
              <div class="card-title">
                <i class="bx bx-cog fs-4 me-2"></i> Paramètres Généraux
              </div>
            </div>
            <div class="card-body p-3">
              <ul class="nav nav-tabs flex-column vertical-tabs-custom" role="tablist">
                <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                  <li class="nav-item">
                    <a class="nav-link  text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Compagnies"
                      aria-selected="true">
                      <i class="bx bx-buildings me-2 align-middle d-inline-block"></i>Compagnie
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($user->userHasPermission('utilisateur_apercu')) { ?>
                  <li class="nav-item">
                    <a class="nav-link active text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Configurations"
                      aria-selected="true">
                      <i class="bx bx-user me-2 align-middle d-inline-block"></i>Utilisateur
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_gare')) { ?>
                  <li class="nav-item">
                    <a class="nav-link  text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Liste_gares"
                      aria-selected="true">
                      <i class="bx bx-home me-2 align-middle d-inline-block"></i>Gares
                    </a>
                  </li>
                <?php } ?>
                
                <?php if ($user->userHasPermission('Configuration_gestion_escale')) { ?>
                  <li class="nav-item">
                    <a class="nav-link  text-break mb-0" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_escales"
                      aria-selected="true">
                      <i class="bx bx-map-pin me-2 align-middle d-inline-block"></i>Escale
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_trajets')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link  text-break mb-0" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_trajets"
                      aria-selected="true">
                      <i class="bx bx-map-alt me-2 align-middle d-inline-block"></i>Trajets
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_horaire')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link  text-break mb-0" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_horaire"
                      aria-selected="true">
                      <i class="bx bx-time me-2 align-middle d-inline-block"></i>Horaire
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_car/chauffeur')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link  text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Cars_chauffeurs"
                      aria-selected="true">
                      <i class="bx bx-car me-2 align-middle d-inline-block"></i>Cars & Chauffeurs
                    </a>
                  </li>
                <?php } ?>
                
                <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                <li class="nav-item mt-2">
                  <a class="nav-link  text-break mb-0" role="tab"
                    aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_horaire/add_permission"
                    aria-selected="true">
                    <i class="bx bx-shield-quarter me-2 align-middle d-inline-block"></i>Permission
                  </a>
                </li>
                <?php endif; ?>
                
                <?php if ($user->userHasPermission('Configuration_place/limite')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link  text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Compagnies/place_limite"
                      aria-selected="true">
                      <i class="bx bx-chair me-2 align-middle d-inline-block"></i>Place limite
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
                <div class="col-xxl-9">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="card config-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="bx bx-user-plus me-2"></i>Espace d'enregistrement des utilisateurs</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="p-4 border rounded-3">
                                <form class="row g-3" method="post">

                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="form-label fw-semibold">Nom & prénom</label>
                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Nom & prénom" name="utilisateurs" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="validationCustom02" class="form-label fw-semibold">Email</label>
                                        <input type="email" class="form-control" id="validationCustom02" placeholder="Email" name="emailUser" required>
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <label for="validationCustomUsername" class="form-label fw-semibold">Mot de passe</label>
                                        <div class="input-group has-validation">
                                            <input type="password" class="form-control" name="motPasse" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="validationCustom03" class="form-label fw-semibold">Confirmer mot de passe</label>
                                        <input type="password" class="form-control" name="ConfirmermotPasse" required>
                                    </div> -->

                                    <!-- Champ Droit -->
                                    <div class="col-md-6">
                                        <label for="droitSelect" class="form-label fw-semibold">Droit</label>
                                        <select class="form-select" id="droitSelect" name="droit" required>
                                            <option value="" disabled selected>Le droit</option>
                                            <option value="Utilisateur">Utilisateur</option>
                                            <?php if(isset($_SESSION['droit']) && $_SESSION['droit'] !== 'chef_d_escale'): ?>
                                                <option value="chef_d_escale">Chef d'escale</option>
                                            <?php endif; ?>
                                            <?php if(isset($_SESSION['droit']) && $_SESSION['droit'] === 'super_admin'): ?>
                                                <option value="Admin">Admin</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- Champ Gare -->
                                    <div class="col-md-6" id="gareField">
                                        <label for="gareSelect" class="form-label fw-semibold">Gare</label>
                                        <select class="form-select single-select" id="gareSelect" name="id_agence" data-placeholder="Choisissez une gare">
                                            <option selected disabled value="">Choisissez une gare</option>
                                            <?php foreach ($listeGares as $listeGare): ?>
                                                <option value="<?= htmlspecialchars($listeGare->idAgence); ?>">
                                                    <?= htmlspecialchars($listeGare->localite . ' ( ' . $listeGare->numeroGare . ' )'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Champ Compagnie -->
                                    <div class="col-md-12" id="compagnieField" style="display: none;">
                                        <label for="compagnieSelect" class="form-label fw-semibold">Compagnie</label>
                                        <select class="form-select" id="compagnieSelect" name="id_compagnie">
                                            <option value="" disabled selected>Sélectionnez une compagnie</option>
                                            <?php foreach ($listeCompagnie as $listeCompagnies): ?>
                                                <option value="<?= htmlspecialchars($listeCompagnies->id_compagnie); ?>">
                                                    <?= htmlspecialchars($listeCompagnies->nom_compagnie); ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="col-12 mt-4 d-flex gap-3">
                                        <button class="btn btn-primary fw-semibold d-flex align-items-center" type="submit" name="saveutilisateur">
                                            <i class="bx bx-save fs-5 me-2"></i> Enregistrer
                                        </button>
                                        <a href="<?= BASE_URL ?>/admin/Configurations" class="btn btn-light fw-semibold border shadow-sm d-flex align-items-center" style="border-radius: 12px; padding: 0.6rem 1.5rem;">
                                            <i class="bx bx-list-ul fs-5 me-2"></i> Voir la liste
                                        </a>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!--end row-->
        </main>
        <!--end page main-->
        <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
    </div>
    <!--end wrapper-->
    <?php $this->view('admin/partials/foot') ?>
    <!-- JS -->
    <script>
        document.getElementById('droitSelect').addEventListener('change', function() {
            const droit = this.value;
            const compagnieField = document.getElementById('compagnieField');
            const gareField = document.getElementById('gareField');

            if (droit === 'Admin') {
                compagnieField.style.display = 'block';
                gareField.style.display = 'none';
            } else {
                compagnieField.style.display = 'none';
                gareField.style.display = 'block';
            }
        });
    </script>
</body>

</html>