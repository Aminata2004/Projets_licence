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
                            <li class="breadcrumb-item active" aria-current="page">Change profile</li>
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
                                    <a class="nav-link " href="<?= BASE_URL?>/admin/Profils/index"><i
                                            class="ti-xs ti ti-user-check me-1"></i> Profile</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link active" href="<?= BASE_URL?>/admin/Profils/changePassword"><i
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
                    <?php $this->view("admin/set_flash") ?>
                    <!-- User Profile Content -->
                       <div class="card mb-4">
                        <h5 class="card-header">Change mot de pass</h5>
                        <div class="card-body">
                            <form id="formAccountSettings" method="post" action="">
                                <div class="row">
                                    <div class="mb-3 col-md-6 form-password-toggle">
                                        <label class="form-label" for="currentPassword">Ancien mot de pass</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" class="form-control border-end-0"
                                                id="ancien_password" name="ancien_password"> <a href="javascript:;"
                                                class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6 form-password-toggle">
                                        <label class="form-label" for="newPassword">Nouveau mot de pass</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" class="form-control border-end-0"
                                                id="new_password" name="new_password"> <a href="javascript:;"
                                                class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-6 form-password-toggle">
                                        <label class="form-label" for="confirmPassword">Confirmer nouveau mot
                                            pass</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" class="form-control border-end-0"
                                                id="confirme_new_passe" name="confirme_new_passe"> <a href="javascript:;"
                                                class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                        </div>
                                    </div>

                                    <div>
                                        <button type="submit" class="btn btn-primary me-2"
                                            name="changer_password">Modifier</button>
                                    </div>
                                </div>
                            </form>
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