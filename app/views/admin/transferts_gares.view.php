<?php $this->view('admin/partials/headers') ?>
<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">🔄 Historique des transferts de passagers</h4>
                <a href="<?= BASE_URL ?>/admin/Programmation_voyages/liste_programmer_voyage" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="bx bx-left-arrow-alt"></i> Retour aux programmations
                </a>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header fw-bold">
                    <i class="bx bx-list-ul me-1"></i> Transferts effectués
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Gare source</th>
                                <th>Gare destination</th>
                                <th>Passagers</th>
                                <th>Billets</th>
                                <th>Montant transféré</th>
                                <th>Effectué par</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listeTransferts)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">Aucun transfert enregistré.</td></tr>
                            <?php else: ?>
                                <?php foreach ($listeTransferts as $t): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($t->date_transfert)) ?></td>
                                        <td><?= htmlspecialchars(($t->localite_source ?? '-') . ' (' . ($t->numeroGare_source ?? '-') . ')') ?></td>
                                        <td><?= htmlspecialchars(($t->localite_destination ?? '-') . ' (' . ($t->numeroGare_destination ?? '-') . ')') ?></td>
                                        <td><?= (int)$t->nombre_passagers ?></td>
                                        <td><?= (int)$t->nombre_billets ?></td>
                                        <td class="fw-bold text-success"><?= number_format($t->montant_total, 0, ',', ' ') ?> F</td>
                                        <td><?= htmlspecialchars($t->agent ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <?php $this->view('admin/partials/foot') ?>
</body>
</html>
