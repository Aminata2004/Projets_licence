  <?php $user = new Configuration($_SESSION['id_utilisateur']) ?>
  <aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
      <div>
        <a href="<?= BASE_URL ?>/admin/Homes/home" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">
          <img src="<?= BASE_URL ?>/images/logos/transgest_logo.png" alt="TransGest" style="height:60px; width:auto; object-fit:contain;">
        </a>
      </div>
      <div class="toggle-icon ms-auto"><i class="bi bi-chevron-double-left"></i>
      </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">

      <li>
        <a href="<?= BASE_URL ?>/admin/Homes/home" class="has-">

          <div class="">Accueil</div>
        </a>

      </li>

      <?php if ($_SESSION['droit'] !== 'super_admin'): ?>
      <?php if ($user->userHasPermission('Billets_creation')) { ?>
        <li class="menu-label">Gestion des reservation</li>
        <li>
          <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-category"></i>
            </div>
            <div class="menu-title">G-réservation</div>
          </a>
        <?php }
        ?>
        <ul>
          <?php if ($user->userHasPermission('Billets_creation')) { ?>
            <li> <a href="<?= BASE_URL ?>/admin/Add_billets"><i class="bi bi-arrow-right-short"></i>Achat de ticket</a>
            </li>
          <?php }
          ?>

          <?php if ($user->userHasPermission('Billets_apercue')) { ?>
            <li> <a href="<?= BASE_URL ?>/admin/Liste_du_jours"><i class="bi bi-arrow-right-short"></i>Liste des ticket</a>
            </li>
          <?php }
          ?>
          <?php if ($user->userHasPermission('Billets_validation')) { ?>
            <li> <a href="<?= BASE_URL ?>/admin/Liste_ententes"><i class="bi bi-arrow-right-short"></i>Ticket en entente</a>
            </li>
          <?php }
          ?>
        </ul>
        </li>
        <?php if ($user->userHasPermission('Billets_creation')) { ?>
          <li>
            <a href="javascript:;" class="has-arrow">
              <div class="parent-icon"><i class="bx bx-bar-chart-alt-2"></i>
              </div>
              <div class="menu-title">Rapport billets</div>
            </a>
          <?php }
          ?>
          <ul>

            <?php if ($user->userHasPermission('Billets_apercue')) { ?>
              <li> <a href="<?= BASE_URL ?>/admin/Rapport_billets/rapport_billets"><i class="bi bi-arrow-right-short"></i>Rapport mensuel</a>
              </li>
            <?php }
            ?>
            <?php if ($user->userHasPermission('Billets_validation')) { ?>
              <li> <a href="<?= BASE_URL ?>/admin/Rapport_billets/rapport_annuel"><i class="bi bi-arrow-right-short"></i>Rapport annuel</a>
              </li>
            <?php }
            ?>
          </ul>
          </li>

          <?php if ($user->userHasPermission('Billets_historique')) { ?>
            <li>
              <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges/historique_colis">
                <div class="font-22"> <i class="bx bx-history"></i>
                </div>
                <div class="menu-title">Historique des billets</div>
              </a>
            </li>
          <?php }
          ?>
          <?php if ($user->userHasPermission('colis_creation')) { ?>
            <li class="menu-label">Gestion des colis</li>
            <li>
              <a href="javascript:;" class="has-arrow">
                <div class="font-22"> <i class="fadeIn animated bx bx-layer-plus"></i>
                </div>
                <div class="menu-title">G-colis</div>
              </a>
            <?php } ?>
            <ul>
              <?php if ($user->userHasPermission('colis_creation')) { ?>
                <li> <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges"><i class="bi bi-arrow-right-short"></i>Liste des colis</a>
                </li>
              <?php } ?>
              <?php if ($user->userHasPermission('colis_envoi')) { ?>
                <li> <a href="<?= BASE_URL ?>/admin/Envoi_colis/envoi_colis"><i class="bi bi-arrow-right-short"></i>Envoi des colis</a>
                </li>
              <?php } ?>
              <?php if ($user->userHasPermission('colis_mouvement')) { ?>
                <li> <a href="<?= BASE_URL ?>/admin/Mouvement_colis"><i class="bi bi-arrow-right-short"></i>Mouvement des colis</a>
                </li>
              <?php } ?>
              <?php if ($user->userHasPermission('colis_livraison')) { ?>
                <li> <a href="<?= BASE_URL ?>/admin/Livraison_colis"><i class="bi bi-arrow-right-short"></i>Livraison des colis</a>
                </li>
              <?php } ?>
            </ul>
            </li>
            <?php if ($user->userHasPermission('colis_reclamation')) { ?>
              <li>
                <a href="<?= BASE_URL ?>/admin/Reclamations">
                  <div class="font-22"> <i class="fadeIn animated bx bx-error"></i>
                  </div>
                  <div class="menu-title">Reclamation</div>
                </a>
              </li>
            <?php } ?>
            <?php if ($user->userHasPermission('colis_historique')) { ?>
              <li>
                <a href="<?= BASE_URL ?>/admin/Historiques/historique_colis_enregistrer">
                  <div class="font-22"><i class="bx bx-history"></i>
                  </div>
                  <div class="menu-title">Historique des colis</div>
                </a>
              </li>
            <?php } ?>

            <?php if ($user->userHasPermission('Caisse_apercue')) {
            ?>
              <li class="menu-label">Gestion de caisse</li>
              <li>
                <a href="javascript:;" class="has-arrow">
                  <div class="parent-icon"><i class="bx bx-wallet"></i>
                  </div>
                  <div class="menu-title">Caisse</div>
                </a>
              <?php } ?>
              <ul>
                <?php if ($user->userHasPermission('Caisse_apercue')) {
                ?>
                  <li> <a href="<?= BASE_URL ?>/admin/Caisse"><i class="bi bi-arrow-right-short"></i>Ajouter caisse</a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Caisse_billant')) {
                ?>
                  <li> <a href="<?= BASE_URL ?>/admin/Caisse/bilant_caisse_billets"><i class="bi bi-arrow-right-short"></i>Billant de caisse</a>
                  </li>
                <?php } ?>
              </ul>
              </li>

              <?php if (in_array($_SESSION['droit'] ?? null, ['Admin', 'chef_d_escale'], true) && $user->userHasPermission('Depenses_gestion')): ?>
                <li class="menu-label">Finances</li>
                <li>
                  <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-money-withdraw"></i>
                    </div>
                    <div class="menu-title">Dépenses</div>
                  </a>
                  <ul>
                    <li> <a href="<?= BASE_URL ?>/admin/Depenses"><i class="bi bi-arrow-right-short"></i>Gérer les dépenses</a>
                    </li>
                    <?php if ($_SESSION['droit'] === 'Admin'): ?>
                      <li> <a href="<?= BASE_URL ?>/admin/Depenses/benefice"><i class="bi bi-arrow-right-short"></i>Bénéfice de la compagnie</a>
                      </li>
                    <?php endif; ?>
                  </ul>
                </li>
              <?php endif; ?>

              <?php if (in_array($_SESSION['droit'] ?? null, ['Admin', 'chef_d_escale'], true)): ?>
                <li class="menu-label">Banque</li>
                <li>
                  <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-buildings"></i>
                    </div>
                    <div class="menu-title">Dépôt en banque</div>
                  </a>
                  <ul>
                    <?php if ($_SESSION['droit'] === 'Admin'): ?>
                      <li> <a href="<?= BASE_URL ?>/admin/Banques"><i class="bi bi-arrow-right-short"></i>Comptes banque</a>
                      </li>
                      <li> <a href="<?= BASE_URL ?>/admin/Depots_banque/enAttente"><i class="bi bi-arrow-right-short"></i>Demandes en attente</a>
                      </li>
                    <?php endif; ?>
                    <li> <a href="<?= BASE_URL ?>/admin/Depots_banque"><i class="bi bi-arrow-right-short"></i>Faire un dépôt</a>
                    </li>
                    <li> <a href="<?= BASE_URL ?>/admin/Depots_banque/historique"><i class="bi bi-arrow-right-short"></i>Historique des dépôts</a>
                    </li>
                  </ul>
                </li>
              <?php endif; ?>

              <?php if ($user->userHasPermission('Programme_Creation')) { ?>
                <li class="menu-label">Gestion des programmations</li>
                <li>
                  <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-calendar"></i>
                    </div>
                    <div class="menu-title">G-programme</div>
                  </a>
                <?php } ?>
                <ul>
                  <?php if ($user->userHasPermission('Programme_Creation')) { ?>
                    <li> <a href="<?= BASE_URL ?>/admin/Programmer_voyages"><i class="bi bi-arrow-right-short"></i>Programme du voyage</a>
                    </li>
                  <?php } ?>
                  <?php if ($user->userHasPermission('Programme_programmer_car')) { ?>
                    <li> <a href="<?= BASE_URL ?>/admin/Programmation_cars"><i class="bi bi-arrow-right-short"></i>Affectation des cars</a>
                    </li>
                  <?php } ?>
                  <?php if ($user->userHasPermission('Programme_programmation_voyage')) { ?>
                    <li> <a href="<?= BASE_URL ?>/admin/Programmation_voyages/liste_programmer_voyage"><i class="bi bi-arrow-right-short"></i>Programmation du voyage</a>
                    </li>
                  <?php } ?>
                  <?php if ($user->userHasPermission('Programme_programmation_voyage') && in_array($_SESSION['droit'] ?? null, ['Admin', 'chef_d_escale', 'super_admin'], true)): ?>
                    <li> <a href="<?= BASE_URL ?>/admin/Transferts_gares/historique"><i class="bi bi-arrow-right-short"></i>Transferts entre gares</a>
                    </li>
                  <?php endif; ?>
                  <?php if ($user->userHasPermission('Programme_hors_programme')) { ?>
                    <li> <a href="#"><i class="bi bi-arrow-right-short"></i>Hors programmer</a>
                    </li>
                  <?php } ?>
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

                <?php endif; // Fin du if !== 'super_admin' ?>

                <?php if (isset($_SESSION['droit']) && ($_SESSION['droit'] === 'Admin' || $_SESSION['droit'] === 'super_admin')): ?>
                <?php if ($user->userHasPermission('Configuration_apercu')) { ?>
                  <li class="menu-label">Paramètre</li>
                  <li>
                    <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                      <a href="<?= BASE_URL ?>/admin/Compagnies">
                        <div class="parent-icon">
                          <i class="fadeIn animated bx bx-shape-polygon"></i>
                        </div>
                        <div class="menu-title">Configuration</div>
                      </a>
                    <?php else: ?>
                      <a href="<?= BASE_URL ?>/admin/Liste_gares">
                        <div class="parent-icon">
                          <i class="fadeIn animated bx bx-shape-polygon"></i>
                        </div>
                        <div class="menu-title">Configuration</div>
                      </a>
                    <?php endif; ?>
                  </li>
                  <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                  <?php $partenaireModel = new Partenaire(); $nbEnAttente = $partenaireModel->countEnAttenteReponse(); ?>
                  <li>
                      <a href="<?= BASE_URL ?>/admin/Partenariats">
                        <div class="parent-icon">
                          <i class="fadeIn animated bx bx-handshake"></i>
                        </div>
                        <div class="menu-title">
                          Demandes de partenariat
                          <?php if ($nbEnAttente > 0): ?>
                            <span class="badge bg-danger rounded-pill ms-1"><?= $nbEnAttente ?></span>
                          <?php endif; ?>
                        </div>
                      </a>
                  </li>
                  <?php endif; ?>
   <?php } ?>
                <?php endif; ?>
                
    </ul>
    <!--end navigation-->
  </aside>