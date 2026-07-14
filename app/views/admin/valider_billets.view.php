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
        <main class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-reservation</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Validation de billets </li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/admin/Liste_ententes" class="btn btn-primary split-bg-primary  text-white"> Liste des billets en ligne</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0 text-primary">Espace de validation</h5>
                        </div>
                   
                        <div class="card-body">
                            <div class="form-validation">
                                <form action="" method="post">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Nom & Prenom</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= $listeticked_validations->Client ?>"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Destination</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= $listeticked_validations->destinationId ?>"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">NBR passagers</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= $listeticked_validations->nombrePassages ?>"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">jourVoyage</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= $listeticked_validations->jourVoyage ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Heure de depart</label>
                                                    <input type="text" class="form-control"
                                                        id="validationCustom07" name="jourVoyage"
                                                        value="<?= $listeticked_validations->Heur_departs ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Numero de paiement</label>
                                                    <input type="text" class="form-control"
                                                        name="numeroPaiement"
                                                        value="<?= $listeticked_validations->numeroPaiement ?>"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="text" class="form-control"
                                                        id="validationCustom07" name="jourVoyage"
                                                        value="<?= $listeticked_validations->emailClient ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Fraix de transport</label>
                                                    <input type="text" class="form-control"
                                                        id="validationCustom07" name="jourVoyage"
                                                        value="<?= $listeticked_validations->montant_payer ?>"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Confirmer le numero de
                                                        paiement</label>
                                                    <input type="tel" class="form-control" id=""
                                                        name="confirme" value="" maxlength="11"
                                                        >
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name='id_reservation' class="form-control"
                                            value="<?= $listeticked_validations->id_reservation ?>">
                                        <input type="hidden" name='numeroBillets' class="form-control"
                                            value="SMT<?= date("ismd") ?>">

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary"
                                                name="validation">Valider la reservation</button>
                                        </div>

                                    </div>
                                </form>
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
    <!-- JS -->
</body>
</html>