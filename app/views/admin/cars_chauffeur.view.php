<?php $this->view('admin/partials/header') ?>

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
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Configuration</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Gares</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleDangerModal">+ Ajouter</button>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleDangerModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content ">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white">Ajout de cars</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-dart">
                                        <form action="" method="post">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">Numero de car</label>
                                                    <input type="number" class="form-control" id="validationCustom01" placeholder="Numero de car" name="numero_car" required autocomplete="off">

                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom02" class="form-label">Matricule</label>
                                                    <input type="text" class="form-control" id="validationCustom02" placeholder="Matricule" name="matriculle" required autocomplete="off">

                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="validationCustomUsername" class="form-label">Nombre de place</label>
                                                <div class="input-group has-validation">
                                                    <input type="number" class="form-control" id="validationCustomUsername" placeholder="Nombre de place" name="nbr_place" required autocomplete="off">

                                                </div>
                                            </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="save">Enregistre</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary split-bg-primary"><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row">
                <div class="col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                Generale
                            </div>
                        </div>
                        <div class="card-body">
                            Modifier<ul class="nav nav-tabs flex-column vertical-tabs-3" role="tablist">
                                <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-break" role="tab"
                                            aria-current="page" href="<?= BASE_URL ?>/admin/Compagnies"
                                            aria-selected="true">
                                            <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Compagnie
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <a class="nav-link  text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/admin/Configurations"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Utilisateur
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/admin/Liste_gares"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Gares
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-break mb-0" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_escales"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Escale
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link  text-break mb-0" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_trajets"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Trajets
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link  text-break mb-0" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_horaire"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Horaire
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link active text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/admin/Cars_chauffeurs"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Cars & Chauffeurs
                                    </a>
                                </li>

                                   <li class="nav-item mt-2">
                                    <a class="nav-link  text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/admin/Compagnies/place_limite"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Place limite
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-9">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                Liste des cars
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills nav-pills-primary mb-3" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" href="<?= BASE_URL ?>/Cars_chauffeurs" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Cars</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" href="<?= BASE_URL ?>/Chauffeurs_cars" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Chauffers</div>
                                            </div>
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="example" class="table table-striped table-bordered table-bordered table-hover-effect table-custom-header" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Numero du car</th>
                                                        <th>Numero de matricule</th>
                                                        <th>Nombre de place</th>

                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($listeCar as $listeCars): ?>
                                                        <tr>
                                                            <td><?= $listeCars->numero_car ?></td>
                                                            <td><?= $listeCars->matriculle ?></td>
                                                            <td><?= $listeCars->nbr_place ?></td>
                                                            <td class=" ">
                                                                <div class="dropup text-center">
                                                                    <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        &#8943; <!-- Trois points horizontaux -->
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <a class="dropdown-item" href="#">Modifier</a>
                                                                        <a class="dropdown-item" href="#">Désactiver</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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

</body>

</html>