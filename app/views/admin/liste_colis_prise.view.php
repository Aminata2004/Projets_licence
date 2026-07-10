<?php $this->view('admin/partials/headers') ?>

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
        <main class="page-content ">
            <!--breadcrumb-->
            <!-- Breadcrumb -->

            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3 text-primary">
                    <i class="bx bx-package me-1"></i> G-colis
                </div>
                <div class="ps-3 flex-grow-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Liste des colis</li>
                        </ol>
                    </nav>
                </div>

                <div class="ms-auto mt-2 mt-sm-0 d-flex gap-2">
                    <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges/ajouter_colis/"
                        class="btn btn-sm btn-success rounded-pill shadow-sm">
                        <i class="bx bx-plus me-1"></i> Ajouter
                    </a>
                    <a href="javascript:history.back()"
                        class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                        <i class="bx bx-left-arrow-alt"></i> Retour
                    </a>
                </div>
            </div>

            <!-- End Breadcrumb -->

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bx bx-list-ul me-1"></i> Liste des colis enregistrés
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example"
                            class="table table-hover align-middle mb-0 table-striped table-bordered rounded-3 mobile-card-table">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Nom colis</th>
                                    <th>Nature</th>
                                    <th>Valeur</th>
                                    <th>Frais de transaction</th>
                                    <th>Destination</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php $this->view('admin/helpers') ?>
                                <?php foreach ($liste_colis as $colis) : ?>
                                    <tr>
                                        <td data-label="Nom colis"><?= htmlspecialchars($colis['nom_colis']) ?></td>
                                        <td data-label="Nature"><?= htmlspecialchars($colis['nature']) ?></td>
                                        <td data-label="Valeur"><span class="badge bg-light text-dark"><?= htmlspecialchars($colis['valeur']) ?></span></td>
                                        <td data-label="Frais de transaction"><span class="badge bg-secondary"><?= htmlspecialchars($colis['fraix_transaction']) ?></span></td>
                                        <td data-label="Destination"><?= htmlspecialchars($colis['destination']) ?></td>
                                        <td data-label="Status"><?= afficherBadgeStatus($colis['status']) ?></td>
                                        <td data-label="Action">
                                            <div class="dropdown">
                                                <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li>
                                                        <a class="dropdown-item details-colis-btn" href="#"
                                                            data-bs-toggle="modal" data-bs-target="#detailsColisModal"
                                                            data-nom="<?= htmlspecialchars($colis['nom_colis'] ?? '') ?>"
                                                            data-nature="<?= htmlspecialchars($colis['nature'] ?? '') ?>"
                                                            data-valeur="<?= htmlspecialchars(number_format($colis['valeur'], 0, ',', ' ')) ?>"
                                                            data-frais="<?= htmlspecialchars(number_format($colis['fraix_transaction'], 0, ',', ' ')) ?>"
                                                            data-lieu-label="Destination"
                                                            data-lieu="<?= htmlspecialchars($colis['destination'] ?? '') ?>"
                                                            data-code="<?= htmlspecialchars($colis['code_colis'] ?? '') ?>"
                                                            data-status="<?= htmlspecialchars($colis['status'] ?? '') ?>"
                                                            data-status-html="<?= htmlspecialchars(afficherBadgeStatus($colis['status'] ?? '')) ?>"
                                                            data-date="<?= htmlspecialchars($colis['date_enregistrement'] ?? '') ?>"
                                                            data-expediteur="<?= htmlspecialchars($colis['expediteur'] ?? '') ?>"
                                                            data-numero-exp="<?= htmlspecialchars($colis['numero_exp'] ?? '') ?>"
                                                            data-destinataire="<?= htmlspecialchars($colis['destinataire'] ?? '') ?>"
                                                            data-numero-dest="<?= htmlspecialchars($colis['numero_dest'] ?? '') ?>">
                                                            <i class="bx bx-show-alt me-2"></i>Détails
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item edit-colis-btn" href="#"
                                                            data-bs-toggle="modal" data-bs-target="#editColisModal"
                                                            data-id="<?= (int)$colis['id_colis'] ?>"
                                                            data-nom="<?= htmlspecialchars($colis['nom_colis'] ?? '') ?>"
                                                            data-nature="<?= htmlspecialchars($colis['nature'] ?? '') ?>"
                                                            data-destination-id="<?= (int)($colis['id_agence'] ?? 0) ?>"
                                                            data-valeur="<?= htmlspecialchars($colis['valeur'] ?? '') ?>"
                                                            data-frais="<?= htmlspecialchars($colis['fraix_transaction'] ?? '') ?>">
                                                            <i class="bx bx-edit me-2"></i>Modifier
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="<?= BASE_URL ?>/admin/Colis_prise_en_charges/imprimer_recu/<?= $colis['id_colis'] ?>"
                                                            target="_blank">
                                                            <i class="bx bx-printer me-2"></i>Imprimer le reçu
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="colis-panel">
                                    <div class="colis-panel-title"><i class="bx bx-download me-1"></i> Destinataire</div>
                                    <div class="colis-info-row"><i class="bx bx-user"></i><span id="dc_destinataire"></span></div>
                                    <div class="colis-info-row"><i class="bx bx-phone"></i><span id="dc_numero_dest"></span></div>
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

        <!-- Modal Modifier Colis -->
        <div class="modal fade" id="editColisModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-dark">Modifier le colis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id_colis" id="edit_id_colis">

                            <div class="mb-3">
                                <label class="form-label">Nom du colis</label>
                                <input type="text" class="form-control" name="nom_colis" id="edit_nom_colis" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nature</label>
                                <input type="text" class="form-control" name="nature" id="edit_nature" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Destination</label>
                                <select class="form-control" name="destination" id="edit_destination" required>
                                    <option value="" disabled>Choisissez la destination</option>
                                    <?php foreach ($listes_agences as $agence): ?>
                                        <?php if ($agence->idAgence != ($_SESSION['id_agence'] ?? null)): ?>
                                            <option value="<?= htmlspecialchars($agence->idAgence) ?>">
                                                <?= htmlspecialchars($agence->localite . ' ( ' . $agence->numeroGare . ' )') ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Valeur</label>
                                    <input type="number" min="0" class="form-control" name="valeur" id="edit_valeur" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Frais de transaction</label>
                                    <input type="number" min="0" class="form-control" name="fraix_transaction" id="edit_fraix_transaction" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary" name="update_colis">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!--end wrapper-->

    <script>
        document.querySelectorAll('.details-colis-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('dc_expediteur').textContent = this.dataset.expediteur || '-';
                document.getElementById('dc_numero_exp').textContent = this.dataset.numeroExp || '-';
                document.getElementById('dc_destinataire').textContent = this.dataset.destinataire || '-';
                document.getElementById('dc_numero_dest').textContent = this.dataset.numeroDest || '-';
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

        document.querySelectorAll('.edit-colis-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('edit_id_colis').value = this.dataset.id || '';
                document.getElementById('edit_nom_colis').value = this.dataset.nom || '';
                document.getElementById('edit_nature').value = this.dataset.nature || '';
                document.getElementById('edit_destination').value = this.dataset.destinationId || '';
                document.getElementById('edit_valeur').value = this.dataset.valeur || '';
                document.getElementById('edit_fraix_transaction').value = this.dataset.frais || '';
            });
        });
    </script>

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
    </style>

    <?php $this->view('admin/partials/foot') ?>

</body>

</html>