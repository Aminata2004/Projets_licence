<header class="top-header">
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


        $db = new Database();     // instancie la classe
        $pdo = $db->bdd();        // récupère l'objet PDO

        // Compter les billets en attente et en ligne

        $stmt = $pdo->prepare("
    SELECT idBillets, Heur_departs, destinationId, departId
    FROM billets
 WHERE validation_billets = 'en_attente'
      AND status_reservation = 'en_ligne'
    ORDER BY idBillets DESC
");
        $stmt->execute();
        $billets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $notifCount = count($billets);
        ?>
        <li class="nav-item dropdown dropdown-large d-none d-sm-block">
          <a class="nav-link" href="#" data-bs-toggle="dropdown">
            <div class="notifications">
              <span class="notify-badge"><?= $notifCount; ?></span>
              <i class="bi bi-bell-fill"></i>
            </div>
          </a>

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
              <img src="assets/images/avatars/avatar-1.png" class="user-img" alt="">
              <div class="user-name d-none d-sm-block">Jhon Deo</div>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="#">
                <div class="d-flex align-items-center">
                  <img src="assets/images/avatars/avatar-1.png" alt="" class="rounded-circle" width="60" height="60">
                  <div class="ms-3">
                    <h6 class="mb-0 dropdown-user-name">Jhon Deo</h6>
                    <small class="mb-0 dropdown-user-designation text-secondary"><?= $_SESSION["droit"] . '   ' . $_SESSION['ville']. ' '.$_SESSION['numero_gare'] ?> </small>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item" href="pages-user-profile.html">
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