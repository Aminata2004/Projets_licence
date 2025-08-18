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
                            <li class="breadcrumb-item active" aria-current="page">Utilisateur</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">


                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleDangerModal">+ Ajouter</button>
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
                                    <a class="nav-link text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/admin/Liste_gares"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Gares
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  text-break mb-0" role="tab"
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
                                    <a class="nav-link text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/admin/Programmation_cars"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Programmation des cars
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link active text-break" role="tab"
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
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Le nombre de place </p>
                                    <?php foreach ($liste_place as $liste_places):
                                    ?>
                                        <h4 class="my-1"><?= $liste_places->place_minumale ?></h4>
                                    <?php endforeach
                                    ?>
                                </div>
                                <div class=" add-button position-fixed  end-0 me-5  " data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop"
                                    data-place_minumale="<?= $liste_places->place_minumale ?>"
                                    data-id_place_minumale="<?= $liste_places->id_place_minumale ?>"><i
                                        class=' bx bx-message-square-edit fs-1'></i>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end row-->
        </main>
        <!--end page main-->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel">Modal title
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= BASE_URL ?>/Compagnies/edit1" method="post">
                            <div class="col-md-12">
                                <label for="bsValidation3" class="form-label">N° de place mumimale<span
                                        class="text-danger scale5 ms-2">*</span></label>
                                <input type="text" class="form-control" id="nameplace_minumale" name="place_minumale" required>
                                <input type="hidden" class="form-control" id="idplace_minumale" name="id_place_minumale">

                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="edit" class="btn btn-primary">Modifier</button>

                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->


    </div>
    <!--end wrapper-->

    <?php $this->view('admin/partials/foot') ?>
    <script>
        $(document).ready(function() {
            // Lorsque le bouton "Ajouter" est cliqué
            $('.add-button').click(function() {
                // Récupérer les attributs de données du lien cliqué
                var place_minumale = $(this).data('place_minumale');
                var id_minumale = $(this).data('id_place_minumale');

                // Remplir le champ du modal avec les données
                $('#nameplace_minumale').val(place_minumale);
                $('#idplace_minumale').val(id_minumale)

                // Afficher le modal
                $('#staticBackdrop').modal('show');
            });

        });
    </script>
</body>

</html>