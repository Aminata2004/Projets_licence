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
        <main class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-colis</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Enregistrement des colis</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/Colis_prise_en_charges" class="btn btn-primary split-bg-primary text-white"> Liste des colis</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row">

                <div class="col-xxl-12">
                    <?php $this->view("set_flash") ?>
                    <div class="col-xl-12 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills mb-3" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="pill" href="#info-pills-home" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-time-five font-18 me-1'></i></div> <!-- en attente = horloge -->
                                                <div class="tab-title">Colis en attente</div>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="pill" href="#info-pills-profile" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-time-five font-18 me-1'></i></div> <!-- reçu = boîte de réception -->
                                                <div class="tab-title">Colis reçu</div>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="pill" href="#info-pills-contact" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-check-shield font-18 me-1'></i></div> <!-- livré = check sécurisé -->
                                                <div class="tab-title">Colis livré</div>
                                            </div>
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="info-pills-home" role="tabpanel">
                                        <form action="" method="post">
                                            <table id="example" class="table table-striped table-bordered table-hover-effect" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="selectAll"></th>
                                                        <th>Nom colis</th>
                                                        <th>Nature</th>
                                                        <th>Valeur</th>
                                                        <th>Frais de transaction</th>
                                                        <th>Destination</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php $this->view('helpers') ?>
                                                    <?php foreach ($liste_colis as $c): ?>
                                                        <?php if ($c['destination'] === $_SESSION['ville']): ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox"
                                                                        name="selected_colis[]"
                                                                        value="<?= (int)$c['id_colis'] ?>"
                                                                        class="checkbox-car">
                                                                </td>
                                                                <td><?= $c['nom_colis'] ?></td>
                                                                <td><?= $c['nature']     ?></td>
                                                                <td><?= $c['valeur']   ?></td>
                                                                <td><?= $c['fraix_transaction'] ?></td>
                                                                <td><?= $c['destination'] ?></td>
                                                                <td><?= afficherBadgeStatus($c['status']) ?></td>
                                                                <td class=" ">
                                                                    <div class="dropup ">
                                                                        <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            &#8943; <!-- Trois points horizontaux -->
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end">
                                                                            <a class="dropdown-item" href="#">Modifier</a>
                                                                            <a class="dropdown-item" href="#">Désactiver</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>

                                            <button class="btn btn-primary mt-4" type="submit" name="reception">Réception</button>
                                        </form>

                                    </div>
                                    <div class="tab-pane fade" id="info-pills-profile" role="tabpanel">
                                        <div class="d-flex align-items-center">

                                            <form class="ms-auto position-relative">
                                                <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-search"></i></div>
                                                <input class="form-control ps-5" type="text" placeholder="search">
                                            </form>
                                        </div>
                                        <div class=" mt-3">
                                            <table class="table align-middle">
                                                <thead class="table-secondary">
                                                    <tr>
                                                        <th>Nom colis</th>
                                                        <th>Nature</th>
                                                        <th>Valeur</th>
                                                        <th>Frais de transaction</th>
                                                        <th>Destination</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($liste_colis_recue as $colis): ?>
                                                        <?php if ($colis['destination'] === $_SESSION['ville']): ?>
                                                            <tr>

                                                                <td><?= $colis['nom_colis'] ?></td>
                                                                <td><?= $colis['nature']     ?></td>
                                                                <td><?= $colis['valeur']   ?></td>
                                                                <td><?= $colis['fraix_transaction'] ?></td>
                                                                <td><?= $colis['destination'] ?></td>
                                                                <td><?= afficherBadgeStatus($colis['status']) ?></td>
                                                                <td class=" ">
                                                                    <div class="dropup ">
                                                                        <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            &#8943; <!-- Trois points horizontaux -->
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end">
                                                                            <a class="dropdown-item" href="#">Livraison</a>
                                                                            <a class="dropdown-item" href="#">Details</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="info-pills-contact" role="tabpanel">
                                        <div class="d-flex align-items-center">

                                            <form class="ms-auto position-relative">
                                                <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-search"></i></div>
                                                <input class="form-control ps-5" type="text" placeholder="search">
                                            </form>
                                        </div>
                                        <div class=" mt-3">
                                            <table class="table align-middle">
                                                <thead class="table-secondary">
                                                    <tr>
                                                        <th>Nom colis</th>
                                                        <th>Nature</th>
                                                        <th>Valeur</th>
                                                        <th>Frais de transaction</th>
                                                        <th>Destination</th>
                                                        <th>Date de livraison</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                   <?php foreach ($liste_colis_livre as $colis_livre): ?>
                                                        <?php if ($colis_livre['destination'] === $_SESSION['ville']): ?>
                                                            <tr>

                                                                <td><?= $colis_livre['nom_colis'] ?></td>
                                                                <td><?= $colis_livre['nature']     ?></td>
                                                                <td><?= $colis_livre['valeur']   ?></td>
                                                                <td><?= $colis_livre['fraix_transaction'] ?></td>
                                                                <td><?= $colis_livre['destination'] ?></td>
                                                                 <td><?= $colis_livre['date_livraison'] ?></td>
                                                                <td><?= afficherBadgeStatus($colis_livre['status']) ?></td>
                                                                <td class=" ">
                                                                    <div class="dropup ">
                                                                        <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            &#8943; <!-- Trois points horizontaux -->
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end">
                                                                            <a class="dropdown-item" href="#">Livraison</a>
                                                                            <a class="dropdown-item" href="#">Details</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
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
    <?php $this->view('partials/foot') ?>
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
    <script>
        // Enregistre l'onglet actif quand on clique
        document.querySelectorAll('a[data-bs-toggle="pill"]').forEach(function(tabLink) {
            tabLink.addEventListener('shown.bs.tab', function(e) {
                localStorage.setItem('activeTab', e.target.getAttribute('href'));
            });
        });

        // Réactive l'onglet actif sauvegardé
        window.addEventListener('DOMContentLoaded', function() {
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                var tab = document.querySelector('a[href="' + activeTab + '"]');
                if (tab) {
                    new bootstrap.Tab(tab).show();
                }
            }
        });
    </script>

</body>

</html>