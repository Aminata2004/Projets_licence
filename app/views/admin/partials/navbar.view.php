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
                    <small class="mb-0 dropdown-user-designation text-secondary"><?= $_SESSION["droit"].'   '.$_SESSION['ville']?> </small>
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
              <a class="dropdown-item" href="<?= BASE_URL ?>/Auth/logout">
                <div class="d-flex align-items-center">
                  <div class="setting-icon"><i class="bi bi-box-arrow-right"></i></div>
                  <div class="setting-text ms-3">
                    Déconnexion
                  </div>
                </div>
              </a>
            </li>

          </ul>

        <li class="nav-item dropdown dropdown-large d-none d-sm-block">
          <a class="nav-link " href="#" data-bs-toggle="dropdown">
            <div class="notifications">
              <span class="notify-badge">8</span>
              <i class="bi bi-bell-fill"></i>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end p-0">
            <div class="p-2 border-bottom m-2">
              <h5 class="h5 mb-0">Notifications</h5>
            </div>
            <div class="header-notifications-list p-2">

              <a class="dropdown-item" href="#">
                <div class="d-flex align-items-center">
                  <div class="notification-box"><i class="bi bi-droplet-fill"></i></div>
                  <div class="ms-3 flex-grow-1">
                    <h6 class="mb-0 dropdown-msg-user">New 24 authors<span class="msg-time float-end text-secondary">1 m</span></h6>
                    <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">24 new authors joined last week</small>
                  </div>
                </div>
              </a>
              <a class="dropdown-item" href="#">
                <div class="d-flex align-items-center">
                  <div class="notification-box"><i class="bi bi-mic-fill"></i></div>
                  <div class="ms-3 flex-grow-1">
                    <h6 class="mb-0 dropdown-msg-user">Your item is shipped <span class="msg-time float-end text-secondary">7 m</span></h6>
                    <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">Successfully shipped your item</small>
                  </div>
                </div>
              </a>

            </div>
            <div class="p-2">
              <div>
                <hr class="dropdown-divider">
              </div>
              <a class="dropdown-item" href="#">
                <div class="text-center">View All Notifications</div>
              </a>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>