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
                            <li class="breadcrumb-item active" aria-current="page">Gestion des escale</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="d-flex gap-2">

                        <!-- Bouton Ajouter -->
                        <button type="button" class="btn btn-success d-flex align-items-center gap-2 shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#exampleDangerModal">
                            <i class="bx bx-plus-circle fs-5"></i> Ajouter
                        </button>

                        <!-- Bouton Retour -->
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
                    <a class="nav-link active text-break mb-0" role="tab"
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
                            <h5 class="mb-0 fw-bold"><i class="bx bx-map-pin me-2"></i>Liste des escales</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header text-center mobile-card-table" style="width:100%">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Escale</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($liste as $listes): ?>
                                            <tr>
                                                <td data-label="Escale"><?= $listes->escales ?></td>
                                                <td class=" " data-label="Action">
                                                    <div class="dropup text-center">
                                                        <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                            &#8943; <!-- Trois points horizontaux -->
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item edit-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleDangerModal1"
                                                                data-id_escale="<?= $listes->id_escale ?>"
                                                                data-escales="<?= htmlspecialchars($listes->escales, ENT_QUOTES) ?>"
                                                                href="#">
                                                                ✏️ Modifier
                                                            </a>
                                                            <a class="dropdown-item text-danger delete-button"
                                                                href="<?= BASE_URL ?>/admin/Add_liste_escales/delete/<?= $listes->id_escale ?>">
                                                                🗑 Supprimer
                                                            </a>

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
            <!-- modal pour insertion des escale -->
            <div class="modal fade" id="exampleDangerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">

                        <!-- Header -->
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title fw-bold text-white">
                                <i class="bx bx-map-pin me-2"></i> Nouvelle escale
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>

                        <!-- Body -->
                        <div class="modal-body">
                            <form action="" method="post" class="needs-validation" novalidate>

                                <!-- Lignes d'escales, ajoutables dynamiquement -->
                                <div id="escalesRows">
                                    <div class="mb-3 d-flex gap-2 align-items-start escale-row">
                                        <div class="flex-grow-1">
                                            <label class="form-label fw-semibold">Nom de l’escale
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                class="form-control form-control-lg"
                                                name="escales[]"
                                                placeholder="Ex : Fana"
                                                required>
                                            <div class="invalid-feedback">Veuillez entrer le nom de l’escale.</div>
                                        </div>
                                        <button type="button" class="btn btn-outline-danger remove-row-btn mt-4 d-none" title="Retirer cette ligne">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="button" id="addEscaleRow" class="btn btn-sm btn-outline-primary mb-3">
                                    <i class="bx bx-plus"></i> Ajouter une ligne
                                </button>

                                <!-- Footer -->
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        <i class="bx bx-x me-1"></i> Annuler
                                    </button>
                                    <button type="submit" class="btn btn-primary" name="save">
                                        <i class="bx bx-save me-1"></i> Enregistrer
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- model de modification  -->
            <div class="modal fade" id="exampleDangerModal1" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">

                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title fw-bold text-white">
                                <i class="bx bx-edit-alt me-2"></i> Modifier l’escale
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form action="<?= BASE_URL ?>/admin/Add_liste_escales/update" method="post">
                                <!-- Champ caché pour l'ID -->
                                <input type="hidden" id="edit_id_escale" name="id_escale">

                                <!-- Champ escale -->
                                <div class="mb-3">
                                    <label for="edit_escale" class="form-label">Nom de l’escale <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" id="edit_escale" name="escales" required>
                                </div>

                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        Annuler
                                    </button>
                                    <button type="submit" class="btn btn-primary fw-bold">
                                        Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <!-- fin de modals -->
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
        // "Add to row" : permet de saisir plusieurs escales d'un coup avant d'enregistrer.
        document.addEventListener("DOMContentLoaded", function() {
            const rowsContainer = document.getElementById("escalesRows");
            const addBtn = document.getElementById("addEscaleRow");

            function toggleRemoveButtons() {
                const rows = rowsContainer.querySelectorAll(".escale-row");
                rows.forEach(function(row) {
                    row.querySelector(".remove-row-btn").classList.toggle("d-none", rows.length <= 1);
                });
            }

            addBtn.addEventListener("click", function() {
                const firstRow = rowsContainer.querySelector(".escale-row");
                const newRow = firstRow.cloneNode(true);
                newRow.querySelector("input").value = "";
                rowsContainer.appendChild(newRow);
                toggleRemoveButtons();
            });

            rowsContainer.addEventListener("click", function(e) {
                const btn = e.target.closest(".remove-row-btn");
                if (btn) {
                    btn.closest(".escale-row").remove();
                    toggleRemoveButtons();
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButtons = document.querySelectorAll(".edit-btn");

            editButtons.forEach(btn => {
                btn.addEventListener("click", function() {
                    let id = this.getAttribute("data-id_escale");
                    let escale = this.getAttribute("data-escales");

                    // Remplir le formulaire du modal
                    document.getElementById("edit_id_escale").value = id;
                    document.getElementById("edit_escale").value = escale;
                });
            });
        });
    </script>
    <!-- script pour la suppression -->
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
                            // On appelle la route de suppression
                            window.location.href = deleteUrl;
                        }
                    });
                });
            });
        });
    </script>


</body>

</html>