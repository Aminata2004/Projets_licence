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
                            <li class="breadcrumb-item active" aria-current="page">Gestion des trajets</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="d-flex gap-2">

                        <!-- Modal -->
                        <div class="modal fade" id="exampleDangerModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white">Nouvelle trajet</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post">
                                            <div class="mb-3">
                                                <label class="form-label">Départ<span class="text-danger">*</span></label>
                                                <select id="choixAgence" name="depart" class="form-control">
                                                    <option></option> <!-- option vide pour permettre le placeholder -->
                                                    <?php
                                                    $localites_vues = []; // tableau pour suivre les localités déjà affichées

                                                    foreach ($liste_agence as $liste_agences):
                                                        $localite = $liste_agences->localite;

                                                        // Vérifie si cette localité a déjà été affichée
                                                        if (in_array($localite, $localites_vues)) {
                                                            continue; // on saute si elle est déjà dans la liste
                                                        }

                                                        // Sinon, on l'affiche et on la marque comme vue
                                                        $localites_vues[] = $localite;
                                                    ?>
                                                        <option value="<?= htmlspecialchars($localite); ?>">
                                                            <?= htmlspecialchars($localite); ?>
                                                        </option>
                                                    <?php endforeach; ?>

                                                </select>
                                            </div>


                                            <div class="mb-3">
                                                <label class="form-label">Escale</label>
                                                <select class="form-control multiple-select" multiple="multiple" placeholder="Choisissez un ou plusieurs escale" name="idEscale[]">
                                                    <?php foreach ($listeEscale as $listeEscales): ?>
                                                        <option
                                                            value="<?= htmlspecialchars($listeEscales->id_escale); ?>">
                                                            <?= htmlspecialchars($listeEscales->escales) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Destination<span class="text-danger">*</span></label>

                                                <select id="choixAgences" name="destination" class="form-control">
                                                    <option></option> <!-- option vide pour permettre le placeholder -->
                                                    <?php
                                                    $localites_vues = []; // tableau pour suivre les localités déjà affichées

                                                    foreach ($liste_agence as $liste_agences):
                                                        $localite = $liste_agences->localite;

                                                        // Vérifie si cette localité a déjà été affichée
                                                        if (in_array($localite, $localites_vues)) {
                                                            continue; // on saute si elle est déjà dans la liste
                                                        }

                                                        // Sinon, on l'affiche et on la marque comme vue
                                                        $localites_vues[] = $localite;
                                                    ?>
                                                        <option value="<?= htmlspecialchars($localite); ?>">
                                                            <?= htmlspecialchars($localite); ?>
                                                        </option>
                                                    <?php endforeach; ?>

                                                </select>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary" name="save">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                <?php if ($user->userHasPermission('Configuration_gestion_trajets')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link active text-break mb-0" role="tab"
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
                        <div class="card-header">
                            <div class="card-title">
                                Liste des escales
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header text-center" style="width:100%">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Depart</th>
                                            <th>Destination</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($liste as $listes): ?>
                                            <tr>
                                                <td><?= $listes->depart ?></td>
                                                <td><?= $listes->destination ?></td>
                                                <td class=" ">
                                                    <div class="dropup text-center">
                                                        <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                            &#8943; <!-- Trois points horizontaux -->
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item details-btn" href="#" data-bs-toggle="modal" data-bs-target="#detailsTrajetModal" 
                                                               data-depart="<?= htmlspecialchars($listes->depart, ENT_QUOTES) ?>" 
                                                               data-destination="<?= htmlspecialchars($listes->destination, ENT_QUOTES) ?>" 
                                                               data-escales="<?= htmlspecialchars($listes->escales_names, ENT_QUOTES) ?>">
                                                                👁️ Details
                                                            </a>
                                                            <a class="dropdown-item edit-btn" href="#" data-bs-toggle="modal" data-bs-target="#editTrajetModal" 
                                                               data-id="<?= $listes->idTrajet ?>" 
                                                               data-depart="<?= htmlspecialchars($listes->depart, ENT_QUOTES) ?>" 
                                                               data-destination="<?= htmlspecialchars($listes->destination, ENT_QUOTES) ?>" 
                                                               data-escales-ids="<?= htmlspecialchars(json_encode($listes->escales_ids), ENT_QUOTES) ?>">
                                                                ✏️ Modifier
                                                            </a>
                                                            <a class="dropdown-item text-danger delete-button"
                                                                href="<?= BASE_URL ?>/admin/Add_liste_trajets/delete/<?= $listes->idTrajet ?>">
                                                                🗑 Supprimer
                                                            </a>
                                                        </div>
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
            <!--end row-->
        </main>
        <!--end page main-->

        <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <!-- Modal Détails Trajet -->
        <div class="modal fade" id="detailsTrajetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title text-white">Détails du trajet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <h4 class="text-primary mb-3" id="detail_depart"></h4>
                        <div class="mb-3">
                            <i class="bx bx-down-arrow-alt fs-2 text-muted"></i>
                        </div>
                        <div id="detail_escales" class="mb-3 text-secondary fw-semibold">
                            <!-- Escales apparaîtront ici -->
                        </div>
                        <div class="mb-3" id="detail_arrow" style="display: none;">
                            <i class="bx bx-down-arrow-alt fs-2 text-muted"></i>
                        </div>
                        <h4 class="text-success mt-3" id="detail_destination"></h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Modifier Trajet -->
        <div class="modal fade" id="editTrajetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-dark">Modifier le trajet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <input type="hidden" name="idTrajet" id="edit_idTrajet">
                            <div class="mb-3">
                                <label class="form-label">Départ<span class="text-danger">*</span></label>
                                <select id="editDepart" name="depart" class="form-control">
                                    <?php foreach ($liste_agence as $liste_agences): 
                                          $localite = $liste_agences->localite; ?>
                                        <option value="<?= htmlspecialchars($localite); ?>">
                                            <?= htmlspecialchars($localite); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Escale</label>
                                <select id="editEscale" class="form-control multiple-select" multiple="multiple" placeholder="Choisissez un ou plusieurs escale" name="idEscale[]">
                                    <?php foreach ($listeEscale as $listeEscales): ?>
                                        <option value="<?= htmlspecialchars($listeEscales->id_escale); ?>">
                                            <?= htmlspecialchars($listeEscales->escales) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Destination<span class="text-danger">*</span></label>
                                <select id="editDestination" name="destination" class="form-control">
                                    <?php foreach ($liste_agence as $liste_agences): 
                                          $localite = $liste_agences->localite; ?>
                                        <option value="<?= htmlspecialchars($localite); ?>">
                                            <?= htmlspecialchars($localite); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary" name="update">Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--end wrapper-->


    <?php $this->view('admin/partials/foot') ?>
    <!-- Initialisation Select2 -->
    <script>
        $(document).ready(function() {
            // Initialisation de tous les champs Select2
            $('#choixAgence').select2({
                tags: true,
                placeholder: "Choisissez ou ajoutez une agence",
                allowClear: true,
                dropdownParent: $('#exampleDangerModal'), // ⬅️ Important dans un modal
                width: '100%'
            });
            $('#choixAgences').select2({
                tags: true,
                placeholder: "Choisissez ou ajoutez une agence",
                allowClear: true,
                dropdownParent: $('#exampleDangerModal'), // ⬅️ Important dans un modal
                width: '100%'
            });

            $('.multiple-select').select2({
                placeholder: "Choisissez un ou plusieurs escale",
                allowClear: true,
                dropdownParent: $('#exampleDangerModal'),
                width: '100%'
            });
            
            $('#editDepart').select2({ dropdownParent: $('#editTrajetModal'), width: '100%', tags: true });
            $('#editDestination').select2({ dropdownParent: $('#editTrajetModal'), width: '100%', tags: true });
            $('#editEscale').select2({
                placeholder: "Choisissez un ou plusieurs escale",
                allowClear: true,
                dropdownParent: $('#editTrajetModal'),
                width: '100%'
            });

            // Bouton de modification
            const editButtons = document.querySelectorAll(".edit-btn");
            editButtons.forEach(button => {
                button.addEventListener("click", function() {
                    document.getElementById("edit_idTrajet").value = this.dataset.id;
                    
                    // MàJ des selects simples
                    $('#editDepart').val(this.dataset.depart).trigger('change');
                    $('#editDestination').val(this.dataset.destination).trigger('change');
                    
                    // MàJ du select multiple
                    try {
                        const ids = JSON.parse(this.dataset.escalesIds);
                        $('#editEscale').val(ids).trigger('change');
                    } catch (e) {
                        $('#editEscale').val([]).trigger('change');
                    }
                });
            });

            // Détails du trajet
            const detailsButtons = document.querySelectorAll(".details-btn");
            detailsButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const depart = this.dataset.depart;
                    const destination = this.dataset.destination;
                    const escales = this.dataset.escales;

                    document.getElementById("detail_depart").textContent = "Départ : " + depart;
                    document.getElementById("detail_destination").textContent = "Arrivée : " + destination;
                    
                    const escalesContainer = document.getElementById("detail_escales");
                    const arrowDown = document.getElementById("detail_arrow");
                    
                    if (escales && escales.trim() !== "") {
                        // Remplacer les flèches textuelles par du HTML pour un meilleur affichage
                        const escalesHtml = escales.split(" ➔ ").join("<br><i class='bx bx-down-arrow-alt text-muted my-1'></i><br>");
                        escalesContainer.innerHTML = "<span class='badge bg-warning text-dark mb-2'>Escales</span><br><br>" + escalesHtml;
                        arrowDown.style.display = "block";
                    } else {
                        escalesContainer.innerHTML = "<span class='badge bg-light text-secondary'>Trajet direct (Aucune escale)</span>";
                        arrowDown.style.display = "none";
                    }
                });
            });

            // Suppression avec SweetAlert
            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const deleteUrl = this.getAttribute('href');

                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: "Toutes les escales liées à ce trajet seront également supprimées. Cette action est irréversible !",
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