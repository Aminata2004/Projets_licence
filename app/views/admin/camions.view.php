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
                            <li class="breadcrumb-item active" aria-current="page">Camions</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="d-flex gap-2">
                        <?php if (($_SESSION['droit'] ?? null) !== 'PDG'): ?>
                          <button type="button" class="btn btn-success d-flex align-items-center gap-2 shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#exampleDangerModal">
                            <i class="bx bx-plus-circle fs-5"></i> Ajouter
                        </button>
                        <?php endif; ?>
                        <!-- Modal  insertion-->
                        <div class="modal fade" id="exampleDangerModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content ">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white">Ajout de camions</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-dart">
                                        <form action="" method="post">
                                            <div id="camionsRows">
                                                <div class="row align-items-end mb-2 camion-row">
                                                    <div class="col-md-5">
                                                        <label class="form-label">Numero de camion</label>
                                                        <input type="number" class="form-control" placeholder="Numero de camion" name="numero_camion[]" required autocomplete="off">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="form-label">Matricule</label>
                                                        <input type="text" class="form-control" placeholder="Matricule" name="matriculle_camion[]" required autocomplete="off">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-outline-danger remove-row-btn d-none w-100" title="Retirer cette ligne">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" id="addCamionRow" class="btn btn-sm btn-outline-primary mb-3">
                                                <i class="bx bx-plus"></i> Ajouter une ligne
                                            </button>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <?php if (($_SESSION['droit'] ?? null) !== 'PDG'): ?>
                                        <button type="submit" class="btn btn-primary" name="save">Enregistre</button>
                                        <?php endif; ?>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        &nbsp;
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
                    <a class="nav-link active text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Cars_chauffeurs"
                      aria-selected="true">
                      <i class="bx bx-car me-2 align-middle d-inline-block"></i>Cars & Camions & Chauffeurs
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
                            <h5 class="mb-0 fw-bold"><i class="bx bx-truck me-2"></i>Liste des camions</h5>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills nav-pills-primary mb-3" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="<?= BASE_URL ?>/admin/Cars_chauffeurs" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Cars</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" href="<?= BASE_URL ?>/admin/Camions" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-truck font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Camions</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="<?= BASE_URL ?>/admin/Chauffeurs_cars" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Chauffers</div>
                                        </div>
                                    </a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header text-center mobile-card-table" style="width:100%">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th class="fw-semibold">Numéro du camion</th>
                                                    <th class="fw-semibold">Numéro de matricule</th>
                                                    <th class="fw-semibold">Statut</th>
                                                    <th class="fw-semibold">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($listeCamion as $listeCamions): ?>
                                                    <tr>
                                                        <td data-label="Numéro du camion"><?= $listeCamions->numero_camion ?></td>
                                                        <td data-label="Numéro de matricule"><?= $listeCamions->matriculle ?></td>
                                                        <td data-label="Statut">
                                                            <?php if (($listeCamions->actif ?? 'on') === 'on'): ?>
                                                                <span class="badge bg-success">Actif</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Inactif</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td data-label="Action">
                                                            <div class="dropdown">
                                                                <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    &#8943;
                                                                </a>
                                                                <ul class="dropdown-menu shadow-sm">
                                                                    <?php if (($_SESSION['droit'] ?? null) !== 'PDG'): ?>
                                                                    <li>
                                                                        <a class="dropdown-item edit-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editCamionModal"
                                                                            data-id="<?= $listeCamions->id_camion ?>"
                                                                            data-numero="<?= htmlspecialchars($listeCamions->numero_camion, ENT_QUOTES) ?>"
                                                                            data-matricule="<?= htmlspecialchars($listeCamions->matriculle, ENT_QUOTES) ?>"
                                                                            data-actif="<?= htmlspecialchars($listeCamions->actif ?? 'on', ENT_QUOTES) ?>"
                                                                            href="#">
                                                                            ✏️ Modifier
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger delete-button"
                                                                            href="<?= BASE_URL ?>/admin/Camions/delete/<?= $listeCamions->id_camion ?>">
                                                                            🗑 Supprimer
                                                                        </a>
                                                                    </li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                        </table>
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
        <!-- model de mofication -->

        <!-- Modal -->
        <div class="modal fade" id="editCamionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?= BASE_URL ?>/admin/Camions/update" method="post">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Modifier le camion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id_camion" id="edit_id">

                            <div class="mb-3">
                                <label for="edit_numero" class="form-label">Numéro</label>
                                <input type="text" class="form-control" name="numero_camion" id="edit_numero">
                            </div>

                            <div class="mb-3">
                                <label for="edit_matricule" class="form-label">Matricule</label>
                                <input type="text" class="form-control" name="matriculle" id="edit_matricule">
                            </div>

                            <div class="mb-3">
                                <label for="edit_actif" class="form-label">Statut</label>
                                <select class="form-select" name="actif" id="edit_actif">
                                    <option value="on">Actif</option>
                                    <option value="off">Inactif</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <?php if (($_SESSION['droit'] ?? null) !== 'PDG'): ?>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- fin -->
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
        // "Add to row" : permet de saisir plusieurs camions (numéro/matricule) d'un coup.
        document.addEventListener("DOMContentLoaded", function() {
            const rowsContainer = document.getElementById("camionsRows");
            const addBtn = document.getElementById("addCamionRow");

            function toggleRemoveButtons() {
                const rows = rowsContainer.querySelectorAll(".camion-row");
                rows.forEach(function(row) {
                    row.querySelector(".remove-row-btn").classList.toggle("d-none", rows.length <= 1);
                });
            }

            addBtn.addEventListener("click", function() {
                const firstRow = rowsContainer.querySelector(".camion-row");
                const newRow = firstRow.cloneNode(true);
                newRow.querySelectorAll("input").forEach(function(input) { input.value = ""; });
                rowsContainer.appendChild(newRow);
                toggleRemoveButtons();
            });

            rowsContainer.addEventListener("click", function(e) {
                const btn = e.target.closest(".remove-row-btn");
                if (btn) {
                    btn.closest(".camion-row").remove();
                    toggleRemoveButtons();
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButtons = document.querySelectorAll(".edit-btn");
            editButtons.forEach(button => {
                button.addEventListener("click", function() {
                    document.getElementById("edit_id").value = this.dataset.id;
                    document.getElementById("edit_numero").value = this.dataset.numero;
                    document.getElementById("edit_matricule").value = this.dataset.matricule;
                    document.getElementById("edit_actif").value = this.dataset.actif;
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    const deleteUrl = this.getAttribute('href');

                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: "Cette action est irréversible !",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler',
                        customClass: {
                            confirmButton: 'btn btn-danger me-2',
                            cancelButton: 'btn btn-secondary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = deleteUrl;
                        }
                    });
                });
            });
        });
    </script>



</body>

</html>
