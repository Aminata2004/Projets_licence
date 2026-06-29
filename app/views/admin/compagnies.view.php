<?php $this->view('admin/partials/header') ?>
<?php $user = new Configuration($_SESSION['id_utilisateur']) ?>

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        <?php $this->view('admin/partials/navbar')
        ?>
        <!--end top header-->

        <!--start sidebar -->
        <?php $this->view('admin/partials/sidebar')
        ?>
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
                            <li class="breadcrumb-item active" aria-current="page">Compagnies</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="d-flex gap-2">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success d-flex align-items-center gap-2 shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#exampleDangerModal">
                            <i class="bx bx-plus-circle fs-5"></i> Ajouter
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleDangerModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content ">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white">Enregistrement des compagnies</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-dart">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="col-md-12">
                                                <label for="tel" class="form-label">Nom du compagnie
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                    value="" name="nom_compagnie"
                                                    placeholder="Nom du compagnie" required>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="bsValidation10"
                                                        class="form-label">Description<span
                                                            class="text-danger scale5 ms-2">*</span></label>
                                                    <input type="text" class="form-control" id="tel"

                                                        value="" name="libele" placeholder="Libele" required>

                                                </div>
                                                <div class="col-md-4">
                                                    <label for="bsValidation11" class="form-label">Slogant<span
                                                            class="text-danger scale5 ms-2">*</span></label>
                                                    <input type="text" class="form-control" id=""
                                                        value=""
                                                        name="slogant" placeholder="Slogant" required>

                                                </div>
                                                <div class="col-md-2">
                                                    <label for="bsValidation10"
                                                        class="form-label">Logo</label>
                                                    <input type="file" class="form-control" id="tel"

                                                        value="" name="logo">
                                                    <small id="messageErreur" class="text-danger"></small>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary" name="save">Enregistre</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:history.back()"
                            class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                            <i class="bx bx-left-arrow-alt fs-5"></i> Retour
                        </a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

                        <style>
        /* TransGest Premium Configuration Theme v2 - Orange & Dark Blue */
        .config-card {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-radius: 20px;
            background: #ffffff;
            overflow: hidden;
        }
        .config-card .card-header {
            background: #0f3b5e !important;
            border-bottom: none !important;
            border-radius: 20px 20px 0 0 !important;
            padding: 1.5rem 1.5rem;
        }
        .config-card .card-title, .config-card .card-header h5 {
            color: #ffffff !important;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            font-size: 1.15rem;
        }
        .config-card .card-header i {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.15);
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
        
        /* Typography */
        .text-primary {
            color: #ea580c !important;
        }
        
        /* Primary buttons (Orange) */
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
        
        /* Outline Primary Buttons (Orange Outline) */
        .btn-outline-primary {
            color: #ea580c !important;
            border-color: #ea580c !important;
            border-radius: 12px;
            font-weight: 600;
        }
        .btn-outline-primary:hover {
            background: #ea580c !important;
            color: white !important;
        }
        
        /* Secondary/Cancel buttons (Dark Blue) */
        .btn-secondary, .btn-light, .btn-info, .btn-outline-secondary {
            background: #0f172a !important; /* Dark Blue */
            color: white !important;
            border: none !important;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 8px 20px -5px rgba(15, 23, 42, 0.3) !important;
        }
        .btn-secondary:hover, .btn-light:hover, .btn-info:hover, .btn-outline-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -5px rgba(15, 23, 42, 0.4) !important;
            background: #1e293b !important;
            color: white !important;
        }
        
        /* Breadcrumb / Top right back button */
        .split-bg-primary {
            background: #0f172a !important;
            border: none !important;
            border-radius: 12px;
        }
        .split-bg-primary:hover {
            background: #1e293b !important;
        }
        
        /* Modals - Dark Blue Header */
        .modal-header {
            background: #0f172a !important;
            border-bottom: none !important;
        }
        .modal-header .modal-title {
            color: #ffffff !important;
        }
        .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
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

        
        /* Breadcrumb Styling */
        .page-breadcrumb .breadcrumb-title {
            color: #0f3b5e !important;
            font-weight: 800 !important;
            font-size: 1.4rem;
            border-right: 2px solid rgba(15, 59, 94, 0.2) !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .page-breadcrumb .breadcrumb-item a {
            color: #ea580c !important;
            font-weight: 600;
            background: rgba(245, 158, 11, 0.1);
            padding: 6px 10px;
            border-radius: 8px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
        }
        .page-breadcrumb .breadcrumb-item a:hover {
            background: #ea580c !important;
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(234, 88, 12, 0.2);
        }
        .page-breadcrumb .breadcrumb-item.active {
            color: #0f3b5e !important;
            font-weight: 700;
            background: rgba(15, 59, 94, 0.08);
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
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
                    <a class="nav-link active text-break" role="tab"
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
                            <h5 class="mb-0 fw-bold"><i class="bx bx-buildings me-2"></i>Liste des compagnies</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header text-center" style="width:100%">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th class="fw-semibold">Logo</th>
                                            <th class="fw-semibold">Nom compagnie</th>
                                            <th class="fw-semibold">Libellé</th>
                                            <th class="fw-semibold">Slogan</th>
                                            <th class="fw-semibold">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($liste as $listes): ?>
                                            <tr class="hover-shadow">
                                                <td class="text-center">
                                                    <?php if (!empty($listes->logo)): ?>
                                                        <img src="<?= BASE_URL ?>/images/logos/<?= htmlspecialchars($listes->logo) ?>" alt="Logo" style="width: 45px; height: 45px; object-fit: contain; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); background: #fff; padding: 2px;">
                                                    <?php else: ?>
                                                        <div style="width: 45px; height: 45px; background: rgba(245, 158, 11, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ea580c; margin: 0 auto;">
                                                            <i class="bx bx-bus fs-4"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="fw-semibold"><?= htmlspecialchars($listes->nom_compagnie) ?></td>
                                                <td><?= htmlspecialchars($listes->libele) ?></td>
                                                <td><?= htmlspecialchars($listes->slogant) ?></td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                                                            &#8943;
                                                        </a>
                                                        <ul class="dropdown-menu shadow-sm">
                                                            <li>
                                                                <a class="dropdown-item edit-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleDangerModal1"
                                                                    data-id_compagnie="<?= $listes->id_compagnie ?>"
                                                                    data-nom_compagnie="<?= htmlspecialchars($listes->nom_compagnie, ENT_QUOTES) ?>"
                                                                    data-libele="<?= htmlspecialchars($listes->libele, ENT_QUOTES) ?>"
                                                                    data-slogant="<?= htmlspecialchars($listes->slogant, ENT_QUOTES) ?>"
                                                                    data-logo="<?= !empty($listes->logo) ? BASE_URL . '/images/logos/' . htmlspecialchars($listes->logo, ENT_QUOTES) : '' ?>"
                                                                    href="#">
                                                                    ✏️ Modifier
                                                                </a>
                                                                <!-- <a class="dropdown-item edit-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleDangerModal1"
                                                                    data-id_compagnie="<?= $listes->id_compagnie ?>"
                                                                    data-nom_compagnie="<?= htmlspecialchars($listes->nom_compagnie, ENT_QUOTES) ?>"
                                                                    data-libele="<?= htmlspecialchars($listes->libele, ENT_QUOTES) ?>"
                                                                    data-slogant="<?= htmlspecialchars($listes->slogant, ENT_QUOTES) ?>"
                                                                    data-logo="/images/logos/<?= htmlspecialchars($listes->logo, ENT_QUOTES) ?>"
                                                                    href="#">
                                                                    ✏️ Modifier
                                                                </a> -->


                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger delete-button"
                                                                    href="<?= BASE_URL ?>/admin/Compagnies/delete/<?= $listes->id_compagnie ?>">
                                                                    🗑 Supprimer
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item text-success"
                                                                    href="<?= BASE_URL ?>/admin/Compagnies/impersonate/<?= $listes->id_compagnie ?>">
                                                                    🛠️ Assister
                                                                </a>
                                                            </li>
                                                        </ul>
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
    <div class="modal fade" id="exampleDangerModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Modification du compagnie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dart">
                    <form action="<?= BASE_URL ?>/admin/Compagnies/edit" method="post" enctype="multipart/form-data">

    <div class="row">
        <div class="col-md-6">
            <label class="form-label">Nom de la compagnie</label>
            <input type="text"
                   class="form-control"
                   name="nom_compagnie"
                   id="inputnomCompagnie"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Libellé</label>
            <input type="text"
                   class="form-control"
                   name="libele"
                   id="inputlibele"
                   required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label class="form-label">Slogan</label>
            <input type="text"
                   class="form-control"
                   name="slogant"
                   id="inputslogant"
                   required>
        </div>

        <div class="col-md-6">
           
            <img id="logoPreview"
                 src=""
                 alt="Logo"
                 width="120"

                 class="mb-2 border"
                 onerror="this.style.display='none'">

            <!-- Logo actuel (pour PHP) -->
            <input type="hidden" name="ancien_logo" id="ancienLogo">

            <label class="form-label mt-2">Changer le logo</label>
            <input type="file"
                   class="form-control"
                   name="logo"
                   accept="image/png, image/jpeg, image/webp">
        </div>
    </div>

    <!-- ID compagnie -->
    <input type="hidden" name="id_compagnie" id="inputidCompagnie">

    <div class="modal-footer mt-4">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            Annuler
        </button>
        <button type="submit" class="btn btn-primary" name="edit">
            Modifier
        </button>
    </div>

</form>

            </div>
        </div>
    </div>

    <?php $this->view('admin/partials/foot') ?>
    <script src="<?= BASE_URL ?>/mon_js/scrip_compagnie.js"></script>
    <script src="<?= BASE_URL ?>/mon_js/alert_delete.js"></script>
</body>

</html>