<?php $this->view('admin/partials/headers') ?>
<?php $user = new Configuration($_SESSION['id_utilisateur']) ?>

<body>
    <div class="wrapper">
        <?php $this->view('admin/partials/navbar') ?>
        <?php $this->view('admin/partials/sidebar') ?>

        <main class="page-content">
            <style>
                :root {
                    --primary: #2563eb;
                    --success: #10b981;
                    --danger: #ef4444;
                    --warning: #f59e0b;
                    --gray-100: #f1f5f9;
                    --gray-600: #475569;
                }
                /* Cartes KPI */
                .kpi-mini {
                    background: white;
                    border-radius: 1rem;
                    padding: 1rem 1.2rem;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    border-left: 4px solid;
                    transition: 0.2s;
                }
                .kpi-mini:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
                /* Cartes caisses */
                .caisse-card {
                    background: white;
                    border-radius: 1rem;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                    transition: 0.2s;
                }
                .caisse-card:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(0,0,0,0.1); }
                .caisse-header {
                    padding: 1rem 1.2rem;
                    border-bottom: 1px solid #eef2f6;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .badge-status {
                    font-size: 0.7rem;
                    font-weight: 600;
                    padding: 0.2rem 0.8rem;
                    border-radius: 2rem;
                }
                .badge-open { background: #dcfce7; color: #166534; }
                .badge-closed { background: #fee2e2; color: #991b1b; }
                .stat-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 0.7rem 1.2rem;
                    border-bottom: 1px solid #f0f2f5;
                }
                .stat-label { font-size: 0.7rem; text-transform: uppercase; color: var(--gray-600); }
                .stat-value { font-weight: 700; font-size: 1rem; }
                .total-value { font-size: 1.2rem; color: var(--primary); }
                .footer-card {
                    padding: 0.8rem 1.2rem;
                    background: #fafcff;
                    display: flex;
                    justify-content: flex-end;
                }
                .btn-cloture {
                    background: none;
                    border: 1px solid #e2e8f0;
                    border-radius: 2rem;
                    padding: 0.3rem 1rem;
                    font-size: 0.7rem;
                    transition: 0.2s;
                }
                .btn-cloture:hover { background: var(--danger); border-color: var(--danger); color: white; }
            </style>

            <!-- En-tête -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">🏦 Gestion des Caisses</h4>
                <div class="d-flex gap-2 mt-2 mt-sm-0">
                    <a href="<?= BASE_URL ?>/admin/Caisse/bilant_caisse_billets" class="btn btn-outline-primary btn-sm rounded-pill">📊 Bilan Billets</a>
                    <a href="<?= BASE_URL ?>/admin/Caisse/bilant_caisse_colis" class="btn btn-outline-secondary btn-sm rounded-pill">📦 Bilan Colis</a>
                    <?php if ($user->userHasPermission('Caisse_creation')): ?>
                        <a href="<?= BASE_URL ?>/admin/Caisse/add_caisse" class="btn btn-primary btn-sm rounded-pill px-3">+ Ouvrir une caisse</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <!-- KPI rapides -->
            <?php
                $garesOuvertes = 0; $garesFermees = 0;
                $totalBillets  = 0; $totalColis   = 0; $totalRembourse = 0;
                foreach ($liste_caisse as $c) {
                    $dr = $_SESSION['droit'];
                    $ic = $_SESSION['id_compagnie'] ?? null;
                    $vi = $_SESSION['ville'] ?? null;
                    if ($dr === 'Admin' && $c->id_compagnie != $ic) continue;
                    if ($dr === 'chef_d_escale' && ($c->id_compagnie != $ic || $c->localite != $vi)) continue;
                    
                    if ($c->status_caisse == 1) $garesOuvertes++; else $garesFermees++;
                    $totalBillets += ($c->montant_billets ?? 0);
                    $totalColis   += ($c->montant_colis ?? 0);
                    $totalRembourse += ($c->montant_rembourse ?? 0);
                }
                $grandTotal = $totalBillets + $totalColis - $totalRembourse;
            ?>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3"><div class="kpi-mini" style="border-left-color: var(--primary);"><div class="text-secondary small">Total gares</div><div class="fs-3 fw-bold"><?= count($liste_caisse) ?></div></div></div>
                <div class="col-6 col-md-3"><div class="kpi-mini" style="border-left-color: var(--success);"><div class="text-secondary small">Caisses ouvertes</div><div class="fs-3 fw-bold text-success"><?= $garesOuvertes ?></div></div></div>
                <div class="col-6 col-md-3"><div class="kpi-mini" style="border-left-color: var(--danger);"><div class="text-secondary small">Caisses fermées</div><div class="fs-3 fw-bold text-danger"><?= $garesFermees ?></div></div></div>
                <div class="col-6 col-md-3"><div class="kpi-mini" style="border-left-color: var(--warning);"><div class="text-secondary small">Total encaissé</div><div class="fs-4 fw-bold text-warning"><?= number_format($grandTotal, 0, ',', ' ') ?> FCFA</div></div></div>
            </div>

            <!-- Filtres -->
            <?php if (!empty($liste_caisse)): ?>
            <div class="bg-white p-3 rounded-3 shadow-sm mb-4 d-flex flex-wrap justify-content-between align-items-center">
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary active filter-btn" data-filter="all">Toutes</button>
                    <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="open">Ouvertes</button>
                    <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="closed">Fermées</button>
                </div>
                <div class="mt-2 mt-sm-0">
                    <input type="text" id="search-caisse" class="form-control form-control-sm rounded-pill" placeholder="🔍 Gare, référence..." style="width: 250px;">
                </div>
            </div>
            <?php endif; ?>

            <!-- Grille des caisses -->
            <?php if (empty($liste_caisse)): ?>
                <div class="alert alert-info text-center py-5">Aucune caisse enregistrée. <a href="<?= BASE_URL ?>/admin/Caisse/add_caisse">Ouvrir une caisse</a></div>
            <?php else: ?>
            <div class="row g-4" id="caisse-grid">
                <?php foreach ($liste_caisse as $caisse):
                    $droit        = $_SESSION['droit'];
                    $id_compagnie = $_SESSION['id_compagnie'] ?? null;
                    $ville        = $_SESSION['ville'] ?? null;
                    if ($droit === 'Admin' && $caisse->id_compagnie != $id_compagnie) continue;
                    if ($droit === 'chef_d_escale' && ($caisse->id_compagnie != $id_compagnie || $caisse->localite != $ville)) continue;
                    
                    $isOpen     = $caisse->status_caisse == 1;
                    $total      = ($caisse->montant_billets ?? 0) + ($caisse->montant_colis ?? 0);
                    $searchData = htmlspecialchars(strtolower($caisse->localite . ' ' . $caisse->numeroGare . ' ' . $caisse->reference_caise));
                ?>
                <div class="col-lg-6 col-xl-4 caisse-item" data-status="<?= $isOpen ? 'open' : 'closed' ?>" data-search="<?= $searchData ?>">
                    <div class="caisse-card">
                        <div class="caisse-header">
                            <div>
                                <h6 class="fw-bold mb-0"><?= htmlspecialchars($caisse->localite) ?></h6>
                                <small class="text-muted">Gare n°<?= htmlspecialchars($caisse->numeroGare) ?></small>
                            </div>
                            <span class="badge-status <?= $isOpen ? 'badge-open' : 'badge-closed' ?>">
                                <?= $isOpen ? '● Ouverte' : '● Fermée' ?>
                            </span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Fond initial</span>
                            <span class="stat-value"><?= number_format($caisse->montant_initial ?? 0, 0, ',', ' ') ?> F</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Billets</span>
                            <span class="stat-value"><?= number_format($caisse->montant_billets ?? 0, 0, ',', ' ') ?> F</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Colis</span>
                            <span class="stat-value"><?= number_format($caisse->montant_colis ?? 0, 0, ',', ' ') ?> F</span>
                        </div>
                        <div class="stat-row text-danger">
                            <span class="stat-label text-danger">Remboursements</span>
                            <span class="stat-value">-<?= number_format($caisse->montant_rembourse ?? 0, 0, ',', ' ') ?> F</span>
                        </div>
                        <?php $total = ($caisse->montant_billets ?? 0) + ($caisse->montant_colis ?? 0) - ($caisse->montant_rembourse ?? 0); ?>
                        <div class="stat-row bg-light">
                            <span class="stat-label fw-bold">Total actuel</span>
                            <span class="stat-value total-value fw-bold"><?= number_format($total, 0, ',', ' ') ?> F</span>
                        </div>
                        <div class="px-3 py-2 small text-muted">
                            <div><i class="bx bx-receipt"></i> Réf: <?= htmlspecialchars($caisse->reference_caise) ?></div>
                            <div><i class="bx bx-calendar"></i> Ouvert le <?= date('d/m/Y', strtotime($caisse->date_enregistrement)) ?></div>
                            <?php if (!$isOpen && !empty($caisse->date_fermeture)): ?>
                            <div><i class="bx bx-time"></i> Fermé le <?= date('d/m/Y', strtotime($caisse->date_fermeture)) ?></div>
                            <?php endif; ?>
                        </div>
                        <?php if ($user->userHasPermission('Caisse_apercue') && $isOpen): ?>
                        <div class="footer-card">
                            <form method="post" action="<?= BASE_URL ?>/admin/Caisse" id="form-fermer-<?= $caisse->id_caisse ?>">
                                <input type="hidden" name="id_caisse" value="<?= $caisse->id_caisse ?>">
                                <input type="hidden" name="newStatut" value="0">
                                <button type="button" class="btn-cloture" onclick="confirmClose(<?= $caisse->id_caisse ?>, '<?= addslashes($caisse->localite) ?>')">🔒 Clôturer</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmClose(id, gare) {
            Swal.fire({ title: 'Clôturer la caisse ?', text: `Caisse de ${gare} sera fermée. Plus aucune vente possible.`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', confirmButtonText: 'Oui, fermer' }).then(r => { if(r.isConfirmed) document.getElementById('form-fermer-'+id).submit(); });
        }
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active', 'btn-primary'));
                this.classList.add('active', 'btn-primary');
                let filter = this.getAttribute('data-filter');
                document.querySelectorAll('.caisse-item').forEach(item => {
                    let status = item.getAttribute('data-status');
                    item.style.display = (filter === 'all' || status === filter) ? '' : 'none';
                });
            });
        });
        document.getElementById('search-caisse')?.addEventListener('input', function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('.caisse-item').forEach(item => {
                let data = item.getAttribute('data-search');
                item.style.display = data.includes(val) ? '' : 'none';
            });
        });
    </script>
    <?php $this->view('admin/partials/foot') ?>
</body>
</html>