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
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-colis</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Enregistrement des colis</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges" class="btn btn-primary split-bg-primary  text-white"> Liste des colis</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row">

                <div class="col-xxl-12">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="col-xl-12 mx-auto">
                        <div id="stepper1" class="bs-stepper">
                            <div class="card border-top border-primary border-3">
                                <div class="card-header ">
                                    <div class="d-lg-flex flex-lg-row align-items-lg-center justify-content-lg-between"
                                        role="tablist">
                                        <div class="step" data-target="#test-l-1">
                                            <div class="step-trigger" role="tab" id="stepper1trigger1"
                                                aria-controls="test-l-1">
                                                <div class="bs-stepper-circle">1</div>
                                                <div class="">
                                                    <h5 class="mb-0 steper-title">Expediteurs</h5>
                                                    <p class="mb-0 steper-sub-title"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bs-stepper-line"></div>
                                        <div class="step" data-target="#test-l-2">
                                            <div class="step-trigger" role="tab" id="stepper1trigger2"
                                                aria-controls="test-l-2">
                                                <div class="bs-stepper-circle">2</div>
                                                <div class="">
                                                    <h5 class="mb-0 steper-title">Destinataire</h5>
                                                    <p class="mb-0 steper-sub-title"></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bs-stepper-line"></div>
                                        <div class="step" data-target="#test-l-4">
                                            <div class="step-trigger" role="tab" id="stepper1trigger4"
                                                aria-controls="test-l-4">
                                                <div class="bs-stepper-circle">4</div>
                                                <div class="">
                                                    <h5 class="mb-0 steper-title">Colis</h5>
                                                    <p class="mb-0 steper-sub-title"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="bs-stepper-content">
                                        <form method="post" id="formStepper" onsubmit="return validerFormulaire();">
                                            <!-- Étape 1 -->
                                            <div id="test-l-1" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger1">
                                                <div class="row g-3">
                                                    <div class="col-12 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="expediteur" class="form-label">Expéditeur</label>
                                                            <input type="text" class="form-control" id="expediteur" name="expediteur" placeholder="Expéditeur" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="numero_exp" class="form-label">Numéro expéditeur</label>
                                                            <input type="text" class="form-control" id="numero_exp" name="numero_exp"
                                                                placeholder="Numéro expéditeur" required oninput="verifierNumero(this, 'erreur_exp')">
                                                            <div id="erreur_exp" class="text-danger small mt-1"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-lg-12">
                                                        <div class="form-group">
                                                            <label for="email_exp" class="form-label">Email expéditeur</label>
                                                            <input type="email" class="form-control" id="email_exp" name="email_exp" placeholder="Email expéditeur" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <button type="button" class="btn btn-primary px-4" onclick="validateStep1()">Next<i class='bx bx-right-arrow-alt ms-2'></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Étape 2 -->
                                            <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">
                                                <div class="row g-3">
                                                    <div class="col-12 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="destinataire" class="form-label">Destinataire</label>
                                                            <input type="text" class="form-control" id="destinataire" name="destinataire" placeholder="Destinataire" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="numero_dest" class="form-label">Numéro destinataire</label>
                                                            <input type="text" class="form-control" id="numero_dest" name="numero_dest"
                                                                placeholder="Numéro destinataire" required oninput="verifierNumero(this, 'erreur_dest')">
                                                            <div id="erreur_dest" class="text-danger small mt-1"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-12">
                                                        <div class="form-group">
                                                            <label for="email_dest" class="form-label">Email destinataire</label>
                                                            <input type="email" class="form-control" id="email_dest" name="email_dest" placeholder="Email destinataire" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()"><i class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                                            <button type="button" class="btn btn-primary px-4" onclick="validateStep2()">Next<i class='bx bx-right-arrow-alt ms-2'></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Étape 3 -->
                                            <div id="test-l-4" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger4">
                                                <div class="row g-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mt-3">
                                                                <label class="form-label" for="nom_colis">Nom colis</label>
                                                                <input type="text" id="nom_colis" class="form-control" placeholder="Nom colis" name="nom_colis" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mt-3">
                                                                <label class="form-label" for="nature">Nature</label>
                                                                <input type="text" id="nature" class="form-control" placeholder="Nature" name="nature" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mt-3">
                                                                <label class="form-label" for="destination">Destination</label>
                                                                <select class="form-control" name="destination" id="destination" required>
                                                                    <option value="" disabled selected>Choisissez la destination</option>
                                                                    <?php foreach ($listes as $liste): if ($liste->idAgence !== $_SESSION['id_agence']) : ?>
                                                                            <option value="<?= htmlspecialchars($liste->idAgence); ?>">
                                                                                <?= htmlspecialchars($liste->localite . ' ( ' . $liste->numeroGare . ' )'); ?>
                                                                            </option>
                                                                    <?php endif;
                                                                    endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mt-3">
                                                                <label class="form-label" for="valeur">Valeur</label>
                                                                <input type="number" class="form-control" id="valeur" name="valeur" min="0" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mt-3">
                                                            <label class="form-label" for="fraix_transaction">Frais transaction</label>
                                                            <input class="form-control" type="number" id="fraix_transaction" name="fraix_transaction" readonly min="0" />
                                                        </div>
                                                        <div class="col-md-6 mt-3">
                                                            <label class="form-label" for="code_colis">Code colis</label>
                                                            <input class="form-control" type="text" id="code_colis" name="code_colis"
                                                                value="<?= $code_colis ?>" readonly />
                                                        </div>

                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()"><i class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                                            <button type="submit" class="btn btn-success px-4" name="envoi">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

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
    <!-- JS -->
    <style>
        .input-error {
            border: 1px solid red;
        }

        .error-message {
            color: red;
            margin-top: 5px;
        }
    </style>
    <!-- Scripts de validation -->
    <script src="<?= BASE_URL ?>/assets/js/scrip_validations.js"></script>
    <script>
       
    </script>

</body>

</html>