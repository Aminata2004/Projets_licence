<?php $this->view('admin/partials/headers') ?>

<body>


  <!--start wrapper-->
  <div class="wrapper">
    <!--start top header-->
    <?php $this->view('admin/partials/navbar') ?>
    <!--end top header-->

    <!--start sidebar -->
    <?php $this->view('admin/partials/sidebar') ?>
    <!--end sidebar -->

    <!--start content-->
    <main class="page-content">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-xl-4 g-4 mb-4">

        <!-- Billets en présentiel -->
        <div class="col">
          <div class="card shadow-lg border-0 rounded-4 hover-shadow">
            <div class="card-body d-flex align-items-center p-4">
              <div>
                <p class="mb-1 text-muted fw-semibold">Billets en présentiel</p>
                <h4 class="mb-0 text-primary">
                  <?= htmlspecialchars($data['billetsJour']['presentiel']); ?>
                </h4>
                <small class="text-muted text-primary">
                  <i class="bi bi-calendar-day"></i> Aujourd'hui
                </small>
              </div>
              <div class="ms-auto fs-2 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                <i class="bi bi-person-badge"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Billets en ligne validés -->
        <div class="col">
          <div class="card shadow-lg border-0 rounded-4 hover-shadow">
            <div class="card-body d-flex align-items-center p-4">
              <div>
                <p class="mb-1 text-muted fw-semibold">Billets en ligne validés</p>
                <h4 class="mb-0 text-success">
                  <?= htmlspecialchars($data['billetsJour']['en_ligne']); ?>
                </h4>
                <small class="text-muted text-success">
                  <i class="bi bi-calendar-day"></i> Aujourd'hui
                </small>
              </div>
              <div class="ms-auto fs-2 text-white bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                <i class="bi bi-laptop"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Billets en attente de validation -->
        <div class="col">
          <div class="card shadow-lg border-0 rounded-4 hover-shadow">
            <div class="card-body d-flex align-items-center p-4">
              <div>
                <p class="mb-1 text-muted fw-semibold">Billets en attente</p>
                <h4 class="mb-0 text-danger">
                  <?= htmlspecialchars($data['billetsJour']['en_attente']); ?>
                </h4>
                <small class="text-muted text-danger">
                  <i class="bi bi-calendar-day"></i> Aujourd'hui
                </small>
              </div>
              <div class="ms-auto fs-2 text-white bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                <i class="bi bi-hourglass-split"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Voyages programmés -->
        <div class="col">
          <div class="card shadow-lg border-0 rounded-4 hover-shadow">
            <div class="card-body d-flex align-items-center p-4">
              <div>
                <p class="mb-1 text-muted fw-semibold">Voyages programmés</p>
                <h4 class="mb-0 text-warning">
                  <?= htmlspecialchars($data['voyagesJour']); ?>
                </h4>
                <small class="text-muted text-warning">
                  <i class="bi bi-calendar-day "></i> Aujourd'hui
                </small>
              </div>
              <div class="ms-auto fs-2 text-white bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                <i class="bi bi-bus-front"></i>
              </div>
            </div>
          </div>
        </div>

      </div>



      <!-- Graphique Billets -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between">
              <h5 class="mb-0">Statistiques des billets (présentiel, ligne, reportés)</h5>
              <i class="bi bi-three-dots-vertical"></i>
            </div>
            <div class="card-body">
              <div id="chart1" style="height:300px;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Indicateurs Colis -->
    <div class="row g-4 mb-4">

  <!-- Colis pris en charge -->
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-lg border-0 rounded-4 hover-shadow">
      <div class="card-body d-flex align-items-center p-4">
        <div>
          <p class="mb-1 text-muted fw-semibold">Colis pris en charge</p>
          <h4 class="mb-0 text-primary">
            <?= htmlspecialchars($data['colisMensuel']['prise_en_charge']); ?>
          </h4>
          <small class="text-primary">Aujourd'hui</small>
        </div>
        <div class="ms-auto fs-2 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
          <i class="bi bi-box-seam"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Colis en cours -->
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-lg border-0 rounded-4 hover-shadow">
      <div class="card-body d-flex align-items-center p-4">
        <div>
          <p class="mb-1 text-muted fw-semibold">Colis entente </p>
          <h4 class="mb-0 text-warning">
            <?= htmlspecialchars($data['colisMensuel']['en_cours']); ?>
          </h4>
          <small class="text-warning">Aujourd'hui</small>
        </div>
        <div class="ms-auto fs-2 text-white bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
          <i class="bi bi-truck"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Colis reçus -->
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-lg border-0 rounded-4 hover-shadow">
      <div class="card-body d-flex align-items-center p-4">
        <div>
          <p class="mb-1 text-muted fw-semibold">Colis recu</p>
          <h4 class="mb-0 text-success">
         <?= htmlspecialchars($data['colisMensuel']['recu'] ?? 0); ?>


          </h4>
          <small class="text-success">Aujourd'hui</small>
        </div>
        <div class="ms-auto fs-2 text-white bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
          <i class="bi bi-inbox"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Colis livrés -->
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-lg border-0 rounded-4 hover-shadow">
      <div class="card-body d-flex align-items-center p-4">
        <div>
          <p class="mb-1 text-muted fw-semibold">Colis livrés</p>
          <h4 class="mb-0 text-info">
            <?= htmlspecialchars($data['colisMensuel']['livre']); ?>
          </h4>
          <small class="text-info">Aujourd'hui</small>
        </div>
        <div class="ms-auto fs-2 text-white bg-info rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
          <i class="bi bi-check2-circle"></i>
        </div>
      </div>
    </div>
  </div>

</div>


      <!-- Statistiques Colis -->
      <div class="row mb-4">
        <div class="col-lg-8">
          <div class="card shadow-sm">
            <div class="card-header bg-transparent">
              <h5 class="mb-0">Statistiques colis (livrés, non livrés)</h5>
            </div>
            <div class="card-body">
              <div id="chart2" style="height:300px;"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card shadow-sm">
            <div class="card-header bg-transparent">
              <h5 class="mb-0">Répartition colis</h5>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between">Nouveaux <span class="badge bg-primary">25%</span></li>
              <li class="list-group-item d-flex justify-content-between">Complétés <span class="badge bg-warning">65%</span></li>
              <li class="list-group-item d-flex justify-content-between">En attente <span class="badge bg-success">10%</span></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Situation de Caisse -->


    </main>
    <!--end page main-->

    <!--start overlay-->
    <div class="overlay nav-toggle-icon"></div>
    <!--end overlay-->

    <!--Start Back To Top Button-->
    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    <!--End Back To Top Button-->



  </div>
  <!--end wrapper-->


  <?php $this->view('admin/partials/foot') ?>

</body>

</html>