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
        <main class="page-content ">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Configuration</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Compagnies</li>
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
                                        <h5 class="modal-title text-white">Enregistrement des compagnies</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-dart">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="col-md-12">
                                                <label for="tel" class="form-label">Nom du compagnie
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                    value="" name="nom_compagnie"
                                                    placeholder="Nom du compagnie" required>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="bsValidation10"
                                                        class="form-label">Description<span
                                                            class="text-danger scale5 ms-2">*</span></label>
                                                    <input type="text" class="form-control" id="tel"

                                                        value="" name="libele" placeholder="Libele" required>

                                                </div>
                                                <div class="col-md-4">
                                                    <label for="bsValidation11" class="form-label">Slogant<span
                                                            class="text-danger scale5 ms-2">*</span></label>
                                                    <input type="text" class="form-control" id=""
                                                        value=""
                                                        name="slogant" placeholder="Slogant" required>

                                                </div>
                                                <div class="col-md-2">
                                                    <label for="bsValidation10"
                                                        class="form-label">Logo</label>
                                                    <input type="file" class="form-control" id="tel"

                                                        value="" name="logo">
                                                    <small id="messageErreur" class="text-danger"></small>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary" name="save">Enregistre</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>&nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary split-bg-primary"><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row">
                <div class="col-xxl-3">
                    <div class="card custom-card  border-primary border-4">
                        <div class="card-header ">
                            <div class="card-title">
                                Generale
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs flex-column vertical-tabs-3" role="tablist">
                                <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                                    <li class="nav-item">
                                        <a class="nav-link active text-break" role="tab"
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
                                    <a class="nav-link text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Liste_gares"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Gares
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
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
                                    <a class="nav-link  text-break mb-0" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Add_liste_horaire"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Horaire
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
                                    <a class="nav-link  text-break" role="tab"
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
                    <div class="card custom-card  border-primary border-4">
                        <div class="card-header  ">
                            <div class="card-title">
                                Liste des compagnies
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered  table-hover-effect table-custom-header" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nom compagnie</th>
                                                <th>Libele</th>
                                                <th>Slogant</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($liste as $listes): ?>
                                                <tr>
                                                    <td><?= $listes->nom_compagnie ?></td>
                                                    <td><?= $listes->libele ?></td>
                                                    <td><?= $listes->slogant ?></td>
                                                    <td class="">
                                                        <div class="dropup text-center">
                                                            <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                &#8943; <!-- Trois points horizontaux -->
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item edit-btn"
                                                                    data-bs-toggle="modal" data-bs-target="#exampleDangerModal1"
                                                                    data-id_compagnie="<?= $listes->id_compagnie ?>"
                                                                    data-nom_compagnie="<?= htmlspecialchars($listes->nom_compagnie, ENT_QUOTES) ?>"
                                                                    data-libele="<?= htmlspecialchars($listes->libele, ENT_QUOTES) ?>"
                                                                    data-slogant="<?= htmlspecialchars($listes->slogant, ENT_QUOTES) ?>"
                                                                    href="#">Modifier</a>
                                                                <a class="dropdown-item delete-button"
                                                                    href="<?= BASE_URL ?>/Compagnies/delete/<?= $listes->id_compagnie ?>">
                                                                    Supprimer
                                                                </a>


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
    <div class="modal fade" id="exampleDangerModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Modification du compagnie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dart">
                    <form action="<?= BASE_URL ?>/Compagnies/edit" method="post" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <label for="tel" class="form-label">Nom du compagnie
                            </label>
                            <input type="text" class="form-control"
                                value="" name="nom_compagnie"
                                id="inputnomCompagnie" required>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="bsValidation10"
                                    class="form-label">Libele</label>
                                <input type="text" class="form-control" id="inputlibele"

                                    value="" name="libele" required>

                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation11" class="form-label">Slogant</label>
                                <input type="text" class="form-control" id="inputslogant"
                                    value=""
                                    name="slogant" required>

                            </div>
                            <input type="hidden" name="id_compagnie" id="inputidCompagnie">

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" name="edit">Modifier</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <?php $this->view('partials/foot') ?>
    <script src="<?= BASE_URL ?>/mon_js/scrip_compagnie.js"></script>
    <script src="<?= BASE_URL ?>/mon_js/alert_delete.js"></script>
</body>

</html>