<?php $this->view('admin/partials/headers') ?>
<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">💸 Gestion des dépenses</h4>
                <?php if (($_SESSION['droit'] ?? null) === 'Admin'): ?>
                    <a href="<?= BASE_URL ?>/admin/Depenses/benefice" class="btn btn-outline-success btn-sm rounded-pill">📈 Voir le bénéfice</a>
                <?php endif; ?>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bx bx-plus-circle me-1"></i> Enregistrer une dépense
                </div>
                <div class="card-body">
                    <form action="" method="post" class="row g-3">

                        <?php if (($_SESSION['droit'] ?? null) === 'Admin'): ?>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Portée de la dépense</label>
                                <select class="form-select" id="portee" name="portee">
                                    <option value="locale">Locale (une gare précise)</option>
                                    <option value="globale">Globale (compagnie)</option>
                                </select>
                            </div>
                            <div class="col-md-4" id="champGare">
                                <label class="form-label fw-semibold">Gare concernée</label>
                                <select class="form-select" name="id_agence">
                                    <option value="" disabled selected>Choisir la gare</option>
                                    <?php foreach ($listeAgences as $agence): ?>
                                        <option value="<?= htmlspecialchars($agence->idAgence) ?>">
                                            <?= htmlspecialchars($agence->localite . ' ( ' . $agence->numeroGare . ' )') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">La dépense sera déduite de la caisse ouverte de cette gare.</small>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="portee" value="locale">
                        <?php endif; ?>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Catégorie</label>
                            <select class="form-select" id="categorie" name="categorie" required>
                                <option value="" disabled selected>Choisir une catégorie</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Montant</label>
                            <div class="input-group">
                                <input type="number" min="1" class="form-control" name="montant" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Date de la dépense</label>
                            <input type="date" class="form-control" name="date_depense" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Libellé <span id="libelleHint" class="text-muted small"></span></label>
                            <input type="text" class="form-control" id="libelle" name="libelle" placeholder="Précision sur la dépense (ex: achat imprimante guichet)">
                        </div>

                        <div class="col-12">
                            <button type="submit" name="save_depense" class="btn btn-success">
                                <i class="bx bx-save me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header fw-bold">
                    <i class="bx bx-list-ul me-1"></i> Historique des dépenses
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Catégorie</th>
                                <th>Libellé</th>
                                <th>Gare</th>
                                <th>Montant</th>
                                <th>Enregistré par</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listeDepenses)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">Aucune dépense enregistrée.</td></tr>
                            <?php else: ?>
                                <?php foreach ($listeDepenses as $d): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($d->date_depense)) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($d->categorie) ?></span></td>
                                        <td><?= htmlspecialchars($d->libelle ?? '-') ?></td>
                                        <td>
                                            <?php if (!empty($d->localite)): ?>
                                                <?= htmlspecialchars($d->localite . ' (' . $d->numeroGare . ')') ?>
                                            <?php else: ?>
                                                <span class="badge bg-dark">Globale (compagnie)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-danger">-<?= number_format($d->montant, 0, ',', ' ') ?> F</td>
                                        <td><?= htmlspecialchars($d->agent ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
        const porteeSelect = document.getElementById('portee');
        const champGare = document.getElementById('champGare');
        const categorieSelect = document.getElementById('categorie');
        const libelleInput = document.getElementById('libelle');
        const libelleHint = document.getElementById('libelleHint');

        function toggleChampGare() {
            if (!porteeSelect || !champGare) return;
            champGare.style.display = porteeSelect.value === 'globale' ? 'none' : '';
        }

        function toggleLibelleHint() {
            const isAutre = categorieSelect.value === 'Autre';
            libelleHint.textContent = isAutre ? '(obligatoire pour "Autre")' : '';
            libelleInput.required = isAutre;
        }

        porteeSelect?.addEventListener('change', toggleChampGare);
        categorieSelect?.addEventListener('change', toggleLibelleHint);
        toggleChampGare();
        toggleLibelleHint();
    </script>

    <?php $this->view('admin/partials/foot') ?>
</body>
</html>
