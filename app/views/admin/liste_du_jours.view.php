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
                            <li class="breadcrumb-item active text-primary" aria-current="page">Liste des tickets du jours</li>
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
            <div class="card">
                <div class="card-body border-top border-primary border-1">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/Liste_tickets" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-list-check font-20"></i> <!-- Liste avec coches -->
                                    </div>
                                    <div class="tab-title">Liste actuelle</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" href="<?= BASE_URL ?>/admin/Liste_du_jours" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-calendar-event font-19"></i> <!-- Icône calendrier -->
                                    </div>
                                    <div class="tab-title">Liste du jour</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/Liste_de_demains" role="tab" aria-selected="false">
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

                            <select class="single-select" id="selectheure" name="heure">
                                <option value="United States">Toutes les heures</option>
                                <?php foreach ($liste_horaires as $liste_horaire): ?>

                                    <option value="<?= htmlspecialchars($liste_horaire->heuredepart) ?>"><?= $liste_horaire->heuredepart ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>


                </div>
            </div>
            <?php $this->view("admin/set_flash") ?>
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
                                
                                <?php 
                                
                                foreach ($liste_du_jour as $item): ?>
                                    <?php 
                                     date_default_timezone_set('Africa/Bamako');
                                    if ($item->jourVoyage == date("Y-m-d")): ?>
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
                                                        <a href="#" class="dropdown-item report-btn"
                                                            data-idclient="<?= $item->idBillets ?>"
                                                            data-jour_voyage="<?= date('Y-m-d', strtotime($item->jourVoyage)) ?>"
                                                            data-destinationid="<?= $item->destinationId ?>"
                                                            data-date_expiration="<?= date('Y-m-d', strtotime($item->date_expiration)) ?>"
                                                            data-heure_actuelle="<?= $item->Heur_departs ?>"
                                                            data-bs-toggle="modal" data-bs-target="#exampleDangerModal">
                                                            Reporter le voyage
                                                        </a>

                                                        <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Liste_du_jours/recu/<?= $item->idBillets ?>" target="_blank">
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
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="exampleDangerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Reporter un voyage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dart">
                    <form action="<?= BASE_URL ?>/Liste_du_jours/reporter" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="validationCustom01" class="form-label">Nouveau jour de voyage</label>
                                <input type="date" class="form-control" id="nouvelleDate" name="nouvelle_date" required>

                            </div>
                            <div class="col-md-12 mt-1">
                                <label for="validationCustom02" class="form-label">Nouveau heure de depart</label>
                                <select class="form-select" name="heure_depart" id="heureDepartSelect">

                                </select>

                            </div>

                            <input type="hidden" id="dateExpiration" name="date_expiration">
                            <input type="hidden" id="destination" name="destinationId">
                            <input type="hidden" name="idClient" value="">


                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" name="edit">Modifier</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <?php $this->view('admin/partials/foot') ?>
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
                        url: '<?= BASE_URL ?>/admin/AjaxFiltreListeClient', // Contrôleur AJAX MVC
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
        $(document).ready(function() {
            $('.report-btn').click(function(e) {
                e.preventDefault();

                let idClient = $(this).data('idclient');
                let jourVoyage = $(this).data('jour_voyage');
                let destinationId = $(this).data('destinationid');
                let dateExpiration = $(this).data('date_expiration');
                let heureActuelle = $(this).data('heure_actuelle'); // Heure à présélectionner

                let today = new Date();
                let expirationDate = new Date(dateExpiration);
                let voyageDate = new Date(jourVoyage);

                today.setHours(0, 0, 0, 0);
                expirationDate.setHours(0, 0, 0, 0);
                voyageDate.setHours(0, 0, 0, 0);

                if (today <= expirationDate) {
                    // Limites de date
                    let minDate = voyageDate.toISOString().split('T')[0];
                    let maxDateObj = new Date(voyageDate);
                    maxDateObj.setDate(maxDateObj.getDate() + 7);
                    let maxDate = maxDateObj.toISOString().split('T')[0];

                    // Appliquer limites au champ de date
                    $('#nouvelleDate').attr('min', minDate);
                    $('#nouvelleDate').attr('max', maxDate);
                    $('#nouvelleDate').val(minDate);

                    // Champs cachés
                    $('#dateExpiration').val(dateExpiration);
                    $('#destination').val(destinationId);
                    $('input[name="idClient"]').val(idClient);

                    // Charger les heures disponibles
                    $.ajax({
                        url: '<?= BASE_URL ?>/Liste_du_jours/getHeuresDisponibles',
                        method: 'POST',
                        data: {
                            destination_id: destinationId
                        },
                        success: function(response) {
                            let heures = JSON.parse(response);
                            let heureSelect = $('#heureDepartSelect');
                            heureSelect.empty();
                            heureSelect.append('<option value="">Choisissez une heure de départ</option>');

                            if (heures.length === 0) {
                                heureSelect.append('<option disabled>Aucune heure disponible</option>');
                            } else {
                                let ancienneHeureDansListe = false;

                                heures.forEach(function(h) {
                                    let selected = '';
                                    if (h === heureActuelle) {
                                        selected = 'selected';
                                        ancienneHeureDansListe = true;
                                    }
                                    heureSelect.append('<option value="' + h + '" ' + selected + '>' + h + '</option>');
                                });

                                // Si l’ancienne heure n’est plus dans la base, on l’affiche quand même
                                if (!ancienneHeureDansListe && heureActuelle) {
                                    heureSelect.prepend('<option value="' + heureActuelle + '" selected disabled>' + heureActuelle + ' (ancienne)</option>');
                                }
                            }
                        },
                        error: function() {
                            alert("Erreur lors du chargement des heures.");
                        }
                    });

                    $('#exampleDangerModal').modal('show');
                } else {
                    alert("La période de modification de ce voyage est expirée !");
                }
            });
        });
    </script>

</body>

</html>