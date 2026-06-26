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
            <div class="page-breadcrumb d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3">
                <div class="breadcrumb-title pe-3">
                    <i class="bx bx-calendar-event me-1"></i> G-programmer
                </div>
                <div class="ps-0 ps-sm-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Programmation de voyage</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-sm-auto mt-2 mt-sm-0">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/admin/Programmation_voyages"
                            class="btn btn-sm btn-info text-white rounded-pill shadow-sm">
                            <i class="bx bx-plus me-1"></i> Ajouter
                        </a>
                        <a href="javascript:history.back()"
                            class="btn btn-sm btn-outline-primary rounded-pill shadow-sm ms-2">
                            <i class="bx bx-left-arrow-alt"></i>
                        </a>
                    </div>
                </div>
            </div>


            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bx bx-bus me-1"></i> Liste des programmations du jour
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example"
                            class="table table-hover align-middle mb-0 table-striped table-bordered rounded-3">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Numéro de car</th>
                                    <th>Horaire</th>
                                    <th>Destination</th>
                                    <th>Place disponible</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                date_default_timezone_set('Africa/Bamako');
                                $dateActuelle = new DateTime();

                                foreach ($listeProgrammer as $listeProgrammers):
                                    if ($listeProgrammers->localite_user !== $_SESSION['ville']) {
                                        continue;
                                    }
                                    $dateEnregistrement = new DateTime($listeProgrammers->date_enregistre);
                                    $interval = $dateActuelle->getTimestamp() - $dateEnregistrement->getTimestamp();

                                    if ($dateEnregistrement->format('Y-m-d') !== $dateActuelle->format('Y-m-d') || $interval > 86400) {
                                        continue;
                                    }
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($listeProgrammers->numero_car) ?></td>

                                        <td><?= htmlspecialchars($listeProgrammers->id_horaire) ?></td>
                                        <td><?= htmlspecialchars($listeProgrammers->id_trajet) ?></td>
                                        <td class="fw-bold text-success">
                                            <?= htmlspecialchars($listeProgrammers->place_disponible) ?>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/Programmation_voyages/edit/<?= $listeProgrammers->id_programmation ?>"><i class="bx bx-edit me-2"></i>Modifier</a></li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i class="bx bx-x-circle me-2"></i>Désactiver</a></li>
                                                </ul>
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
    <?php $this->view('admin/partials/foot') ?>

</body>

</html>