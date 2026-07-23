<?php $this->view('admin/partials/headers') ?>
<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">🏦 Dépôt en banque</h4>
                <a href="<?= BASE_URL ?>/admin/Depots_banque/historique" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="bx bx-history"></i> Historique complet
                </a>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <div class="card border-0 shadow rounded-4 overflow-hidden mb-4">
                <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2"
                     style="background: linear-gradient(135deg, #0f3b5e, #1d6fa5); color: #fff;">
                    <i class="bx bx-plus-circle fs-5"></i>
                    <span class="fw-semibold">Nouvelle demande de dépôt</span>
                </div>
                <div class="card-body">
                    <?php if (empty($listeBanques)): ?>
                        <p class="text-muted mb-0">Aucun compte banque actif n'est disponible pour l'instant. Contactez votre admin.</p>
                    <?php else: ?>
                        <form action="" method="post" class="row g-3">
                            <?= csrf_field() ?>
                            <?php if (($_SESSION['droit'] ?? null) === 'Admin'): ?>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Gare concernée</label>
                                    <select class="form-select" name="id_agence" required>
                                        <option value="" disabled selected>Choisir la gare</option>
                                        <?php foreach ($listeAgences as $agence): ?>
                                            <option value="<?= htmlspecialchars($agence->idAgence) ?>">
                                                <?= htmlspecialchars($agence->localite . ' ( ' . $agence->numeroGare . ' )') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted">Le montant sera déduit de la caisse ouverte de cette gare.</small>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Compte banque</label>
                                <select class="form-select" name="id_banque" required>
                                    <option value="" disabled selected>Choisir un compte</option>
                                    <?php foreach ($listeBanques as $b): ?>
                                        <option value="<?= $b->id_banque ?>"><?= htmlspecialchars($b->nom) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Montant à déposer</label>
                                <div class="input-group">
                                    <input type="number" min="1" step="1" class="form-control" name="montant" required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Référence (optionnel)</label>
                                <input type="text" class="form-control" name="reference" placeholder="n° de bordereau">
                            </div>
                            <div class="col-12">
                                <button type="submit" name="creer_demande" class="btn btn-success">
                                    <i class="bx bx-send me-1"></i> Envoyer la demande
                                </button>
                                <?php if (($_SESSION['droit'] ?? null) === 'chef_d_escale'): ?>
                                    <small class="text-muted d-block mt-2">La demande sera soumise à l'admin. L'argent ne sort de votre caisse qu'après sa validation.</small>
                                <?php else: ?>
                                    <small class="text-muted d-block mt-2">L'argent ne sort de la caisse qu'après confirmation depuis "Demandes en attente".</small>
                                <?php endif; ?>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2"
                     style="background: linear-gradient(135deg, #0f3b5e, #1d6fa5); color: #fff;">
                    <i class="bx bx-list-ul fs-5"></i>
                    <span class="fw-semibold"><?= ($_SESSION['droit'] ?? null) === 'chef_d_escale' ? 'Mes demandes' : 'Demandes récentes' ?></span>
                </div>
                <div class="table-responsive p-2">
                    <table id="example" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="border-0">Date</th>
                                <?php if (($_SESSION['droit'] ?? null) === 'Admin'): ?><th class="border-0">Gare</th><?php endif; ?>
                                <th class="border-0">Banque</th>
                                <th class="border-0">Montant</th>
                                <th class="border-0">Référence</th>
                                <th class="border-0">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listeDemandes)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">Aucune demande enregistrée.</td></tr>
                            <?php else: ?>
                                <?php foreach ($listeDemandes as $d): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($d->date_demande)) ?></td>
                                        <?php if (($_SESSION['droit'] ?? null) === 'Admin'): ?>
                                            <td><?= htmlspecialchars($d->localite . ' (' . $d->numeroGare . ')') ?></td>
                                        <?php endif; ?>
                                        <td><?= htmlspecialchars($d->nom_banque) ?></td>
                                        <td class="fw-bold"><?= number_format($d->montant, 0, ',', ' ') ?> F</td>
                                        <td><?= htmlspecialchars($d->reference ?? '-') ?></td>
                                        <td>
                                            <?php if ($d->statut === 'en_attente'): ?>
                                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">En attente</span>
                                            <?php elseif ($d->statut === 'confirme'): ?>
                                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">Confirmé</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-2" title="<?= htmlspecialchars($d->motif_rejet ?? '') ?>">Rejeté</span>
                                            <?php endif; ?>
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

    <?php $this->view('admin/partials/foot') ?>
</body>
</html>
