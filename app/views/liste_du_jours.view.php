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
                <div class="breadcrumb-title pe-3">G-reservation</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Liste des tickets du jours</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/Add_billets" class="btn btn-primary split-bg-primary text-white"> + Ajouter</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body border-top border-primary border-1">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/Liste_tickets/liste_actuelle" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-list-check font-20"></i> <!-- Liste avec coches -->
                                    </div>
                                    <div class="tab-title">Liste actuelle</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" href="<?= BASE_URL ?>/Liste_du_jours" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-calendar-event font-19"></i> <!-- Icône calendrier -->
                                    </div>
                                    <div class="tab-title">Liste du jour</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/Liste_de_demains" role="tab" aria-selected="false">
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
            <div class="card border-top border-primary border-1">
                <div class="bg-light border-bottom rounded-top px-3 py-2 d-flex align-items-center mb-0 mt-1" style="gap:8px;">
                    <i class="bx bx-filter-alt text-primary" style="font-size:1.3rem;"></i>
                    <h6 class="mb-0 fw-bold text-primary" style="letter-spacing:1px;">Filtrage</h6>
                </div>

                <div class="card-body p-4 border-1">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="destination" class="form-label">Destination</label>
                            <select class="form-select" id="id_destination" name="destination">
                                <option value="">Toutes les destinations</option>
                                <?php if (!empty($destinations) && is_array($destinations)): ?>
                                    <?php foreach ($destinations as $destination): ?>
                                        <?php if (is_array($destination) && isset($destination['idDestination'])): ?>
                                            <option value="<?= htmlspecialchars(trim($destination['idDestination'])) ?>">
                                                <?= htmlspecialchars(trim($destination['idDestination'])) ?>
                                            </option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="heure" class="form-label">Heure de départ</label>
                            <select class="form-select" id="selectheure" name="heure">
                                <option value="">Toutes les heures</option>
                                <?php foreach ($liste_horaires as $liste_horaire): ?>

                                    <option value="<?= htmlspecialchars($liste_horaire->heuredepart) ?>"><?= $liste_horaire->heuredepart ?></option>
                                <?php endforeach ?>

                            </select>
                        </div>
                    </div>


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
                            <tbody id="tableClient">
                                <?php foreach ($liste_du_jour as $item): ?>
                                    <?php if ($item->jourVoyage == date("Y-m-d")): ?>
                                        <tr class="text-center">
                                            <td><?= $item->Client ?></td>
                                            <td><?= $item->destinationId ?></td>
                                            <td><?= $item->nombrePassages ?></td>
                                            <td><?= $item->Heur_departs ?></td>
                                            <td><?= $item->jourVoyage ?></td>
                                            <td><?= $item->date_expiration ?></td>
                                             <td class=" ">
                                            <div class="dropup ">
                                                <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                    &#8943; <!-- Trois points horizontaux -->
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                     <a class="dropdown-item" href="#">Details</a>
                                                    <a class="dropdown-item" href="#">Modifier</a>
                                                    <a class="dropdown-item" href="<?= BASE_URL ?>/Colis_prise_en_charges/imprimer_recu/<?= $colis['id_colis'] ?>" target="_blank">
                                                        Imprimer le reçu
                                                    </a>

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
    <!-- JQuery et SweetAlert2 déjà inclus -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script>
        $(document).ready(function() {
            $('#selectheure, #id_destination').change(function() {
                const selectheure = $('#selectheure').val();
                const id_destination = $('#id_destination').val();

                if (selectheure && id_destination) {
                    $.ajax({
                        url: '<?= BASE_URL ?>/AjaxFiltreListeClient', // Contrôleur AJAX MVC
                        type: 'POST',
                        data: {
                            selectheure: selectheure,
                            id_destination: id_destination
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.error) {
                                Swal.fire('Erreur', response.error, 'error');
                            } else {
                                $('#tableClient').html(response.tbody);
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Erreur AJAX', xhr.responseText, 'error');
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>