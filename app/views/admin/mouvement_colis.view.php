<?php $this->view('admin/partials/headers') ?>
<?php $this->view('admin/helpers') ?>

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
            <!-- Breadcrumb -->
            <div class="page-breadcrumb d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3">
                <div class="breadcrumb-title pe-3 text-primary"><i class="bx bx-package me-1"></i> G-colis</div>
                <div class="ps-3 flex-grow-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Mouvement des colis</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto mt-2 mt-sm-0">
                    <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges" class="btn btn-sm btn-primary rounded-pill shadow-sm me-2">
                        <i class="bx bx-list-ul me-1"></i> Liste des colis
                    </a>
                    <a href="javascript:history.back()" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                        <i class="bx bx-left-arrow-alt me-1"></i> Retour
                    </a>
                </div>
            </div>
            <!-- /Breadcrumb -->

            <?php
            $totalAttente = count($liste_colis);
            $totalRecu = count($liste_colis_recue);
            $totalLivre = count($liste_colis_livre);
            ?>

            <!-- Stat cards -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bx bx-time-five"></i>
                            </div>
                            <div class="ms-3">
                                <div class="text-muted small">Colis en attente</div>
                                <div class="fw-bold fs-4 lh-1"><?= $totalAttente ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="bx bx-inbox"></i>
                            </div>
                            <div class="ms-3">
                                <div class="text-muted small">Colis reçus</div>
                                <div class="fw-bold fs-4 lh-1"><?= $totalRecu ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="bx bx-check-shield"></i>
                            </div>
                            <div class="ms-3">
                                <div class="text-muted small">Colis livrés</div>
                                <div class="fw-bold fs-4 lh-1"><?= $totalLivre ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="col-xl-12 mx-auto">
                        <div class="card border-0 shadow-sm rounded">
                            <div class="card-body">

                                <!-- Tabs -->
                                <ul class="nav nav-pills mb-4 justify-content-start flex-wrap" role="tablist">
                                    <li class="nav-item me-2 mb-2" role="presentation">
                                        <a class="nav-link active d-flex align-items-center px-3 py-2" data-bs-toggle="pill" href="#info-pills-home" role="tab">
                                            <i class='bx bx-time-five me-2'></i> Colis en attente
                                            <span class="badge rounded-pill bg-warning text-dark ms-2"><?= $totalAttente ?></span>
                                        </a>
                                    </li>
                                    <li class="nav-item me-2 mb-2" role="presentation">
                                        <a class="nav-link d-flex align-items-center px-3 py-2" data-bs-toggle="pill" href="#info-pills-profile" role="tab">
                                            <i class='bx bx-inbox me-2'></i> Colis reçu
                                            <span class="badge rounded-pill bg-success ms-2"><?= $totalRecu ?></span>
                                        </a>
                                    </li>
                                    <li class="nav-item me-2 mb-2" role="presentation">
                                        <a class="nav-link d-flex align-items-center px-3 py-2" data-bs-toggle="pill" href="#info-pills-contact" role="tab">
                                            <i class='bx bx-check-shield me-2'></i> Colis livré
                                            <span class="badge rounded-pill bg-info ms-2"><?= $totalLivre ?></span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab Contents -->
                                <div class="tab-content" id="pills-tabContent">

                                    <!-- Colis en attente -->
                                    <div class="tab-pane fade show active" id="info-pills-home" role="tabpanel">
                                        <form action="" method="post">
                                            <div class="d-flex justify-content-end mb-3">
                                                <div class="w-30 position-relative">
                                                    <div class="position-absolute top-50 translate-middle-y ps-3">
                                                        <i class="bi bi-search text-secondary"></i>
                                                    </div>
                                                    <input class="form-control ps-5 rounded-pill search-input" type="text" data-target="table-attente" placeholder="Rechercher un colis...">
                                                </div>
                                            </div>
                                            <div class="table-responsive mb-3">
                                                <table id="table-attente" class="table table-striped table-hover align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th><input type="checkbox" id="selectAll"></th>
                                                            <th>Nom colis</th>
                                                            <th>Nature</th>
                                                            <th>Valeur</th>
                                                            <th>Frais de transaction</th>
                                                            <th>Destination</th>
                                                            <th>Code colis</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($liste_colis as $c): ?>
                                                            <tr>
                                                                <td><input type="checkbox" name="selected_colis[]" value="<?= (int)$c['id_colis'] ?>" class="form-check-input checkbox-car"></td>
                                                                <td class="fw-medium"><?= htmlspecialchars($c['nom_colis']) ?></td>
                                                                <td><?= htmlspecialchars($c['nature']) ?></td>
                                                                <td><?= number_format($c['valeur'], 0, ',', ' ') ?> FCFA</td>
                                                                <td><?= number_format($c['fraix_transaction'], 0, ',', ' ') ?> FCFA</td>
                                                                <td><?= htmlspecialchars($c['destination']) ?></td>
                                                                <td><?= htmlspecialchars($c['code_colis']) ?></td>
                                                                <td><?= afficherBadgeStatus($c['status']) ?></td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown">
                                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                            <li>
                                                                                <a class="dropdown-item details-colis-btn" href="#"
                                                                                    data-bs-toggle="modal" data-bs-target="#detailsColisModal"
                                                                                    data-nom="<?= htmlspecialchars($c['nom_colis'] ?? '') ?>"
                                                                                    data-nature="<?= htmlspecialchars($c['nature'] ?? '') ?>"
                                                                                    data-valeur="<?= htmlspecialchars(number_format($c['valeur'], 0, ',', ' ')) ?>"
                                                                                    data-frais="<?= htmlspecialchars(number_format($c['fraix_transaction'], 0, ',', ' ')) ?>"
                                                                                    data-lieu-label="Destination"
                                                                                    data-lieu="<?= htmlspecialchars($c['destination'] ?? '') ?>"
                                                                                    data-code="<?= htmlspecialchars($c['code_colis'] ?? '') ?>"
                                                                                    data-status="<?= htmlspecialchars($c['status'] ?? '') ?>"
                                                                                    data-status-html="<?= htmlspecialchars(afficherBadgeStatus($c['status'] ?? '')) ?>"
                                                                                    data-date="<?= htmlspecialchars($c['date_enregistrement'] ?? '') ?>"
                                                                                    data-expediteur="<?= htmlspecialchars($c['expediteur'] ?? '') ?>"
                                                                                    data-numero-exp="<?= htmlspecialchars($c['numero_exp'] ?? '') ?>"
                                                                                    data-email-exp="<?= htmlspecialchars($c['email_exp'] ?? '') ?>"
                                                                                    data-destinataire="<?= htmlspecialchars($c['destinataire'] ?? '') ?>"
                                                                                    data-numero-dest="<?= htmlspecialchars($c['numero_dest'] ?? '') ?>"
                                                                                    data-email-dest="<?= htmlspecialchars($c['email_dest'] ?? '') ?>">
                                                                                    <i class="bx bx-show-alt me-1"></i> Détails
                                                                                </a>
                                                                            </li>
                                                                            <li><a class="dropdown-item" href="#"><i class="bx bx-block me-1"></i> Désactiver</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                                <?php if (empty($liste_colis)): ?>
                                                    <div class="text-center text-muted py-5">
                                                        <i class="bx bx-package fs-1 d-block mb-2"></i>
                                                        Aucun colis en attente pour le moment.
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (!empty($liste_colis)): ?>
                                                <div class="d-flex justify-content-end mt-3">
                                                    <button class="btn btn-success rounded-pill px-4" type="submit" name="reception">
                                                        <i class="bx bx-check me-1"></i> Réception
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </form>
                                    </div>

                                    <!-- Colis reçu -->
                                    <div class="tab-pane fade" id="info-pills-profile" role="tabpanel">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="w-30 position-relative">
                                                <div class="position-absolute top-50 translate-middle-y ps-3">
                                                    <i class="bi bi-search text-secondary"></i>
                                                </div>
                                                <input class="form-control ps-5 rounded-pill search-input" type="text" data-target="table-recu" placeholder="Rechercher un colis...">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="table-recu" class="table table-striped table-hover align-middle text-center mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Nom colis</th>
                                                        <th>Nature</th>
                                                        <th>Valeur</th>
                                                        <th>Frais de transaction</th>
                                                        <th>Provenance</th>
                                                        <th>Code colis</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($liste_colis_recue as $colis): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($colis['nom_colis']) ?></td>
                                                            <td><?= htmlspecialchars($colis['nature']) ?></td>
                                                            <td><?= number_format($colis['valeur'], 0, ',', ' ') ?> FCFA</td>
                                                            <td><?= number_format($colis['fraix_transaction'], 0, ',', ' ') ?> FCFA</td>
                                                            <td><?= htmlspecialchars($colis['provient_de']) ?></td>
                                                            <td><?= htmlspecialchars($colis['code_colis']) ?></td>
                                                            <td><?= afficherBadgeStatus($colis['status']) ?></td>
                                            <td>
                                                                <div class="dropdown">
                                                                    <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown">
                                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                        <li>
                                                                            <a class="dropdown-item details-colis-btn" href="#"
                                                                                data-bs-toggle="modal" data-bs-target="#detailsColisModal"
                                                                                data-nom="<?= htmlspecialchars($colis['nom_colis'] ?? '') ?>"
                                                                                data-nature="<?= htmlspecialchars($colis['nature'] ?? '') ?>"
                                                                                data-valeur="<?= htmlspecialchars(number_format($colis['valeur'], 0, ',', ' ')) ?>"
                                                                                data-frais="<?= htmlspecialchars(number_format($colis['fraix_transaction'], 0, ',', ' ')) ?>"
                                                                                data-lieu-label="Provenance"
                                                                                data-lieu="<?= htmlspecialchars($colis['provient_de'] ?? '') ?>"
                                                                                data-code="<?= htmlspecialchars($colis['code_colis'] ?? '') ?>"
                                                                                data-status="<?= htmlspecialchars($colis['status'] ?? '') ?>"
                                                                                data-status-html="<?= htmlspecialchars(afficherBadgeStatus($colis['status'] ?? '')) ?>"
                                                                                data-date="<?= htmlspecialchars($colis['date_enregistrement'] ?? '') ?>"
                                                                                data-expediteur="<?= htmlspecialchars($colis['expediteur'] ?? '') ?>"
                                                                                data-numero-exp="<?= htmlspecialchars($colis['numero_exp'] ?? '') ?>"
                                                                                data-email-exp="<?= htmlspecialchars($colis['email_exp'] ?? '') ?>"
                                                                                data-destinataire="<?= htmlspecialchars($colis['destinataire'] ?? '') ?>"
                                                                                data-numero-dest="<?= htmlspecialchars($colis['numero_dest'] ?? '') ?>"
                                                                                data-email-dest="<?= htmlspecialchars($colis['email_dest'] ?? '') ?>">
                                                                                <i class="bx bx-show-alt me-1"></i> Détails
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                href="<?= BASE_URL ?>/admin/Livraison_colis?code=<?= urlencode($colis['code_colis']) ?>">
                                                                                <i class="bx bx-truck me-2"></i>Livrer
                                                                            </a>
                                                                        </li>
                                                                        <?php
                                                                        $msgRecu = "Bonjour " . ($colis['destinataire'] ?? '') . ", votre colis (code "
                                                                            . ($colis['code_colis'] ?? '') . ") est arrivé à " . ($colis['destination'] ?? '')
                                                                            . ", gare n° " . ($colis['numero_gare_retrait'] ?? '') . ", et vous y attend."
                                                                            . " Présentez ce message ou le code du colis pour le retrait.";
                                                                        $lienWhatsappRecu = whatsapp_link($colis['whatsapp_dest'] ?? $colis['numero_dest'] ?? '', $msgRecu);
                                                                        ?>
                                                                        <?php if ($lienWhatsappRecu): ?>
                                                                            <li>
                                                                                <a class="dropdown-item" href="<?= htmlspecialchars($lienWhatsappRecu) ?>" target="_blank" rel="noopener">
                                                                                    <i class="bx bxl-whatsapp me-2 text-success"></i>Notifier par WhatsApp
                                                                                </a>
                                                                            </li>
                                                                        <?php endif; ?>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                            <?php if (empty($liste_colis_recue)): ?>
                                                <div class="text-center text-muted py-5">
                                                    <i class="bx bx-inbox fs-1 d-block mb-2"></i>
                                                    Aucun colis reçu pour le moment.
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Colis livré -->
                                    <div class="tab-pane fade" id="info-pills-contact" role="tabpanel">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="w-30 position-relative">
                                                <div class="position-absolute top-50 translate-middle-y ps-3">
                                                    <i class="bi bi-search text-secondary"></i>
                                                </div>
                                                <input class="form-control ps-5 rounded-pill search-input" type="text" data-target="table-livre" placeholder="Rechercher un colis...">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="table-livre" class="table table-striped table-hover align-middle text-center mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Nom colis</th>
                                                        <th>Nature</th>
                                                        <th>Valeur</th>
                                                        <th>Frais de transaction</th>
                                                        <th>Destination</th>
                                                        <th>Date de livraison</th>
                                                        <th>Code colis</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($liste_colis_livre as $colis_livre): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($colis_livre['nom_colis']) ?></td>
                                                            <td><?= htmlspecialchars($colis_livre['nature']) ?></td>
                                                            <td><?= number_format($colis_livre['valeur'], 0, ',', ' ') ?> FCFA</td>
                                                            <td><?= number_format($colis_livre['fraix_transaction'], 0, ',', ' ') ?> FCFA</td>
                                                            <td><?= htmlspecialchars($colis_livre['destination']) ?></td>
                                                            <td><?= htmlspecialchars($colis_livre['date_livraison']) ?></td>
                                                            <td><?= htmlspecialchars($colis_livre['code_colis']) ?></td>
                                                            <td><?= afficherBadgeStatus($colis_livre['status']) ?></td>
                                                            <td>
                                                                <?php
                                                                // Message de confirmation de remise : envoyé à l'EXPÉDITEUR (preuve que son colis est bien arrivé),
                                                                // pas au destinataire (lui est notifié plus tôt, à l'étape "Colis reçu").
                                                                $msgLivre = "Bonjour " . ($colis_livre['expediteur'] ?? '') . ", votre colis (code "
                                                                    . ($colis_livre['code_colis'] ?? '') . ") a bien été remis à son destinataire le "
                                                                    . ($colis_livre['date_livraison'] ?? '') . ". Merci de votre confiance.";
                                                                $lienWhatsappLivre = whatsapp_link($colis_livre['whatsapp_exp'] ?? $colis_livre['numero_exp'] ?? '', $msgLivre);
                                                                ?>
                                                                <?php if ($lienWhatsappLivre): ?>
                                                                    <a href="<?= htmlspecialchars($lienWhatsappLivre) ?>" target="_blank" rel="noopener"
                                                                        class="btn btn-sm btn-outline-success" title="Confirmer la remise par WhatsApp (à l'expéditeur)">
                                                                        <i class="bx bxl-whatsapp"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                            <?php if (empty($liste_colis_livre)): ?>
                                                <div class="text-center text-muted py-5">
                                                    <i class="bx bx-check-shield fs-1 d-block mb-2"></i>
                                                    Aucun colis livré pour le moment.
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div> <!-- tab-content -->

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

        <!-- Modal Détails Colis -->
        <div class="modal fade" id="detailsColisModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow colis-modal">
                    <div class="modal-header colis-modal-header text-white">
                        <div>
                            <h5 class="modal-title mb-0"><i class="bx bx-package me-2"></i><span id="dc_nom"></span></h5>
                            <small class="opacity-75">Code colis : <span id="dc_code"></span></small>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-4 text-center">
                            <div class="col-4">
                                <div class="colis-stat-icon bg-success bg-opacity-10 text-success"><i class="bx bx-money"></i></div>
                                <div class="small text-muted">Valeur</div>
                                <div class="fw-bold"><span id="dc_valeur"></span> FCFA</div>
                            </div>
                            <div class="col-4">
                                <div class="colis-stat-icon bg-warning bg-opacity-10 text-warning"><i class="bx bx-receipt"></i></div>
                                <div class="small text-muted">Frais</div>
                                <div class="fw-bold"><span id="dc_frais"></span> FCFA</div>
                            </div>
                            <div class="col-4">
                                <div class="colis-stat-icon bg-info bg-opacity-10 text-info"><i class="bx bx-check-shield"></i></div>
                                <div class="small text-muted">Statut</div>
                                <div id="dc_status_badge"></div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="colis-panel">
                                    <div class="colis-panel-title"><i class="bx bx-upload me-1"></i> Expéditeur</div>
                                    <div class="colis-info-row"><i class="bx bx-user"></i><span id="dc_expediteur"></span></div>
                                    <div class="colis-info-row"><i class="bx bx-phone"></i><span id="dc_numero_exp"></span></div>
                                    <div class="colis-info-row"><i class="bx bx-envelope"></i><span id="dc_email_exp"></span></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="colis-panel">
                                    <div class="colis-panel-title"><i class="bx bx-download me-1"></i> Destinataire</div>
                                    <div class="colis-info-row"><i class="bx bx-user"></i><span id="dc_destinataire"></span></div>
                                    <div class="colis-info-row"><i class="bx bx-phone"></i><span id="dc_numero_dest"></span></div>
                                    <div class="colis-info-row"><i class="bx bx-envelope"></i><span id="dc_email_dest"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="colis-panel mt-3">
                            <div class="colis-panel-title"><i class="bx bx-box me-1"></i> Colis</div>
                            <div class="colis-info-row"><i class="bx bx-tag"></i> Nature : <span class="ms-1" id="dc_nature"></span></div>
                            <div class="colis-info-row"><i class="bx bx-map-pin"></i> <span id="dc_lieu_label">Destination</span> : <span class="ms-1" id="dc_lieu"></span></div>
                            <div class="colis-info-row"><i class="bx bx-calendar"></i> Enregistré le <span class="ms-1" id="dc_date"></span></div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end wrapper-->
    <?php $this->view('admin/partials/foot') ?>

    <style>
        .colis-modal-header {
            background: linear-gradient(135deg, #0f3b5e, #1d6fa5);
        }

        .colis-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin: 0 auto 6px;
        }

        .colis-panel {
            background: #f8f9fb;
            border-radius: 10px;
            padding: 14px 16px;
            height: 100%;
        }

        .colis-panel-title {
            font-weight: 600;
            color: #0f3b5e;
            margin-bottom: 10px;
            font-size: .95rem;
        }

        .colis-info-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 0;
            font-size: .92rem;
        }

        .colis-info-row i {
            color: #6c757d;
            font-size: 1rem;
            width: 18px;
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .nav-pills .nav-link {
            border-radius: 50px;
            background-color: #f4f6f9;
            color: #495057;
        }

        .nav-pills .nav-link.active {
            box-shadow: 0 2px 6px rgba(0, 0, 0, .12);
        }
    </style>

    <!-- JS -->
    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.checkbox-car').forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        });

        document.querySelectorAll('.details-colis-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('dc_expediteur').textContent = this.dataset.expediteur || '-';
                document.getElementById('dc_numero_exp').textContent = this.dataset.numeroExp || '-';
                document.getElementById('dc_email_exp').textContent = this.dataset.emailExp || '-';
                document.getElementById('dc_destinataire').textContent = this.dataset.destinataire || '-';
                document.getElementById('dc_numero_dest').textContent = this.dataset.numeroDest || '-';
                document.getElementById('dc_email_dest').textContent = this.dataset.emailDest || '-';
                document.getElementById('dc_nom').textContent = this.dataset.nom || '-';
                document.getElementById('dc_nature').textContent = this.dataset.nature || '-';
                document.getElementById('dc_valeur').textContent = this.dataset.valeur || '0';
                document.getElementById('dc_frais').textContent = this.dataset.frais || '0';
                document.getElementById('dc_lieu_label').textContent = this.dataset.lieuLabel || 'Destination';
                document.getElementById('dc_lieu').textContent = this.dataset.lieu || '-';
                document.getElementById('dc_code').textContent = this.dataset.code || '-';
                document.getElementById('dc_date').textContent = this.dataset.date || '-';
                document.getElementById('dc_status_badge').innerHTML = this.dataset.statusHtml || '-';
            });
        });

        $(document).ready(function() {
            const tables = {};
            ['table-attente', 'table-recu', 'table-livre'].forEach(function(id) {
                if ($('#' + id).find('tbody tr').length > 0) {
                    tables[id] = $('#' + id).DataTable({
                        dom: 'lrtip',
                        order: [],
                        language: {
                            emptyTable: "Aucune donnée disponible",
                            zeroRecords: "Aucun résultat trouvé",
                            lengthMenu: "Afficher _MENU_ lignes",
                            info: "Affichage de _START_ à _END_ sur _TOTAL_ lignes",
                            infoEmpty: "0 ligne",
                            search: "Rechercher :",
                            paginate: {
                                previous: "Précédent",
                                next: "Suivant"
                            }
                        }
                    });
                }
            });

            // Recherche personnalisée reliée aux DataTables
            $('.search-input').on('keyup', function() {
                const target = $(this).data('target');
                if (tables[target]) {
                    tables[target].search(this.value).draw();
                }
            });

            // Recalcule la largeur des colonnes quand un onglet caché devient visible
            const tableByTab = {
                '#info-pills-home': 'table-attente',
                '#info-pills-profile': 'table-recu',
                '#info-pills-contact': 'table-livre'
            };
            $('a[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                const id = tableByTab[$(e.target).attr('href')];
                if (id && tables[id]) {
                    tables[id].columns.adjust();
                }
            });
        });
    </script>

</body>

</html>
