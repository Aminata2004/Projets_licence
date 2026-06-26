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
            <!--breadcrumb-->
            <div class="page-breadcrumb d-flex align-items-center justify-content-between mb-3 p-3  ">
                <!-- Titre + fil d’Ariane -->
                <div class="d-flex align-items-center">
                    <div class="breadcrumb-title pe-3 text-primary">
                        <i class="bx bx-calendar-event me-1"></i> G-Programme
                    </div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0 small">
                                <li class="breadcrumb-item">
                                    <a href="<?= BASE_URL ?>/admin/dashboard" class="text-muted text-decoration-none">
                                        <i class="bx bx-home-alt"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active  text-dark" aria-current="page">
                                    Détails de la programmation
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="btn-group">

                    <a href="javascript:history.back()" class="btn btn-sm btn-outline-primary shadow-sm ms-2">
                        <i class="bx bx-left-arrow-alt"></i> Retour
                    </a>
                </div>
            </div>

            <!--end breadcrumb-->
            <!-- <div class="row">

                <div class="col-xxl-12">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="card custom-card">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                            <h5 class="mb-0 fw-bold">Détails de la programmation</h5>

                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Numéro de car</th>
                                        <th>Nombre de places</th>
                                        <th>Itinéraire</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($details as $detail) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($detail->numero_car) ?></td>
                                            <td><?= htmlspecialchars($detail->nbr_place) ?></td>
                                            <td><?= htmlspecialchars($detail->depart) ?> - <?= htmlspecialchars($detail->destination) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div> -->
            <!-- Modal -->
            <div class="row">
                <div class="col-xxl-12">
                    <?php $this->view("admin/set_flash") ?>

                    <div class="card custom-card shadow-sm">
                        <div class="card-header bg-primary text-white rounded-top">
                            <h5 class="mb-0 fw-bold">Détails de la programmation</h5>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <?php foreach ($details as $detail) : ?>
                                    <div class="col-md-4">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-primary mb-2">
                                                    🚍 Car N° <?= htmlspecialchars($detail->numero_car) ?>
                                                </h6>

                                                <p class="mb-1">
                                                    <strong>Places :</strong>
                                                    <?= htmlspecialchars($detail->nbr_place) ?>
                                                </p>

                                                <p class="mb-0">
                                                    <strong>Itinéraire :</strong><br>
                                                    <?= htmlspecialchars($detail->depart) ?>
                                                    →
                                                    <?= htmlspecialchars($detail->destination) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <!--end row-->
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
    <!-- Initialisation Select2 -->

</body>

</html>