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
            <button type="button" id="btnSelectionMultiple" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm">
              <i class="bx bx-list-check fs-5"></i> Sélection multiple
            </button>
            <a href="<?= BASE_URL ?>/admin/Employes/printListPdf" class="btn btn-outline-danger d-flex align-items-center gap-2 shadow-sm" target="_blank">
              <i class="bx bxs-file-pdf fs-5"></i> Télécharger en PDF
            </a>
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
              <form id="formImpressionGroupee" method="post" action="<?= BASE_URL ?>/admin/Employes/printSelection" target="_blank">
                <?= csrf_field() ?>
                <div id="batchToolbar" class="d-none align-items-center flex-wrap gap-3 mb-3 p-3 bg-light rounded border">
                  <span id="selectionCount" class="fw-semibold">0 sélectionné(s)</span>
                  <select name="format" class="form-select form-select-sm" style="width:auto">
                    <option value="1" selected>Format 1 (Corporate Horizontal)</option>
                  </select>
                  <button type="submit" id="btnImprimerSelection" class="btn btn-primary btn-sm d-flex align-items-center gap-2" disabled>
                    <i class="bx bx-printer"></i> Imprimer la sélection (max 4 par feuille A4)
                  </button>
                </div>

              <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header text-center mobile-card-table" style="width:100%">
                  <thead class="table-light text-center">
                    <tr>
                      <th class="fw-semibold selection-col d-none"><input type="checkbox" id="checkAll" class="form-check-input"></th>
                      <th class="fw-semibold">Photo</th>
                      <th class="fw-semibold">Nom &amp; prénom</th>
                      <th class="fw-semibold">Fonction</th>
                      <th class="fw-semibold">Contact</th>
                      <th class="fw-semibold">Téléphone</th>
                      <th class="fw-semibold">Affectation</th>
                      <th class="fw-semibold">Statut</th>
                      <th class="fw-semibold">Action</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <?php if (empty($employes)): ?>
                      <tr>
                        <td colspan="9" class="text-muted py-4">Aucun employé trouvé pour cette compagnie.</td>
                      </tr>
                    <?php endif; ?>
                    <?php foreach ($employes as $employe): ?>
                      <tr class="align-middle text-center">
                        <td class="selection-col d-none">
                          <input type="checkbox" class="form-check-input row-check" name="selection[]" value="<?= htmlspecialchars($employe['type']) ?>:<?= (int)$employe['id'] ?>">
                        </td>
                        <td data-label="Photo">
                            <?php if (!empty($employe['photo'])): ?>
                                <img src="<?= ASSET_URL ?>/uploads/profiles/<?= htmlspecialchars($employe['photo']) ?>" alt="Photo" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bx bx-user fs-5"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td data-label="Nom & prénom"><?= htmlspecialchars($employe['nom']) ?></td>
                        <td data-label="Fonction">
                          <span class="badge <?= $employe['type'] === 'Chauffeur' ? 'bg-warning text-dark' : 'bg-primary' ?>">
                            <?= htmlspecialchars($employe['fonction']) ?>
                          </span>
                        </td>
                        <td data-label="Contact"><?= htmlspecialchars($employe['contact']) ?></td>
                        <td data-label="Téléphone"><?= htmlspecialchars($employe['telephone']) ?></td>
                        <td data-label="Affectation"><?= htmlspecialchars($employe['affectation']) ?></td>
                        <td data-label="Statut">
                          <span class="badge <?= $employe['statut'] === 'Actif' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= htmlspecialchars($employe['statut']) ?>
                          </span>
                        </td>
                        <td data-label="Action">
                            <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-2"
                                    data-bs-toggle="modal" data-bs-target="#modalImprimerBadge"
                                    data-type="<?= htmlspecialchars($employe['type']) ?>" data-id="<?= (int)$employe['id'] ?>"
                                    data-nom="<?= htmlspecialchars($employe['nom']) ?>">
                                <i class="bx bx-printer"></i> Imprimer
                            </button>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
              </form>

              <!-- Modal : choix du format d'impression du badge -->
              <div class="modal fade" id="modalImprimerBadge" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="bx bx-printer text-primary fs-4"></i>
                        Imprimer le badge <span id="modalImprimerNom" class="text-primary"></span>
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                      <p class="text-muted mb-3">Choisissez le format de la carte à imprimer.</p>
                      <a href="#" id="modalImprimerLienFormat1" target="_blank"
                         class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-between px-3 py-3">
                        <span class="d-flex align-items-center gap-2">
                          <i class="bx bxs-id-card fs-4"></i>
                          Format 1 — Corporate Horizontal
                        </span>
                        <i class="bx bx-chevron-right fs-4"></i>
                      </a>
                    </div>
                  </div>
                </div>
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
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var btnToggle = document.getElementById('btnSelectionMultiple');
      var toolbar = document.getElementById('batchToolbar');
      var table = document.getElementById('example');
      var checkAll = document.getElementById('checkAll');
      var btnSubmit = document.getElementById('btnImprimerSelection');
      var countLabel = document.getElementById('selectionCount');
      if (!btnToggle || !toolbar || !table) return;

      function updateCount() {
        var checked = table.querySelectorAll('.row-check:checked').length;
        countLabel.textContent = checked + ' sélectionné(s)';
        btnSubmit.disabled = checked === 0;
      }

      btnToggle.addEventListener('click', function () {
        var enabling = toolbar.classList.contains('d-none');
        toolbar.classList.toggle('d-none', !enabling);
        toolbar.classList.toggle('d-flex', enabling);
        document.querySelectorAll('.selection-col').forEach(function (el) {
          el.classList.toggle('d-none', !enabling);
        });
        btnToggle.classList.toggle('active', enabling);
        btnToggle.classList.toggle('btn-outline-secondary', !enabling);
        btnToggle.classList.toggle('btn-secondary', enabling);

        if (!enabling) {
          table.querySelectorAll('.row-check').forEach(function (cb) { cb.checked = false; });
          if (checkAll) checkAll.checked = false;
        }
        updateCount();
      });

      table.addEventListener('change', function (e) {
        if (e.target.classList.contains('row-check')) {
          updateCount();
        }
      });

      if (checkAll) {
        checkAll.addEventListener('change', function () {
          table.querySelectorAll('.row-check').forEach(function (cb) { cb.checked = checkAll.checked; });
          updateCount();
        });
      }

      var modalImprimer = document.getElementById('modalImprimerBadge');
      var lienFormat1 = document.getElementById('modalImprimerLienFormat1');
      if (modalImprimer) {
        modalImprimer.addEventListener('show.bs.modal', function (event) {
          var btn = event.relatedTarget;
          if (!btn) return;
          var type = btn.getAttribute('data-type');
          var id = btn.getAttribute('data-id');
          var nom = btn.getAttribute('data-nom') || '';
          document.getElementById('modalImprimerNom').textContent = nom;
          lienFormat1.href =
            '<?= BASE_URL ?>/admin/Employes/printCard/' + encodeURIComponent(type) + '/' + encodeURIComponent(id) + '/1';
        });
      }
      if (lienFormat1) {
        lienFormat1.addEventListener('click', function () {
          var modalInstance = bootstrap.Modal.getInstance(modalImprimer);
          if (modalInstance) modalInstance.hide();
        });
      }
    });
  </script>
</body>
</html>
