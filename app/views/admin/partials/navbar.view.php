  <?php $user = new Configuration($_SESSION['id_utilisateur']) ?>
  <header class="top-header">
    <?php if (isset($_SESSION['super_admin_id'])): ?>
      <div style="background-color: #dc3545; color: white; text-align: center; padding: 10px; font-weight: bold; z-index: 9999; position: relative;">
          ⚠️ MODE SUPPORT TECHNIQUE : Vous visualisez actuellement les données avec l'identité : <?= $_SESSION['nom'] ?>.
          <a href="<?= BASE_URL ?>/admin/Compagnies/leave_impersonate" class="btn btn-sm btn-light ms-3 fw-bold text-danger">Quitter le mode support</a>
      </div>
    <?php endif; ?>
    <nav class="navbar navbar-expand">
      <div class="mobile-toggle-icon d-xl-none">
        <i class="bi bi-list"></i>
      </div>
      <div class="top-navbar d-none d-xl-block">

      </div>
      <div class="search-toggle-icon d-xl-none ms-auto">
        <i class="bi bi-search"></i>
      </div>
      <form class="searchbar d-none d-xl-flex ms-auto">
        <div class="position-absolute top-50 translate-middle-y search-icon ms-3"></div>

        <div class="position-absolute top-50 translate-middle-y d-block d-xl-none search-close-icon"><i class="bi bi-x-lg"></i></div>
      </form>
      <div class="top-navbar-right ms-3">
        <ul class="navbar-nav align-items-center">

          <?php
          require_once __DIR__ . '/../../../core/database.php';


          $_SESSION['droit'];         // 'Admin_global', 'chef_d_escale', 'Utilisateur', 'Admin'
          $_SESSION['ville'];        // pour chef_d_escale / Utilisateur
          $_SESSION['numero_gare'];  // pour chef_d_escale / Utilisateur
          $_SESSION['id_compagnie']; // pour Admin



          $db = new Database();     // instancie la classe
          $pdo = $db->bdd();        // récupère l'objet PDO


          $sql = "
            SELECT idBillets, Heur_departs, destinationId, departId, num_gare, id_compagnie
            FROM billets
            WHERE validation_billets = 'en_attente'
              AND status_reservation = 'en_ligne'
         ";

          $params = [];

          if ($_SESSION['droit'] === 'chef_d_escale' || $_SESSION['droit'] === 'Utilisateur') {
            // Filtrer par compagnie, puis par ville et numéro de gare
            $sql .= " AND id_compagnie = :id_compagnie AND departId = :ville AND num_gare = :numero_gare";
            $params[':id_compagnie'] = $_SESSION['id_compagnie'];
            $params[':ville'] = $_SESSION['ville'];
            $params[':numero_gare'] = $_SESSION['numero_gare'];
          } elseif ($_SESSION['droit'] === 'Admin') {
            // Filtrer par compagnie
            $sql .= " AND id_compagnie = :id_compagnie";
            $params[':id_compagnie'] = $_SESSION['id_compagnie'];
          }

          // Exécution de la requête
          $stmt = $pdo->prepare($sql);
          $stmt->execute($params);

          $billets = $stmt->fetchAll(PDO::FETCH_ASSOC);

          // Locations de cars en attente : uniquement pertinent pour l'Admin, qui est le
          // seul a pouvoir les valider (creees par un chef d'escale).
          $locationsEnAttente = [];
          if ($_SESSION['droit'] === 'Admin') {
            $stmtLoc = $pdo->prepare(
              "SELECT id_location, destination, frais_location, a.localite
               FROM location_car l
               LEFT JOIN agence a ON l.id_agence_depart = a.idAgence
               WHERE l.statut = 'en_attente' AND l.id_compagnie = :id_compagnie"
            );
            $stmtLoc->execute([':id_compagnie' => $_SESSION['id_compagnie']]);
            $locationsEnAttente = $stmtLoc->fetchAll(PDO::FETCH_ASSOC);
          }

          // Passagers non embarques, en retard de plus de 30 minutes sur leur depart du
          // jour : pertinent uniquement pour le chef d'escale (sa gare) et l'Admin (toute
          // la compagnie), pas pour un simple Utilisateur.
          $billetsEnRetard = [];
          if ($_SESSION['droit'] === 'chef_d_escale') {
            $billetsEnRetard = (new Liste_du_jour())->getBilletsEnRetard(
              $_SESSION['ville'] ?? null,
              $_SESSION['numero_gare'] ?? null,
              $_SESSION['id_compagnie']
            );
          } elseif ($_SESSION['droit'] === 'Admin') {
            $billetsEnRetard = (new Liste_du_jour())->getBilletsEnRetard(null, null, $_SESSION['id_compagnie']);
          }

          $notifCount = count($billets) + count($locationsEnAttente) + count($billetsEnRetard);

          // Identite affichee (nav du haut) : le role seul ("chef_d_escale") ne dit pas de
          // quelle gare il s'agit, ce qui devient ambigu des qu'un compte Admin visite
          // plusieurs gares/compagnies (mode support) ou qu'il y a plusieurs "Utilisateur"
          // (billetterie/colis) sur des gares differentes. On affiche donc role + gare (et,
          // pour un simple Utilisateur, son service — meme intitules que Configurations::index()).
          $libellesRole = [
            'Utilisateur'   => 'Utilisateur',
            'chef_d_escale' => "Chef d'escale",
            'Admin'         => 'Admin',
            'super_admin'   => 'Super Admin',
            'PDG'           => 'PDG',
          ];
          $libellesService = ['billet' => 'Billetterie', 'colis' => 'Colis / Courrier'];

          $roleAffiche = $libellesRole[$_SESSION['droit'] ?? ''] ?? ($_SESSION['droit'] ?? '');
          if (($_SESSION['droit'] ?? null) === 'Utilisateur' && !empty($_SESSION['profile']) && isset($libellesService[$_SESSION['profile']])) {
            $roleAffiche = $libellesService[$_SESSION['profile']];
          }

          $gareAffichee = trim(($_SESSION['ville'] ?? '') . (!empty($_SESSION['numero_gare']) ? ' (' . $_SESSION['numero_gare'] . ')' : ''));
          $identiteAffichee = $roleAffiche . ($gareAffichee !== '' ? ' — ' . $gareAffichee : '');

          ?>
     
          <?php if ($user->userHasPermission('Billets_notification')) { ?>
            <li class="nav-item dropdown dropdown-large d-none d-sm-block">
              <a class="nav-link" href="#" data-bs-toggle="dropdown">
                <div class="notifications">
                  <?php if ($notifCount > 0): ?>
                    <span class="notify-badge"><?= $notifCount; ?></span>
                  <?php endif; ?>
                  <i class="bx bxs-bell"></i>
                </div>
              </a>
            <?php }
            ?>

            <div class="dropdown-menu dropdown-menu-end p-0">
              <div class="header-notifications-list p-2">
                <?php if ($notifCount > 0): ?>
                  <?php foreach ($billets as $billet): ?>
                    <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Liste_ententes/validation/<?= htmlspecialchars($billet['idBillets']); ?>">
                      <div class="d-flex align-items-center">
                        <div class="notification-box"><i class="bx bxs-coupon"></i></div>
                        <div class="ms-3 flex-grow-1">
                          <h6 class="mb-0 dropdown-msg-user">Billet en attente</h6>
                          <small class="mb-0 dropdown-msg-text text-secondary">
                            <?php if ($_SESSION['droit'] === 'Admin'): ?>
                              <span class="badge bg-secondary me-1"><?= htmlspecialchars($billet['departId']); ?></span>
                            <?php endif; ?>
                            <?= htmlspecialchars($billet['destinationId']); ?> - <?= htmlspecialchars($billet['Heur_departs']); ?>
                          </small>
                        </div>
                      </div>
                    </a>
                  <?php endforeach; ?>
                  <?php foreach ($locationsEnAttente as $location): ?>
                    <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Locations_cars">
                      <div class="d-flex align-items-center">
                        <div class="notification-box"><i class="bx bx-car"></i></div>
                        <div class="ms-3 flex-grow-1">
                          <h6 class="mb-0 dropdown-msg-user">Location de car en attente</h6>
                          <small class="mb-0 dropdown-msg-text text-secondary">
                            <span class="badge bg-secondary me-1"><?= htmlspecialchars($location['localite'] ?? '-'); ?></span>
                            <?= htmlspecialchars($location['destination']); ?> - <?= number_format($location['frais_location'], 0, ',', ' ') ?> F
                          </small>
                        </div>
                      </div>
                    </a>
                  <?php endforeach; ?>
                  <?php foreach ($billetsEnRetard as $billetRetard): ?>
                    <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Liste_du_jours/embarquement?destination=<?= urlencode($billetRetard['destinationId']) ?>&heure=<?= urlencode($billetRetard['Heur_departs']) ?>">
                      <div class="d-flex align-items-center">
                        <div class="notification-box"><i class="bx bx-time-five text-danger"></i></div>
                        <div class="ms-3 flex-grow-1">
                          <h6 class="mb-0 dropdown-msg-user">Client non embarqué (retard)</h6>
                          <small class="mb-0 dropdown-msg-text text-secondary">
                            <?= htmlspecialchars($billetRetard['Client']); ?> —
                            <?= htmlspecialchars($billetRetard['destinationId']); ?> - <?= htmlspecialchars($billetRetard['Heur_departs']); ?>
                          </small>
                        </div>
                      </div>
                    </a>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-center text-secondary p-2">Aucune notification</p>
                <?php endif; ?>
              </div>
            </div>
            </li>



            <li class="nav-item dropdown dropdown-large">
              <a class="nav-link " href="#" data-bs-toggle="dropdown">
                <div class="user-setting d-flex align-items-center gap-1">
                  <img src="<?= !empty($_SESSION['photo']) ? ASSET_URL . '/uploads/profiles/' . htmlspecialchars($_SESSION['photo']) : ASSET_URL . '/assets_site/img/reservation.png' ?>" class="user-img" alt="">
                  <div class="user-name"><?= htmlspecialchars($_SESSION['nom'] ?? '') ?> <small style="font-size: 0.75rem; color: #f59e0b; display: block; line-height: 1;"><?= htmlspecialchars($identiteAffichee) ?></small></div>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Profils">
                    <div class="d-flex align-items-center">
                      <div class="setting-icon"><i class="bx bxs-user"></i></div>
                      <div class="setting-text ms-3"><span>Profile</span></div>
                    </div>
                  </a>
                </li>

                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Auth/logout">
                    <div class="d-flex align-items-center">
                      <div class="setting-icon"><i class="bx bx-log-out-circle"></i></div>
                      <div class="setting-text ms-3">
                        Déconnexion
                      </div>
                    </div>
                  </a>
                </li>

              </ul>

        </ul>
      </div>
    </nav>
  </header>