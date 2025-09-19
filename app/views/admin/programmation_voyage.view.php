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
                        <div class="row mb-4">
                            <label for="jourVoyage" class="col-sm-2 col-form-label fw-semibold">Jour du voyage</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control shadow-sm" id="jourVoyage" name="jourVoyage" required>
                            </div>
                        </div>

                        <!-- Tableau -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle shadow-sm rounded">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Numéro Car</th>
                                        <th>Horaire</th>
                                        <th>Destination</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php if (!empty($cars_destinations)) : ?>
                                        <?php foreach ($cars_destinations as $numero_car => $destinations) : ?>
                                            <tr>
                                                <td><input type="checkbox" name="select_car[]" class="form-check-input checkbox-car"></td>
                                                <td>
                                                    <input type="text" name="id_care[]" class="form-control text-center shadow-sm" value="<?= htmlspecialchars($numero_car) ?>" readonly>
                                                </td>
                                                <td>
                                                    <select class="form-select shadow-sm" name="id_horaire[]" required>
                                                        <option value="" disabled selected>Choisir un horaire</option>
                                                        <?php foreach ($listehoraire as $horaire): ?>
                                                            <option value="<?= $horaire->heuredepart ?>">
                                                                <?= date('H:i', strtotime($horaire->heuredepart)) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select shadow-sm" name="id_destination[]">
                                                        <option selected disabled value="">Choisir une destination</option>
                                                        <?php foreach ($destinations as $d): ?>
                                                            <option
                                                                value="<?= htmlspecialchars($d->idDestination) ?>"
                                                                <?php
                                                                if (
                                                                    ($_SESSION['droit'] !== 'Admin' && $_SESSION['droit'] !== 'Admin_regionale')
                                                                    || $d->idDepart != $_SESSION['ville']
                                                                ) {
                                                                    echo 'disabled';
                                                                }
                                                                ?>>
                                                                <?= htmlspecialchars($d->idDepart . ' -> ' . $d->idDestination) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                            </tr>
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
    </script>
</body>

</html>