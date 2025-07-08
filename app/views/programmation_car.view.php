<?php $this->view('partials/header') ?>

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        <?php $this->view('partials/navbar') ?>
        <!--end top header-->

        <!--start sidebar -->
        <?php $this->view('partials/sidebar') ?>
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
                            <li class="breadcrumb-item active" aria-current="page">Programmation du car</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleDangerModal">
                            Programmer un car
                        </button>
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
                            <div class="card-title ">
                                Generale
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs flex-column vertical-tabs-3" role="tablist">
                                <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-break" role="tab"
                                            aria-current="page" href="<?= BASE_URL ?>/Compagnies"
                                            aria-selected="true">
                                            <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Compagnie
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <a class="nav-link  text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Configurations"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Utilisateur
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Liste_gares"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Gares
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-break mb-0" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Add_liste_escales"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Escale
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link  text-break mb-0" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Add_liste_trajets"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Trajets
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link  text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Cars_chauffeurs"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Cars & Chauffeurs
                                    </a>
                                </li>

                                <li class="nav-item mt-2">
                                    <a class="nav-link  active text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Programmation_cars"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Programmation des cars
                                    </a>
                                </li>
                                   <li class="nav-item mt-2">
                                    <a class="nav-link  text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Compagnies/place_limite"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Place limite
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-9">
                    <?php $this->view("set_flash") ?>
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                Liste des cars programmer
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Numero de car</th>
                                                <th>Nbr de place</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($Select_car1 as $Select_cars) : ?>
                                                <tr>
                                                    <td> Car : <?= $Select_cars->numero_car ?></td>
                                                    <td> <?= $Select_cars->nbr_place ?></td>
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
                                                <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->


            <div class="modal fade" id="exampleDangerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Programmation du car</h5>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <div class="modal-body">
                                    <div class="col-12">
                                        <label class="form-label" for="formValidationName">Car</label>
                                        <select class="form-control " name="id_car">
                                            <option disabled>Choisissez le car</option>
                                            <?php foreach ($listeCar as $listeCars): ?>
                                                <option value="<?= $listeCars->id_car ?>">car : <?= $listeCars->numero_car ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-12 mt-4">
                                        <label class="form-label">Trajet a parcourire</label>
                                        <select class="form-control multiple-select" multiple="multiple" placeholder="Choisissez un ou plusieurs escale" name="idTrajet[]">
                                            <option value="" disabled>Choisissez un ou plusieurs trajet</option>
                                            <?php foreach ($listeTrajet as $listeTrajets): ?>
                                                <option
                                                    value="<?= htmlspecialchars($listeTrajets->idTrajet); ?>">
                                                    <?= htmlspecialchars($listeTrajets->depart . ' - ' . $listeTrajets->destination) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                        Annuler
                                    </button>
                                    <button type="submit" class="btn btn-primary" name="programmer_car">Enregistre</button>
                                </div>
                            </form>
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


    <?php $this->view('partials/foot') ?>
    <!-- Initialisation Select2 -->
    <script>
        $(document).ready(function() {
            $('.multiple-select').select2({
                dropdownParent: $('#exampleDangerModal')
            });
        });
    </script>
</body>

</html>