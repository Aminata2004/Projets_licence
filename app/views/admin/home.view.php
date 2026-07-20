<?php $this->view('admin/partials/headers') ?>

<body>
<div class="wrapper">
  <?php $this->view('admin/partials/navbar') ?>
  <?php $this->view('admin/partials/sidebar') ?>

  <main class="page-content">

    <!-- HERO DE BIENVENUE -->
    <div class="tg-hero mb-4">
      <div class="tg-hero__content d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="tg-hero__icon">
            <?php if ($_SESSION['droit'] === 'super_admin'): ?>
              <i class="bi bi-diagram-3-fill"></i>
            <?php elseif ($_SESSION['droit'] === 'Admin'): ?>
              <i class="bi bi-building"></i>
            <?php elseif ($_SESSION['droit'] === 'chef_d_escale'): ?>
              <i class="bi bi-geo-alt-fill"></i>
            <?php elseif (($_SESSION['profile'] ?? null) === 'billet'): ?>
              <i class="bi bi-ticket-perforated-fill"></i>
            <?php elseif (($_SESSION['profile'] ?? null) === 'colis'): ?>
              <i class="bi bi-box-seam-fill"></i>
            <?php else: ?>
              <i class="bi bi-person-fill"></i>
            <?php endif; ?>
          </div>
          <div>
            <h4 class="tg-hero__title mb-0">
              <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                Tableau de bord plateforme
              <?php elseif ($_SESSION['droit'] === 'Admin'): ?>
                Tableau de bord administrateur
                <?php if (!empty($gareLabel)): ?> — <?= htmlspecialchars($gareLabel) ?><?php endif; ?>
              <?php elseif ($_SESSION['droit'] === 'chef_d_escale'): ?>
                Gestion de la gare – <?= htmlspecialchars($_SESSION['ville']) ?>
              <?php elseif (($_SESSION['profile'] ?? null) === 'billet'): ?>
                Espace billetterie
              <?php elseif (($_SESSION['profile'] ?? null) === 'colis'): ?>
                Espace colis & courrier
              <?php else: ?>
                Espace personnel
              <?php endif; ?>
            </h4>
            <p class="tg-hero__subtitle">
              <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                Vue globale de l'ensemble des compagnies
              <?php elseif ($_SESSION['droit'] === 'Admin'): ?>
                <?= !empty($gareLabel) ? "Activité de la gare de " . htmlspecialchars($gareLabel) : "Vue globale de l'ensemble des gares et activités" ?>
              <?php elseif ($_SESSION['droit'] === 'chef_d_escale'): ?>
                Suivez en temps réel les opérations de votre gare
              <?php else: ?>
                Consultez vos performances et réservations
              <?php endif; ?>
            </p>
          </div>
        </div>
        <div class="tg-hero__date-chip">
          <div class="tg-date-label">Aujourd'hui</div>
          <div class="tg-date-value" id="currentDate"></div>
        </div>
      </div>
    </div>

    <?php if ($mode === 'plateforme'): ?>

      <!-- VUE PLATEFORME (super_admin) -->
      <div class="tg-stat-grid mb-4">
        <a href="<?= BASE_URL ?>/admin/Compagnies" class="tg-stat-card" style="--tg-stat-color: var(--accent);">
          <div class="tg-stat-card__top">
            <div>
              <div class="tg-stat-card__value"><?= htmlspecialchars($platformStats['totalCompagnies']); ?></div>
              <div class="tg-stat-card__label">Compagnies</div>
            </div>
            <div class="tg-stat-card__icon"><i class="bi bi-building"></i></div>
          </div>
        </a>
        <a href="<?= BASE_URL ?>/admin/Compagnies" class="tg-stat-card" style="--tg-stat-color: var(--info);">
          <div class="tg-stat-card__top">
            <div>
              <div class="tg-stat-card__value"><?= htmlspecialchars($platformStats['totalGares']); ?></div>
              <div class="tg-stat-card__label">Gares</div>
            </div>
            <div class="tg-stat-card__icon"><i class="bi bi-geo-alt-fill"></i></div>
          </div>
        </a>
        <a href="<?= BASE_URL ?>/admin/Compagnies" class="tg-stat-card" style="--tg-stat-color: var(--success);">
          <div class="tg-stat-card__top">
            <div>
              <div class="tg-stat-card__value"><?= htmlspecialchars($platformStats['totalUtilisateurs']); ?></div>
              <div class="tg-stat-card__label">Utilisateurs actifs</div>
            </div>
            <div class="tg-stat-card__icon"><i class="bi bi-people-fill"></i></div>
          </div>
        </a>
        <a href="<?= BASE_URL ?>/admin/Compagnies" class="tg-stat-card" style="--tg-stat-color: var(--warning);">
          <div class="tg-stat-card__top">
            <div>
              <div class="tg-stat-card__value"><?= htmlspecialchars($platformStats['totalBilletsJour']); ?></div>
              <div class="tg-stat-card__label">Billets vendus aujourd'hui</div>
            </div>
            <div class="tg-stat-card__icon"><i class="bi bi-ticket-perforated"></i></div>
          </div>
        </a>
      </div>

      <div class="row mb-4">
        <div class="col-12">
          <div class="tg-panel">
            <div class="d-flex align-items-center mb-3">
              <span class="tg-panel__icon" style="background: rgba(59,130,246,0.12); color: var(--accent);"><i class="bi bi-building"></i></span>
              <div>
                <h5 class="tg-panel__title">Activité par compagnie</h5>
                <p class="tg-panel__subtitle">Vue consolidée toutes compagnies confondues</p>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead>
                  <tr>
                    <th>Compagnie</th>
                    <th>Gares</th>
                    <th>Utilisateurs actifs</th>
                    <th>Billets aujourd'hui</th>
                    <th>Colis ce mois</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($compagniesOverview)): ?>
                    <tr><td colspan="5" class="text-center text-muted">Aucune compagnie enregistrée</td></tr>
                  <?php else: ?>
                    <?php foreach ($compagniesOverview as $compagnie): ?>
                      <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($compagnie['nom_compagnie']) ?></td>
                        <td><?= (int)$compagnie['nb_gares'] ?></td>
                        <td><?= (int)$compagnie['nb_utilisateurs'] ?></td>
                        <td><?= (int)$compagnie['billets_jour'] ?></td>
                        <td><?= (int)$compagnie['colis_mois'] ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    <?php else: ?>

      <!-- FILTRE PAR GARE (Admin uniquement) -->
      <?php if ($_SESSION['droit'] === 'Admin' && !empty($listeGares)): ?>
        <div class="tg-filter-bar mb-4">
          <label for="gareSelectFiltre" class="fw-semibold small text-muted mb-0">
            <i class="bi bi-funnel-fill me-1"></i> Filtrer par gare
          </label>
          <form method="get" class="d-flex flex-wrap align-items-center gap-2 mb-0">
            <select name="gare" id="gareSelectFiltre" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="">Toutes les gares (vue globale)</option>
              <?php foreach ($listeGares as $gare): ?>
                <option value="<?= htmlspecialchars($gare->idAgence) ?>" <?= ((string)$gareId === (string)$gare->idAgence) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($gare->localite . ' (' . $gare->numeroGare . ')') ?>
                </option>
              <?php endforeach; ?>
            </select>
          </form>
          <?php if (!empty($gareId)): ?>
            <a href="?" class="tg-reset-pill"><i class="bi bi-x-circle me-1"></i>Réinitialiser</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- ACTIONS RAPIDES -->
      <?php if ($showBillets || $showColis): ?>
        <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
          <span class="tg-section-label mb-0 me-2"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>Actions rapides</span>
          <?php if ($showBillets): ?>
            <a href="<?= BASE_URL ?>/admin/Add_billets" class="tg-quick-action"><i class="bi bi-plus-circle"></i> Nouvelle vente</a>
          <?php endif; ?>
          <?php if ($showColis): ?>
            <a href="<?= BASE_URL ?>/admin/Suivis_colis" class="tg-quick-action"><i class="bi bi-truck"></i> Suivi colis</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- KPI BILLETS -->
      <?php if ($showBillets): ?>
        <div class="tg-section-label"><i class="bi bi-ticket-perforated me-1"></i>Billetterie — Aujourd'hui</div>
        <div class="tg-stat-grid mb-4">
          <a href="<?= BASE_URL ?>/admin/Liste_du_jours" class="tg-stat-card" style="--tg-stat-color: var(--accent);">
            <div class="tg-stat-card__top">
              <div>
                <div class="tg-stat-card__value"><?= htmlspecialchars($billetsJour['presentiel']); ?></div>
                <div class="tg-stat-card__label">Billets en présentiel</div>
              </div>
              <div class="tg-stat-card__icon"><i class="bi bi-person-badge"></i></div>
            </div>
          </a>
          <a href="<?= BASE_URL ?>/admin/Liste_du_jours" class="tg-stat-card" style="--tg-stat-color: var(--success);">
            <div class="tg-stat-card__top">
              <div>
                <div class="tg-stat-card__value"><?= htmlspecialchars($billetsJour['en_ligne']); ?></div>
                <div class="tg-stat-card__label">Billets en ligne validés</div>
              </div>
              <div class="tg-stat-card__icon"><i class="bi bi-laptop"></i></div>
            </div>
          </a>
          <a href="<?= BASE_URL ?>/admin/Liste_ententes" class="tg-stat-card" style="--tg-stat-color: var(--warning);">
            <div class="tg-stat-card__top">
              <div>
                <div class="tg-stat-card__value"><?= htmlspecialchars($billetsJour['en_attente']); ?></div>
                <div class="tg-stat-card__label">En attente de validation</div>
              </div>
              <div class="tg-stat-card__icon"><i class="bi bi-hourglass-split"></i></div>
            </div>
          </a>
          <?php if ($showVoyages): ?>
            <a href="<?= BASE_URL ?>/admin/Programmation_voyages/liste_programmer_voyage" class="tg-stat-card" style="--tg-stat-color: var(--info);">
              <div class="tg-stat-card__top">
                <div>
                  <div class="tg-stat-card__value"><?= htmlspecialchars($voyagesJour); ?></div>
                  <div class="tg-stat-card__label">Voyages programmés</div>
                </div>
                <div class="tg-stat-card__icon"><i class="bi bi-bus-front"></i></div>
              </div>
            </a>
          <?php endif; ?>
        </div>
      <?php elseif ($showVoyages): ?>
        <div class="tg-section-label"><i class="bi bi-bus-front me-1"></i>Voyages — Aujourd'hui</div>
        <div class="tg-stat-grid mb-4">
          <a href="<?= BASE_URL ?>/admin/Programmation_voyages/liste_programmer_voyage" class="tg-stat-card" style="--tg-stat-color: var(--info);">
            <div class="tg-stat-card__top">
              <div>
                <div class="tg-stat-card__value"><?= htmlspecialchars($voyagesJour); ?></div>
                <div class="tg-stat-card__label">Voyages programmés</div>
              </div>
              <div class="tg-stat-card__icon"><i class="bi bi-bus-front"></i></div>
            </div>
          </a>
        </div>
      <?php endif; ?>

      <!-- KPI COLIS -->
      <?php if ($showColis): ?>
        <div class="tg-section-label"><i class="bi bi-box-seam me-1"></i>Colis & courrier — Aujourd'hui</div>
        <div class="tg-stat-grid mb-4">
          <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges" class="tg-stat-card" style="--tg-stat-color: var(--accent);">
            <div class="tg-stat-card__top">
              <div>
                <div class="tg-stat-card__value"><?= htmlspecialchars($colisJour['prise_en_charge']); ?></div>
                <div class="tg-stat-card__label">Colis pris en charge</div>
              </div>
              <div class="tg-stat-card__icon"><i class="bi bi-box-seam"></i></div>
            </div>
          </a>
          <a href="<?= BASE_URL ?>/admin/Mouvement_colis" class="tg-stat-card" style="--tg-stat-color: var(--warning);">
            <div class="tg-stat-card__top">
              <div>
                <div class="tg-stat-card__value"><?= htmlspecialchars($colisJour['en_cours']); ?></div>
                <div class="tg-stat-card__label">Colis en cours</div>
              </div>
              <div class="tg-stat-card__icon"><i class="bi bi-truck"></i></div>
            </div>
          </a>
          <a href="<?= BASE_URL ?>/admin/Mouvement_colis" class="tg-stat-card" style="--tg-stat-color: var(--success);">
            <div class="tg-stat-card__top">
              <div>
                <div class="tg-stat-card__value"><?= htmlspecialchars($colisJour['recu'] ?? 0); ?></div>
                <div class="tg-stat-card__label">Colis reçus</div>
              </div>
              <div class="tg-stat-card__icon"><i class="bi bi-inbox"></i></div>
            </div>
          </a>
          <a href="<?= BASE_URL ?>/admin/Livraison_colis" class="tg-stat-card" style="--tg-stat-color: var(--info);">
            <div class="tg-stat-card__top">
              <div>
                <div class="tg-stat-card__value"><?= htmlspecialchars($colisJour['livre']); ?></div>
                <div class="tg-stat-card__label">Colis livrés</div>
              </div>
              <div class="tg-stat-card__icon"><i class="bi bi-check2-circle"></i></div>
            </div>
          </a>
        </div>
      <?php endif; ?>

      <!-- FINANCES : aperçu bénéfice (Admin) / état de la caisse (chef d'escale) -->
      <?php if (($_SESSION['droit'] === 'Admin' && !empty($beneficeJour)) || ($_SESSION['droit'] === 'chef_d_escale')): ?>
        <div class="tg-section-label"><i class="bi bi-cash-coin me-1"></i>Finances</div>
        <div class="tg-stat-grid mb-4">
          <?php if ($_SESSION['droit'] === 'Admin' && !empty($beneficeJour)): ?>
            <a href="<?= BASE_URL ?>/admin/Depenses/benefice" class="tg-stat-card" style="--tg-stat-color: <?= $beneficeJour['benefice'] >= 0 ? 'var(--success)' : 'var(--danger)' ?>;">
              <div class="tg-stat-card__top">
                <div>
                  <div class="tg-stat-card__value"><?= number_format($beneficeJour['benefice'], 0, ',', ' ') ?> F</div>
                  <div class="tg-stat-card__label">Bénéfice aujourd'hui</div>
                </div>
                <div class="tg-stat-card__icon"><i class="bi bi-graph-up-arrow"></i></div>
              </div>
              <div class="tg-stat-card__meta"><i class="bi bi-arrow-right-circle"></i> Voir le détail</div>
            </a>
          <?php endif; ?>

          <?php if (in_array($_SESSION['droit'], ['Admin', 'chef_d_escale']) && !empty($beneficeJour)): ?>
            <div class="tg-stat-card" style="--tg-stat-color: var(--primary);">
              <div class="tg-stat-card__top">
                <div>
                  <div class="tg-stat-card__value"><?= number_format($beneficeJour['revenus_billets'], 0, ',', ' ') ?> F</div>
                  <div class="tg-stat-card__label">Revenus billets</div>
                </div>
                <div class="tg-stat-card__icon"><i class="bi bi-ticket-perforated"></i></div>
              </div>
            </div>

            <div class="tg-stat-card" style="--tg-stat-color: var(--info);">
              <div class="tg-stat-card__top">
                <div>
                  <div class="tg-stat-card__value"><?= number_format($beneficeJour['revenus_colis'], 0, ',', ' ') ?> F</div>
                  <div class="tg-stat-card__label">Revenus colis</div>
                </div>
                <div class="tg-stat-card__icon"><i class="bi bi-box-seam"></i></div>
              </div>
            </div>
          <?php endif; ?>

          <?php if ($_SESSION['droit'] === 'chef_d_escale'): ?>
            <a href="<?= BASE_URL ?>/admin/Caisse" class="tg-stat-card" style="--tg-stat-color: <?= $caisseGare ? 'var(--success)' : 'var(--danger)' ?>;">
              <div class="tg-stat-card__top">
                <div>
                  <div class="tg-stat-card__value">
                    <?= $caisseGare ? number_format($caisseGare->solde, 0, ',', ' ') . ' F' : 'Fermée' ?>
                  </div>
                  <div class="tg-stat-card__label">État de ma caisse</div>
                </div>
                <div class="tg-stat-card__icon"><i class="bi bi-wallet2"></i></div>
              </div>
              <div class="tg-stat-card__meta">
                <i class="bi bi-arrow-right-circle"></i>
                <?= $caisseGare ? 'Voir / clôturer' : 'Ouvrir une caisse' ?>
              </div>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="row g-4 mb-4">

        <!-- TOP GARES (Admin, vue globale uniquement) -->
        <?php if ($showTopGares && !empty($topGares)): ?>
          <div class="col-lg-7">
            <div class="tg-panel h-100">
              <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="d-flex align-items-center">
                  <span class="tg-panel__icon" style="background: rgba(245,158,11,0.14); color: var(--tg-orange);"><i class="bi bi-trophy-fill"></i></span>
                  <div>
                    <h5 class="tg-panel__title">Top des gares</h5>
                    <p class="tg-panel__subtitle">Classement par billets vendus</p>
                  </div>
                </div>
                <span class="badge bg-light text-dark px-3 py-2 rounded-pill"><i class="bi bi-calendar-month"></i> <?= date('F Y') ?></span>
              </div>
              <?php
              $maxBillets = max(array_column($topGares, 'total_billets'));
              foreach ($topGares as $rang => $gare):
                $percent = ($maxBillets > 0) ? ($gare['total_billets'] / $maxBillets) * 100 : 0;
                $rangAffiche = $rang + 1;
                $rankClass = $rangAffiche <= 3 ? ' tg-rank--' . $rangAffiche : '';
              ?>
              <div class="tg-leaderboard-item">
                <div class="tg-rank<?= $rankClass ?>"><?= $rangAffiche ?></div>
                <div class="tg-leaderboard-body">
                  <div class="d-flex justify-content-between mb-1">
                    <span class="fw-semibold"><i class="bi bi-geo-alt-fill text-muted me-1"></i><?= htmlspecialchars($gare['gare']) ?></span>
                    <span class="badge bg-primary rounded-pill"><?= number_format($gare['total_billets']) ?> billets</span>
                  </div>
                  <div class="progress-custom">
                    <div class="progress-bar-custom" style="width: <?= $percent ?>%;"></div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- RÉPARTITION COLIS (données réelles, donut CSS) -->
        <?php if ($showColis):
          $segments = [
            ['label' => 'Pris en charge', 'valeur' => (int)$colisJour['prise_en_charge'], 'color' => '#3b82f6'],
            ['label' => 'En cours',       'valeur' => (int)$colisJour['en_cours'],       'color' => '#f59e0b'],
            ['label' => 'Reçus',          'valeur' => (int)$colisJour['recu'],           'color' => '#06b6d4'],
            ['label' => 'Livrés',         'valeur' => (int)$colisJour['livre'],          'color' => '#10b981'],
            ['label' => 'En attente',     'valeur' => (int)$colisJour['attente'],        'color' => '#ef4444'],
          ];
          $totalColis = array_sum(array_column($segments, 'valeur'));
          $stops = [];
          $cursor = 0;
          foreach ($segments as $segment) {
              if ($totalColis === 0 || $segment['valeur'] === 0) continue;
              $start = $cursor;
              $cursor += ($segment['valeur'] / $totalColis) * 360;
              $stops[] = "{$segment['color']} {$start}deg {$cursor}deg";
          }
          $conicGradient = $totalColis > 0 ? 'conic-gradient(' . implode(', ', $stops) . ')' : 'conic-gradient(var(--tg-gray-200) 0deg 360deg)';
        ?>
          <div class="col-lg-5">
            <div class="tg-panel h-100">
              <div class="d-flex align-items-center mb-3">
                <span class="tg-panel__icon" style="background: rgba(16,185,129,0.14); color: var(--tg-success);"><i class="bi bi-pie-chart-fill"></i></span>
                <div>
                  <h5 class="tg-panel__title">Répartition colis</h5>
                  <p class="tg-panel__subtitle">Aujourd'hui</p>
                </div>
              </div>
              <?php if ($totalColis === 0): ?>
                <div class="tg-empty">
                  <i class="bi bi-inbox"></i>
                  Aucun colis enregistré aujourd'hui.
                </div>
              <?php else: ?>
                <div class="tg-donut-wrap">
                  <div class="tg-donut" style="background: <?= $conicGradient ?>;">
                    <div class="tg-donut__hole">
                      <strong><?= (int)$totalColis ?></strong>
                      <span>colis</span>
                    </div>
                  </div>
                  <div class="tg-donut-legend">
                    <?php foreach ($segments as $segment):
                      if ($segment['valeur'] === 0) continue;
                      $percent = round(($segment['valeur'] / $totalColis) * 100);
                    ?>
                      <div class="tg-donut-legend__item">
                        <span class="tg-donut-legend__label">
                          <span class="tg-donut-legend__dot" style="background: <?= $segment['color'] ?>;"></span>
                          <?= htmlspecialchars($segment['label']) ?>
                        </span>
                        <span class="tg-donut-legend__value"><?= $percent ?>% <span class="text-muted fw-normal">(<?= $segment['valeur'] ?>)</span></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>

      </div>

      <!-- ACTIVITÉS RÉCENTES (données réelles, timeline) -->
      <div class="row">
        <div class="col-12">
          <div class="tg-panel">
            <div class="d-flex align-items-center mb-3">
              <span class="tg-panel__icon" style="background: rgba(15,23,42,0.06); color: var(--tg-navy);"><i class="bi bi-clock-history"></i></span>
              <div>
                <h5 class="tg-panel__title">Activités récentes</h5>
                <p class="tg-panel__subtitle">Dernières actions dans votre périmètre</p>
              </div>
            </div>
            <?php if (empty($activiteRecente)): ?>
              <div class="tg-empty">
                <i class="bi bi-hourglass"></i>
                Aucune activité récente.
              </div>
            <?php else: ?>
              <div class="tg-timeline">
                <?php foreach ($activiteRecente as $activite):
                  $estBillet = $activite['type'] === 'billet';
                ?>
                  <div class="tg-timeline-item">
                    <div class="tg-timeline-dot" style="background: <?= $estBillet ? 'var(--tg-accent, #3b82f6)' : 'var(--tg-success, #10b981)' ?>;">
                      <i class="bi <?= $estBillet ? 'bi-ticket-perforated' : 'bi-truck' ?>"></i>
                    </div>
                    <div class="tg-timeline-card">
                      <div class="d-flex justify-content-between flex-wrap gap-2">
                        <span class="fw-semibold"><?= htmlspecialchars($activite['titre']) ?></span>
                        <small class="text-muted"><?= htmlspecialchars($activite['date']) ?></small>
                      </div>
                      <p class="text-muted small mb-0"><?= htmlspecialchars($activite['detail']) ?></p>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    <?php endif; ?>

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
