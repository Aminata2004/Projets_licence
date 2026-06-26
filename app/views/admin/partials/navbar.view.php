  <?php $user = new Configuration($_SESSION['id_utilisateur']) ?>
  <style>
    .top-header {
      background: #0f3b5e !important;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
    }
    .top-header .nav-link,
    .top-header .mobile-toggle-icon i,
    .top-header .user-name {
      color: #ffffff !important;
    }
    .top-header .notifications i {
      color: #ffffff !important;
    }
    .top-header .search-close-icon i, .top-header .search-toggle-icon i {
      color: #ffffff !important;
    }
    .dropdown-menu {
      box-shadow: 0 5px 20px rgba(0,0,0,0.15) !important;
      border: none !important;
    }
  </style>
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
            // Filtrer par ville et numéro de gare
            $sql .= " AND departId = :ville AND num_gare = :numero_gare";
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
          $notifCount = count($billets);

          ?>
     
          <?php if ($user->userHasPermission('Billets_notification')) { ?>
            <li class="nav-item dropdown dropdown-large d-none d-sm-block">
              <a class="nav-link" href="#" data-bs-toggle="dropdown">
                <div class="notifications">
                  <span class="notify-badge"><?= $notifCount; ?></span>
                  <i class="bi bi-bell-fill"></i>
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
                        <div class="notification-box"><i class="bi bi-ticket-perforated-fill"></i></div>
                        <div class="ms-3 flex-grow-1">
                          <h6 class="mb-0 dropdown-msg-user">Billet en attente</h6>
                          <small class="mb-0 dropdown-msg-text text-secondary">
                            <?= htmlspecialchars($billet['destinationId']); ?> - <?= htmlspecialchars($billet['Heur_departs']); ?>
                          </small>
                        </div>
                      </div>
                    </a>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-center text-secondary p-2">Aucun billet en attente</p>
                <?php endif; ?>
              </div>
            </div>
            </li>



            <li class="nav-item dropdown dropdown-large">
              <a class="nav-link " href="#" data-bs-toggle="dropdown">
                <div class="user-setting d-flex align-items-center gap-1">
                  <img src="<?= BASE_URL ?>/assets_site/img/reservation.png" class="user-img" alt="">
                  <div class="user-name d-none d-sm-block"><?= $_SESSION['nom']?></div>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="#">
                    <div class="d-flex align-items-center">
                      <img src="<?= BASE_URL ?>/assets_site/img/reservation.png" alt="" class="rounded-circle" width="60" height="60">
                      <div class="ms-3">
                        <h6 class="mb-0 dropdown-user-name"><?= $_SESSION['nom']?></h6>
                        <small class="mb-0 dropdown-user-designation text-secondary"><?= $_SESSION["droit"] ?> </small>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Profils">
                    <div class="d-flex align-items-center">
                      <div class="setting-icon"><i class="bi bi-person-fill"></i></div>
                      <div class="setting-text ms-3"><span>Profile</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="#">
                    <div class="d-flex align-items-center">
                      <!-- <div class="setting-icon"><i class="bi bi-gear-fill"></i></div> -->

                    </div>
                  </a>
                </li>

                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Auth/logout">
                    <div class="d-flex align-items-center">
                      <div class="setting-icon"><i class="bi bi-box-arrow-right"></i></div>
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