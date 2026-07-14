<?php $this->view('admin/partials/headers') ?>

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        <?php $this->view('admin/partials/navbar')
        ?>
        <!--end top header-->

        <!--start sidebar -->
        <?php $this->view('admin/partials/sidebar')
        ?>
        <!--end sidebar -->

        <!--start content-->
        <main class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Profile</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Mes informations</li>
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
                <div class="container-fluid">

                    <!-- Header -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="user-profile-header-banner">
                                    <img src="<?= BASE_URL ?>/assets_site/img/acc.png"
                                        alt="Banner image"
                                        class="rounded-top"
                                        style="width: 100%; height: 400px; object-fit: cover;" />
                                </div>

                                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                                    <!-- Image de profil -->
                                    <div class="flex-shrink-0 mt-n5 mx-sm-4 mx-auto">
                                        <img src="<?= BASE_URL ?>/assets_site/img/reservation.png"
                                            alt="user image"
                                            class="rounded-circle user-profile-img"
                                            style="width:130px; height:130px; object-fit:cover; border:3px solid #fff;" />
                                    </div>

                                    <!-- Infos utilisateur -->
                                    <div class="flex-grow-1 mt-3 mt-sm-5">
                                        <div class="d-flex align-items-md-end align-items-sm-start align-items-center 
                                            justify-content-md-between justify-content-start mx-4 
                                            flex-md-row flex-column gap-4">
                                            <div class="user-profile-info">
                                                <h4><?= $_SESSION['nom'] ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--/ Header -->

                    <!-- Navbar pills -->
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                                <li class="nav-item">
                                    <a class="nav-link active" href="<?= BASE_URL?>/admin/Profils/index"><i
                                            class="ti-xs ti ti-user-check me-1"></i> Profile</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL?>/admin/Profils/changePassword"><i
                                            class="ti-xs ti ti-users me-1"></i>Change mot de pass</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL?>/admin/Profils/activites"><i class="ti-xs ti ti-user-check me-1"></i>
                                        Activite</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <!--/ Navbar pills -->

                    <!-- User Profile Content -->
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-5">
                            <!-- About User -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <small class="card-text text-uppercase">Profile Details</small>
                                    <ul class="list-unstyled mb-4 mt-3">
                                        <li class="d-flex align-items-center mb-3">
                                            <i class="ti ti-user text-heading"></i><span
                                                class="fw-medium mx-2 text-heading">Nom et Prenom:</span>
                                            <?= $_SESSION['nom'] ?> <span> </span>
                                        </li>
                                        <!-- <li class="d-flex align-items-center mb-3">
                                            <i class="ti ti-check text-heading"></i><span
                                                class="fw-medium mx-2 text-heading">Status:</span> <span>

                                            </span>
                                        </li> -->
                                        <li class="d-flex align-items-center mb-3">
                                            <i class="ti ti-crown text-heading"></i><span
                                                class="fw-medium mx-2 text-heading">Role:</span><?= $_SESSION['droit'] ?>
                                            <span></span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <i class="ti ti-flag text-heading"></i><span
                                                class="fw-medium mx-2 text-heading">Ville:</span> <span>
                                                <?= $_SESSION['ville'] ?></span>
                                        </li>

                                    </ul>

                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-phone-call"></i><span class="fw-medium mx-2 text-heading">Numero
                                            de gare:</span>
                                        <span><?= $_SESSION['numero_gare'] ?></span>
                                    </li>

                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-mail"></i><span class="fw-medium mx-2 text-heading">Email
                                            utilisateur</span>
                                        <span><?= $_SESSION['emailUser'] ?></span>
                                    </li>
                                    </ul>

                                </div>
                            </div>
                            <!--/ About User -->

                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-8">
                            <?php
                            //$errors=[]; $button_name='modifier'
                            ?>
                            <?php //require_once("partials/notification.php") ?>
                            <div class="card mb-4">
                                <h5 class="card-header"> Modifier profile</h5>
                                <!-- Account -->
                                <div class="card-body">

                                </div>

                                <div class="card-body">
                                    <form id="formAccountSettings" action="modifier_utilisateur.php" method="post">
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label for="firstName" class="form-label">Nom & Prenom</label>
                                                <input class="form-control" type="text" name="utilisateurs"
                                                    value="<?= $info_user['utilisateurs'] ?>" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="lastName" class="form-label">Email</label>
                                                <input class="form-control" type="text" name="emailUser" id="lastName"
                                                    value="<?= $info_user['emailUser'] ?>" />
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label for="organization" class="form-label">Ancien mot de pass</label>
                                                <input type="password" class="form-control" id="organization"
                                                    name="ancien_password" value="" />
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="submit" class="btn btn-primary me-2"
                                                name="modifier">Modifier</button>
                                            <?php
                                            //require_once('Partials/Alert.php') 
                                            ?>
                                            <!-- <button type="reset" class="btn btn-label-secondary">Cancel</button> -->
                                        </div>
                                    </form>
                                </div>
                                <!-- /Account -->
                            </div>
                        </div>
                    </div>
                    <!--/ User Profile Content -->
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