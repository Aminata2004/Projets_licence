<?php $this->view('admin/partials/headers') ?>
<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">🏦 Comptes banque</h4>
                <div class="d-flex gap-2">
                    <a href="<?= BASE_URL ?>/admin/Depots_banque/enAttente" class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="bx bx-time"></i> Demandes en attente
                    </a>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalNouvelleBanque">
                        <i class="bx bx-plus me-1"></i> Nouveau compte
                    </button>
                </div>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <?php
                $nbActifs = count(array_filter($listeBanques, fn($b) => $b->statut === 'active'));
                $soldeTotal = array_sum(array_map(fn($b) => (float)$b->solde, $listeBanques));
            ?>

            <div class="d-flex flex-wrap gap-4 align-items-center text-muted small mb-3 px-1">
                <span><i class="bx bx-buildings text-primary me-1"></i><?= count($listeBanques) ?> compte(s) banque</span>
                <span class="text-secondary">|</span>
                <span><i class="bx bx-check-circle text-success me-1"></i><?= $nbActifs ?> actif(s)</span>
                <span class="text-secondary">|</span>
                <span><i class="bx bx-wallet text-info me-1"></i>Solde cumulé : <strong class="text-dark"><?= number_format($soldeTotal, 0, ',', ' ') ?> F</strong></span>
            </div>

            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2"
                     style="background: linear-gradient(135deg, #0f3b5e, #1d6fa5); color: #fff;">
                    <i class="bx bx-list-ul fs-5"></i>
                    <span class="fw-semibold">Comptes banque de la compagnie</span>
                </div>
                <div class="table-responsive p-2">
                    <table id="example" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="border-0">Nom</th>
                                <th class="border-0">N° de compte</th>
                                <th class="border-0">Solde</th>
                                <th class="border-0">Statut</th>
                                <th class="border-0">Ajouté le</th>
                                <th class="border-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listeBanques)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">Aucun compte banque enregistré.</td></tr>
                            <?php else: ?>
                                <?php foreach ($listeBanques as $b): ?>
                                    <tr>
                                        <td class="fw-semibold">
                                            <i class="bx bx-buildings text-primary me-1"></i> <?= htmlspecialchars($b->nom) ?>
                                        </td>
                                        <td><?= htmlspecialchars($b->numero_compte ?? '-') ?></td>
                                        <td class="fw-bold text-success"><?= number_format($b->solde, 0, ',', ' ') ?> F</td>
                                        <td>
                                            <?php if ($b->statut === 'active'): ?>
                                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">● Actif</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">● Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted"><?= date('d/m/Y', strtotime($b->date_creation)) ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/Banques/mouvement/<?= $b->id_banque ?>">
                                                            <i class="bx bx-transfer-alt me-2"></i>Mouvement</a></li>
                                                    <li><a class="dropdown-item" href="#"
                                                            data-bs-toggle="modal" data-bs-target="#modalEditBanque"
                                                            data-id="<?= $b->id_banque ?>"
                                                            data-nom="<?= htmlspecialchars($b->nom) ?>"
                                                            data-numero="<?= htmlspecialchars($b->numero_compte ?? '') ?>"
                                                            data-statut="<?= $b->statut ?>">
                                                            <i class="bx bx-edit me-2"></i>Modifier</a></li>
                                                </ul>
                                            </div>
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

    <!-- Modal nouveau compte banque -->
    <div class="modal fade" id="modalNouvelleBanque" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="">
                    <?= csrf_field() ?>
                    <div class="modal-header border-0" style="background: linear-gradient(135deg, #0f3b5e, #1d6fa5);">
                        <h5 class="modal-title text-white">Nouveau compte banque</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nom du compte</label>
                            <input type="text" class="form-control" name="nom" required placeholder="ex: BDM agence Bamako">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">N° de compte (optionnel)</label>
                            <input type="text" class="form-control" name="numero_compte">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="creer_banque" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- fin modal nouveau compte -->

    <!-- Modal modifier compte banque -->
    <div class="modal fade" id="modalEditBanque" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_banque" id="editIdBanque">
                    <div class="modal-header border-0" style="background: linear-gradient(135deg, #0f3b5e, #1d6fa5);">
                        <h5 class="modal-title text-white">Modifier le compte banque</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nom du compte</label>
                            <input type="text" class="form-control" name="nom" id="editNom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">N° de compte (optionnel)</label>
                            <input type="text" class="form-control" name="numero_compte" id="editNumero">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Statut</label>
                            <select class="form-select" name="statut" id="editStatut">
                                <option value="active">Actif</option>
                                <option value="inactive">Inactif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="modifier_banque" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- fin modal modifier compte -->

    <?php $this->view('admin/partials/foot') ?>

    <script>
        document.getElementById('modalEditBanque')?.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            document.getElementById('editIdBanque').value = btn.dataset.id;
            document.getElementById('editNom').value = btn.dataset.nom;
            document.getElementById('editNumero').value = btn.dataset.numero;
            document.getElementById('editStatut').value = btn.dataset.statut;
        });
    </script>
</body>
</html>
