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
        <div class="breadcrumb-title pe-3">Personnel</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Employés</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
          <div class="d-flex gap-2">
            <?php if ($peutVoirUtilisateurs): ?>
              <a href="<?= BASE_URL ?>/admin/Configurations" class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="bx bx-user fs-5"></i> Gérer les utilisateurs
              </a>
            <?php endif; ?>
            <?php if ($peutVoirChauffeurs): ?>
              <a href="<?= BASE_URL ?>/admin/Chauffeurs_cars" class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="bx bx-car fs-5"></i> Gérer les chauffeurs
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!--end breadcrumb-->

      <?php $this->view("admin/set_flash") ?>

      <div class="row">
        <div class="col-12">
          <div class="card config-card">
            <div class="card-header">
              <h5 class="mb-0 fw-bold"><i class="bx bx-id-card me-2"></i>Liste des employés</h5>
            </div>
            <div class="card-body p-4">
              <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header text-center mobile-card-table" style="width:100%">
                  <thead class="table-light text-center">
                    <tr>
                      <th class="fw-semibold">Nom &amp; prénom</th>
                      <th class="fw-semibold">Fonction</th>
                      <th class="fw-semibold">Contact</th>
                      <th class="fw-semibold">Affectation</th>
                      <th class="fw-semibold">Type</th>
                      <th class="fw-semibold">Statut</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <?php if (empty($employes)): ?>
                      <tr>
                        <td colspan="6" class="text-muted py-4">Aucun employé trouvé pour cette compagnie.</td>
                      </tr>
                    <?php endif; ?>
                    <?php foreach ($employes as $employe): ?>
                      <tr class="align-middle text-center">
                        <td data-label="Nom & prénom"><?= htmlspecialchars($employe['nom']) ?></td>
                        <td data-label="Fonction"><?= htmlspecialchars($employe['fonction']) ?></td>
                        <td data-label="Contact"><?= htmlspecialchars($employe['contact']) ?></td>
                        <td data-label="Affectation"><?= htmlspecialchars($employe['affectation']) ?></td>
                        <td data-label="Type">
                          <span class="badge <?= $employe['type'] === 'Chauffeur' ? 'bg-warning text-dark' : 'bg-primary' ?>">
                            <?= htmlspecialchars($employe['type']) ?>
                          </span>
                        </td>
                        <td data-label="Statut">
                          <span class="badge <?= $employe['statut'] === 'Actif' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= htmlspecialchars($employe['statut']) ?>
                          </span>
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
