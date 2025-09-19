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
        <main class="page-content ">
            <!--breadcrumb-->
            <!-- Breadcrumb -->

            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3 text-primary">
                    <i class="bx bx-package me-1"></i> G-colis
                </div>
                <div class="ps-3 flex-grow-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Liste des colis</li>
                        </ol>
                    </nav>
                </div>

                <div class="ms-auto mt-2 mt-sm-0 d-flex gap-2">
                    <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges/ajouter_colis/"
                        class="btn btn-sm btn-success rounded-pill shadow-sm">
                        <i class="bx bx-plus me-1"></i> Ajouter
                    </a>
                    <a href="javascript:history.back()"
                        class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                        <i class="bx bx-left-arrow-alt"></i> Retour
                    </a>
                </div>
            </div>

            <!-- End Breadcrumb -->

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bx bx-list-ul me-1"></i> Liste des colis enregistrés
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example"
                            class="table table-hover align-middle mb-0 table-striped table-bordered rounded-3">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Nom colis</th>
                                    <th>Nature</th>
                                    <th>Valeur</th>
                                    <th>Frais de transaction</th>
                                    <th>Destination</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php $this->view('admin/helpers') ?>
                                <?php foreach ($liste_colis as $colis) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($colis['nom_colis']) ?></td>
                                        <td><?= htmlspecialchars($colis['nature']) ?></td>
                                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars($colis['valeur']) ?></span></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($colis['fraix_transaction']) ?></span></td>
                                        <td><?= htmlspecialchars($colis['destination']) ?></td>
                                        <td><?= afficherBadgeStatus($colis['status']) ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="bx bx-show-alt me-2"></i>Détails
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="bx bx-edit me-2"></i>Modifier
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="<?= BASE_URL ?>/admin/Colis_prise_en_charges/imprimer_recu/<?= $colis['id_colis'] ?>"
                                                            target="_blank">
                                                            <i class="bx bx-printer me-2"></i>Imprimer le reçu
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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

</body>

</html>