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
            <!-- Breadcrumb -->
            <div class="page-breadcrumb d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3">
                <div class="breadcrumb-title pe-3 text-primary"><i class="bx bx-package me-1"></i> G-colis</div>
                <div class="ps-3 flex-grow-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Mouvement des colis</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto mt-2 mt-sm-0">
                    <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges" class="btn btn-sm btn-primary rounded-pill shadow-sm me-2">
                        <i class="bx bx-list-ul me-1"></i> Liste des colis
                    </a>
                    <a href="javascript:history.back()" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                        <i class="bx bx-left-arrow-alt me-1"></i> Retour
                    </a>
                </div>
            </div>
            <!-- /Breadcrumb -->
            <div class="row">
                <div class="col-xxl-12">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="col-xl-12 mx-auto">
                        <div class="card border-0 shadow-sm rounded">
                            <div class="card-body">

                                <!-- Tabs -->
                                <ul class="nav nav-pills mb-4 justify-content-start" role="tablist">
                                    <li class="nav-item me-2" role="presentation">
                                        <a class="nav-link active d-flex align-items-center px-3 py-2" data-bs-toggle="pill" href="#info-pills-home" role="tab">
                                            <i class='bx bx-time-five me-2'></i> Colis en attente
                                        </a>
                                    </li>
                                    <li class="nav-item me-2" role="presentation">
                                        <a class="nav-link d-flex align-items-center px-3 py-2" data-bs-toggle="pill" href="#info-pills-profile" role="tab">
                                            <i class='bx bx-inbox me-2'></i> Colis reçu
                                        </a>
                                    </li>
                                    <li class="nav-item me-2" role="presentation">
                                        <a class="nav-link d-flex align-items-center px-3 py-2" data-bs-toggle="pill" href="#info-pills-contact" role="tab">
                                            <i class='bx bx-check-shield me-2'></i> Colis livré
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab Contents -->
                                <div class="tab-content" id="pills-tabContent">

                                    <!-- Colis en attente -->
                                    <div class="tab-pane fade show active" id="info-pills-home" role="tabpanel">
                                        <form action="" method="post">
                                            <div class="table-responsive mb-3">
                                                <table id="example" class="table table-striped table-hover align-middle  mb-0">
                                                    <thead class="table-light ">
                                                        <tr>
                                                            <th><input type="checkbox" id="selectAll"></th>
                                                            <th>Nom colis</th>
                                                            <th>Nature</th>
                                                            <th>Valeur</th>
                                                            <th>Frais de transaction</th>
                                                            <th>Destination</th>
                                                            <th>Status</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="">
                                                        <?php $this->view('admin/helpers') ?>
                                                        <?php foreach ($liste_colis as $c): ?>
                                                            <?php if ($c['destination'] === $_SESSION['ville']): ?>
                                                                <tr class="">
                                                                    <td><input type="checkbox" name="selected_colis[]" value="<?= (int)$c['id_colis'] ?>" class="form-check-input checkbox-car"></td>
                                                                    <td class="fw-medium"><?= htmlspecialchars($c['nom_colis']) ?></td>
                                                                    <td><?= htmlspecialchars($c['nature']) ?></td>
                                                                    <td><?= number_format($c['valeur'], 0, ',', ' ') ?> FCFA</td>
                                                                    <td><?= number_format($c['fraix_transaction'], 0, ',', ' ') ?> FCFA</td>
                                                                    <td><?= htmlspecialchars($c['destination']) ?></td>
                                                                    <td><?= htmlspecialchars($c['code_colis']) ?></td>
                                                                    <td><?= afficherBadgeStatus($c['status']) ?></td>
                                                                    <td>
                                                                        <div class="dropdown">
                                                                            <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown">
                                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                                            </a>
                                                                            <ul class="dropdown-menu dropdown-menu-end">

                                                                                <li><a class="dropdown-item" href="#"><i class="bx bx-block me-1"></i> Désactiver</a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif ?>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="d-flex justify-content-end mt-3">
                                                <button class="btn btn-success rounded-pill px-4" type="submit" name="reception">
                                                    <i class="bx bx-check me-1"></i> Réception
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Colis reçu -->
                                    <div class="tab-pane fade" id="info-pills-profile" role="tabpanel">
                                        <div class="d-flex justify-content-end mb-3">
                                            <form class="w-30 position-relative">
                                                <div class="position-absolute top-50 translate-middle-y ps-3">
                                                    <i class="bi bi-search text-secondary"></i>
                                                </div>
                                                <input class="form-control ps-5 rounded-pill" type="text" placeholder="Rechercher un colis...">
                                            </form>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="" class="table table-striped table-hover align-middle text-center mb-0">
                                                <thead class="table-light ">
                                                    <tr>
                                                        <th>Nom colis</th>
                                                        <th>Nature</th>
                                                        <th>Valeur</th>
                                                        <th>Frais de transaction</th>
                                                        <th>Provenance</th>
                                                        <th>Code colis</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($liste_colis_recue as $colis): ?>
                                                        <?php if ($colis['destination'] === $_SESSION['ville']): ?>
                                                            <tr>
                                                                <td><?= $colis['nom_colis'] ?></td>
                                                                <td><?= $colis['nature'] ?></td>
                                                                <td><?= number_format($colis['valeur'], 0, ',', ' ') ?> FCFA</td>
                                                                <td><?= number_format($colis['fraix_transaction'], 0, ',', ' ') ?> FCFA</td>
                                                                <td><?= $colis['provient_de'] ?></td>
                                                                <td><?= htmlspecialchars($colis['code_colis']) ?></td>
                                                                <td><?= afficherBadgeStatus($colis['status']) ?></td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown">
                                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu dropdown-menu-end">

                                                                            <li><a class="dropdown-item" href="#"> Details</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Colis livré -->
                                    <div class="tab-pane fade" id="info-pills-contact" role="tabpanel">
                                        <div class="d-flex justify-content-end mb-3">
                                            <form class="w-30 position-relative">
                                                <div class="position-absolute top-50 translate-middle-y ps-3">
                                                    <i class="bi bi-search text-secondary"></i>
                                                </div>
                                                <input class="form-control ps-5 rounded-pill" type="text" placeholder="Rechercher un colis...">
                                            </form>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="example3" class="table table-striped table-hover align-middle text-center mb-0">
                                                <thead class="table-light ">
                                                    <tr>
                                                        <th>Nom colis</th>
                                                        <th>Nature</th>
                                                        <th>Valeur</th>
                                                        <th>Frais de transaction</th>
                                                        <th>Destination</th>
                                                        <th>Date de livraison</th>
                                                        <th>Code colis</th>
                                                        <th>Status</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($liste_colis_livre as $colis_livre): ?>
                                                        <?php if ($colis_livre['destination'] === $_SESSION['ville']): ?>
                                                            <tr>
                                                                <td><?= $colis_livre['nom_colis'] ?></td>
                                                                <td><?= $colis_livre['nature'] ?></td>
                                                                <td><?= number_format($colis_livre['valeur'], 0, ',', ' ') ?> FCFA</td>
                                                                <td><?= number_format($colis_livre['fraix_transaction'], 0, ',', ' ') ?> FCFA</td>
                                                                <td><?= $colis_livre['destination'] ?></td>
                                                                <td><?= $colis_livre['date_livraison'] ?></td>
                                                                <td><?= $colis_livre['code_colis'] ?></td>
                                                                <td><?= afficherBadgeStatus($colis_livre['status']) ?></td>

                                                            </tr>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div> <!-- tab-content -->

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
    <!-- ✅ Script JS pour gérer la sélection de tous les checkboxes -->
    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.checkbox-car').forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        });
    </script>
  

</body>

</html>