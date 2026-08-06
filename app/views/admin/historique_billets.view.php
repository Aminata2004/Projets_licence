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
                            <li class="breadcrumb-item active text-primary" aria-current="page">Historique des billets</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-sm-auto mt-2 mt-sm-0">
                    <div class="btn-group">
                        <?php if (($_SESSION['droit'] ?? null) !== 'PDG'): ?>
                        <a href="<?= BASE_URL ?>/admin/Add_billets" class="btn btn-primary split-bg-primary text-white"> + Ajouter</a> &nbsp;
                        <?php endif; ?>
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body border-top border-primary border-1">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/Liste_du_jours" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-calendar-event font-19"></i>
                                    </div>
                                    <div class="tab-title">Liste du jour</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/Liste_de_demains" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-time-five font-19"></i>
                                    </div>
                                    <div class="tab-title">Liste de demain</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" href="<?= BASE_URL ?>/admin/Liste_du_jours/historique" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-history font-19"></i>
                                    </div>
                                    <div class="tab-title">Historique</div>
                                </div>
                            </a>
                        </li>
                    </ul>
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
                            <label for="dateHistorique" class="form-label">Date</label>
                            <input type="date" class="form-control" id="dateHistorique" name="date"
                                value="<?= htmlspecialchars($date) ?>" max="<?= date('Y-m-d') ?>"
                                onchange="this.form.submit()">
                        </div>
                        <div class="col-md-4">
                            <label for="destination" class="form-label">Destination</label>
                            <select class="form-select" id="id_destination" name="destination">
                                <option value="">Toutes les destinations</option>
                                <?php if (!empty($destinations) && is_array($destinations)): ?>
                                    <?php foreach ($destinations as $destination): ?>
                                        <?php if (is_array($destination) && isset($destination['idDestination'])): ?>
                                            <option value="<?= htmlspecialchars(trim($destination['idDestination'])) ?>">
                                                <?= htmlspecialchars(trim($destination['idDestination'])) ?>
                                            </option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="heure" class="form-label">Heure de départ</label>
                            <select class="single-select" id="selectheure" name="heure">
                                <option value="">Toutes les heures</option>
                                <?php foreach ($liste_horaires as $liste_horaire): ?>
                                    <option value="<?= htmlspecialchars($liste_horaire->heuredepart) ?>"><?= $liste_horaire->heuredepart ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <button type="button" class="btn btn-success" id="btnImprimerListeHistorique">
                                <i class="bx bx-printer"></i> Imprimer la liste d'embarquement de cette date
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->view("admin/set_flash") ?>
            <div class="card">
                <div class="card-body">

                    <div class="tab-content py-3 table-responsive">
                        <table id="example" class="table table-striped table-bordered mobile-card-table" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Client</th>
                                    <th>Destination</th>
                                    <th>N° de place</th>
                                    <th>Heure de départ</th>
                                    <th>Jour de voyage</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($liste_historique as $item): ?>
                                    <tr class="text-center">
                                        <td data-label="Client"><?= htmlspecialchars($item->Client) ?></td>
                                        <td data-label="Destination"><?= htmlspecialchars($item->destinationId) ?></td>
                                        <td data-label="N° de place">Chaisse N°<?= htmlspecialchars($item->numeroPlace) ?></td>
                                        <td data-label="Heure de départ"><?= htmlspecialchars($item->Heur_departs) ?></td>
                                        <td data-label="Jour de voyage"><?= htmlspecialchars($item->jourVoyage) ?></td>
                                        <td data-label="Action">
                                            <div class="dropup">
                                                <a href="#" class="text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                    &#8943;
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Liste_du_jours/recu/<?= $item->idBillets ?>" target="_blank">
                                                        Imprimer (imprimante câble/USB)
                                                    </a>
                                                    <a class="dropdown-item thermal-print-btn" href="#" data-id="<?= $item->idBillets ?>">
                                                        Imprimer (imprimante WiFi)
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                <?php if (empty($liste_historique)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Aucun billet pour cette date.</td>
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

    <?php $this->view('admin/partials/foot') ?>
    <script src="<?= ASSET_URL ?>/mon_js/thermal-print.js"></script>
    <script>
        document.getElementById('btnImprimerListeHistorique').addEventListener('click', function() {
            const date = document.getElementById('dateHistorique').value;
            const destination = document.getElementById('id_destination').value;
            const heure = document.getElementById('selectheure').value;
            const url = '<?= BASE_URL ?>/admin/Liste_du_jours/imprimerListe'
                + '?date=' + encodeURIComponent(date)
                + '&destination=' + encodeURIComponent(destination)
                + '&heure=' + encodeURIComponent(heure);
            window.open(url, '_blank');
        });
    </script>

</body>

</html>
