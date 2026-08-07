
  <footer class="text-center py-3" style="border-top:1px solid #e9ecef; background:#fff; margin-top:auto;">
    <p class="mb-0 text-muted small">Copyright © 2026 Computer Service Barry. All rights reserved.</p>
  </footer>

   <!-- Bootstrap bundle JS -->
  <script src="<?=ASSET_URL?>/assets/js/bootstrap.bundle.min.js"></script>
  <!--plugins-->
  <script src="<?=ASSET_URL?>/assets/js/jquery.min.js"></script>
  <script src="<?=ASSET_URL?>/assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="<?=ASSET_URL?>/assets/plugins/metismenu/js/metisMenu.min.js"></script>
  <script src="<?=ASSET_URL?>/assets/plugins/easyPieChart/jquery.easypiechart.js"></script>
  <script src="<?=ASSET_URL?>/assets/plugins/peity/jquery.peity.min.js"></script>
  <script src="<?=ASSET_URL?>/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
  <script src="<?=ASSET_URL?>/assets/js/pace.min.js"></script>
  <script src="<?=ASSET_URL?>/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
	<script src="<?=ASSET_URL?>/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
  <script src="<?=ASSET_URL?>/assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
  <script src="<?=ASSET_URL?>/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="<?=ASSET_URL?>/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
  <!--app-->
  <script src="<?=ASSET_URL?>/assets/js/app.js"></script>
  <script src="<?=ASSET_URL?>/assets/js/index.js"></script>
    <script src="<?=ASSET_URL?>/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
  <script src="<?=ASSET_URL?>/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
  <script src="<?=ASSET_URL?>/assets/js/table-datatable.js"></script>
  <script src="<?=ASSET_URL?>/assets/plugins/select2/js/select2.min.js"></script>
  <script src="<?=ASSET_URL?>/assets/js/form-select2.js"></script>
    <script src="<?=ASSET_URL?>/assets/js/js_gare.js"></script>

    <script src="<?=ASSET_URL?>/assets/plugins/js/bs-stepper.min.js"></script>
<script src="<?=ASSET_URL?>/assets/plugins/js/main.js"></script>

<script src="<?=ASSET_URL?>/assets/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?=ASSET_URL?>/assets/datatable/js/dataTables.bootstrap5.min.js"></script>
 <script src="<?=ASSET_URL?>/mon_js/swt_alert.js"></script>

  <script>
     new PerfectScrollbar(".best-product")
     new PerfectScrollbar(".top-sellers-list")

$(document).ready(function() {
    $('#example1').DataTable();
});
</script>

<script>
  // Un menu "..." (dropdown-menu) ouvert dans un tableau .table-responsive était rogné :
  // overflow-x:auto rend aussi overflow-y effectif (règle CSS), donc tout ce qui dépasse
  // verticalement est masqué -- plus une ligne est haute (texte long dans Libellé/Slogan
  // par ex.), plus c'est visible. La tentative précédente (basculer overflow:visible sur
  // show.bs.dropdown/hide.bs.dropdown) ne suffisait pas de façon fiable. On utilise à la
  // place le mécanisme officiel de Bootstrap pour ce cas ("dropdown dans un conteneur à
  // défilement") : positionner le menu en "fixed" plutôt qu'"absolute", ce qui le fait
  // échapper complètement au découpage du conteneur, quel que soit le timing.
  // Fait au clic, en phase de capture, avant que Bootstrap ne crée l'instance Dropdown
  // (qui lit data-bs-strategy à ce moment-là) -- couvre aussi les lignes ajoutées
  // dynamiquement (DataTables, pagination...) sans avoir à les réinitialiser.
  document.addEventListener('click', function (event) {
    var toggle = event.target.closest('.table-responsive [data-bs-toggle="dropdown"]');
    if (toggle) {
      toggle.setAttribute('data-bs-strategy', 'fixed');
    }
  }, true);
</script>

<style>
  /* Spinner générique pour les liens d'action (suppression) qui ne sont pas des boutons
     de formulaire — ceux-là utilisent déjà .spinner-border (Bootstrap), géré en JS. */
  .tg-action-busy {
    pointer-events: none;
    opacity: .55;
  }
  .tg-action-busy::after {
    content: "";
    display: inline-block;
    width: .75em;
    height: .75em;
    margin-left: .4em;
    border: 2px solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    vertical-align: -0.15em;
    animation: tg-spin .6s linear infinite;
  }
  @keyframes tg-spin {
    to { transform: rotate(360deg); }
  }
</style>

<script>
  // Anti double-action générique pour tout l'admin : empêche de déclencher deux fois la
  // même action (ajouter/modifier/supprimer) sur un double-clic ou un rechargement lent,
  // et affiche un indicateur de chargement pendant que ça travaille.
  (function () {
    // 1) Formulaires (ajouter/modifier/supprimer via POST) : désactive le bouton cliqué
    // et ajoute un spinner UNIQUEMENT quand le navigateur a réellement décidé de soumettre
    // (l'event 'submit' ne se déclenche qu'après une validation HTML5/JS réussie — si un
    // onsubmit="return ..." ou une validation Bootstrap a déjà annulé l'envoi,
    // event.defaultPrevented est déjà vrai ici et on ne touche à rien).
    document.addEventListener('submit', function (event) {
      var form = event.target;
      if (!(form instanceof HTMLFormElement) || event.defaultPrevented) return;

      // Un second submit du même formulaire avant le rechargement de la page (ex: touche
      // Entrée pressée deux fois très vite) est bloqué net.
      if (form.dataset.tgSubmitting === '1') {
        event.preventDefault();
        return;
      }

      var btn = event.submitter
        || form.querySelector('button[type="submit"], input[type="submit"]');
      if (!btn || btn.disabled) return;

      form.dataset.tgSubmitting = '1';

      // Important : on NE met JAMAIS btn.disabled = true ici. Un contrôle désactivé est
      // exclu des données envoyées par le navigateur — son name (ex: name="programmer_car",
      // utilisé côté serveur via isset($_POST['programmer_car'])) disparaîtrait du POST et
      // l'action ne se déclencherait plus jamais, tout en rechargeant la page comme si de
      // rien n'était. On simule juste l'état "occupé" visuellement (spinner + interactions
      // désactivées en CSS), sans toucher aux données réellement soumises.
      btn.classList.add('tg-action-busy');
      if (btn.tagName === 'BUTTON') {
        btn.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');
      }
    }, false);

    // 2) Liens d'action qui ne passent pas par un formulaire (ex: "Supprimer" avec
    // confirmation SweetAlert avant navigation GET). On ne touche pas à la logique de
    // confirmation existante (déjà en place par vue) : on bloque juste un second clic
    // pendant que la première confirmation/action est en cours.
    document.addEventListener('click', function (event) {
      var link = event.target.closest('.delete-button, .supprimer-car-button');
      if (!link) return;

      if (link.dataset.tgBusy === '1') {
        event.preventDefault();
        event.stopImmediatePropagation();
        return;
      }

      link.dataset.tgBusy = '1';
      link.classList.add('tg-action-busy');
      // Si l'utilisateur annule la confirmation, on ne peut pas le détecter de façon
      // fiable ici (chaque vue gère sa propre boîte de dialogue) : on redonne la main
      // après un court délai plutôt que de bloquer le lien définitivement.
      window.setTimeout(function () {
        link.dataset.tgBusy = '0';
        link.classList.remove('tg-action-busy');
      }, 1500);
    }, true);
  })();
</script>
