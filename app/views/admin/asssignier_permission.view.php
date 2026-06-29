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
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success d-flex align-items-center gap-2 shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#exampleDangerModal">
                            <i class="bx bx-plus-circle fs-5"></i> Ajouter
                        </button>
                        &nbsp;
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
                  <a class="nav-link active text-break mb-0" role="tab"
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
                <div class="col-12 col-lg-9 pt-4 pt-lg-0">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="store_details" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card config-card">
                                        <div class="card-header">
                                            <h5><i class="bx bx-lock-open me-2"></i> Assignation de permissions à : <span style="color:#ea580c"><?= htmlspecialchars($utilisateur->utilisateurs ?? '') ?></span></h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="container mt-4">

                                                <h3 class="text-center mb-4" style="display:none">
                                                    Assignation de permissions à : <span class="text-primary"><?= htmlspecialchars($utilisateur->utilisateurs ?? '') ?></span>
                                                </h3>
                                                <form method="post" action="#">

                                                    <?php
                                                    $groupes = [];
                                                    foreach ($allPermissions as $perm) {
                                                        $parts = explode('_', $perm->nom_permission, 2);
                                                        if (count($parts) == 2) {
                                                            $module = $parts[0];
                                                            $action = $parts[1];
                                                        } else {
                                                            $module = 'autres';
                                                            $action = $perm->nom_permission;
                                                        }
                                                        $groupes[$module][] = [
                                                            'id' => $perm->id_permision,
                                                            'action' => $action,
                                                            'full_name' => $perm->nom_permission
                                                        ];
                                                    }
                                                    ?>

                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="select_all_permissions">
                                                            <label class="form-check-label fw-bold" for="select_all_permissions">
                                                                Sélectionner/Désélectionner tout
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3">
                                                        <?php foreach ($groupes as $module => $permissions): ?>
                                                            <div class="col-12">
                                                                <div class="card permission-card h-100">
                                                                    <div class="card-header permission-module-header text-white">
                                                                        <?php
                                                                        $moduleIcons = [
                                                                            'Billets'       => 'bx bx-ticket',
                                                                            'Colis'         => 'bx bx-package',
                                                                            'Configuration' => 'bx bx-cog',
                                                                            'Programmation' => 'bx bx-calendar',
                                                                            'Rapport'       => 'bx bx-bar-chart-alt-2',
                                                                            'utilisateur'   => 'bx bx-user',
                                                                        ];
                                                                        $iconClass = $moduleIcons[$module] ?? 'bx bx-key';
                                                                        $moduleIndex = 'module_' . md5($module);
                                                                        ?>
                                                                        <span><i class="<?= $iconClass ?> me-2"></i><?= ucfirst($module) ?></span>
                                                                        <label class="module-select-all" for="<?= $moduleIndex ?>">
                                                                            <input class="form-check-input module-all-cb" type="checkbox"
                                                                                   id="<?= $moduleIndex ?>"
                                                                                   data-module="<?= $moduleIndex ?>">
                                                                            Tout sélectionner
                                                                        </label>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="d-flex flex-wrap gap-3">
                                                                            <?php
                                                                            // Permissions sensibles à cacher pour tous sauf SupAdmin
                                                                            $sensitive = [
                                                                                'matieres_action',
                                                                                'matieres_creation',
                                                                                'matieres_modif',
                                                                                'matieres_supp',
                                                                                'programme_activer ou réactiver',
                                                                                'programme_apercu',
                                                                                'programme_création',
                                                                                'programme_modification'
                                                                            ];
                                                                            ?>

                                                                            <?php foreach ($permissions as $perm):
                                                                                // Filtre si l'utilisateur n'est pas SupAdmin
                                                                                if (($utilisateur->droit ?? '') !== 'SupAdmin' && in_array($perm['full_name'], $sensitive)) {
                                                                                    continue;
                                                                                }

                                                                                $permId = $perm['id'];
                                                                                $permAction = ucfirst(str_replace('_', ' ', $perm['action']));
                                                                                $checked = in_array($permId, $userPermissions) ? 'checked' : '';
                                                                                $inputId = "perm_{$permId}";
                                                                            ?>
                                                                                <div class="form-check me-4 mb-2">
                                                                                    <input class="form-check-input permission-checkbox"
                                                                                           type="checkbox"
                                                                                           name="permissions[]"
                                                                                           value="<?= $permId ?>"
                                                                                           id="<?= $inputId ?>"
                                                                                           data-module="<?= $moduleIndex ?>"
                                                                                           <?= $checked ?>>
                                                                                    <label class="form-check-label" for="<?= $inputId ?>">
                                                                                        <?= $permAction ?>
                                                                                    </label>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <div class="text-end mt-4">
                                                        <button type="submit" class="btn btn-transgest px-5"><i class="bx bx-check me-2"></i>Assigner les permissions</button>
                                                    </div>
                                                </form>

                                            </div>
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
        document.addEventListener("DOMContentLoaded", function() {
            const globalSelectAll = document.getElementById("select_all_permissions");
            const allPermCb = document.querySelectorAll(".permission-checkbox");

            // Sync la case d'un module selon ses enfants
            function syncModuleCb(moduleId) {
                const moduleCbs = document.querySelectorAll(`.permission-checkbox[data-module="${moduleId}"]`);
                const moduleAllCb = document.querySelector(`.module-all-cb[data-module="${moduleId}"]`);
                if (!moduleAllCb) return;
                const total   = moduleCbs.length;
                const checked = [...moduleCbs].filter(c => c.checked).length;
                moduleAllCb.checked       = (checked === total);
                moduleAllCb.indeterminate = (checked > 0 && checked < total);
            }

            // Sync la case globale
            function syncGlobal() {
                const total   = allPermCb.length;
                const checked = [...allPermCb].filter(c => c.checked).length;
                globalSelectAll.checked       = (checked === total);
                globalSelectAll.indeterminate = (checked > 0 && checked < total);
            }

            // 1. Case GLOBALE
            globalSelectAll.addEventListener("change", function() {
                allPermCb.forEach(cb => cb.checked = this.checked);
                document.querySelectorAll('.module-all-cb').forEach(cb => {
                    cb.checked = this.checked;
                    cb.indeterminate = false;
                });
            });

            // 2. Case de MODULE
            document.querySelectorAll('.module-all-cb').forEach(moduleAllCb => {
                moduleAllCb.addEventListener("change", function() {
                    const moduleId = this.dataset.module;
                    document.querySelectorAll(`.permission-checkbox[data-module="${moduleId}"]`)
                        .forEach(cb => cb.checked = this.checked);
                    this.indeterminate = false;
                    syncGlobal();
                });
            });

            // 3. Case INDIVIDUELLE
            allPermCb.forEach(cb => {
                cb.addEventListener("change", function() {
                    syncModuleCb(this.dataset.module);
                    syncGlobal();
                });
            });
        });
    </script>

</body>

</html>