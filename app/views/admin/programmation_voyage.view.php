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
                            <li class="breadcrumb-item active" aria-current="page">Nouvelle programmation de voyage</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-sm-auto mt-2 mt-sm-0">
                    <a href="javascript:history.back()" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                        <i class="bx bx-left-arrow-alt"></i> Retour
                    </a>
                </div>
            </div>
            <!--end breadcrumb-->

            <?php $this->view("admin/set_flash") ?>

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bx bx-bus me-1"></i> Programmation des voyages
                </div>
                <div class="card-body">
                    <form action="" method="post">

                        <!-- Date du voyage -->
                        <div class="row mb-4 align-items-center">
                            <label for="jourVoyage" class="col-sm-2 col-form-label fw-semibold">Jour du voyage</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control shadow-sm" id="jourVoyage" name="jourVoyage" required>
                            </div>
                            <?php if (!empty($programmation_veille)): ?>
                                <div class="col-sm-6">
                                    <button type="button" id="btnReproduireHier" class="btn btn-outline-primary shadow-sm">
                                        <i class="bx bx-repeat me-1"></i> Reproduire la programmation du <?= date('d/m/Y', strtotime($derniere_date)) ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tableau -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle shadow-sm rounded">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Numéro Car</th>
                                        <th>Horaire</th>
                                        <?php if ($_SESSION['droit'] === 'Admin'): ?>
                                            <th>Départ</th>
                                        <?php endif; ?>
                                        <th>Destination</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php if (!empty($cars_destinations)) : ?>
                                        <?php $indexRow = 0; ?>
                                        <?php foreach ($cars_destinations as $numero_car => $destinations ) : ?>
                                            <tr data-id-car="<?= htmlspecialchars($destinations[0]->id_car) ?>">
                                                <td><input type="checkbox" name="select_car[]" value="<?= $indexRow ?>" class="form-check-input checkbox-car"></td>
                                                <td>
                                                    <input type="text" name="numero_car[]" class="form-control text-center shadow-sm" value="<?= htmlspecialchars($numero_car) ?>" readonly>
                                                    <input type="hidden" name="id_care[]" value="<?= htmlspecialchars($destinations[0]->id_car) ?>">
                                                </td>
                                                <td>
                                                    <select class="form-select shadow-sm" name="id_horaire[]" >
                                                        <option value="" disabled selected>Choisir un horaire</option>
                                                        <?php foreach ($listehoraire as $horaire): ?>
                                                            <option value="<?= $horaire->heuredepart ?>">
                                                                <?= date('H:i', strtotime($horaire->heuredepart)) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <?php if ($_SESSION['droit'] === 'Admin'): ?>
                                                    <td>
                                                        <input type="text" name="id_depart[]" class="form-control text-center shadow-sm champ-depart" readonly placeholder="—">
                                                    </td>
                                                <?php endif; ?>
                                                <td>
                                                    <select class="form-select shadow-sm" name="id_destination[]">
                                                        <option selected disabled value="">Choisir une destination</option>
                                                        <?php foreach ($destinations as $d): ?>
                                                            <?php
                                                            // Cas 1 : Admin => il voit tout
                                                            if ($_SESSION['droit'] === 'Admin') {
                                                                $afficher = true;
                                                            }
                                                            // Cas 2 : chef_d_escale => il voit uniquement les départs qui correspondent à sa ville
                                                            elseif ($_SESSION['droit'] === 'chef_d_escale' && $d->departLocalite === $_SESSION['ville']) {
                                                                $afficher = true;
                                                            }
                                                            // Cas 3 : autres => rien
                                                            else {
                                                                $afficher = false;
                                                            }
                                                            ?>

                                                            <?php if ($afficher): ?>
                                                                <option value="<?= htmlspecialchars($d->destinationLocalite) ?>" data-depart="<?= htmlspecialchars($d->departLocalite) ?>">
                                                                    <?= htmlspecialchars($d->departLocalite . ' -> ' . $d->destinationLocalite) ?>
                                                                </option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php $indexRow++; ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="4" class="text-muted">🚫 Aucun car disponible</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Boutons -->
                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-success shadow-sm" type="submit" name="programmer">
                                <i class="bx bx-save me-1"></i> Enregistrer
                            </button>
                            <a href="<?= BASE_URL ?>/admin/Programmation_voyages/liste_programmer_voyage"
                                class="btn btn-info text-white shadow-sm">
                                <i class="bx bx-list-ul me-1"></i> Voir la liste
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Véhicules en approche -->
            <?php if (!empty($cars_en_transit)): ?>
            <div class="card shadow-lg border-0 rounded-3 mt-4">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="bx bx-check-shield me-1"></i> Véhicules en approche (Validation d'arrivée)
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle shadow-sm rounded">
                            <thead class="table-success text-center">
                                <tr>
                                    <th>Numéro Car</th>
                                    <th>Places Totales</th>
                                    <th>Destination prévue</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php foreach ($cars_en_transit as $car): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($car->numero_car) ?></td>
                                        <td><?= htmlspecialchars($car->nbr_place) ?></td>
                                        <td>
                                            <?php 
                                                // Extract the destination string from En_transit_X
                                                $dest = substr($car->status_car, 11);
                                                echo htmlspecialchars($dest);
                                            ?>
                                        </td>
                                        <td>
                                            <form action="" method="post" class="d-inline form-valider-arrivee"
                                                data-depart-datetime="<?= htmlspecialchars($car->depart_datetime ?? '') ?>"
                                                data-id-programmation="<?= htmlspecialchars($car->id_programmation ?? '') ?>">
                                                <input type="hidden" name="id_car_arrivee" value="<?= $car->id_car ?>">
                                                <input type="hidden" name="force_arrivee" value="0" class="champ-force-arrivee">
                                                <button type="submit" name="valider_arrivee" class="btn btn-sm btn-success shadow-sm rounded-pill px-3">
                                                    <i class="bx bx-check-double me-1"></i> Valider l'arrivée
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

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
    <!-- ✅ Script JS pour gérer la sélection de tous les checkboxes -->
    <script>
        const dateInput = document.getElementById('jourVoyage');

        (function initDateLimits() {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);

            const toISO = d => d.toISOString().slice(0, 10); // YYYY-MM-DD
            dateInput.min = toISO(today);
            dateInput.max = toISO(tomorrow);

            // Fixer la valeur par défaut à aujourd'hui
            dateInput.value = dateInput.min;
        })();


        document.getElementById('selectAll').addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.checkbox-car').forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        });

        // Admin uniquement : remplit automatiquement le champ "Départ" selon la
        // destination choisie (chaque option porte la localité de départ réelle du trajet).
        document.querySelectorAll('select[name="id_destination[]"]').forEach(function(select) {
            select.addEventListener('change', function() {
                const depart = this.options[this.selectedIndex]?.getAttribute('data-depart') || '';
                const champDepart = this.closest('tr').querySelector('.champ-depart');
                if (champDepart) {
                    champDepart.value = depart;
                }
            });
        });

        // Reproduire la programmation de la veille : pré-remplit chaque ligne dont le car
        // était déjà programmé hier. Les cars indisponibles aujourd'hui (pas dans le tableau)
        // ou dont l'horaire/destination n'existe plus sont simplement ignorés — l'agent les
        // programme alors manuellement comme d'habitude.
        <?php if (!empty($programmation_veille)): ?>
            const programmationVeille = <?= json_encode($programmation_veille, JSON_HEX_TAG | JSON_HEX_APOS) ?>;

            document.getElementById('btnReproduireHier').addEventListener('click', function() {
                let appliques = 0;
                let ignores = 0;

                document.querySelectorAll('tbody tr[data-id-car]').forEach(function(tr) {
                    const idCar = tr.getAttribute('data-id-car');
                    const prev = programmationVeille[idCar];
                    if (!prev) return;

                    const selectHoraire = tr.querySelector('select[name="id_horaire[]"]');
                    const selectDestination = tr.querySelector('select[name="id_destination[]"]');
                    const checkbox = tr.querySelector('.checkbox-car');

                    const horaireExiste = selectHoraire && [...selectHoraire.options].some(o => o.value === prev.id_horaire);
                    const destinationExiste = selectDestination && [...selectDestination.options].some(o => o.value === prev.id_trajet);

                    if (!horaireExiste || !destinationExiste) {
                        ignores++;
                        return;
                    }

                    selectHoraire.value = prev.id_horaire;
                    selectDestination.value = prev.id_trajet;
                    selectDestination.dispatchEvent(new Event('change')); // met à jour le champ "Départ" (Admin)
                    if (checkbox) checkbox.checked = true;
                    appliques++;
                });

                let message = appliques + ' car(s) pré-rempli(s) depuis la programmation du <?= date('d/m/Y', strtotime($derniere_date)) ?>.';
                if (ignores > 0) {
                    message += ' ' + ignores + ' car(s) non repris (horaire/destination indisponible aujourd\'hui) — à programmer manuellement.';
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Reproduction terminée', message, 'info');
                } else {
                    alert(message);
                }
            });
        <?php endif; ?>

        // Règle des 3h avant de pouvoir valider l'arrivée d'un car : si le départ prévu
        // date de moins de 3h, on avertit le chef avant de valider (au lieu de bloquer sec),
        // avec la possibilité de reprogrammer ce car si ce n'est pas une vraie arrivée.
        document.querySelectorAll('.form-valider-arrivee').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const departDatetime = form.getAttribute('data-depart-datetime');
                const idProgrammation = form.getAttribute('data-id-programmation');
                if (!departDatetime) return; // pas de programmation retrouvée : on laisse passer

                const departTime = new Date(departDatetime.replace(' ', 'T')).getTime();
                const delaiAtteint = !isNaN(departTime) && (Date.now() - departTime) >= 3 * 60 * 60 * 1000;

                if (delaiAtteint) return; // 3h écoulées : soumission normale

                e.preventDefault();
                Swal.fire({
                    title: 'Arrivée déjà réelle ?',
                    text: "Ce car est parti il y a moins de 3h : es-tu sûr que ce soit une réelle arrivée ?",
                    icon: 'warning',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Oui, valider quand même',
                    denyButtonText: 'Reprogrammer ce car',
                    cancelButtonText: 'Annuler'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.querySelector('.champ-force-arrivee').value = '1';
                        form.submit();
                    } else if (result.isDenied && idProgrammation) {
                        window.location.href = '<?= BASE_URL ?>/admin/Programmation_voyages/edit/' + idProgrammation;
                    }
                });
            });
        });
    </script>
</body>

</html>