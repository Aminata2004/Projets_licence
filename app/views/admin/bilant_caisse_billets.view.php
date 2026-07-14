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

            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3 fw-bold text-primary">💰 Gestion du Caisse</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 bg-transparent">
                            <li class="breadcrumb-item">
                                <a href="javascript:;" class="text-decoration-none text-secondary">
                                    <i class="bx bx-home-alt"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active fw-semibold" aria-current="page">Bilan des Billets</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Section Totaux des billets -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body border-bottom border-2 border-primary">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" href="<?= BASE_URL ?>/admin/Caisse/bilant_caisse_billets" role="tab">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon me-2">
                                        <i class="fadeIn animated bx bx-list-check font-20"></i>
                                    </div>
                                    <span class="fw-semibold">Bilant des Billets</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/Caisse/bilant_caisse_colis" role="tab">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon me-2">
                                        <i class="fadeIn animated bx bx-package font-20"></i>
                                    </div>
                                    <span class="fw-semibold">Bilant des Colis</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">

                    <div class="tab-content py-3 table-responsive">
                        <table id="example" class="table table-hover table-striped align-middle text-center">
                            <thead class="table-primary text-uppercase">
                                <tr>
                                    <th>Référence Caisse</th>
                                    <th>Localité</th>
                                    <th>N° Gare</th>
                                    <th>Situation Journalière</th>
                                    <th>Situation Mensuelle</th>
                                    <th>État Actuel du Caisse</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($liste_caisse)) : ?>
                                    <?php foreach ($liste_caisse as $caisse) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($caisse->reference_caise) ?></td>
                                            <td><?= htmlspecialchars($caisse->localite) ?></td>
                                            <td><?= htmlspecialchars($caisse->numeroGare) ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= number_format($caisse->total_jour, 0, ',', ' ') ?> FCFA
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    <?= number_format($caisse->total_mois, 0, ',', ' ') ?> FCFA
                                                </span>
                                            </td>
                                            <td>
                                                <?= number_format($caisse->montant_billets ?? 0, 0, ',', ' ') ?> FCFA
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bx bx-show"></i> Voir
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Aucune caisse trouvée</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>


                        </table>
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