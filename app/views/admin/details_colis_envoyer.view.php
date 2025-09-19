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
          
            <!-- Breadcrumb -->
            <div class="page-breadcrumb d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3">
                <div class="breadcrumb-title pe-3 text-primary"><i class="bx bx-package me-1"></i> G-colis</div>
                <div class="ps-3 flex-grow-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active " aria-current="page">
                                Colis envoyés pour le car N° <?= htmlspecialchars($id_car) ?>
                                le <?= date('d/m/Y à H:i', strtotime($date_envoi)) ?>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto mt-2 mt-sm-0">
                    <a href="javascript:history.back()" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                        <i class="bx bx-left-arrow-alt me-1"></i>Retour
                    </a>
                </div>
            </div>
            <!-- /Breadcrumb -->

            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-hover table-bordered align-middle shadow-sm">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Nom du colis</th>
                                    <th>Nature</th>
                                    <th>Valeur</th>
                                    <th>Frais de transaction</th>
                                    <th>Date d'envoi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php foreach ($liste_colis as $colis): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($colis->nom_colis) ?></td>
                                        <td><?= htmlspecialchars($colis->nature) ?></td>
                                        <td><?= htmlspecialchars($colis->valeur) ?></td>
                                        <td><?= htmlspecialchars($colis->fraix_transaction) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($colis->date_enregistre)) ?></td>
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