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
                <div class="breadcrumb-title pe-3">G-programmer</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Programmer journalier </li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/Programmation_voyages" class="btn btn-info text-white "> + Ajouter</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Numero de car</th>
                                    <th>Horaire</th>
                                    <th>Destination</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $dateActuelle = new DateTime(); // Date et heure actuelles

                                foreach ($listeProgrammer as $listeProgrammers):
                                    // Vérifie si la localité correspond à la ville en session
                                    if ($listeProgrammers->localite_user !== $_SESSION['ville']) {
                                        continue;
                                    }

                                    // Vérifie que la date d'enregistrement est aujourd'hui et < 24h
                                    $dateEnregistrement = new DateTime($listeProgrammers->date_enregistre);
                                    $interval = $dateActuelle->getTimestamp() - $dateEnregistrement->getTimestamp();

                                    // Si ce n'est pas aujourd'hui ou si plus de 24h sont passées, on saute
                                    if ($dateEnregistrement->format('Y-m-d') !== $dateActuelle->format('Y-m-d') || $interval > 86400) {
                                        continue;
                                    }
                                ?>

                                    <tr>
                                        <td><?= htmlspecialchars($listeProgrammers->id_car_programmer) ?></td>
                                        <td><?= htmlspecialchars($listeProgrammers->id_horaire) ?></td>
                                        <td><?= htmlspecialchars($listeProgrammers->id_trajet) ?></td>
                                        <td>
                                            <div class="dropup">
                                                <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                    &#8943;
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">Modifier</a>
                                                    <a class="dropdown-item" href="#">Désactiver</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

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
    <?php $this->view('partials/foot') ?>

</body>

</html>