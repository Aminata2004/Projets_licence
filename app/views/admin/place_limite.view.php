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
                    <div class="btn-group">

                        <a href="javascript:history.back()" class="btn btn-primary split-bg-primary"><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

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
                                        <a class="nav-link  text-break" role="tab"
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
                                        <a class="nav-link active text-break" role="tab"
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
                            <h5 class="mb-0 fw-bold"><i class="bx bx-chair me-2"></i>Limites des places</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Le nombre de place </p>
                                    <?php foreach ($liste_place as $liste_places):
                                    ?>
                                        <h4 class="my-1"><?= $liste_places->place_minumale ?></h4>
                                    <?php endforeach
                                    ?>
                                </div>
                                <div class=" add-button position-fixed  end-0 me-5  " data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop"
                                    data-place_minumale="<?= $liste_places->place_minumale ?>"
                                    data-id_place_minumale="<?= $liste_places->id_place_minumale ?>"><i
                                        class=' bx bx-message-square-edit fs-1'></i>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end row-->
        </main>
        <!--end page main-->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel">Modal title
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= BASE_URL ?>/Compagnies/edit1" method="post">
                            <div class="col-md-12">
                                <label for="bsValidation3" class="form-label">N° de place mumimale<span
                                        class="text-danger scale5 ms-2">*</span></label>
                                <input type="text" class="form-control" id="nameplace_minumale" name="place_minumale" required>
                                <input type="hidden" class="form-control" id="idplace_minumale" name="id_place_minumale">

                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="edit" class="btn btn-primary">Modifier</button>

                    </div>
                    </form>
                </div>
            </div>
        </div>
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
        $(document).ready(function() {
            // Lorsque le bouton "Ajouter" est cliqué
            $('.add-button').click(function() {
                // Récupérer les attributs de données du lien cliqué
                var place_minumale = $(this).data('place_minumale');
                var id_minumale = $(this).data('id_place_minumale');

                // Remplir le champ du modal avec les données
                $('#nameplace_minumale').val(place_minumale);
                $('#idplace_minumale').val(id_minumale)

                // Afficher le modal
                $('#staticBackdrop').modal('show');
            });

        });
    </script>
</body>

</html>