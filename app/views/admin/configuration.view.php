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
    <main class="page-content ">
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
          <div class="btn-group">
            <a href="<?= BASE_URL ?>/admin/Configurations/add_utilisateurs" class="btn btn-primary "> + Ajouter</a> &nbsp;
            <a href="javascript:history.back()" class="btn btn-primary split-bg-primary"><i
                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

          </div>
        </div>
      </div>
      <!--end breadcrumb-->
      <div class="row">
        <div class="col-xxl-3">
          <div class="card custom-card">
            <div class="card-header">
              <div class="card-title">
                Generale
              </div>
            </div>
            <div class="card-body">
              <ul class="nav nav-tabs flex-column vertical-tabs-3" role="tablist">
                <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                  <li class="nav-item">
                    <a class="nav-link text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Compagnies"
                      aria-selected="true">
                      <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Compagnie
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($user->userHasPermission('Configuration_gestion_gare')) { ?>
                  <li class="nav-item">
                    <a class="nav-link text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Liste_gares"
                      aria-selected="true">
                      <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Gares
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('utilisateur_apercu')) { ?>
                  <li class="nav-item">
                    <a class="nav-link active text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Configurations"
                      aria-selected="true">
                      <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Utilisateur
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_escale')) { ?>
                  <li class="nav-item">
                    <a class="nav-link  text-break mb-0" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_escales"
                      aria-selected="true">
                      <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Escale
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_trajets')) { ?>
                  <!-- <li class="nav-item mt-2">
                    <a class="nav-link  text-break mb-0" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_trajets"
                      aria-selected="true">
                      <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Trajets
                    </a>
                  </li> -->
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_horaire')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link  text-break mb-0" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_horaire"
                      aria-selected="true">
                      <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Horaire
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_car/chauffeur')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link   text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Cars_chauffeurs"
                      aria-selected="true">
                      <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Cars & Chauffeurs
                    </a>
                  </li>
                <?php } ?>
                <li class="nav-item mt-2">
                  <a class="nav-link  text-break mb-0" role="tab"
                    aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_horaire/add_permission"
                    aria-selected="true">
                    <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Permission
                  </a>
                </li>
                <?php if ($user->userHasPermission('Configuration_place/limite')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link  text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Compagnies/place_limite"
                      aria-selected="true">
                      <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Place limite
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-xxl-9">
          <div class="card shadow-lg rounded-3 border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
              <h5 class="mb-0 fw-bold">👥 Liste des utilisateurs</h5>
            </div>
            <div class="card-body p-4">
              <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header" style="width:100%">
                  <thead class="table-light text-center">
                    <tr>
                      <th class="fw-semibold">Utilisateur</th>
                      <th class="fw-semibold">Email</th>
                      <th class="fw-semibold">Gare</th>
                      <th class="fw-semibold">Droit</th>
                      <th class="fw-semibold">Action</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <?php foreach ($liste as $listes): ?>
                      <tr class="align-middle text-center">
                        <td><?= htmlspecialchars($listes->utilisateurs) ?></td>
                        <td><?= htmlspecialchars($listes->emailUser) ?></td>
                        <td><?= htmlspecialchars($listes->numeroGare) ?></td>
                        <td><?= htmlspecialchars($listes->droit) ?></td>
                        <!-- <td>
                          <div class="dropdown">
                            <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                              &#8943;
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                              <li>
                                <a class="dropdown-item" href="#"><i class="bi bi-pencil-square me-2"></i>Modifier</a>
                              </li>
                              <li>
                                <a class="dropdown-item text-danger" href="#"><i class="bi bi-x-circle me-2"></i>Désactiver</a>
                              </li>
                            </ul>
                          </div>
                        </td> -->
                        <td class="text-center">
                          <a href="<?= BASE_URL ?>/admin/Permissions/assigner/<?= htmlspecialchars($listes->idUser) ?>"
                            title="Permission">
                            <i class="bx bx-lock-open text-primary fs-4 cursor-pointer"></i>
                          </a>


                          <!-- Modifier -->
                          <i class="bx bx-edit text-primary me-2 fs-4 cursor-pointer add-button"
                            title="Modifier"
                            data-bs-toggle="modal"
                            data-bs-target="#modalModification"
                            data-id="<?= $listes->idUser ?>"
                            data-utilisateurs="<?= $listes->utilisateurs ?>"
                            data-email="<?= $listes->emailUser ?>"
                            data-motpasse="<?= $listes->motPasse ?>"
                            data-droit="<?= $listes->droit ?>">
                          </i>

                          <!-- Activation/Désactivation -->
                          <?php if ($listes->status == 1): ?>

                            <!-- Activer -->
                            <i class="bx bx-user-check text-success fs-4 cursor-pointer"

                              data-bs-toggle="modal"
                              data-bs-target="#animationModal<?= $listes->idUser ?>">
                            </i>
                          <?php else: ?>
                            <!-- Désactiver -->
                            <i class="bx bx-user-x text-danger fs-4 cursor-pointer"
                              data-bs-toggle="modal"
                              data-bs-target="#animationModal<?= $listes->idUser ?>">
                            </i>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <!-- Modal Activation/Désactivation -->
                      <div class="modal fade animate__animated animate__slideInDown"
                        id="animationModal<?= $listes->idUser ?>"
                        tabindex="-1"
                        role="dialog"
                        aria-labelledby="exampleModalCenterTitle"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <form action="<?= BASE_URL ?>/admin/Configurations" method="post">
                              <div class="modal-header">
                                <h5 class="modal-title fw-bold text-primary" id="exampleModalCenterTitle">
                                  Confirmation de <?= $listes->status == 1 ? 'désactivation' : 'réactivation' ?>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                </button>
                              </div>
                              <div class="modal-body text-center">
                                <i class="nav-icon fa fa-exclamation-triangle text-danger" style="font-size: 60px;"></i>
                                <p class="mt-3">
                                  Voulez-vous vraiment
                                  <strong class="text-danger">
                                    <?= $listes->status == 1 ? 'désactiver' : 'activer' ?>
                                  </strong>
                                  le compte <br><strong><?= $listes->utilisateurs ?></strong> ?
                                </p>

                                <input type="hidden" name="idUser" value="<?= $listes->idUser ?>">
                                <input type="hidden" name="newStatut" value="<?= $listes->status == 1 ? 0 : 1 ?>">
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" name="valider" class="btn btn-primary">
                                  Oui <?= $listes->status == 1 ? 'Désactiver' : 'Activer' ?>
                                </button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    <?php endforeach ?>
                  </tbody>
                </table>
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
</body>

</html>