  <aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
      <div>
        <img src="assets/images/logo.png" class="logo-icon" alt="logo icon">
      </div>
      <div>
        <h4 class="logo-text">G_compagnie</h4>
      </div>
      <div class="toggle-icon ms-auto"><i class="bi bi-chevron-double-left"></i>
      </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
      <li>
        <a href="<?= BASE_URL ?>/Homes" class="has-">

          <div class="">Accueil</div>
        </a>

      </li>
      <li class="menu-label">Gestion des reservation</li>
      <li>
        <a href="javascript:;" class="has-arrow">
          <div class="parent-icon"><i class="bx bx-category"></i>
          </div>
          <div class="menu-title">G-réservation</div>
        </a>
        <ul>
          <li> <a href="<?= BASE_URL?>/Add_billets"><i class="bi bi-arrow-right-short"></i>Achat de ticket</a>
          </li>
          <li> <a href="<?= BASE_URL?>/Liste_tickets"><i class="bi bi-arrow-right-short"></i>Liste des ticket</a>
          </li>
          <li> <a href="#"><i class="bi bi-arrow-right-short"></i>Ticket en entente</a>
          </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="has-arrow">
          <div class="parent-icon"><i class="fadeIn animated bx bx-filter"></i>
          </div>
          <div class="menu-title">Filtrage</div>
        </a>
        <ul>
          <li> <a href="#"><i class='bx bx-radio-circle'></i>Par jour</a>
          </li>
          <li> <a href="#"><i class='bx bx-radio-circle'></i>Par voyage</a>
          </li>
        </ul>
      </li>
      <li class="menu-label">Gestion des colis</li>
      <li>
        <a href="javascript:;" class="has-arrow">
          <div class="font-22"> <i class="fadeIn animated bx bx-layer-plus"></i>
          </div>
          <div class="menu-title">G-colis</div>
        </a>
        <ul>
          <li> <a href="<?= BASE_URL ?>/Colis_prise_en_charges"><i class="bi bi-arrow-right-short"></i>Liste des colis</a>
          </li>
          <li> <a href="<?= BASE_URL ?>/Envoi_colis"><i class="bi bi-arrow-right-short"></i>Envoi des colis</a>
          </li>
          <li> <a href="<?= BASE_URL ?>/Mouvement_colis"><i class="bi bi-arrow-right-short"></i>Mouvement des colis</a>
          </li>
          <li> <a href="<?= BASE_URL ?>/Livraison_colis"><i class="bi bi-arrow-right-short"></i>Livraison des colis</a>
          </li>
        </ul>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/Reclamations">
          <div class="font-22"> <i class="fadeIn animated bx bx-error"></i>
          </div>
          <div class="menu-title">Reclamation</div>
        </a>
      </li>
      <li class="menu-label">Gestion des programmations</li>
      <li>
        <a href="javascript:;" class="has-arrow">
          <div class="parent-icon"><i class="fadeIn animated bx bx-data"></i>
          </div>
          <div class="menu-title">G-programmer</div>
        </a>
        <ul>
          <li> <a href="<?= BASE_URL ?>/Programmation_voyages/liste_programmer_voyage"><i class="bi bi-arrow-right-short"></i>Programmation du voyage</a>
          </li>
          <li> <a href="<?= BASE_URL ?>/Programmer_voyages"><i class="bi bi-arrow-right-short"></i>Programmer du voyage</a>
          </li>
          <li> <a href="#"><i class="bi bi-arrow-right-short"></i>Hors programmer</a>
          </li>
        </ul>
      </li>
      <li class="menu-label"></li>
      <!-- <li>
              <a href="#">
                <div class="parent-icon"><i class="fadeIn animated bx bx-shape-polygon"></i>
                </div>
                <div class="menu-title">Reclamation</div>
              </a>
            </li>
             <li>
              <a href="#">
                <div class="parent-icon"><i class="fadeIn animated bx bx-shape-polygon"></i>
                </div>
                <div class="menu-title">Historique</div>
              </a>
            </li>
             <li>
              <a href="#">
                <div class="parent-icon"><i class="fadeIn animated bx bx-shape-polygon"></i>
                </div>
                <div class="menu-title">Caisse</div>
              </a>
            </li> -->
      <?php if (isset($_SESSION['droit']) && $_SESSION['droit'] === 'Admin' || $_SESSION['droit'] === 'super_admin') : ?>
        <li class="menu-label">Paramètre</li>
        <li>
          <?php if ($_SESSION['droit'] === 'super_admin'): ?>
            <a href="<?= BASE_URL ?>/Compagnies">
              <div class="parent-icon"><i class="fadeIn animated bx bx-shape-polygon"></i></div>
              <div class="menu-title">Configuration</div>
            </a>
          <?php
          else: ?>
            <a href="<?= BASE_URL ?>/Configurations">
              <div class="parent-icon"><i class="fadeIn animated bx bx-shape-polygon"></i></div>
              <div class="menu-title">Configuration</div>
            </a>
          <?php endif
          ?>

        </li>
      <?php endif; ?>

    </ul>
    <!--end navigation-->
  </aside>