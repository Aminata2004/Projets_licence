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
        <main class="page-content ">
            <!--breadcrumb-->
            <!-- <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-colis</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Liste des colis envoyer</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/admin/Envoi_colis/envoi_colis" class="btn btn-primary split-bg-primary text-white">
                            + Envoyer un colis
                        </a>
                        &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div> -->

            <!-- Breadcrumb -->
            <div class="page-breadcrumb d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3">
                <div class="breadcrumb-title pe-3 text-primary"><i class="bx bx-package me-1"></i> G-colis</div>
                <div class="ps-3 flex-grow-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active " aria-current="page">Liste des colis envoyés</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto mt-2 mt-sm-0 d-flex gap-2">
                    <a href="<?= BASE_URL ?>/admin/Envoi_colis/envoi_colis" class="btn btn-sm btn-success rounded-pill shadow-sm">
                        <i class="bx bx-send me-1"></i> Envoyer un colis
                    </a>
                    <a href="javascript:history.back()" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                        <i class="bx bx-left-arrow-alt"></i>Retour
                    </a>
                </div>
            </div>
            <!-- /Breadcrumb -->

            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover table-striped table-bordered align-middle shadow-sm">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Date d'envoi</th>
                                    <th>Numéro du car</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php foreach ($liste_colis_envoyer as $colis): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($colis->dates) ?></td>
                                        <td>Car n°<?= htmlspecialchars($colis->numero_car) ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Envoi_colis/envoi_colis?id_car=<?= $colis->numero_car ?>">
                                                            <i class="bx bx-plus me-2"></i> Ajouter
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Envoi_colis/details_colis_envoyer?id_car=<?= $colis->numero_car ?>&date=<?= $colis->dates ?>">
                                                            <i class="bx bx-info-circle me-2"></i> Détails / Changer de car
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger annuler-envoi-btn"
                                                            href="<?= BASE_URL ?>/admin/Envoi_colis/annuler_envoi?id_car=<?= $colis->numero_car ?>&date=<?= $colis->dates ?>">
                                                            <i class="bx bx-trash me-2"></i> Annuler l'envoi
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
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
    <script>
        document.querySelectorAll('.annuler-envoi-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                const url = this.getAttribute('href');
                Swal.fire({
                    title: "Annuler cet envoi ?",
                    text: "Les colis de ce lot redeviendront disponibles pour un nouvel envoi.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, annuler',
                    cancelButtonText: 'Retour',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>

</body>

</html>