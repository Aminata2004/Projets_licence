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
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-reservation</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Liste des tickets </li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/admin/Add_billets" class="btn btn-primary split-bg-primary text-white"> + Ajouter</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-body border-top border-primary border-1">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" href="<?= BASE_URL ?>/admin/Liste_tickets" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-list-check font-20"></i> <!-- Liste avec coches -->
                                    </div>
                                    <div class="tab-title">Liste actuelle</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/Liste_du_jours" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-calendar-event font-19"></i> <!-- Icône calendrier -->
                                    </div>
                                    <div class="tab-title">Liste du jour</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/Liste_de_demains" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-time-five font-19"></i> <!-- Icône horloge -->
                                    </div>
                                    <div class="tab-title">Liste de demain</div>
                                </div>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">


                    <div class="tab-content py-3 table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Client</th>
                                    <th>Destionation</th>
                                    <th>Nbr de passages</th>
                                    <th>heure de depart</th>
                                    <th>Jour de voyage</th>
                                    <th>Date d'expiration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Configurer le fuseau horaire à l'UTC (Mali)
                                date_default_timezone_set('UTC');

                                // Obtenir l'heure actuelle au Mali
                                $current_time = date("H:i");


                                // Définir les créneaux horaires et leurs plages correspondantes
                                $time_slots = [
                                    '00:00' => ['start' => '00:00', 'end' => '05:30', 'depart' => ['05:00:00']],
                                    '05:30' => ['start' => '05:30', 'end' => '07:00', 'depart' => ['06:00:00']],
                                    '07:00' => ['start' => '07:00', 'end' => '09:00', 'depart' => ['08:00:00']],
                                    '09:00' => ['start' => '09:00', 'end' => '11:00', 'depart' => ['10:00:00']],
                                    '11:00' => ['start' => '11:00', 'end' => '14:30', 'depart' => ['14:00:00']],
                                    '14:30' => ['start' => '14:30', 'end' => '15:30', 'depart' => ['16:00:00']],
                                    '15:30' => ['start' => '15:30', 'end' => '16:20', 'depart' => ['16:00:00']],
                                    '16:20' => ['start' => '16:20', 'end' => '23:59', 'depart' => ['16:30:00']]
                                ];

                                // Parcourir les billets pour vérifier si l'heure de départ correspond à l'heure actuelle et au créneau
                                foreach ($listeClients as $listeClient) {
                                    if ($listeClient->jourVoyage == date("Y-m-d")) { // Vérifie si c'est aujourd'hui
                                        foreach ($time_slots as $slot) {
                                            // Vérifier si l'heure actuelle se situe dans ce créneau
                                            if ($current_time >= $slot['start'] &&  $current_time <= $slot['end']) {
                                                // Vérifier si l'heure de départ du billet correspond aux heures de départ du créneau
                                                if (in_array($listeClient->Heur_departs, $slot['depart'])) {

                                                ?>
                                                    <tr class="text-center">
                                                        <td><?= $listeClient->Client ?></td>
                                                        <td><?= $listeClient->localite ?></td>
                                                        <td><?= $listeClient->nombrePassages ?></td>
                                                        <td><?= $listeClient->Heur_departs ?></td>
                                                        <td><?= $listeClient->jourVoyage ?></td>
                                                        <td><?= $listeClient->date_expiration ?></td>
                                                        <td>
                                                            <div class="dropdown ms-auto text-center">
                                                                <div class="btn-link" data-bs-toggle="dropdown">
                                                                    <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                                        version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none"
                                                                            fill-rule="evenodd">
                                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                                            <circle fill="#000000" cx="5" cy="12" r="2">
                                                                            </circle>
                                                                            <circle fill="#000000" cx="12" cy="12" r="2">
                                                                            </circle>
                                                                            <circle fill="#000000" cx="19" cy="12" r="2">
                                                                            </circle>
                                                                        </g>
                                                                    </svg>
                                                                </div>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item"
                                                                        href="Imprimer.php?id_client=<?= $listeClient->idClient ?>">Imprimer</a>
                                                                    <a class="dropdown-item"
                                                                        href="Detail_client.php?id_client=<?= $listeClient->idClient ?>">Details</a>
                                                                    <a class="dropdown-item"
                                                                        href="Reporter_voyage.php?id_client=<?= $listeClient->idClient ?>">Reporter</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>

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