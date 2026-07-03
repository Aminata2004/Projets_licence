<?php $this->view('admin/partials/headers') ?>

<body>
<div class="wrapper">
  <?php $this->view('admin/partials/navbar') ?>
  <?php $this->view('admin/partials/sidebar') ?>

  <main class="page-content">

    <!-- EN-TÊTE DE BIENVENUE MODERNE -->
    <div class="welcome-header mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="welcome-icon">
            <?php if ($_SESSION['droit'] === 'Admin'): ?>
              <i class="bi bi-building"></i>
            <?php elseif ($_SESSION['droit'] === 'chef_d_escale'): ?>
              <i class="bi bi-geo-alt-fill"></i>
            <?php else: ?>
              <i class="bi bi-person-fill"></i>
            <?php endif; ?>
          </div>
          <div>
            <h4 class="fw-bold mb-0">
              <?php if ($_SESSION['droit'] === 'Admin'): ?>
                Tableau de bord administrateur
              <?php elseif ($_SESSION['droit'] === 'chef_d_escale'): ?>
                Gestion de la gare – <?= htmlspecialchars($_SESSION['ville']) ?>
              <?php else: ?>
                Espace personnel
              <?php endif; ?>
            </h4>
            <p class="text-white-50 small mb-0">
              <?php if ($_SESSION['droit'] === 'Admin'): ?>
                Vue globale de l'ensemble des gares et activités
              <?php elseif ($_SESSION['droit'] === 'chef_d_escale'): ?>
                Suivez en temps réel les opérations de votre gare
              <?php else: ?>
                Consultez vos performances et réservations
              <?php endif; ?>
            </p>
          </div>
        </div>
        <div class="text-end">
          <div class="small text-white-50">Aujourd'hui</div>
          <div class="fw-bold" id="currentDate"></div>
        </div>
      </div>
    </div>

    <!-- ACTIONS RAPIDES -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="dashboard-card p-3">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span class="text-muted small fw-semibold"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Actions rapides :</span>
            <div class="d-flex flex-wrap gap-2">
              <a href="#" class="quick-action-btn"><i class="bi bi-plus-circle me-1"></i> Nouvelle vente</a>
              <a href="#" class="quick-action-btn"><i class="bi bi-truck me-1"></i> Suivi colis</a>
              <a href="#" class="quick-action-btn"><i class="bi bi-file-earmark-text me-1"></i> Rapports</a>
              <a href="#" class="quick-action-btn"><i class="bi bi-gear me-1"></i> Paramètres</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- KPI CARTES (billets & voyages) -->
    <div class="row g-4 mb-4">
      <div class="col-sm-6 col-lg-3">
        <div class="kpi-card" style="border-left-color: var(--accent);">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-value text-primary"><?= htmlspecialchars($data['billetsJour']['presentiel']); ?></div>
              <div class="kpi-label">Billets en présentiel</div>
            </div>
            <div class="bg-primary bg-opacity-10 p-2 rounded-3">
              <i class="bi bi-person-badge text-primary fs-4"></i>
            </div>
          </div>
          <small class="text-muted"><i class="bi bi-calendar-day"></i> Aujourd'hui</small>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="kpi-card" style="border-left-color: var(--success);">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-value text-success"><?= htmlspecialchars($data['billetsJour']['en_ligne']); ?></div>
              <div class="kpi-label">Billets en ligne validés</div>
            </div>
            <div class="bg-success bg-opacity-10 p-2 rounded-3">
              <i class="bi bi-laptop text-success fs-4"></i>
            </div>
          </div>
          <small class="text-muted"><i class="bi bi-calendar-day"></i> Aujourd'hui</small>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="kpi-card" style="border-left-color: var(--warning);">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-value text-warning"><?= htmlspecialchars($data['billetsJour']['en_attente']); ?></div>
              <div class="kpi-label">En attente de validation</div>
            </div>
            <div class="bg-warning bg-opacity-10 p-2 rounded-3">
              <i class="bi bi-hourglass-split text-warning fs-4"></i>
            </div>
          </div>
          <small class="text-muted"><i class="bi bi-calendar-day"></i> Aujourd'hui</small>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="kpi-card" style="border-left-color: var(--info);">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-value text-info"><?= htmlspecialchars($data['voyagesJour']); ?></div>
              <div class="kpi-label">Voyages programmés</div>
            </div>
            <div class="bg-info bg-opacity-10 p-2 rounded-3">
              <i class="bi bi-bus-front text-info fs-4"></i>
            </div>
          </div>
          <small class="text-muted"><i class="bi bi-calendar-day"></i> Aujourd'hui</small>
        </div>
      </div>
    </div>

    <!-- SECTION COLIS (KPI colis) -->
    <div class="row g-4 mb-4">
      <div class="col-md-6 col-lg-3">
        <div class="kpi-card" style="border-left-color: var(--accent);">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-value text-primary"><?= htmlspecialchars($data['colisMensuel']['prise_en_charge']); ?></div>
              <div class="kpi-label">Colis pris en charge</div>
            </div>
            <div class="bg-primary bg-opacity-10 p-2 rounded-3">
              <i class="bi bi-box-seam text-primary fs-4"></i>
            </div>
          </div>
          <small class="text-muted"><i class="bi bi-calendar-day"></i> Aujourd'hui</small>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="kpi-card" style="border-left-color: var(--warning);">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-value text-warning"><?= htmlspecialchars($data['colisMensuel']['en_cours']); ?></div>
              <div class="kpi-label">Colis en cours</div>
            </div>
            <div class="bg-warning bg-opacity-10 p-2 rounded-3">
              <i class="bi bi-truck text-warning fs-4"></i>
            </div>
          </div>
          <small class="text-muted"><i class="bi bi-calendar-day"></i> Aujourd'hui</small>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="kpi-card" style="border-left-color: var(--success);">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-value text-success"><?= htmlspecialchars($data['colisMensuel']['recu'] ?? 0); ?></div>
              <div class="kpi-label">Colis reçus</div>
            </div>
            <div class="bg-success bg-opacity-10 p-2 rounded-3">
              <i class="bi bi-inbox text-success fs-4"></i>
            </div>
          </div>
          <small class="text-muted"><i class="bi bi-calendar-day"></i> Aujourd'hui</small>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="kpi-card" style="border-left-color: var(--info);">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-value text-info"><?= htmlspecialchars($data['colisMensuel']['livre']); ?></div>
              <div class="kpi-label">Colis livrés</div>
            </div>
            <div class="bg-info bg-opacity-10 p-2 rounded-3">
              <i class="bi bi-check2-circle text-info fs-4"></i>
            </div>
          </div>
          <small class="text-muted"><i class="bi bi-calendar-day"></i> Aujourd'hui</small>
        </div>
      </div>
    </div>

    <!-- SECTION STATISTIQUES (admin : top gares / user : graphique) -->
    <div class="row mb-4">
      <?php if ($_SESSION['droit'] === 'Admin' && !empty($data['topGares'])): ?>
        <div class="col-12">
          <div class="dashboard-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
              <div>
                <h5 class="fw-bold mb-0"><i class="bi bi-trophy-fill text-warning me-2"></i>Top des gares – Ce mois</h5>
                <p class="text-muted small mb-0">Classement par nombre de billets vendus</p>
              </div>
              <span class="badge bg-light text-dark px-3 py-2 rounded-pill"><i class="bi bi-calendar-month"></i> <?= date('F Y') ?></span>
            </div>
            <div class="row g-4">
              <?php 
              $maxBillets = max(array_column($data['topGares'], 'total_billets')); 
              foreach ($data['topGares'] as $index => $gare): 
                $percent = ($maxBillets > 0) ? ($gare['total_billets'] / $maxBillets) * 100 : 0;
              ?>
              <div class="col-md-6">
                <div class="d-flex justify-content-between mb-1">
                  <span class="fw-semibold"><i class="bi bi-geo-alt-fill text-muted me-2"></i><?= htmlspecialchars($gare['gare']) ?></span>
                  <span class="badge bg-primary rounded-pill"><?= number_format($gare['total_billets']) ?> billets</span>
                </div>
                <div class="progress-custom">
                  <div class="progress-bar-custom" style="width: <?= $percent ?>%;"></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php elseif ($_SESSION['droit'] !== 'Admin'): ?>
        <div class="col-12">
          <div class="dashboard-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-graph-up text-primary me-2"></i>Évolution des ventes</h5>
            <div class="chart-placeholder">
              <i class="bi bi-bar-chart-steps fs-1 me-2"></i> Graphique des ventes (intégration ApexCharts possible)
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- STATISTIQUES COLIS (graphiques) -->
    <div class="row mb-4">
      <div class="col-lg-8">
        <div class="dashboard-card p-4">
          <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart-steps text-info me-2"></i>Statistiques colis (livrés / non livrés)</h5>
          <div class="chart-placeholder" style="height: 200px;">
            <i class="bi bi-pie-chart fs-1 me-2"></i> Graphique des colis
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="dashboard-card p-4">
          <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart-fill text-success me-2"></i>Répartition colis</h5>
          <div class="mb-3">
            <div class="d-flex justify-content-between small mb-1">
              <span><i class="bi bi-circle-fill text-primary"></i> Nouveaux</span>
              <span>25%</span>
            </div>
            <div class="progress-custom">
              <div class="progress-bar-custom bg-primary" style="width: 25%; background: var(--accent);"></div>
            </div>
          </div>
          <div class="mb-3">
            <div class="d-flex justify-content-between small mb-1">
              <span><i class="bi bi-circle-fill text-warning"></i> Complétés</span>
              <span>65%</span>
            </div>
            <div class="progress-custom">
              <div class="progress-bar-custom" style="width: 65%;"></div>
            </div>
          </div>
          <div class="mb-3">
            <div class="d-flex justify-content-between small mb-1">
              <span><i class="bi bi-circle-fill text-success"></i> En attente</span>
              <span>10%</span>
            </div>
            <div class="progress-custom">
              <div class="progress-bar-custom bg-success" style="width: 10%; background: var(--success);"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ACTIVITÉS RÉCENTES -->
    <div class="row">
      <div class="col-12">
        <div class="dashboard-card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-secondary me-2"></i>Activités récentes</h5>
            <a href="#" class="text-decoration-none small">Voir tout <i class="bi bi-arrow-right"></i></a>
          </div>
          <div>
            <div class="activity-item">
              <div class="activity-badge"><i class="bi bi-cart-plus"></i></div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between">
                  <span class="fw-semibold">Nouvelle vente en ligne</span>
                  <small class="text-muted">Il y a 5 min</small>
                </div>
                <p class="text-muted small mb-0">Billet #BL-2412 réservé par Aminata Diallo</p>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-badge"><i class="bi bi-truck"></i></div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between">
                  <span class="fw-semibold">Colis livré</span>
                  <small class="text-muted">Il y a 1 heure</small>
                </div>
                <p class="text-muted small mb-0">Colis #COL-987 réceptionné à la gare de Ségou</p>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-badge"><i class="bi bi-bus-front"></i></div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between">
                  <span class="fw-semibold">Départ programmé</span>
                  <small class="text-muted">Il y a 3 heures</small>
                </div>
                <p class="text-muted small mb-0">Bus Trans Mali Express destination Mopti (10h00)</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>

  <div class="overlay nav-toggle-icon"></div>
  <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
</div>

<?php $this->view('admin/partials/foot') ?>

<script>
  // Date dynamique
  function updateDate() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('currentDate').innerHTML = now.toLocaleDateString('fr-FR', options);
  }
  updateDate();
</script>

</body>
</html>