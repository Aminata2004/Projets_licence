<?php $this->view('admin/partials/headers') ?>

<body>
<div class="wrapper">
    <?php $this->view('admin/partials/navbar') ?>
    <?php $this->view('admin/partials/sidebar') ?>

    <main class="page-content">

        <!-- En-tête -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">📈 Rapport Global Compagnie</h4>
                <p class="text-muted small mb-0">Vue consolidée des recettes de toutes les escales</p>
            </div>
            
            <form method="get" class="d-flex gap-2 bg-white p-2 rounded-pill shadow-sm">
                <input type="date" name="date" class="form-control form-control-sm rounded-pill border-0 bg-light px-3" value="<?= htmlspecialchars($date) ?>" max="<?= date('Y-m-d') ?>">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">Consulter</button>
            </form>
        </div>

        <?php $this->view("admin/set_flash") ?>

        <!-- KPI Globaux -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <div class="card-body p-4 text-white text-center d-flex flex-column justify-content-center">
                        <div class="text-white-50 small fw-bold text-uppercase mb-2">Chiffre d'Affaires Global (<?= date('d/m', strtotime($date)) ?>)</div>
                        <div class="fs-1 fw-bold"><?= number_format($grand_total, 0, ',', ' ') ?> <span class="fs-4">FCFA</span></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="row g-3 h-100">
                    <div class="col-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle me-2"><i class="bx bx-receipt fs-5"></i></div>
                                <span class="text-muted fw-semibold">Recettes Billets</span>
                            </div>
                            <h3 class="fw-bold mb-0 mt-auto"><?= number_format($total_billets, 0, ',', ' ') ?></h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-info bg-opacity-10 text-info p-2 rounded-circle me-2"><i class="bx bx-package fs-5"></i></div>
                                <span class="text-muted fw-semibold">Recettes Colis</span>
                            </div>
                            <h3 class="fw-bold mb-0 mt-auto"><?= number_format($total_colis, 0, ',', ' ') ?></h3>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted fw-semibold">Total Écarts Constatés</span>
                                <span class="fw-bold fs-5 <?= $total_ecarts >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= $total_ecarts >= 0 ? '+' : '' ?><?= number_format($total_ecarts, 0, ',', ' ') ?> FCFA
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détail par Agence/Escale -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Détail par escale / gare</h6>
                <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="window.print()"><i class="bx bx-printer me-1"></i>Imprimer</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="px-4 py-3">Escale</th>
                                <th class="text-center">Caisses Actives</th>
                                <th class="text-end">Billets</th>
                                <th class="text-end">Colis</th>
                                <th class="text-end">Total CA</th>
                                <th class="text-end px-4">Écarts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($rapport)): ?>
                                <tr><td colspan="6" class="text-center py-5 text-muted">Aucune donnée pour cette date.</td></tr>
                            <?php endif; ?>
                            
                            <?php foreach($rapport as $r): ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($r->localite) ?></div>
                                    <div class="small text-muted">Gare: <?= htmlspecialchars($r->numeroGare) ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border"><?= $r->nb_caisses ?></span>
                                    <div class="small text-muted mt-1" style="font-size:10px;">
                                        <span class="text-success"><?= $r->caisses_ouvertes ?> O</span> / 
                                        <span><?= $r->caisses_fermees ?> F</span>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold"><?= number_format((float)$r->total_billets, 0, ',', ' ') ?></td>
                                <td class="text-end fw-semibold"><?= number_format((float)$r->total_colis, 0, ',', ' ') ?></td>
                                <td class="text-end fw-bold text-primary fs-6 bg-light bg-opacity-50"><?= number_format((float)$r->grand_total, 0, ',', ' ') ?></td>
                                <td class="text-end px-4 fw-bold <?= $r->total_ecarts >= 0 ? ($r->total_ecarts == 0 ? 'text-muted' : 'text-success') : 'text-danger' ?>">
                                    <?= $r->total_ecarts >= 0 ? ($r->total_ecarts == 0 ? '—' : '+'.number_format((float)$r->total_ecarts, 0, ',', ' ')) : number_format((float)$r->total_ecarts, 0, ',', ' ') ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-light fw-bold text-dark">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-end">TOTAUX GÉNÉRAUX</td>
                                <td class="text-end"><?= number_format($total_billets, 0, ',', ' ') ?></td>
                                <td class="text-end"><?= number_format($total_colis, 0, ',', ' ') ?></td>
                                <td class="text-end fs-5 text-primary"><?= number_format($grand_total, 0, ',', ' ') ?></td>
                                <td class="text-end px-4 <?= $total_ecarts >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= $total_ecarts >= 0 ? '+' : '' ?><?= number_format($total_ecarts, 0, ',', ' ') ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </main>
</div>

<?php $this->view('admin/partials/foot') ?>
</body>
</html>
