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
                            <li class="breadcrumb-item active" aria-current="page">Gestion des gares</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">

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
                                    <a class="nav-link active text-break" role="tab"
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
                                Espace d'enregistrement des gares
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="p-4 border rounded">
                                    <form class="row g-3 " onsubmit="return validerFormulaire()"
                                        method="post">

                                        <div class="col-md-6">
                                            <label for="bsValidation3" class="form-label">Localite<span
                                                    class="text-danger scale5 ms-2">*</span></label>
                                            <input type="text" class="form-control" id=""
                                                name="localite"
                                                value="" placeholder="ex:Segou" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tel" class="form-label">Libelle
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                value="" name="libele"
                                                placeholder="" required>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="tel" class="form-label">Code marchant du gare
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                value="" name="code"
                                                placeholder="ex:123489" required>

                                        </div>
                                        <div class="col-md-4">
                                            <label for="bsValidation10"
                                                class="form-label">Numero d'orange<span
                                                    class="text-danger scale5 ms-2">*</span></label>
                                            <input type="text" class="form-control" id="tel"
                                                maxlength="11" oninput="verifierNumero()"
                                                value="" name="tel" placeholder="ex:78907812" required>
                                            <small id="messageErreur" class="text-danger"></small>

                                        </div>
                                        <div class="col-md-2">
                                            <label for="bsValidation11" class="form-label">Numero
                                                de
                                                gars<span
                                                    class="text-danger scale5 ms-2">*</span></label>
                                            <input type="text" class="form-control" id=""
                                                value=""
                                                name="numeroGare" placeholder="ex:Gare I" required>
                                            <div class="invalid-feedback">
                                                Please select a valid State.
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <button type="submit" name="enregistre"
                                                    class="btn btn-primary px-4">Enregistre</button>
                                                <a href="<?= BASE_URL ?>/Liste_gares" class="btn btn-info">Voir la liste</a>
                                            </div>
                                        </div>

                                    </form>
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