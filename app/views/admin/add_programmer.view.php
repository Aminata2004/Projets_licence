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
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-4 p-2 rounded">
                <div class="breadcrumb-title pe-3 text-primary">
                    <i class="bx bx-calendar-check me-1"></i> G-programmer
                </div>
                <div class="ps-3 flex-grow-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:;" class="text-decoration-none text-muted"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-dark" aria-current="page">Enregistrement du programme</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <a href="<?= BASE_URL ?>/admin/Programmer_voyages" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                        <i class="bx bx-list-ul me-1"></i> Voir la liste
                    </a>
                    <a href="javascript:history.back()" class="btn btn-sm btn-primary rounded-pill shadow-sm">
                        <i class="bx bx-left-arrow-alt"></i> Retour
                    </a>
                </div>
            </div>
            <!--end breadcrumb-->

            <?php $this->view("admin/set_flash") ?>

            <div class="row">
                <div class="col-xxl-12">
                    <div class="col-xl-12 mx-auto">
                        <div id="stepper1" class="bs-stepper">
                            <div class="card border-top border-primary border-3">
                                <div class="card-header">
                                    <div class="d-lg-flex flex-lg-row align-items-lg-center justify-content-lg-between" role="tablist">

                                        <!-- Étape 1 : Itinéraire -->
                                        <div class="step" data-target="#step-itineraire">
                                            <div class="step-trigger" role="tab" id="stepper1trigger1" aria-controls="step-itineraire">
                                                <div class="bs-stepper-circle bg-primary text-white">
                                                    <i class="bx bx-map-pin"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0 steper-title">Itinéraire</h5>
                                                    <p class="mb-0 steper-sub-title small text-muted">Départ, escales, destination</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bs-stepper-line"></div>

                                        <!-- Étape 2 : Horaire -->
                                        <div class="step" data-target="#step-horaire">
                                            <div class="step-trigger" role="tab" id="stepper1trigger2" aria-controls="step-horaire">
                                                <div class="bs-stepper-circle bg-primary text-white">
                                                    <i class="bx bx-time-five"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0 steper-title">Horaire</h5>
                                                    <p class="mb-0 steper-sub-title small text-muted">Heure de départ, RDV</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bs-stepper-line"></div>

                                        <!-- Étape 3 : Tarification -->
                                        <div class="step" data-target="#step-tarif">
                                            <div class="step-trigger" role="tab" id="stepper1trigger3" aria-controls="step-tarif">
                                                <div class="bs-stepper-circle bg-primary text-white">
                                                    <i class="bx bx-money"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0 steper-title">Tarification</h5>
                                                    <p class="mb-0 steper-sub-title small text-muted">Prix transport et escales</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="bs-stepper-content">
                                        <form action="" method="post">

                                            <!-- Étape 1 : Itinéraire -->
                                            <div id="step-itineraire" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger1">
                                                <div class="row g-3">
                                                    <div class="col-12 col-lg-4">
                                                        <label for="choixAgence" class="form-label">
                                                            <i class="bx bx-current-location text-primary me-1"></i>Départ<span class="text-danger ms-1">*</span>
                                                        </label>
                                                        <select id="choixAgence" name="idDepart" class="form-select shadow-sm" required>
                                                            <option value=""> Sélectionner le départ</option>
                                                            <?php foreach ($liste_agence as $liste_agences):?>
                                                                <option value="<?= htmlspecialchars($liste_agences->idAgence); ?>"
                                                                    data-localite="<?= htmlspecialchars($liste_agences->localite); ?>">
                                                                    <?= htmlspecialchars($liste_agences->localite . '  ( ' .    $liste_agences->numeroGare . ' )'); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-lg-4">
                                                        <label class="form-label"><i class="bx bx-map-alt text-primary me-1"></i>Escale(s)</label>
                                                        <select class="form-control multiple-select" multiple name="idEscale[]" data-placeholder="Choisissez une ou plusieurs escales">
                                                            <?php foreach ($listeEscale as $listeEscales): ?>
                                                                <option value="<?= htmlspecialchars($listeEscales->id_escale); ?>">
                                                                    <?= htmlspecialchars($listeEscales->escales); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-lg-4">
                                                        <label for="choixAgences" class="form-label">
                                                            <i class="bx bx-flag text-primary me-1"></i>Destination<span class="text-danger ms-1">*</span>
                                                        </label>
                                                        <select id="choixAgences" name="idDestination" class="form-select shadow-sm" required>
                                                            <option value=""> Sélectionner la destination</option>
                                                            <?php foreach ($liste_agence as $liste_agences):?>
                                                                <option value="<?= htmlspecialchars($liste_agences->idAgence); ?>"
                                                                    data-localite="<?= htmlspecialchars($liste_agences->localite); ?>">
                                                                    <?= htmlspecialchars($liste_agences->localite . '  ( ' .    $liste_agences->numeroGare . ' )'); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <small class="form-text text-muted">
                                                            <i class="bx bx-info-circle"></i> Les gares de la même localité que le départ sont masquées (voyage interne impossible).
                                                        </small>
                                                    </div>

                                                    <div class="col-12">
                                                        <button type="button" class="btn btn-primary px-4" onclick="stepper1.next()">
                                                            Suivant <i class="bx bx-right-arrow-alt ms-2"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Étape 2 : Horaire -->
                                            <div id="step-horaire" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">
                                                <div class="row g-3">
                                                    <div class="col-12 col-lg-6">
                                                        <label for="heureDepart" class="form-label">
                                                            <i class="bx bx-time text-primary me-1"></i>Heure de départ<span class="text-danger ms-1">*</span>
                                                        </label>
                                                        <select class="single-select" id="heureDepart" name="heureDepart" onchange="calculerRDV(); checkStepHoraire();">
                                                            <option value="United States">Toutes les heures</option>
                                                            <?php foreach ($listehoraire as $listehoraires): ?>
                                                                <option value="<?= $listehoraires->heuredepart ?>">
                                                                    <?= $listehoraires->heuredepart ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-lg-6">
                                                        <label for="rdv" class="form-label">
                                                            <i class="bx bx-alarm text-primary me-1"></i>RDV<span class="text-danger ms-1">*</span>
                                                        </label>
                                                        <input type="time" class="form-control shadow-sm" id="rdv" name="rdv" value="" required>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()">
                                                                <i class="bx bx-left-arrow-alt me-2"></i>Précédent
                                                            </button>
                                                            <button type="button" class="btn btn-primary px-4" onclick="stepper1.next()">
                                                                Suivant <i class="bx bx-right-arrow-alt ms-2"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Étape 3 : Tarification -->
                                            <div id="step-tarif" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger3">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label for="prix" class="form-label">
                                                            <i class="bx bx-money text-primary me-1"></i>Frais de transport<span class="text-danger ms-1">*</span>
                                                        </label>
                                                        <div class="input-group shadow-sm">
                                                            <input type="number" class="form-control" id="prix" name="prix" value="" required>
                                                            <span class="input-group-text">FCFA</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12" id="fraixEscaleField" style="display: none;">
                                                        <div id="fraixEscaleContainer"></div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()">
                                                                <i class="bx bx-left-arrow-alt me-2"></i>Précédent
                                                            </button>
                                                            <button type="submit" class="btn btn-success rounded-pill shadow-sm px-4" name="enregistre">
                                                                <i class="bx bx-check me-1"></i> Enregistrer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
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
    <?php $this->view('admin/partials/foot') ?>
    <script>
        function calculerRDV() {
            let heureDepart = document.getElementById("heureDepart").value;
            let rdvInput = document.getElementById("rdv");

            if (heureDepart) {
                // Convertir l'heure de départ en heures et minutes
                let [heures, minutes] = heureDepart.split(':').map(Number);

                // Soustraire 45 minutes
                minutes -= 45;
                if (minutes < 0) {
                    minutes += 60;
                    heures -= 1;
                }

                // Formatage pour affichage (ajout de zéro si nécessaire)
                let heureRDV = (heures < 10 ? "0" : "") + heures + ":" + (minutes < 10 ? "0" : "") + minutes;

                // Mettre la valeur calculée dans le champ RDV
                rdvInput.value = heureRDV;
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            const fraixEscaleField = $('#fraixEscaleField');
            const fraixEscaleContainer = $('#fraixEscaleContainer');

            $('select[name="idEscale[]"]').on('change', function() {
                const selectEscale = $(this);
                const selected = selectEscale.val(); // Récupère toutes les escales sélectionnées
                fraixEscaleContainer.empty(); // On vide le contenu précédent

                if (selected && selected.length > 0) {
                    fraixEscaleContainer.append('<label class="form-label fw-semibold"><i class="bx bx-map-alt me-1"></i>Frais des escales</label>');
                    selected.forEach((id) => {
                        const escaleName = selectEscale.find('option[value="' + id + '"]').text().trim();
                        const group = $('<div class="input-group mb-2"></div>');
                        group.append($('<span class="input-group-text"></span>').text(escaleName));
                        group.append($('<input type="number" class="form-control">').attr({
                            name: 'prix_escale[' + id + ']',
                            placeholder: 'Frais pour ' + escaleName
                        }));
                        group.append('<span class="input-group-text">FCFA</span>');
                        fraixEscaleContainer.append(group);
                    });
                    fraixEscaleField.show();
                } else {
                    fraixEscaleField.hide();
                }
            });
        });
    </script>

    <script>
        // Filtre la destination selon le départ choisi : on masque les gares de la
        // même localité que le départ pour éviter d'enregistrer un voyage interne.
        document.addEventListener('DOMContentLoaded', function() {
            const departSelect = document.getElementById('choixAgence');
            const destinationSelect = document.getElementById('choixAgences');

            if (!departSelect || !destinationSelect) return;

            const destinationOptionsOriginal = Array.from(destinationSelect.options).map(function(opt) {
                return {
                    value: opt.value,
                    text: opt.textContent,
                    localite: opt.dataset.localite || null
                };
            });

            departSelect.addEventListener('change', function() {
                const departOption = departSelect.options[departSelect.selectedIndex];
                const departLocalite = departOption ? departOption.dataset.localite : null;
                const currentDestination = destinationSelect.value;

                destinationSelect.innerHTML = '';
                destinationOptionsOriginal.forEach(function(opt) {
                    // On masque uniquement les gares de la même localité que le départ
                    if (opt.localite && departLocalite && opt.localite === departLocalite) {
                        return;
                    }
                    const optionEl = document.createElement('option');
                    optionEl.value = opt.value;
                    optionEl.textContent = opt.text;
                    if (opt.localite) optionEl.dataset.localite = opt.localite;
                    destinationSelect.appendChild(optionEl);
                });

                // On restaure la sélection précédente si elle est toujours valide
                if (Array.from(destinationSelect.options).some(o => o.value === currentDestination)) {
                    destinationSelect.value = currentDestination;
                }

                checkStepItineraire();
            });
        });
    </script>

    <script>
        // Avance automatiquement à l'étape suivante dès que les champs obligatoires
        // de l'étape courante sont remplis, pour aller plus vite dans la saisie.
        let step1Complete = false;
        let step2Complete = false;

        function checkStepItineraire() {
            const isComplete = document.getElementById('choixAgence').value !== '' &&
                document.getElementById('choixAgences').value !== '';
            if (isComplete && !step1Complete && typeof stepper1 !== 'undefined' && stepper1) {
                stepper1.next();
            }
            step1Complete = isComplete;
        }

        function checkStepHoraire() {
            const isComplete = document.getElementById('rdv').value !== '';
            if (isComplete && !step2Complete && typeof stepper1 !== 'undefined' && stepper1) {
                stepper1.next();
            }
            step2Complete = isComplete;
        }

        document.getElementById('choixAgence').addEventListener('change', checkStepItineraire);
        document.getElementById('choixAgences').addEventListener('change', checkStepItineraire);
        document.getElementById('rdv').addEventListener('input', checkStepHoraire);
    </script>


</body>

</html>
