<?php $this->view('admin/partials/headers') ?>
<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">🏦 Mouvements — <?= htmlspecialchars($banque['nom']) ?></h4>
                <a href="<?= BASE_URL ?>/admin/Banques" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="bx bx-left-arrow-alt"></i> Retour aux comptes banque
                </a>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <?php
                $nbEntrees = count(array_filter($listeMouvements, fn($m) => $m->type === 'entree'));
                $totalEntrees = array_sum(array_map(fn($m) => $m->type === 'entree' ? (float)$m->montant : 0, $listeMouvements));
                $nbSorties = count($listeMouvements) - $nbEntrees;
                $totalSorties = array_sum(array_map(fn($m) => $m->type !== 'entree' ? (float)$m->montant : 0, $listeMouvements));
            ?>

            <div class="d-flex flex-wrap gap-4 align-items-center text-muted small mb-3 px-1">
                <span><i class="bx bx-wallet text-primary me-1"></i>Solde actuel : <strong class="text-dark"><?= number_format($banque['solde'], 0, ',', ' ') ?> F</strong></span>
                <span class="text-secondary">|</span>
                <span><i class="bx bx-down-arrow-circle text-success me-1"></i><?= $nbEntrees ?> entrée(s) : <strong class="text-dark">+<?= number_format($totalEntrees, 0, ',', ' ') ?> F</strong></span>
                <span class="text-secondary">|</span>
                <span><i class="bx bx-up-arrow-circle text-danger me-1"></i><?= $nbSorties ?> sortie(s) : <strong class="text-dark">-<?= number_format($totalSorties, 0, ',', ' ') ?> F</strong></span>
                <span class="text-secondary">|</span>
                <span>
                    <i class="bx bx-buildings text-info me-1"></i><?= htmlspecialchars($banque['numero_compte'] ?? 'N° de compte') ?>
                    <?php if ($banque['statut'] === 'active'): ?>
                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2 py-1 ms-1">Actif</span>
                    <?php else: ?>
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 ms-1">Inactif</span>
                    <?php endif; ?>
                </span>
            </div>

            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2"
                     style="background: linear-gradient(135deg, #0f3b5e, #1d6fa5); color: #fff;">
                    <i class="bx bx-list-ul fs-5"></i>
                    <span class="fw-semibold">Historique des mouvements</span>
                </div>
                <div class="table-responsive p-2">
                    <table id="example" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="border-0">Date</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Gare</th>
                                <th class="border-0">Montant</th>
                                <th class="border-0">Référence</th>
                                <th class="border-0">Demandé par</th>
                                <th class="border-0">Validé par</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listeMouvements)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">Aucun mouvement enregistré sur ce compte.</td></tr>
                            <?php else: ?>
                                <?php foreach ($listeMouvements as $m): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($m->date_validation)) ?></td>
                                        <td>
                                            <?php if ($m->type === 'entree'): ?>
                                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2"><i class="bx bx-down-arrow-circle"></i> Entrée</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-2"><i class="bx bx-up-arrow-circle"></i> Sortie</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($m->localite . ' (' . $m->numeroGare . ')') ?></td>
                                        <td class="fw-bold <?= $m->type === 'entree' ? 'text-success' : 'text-danger' ?>">
                                            <?= $m->type === 'entree' ? '+' : '-' ?><?= number_format($m->montant, 0, ',', ' ') ?> F
                                        </td>
                                        <td><?= htmlspecialchars($m->reference ?? '-') ?></td>
                                        <td><?= htmlspecialchars($m->demandeur) ?></td>
                                        <td><?= htmlspecialchars($m->validateur ?? '-') ?></td>
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
