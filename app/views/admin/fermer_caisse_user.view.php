<?php $this->view('admin/partials/headers') ?>

<body>
<div class="wrapper">
    <?php $this->view('admin/partials/navbar') ?>
    <?php $this->view('admin/partials/sidebar') ?>

    <main class="page-content">

        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="<?= BASE_URL ?>/admin/Caisse/ma_caisse" class="btn btn-outline-secondary btn-sm rounded-pill">← Retour</a>
            <div>
                <h4 class="fw-bold mb-0"><i class="bx bx-lock-alt text-warning me-1"></i>Fermer ma caisse</h4>
                <p class="text-muted small mb-0">Réf: <?= htmlspecialchars($caisse->reference) ?></p>
            </div>
        </div>

        <?php $this->view("admin/set_flash") ?>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-header border-bottom text-center py-4 bg-light">
                        <i class="bx bx-lock-alt" style="font-size:3rem;"></i>
                        <h5 class="fw-bold mb-0 mt-2">Clôture de la journée</h5>
                    </div>
                    <div class="card-body p-4">

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded text-center">
                                    <div class="small text-muted">Total Billets</div>
                                    <div class="fw-bold text-success fs-5"><?= number_format((float)$caisse->total_billets, 0, ',', ' ') ?></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded text-center">
                                    <div class="small text-muted">Total Colis</div>
                                    <div class="fw-bold text-info fs-5"><?= number_format((float)$caisse->total_colis, 0, ',', ' ') ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 text-center mb-4">
                            <div class="small text-muted mb-1">Montant attendu (Initial + Billets + Colis)</div>
                            <div class="fs-2 fw-bold text-primary"><?= number_format($montant_attendu, 0, ',', ' ') ?> FCFA</div>
                        </div>

                        <form method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_caisse_user" value="<?= $caisse->id_caisse_user ?>">

                            <div class="mb-4">
                                <label class="form-label fw-bold">Montant réel compté dans la caisse <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0"><i class="bx bx-money"></i></span>
                                    <input type="number"
                                           class="form-control border-start-0 fs-4 fw-bold text-primary"
                                           name="montant_compte"
                                           id="montant_compte"
                                           value=""
                                           min="0"
                                           required
                                           placeholder="Ex: <?= $montant_attendu ?>">
                                    <span class="input-group-text bg-light">FCFA</span>
                                </div>
                                <div class="form-text mt-2 text-muted">
                                    Comptez physiquement votre caisse et saisissez le montant exact. L'écart sera calculé automatiquement.
                                </div>
                            </div>
                            
                            <div id="ecart_preview" class="alert d-none text-center rounded-3 mb-4">
                                <div class="small fw-semibold mb-1">Écart calculé</div>
                                <div id="ecart_valeur" class="fs-3 fw-bold">0 FCFA</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="fermer_caisse" class="btn btn-warning btn-lg fw-bold rounded-pill">
                                    Confirmer la fermeture
                                </button>
                                <a href="<?= BASE_URL ?>/admin/Caisse/ma_caisse" class="btn btn-outline-secondary rounded-pill">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<?php $this->view('admin/partials/foot') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputCompte = document.getElementById('montant_compte');
    const previewBox = document.getElementById('ecart_preview');
    const valeurBox = document.getElementById('ecart_valeur');
    const attendu = <?= $montant_attendu ?>;

    inputCompte.addEventListener('input', function() {
        const compte = parseFloat(this.value);
        if(isNaN(compte)) {
            previewBox.classList.add('d-none');
            return;
        }

        const ecart = compte - attendu;
        previewBox.classList.remove('d-none', 'alert-success', 'alert-warning', 'alert-danger');
        
        let formattedEcart = new Intl.NumberFormat('fr-FR').format(Math.abs(ecart)) + ' FCFA';

        if(ecart === 0) {
            previewBox.classList.add('alert-success');
            valeurBox.textContent = 'Parfait (Aucun écart)';
        } else if (ecart > 0) {
            previewBox.classList.add('alert-info');
            valeurBox.textContent = 'Excédent : +' + formattedEcart;
        } else {
            previewBox.classList.add('alert-danger');
            valeurBox.textContent = 'Déficit : -' + formattedEcart;
        }
    });
});
</script>
</body>
</html>
