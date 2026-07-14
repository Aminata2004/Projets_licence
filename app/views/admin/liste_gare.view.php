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
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Configuration</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Gares</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="d-flex gap-2">
                        <a href="<?= BASE_URL ?>/admin/Liste_gares/add_gares" class="btn btn-success d-flex align-items-center gap-2 shadow-sm">
                            <i class="bx bx-plus-circle fs-5"></i> Ajouter
                        </a>
                        <a href="javascript:history.back()"
                            class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                            <i class="bx bx-left-arrow-alt fs-5"></i> Retour
                        </a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                                        <div class="col-12 col-xxl-3">
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
                    <a class="nav-link  text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Configurations"
                      aria-selected="true">
                      <i class="bx bx-user me-2 align-middle d-inline-block"></i>Utilisateur
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_gare')) { ?>
                  <li class="nav-item">
                    <a class="nav-link active text-break" role="tab"
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
                <div class="col-12 col-xxl-9">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="card config-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="bx bx-buildings me-2"></i>Liste des gares</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header text-center mobile-card-table" style="width:100%">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>N gare</th>
                                            <th>Localite</th>
                                            <th>Code marchant</th>
                                            <th>Tel</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($listes as $liste) : ?>
                                            <tr>
                                                <td data-label="N gare"><?= $liste->numeroGare ?></td>
                                                <td data-label="Localite"><?= $liste->localite ?></td>
                                                <td data-label="Code marchant"><?= $liste->code ?></td>
                                                <td data-label="Tel"><?= $liste->tel ?></td>
                                                <td data-label="Statut">
                                                    <?php if(isset($liste->status) && $liste->status == 0): ?>
                                                        <span class="badge bg-danger">Suspendue</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class=" " data-label="Action">
                                                    <div class="dropup text-center">
                                                        <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                            &#8943; <!-- Trois points horizontaux -->
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu">
                                                            <a class="dropdown-item edit-btn"
                                                                data-bs-toggle="modal" data-bs-target="#exampleDangerModal1"
                                                                data-numeros="<?= htmlspecialchars($liste->idAgence, ENT_QUOTES) ?>"
                                                                data-numero="<?= htmlspecialchars($liste->numeroGare, ENT_QUOTES) ?>"
                                                                data-localite="<?= htmlspecialchars($liste->localite, ENT_QUOTES) ?>"
                                                                data-code="<?= htmlspecialchars($liste->code, ENT_QUOTES) ?>"
                                                                data-tel="<?= htmlspecialchars($liste->tel, ENT_QUOTES) ?>"
                                                                href="">Modifier</a>
                                                            <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Liste_gares/suspend/<?= $liste->idAgence ?>">
                                                                <?= (isset($liste->status) && $liste->status == 0) ? 'Activer' : 'Suspendre' ?>
                                                            </a>
                                                            <a class="dropdown-item text-danger delete-button" href="<?= BASE_URL ?>/admin/Liste_gares/delete/<?= $liste->idAgence ?>">Supprimer</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
    <!--model pour la modification-->
    <div class="modal fade" id="exampleDangerModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Modification du gare</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dart">
                    <form class="row g-3 "
                        method="post" action="<?= BASE_URL ?>/admin/Liste_gares/edit">
                        <input type="hidden" name="idAgence" id="inputnumeros">
                        <div class="col-md-6">
                            <label for="tel" class="form-label">Numero du gare
                            </label>
                            <input type="text" class="form-control"
                                value="" name="numeroGare" id="inputnumero"
                                placeholder="" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tel" class="form-label">Localite
                            </label>
                            <input type="text" class="form-control"
                                value="" name="localite"
                                placeholder="" required id="inputlocalite">
                        </div>
                        <div class="col-md-6">
                            <label for="tel" class="form-label">Code marchant du gare
                            </label>
                            <input type="text" class="form-control"
                                value="" name="code"
                                placeholder="" required id="inputcode">
                        </div>
                        <div class="col-md-6">
                            <label for="bsValidation10"
                                class="form-label">Numero d'orange</label>
                            <input type="text" class="form-control" id="tel"
                                maxlength="11" oninput="verifierNumero()"
                                value="" name="tel" placeholder="" required>
                            <small id="messageErreur" class="text-danger"></small>
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" name="edit"
                                    class="btn btn-primary px-4">Modifier</button>
                                <a href="" class="btn btn-info">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- la fin du model  -->

        <?php $this->view('admin/partials/foot') ?>
        <script src="<?= BASE_URL ?>/mon_js/scrip_agence.js"></script>
        <script src="<?= BASE_URL ?>/mon_js/alert_delete.js"></script>

</body>

</html>