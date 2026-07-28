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
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-reservation</div>
                <div class="ps-3 d-none d-sm-block">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Embarquement</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-sm-auto mt-2 mt-sm-0">
                    <a href="javascript:history.back()" class="btn btn-primary"><i class="fadeIn animated bx bx-left-arrow-alt"></i></a>
                </div>
            </div>

            <div class="card border-top border-primary border-1">
                <div class="bg-light border-bottom rounded-top px-3 py-2 d-flex align-items-center mb-0 mt-1" style="gap:8px;">
                    <i class="bx bx-filter-alt text-primary" style="font-size:1.3rem;"></i>
                    <h6 class="mb-0 fw-bold text-primary" style="letter-spacing:1px;">Filtrage</h6>
                </div>

                <div class="card-body p-4 border-1">
                    <form method="get" class="row g-3">
                        <div class="col-md-4">
                            <label for="dateEmbarquement" class="form-label">Date</label>
                            <input type="date" class="form-control" id="dateEmbarquement" name="date"
                                value="<?= htmlspecialchars($date) ?>" onchange="this.form.submit()">
                        </div>
                        <div class="col-md-4">
                            <label for="destination" class="form-label">Destination</label>
                            <select class="form-select" name="destination" onchange="this.form.submit()">
                                <option value="">Toutes les destinations</option>
                                <?php if (!empty($destinations) && is_array($destinations)): ?>
                                    <?php foreach ($destinations as $destination): ?>
                                        <?php if (is_array($destination) && isset($destination['idDestination'])): ?>
                                            <option value="<?= htmlspecialchars(trim($destination['idDestination'])) ?>"
                                                <?= (($_GET['destination'] ?? '') === trim($destination['idDestination'])) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars(trim($destination['idDestination'])) ?>
                                            </option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="heure" class="form-label">Heure de départ</label>
                            <select class="form-select" name="heure" onchange="this.form.submit()">
                                <option value="">Toutes les heures</option>
                                <?php foreach ($liste_horaires as $liste_horaire): ?>
                                    <option value="<?= htmlspecialchars($liste_horaire->heuredepart) ?>"
                                        <?= (($_GET['heure'] ?? '') === $liste_horaire->heuredepart) ? 'selected' : '' ?>>
                                        <?= $liste_horaire->heuredepart ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <?php
                $total = count($liste);
                $embarques = count(array_filter($liste, fn($b) => ($b['statut_embarquement'] ?? null) === 'embarque'));
            ?>
            <div class="card shadow-sm border-0 rounded-3 mb-3">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="fs-4 fw-bold text-success"><?= $embarques ?></span>
                        <span class="text-muted"> / <?= $total ?> embarqués</span>
                    </div>
                    <?php if ($total > 0): ?>
                        <div class="progress flex-grow-1 mx-3" style="height: 10px; max-width: 300px;">
                            <div class="progress-bar bg-success" style="width: <?= $total ? round($embarques / $total * 100) : 0 ?>%"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mobile-card-table" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Client</th>
                                    <th>Destination</th>
                                    <th>N° de place</th>
                                    <th>Heure de départ</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($liste as $b): ?>
                                    <tr class="text-center">
                                        <td data-label="Client"><?= htmlspecialchars($b['Client'] ?? '-') ?></td>
                                        <td data-label="Destination"><?= htmlspecialchars($b['destinationId'] ?? '-') ?></td>
                                        <td data-label="N° de place">Chaisse N°<?= htmlspecialchars($b['numeroPlace'] ?? '-') ?></td>
                                        <td data-label="Heure de départ"><?= htmlspecialchars($b['Heur_departs'] ?? '-') ?></td>
                                        <td data-label="Statut">
                                            <?php if (($b['statut_embarquement'] ?? null) === 'embarque'): ?>
                                                <span class="badge bg-success">
                                                    Embarqué <?= !empty($b['embarque_le']) ? '(' . date('H:i', strtotime($b['embarque_le'])) . ')' : '' ?>
                                                </span>
                                                <?php if (!empty($b['embarque_par_nom'])): ?>
                                                    <br><small class="text-muted">par <?= htmlspecialchars($b['embarque_par_nom']) ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Action">
                                            <?php if (($b['statut_embarquement'] ?? null) === 'embarque'): ?>
                                                <form action="<?= BASE_URL ?>/admin/Liste_du_jours/annulerEmbarquement" method="post" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="idBillets" value="<?= $b['idBillets'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bx bx-undo"></i> Annuler
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form action="<?= BASE_URL ?>/admin/Liste_du_jours/marquerEmbarque" method="post" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="idBillets" value="<?= $b['idBillets'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bx bx-check"></i> Embarquer
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-demander-report"
                                                    data-id="<?= $b['idBillets'] ?>" data-client="<?= htmlspecialchars($b['Client'] ?? '-') ?>"
                                                    data-bs-toggle="modal" data-bs-target="#modalDemanderReport">
                                                    <i class="bx bx-calendar-x"></i> Demander le report
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                <?php if (empty($liste)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Aucun billet pour ce filtre.</td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
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

    <!-- Modal Demander le report -->
    <div class="modal fade" id="modalDemanderReport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Demander le report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
                </div>
                <form action="<?= BASE_URL ?>/admin/Liste_du_jours/demanderReport" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <p>Client non embarqué : <strong id="reportClientNom"></strong></p>
                        <p class="text-muted small">La demande sera soumise à validation avant d'être appliquée.</p>
                        <input type="hidden" name="idBillets" id="reportIdBillets">
                        <div class="mb-3">
                            <label class="form-label">Nouvelle date de voyage</label>
                            <input type="date" class="form-control" name="nouvelle_date" min="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nouvelle heure de départ</label>
                            <input type="time" class="form-control" name="nouvelle_heure" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Envoyer la demande</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $this->view('admin/partials/foot') ?>

    <script>
        document.querySelectorAll('.btn-demander-report').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('reportIdBillets').value = this.dataset.id;
                document.getElementById('reportClientNom').textContent = this.dataset.client;
            });
        });
    </script>

</body>

</html>
