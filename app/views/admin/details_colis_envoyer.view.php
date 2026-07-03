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
          
            <!-- Breadcrumb -->
            <div class="page-breadcrumb d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3">
                <div class="breadcrumb-title pe-3 text-primary"><i class="bx bx-package me-1"></i> G-colis</div>
                <div class="ps-3 flex-grow-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active " aria-current="page">
                                Colis envoyés pour le car N° <?= htmlspecialchars($id_car) ?>
                                le <?= date('d/m/Y à H:i', strtotime($date_envoi)) ?>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto mt-2 mt-sm-0">
                    <a href="javascript:history.back()" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                        <i class="bx bx-left-arrow-alt me-1"></i>Retour
                    </a>
                </div>
            </div>
            <!-- /Breadcrumb -->

            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-hover table-bordered align-middle shadow-sm">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Nom du colis</th>
                                    <th>Nature</th>
                                    <th>Valeur</th>
                                    <th>Frais de transaction</th>
                                    <th>Date d'envoi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php foreach ($liste_colis as $colis): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($colis->nom_colis) ?></td>
                                        <td><?= htmlspecialchars($colis->nature) ?></td>
                                        <td><?= htmlspecialchars($colis->valeur) ?></td>
                                        <td><?= htmlspecialchars($colis->fraix_transaction) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($colis->date_enregistre)) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary changer-car-btn"
                                                data-bs-toggle="modal" data-bs-target="#modalChangerCar"
                                                data-id-colis="<?= htmlspecialchars($colis->id_colis) ?>"
                                                data-nom-colis="<?= htmlspecialchars($colis->nom_colis) ?>">
                                                <i class="bx bx-transfer-alt me-1"></i> Changer de car
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($liste_colis)): ?>
                                    <tr>
                                        <td colspan="6" class="text-muted py-4">Aucun colis dans cet envoi.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal changer de car -->
            <div class="modal fade" id="modalChangerCar" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Changer le car d'envoi</h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= BASE_URL ?>/admin/Envoi_colis/changer_car" method="post">
                            <div class="modal-body">
                                <p>Colis : <strong id="modalNomColis"></strong></p>
                                <input type="hidden" name="id_colis" id="modalIdColis">
                                <input type="hidden" name="ancien_id_car" value="<?= htmlspecialchars($id_car) ?>">
                                <input type="hidden" name="ancienne_date" value="<?= htmlspecialchars($date_envoi) ?>">

                                <?php
                                $autresCars = array_filter($liste_cars, fn($car) => $car['id_car_programmer'] != $id_car);
                                ?>

                                <?php if (empty($autresCars)): ?>
                                    <div class="alert alert-warning mb-0">
                                        <i class="bx bx-error me-1"></i>
                                        Aucun autre car programmé aujourd'hui. Activez et programmez un autre car
                                        (menus <em>Cars &amp; chauffeurs</em> et <em>Programmation des voyages</em>)
                                        pour pouvoir réaffecter ce colis.
                                    </div>
                                <?php else: ?>
                                    <label class="form-label fw-semibold">Nouveau car</label>
                                    <select class="form-select" name="nouveau_id_car" required>
                                        <option value="" disabled selected>Choisir un car</option>
                                        <?php foreach ($autresCars as $car): ?>
                                            <option value="<?= htmlspecialchars($car['id_car_programmer']) ?>">
                                                Car N°<?= htmlspecialchars($car['id_car_programmer']) ?> —
                                                Départ: <?= htmlspecialchars($car['id_horaire']) ?> —
                                                Destination: <?= htmlspecialchars($car['id_trajet']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary" <?= empty($autresCars) ? 'disabled' : '' ?>>Confirmer</button>
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
    <script>
        document.querySelectorAll('.changer-car-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('modalIdColis').value = this.getAttribute('data-id-colis');
                document.getElementById('modalNomColis').textContent = this.getAttribute('data-nom-colis');
            });
        });
    </script>

</body>

</html>