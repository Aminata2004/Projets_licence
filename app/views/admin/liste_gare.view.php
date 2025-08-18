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
                        <a href="<?= BASE_URL ?>/admin/Liste_gares/add_gares" class="btn btn-primary split-bg-primary">+ Ajouter</a>
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
                            <ul class="nav nav-tabs flex-column vertical-tabs-3" role="tablist">
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
                                    <a class="nav-link  active text-break" role="tab"
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
                                    <a class="nav-link  text-break" role="tab"
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
                                Liste des gares
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>N gare</th>
                                                <th>Localite</th>
                                                <th>Code marchant</th>
                                                <th>Tel</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($listes as $liste) : ?>
                                                <tr>
                                                    <td><?= $liste->numeroGare ?></td>
                                                    <td><?= $liste->localite ?></td>
                                                    <td><?= $liste->code ?></td>
                                                    <td><?= $liste->tel ?></td>
                                                    <td class=" ">
                                                        <div class="dropup text-center">
                                                            <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                &#8943; <!-- Trois points horizontaux -->
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item edit-btn"
                                                                    data-bs-toggle="modal" data-bs-target="#exampleDangerModal1"
                                                                    data-idAgence="<?= $liste->idAgence ?>"
                                                                   
                                                                     data-numero="<?= htmlspecialchars($liste->numeroGare, ENT_QUOTES) ?>"
                                                                    data-localite="<?= htmlspecialchars($liste->localite, ENT_QUOTES) ?>"
                                                                    data-code="<?= htmlspecialchars($liste->code, ENT_QUOTES) ?>"
                                                                    data-tel="<?= htmlspecialchars($liste->tel, ENT_QUOTES) ?>"
                                                                    href="">Modifier</a>
                                                                <a class="dropdown-item" href="#">Désactiver</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>

                                        </tbody>

                                    </table>
                                </div>
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
    <!--model pour la modification-->
    <div class="modal fade" id="exampleDangerModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Modification du gare</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dart">
                    <form class="row g-3 "
                        method="post" action="<?= BASE_URL ?>/Liste_gares/edit">
                        <input type="hidden" name="idAgence" id="inputidAgence">
                        <div class="col-md-6">
                            <label for="tel" class="form-label">Numero du gare
                            </label>
                            <input type="text" class="form-control"
                                value="" name="numeroGare" id="inputnumero"
                                placeholder=""  required >
                        </div>
                        <div class="col-md-6">
                            <label for="tel" class="form-label">Localite
                            </label>
                            <input type="text" class="form-control"
                                value="" name="localite"
                                placeholder="" required id="inputlocalite">
                        </div>
                        <div class="col-md-6">
                            <label for="tel" class="form-label">Code marchant du gare
                                </label>
                            <input type="text" class="form-control"
                                value="" name="code"
                                placeholder="" required id="inputcode">
                        </div>
                        <div class="col-md-6">
                            <label for="bsValidation10"
                                class="form-label">Numero d'orange</label>
                            <input type="text" class="form-control" id="tel"
                                maxlength="11" oninput="verifierNumero()"
                                value="" name="tel" placeholder="" required>
                            <small id="messageErreur" class="text-danger"></small>
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" name="edit"
                                    class="btn btn-primary px-4">Modifier</button>
                                <a href="" class="btn btn-info">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- la fin du model  -->

      <?php $this->view('admin/partials/foot') ?>
    <script src="<?= BASE_URL ?>/mon_js/scrip_agence.js"></script>
    <script src="<?= BASE_URL ?>/mon_js/alert_delete.js"></script>

</body>

</html>