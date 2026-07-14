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
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
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

                        <a href="javascript:history.back()" class="btn btn-primary split-bg-primary"><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row">

                <div class="col-xxl-12">
                    <?php $this->view("admin/set_flash") ?>
                    <form class="row g-3 " method="post">
                        <div class="card custom-card border-top border-primary border-3">
                            <div class="card-header  ">
                                <div class="card-title ">
                                    Expediteurs
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="form-label">Utilisateurs</label>
                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Nom & prenom" name="utilisateurs" required>

                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationCustom02" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="validationCustom02" placeholder="Email" name="emailUser" required>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="validationCustomUsername" class="form-label">Mot de passe</label>
                                    <div class="input-group has-validation">
                                        <input type="password" class="form-control" id="" name="motPasse" required>

                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="card custom-card border-top border-primary border-3">
                            <div class="card-header  ">
                                <div class="card-title ">
                                    Destinataire
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="form-label">Utilisateurs</label>
                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Nom & prenom" name="utilisateurs" required>

                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationCustom02" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="validationCustom02" placeholder="Email" name="emailUser" required>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="validationCustomUsername" class="form-label">Mot de passe</label>
                                    <div class="input-group has-validation">
                                        <input type="password" class="form-control" id="" name="motPasse" required>

                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="card custom-card border-top border-primary border-3">
                            <div class="card-header  ">
                                <div class="card-title ">
                                    Colis
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="form-label">Utilisateurs</label>
                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Nom & prenom" name="utilisateurs" required>

                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationCustom02" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="validationCustom02" placeholder="Email" name="emailUser" required>

                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="form-label">Utilisateurs</label>
                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Nom & prenom" name="utilisateurs" required>

                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationCustom02" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="validationCustom02" placeholder="Email" name="emailUser" required>

                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="form-label">Utilisateurs</label>
                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Nom & prenom" name="utilisateurs" required>

                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationCustom02" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="validationCustom02" placeholder="Email" name="emailUser" required>

                                    </div>
                                </div>
                                <!-- <div class="col-md-12">
                                    <label for="validationCustomUsername" class="form-label">Mot de passe</label>
                                    <div class="input-group has-validation">
                                        <input type="password" class="form-control" id="" name="motPasse" required>

                                    </div>
                                </div> -->
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


    <?php $this->view('admin/partials/foot') ?>
    <!-- JS -->

</body>

</html>