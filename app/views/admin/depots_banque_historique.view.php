<?php $this->view('admin/partials/headers') ?>
<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">🔄 Historique des dépôts en banque</h4>
                <?php if (($_SESSION['droit'] ?? null) === 'Admin'): ?>
                    <a href="<?= BASE_URL ?>/admin/Depots_banque/enAttente" class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="bx bx-left-arrow-alt"></i> Retour aux demandes en attente
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/admin/Depots_banque" class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="bx bx-left-arrow-alt"></i> Retour au dépôt
                    </a>
                <?php endif; ?>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <?php
                $nbEnAttente = count(array_filter($listeDemandes, fn($d) => $d->statut === 'en_attente'));
                $nbConfirme  = count(array_filter($listeDemandes, fn($d) => $d->statut === 'confirme'));
                $nbRejete    = count(array_filter($listeDemandes, fn($d) => $d->statut === 'rejete'));
                $totalConfirme = array_sum(array_map(fn($d) => $d->statut === 'confirme' ? (float)$d->montant : 0, $listeDemandes));
            ?>

            <div class="d-flex flex-wrap gap-4 align-items-center text-muted small mb-3 px-1">
                <span><i class="bx bx-time text-warning me-1"></i><?= $nbEnAttente ?> en attente</span>
                <span class="text-secondary">|</span>
                <span><i class="bx bx-check-circle text-success me-1"></i><?= $nbConfirme ?> confirmé(s) : <strong class="text-dark"><?= number_format($totalConfirme, 0, ',', ' ') ?> F</strong></span>
                <span class="text-secondary">|</span>
                <span><i class="bx bx-x-circle text-danger me-1"></i><?= $nbRejete ?> rejetée(s)</span>
                <span class="text-secondary">|</span>
                <span><i class="bx bx-list-ul text-primary me-1"></i>Total : <strong class="text-dark"><?= count($listeDemandes) ?></strong></span>
            </div>

            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2"
                     style="background: linear-gradient(135deg, #0f3b5e, #1d6fa5); color: #fff;">
                    <i class="bx bx-list-ul fs-5"></i>
                    <span class="fw-semibold">Toutes les demandes</span>
                </div>
                <div class="table-responsive p-2">
                    <table id="example" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="border-0">Date demande</th>
                                <th class="border-0">Gare</th>
                                <th class="border-0">Banque</th>
                                <th class="border-0">Montant</th>
                                <th class="border-0">Référence</th>
                                <th class="border-0">Demandé par</th>
                                <th class="border-0">Statut</th>
                                <th class="border-0">Validé par</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listeDemandes)): ?>
                                <tr><td colspan="8" class="text-center text-muted py-4">Aucun dépôt enregistré.</td></tr>
                            <?php else: ?>
                                <?php foreach ($listeDemandes as $d): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($d->date_demande)) ?></td>
                                        <td><?= htmlspecialchars($d->localite . ' (' . $d->numeroGare . ')') ?></td>
                                        <td><?= htmlspecialchars($d->nom_banque) ?></td>
                                        <td class="fw-bold"><?= number_format($d->montant, 0, ',', ' ') ?> F</td>
                                        <td><?= htmlspecialchars($d->reference ?? '-') ?></td>
                                        <td><?= htmlspecialchars($d->demandeur) ?></td>
                                        <td>
                                            <?php if ($d->statut === 'en_attente'): ?>
                                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">En attente</span>
                                            <?php elseif ($d->statut === 'confirme'): ?>
                                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">Confirmé</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-2" title="<?= htmlspecialchars($d->motif_rejet ?? '') ?>">Rejeté</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($d->validateur ?? '-') ?></td>
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
