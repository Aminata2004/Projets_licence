<?php $this->view('admin/partials/headers') ?>
<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    🔁 Demandes de report
                    <?= $estAdmin ? '— à valider' : '— de votre gare' ?>
                </h4>
                <a href="<?= BASE_URL ?>/admin/Liste_du_jours/index" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="bx bx-arrow-back"></i> Retour à la liste des billets
                </a>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <?php if (!$estAdmin): ?>
                <div class="alert alert-info small">
                    Ces demandes viennent des agents de votre gare (client non embarqué). Transmettez-les à l'Admin pour validation finale, ou rejetez-les directement.
                </div>
            <?php endif; ?>

            <div class="d-flex flex-wrap gap-4 align-items-center text-muted small mb-3 px-1">
                <span><i class="bx bx-time text-warning me-1"></i><?= count($listeDemandes) ?> demande(s) en attente</span>
            </div>

            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2"
                     style="background: linear-gradient(135deg, #0f3b5e, #1d6fa5); color: #fff;">
                    <i class="bx bx-time fs-5"></i>
                    <span class="fw-semibold">À traiter</span>
                </div>
                <div class="table-responsive p-2">
                    <table id="example" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="border-0">Demandé le</th>
                                <th class="border-0">Billet</th>
                                <th class="border-0">Client</th>
                                <th class="border-0">Trajet</th>
                                <th class="border-0">Départ actuel</th>
                                <th class="border-0">Nouveau départ demandé</th>
                                <th class="border-0">Demandé par</th>
                                <?php if ($estAdmin): ?>
                                    <th class="border-0">Transmis par</th>
                                <?php endif; ?>
                                <th class="border-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listeDemandes)): ?>
                                <tr><td colspan="<?= $estAdmin ? '9' : '8' ?>" class="text-center text-muted py-4">Aucune demande en attente.</td></tr>
                            <?php else: ?>
                                <?php foreach ($listeDemandes as $d): ?>
                                    <tr>
                                        <td><?= !empty($d['demande_report_le']) ? date('d/m/Y H:i', strtotime($d['demande_report_le'])) : '-' ?></td>
                                        <td><?= htmlspecialchars($d['numeroBillets']) ?></td>
                                        <td><?= htmlspecialchars($d['Client']) ?></td>
                                        <td><?= htmlspecialchars($d['departId'] . ' → ' . $d['destinationId']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($d['jourVoyage'])) ?> à <?= htmlspecialchars(substr($d['Heur_departs'], 0, 5)) ?></td>
                                        <td class="fw-bold text-danger">
                                            <?= date('d/m/Y', strtotime($d['nouvelle_date_demandee'])) ?> à <?= htmlspecialchars(substr($d['nouvelle_heure_demandee'], 0, 5)) ?>
                                        </td>
                                        <td><?= htmlspecialchars($d['demandeur'] ?? '-') ?></td>
                                        <?php if ($estAdmin): ?>
                                            <td><?= htmlspecialchars($d['transmis_par_nom'] ?? '-') ?></td>
                                        <?php endif; ?>
                                        <td class="d-flex gap-2">
                                            <?php if ($estAdmin): ?>
                                                <form method="post" action="<?= BASE_URL ?>/admin/Liste_du_jours/confirmerReport/<?= $d['idBillets'] ?>" id="form-confirmer-<?= $d['idBillets'] ?>">
                                                    <?= csrf_field() ?>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        onclick="confirmerReport(<?= $d['idBillets'] ?>, '<?= addslashes($d['numeroBillets']) ?>')">
                                                        <i class="bx bx-check"></i> Valider
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form method="post" action="<?= BASE_URL ?>/admin/Liste_du_jours/transmettreReport/<?= $d['idBillets'] ?>">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="bx bx-send"></i> Transmettre à l'Admin
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#modalRejet<?= $d['idBillets'] ?>">
                                                <i class="bx bx-x"></i> Rejeter
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- Modales de rejet -->
    <?php if (!empty($listeDemandes)): ?>
        <?php foreach ($listeDemandes as $d): ?>
            <div class="modal fade" id="modalRejet<?= $d['idBillets'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="post" action="<?= BASE_URL ?>/admin/Liste_du_jours/rejeterReport/<?= $d['idBillets'] ?>">
                            <?= csrf_field() ?>
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title text-white">Rejeter la demande</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
                            </div>
                            <div class="modal-body">
                                <p>Le billet n°<strong><?= htmlspecialchars($d['numeroBillets']) ?></strong> restera sur son départ initial.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-danger">Rejeter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php $this->view('admin/partials/foot') ?>
    <script>
        function confirmerReport(id, numeroBillets) {
            Swal.fire({
                title: 'Valider ce report ?',
                html: `Le billet <strong>${numeroBillets}</strong> sera réellement reprogrammé à la nouvelle date/heure demandée.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, valider',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-confirmer-' + id).submit();
                }
            });
        }
    </script>
</body>
</html>
