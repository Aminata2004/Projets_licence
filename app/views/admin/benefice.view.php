<?php $this->view('admin/partials/headers') ?>
<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">📈 Bénéfice de la compagnie</h4>
                <a href="<?= BASE_URL ?>/admin/Depenses" class="btn btn-outline-primary btn-sm rounded-pill">💸 Gérer les dépenses</a>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <div class="bg-white p-2 rounded-3 shadow-sm mb-4 d-inline-flex gap-2">
                <a href="?periode=jour" class="btn btn-sm <?= $periode === 'jour' ? 'btn-primary' : 'btn-outline-primary' ?> rounded-pill">Aujourd'hui</a>
                <a href="?periode=mois" class="btn btn-sm <?= $periode === 'mois' ? 'btn-primary' : 'btn-outline-primary' ?> rounded-pill">Ce mois-ci</a>
                <a href="?periode=tout" class="btn btn-sm <?= $periode === 'tout' ? 'btn-primary' : 'btn-outline-primary' ?> rounded-pill">Depuis le début</a>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="kpi-mini" style="border-left-color: var(--success);">
                        <div class="text-secondary small">Revenus billets</div>
                        <div class="fs-4 fw-bold text-success"><?= number_format($benefice['revenus_billets'], 0, ',', ' ') ?> F</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-mini" style="border-left-color: var(--success);">
                        <div class="text-secondary small">Revenus colis</div>
                        <div class="fs-4 fw-bold text-success"><?= number_format($benefice['revenus_colis'], 0, ',', ' ') ?> F</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-mini" style="border-left-color: var(--danger);">
                        <div class="text-secondary small">Remboursements</div>
                        <div class="fs-4 fw-bold text-danger">-<?= number_format($benefice['remboursements'], 0, ',', ' ') ?> F</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-mini" style="border-left-color: var(--danger);">
                        <div class="text-secondary small">Dépenses (locales + globales)</div>
                        <div class="fs-4 fw-bold text-danger">-<?= number_format($benefice['depenses_locales'] + $benefice['depenses_globales'], 0, ',', ' ') ?> F</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body text-center py-5">
                    <div class="text-secondary mb-2">Bénéfice net</div>
                    <div class="display-4 fw-bold <?= $benefice['benefice'] >= 0 ? 'text-success' : 'text-danger' ?>">
                        <?= number_format($benefice['benefice'], 0, ',', ' ') ?> FCFA
                    </div>
                    <small class="text-muted d-block mt-2">
                        Revenus (billets + colis) − remboursements − dépenses locales − dépenses globales
                    </small>
                    <small class="text-muted d-block">
                        ⚠️ Les remboursements sont cumulés depuis l'ouverture des caisses (pas encore filtrables par période).
                    </small>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <div class="kpi-mini" style="border-left-color: var(--warning);">
                        <div class="text-secondary small">Dont dépenses locales (gares)</div>
                        <div class="fs-5 fw-bold text-danger">-<?= number_format($benefice['depenses_locales'], 0, ',', ' ') ?> F</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="kpi-mini" style="border-left-color: var(--warning);">
                        <div class="text-secondary small">Dont dépenses globales (compagnie)</div>
                        <div class="fs-5 fw-bold text-danger">-<?= number_format($benefice['depenses_globales'], 0, ',', ' ') ?> F</div>
                    </div>
                </div>
            </div>

        </main>
    </div>
    <?php $this->view('admin/partials/foot') ?>
</body>
</html>
