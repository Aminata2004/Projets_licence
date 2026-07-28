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
            <input type="hidden" id="csrfToken" value="<?= htmlspecialchars(csrf_token()) ?>">

            <?php if (!empty($carsComplets)): ?>
                <div class="alert alert-danger d-flex align-items-start gap-2" style="border-left: 4px solid #dc3545;">
                    <i class="bx bx-error-circle" style="font-size:1.4rem;"></i>
                    <div>
                        <strong>Bus complet<?= count($carsComplets) > 1 ? 's' : '' ?> : embarquement requis</strong>
                        <ul class="mb-0 mt-1">
                            <?php foreach ($carsComplets as $c): ?>
                                <li>
                                    Car N°<?= htmlspecialchars($c['numero_car']) ?> vers <strong><?= htmlspecialchars($c['destination']) ?></strong>
                                    à <?= htmlspecialchars($c['heure']) ?> — <?= (int)$c['nbr_place_reserve'] ?>/<?= (int)$c['nbr_place'] ?> places vendues.
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php
                $total = count($liste);
                $embarques = count(array_filter($liste, fn($b) => ($b['statut_embarquement'] ?? null) === 'embarque'));
            ?>
            <div class="card shadow-sm border-0 rounded-3 mb-3">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="fs-4 fw-bold text-success" id="compteurEmbarques"><?= $embarques ?></span>
                        <span class="text-muted"> / <span id="compteurTotal"><?= $total ?></span> embarqués</span>
                    </div>
                    <?php if ($total > 0): ?>
                        <div class="progress flex-grow-1 mx-3" style="height: 10px; max-width: 300px;">
                            <div class="progress-bar bg-success" id="progressEmbarquement" style="width: <?= $total ? round($embarques / $total * 100) : 0 ?>%"></div>
                        </div>
                    <?php endif; ?>
                    <button type="button" class="btn btn-success" id="btnEmbarquerSelection" disabled>
                        <i class="bx bx-check-double"></i> Embarquer la sélection (<span id="nbSelection">0</span>)
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mobile-card-table" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th><input type="checkbox" id="checkAll"></th>
                                    <th>Client</th>
                                    <th>Destination</th>
                                    <th>N° de place</th>
                                    <th>Heure de départ</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyEmbarquement">
                                <?php foreach ($liste as $b): ?>
                                    <?php $estEmbarque = ($b['statut_embarquement'] ?? null) === 'embarque'; ?>
                                    <tr class="text-center" data-id="<?= $b['idBillets'] ?>">
                                        <td>
                                            <input type="checkbox" class="chk-billet" value="<?= $b['idBillets'] ?>" <?= $estEmbarque ? 'disabled' : '' ?>>
                                        </td>
                                        <td data-label="Client"><?= htmlspecialchars($b['Client'] ?? '-') ?></td>
                                        <td data-label="Destination"><?= htmlspecialchars($b['destinationId'] ?? '-') ?></td>
                                        <td data-label="N° de place">Chaisse N°<?= htmlspecialchars($b['numeroPlace'] ?? '-') ?></td>
                                        <td data-label="Heure de départ"><?= htmlspecialchars($b['Heur_departs'] ?? '-') ?></td>
                                        <td data-label="Statut" class="cell-statut">
                                            <?php if ($estEmbarque): ?>
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
                                        <td data-label="Action" class="cell-action">
                                            <?php if ($estEmbarque): ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-annuler-embarquement" data-id="<?= $b['idBillets'] ?>">
                                                    <i class="bx bx-undo"></i> Annuler
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-success btn-marquer-embarque" data-id="<?= $b['idBillets'] ?>">
                                                    <i class="bx bx-check"></i> Embarquer
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-demander-report"
                                                    data-id="<?= $b['idBillets'] ?>" data-client="<?= htmlspecialchars($b['Client'] ?? '-') ?>"
                                                    data-destination="<?= htmlspecialchars($b['destinationId'] ?? '') ?>"
                                                    data-bs-toggle="modal" data-bs-target="#modalDemanderReport">
                                                    <i class="bx bx-calendar-x"></i> Demander le report
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                <?php if (empty($liste)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Aucun billet pour ce filtre.</td>
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
                            <select class="form-select" name="nouvelle_heure" id="reportHeureSelect" required>
                                <option value="" disabled selected>Choisissez une heure de départ</option>
                            </select>
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
        const csrfToken = document.getElementById('csrfToken').value;
        const baseUrl = '<?= BASE_URL ?>';
        const tbody = document.getElementById('tbodyEmbarquement');

        function toast(icon, message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: message,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        }

        function majCompteurs() {
            const badgesEmbarques = tbody.querySelectorAll('.cell-statut .badge.bg-success').length;
            const totalLignes = tbody.querySelectorAll('tr[data-id]').length;
            document.getElementById('compteurEmbarques').textContent = badgesEmbarques;
            document.getElementById('compteurTotal').textContent = totalLignes;
            const progress = document.getElementById('progressEmbarquement');
            if (progress) {
                progress.style.width = (totalLignes ? Math.round(badgesEmbarques / totalLignes * 100) : 0) + '%';
            }
        }

        function majSelectionUi() {
            const n = tbody.querySelectorAll('.chk-billet:checked').length;
            document.getElementById('nbSelection').textContent = n;
            document.getElementById('btnEmbarquerSelection').disabled = (n === 0);
        }

        function appliquerLigneEmbarquee(tr) {
            const id = tr.dataset.id;
            tr.querySelector('.cell-statut').innerHTML =
                '<span class="badge bg-success">Embarqué (à l\'instant)</span>';
            tr.querySelector('.cell-action').innerHTML =
                '<button type="button" class="btn btn-sm btn-outline-secondary btn-annuler-embarquement" data-id="' + id + '">' +
                '<i class="bx bx-undo"></i> Annuler</button>';
            const chk = tr.querySelector('.chk-billet');
            chk.checked = false;
            chk.disabled = true;
            attacherBoutonsLigne(tr);
        }

        function appliquerLigneNonEmbarquee(tr) {
            const id = tr.dataset.id;
            const client = tr.querySelector('[data-label="Client"]').textContent.trim();
            const destination = tr.querySelector('[data-label="Destination"]').textContent.trim();
            tr.querySelector('.cell-statut').innerHTML =
                '<span class="badge bg-warning text-dark">En attente</span>';
            tr.querySelector('.cell-action').innerHTML =
                '<button type="button" class="btn btn-sm btn-success btn-marquer-embarque" data-id="' + id + '">' +
                '<i class="bx bx-check"></i> Embarquer</button> ' +
                '<button type="button" class="btn btn-sm btn-outline-danger btn-demander-report" data-id="' + id + '" ' +
                'data-client="' + client + '" data-destination="' + destination + '" ' +
                'data-bs-toggle="modal" data-bs-target="#modalDemanderReport">' +
                '<i class="bx bx-calendar-x"></i> Demander le report</button>';
            tr.querySelector('.chk-billet').disabled = false;
            attacherBoutonsLigne(tr);
        }

        function marquerEmbarqueAjax(id, tr) {
            fetch(baseUrl + '/admin/Liste_du_jours/marquerEmbarque', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'idBillets=' + encodeURIComponent(id) + '&csrf_token=' + encodeURIComponent(csrfToken)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.ok) {
                        appliquerLigneEmbarquee(tr);
                        majCompteurs();
                    }
                    toast(data.ok ? 'success' : 'warning', data.message || (data.ok ? 'Client embarqué.' : 'Action impossible.'));
                })
                .catch(() => toast('error', "Erreur réseau, veuillez réessayer."));
        }

        function annulerEmbarqueAjax(id, tr) {
            fetch(baseUrl + '/admin/Liste_du_jours/annulerEmbarquement', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'idBillets=' + encodeURIComponent(id) + '&csrf_token=' + encodeURIComponent(csrfToken)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.ok) {
                        appliquerLigneNonEmbarquee(tr);
                        majCompteurs();
                    }
                    toast(data.ok ? 'info' : 'warning', data.message || (data.ok ? 'Embarquement annulé.' : 'Action impossible.'));
                })
                .catch(() => toast('error', "Erreur réseau, veuillez réessayer."));
        }

        function attacherBoutonsLigne(tr) {
            const btnEmbarquer = tr.querySelector('.btn-marquer-embarque');
            if (btnEmbarquer) {
                btnEmbarquer.addEventListener('click', function() {
                    marquerEmbarqueAjax(this.dataset.id, tr);
                });
            }
            const btnAnnuler = tr.querySelector('.btn-annuler-embarquement');
            if (btnAnnuler) {
                btnAnnuler.addEventListener('click', function() {
                    annulerEmbarqueAjax(this.dataset.id, tr);
                });
            }
            const btnReport = tr.querySelector('.btn-demander-report');
            if (btnReport) {
                btnReport.addEventListener('click', function() {
                    document.getElementById('reportIdBillets').value = this.dataset.id;
                    document.getElementById('reportClientNom').textContent = this.dataset.client;
                    chargerHeuresReport(this.dataset.destination);
                });
            }
        }

        // Au lieu de laisser saisir n'importe quelle heure, on ne propose que les heures
        // reellement programmees pour cette destination (meme logique que le report classique
        // de Liste_du_jours::getHeuresDisponibles, deja utilise ailleurs dans l'appli) : elles
        // s'appliquent aussi bien a "aujourd'hui" qu'a "demain", l'horaire type ne change pas.
        function chargerHeuresReport(destinationId) {
            const select = document.getElementById('reportHeureSelect');
            select.innerHTML = '<option value="" disabled selected>Chargement...</option>';
            select.disabled = true;

            fetch(baseUrl + '/admin/Liste_du_jours/getHeuresDisponibles', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'destination_id=' + encodeURIComponent(destinationId)
                })
                .then(r => r.json())
                .then(heures => {
                    select.innerHTML = '<option value="" disabled selected>Choisissez une heure de départ</option>';
                    if (!Array.isArray(heures) || heures.length === 0) {
                        select.innerHTML += '<option value="" disabled>Aucune heure programmée pour cette destination</option>';
                    } else {
                        heures.forEach(h => {
                            select.innerHTML += '<option value="' + h + '">' + h + '</option>';
                        });
                    }
                    select.disabled = false;
                })
                .catch(() => {
                    select.innerHTML = '<option value="" disabled selected>Erreur de chargement</option>';
                    select.disabled = false;
                });
        }

        tbody.querySelectorAll('tr[data-id]').forEach(attacherBoutonsLigne);

        tbody.addEventListener('change', function(e) {
            if (e.target.classList.contains('chk-billet')) {
                majSelectionUi();
            }
        });

        document.getElementById('checkAll').addEventListener('change', function() {
            tbody.querySelectorAll('.chk-billet:not(:disabled)').forEach(chk => {
                chk.checked = this.checked;
            });
            majSelectionUi();
        });

        document.getElementById('btnEmbarquerSelection').addEventListener('click', function() {
            const cases = Array.from(tbody.querySelectorAll('.chk-billet:checked'));
            const ids = cases.map(c => c.value);
            if (ids.length === 0) return;

            this.disabled = true;
            const body = new URLSearchParams();
            ids.forEach(id => body.append('idsBillets[]', id));
            body.append('csrf_token', csrfToken);

            fetch(baseUrl + '/admin/Liste_du_jours/marquerEmbarqueLot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: body.toString()
                })
                .then(r => r.json())
                .then(data => {
                    if (data.ok && Array.isArray(data.details)) {
                        data.details.forEach(function(d) {
                            if (d.ok) {
                                const tr = tbody.querySelector('tr[data-id="' + d.id + '"]');
                                if (tr) appliquerLigneEmbarquee(tr);
                            }
                        });
                        majCompteurs();
                        document.getElementById('checkAll').checked = false;
                        majSelectionUi();
                        let resume = data.succes + ' embarqué(s)';
                        if (data.deja > 0) resume += ', ' + data.deja + ' déjà embarqué(s)';
                        if (data.echecs > 0) resume += ', ' + data.echecs + ' échec(s)';
                        toast(data.echecs > 0 ? 'warning' : 'success', resume);
                    } else {
                        toast('error', data.message || "Erreur lors de l'embarquement en masse.");
                    }
                })
                .catch(() => toast('error', "Erreur réseau, veuillez réessayer."))
                .finally(() => majSelectionUi());
        });
    </script>

</body>

</html>
