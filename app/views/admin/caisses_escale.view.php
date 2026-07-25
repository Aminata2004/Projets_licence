<?php $this->view('admin/partials/headers') ?>

<body>
<div class="wrapper">
    <?php $this->view('admin/partials/navbar') ?>
    <?php $this->view('admin/partials/sidebar') ?>

    <main class="page-content">

        <!-- En-tête -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="bx bx-shield-quarter text-primary me-1"></i>Supervision Escale</h4>
                <p class="text-muted small mb-0"><?= htmlspecialchars($_SESSION['ville'] ?? '') ?> – <?= date('d/m/Y', strtotime($date)) ?></p>
            </div>

            <form method="get" class="d-flex gap-2 flex-wrap">
                <?php if ($estAdmin): ?>
                    <select name="id_agence" class="form-select form-select-sm rounded-pill px-3" onchange="this.form.submit()">
                        <option value="" disabled <?= !$idAgenceSelectionnee ? 'selected' : '' ?>>Choisissez une gare</option>
                        <?php foreach ($listeAgences as $a): ?>
                            <option value="<?= htmlspecialchars($a->idAgence) ?>" <?= (int)$a->idAgence === $idAgenceSelectionnee ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a->localite . ' (' . $a->numeroGare . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <input type="date" name="date" class="form-control form-control-sm rounded-pill px-3" value="<?= htmlspecialchars($date) ?>" max="<?= date('Y-m-d') ?>">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">Filtrer</button>
            </form>
        </div>

        <?php $this->view("admin/set_flash") ?>

        <?php if ($estAdmin && !$idAgenceSelectionnee): ?>
        <div class="card border-0 shadow-sm rounded-3 text-center py-5">
            <div class="text-muted"><i class="bx bx-shield-quarter" style="font-size:4rem;"></i></div>
            <h5 class="mt-2 fw-bold">Choisissez une gare</h5>
            <p class="text-muted">Sélectionnez une gare ci-dessus pour superviser ses caisses.</p>
        </div>
        <?php else: ?>

        <!-- KPI Globaux de la journée -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 text-center py-3 border-start border-4 border-success">
                    <div class="text-muted small fw-bold text-uppercase">Total Billets</div>
                    <div class="fs-3 fw-bold text-dark mt-1"><?= number_format($total_billets, 0, ',', ' ') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 text-center py-3 border-start border-4 border-info">
                    <div class="text-muted small fw-bold text-uppercase">Total Colis</div>
                    <div class="fs-3 fw-bold text-dark mt-1"><?= number_format($total_colis, 0, ',', ' ') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 text-center py-3 border-start border-4 border-primary bg-primary bg-opacity-10">
                    <div class="text-primary small fw-bold text-uppercase">Chiffre d'affaires total</div>
                    <div class="fs-3 fw-bold text-primary mt-1"><?= number_format($grand_total, 0, ',', ' ') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 text-center py-3 border-start border-4 <?= $total_ecarts >= 0 ? 'border-success' : 'border-danger' ?>">
                    <div class="text-muted small fw-bold text-uppercase">Écarts constatés</div>
                    <div class="fs-3 fw-bold mt-1 <?= $total_ecarts >= 0 ? 'text-success' : 'text-danger' ?>">
                        <?= $total_ecarts >= 0 ? '+' : '' ?><?= number_format($total_ecarts, 0, ',', ' ') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Clôture -->
        <?php if($date === date('Y-m-d')): ?>
        <div class="d-flex justify-content-end mb-4">
            <a href="<?= BASE_URL ?>/admin/Caisse/cloture_escale?<?= http_build_query($estAdmin ? ['date' => $date, 'id_agence' => $idAgenceSelectionnee] : ['date' => $date]) ?>" class="btn btn-dark fw-bold rounded-pill px-4 shadow">
                <i class="bx bx-check-double me-1"></i> Procéder à la clôture de l'escale
            </a>
        </div>
        <?php endif; ?>

        <div class="row">
            <!-- Liste des caisses (Opérateurs) -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Caisses des opérateurs (<?= count($caisses) ?>)</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover-effect table-custom-header mobile-card-table" style="width:100%">
                                <thead class="table-light small text-center">
                                    <tr>
                                        <th>Opérateur</th>
                                        <th>Statut</th>
                                        <th class="text-end">CA Généré</th>
                                        <th class="text-end">Compté</th>
                                        <th class="text-end">Écart</th>
                                        <th>Versement</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php if(empty($caisses)): ?>
                                        <tr><td colspan="6" class="text-center py-4 text-muted">Aucune caisse pour cette date.</td></tr>
                                    <?php endif; ?>

                                    <?php foreach($caisses as $c):
                                        $caAttendu = (float)$c->montant_initial + (float)$c->total_billets + (float)$c->total_colis;
                                        $statuts = ['ouverte'=>'<span class="badge bg-success">Ouverte</span>',
                                                    'fermee'=>'<span class="badge bg-secondary">Fermée</span>',
                                                    'versee'=>'<span class="badge bg-primary">Versée</span>'];

                                        $vstatuts = ['en_attente'=>'<span class="badge bg-warning text-dark"><i class="bx bx-time"></i> Attente</span>',
                                                     'valide'=>'<span class="badge bg-success"><i class="bx bx-check"></i> Validé</span>',
                                                     'rejete'=>'<span class="badge bg-danger">Rejeté</span>'];
                                    ?>
                                    <tr>
                                        <td data-label="Opérateur">
                                            <div class="fw-bold"><?= htmlspecialchars($c->nom_operateur) ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($c->reference) ?></div>
                                        </td>
                                        <td data-label="Statut"><?= $statuts[$c->statut] ?></td>
                                        <td data-label="CA Généré" class="text-end fw-semibold"><?= number_format($caAttendu, 0, ',', ' ') ?></td>
                                        <td data-label="Compté" class="text-end text-muted"><?= isset($c->montant_compte) ? number_format((float)$c->montant_compte, 0, ',', ' ') : '—' ?></td>
                                        <td data-label="Écart" class="text-end fw-bold <?= isset($c->ecart) ? ($c->ecart >= 0 ? 'text-success' : 'text-danger') : '' ?>">
                                            <?= isset($c->ecart) ? ($c->ecart >= 0 ? '+' : '') . number_format((float)$c->ecart, 0, ',', ' ') : '—' ?>
                                        </td>
                                        <td data-label="Versement">
                                            <?php if($c->montant_verse > 0): ?>
                                                <div class="small fw-bold"><?= number_format((float)$c->montant_verse, 0, ',', ' ') ?></div>
                                                <div><?= $vstatuts[$c->statut_versement] ?? '' ?></div>
                                            <?php else: ?>
                                                <span class="text-muted small">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation des versements -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 border-top border-4 border-warning mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Versements en attente</h6>
                        <span class="badge bg-warning text-dark rounded-pill"><?= count($versements_attente) ?></span>
                    </div>
                    <div class="card-body p-0">
                        <?php if(empty($versements_attente)): ?>
                            <div class="text-center py-4 text-muted small">Aucun versement en attente.</div>
                        <?php endif; ?>
                        
                        <div class="list-group list-group-flush">
                            <?php foreach($versements_attente as $v): ?>
                            <div class="list-group-item p-3">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($v->nom_emetteur) ?></h6>
                                    <small class="text-muted"><?= date('H:i', strtotime($v->date_versement)) ?></small>
                                </div>
                                <div class="fs-4 fw-bold text-primary mb-2"><?= number_format((float)$v->montant, 0, ',', ' ') ?> FCFA</div>
                                <?php if($v->commentaire): ?>
                                    <div class="small text-muted bg-light p-2 rounded mb-2 fst-italic">"<?= htmlspecialchars($v->commentaire) ?>"</div>
                                <?php endif; ?>
                                
                                <form action="<?= BASE_URL ?>/admin/Caisse/valider_versement" method="post" class="d-flex gap-2">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_versement" value="<?= $v->id_versement ?>">
                                    <input type="hidden" name="id_agence" value="<?= htmlspecialchars($idAgenceSelectionnee) ?>">
                                    <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
                                    <button type="submit" name="action" value="valide" class="btn btn-sm btn-success flex-grow-1 fw-semibold">
                                        Valider
                                    </button>
                                    <button type="submit" name="action" value="rejete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Rejeter ce versement ?');">
                                        Rejeter
                                    </button>
                                </form>
                            </div>
                            <?php endforeach; ?>
                        </div>
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
