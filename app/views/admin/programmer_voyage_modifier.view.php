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
                    <i class="bx bx-bus me-1"></i> Modification de la programmation des voyages
                </div>
                <div class="card-body">
                    <form action="" method="post">

                        <!-- Date du voyage -->
                        <div class="row mb-4">
                            <label for="jourVoyage" class="col-sm-2 col-form-label fw-semibold">Jour du voyage</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control shadow-sm" id="jourVoyage" name="jourVoyage" required>
                            </div>
                        </div>

                        <?php if (!empty($besoin_choix)): ?>
                            <div class="alert alert-warning shadow-sm">
                                <strong><?= (int)$besoin_choix['count'] ?> réservation(s)</strong> existent déjà sur le
                                créneau actuel de ce car
                                (<?= htmlspecialchars($programmation->localite_user . ' → ' . $programmation->id_trajet) ?>
                                à <?= htmlspecialchars(date('H:i', strtotime($programmation->id_horaire))) ?>).
                                Que voulez-vous faire de ces réservations ?
                                <div class="mt-2">
                                    <?php if (empty($besoin_choix['destination_change'])): ?>
                                        <label class="d-block">
                                            <input type="radio" name="action_reservations" value="suivre" required>
                                            Faire suivre ces billets vers le nouveau créneau (mêmes clients, même car, nouvelle heure)
                                        </label>
                                    <?php endif; ?>
                                    <label class="d-block">
                                        <input type="radio" name="action_reservations" value="nouveau_car"
                                            <?= !empty($besoin_choix['destination_change']) ? 'checked' : '' ?> required>
                                        Garder ces clients sur le créneau actuel : un autre car le reprend
                                    </label>
                                </div>
                                <div class="mt-2" id="carRemplacementBox" style="display:none;">
                                    <label class="form-label mb-1">Car de remplacement pour l'ancien créneau</label>
                                    <select class="form-select" name="id_car_remplacement" style="max-width:280px;">
                                        <option value="">Choisir un car</option>
                                        <?php foreach (($cars_remplacement ?? []) as $c): ?>
                                            <option value="<?= $c['id_car'] ?>"><?= htmlspecialchars($c['numero_car']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Tableau -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle shadow-sm rounded">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>Numéro Car</th>
                                        <th>Horaire</th>
                                        <th>Destination</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php if (!empty($cars_destinations)) : ?>
                                        <?php foreach ($cars_destinations as $numero_car => $destinations) : ?>
                                            <tr>
                                                <!-- Numéro du car et id caché -->
                                                <td>
                                                    <input type="text" name="numero_car[]" class="form-control text-center shadow-sm" value="<?= htmlspecialchars($numero_car) ?>" readonly>
                                                    <input type="hidden" name="id_care[]" value="<?= htmlspecialchars($programmation->id_car_programmer) ?>">
                                                </td>

                                                <!-- Horaire : rempli en JS depuis le trajet choisi (jamais une saisie
                                                     libre) : évite de programmer un car à une heure qui n'existe pas
                                                     pour ce trajet. -->
                                                <td>
                                                    <select class="form-select shadow-sm" name="id_horaire[]" id="selectHoraireEdit" required>
                                                        <option value="" disabled selected>—</option>
                                                    </select>
                                                </td>

                                                <!-- Sélect Destination pré-sélectionné -->
                                                <td>
                                                    <select class="form-select shadow-sm" name="id_destination[]" id="selectDestinationEdit" required>
                                                        <option value="" disabled>Choisir une destination</option>
                                                        <?php
                                                            $destinationActuelle = $destination_soumise ?? $programmation->id_trajet;
                                                            $horaireActuel = $horaire_soumis ?? $programmation->id_horaire;
                                                        ?>
                                                        <?php foreach ($destinations as $d): ?>
                                                            <?php
                                                            // Vérification des droits pour afficher
                                                            if (
                                                                $_SESSION['droit'] === 'Admin' ||
                                                                ($_SESSION['droit'] === 'chef_d_escale' && $d->departLocalite === $_SESSION['ville'])
                                                            ) {
                                                                $afficher = true;
                                                            } else {
                                                                $afficher = false;
                                                            }
                                                            ?>
                                                            <?php if ($afficher): ?>
                                                                <option value="<?= htmlspecialchars($d->destinationLocalite) ?>"
                                                                    data-heure="<?= htmlspecialchars($d->heureDepart) ?>"
                                                                    <?= ($d->destinationLocalite == $destinationActuelle && $d->heureDepart == $horaireActuel) ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($d->departLocalite . ' -> ' . $d->destinationLocalite) ?>
                                                                    (<?= htmlspecialchars(date('H:i', strtotime($d->heureDepart))) ?>)
                                                                </option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="3" class="text-muted">🚫 Aucun car disponible</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                        </div>

                        <!-- Boutons -->
                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-success shadow-sm" type="submit" name="modifier">
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
        // Horaire dérivé du trajet choisi : jamais une saisie libre (évite de programmer
        // un car à une heure qui n'existe pas pour ce trajet précis).
        (function() {
            const selectDestination = document.getElementById('selectDestinationEdit');
            const selectHoraire = document.getElementById('selectHoraireEdit');
            if (!selectDestination || !selectHoraire) return;

            function majHoraire() {
                const selectedOption = selectDestination.options[selectDestination.selectedIndex];
                const heure = selectedOption?.getAttribute('data-heure') || '';
                selectHoraire.innerHTML = heure
                    ? `<option value="${heure}" selected>${heure.slice(0, 5)}</option>`
                    : '<option value="" disabled selected>—</option>';
            }

            selectDestination.addEventListener('change', majHoraire);
            majHoraire(); // applique tout de suite la sélection déjà pré-remplie côté serveur
        })();

        // Affiche le select "car de remplacement" seulement si cette option est choisie.
        (function() {
            const radios = document.querySelectorAll('input[name="action_reservations"]');
            const box = document.getElementById('carRemplacementBox');
            if (!radios.length || !box) return;

            function majAffichage() {
                const choisi = document.querySelector('input[name="action_reservations"]:checked');
                box.style.display = (choisi && choisi.value === 'nouveau_car') ? 'block' : 'none';
            }

            radios.forEach(r => r.addEventListener('change', majAffichage));
            majAffichage();
        })();
    </script>

</body>

</html>