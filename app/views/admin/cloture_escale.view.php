<?php $this->view('admin/partials/headers') ?>

<body>
<div class="wrapper">
    <?php $this->view('admin/partials/navbar') ?>
    <?php $this->view('admin/partials/sidebar') ?>

    <main class="page-content">

        <?php $retourQs = http_build_query($estAdmin ? ['date' => $date, 'id_agence' => $idAgenceSelectionnee] : ['date' => $date]); ?>
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="<?= BASE_URL ?>/admin/Caisse/caisses_escale?<?= $retourQs ?>" class="btn btn-outline-secondary btn-sm rounded-pill">← Retour</a>
            <div>
                <h4 class="fw-bold mb-0"><i class="bx bx-lock-alt text-dark me-1"></i>Clôture de l'Escale</h4>
                <p class="text-muted small mb-0"><?= htmlspecialchars($_SESSION['ville'] ?? '') ?> – <?= date('d/m/Y', strtotime($date)) ?></p>
            </div>
        </div>

        <?php $this->view("admin/set_flash") ?>

        <?php if ($estAdmin && !$idAgenceSelectionnee): ?>
        <div class="card border-0 shadow-sm rounded-3 text-center py-5">
            <div class="text-muted"><i class="bx bx-shield-quarter" style="font-size:4rem;"></i></div>
            <h5 class="mt-2 fw-bold">Aucune gare sélectionnée</h5>
            <p class="text-muted">Retournez à la Supervision Escale et choisissez une gare avant de procéder à la clôture.</p>
        </div>
        <?php else: ?>

        <?php
        // Vérifier si toutes les caisses de la journée sont fermées ou versées
        $toutesFermees = true;
        foreach($caisses as $c) {
            if($c->statut === 'ouverte') {
                $toutesFermees = false;
                break;
            }
        }
        ?>

        <div class="row">
            <div class="col-lg-8 mx-auto">

                <?php if(!$toutesFermees): ?>
                <div class="alert alert-danger shadow-sm rounded-3 mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error-circle fs-2 me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Clôture impossible</h6>
                            <p class="mb-0 small">Certaines caisses d'opérateurs sont encore <strong>ouvertes</strong>. Tous les opérateurs doivent fermer leur caisse avant de pouvoir clôturer la journée de l'escale.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card border-0 shadow-lg rounded-3 mb-4">
                    <div class="card-header bg-dark text-white py-3">
                        <h6 class="fw-bold mb-0"><i class="bx bx-file me-2"></i>Rapport de clôture (Prévisualisation)</h6>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="small text-muted mb-1 text-uppercase fw-bold">Recettes Billets</div>
                                    <div class="fs-4 fw-bold"><?= number_format($total_billets, 0, ',', ' ') ?> FCFA</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="small text-muted mb-1 text-uppercase fw-bold">Recettes Colis</div>
                                    <div class="fs-4 fw-bold"><?= number_format($total_colis, 0, ',', ' ') ?> FCFA</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-4 border rounded-3 text-center" style="background-color: rgba(13, 110, 253, 0.05); border-color: rgba(13, 110, 253, 0.2) !important;">
                                    <div class="small text-primary mb-1 text-uppercase fw-bold">Chiffre d'Affaires Total de l'Escale</div>
                                    <div class="fs-1 fw-bold text-primary"><?= number_format($grand_total, 0, ',', ' ') ?> FCFA</div>
                                    <div class="mt-2 text-muted small">Total des écarts opérateurs : <?= $total_ecarts >= 0 ? '+' : '' ?><?= number_format($total_ecarts, 0, ',', ' ') ?> FCFA</div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning small mb-4">
                            <i class="bx bx-info-circle me-1"></i> <strong>Attention :</strong> La clôture est définitive. Elle génère le rapport officiel qui sera transmis à l'Administration Générale et aux propriétaires. Assurez-vous d'avoir validé tous les versements des opérateurs.
                        </div>

                        <form method="post">
                            <?= csrf_field() ?>
                            <?php if ($estAdmin): ?>
                                <input type="hidden" name="id_agence" value="<?= htmlspecialchars($idAgenceSelectionnee) ?>">
                            <?php endif; ?>
                            <input type="hidden" name="date_cloture" value="<?= htmlspecialchars($date) ?>">
                            <div class="d-grid">
                                <button type="submit" name="cloturer" class="btn btn-dark btn-lg fw-bold rounded-pill shadow" <?= !$toutesFermees ? 'disabled' : '' ?>>
                                    <i class="bx bx-lock-alt me-1"></i> Valider la clôture du <?= date('d/m/Y', strtotime($date)) ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Historique récent -->
                <?php if(!empty($historique_clotures)): ?>
                <h6 class="fw-bold mb-3 text-muted text-uppercase small">Historique des clôtures récentes</h6>
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="list-group list-group-flush">
                        <?php foreach($historique_clotures as $h): ?>
                        <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold"><i class="bx bx-calendar me-2 text-muted"></i><?= date('d/m/Y', strtotime($h->date_cloture)) ?></div>
                                <div class="small text-muted mt-1">Clôturé le <?= date('d/m/Y H:i', strtotime($h->date_creation)) ?></div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary"><?= number_format((float)$h->total_billets + (float)$h->total_colis, 0, ',', ' ') ?> FCFA</div>
                                <span class="badge bg-success"><i class="bx bx-check"></i> Validée</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <?php endif; ?>

    </main>
</div>

<?php $this->view('admin/partials/foot') ?>
</body>
</html>
