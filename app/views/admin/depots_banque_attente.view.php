<?php $this->view('admin/partials/headers') ?>
<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">🏦 Demandes de dépôt en attente</h4>
                <a href="<?= BASE_URL ?>/admin/Depots_banque/historique" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="bx bx-history"></i> Historique complet
                </a>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <?php $totalEnAttente = array_sum(array_map(fn($d) => (float)$d->montant, $listeDemandes)); ?>

            <div class="d-flex flex-wrap gap-4 align-items-center text-muted small mb-3 px-1">
                <span><i class="bx bx-time text-warning me-1"></i><?= count($listeDemandes) ?> demande(s) en attente</span>
                <span class="text-secondary">|</span>
                <span><i class="bx bx-money text-primary me-1"></i>Montant total : <strong class="text-dark"><?= number_format($totalEnAttente, 0, ',', ' ') ?> F</strong></span>
            </div>

            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2"
                     style="background: linear-gradient(135deg, #0f3b5e, #1d6fa5); color: #fff;">
                    <i class="bx bx-time fs-5"></i>
                    <span class="fw-semibold">À valider</span>
                </div>
                <div class="table-responsive p-2">
                    <table id="example" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="border-0">Date</th>
                                <th class="border-0">Gare</th>
                                <th class="border-0">Banque</th>
                                <th class="border-0">Montant</th>
                                <th class="border-0">Référence</th>
                                <th class="border-0">Demandé par</th>
                                <th class="border-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listeDemandes)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">Aucune demande en attente.</td></tr>
                            <?php else: ?>
                                <?php foreach ($listeDemandes as $d): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($d->date_demande)) ?></td>
                                        <td><?= htmlspecialchars($d->localite . ' (' . $d->numeroGare . ')') ?></td>
                                        <td><?= htmlspecialchars($d->nom_banque) ?></td>
                                        <td class="fw-bold"><?= number_format($d->montant, 0, ',', ' ') ?> F</td>
                                        <td><?= htmlspecialchars($d->reference ?? '-') ?></td>
                                        <td><?= htmlspecialchars($d->demandeur) ?></td>
                                        <td class="d-flex gap-2">
                                            <form method="post" action="<?= BASE_URL ?>/admin/Depots_banque/confirmer/<?= $d->id_depot ?>" id="form-confirmer-<?= $d->id_depot ?>">
                                                <?= csrf_field() ?>
                                                <button type="button" class="btn btn-sm btn-success"
                                                    onclick="confirmerDepot(<?= $d->id_depot ?>, <?= (float)$d->montant ?>, '<?= addslashes($d->localite . ' (' . $d->numeroGare . ')') ?>')">
                                                    <i class="bx bx-check"></i> Confirmer
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#modalRejet<?= $d->id_depot ?>">
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

    <!-- Modales de rejet : placées hors du tableau (invalide en enfant direct de tbody), comme dans compagnies.view.php -->
    <?php if (!empty($listeDemandes)): ?>
        <?php foreach ($listeDemandes as $d): ?>
            <div class="modal fade" id="modalRejet<?= $d->id_depot ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="post" action="<?= BASE_URL ?>/admin/Depots_banque/rejeter/<?= $d->id_depot ?>">
                            <?= csrf_field() ?>
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title text-white">Rejeter la demande</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label fw-semibold">Motif (optionnel)</label>
                                <textarea class="form-control" name="motif_rejet" rows="3"></textarea>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmerDepot(id, montant, gare) {
            const montantFmt = montant.toLocaleString('fr-FR');
            Swal.fire({
                title: 'Confirmer ce dépôt ?',
                html: `<strong>${montantFmt} FCFA</strong> seront débités de la caisse de <strong>${gare}</strong> et crédités sur le compte banque.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, confirmer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-confirmer-' + id).submit();
                }
            });
        }
    </script>

    <?php $this->view('admin/partials/foot') ?>
</body>
</html>
