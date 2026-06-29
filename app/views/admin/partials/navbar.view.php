  <?php $user = new Configuration($_SESSION['id_utilisateur']) ?>
    <style>
    .top-header {
      background: #ffffff !important;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
      padding: 0.5rem 1rem;
    }
    
    .top-header .nav-link,
    .top-header .mobile-toggle-icon i {
      color: #0f172a !important;
    }
    
    /* Notification Bell */
    .top-header .notifications {
      position: relative;
      background: rgba(15, 23, 42, 0.05);
      width: 42px;
      height: 42px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    .top-header .notifications:hover {
      background: rgba(15, 23, 42, 0.1);
      transform: scale(1.05);
    }
    .top-header .notifications i {
      color: #0f3b5e !important;
      font-size: 1.3rem;
    }
    .top-header .notify-badge {
      position: absolute;
      top: -3px;
      right: -3px;
      background: linear-gradient(135deg, #f59e0b, #ea580c);
      color: white;
      font-size: 0.75rem;
      font-weight: 700;
      padding: 3px 6px;
      border-radius: 50%;
      border: 2px solid #ffffff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* User Profile Menu */
    .top-header .user-setting {
      padding: 5px 15px 5px 5px;
      border-radius: 30px;
      background: rgba(15, 23, 42, 0.03);
      transition: all 0.3s ease;
      border: 1px solid rgba(15, 23, 42, 0.05);
    }
    .top-header .user-setting:hover {
      background: rgba(15, 23, 42, 0.08);
      border-color: rgba(15, 23, 42, 0.1);
    }
    .top-header .user-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid #ea580c;
      padding: 2px;
      background: #ffffff;
      object-fit: contain;
    }
    .top-header .user-name {
      color: #0f3b5e !important;
      font-weight: 700;
      margin-left: 10px;
      font-size: 0.95rem;
      letter-spacing: 0.3px;
    }

    /* Premium Dropdown Menus */
    .top-header .dropdown-menu {
      box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
      border: none !important;
      border-radius: 16px !important;
      padding: 12px;
      margin-top: 10px;
      min-width: 250px;
    }
    .top-header .dropdown-item {
      border-radius: 10px;
      padding: 10px 15px;
      transition: all 0.2s ease;
      font-weight: 500;
      color: #475569;
      display: flex;
      align-items: center;
    }
    .top-header .dropdown-item:hover {
      background: rgba(245, 158, 11, 0.08);
      color: #ea580c;
      transform: translateX(4px);
    }
    .top-header .dropdown-item .setting-icon i,
    .top-header .dropdown-item i.bi {
      color: #ea580c;
      font-size: 1.3rem;
      margin-right: 10px;
    }
    
    /* Notification Dropdown Items */
    .header-notifications-list {
      max-height: 300px;
      overflow-y: auto;
    }
    .notification-box {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: rgba(245, 158, 11, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ea580c;
      font-size: 1.4rem;
    }
    .dropdown-msg-user {
      font-weight: 700;
      color: #0f172a;
    }
    .dropdown-user-name {
      font-weight: 800;
      font-size: 1.1rem;
      color: #0f172a;
    }
    .dropdown-user-designation {
      color: #ea580c !important;
      font-weight: 600;
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
                  <div class="user-name"><?= $_SESSION['nom']?> <small style="font-size: 0.75rem; color: #f59e0b; display: block; line-height: 1;"><?= $_SESSION["droit"] ?></small></div>
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