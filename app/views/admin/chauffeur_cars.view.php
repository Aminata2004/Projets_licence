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
                            <li class="breadcrumb-item active" aria-current="page">Chauffeurs</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-transgest" data-bs-toggle="modal" data-bs-target="#addChauffeurModal">
                            + Ajouter
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="addChauffeurModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b, #ea580c);">
                                        <h5 class="modal-title text-white">Enregistrement des chauffeurs</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body text-dark">
                                        <form action="" method="post">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="nomPrenom" class="form-label">Nom & Prénom</label>
                                                    <input type="text" class="form-control" id="nomPrenom" name="nom_prenom" placeholder="Nom & prénom" required autocomplete="off">
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="numero" class="form-label">Numéro</label>
                                                    <input type="text" class="form-control" id="numero" name="numero" placeholder="Numéro" required autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <label for="selectCar" class="form-label">Car</label>
                                                <select class="form-select" name="id_car" id="selectCar" required>
                                                    <option selected disabled>Choisissez le car</option>
                                                    <?php foreach ($listeCar as $listeCars): ?>
                                                        <option value="<?= $listeCars->id_car ?>">Car : <?= $listeCars->numero_car ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary" name="save">Enregistrer</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        &nbsp;
                        <a href="javascript:history.back()"
                            class="btn btn-transgest d-flex align-items-center gap-2">
                            <i class="bx bx-left-arrow-alt fs-5"></i> Retour
                        </a>
                    </div>
                </div>

            </div>
            <!--end breadcrumb-->

                        <style>
        /* TransGest Premium Configuration Theme v2 */
        .config-card {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-radius: 20px;
            background: #ffffff;
            overflow: hidden;
        }
        .config-card .card-header {
            background: #ffffff !important;
            border-bottom: 1px solid #f1f5f9 !important;
            border-radius: 20px 20px 0 0 !important;
            padding: 1.5rem 1.5rem;
        }
        .config-card .card-title, .config-card .card-header h5 {
            color: #0f172a !important;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            font-size: 1.15rem;
        }
        .config-card .card-header i {
            color: #ea580c !important;
            background: rgba(245, 158, 11, 0.1);
            padding: 8px;
            border-radius: 10px;
            margin-right: 12px !important;
        }
        .vertical-tabs-custom .nav-link {
            border-radius: 12px;
            margin-bottom: 8px;
            color: #475569;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 12px 15px;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
        }
        .vertical-tabs-custom .nav-link:hover {
            background: rgba(245, 158, 11, 0.08);
            color: #ea580c;
            transform: translateX(4px);
        }
        .vertical-tabs-custom .nav-link.active {
            background: linear-gradient(135deg, #f59e0b, #ea580c) !important;
            color: white !important;
            border: none !important;
            box-shadow: 0 8px 20px -5px rgba(234, 88, 12, 0.4);
        }
        .vertical-tabs-custom .nav-link i {
            font-size: 1.2rem;
            margin-right: 10px;
            background: transparent !important;
            padding: 0 !important;
            color: inherit !important;
        }
        
        /* Overrides Bootstrap Blue to TransGest Orange */
        .bg-primary {
            background: linear-gradient(135deg, #f59e0b, #ea580c) !important;
        }
        .text-primary {
            color: #ea580c !important;
        }
        .btn-primary, .btn-transgest, .btn-success {
            background: linear-gradient(135deg, #f59e0b, #ea580c) !important;
            color: white !important;
            border: none !important;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 8px 20px -5px rgba(234, 88, 12, 0.4) !important;
        }
        .btn-primary:hover, .btn-transgest:hover, .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -5px rgba(234, 88, 12, 0.5) !important;
            color: white !important;
        }
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
            background: linear-gradient(135deg, #f59e0b, #ea580c) !important;
            color: white !important;
            box-shadow: 0 8px 20px -5px rgba(234, 88, 12, 0.4);
        }
        .nav-pills .nav-link {
            color: #475569;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .nav-pills .nav-link:hover {
            background: rgba(245, 158, 11, 0.08);
            color: #ea580c;
        }
        .page-item.active .page-link {
            background-color: #ea580c !important;
            border-color: #ea580c !important;
        }
        
        /* Table Styling */
        .table-custom-header thead th, .table-primary th {
            background: rgba(245, 158, 11, 0.05) !important;
            color: #0f172a !important;
            font-weight: 600;
            border-bottom: 2px solid rgba(245, 158, 11, 0.1) !important;
            padding: 1rem;
        }
        .table-hover-effect tbody tr {
            transition: all 0.2s;
        }
        .table-hover-effect tbody tr:hover {
            background: rgba(245, 158, 11, 0.04) !important;
            transform: scale(1.002);
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            border-radius: 8px;
        }
        .table-light {
            --bs-table-bg: rgba(245, 158, 11, 0.02) !important;
        }
      </style>

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
                                        <a class="nav-link  text-break" role="tab"
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
                                        <a class="nav-link active  text-break" role="tab"
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
                    <?php $this->view("admin/set_flash") ?>
                    <div class="card config-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="bx bx-car me-2"></i>Liste des chauffeurs</h5>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills nav-pills-primary mb-3" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link " href="<?= BASE_URL ?>/admin/Cars_chauffeurs" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Cars</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" href="<?= BASE_URL ?>/admin/Chauffeurs_cars" role="tab" aria-selected="false">
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
                                        <table id="example" class="table table-hover align-middle table-hover-effect">
                                            <thead class="table-primary text-center">
                                                <tr>
                                                    <th class="fw-semibold">Nom & prénom</th>
                                                    <th class="fw-semibold">Numéro</th>
                                                    <th class="fw-semibold">Numéro du car</th>
                                                    <th class="fw-semibold">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php foreach ($listeChaufeur as $listeChaufeurs) : ?>
                                                    <tr>
                                                        <td><?= $listeChaufeurs->nom_prenom ?></td>
                                                        <td><?= $listeChaufeurs->numero ?></td>
                                                        <td><?= $listeChaufeurs->numero_car ?></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    &#8943;
                                                                </a>
                                                                <ul class="dropdown-menu shadow-sm">
                                                                    <li>
                                                                        <a class="dropdown-item edit-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editChauffeurModal"
                                                                            data-id="<?= $listeChaufeurs->id_chauffeur ?>"
                                                                            data-nom="<?= htmlspecialchars($listeChaufeurs->nom_prenom, ENT_QUOTES) ?>"
                                                                            data-numero="<?= htmlspecialchars($listeChaufeurs->numero, ENT_QUOTES) ?>"
                                                                            data-car="<?= htmlspecialchars($listeChaufeurs->numero_car, ENT_QUOTES) ?>"
                                                                            href="#">
                                                                            ✏️ Modifier
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger delete-button"
                                                                            href="<?= BASE_URL ?>/admin/Chauffeurs_cars/delete/<?= $listeChaufeurs->id_chauffeur ?>">
                                                                            🗑 Supprimer
                                                                        </a>
                                                                    </li>
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

        <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->


    </div>
    <!--end wrapper-->
    <!-- model de modification du chauffeur -->

    <!-- Modal -->
    <div class="modal fade" id="editChauffeurModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= BASE_URL ?>/admin/Chauffeurs_cars/update" method="post">
                    <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b, #ea580c);">
                        <h5 class="modal-title text-white">Modifier le chauffeur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id_chauffeur" id="edit_chauffeur_id">

                        <div class="mb-3">
                            <label for="edit_nom" class="form-label">Nom & Prénom</label>
                            <input type="text" class="form-control" name="nom_prenom" id="edit_nom">
                        </div>

                        <div class="mb-3">
                            <label for="edit_numero" class="form-label">Numéro</label>
                            <input type="text" class="form-control" name="numero" id="edit_numero">
                        </div>

                        <div class="mb-3">
                            <label for="edit_car" class="form-label">Numéro du véhicule</label>
                            <input type="text" class="form-control" name="numero_car" id="edit_car">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $this->view('admin/partials/foot') ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButtons = document.querySelectorAll(".edit-btn");

            editButtons.forEach(button => {
                button.addEventListener("click", function() {
                    document.getElementById("edit_chauffeur_id").value = this.dataset.id;
                    document.getElementById("edit_nom").value = this.dataset.nom;
                    document.getElementById("edit_numero").value = this.dataset.numero;
                    document.getElementById("edit_car").value = this.dataset.car;
                });
            });
        });



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