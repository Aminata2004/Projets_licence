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
                <div class="breadcrumb-title pe-3">G-reservation</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Liste des tickets en entente</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>
                    </div>
                </div>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <div class="card">
                <div class="card-body">
                    <div class="tab-content py-3 table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Client</th>
                                    <th>Destionation</th>
                                    <th>Nbr de passages</th>
                                    <th>heure de depart</th>
                                    <th>Jour de voyage</th>
                                    <th>Date d'expiration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($liste_entente as $item): ?>
                                    <tr class="text-center">
                                        <td><?= htmlspecialchars($item->Client) ?></td>
                                        <td><?= htmlspecialchars($item->destinationId) ?></td>
                                        <td><?= htmlspecialchars($item->nombrePassages) ?></td>
                                        <td><?= htmlspecialchars($item->Heur_departs) ?></td>
                                        <td><?= htmlspecialchars($item->jourVoyage) ?></td>
                                        <td><?= htmlspecialchars($item->date_expiration) ?></td>
                                        <td class=" ">
                                            <div class="dropup ">
                                                <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                    &#8943; <!-- Trois points horizontaux -->
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Liste_ententes/validation/<?= $item->idBillets ?>">
                                                        Valider
                                                    </a>


                                                    <a class="dropdown-item" href="#">
                                                        Annuler
                                                    </a>

                                                </div>
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