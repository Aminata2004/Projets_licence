<?php $this->view('admin/partials/headers') ?>

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        <?php $this->view('admin/partials/navbar')
        ?>
        <!--end top header-->

        <!--start sidebar -->
        <?php $this->view('admin/partials/sidebar')
        ?>
        <!--end sidebar -->

        <!--start content-->

        <main class="page-content">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Rapport Mensuel</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Totaux des billets réservés</li>
                        </ol>
                    </nav>
                </div>
             
            </div>

            <!-- Section Totaux des billets -->
 
            <div class="row g-4">
                <!-- Billets présentiels -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-start border-4 border-primary shadow-sm h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1 text-muted fw-medium">Présentiel</p>
                                <h3 class="mb-0 text-primary"><?= $data['totalPresentiel'] ?? 0 ?></h3>
                            </div>
                            <i class="bi bi-box-seam fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>

                <!-- Billets en ligne -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-start border-4 border-success shadow-sm h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1 text-muted fw-medium">En ligne</p>
                                <h3 class="mb-0 text-success"><?= $data['totalEnLigne'] ?? 0 ?></h3>
                            </div>
                            <i class="bi bi-send-check fs-1 text-success"></i>
                        </div>
                    </div>
                </div>

                <!-- Billets reportés -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-start border-4 border-warning shadow-sm h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1 text-muted fw-medium">Reportés</p>
                                <h3 class="mb-0 text-warning"><?= $data['totalRepporte'] ?? 0 ?></h3>
                            </div>
                            <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Statistiques mensuelles -->


            <!-- Section Statistiques mensuelles -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">📅 Statistiques mensuelles</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0 text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mois</th>
                                            <th>Type</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['statsParMois'])): ?>
                                            <?php foreach ($data['statsParMois'] as $row): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['mois']) ?></td>
                                                    <td>
                                                        <?php
                                                        $type = $row['type_reservation'];
                                                        if ($type === 'repporte') echo "<span class='badge bg-warning text-dark'>Billets reportés</span>";
                                                        elseif ($type === 'presentiel') echo "<span class='badge bg-primary'>Présentiel</span>";
                                                        else echo "<span class='badge bg-success'>En ligne</span>";
                                                        ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['total']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Aucune donnée disponible</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Situation des caisses et billets par gare -->
            <div class="row mt-4">
                <div class="col-12">
                    <h4 class="mb-3">🏦 Situation des caisses et billets pour le mois <?= date('F Y') ?></h4>
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle text-center mb-0">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Localité</th>
                                            <th>Gare</th>
                                            <th>Type de réservation</th>
                                            <th>Total payé (FCFA)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['billetsParGare'])): ?>
                                            <?php foreach ($data['billetsParGare'] as $row): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['localite']) ?></td>
                                                    <td><?= htmlspecialchars($row['num_gare']) ?></td>
                                                    <td>
                                                        <?php
                                                        $type = ucfirst($row['status_reservation']);
                                                        if ($type === 'Repporte') echo "<span class='badge bg-warning text-dark'>Billets reportés</span>";
                                                        elseif ($type === 'Presentiel') echo "<span class='badge bg-primary'>Présentiel</span>";
                                                        else echo "<span class='badge bg-success'>En ligne</span>";
                                                        ?>
                                                    </td>
                                                    <td class="fw-bold text-end"><?= number_format($row['total'], 0, ',', ' ') ?> FCFA</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Aucune donnée disponible pour ce mois</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





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