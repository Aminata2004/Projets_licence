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
                <div class="breadcrumb-title pe-3">G-programmer</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Programmer du voyages</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">

                        <a href="javascript:history.back()" class="btn btn-primary split-bg-primary"><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <?php $this->view("admin/set_flash") ?>
            <div class="card">

                <div class="card-body border-top border-primary border-1">
                    <div class="d-flex align-items-center">

                    </div>
                    <div class="table-responsive mt-3">
                        <form action="" method="post">
                            <table class="table table-hover table-hover-effect table-custom-header">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Numéro Car</th>
                                        <th>Horaire</th>
                                        <th>Destination</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <label for="">Jours du voyage</label>
                                    <div class="col-lg-2">
                                        <input type="date"
                                            class="form-control"
                                            id="jourVoyage"
                                            name="jourVoyage"
                                            required>
                                    </div>
                                    <?php if (!empty($cars_destinations)) : ?>
                                        <?php foreach ($cars_destinations as $numero_car => $destinations) : ?>
                                            <tr>
                                                <td><input type="checkbox" name="select_car[]" class="checkbox-car"></td>
                                                <td>
                                                    <input type="text" name="id_care[]" class="form-control" value="<?= htmlspecialchars($numero_car) ?>" readonly>
                                                </td>
                                                <td>


                                                    <select class="form-control" name="id_horaire[]" required>
                                                        <option value="" disabled selected>Sélectionner l'horaire</option>
                                                        <?php foreach ($listehoraire as $horaire): ?>
                                                            <option value="<?= $horaire->heuredepart ?>">
                                                                <?= date('H:i', strtotime($horaire->heuredepart)) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" name="id_destination[]">
                                                        <option selected disabled value="">Sélectionner la destination</option>
                                                        <?php foreach ($destinations as $d): ?>
                                                            <option
                                                                value="<?= htmlspecialchars($d->idDestination) ?>"
                                                                <?php
                                                                // Si l'utilisateur n'est pas Admin et que la ville ne correspond pas, on désactive
                                                                if ($_SESSION['droit'] !== 'Admin' && $d->idDepart != $_SESSION['ville']) {
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
                                            <td colspan="4">Aucun car disponible </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <div class="col-12 mt-4">
                                <button class="btn btn-primary" type="submit" name="programmer">Enregistre</button>
                                <a href="<?= BASE_URL ?>/admin/Programmation_voyages/liste_programmer_voyage" class="btn btn-info text-white">Voir la liste</a>

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