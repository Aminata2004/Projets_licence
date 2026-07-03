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

                                                <!-- Sélect Horaire pré-sélectionné -->
                                                <td>
                                                    <select class="form-select shadow-sm" name="id_horaire[]" required>
                                                        <option value="" disabled>Choisir un horaire</option>
                                                        <?php foreach ($listehoraire as $horaire): ?>
                                                            <option value="<?= htmlspecialchars($horaire->heuredepart) ?>"
                                                                <?= ($horaire->heuredepart == $programmation->id_horaire) ? 'selected' : '' ?>>
                                                                <?= date('H:i', strtotime($horaire->heuredepart)) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>

                                                <!-- Sélect Destination pré-sélectionné -->
                                                <td>
                                                    <select class="form-select shadow-sm" name="id_destination[]" required>
                                                        <option value="" disabled>Choisir une destination</option>
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
                                                                    <?= ($d->destinationLocalite == $programmation->id_trajet) ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($d->departLocalite . ' -> ' . $d->destinationLocalite) ?>
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
    <!-- ✅ Script JS pour gérer la sélection de tous les checkboxes -->

</body>

</html>