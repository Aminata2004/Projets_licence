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
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-colis</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Liste des colis </li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges/ajouter_colis/" class="btn btn-primary split-bg-primary text-white"> + Ajouter</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nom colis</th>
                                    <th>Nature</th>
                                    <th>Valeur</th>
                                    <th>Fraix de transaction</th>
                                    <th>Destination</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <!-- ajoute ici les autres colonnes -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php $this->view('admin/helpers') ?>
                                <?php foreach ($liste_colis as $colis) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($colis['nom_colis']) ?></td>
                                        <td><?= htmlspecialchars($colis['nature']) ?></td>
                                        <td><?= htmlspecialchars($colis['valeur']) ?></td>
                                        <td><?= htmlspecialchars($colis['fraix_transaction']) ?></td>
                                        <td><?= htmlspecialchars($colis['destination']) ?></td>
                                        <td><?= afficherBadgeStatus($colis['status']) ?></td>
                                        <td class=" ">
                                            <div class="dropup ">
                                                <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                    &#8943; <!-- Trois points horizontaux -->
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                     <a class="dropdown-item" href="#">Details</a>
                                                    <a class="dropdown-item" href="#">Modifier</a>
                                                    <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Colis_prise_en_charges/imprimer_recu/<?= $colis['id_colis'] ?>" target="_blank">
                                                        Imprimer le reçu
                                                    </a>

                                                </div>
                                            </div>
                                        </td>
                                        <!-- autres colonnes -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

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