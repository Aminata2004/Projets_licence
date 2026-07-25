<?php $this->view('admin/partials/headers') ?>

<body>
<div class="wrapper">
    <?php $this->view('admin/partials/navbar') ?>
    <?php $this->view('admin/partials/sidebar') ?>

    <main class="page-content">

        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="<?= BASE_URL ?>/admin/Caisse/ma_caisse" class="btn btn-outline-secondary btn-sm rounded-pill">← Retour</a>
            <div>
                <h4 class="fw-bold mb-0">🔓 Ouvrir ma caisse</h4>
                <p class="text-muted small mb-0">Service du <?= date('d/m/Y') ?></p>
            </div>
        </div>

        <?php $this->view("admin/set_flash") ?>

        <?php if ($existante): ?>
        <div class="alert alert-warning rounded-3 shadow-sm">
            <h6 class="fw-bold"><i class="bx bx-info-circle me-2"></i>Caisse déjà ouverte</h6>
            <p class="mb-1">Vous avez déjà une caisse active aujourd'hui : <strong><?= htmlspecialchars($existante->reference) ?></strong></p>
            <a href="<?= BASE_URL ?>/admin/Caisse/ma_caisse" class="btn btn-warning btn-sm rounded-pill">Voir ma caisse</a>
        </div>
        <?php else: ?>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-header border-bottom text-center py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="bx bx-wallet text-white" style="font-size:3rem;"></i>
                        <h5 class="fw-bold text-white mb-0 mt-2">Ouverture de caisse</h5>
                        <p class="text-white-50 small mb-0"><?= !empty($_SESSION['ville']) ? htmlspecialchars($_SESSION['ville']) . ' – ' : '' ?><?= htmlspecialchars($_SESSION['utilisateurs'] ?? '') ?></p>
                    </div>
                    <div class="card-body p-4">
                        <form method="post">
                            <?= csrf_field() ?>

                            <?php if ($estAdmin): ?>
                            <div class="mb-4">
                                <label for="id_agence" class="form-label fw-semibold">Gare concernée</label>
                                <select class="form-select" name="id_agence" id="id_agence" required>
                                    <option value="" disabled selected>Choisissez une gare</option>
                                    <?php foreach ($listeAgences as $a): ?>
                                        <option value="<?= htmlspecialchars($a->idAgence) ?>">
                                            <?= htmlspecialchars($a->localite . ' (' . $a->numeroGare . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Fond initial de caisse (FCFA)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-money"></i></span>
                                    <input type="number"
                                           class="form-control border-start-0"
                                           name="montant_initial"
                                           value="0"
                                           min="0"
                                           step="100"
                                           required
                                           placeholder="0">
                                    <span class="input-group-text bg-light">FCFA</span>
                                </div>
                                <div class="form-text">Montant en espèces disponible à l'ouverture (peut être 0).</div>
                            </div>

                            <div class="mb-3 p-3 bg-light rounded-3">
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Date du service</span>
                                    <strong><?= date('d/m/Y') ?></strong>
                                </div>
                                <div class="d-flex justify-content-between small mt-1">
                                    <span class="text-muted">Heure d'ouverture</span>
                                    <strong><?= date('H:i') ?></strong>
                                </div>
                                <?php if (!$estAdmin): ?>
                                <div class="d-flex justify-content-between small mt-1">
                                    <span class="text-muted">Agence</span>
                                    <strong><?= htmlspecialchars($_SESSION['ville'] ?? '—') ?></strong>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="ouvrir_caisse" class="btn btn-success fw-semibold rounded-pill py-2">
                                    <i class="bx bx-lock-open-alt me-2"></i>Ouvrir ma caisse
                                </button>
                                <a href="<?= BASE_URL ?>/admin/Caisse/ma_caisse" class="btn btn-outline-secondary rounded-pill">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </main>
</div>

<?php $this->view('admin/partials/foot') ?>
</body>
</html>
