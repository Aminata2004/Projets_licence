<?php $this->view('partials/headers') ?>

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
                <div class="breadcrumb-title pe-3">G-colis</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Reclamation de colis</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/Envoi_colis/liste_colis_envoyer" class="btn btn-primary split-bg-primary text-white"> Voir la liste</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <?php $this->view("set_flash") ?>
            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="nom_colis">Numero de code</label>
                                <input type="text" id="nom_colis" class="form-control" placeholder="Numero du code" name="code" required />
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="d-flex align-items-center gap-3">
                              
                                <button type="submit" class="btn btn-primary px-4" name="envoi">Valider</button>
                            </div>
                        </div>
                    </form>
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


</body>

</html>