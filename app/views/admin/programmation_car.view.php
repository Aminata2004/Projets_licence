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
            <!--breadcrumb-->
            <div class="page-breadcrumb d-flex align-items-center justify-content-between mb-3 p-3  ">
                <!-- Titre + fil d’Ariane -->
                <div class="d-flex align-items-center">
                    <div class="breadcrumb-title pe-3 text-primary">
                        <i class="bx bx-calendar-event me-1"></i> G-Programme
                    </div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0 small">
                                <li class="breadcrumb-item">
                                    <a href="<?= BASE_URL ?>/admin/dashboard" class="text-muted text-decoration-none">
                                        <i class="bx bx-home-alt"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active  text-dark" aria-current="page">
                                    Programmation du car
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#exampleDangerModal">
                        <i class="bx bx-bus me-1"></i> Programmer un car
                    </button>
                    <a href="javascript:history.back()" class="btn btn-sm btn-outline-primary shadow-sm ms-2">
                        <i class="bx bx-left-arrow-alt"></i> Retour
                    </a>
                </div>
            </div>

            <!--end breadcrumb-->
            <div class="row">

                <div class="col-xxl-12">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="card custom-card">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                            <h5 class="mb-0 fw-bold">Liste des cars programmés</h5>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header mobile-card-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Numero de car</th>
                                            <th>Nbr de place</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($Select_car1 as $Select_cars) : ?>
                                            <tr>
                                                <td data-label="Numero de car"> Car : <?= $Select_cars->numero_car ?></td>
                                                <td data-label="Nbr de place"> <?= $Select_cars->nbr_place ?></td>
                                                <td class=" " data-label="Action">
                                                    <div class="dropup ">
                                                        <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                            &#8943; <!-- Trois points horizontaux -->
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item ajouter-trajet-button"
                                                                href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalAjouterTrajet"
                                                                data-id-car="<?= $Select_cars->id_car ?>"
                                                                data-numero-car="<?= htmlspecialchars($Select_cars->numero_car) ?>"
                                                                data-trajets-existants="<?= htmlspecialchars(json_encode($trajetsParCar[$Select_cars->id_car] ?? []), ENT_QUOTES) ?>">
                                                                Ajouter
                                                            </a>
                                                            <a class="dropdown-item text-danger supprimer-car-button"
                                                                href="<?= BASE_URL ?>/admin/Programmation_cars/supprimer/<?= $Select_cars->id_car ?>">
                                                                Supprimer
                                                            </a>
                                                            <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Programmation_cars/details/<?= $Select_cars->id_car ?>">Details</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <div class="modal fade" id="exampleDangerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Programmation du car</h5>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <div class="modal-body">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label mb-0">Car(s) <span class="text-danger">*</span></label>
                                            <?php if (!empty($listeCar)): ?>
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" id="toutCocherCars">
                                                    <label class="form-check-label small" for="toutCocherCars">Tout cocher</label>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <p class="small text-muted mb-2">Cochez un ou plusieurs cars (ou "Tout cocher" pour tous les attribuer d'un coup) : les mêmes trajets choisis ci-dessous leur seront affectés à tous, en une seule fois.</p>
                                        <div class="border rounded p-2" style="max-height: 180px; overflow-y: auto;">
                                            <?php if (empty($listeCar)): ?>
                                                <p class="text-muted small mb-0">Aucun car disponible à programmer.</p>
                                            <?php else: ?>
                                                <?php foreach ($listeCar as $listeCars): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input car-checkbox" type="checkbox" name="id_car[]"
                                                            value="<?= $listeCars->id_car ?>"
                                                            id="carProg<?= $listeCars->id_car ?>">
                                                        <label class="form-check-label" for="carProg<?= $listeCars->id_car ?>">
                                                            Car : <?= htmlspecialchars($listeCars->numero_car) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-12 mt-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label mb-0">Trajet a parcourire</label>
                                            <?php if (!empty($listeTrajet)): ?>
                                                <button type="button" id="toutSelectionnerTrajets" class="btn btn-sm btn-outline-primary">
                                                    Tout sélectionner
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <select class="form-control multiple-select" multiple="multiple" placeholder="Choisissez un ou plusieurs escale" name="idTrajet[]">
                                            <option value="" disabled>Choisissez un ou plusieurs trajet</option>
                                            <?php foreach ($listeTrajet as $listeTrajets): ?>
                                                <option value="<?= htmlspecialchars($listeTrajets->idProgrammer); ?>">
                                                    <?= htmlspecialchars($listeTrajets->depart . ' (' . $listeTrajets->gareDepart . ') → ' . $listeTrajets->destination . ' (' . $listeTrajets->gareDestination . ') — ' . substr($listeTrajets->heureDepart, 0, 5)); ?>
                                                </option>
                                            <?php endforeach; ?>

                                        </select>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                        Annuler
                                    </button>
                                    <button type="submit" class="btn btn-primary" name="programmer_car">Enregistre</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Ajouter un trajet à un car déjà programmé -->
            <div class="modal fade" id="modalAjouterTrajet" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">
                                Ajouter un trajet <span id="modalAjouterNumeroCar"></span>
                            </h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= BASE_URL ?>/admin/Programmation_cars/ajouter_trajet" method="post">
                            <div class="modal-body">
                                <input type="hidden" name="id_car" id="modalAjouterIdCar">
                                <div class="mb-3 col-12">
                                    <label class="form-label">Trajet à parcourir</label>
                                    <select class="form-control multiple-select-ajouter" multiple="multiple" placeholder="Choisissez un ou plusieurs trajet" name="idTrajet[]">
                                        <?php foreach ($listeTrajet as $listeTrajets): ?>
                                            <option value="<?= htmlspecialchars($listeTrajets->idProgrammer); ?>">
                                                <?= htmlspecialchars($listeTrajets->depart . ' (' . $listeTrajets->gareDepart . ') → ' . $listeTrajets->destination . ' (' . $listeTrajets->gareDestination . ') — ' . substr($listeTrajets->heureDepart, 0, 5)); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                    Annuler
                                </button>
                                <button type="submit" class="btn btn-primary" name="ajouter_trajet">Enregistrer</button>
                            </div>
                        </form>
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
    <!-- Initialisation Select2 -->
    <script>
        $(document).ready(function() {
            $('.multiple-select').select2({
                dropdownParent: $('#exampleDangerModal')
            });
            $('.multiple-select-ajouter').select2({
                dropdownParent: $('#modalAjouterTrajet')
            });

            // Raccourcis "Tout cocher" / "Tout sélectionner" : pour attribuer tous les
            // cars disponibles à tous les trajets en une seule soumission, sans avoir à
            // cocher/sélectionner un par un.
            $('#toutCocherCars').on('change', function() {
                $('.car-checkbox').prop('checked', $(this).is(':checked'));
            });
            $('.car-checkbox').on('change', function() {
                var totalCars = $('.car-checkbox').length;
                var cochees = $('.car-checkbox:checked').length;
                $('#toutCocherCars').prop('checked', totalCars > 0 && cochees === totalCars);
            });

            $('#toutSelectionnerTrajets').on('click', function() {
                $('.multiple-select option').prop('selected', true);
                $('.multiple-select').trigger('change');
            });

            // Remplir le modal "Ajouter un trajet" avec le car cliqué
            $('.ajouter-trajet-button').click(function() {
                var idCar = $(this).data('id-car');
                var numeroCar = $(this).data('numero-car');
                var trajetsExistants = $(this).data('trajets-existants') || [];

                $('#modalAjouterIdCar').val(idCar);
                $('#modalAjouterNumeroCar').text('(Car : ' + numeroCar + ')');

                // Grise les trajets déjà assignés à ce car (au lieu de les laisser
                // sélectionnables puis rejetés en silence côté serveur) : empêche
                // directement le doublon plutôt que de le bloquer après coup.
                var $selectAjouter = $('.multiple-select-ajouter');
                $selectAjouter.val(null);
                $selectAjouter.find('option').each(function() {
                    var $option = $(this);
                    var dejaAssigne = trajetsExistants.includes(parseInt($option.val(), 10));
                    var texteBase = $option.data('texte-base');
                    if (texteBase === undefined) {
                        texteBase = $option.text();
                        $option.data('texte-base', texteBase);
                    }
                    $option.prop('disabled', dejaAssigne);
                    $option.text(dejaAssigne ? texteBase + ' (déjà assigné à ce car)' : texteBase);
                });
                $selectAjouter.trigger('change');
            });

            // Confirmation avant suppression de la programmation d'un car
            $('.supprimer-car-button').click(function(event) {
                event.preventDefault();
                const deleteUrl = $(this).attr('href');

                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "La programmation de ce car et ses trajets affectés seront supprimés. Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    </script>
</body>

</html>