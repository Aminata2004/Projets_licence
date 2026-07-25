<?php $this->view('admin/partials/headers') ?>

<body>
<div class="wrapper">
    <?php $this->view('admin/partials/navbar') ?>
    <?php $this->view('admin/partials/sidebar') ?>

    <main class="page-content">

        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="<?= BASE_URL ?>/admin/Caisse/ma_caisse" class="btn btn-outline-secondary btn-sm rounded-pill">← Retour</a>
            <div>
                <h4 class="fw-bold mb-0"><i class="bx bx-transfer text-primary me-1"></i>Verser au Chef d'Escale</h4>
                <p class="text-muted small mb-0">Caisse Réf: <?= htmlspecialchars($caisse->reference) ?></p>
            </div>
        </div>

        <?php $this->view("admin/set_flash") ?>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        
                        <div class="alert alert-light border text-center mb-4">
                            <div class="small text-muted mb-1">Montant compté à la fermeture</div>
                            <div class="fs-2 fw-bold text-success"><?= number_format((float)$caisse->montant_compte, 0, ',', ' ') ?> FCFA</div>
                        </div>

                        <form method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_caisse_user" value="<?= $caisse->id_caisse_user ?>">

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Chef d'escale destinataire <span class="text-danger">*</span></label>
                                <select name="id_chef_escale" class="form-select" required>
                                    <option value="">-- Sélectionner le chef d'escale --</option>
                                    <?php foreach ($chefs as $chef): ?>
                                        <option value="<?= $chef->idUser ?>"><?= htmlspecialchars($chef->utilisateurs) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(empty($chefs)): ?>
                                    <div class="text-danger small mt-1">Aucun chef d'escale configuré pour cette gare.</div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Montant à verser (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control form-control-lg fw-bold" 
                                       name="montant" 
                                       value="<?= (float)$caisse->montant_compte ?>" 
                                       min="0" 
                                       required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Commentaire (optionnel)</label>
                                <textarea name="commentaire" class="form-control" rows="2" placeholder="Détails, billets abimés, etc."></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="effectuer_versement" class="btn btn-primary btn-lg rounded-pill" <?= empty($chefs) ? 'disabled' : '' ?>>
                                    <i class="bx bx-send me-1"></i> Envoyer la demande de versement
                                </button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<?php $this->view('admin/partials/foot') ?>
</body>
</html>
