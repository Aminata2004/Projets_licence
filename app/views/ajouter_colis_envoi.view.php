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
                <div class="breadcrumb-title pe-3">G-colis</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Ajouter des colis à envoyer</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/Envoi_colis/liste_colis_envoyer" class="btn btn-primary split-bg-primary text-white"> Voir la liste</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <?php $this->view("set_flash") ?>
            <div class="card">
                <form action="" method="post">
                    <div class="card-body border-top border-primary border-4">

                        <div class="col-4">
                            <select name="id_car_selectionner" class="form-control col-12">
                                <?php if (!empty($car_selectionne)): ?>
                                    <option value="<?= $car_selectionne['id_car_programmer'] ?>" selected>
                                        Car N°<?= $car_selectionne['id_car_programmer'] ?> —
                                        Trajet: <?= $car_selectionne['id_trajet'] ?> —
                                        Départ: <?= $car_selectionne['heuredepart'] ?>
                                    </option>

                                <?php else: ?>
                                    <option disabled selected>Choisir un car</option>
                                <?php endif; ?>

                            </select>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-hover-effect table-custom-header">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Nom colis</th>
                                        <th>Nature</th>
                                        <th>Valeur</th>
                                        <th>Fraix de transaction</th>
                                        <th>Destination</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($liste_colis as $colis) : ?>
                                        <?php if ($colis['status'] === 'enregistre') : ?>
                                            <tr>
                                                <td><input type="checkbox" name="selected_colis[]" class="checkbox-car" value="<?= htmlspecialchars($colis['id_colis']) ?>"></td>
                                                <td><?= htmlspecialchars($colis['nom_colis']) ?></td>
                                                <td><?= htmlspecialchars($colis['nature']) ?></td>
                                                <td><?= htmlspecialchars($colis['valeur']) ?></td>
                                                <td><?= htmlspecialchars($colis['fraix_transaction']) ?></td>
                                                <td><?= htmlspecialchars($colis['destination']) ?></td>

                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="col-12 mt-4">
                                <button class="btn btn-primary" type="submit" name="submit">Enregistre</button>

                            </div>

                        </div>
                    </div>
                </form>
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
    <!-- ✅ Script JS pour gérer la sélection de tous les checkboxes -->
    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.checkbox-car').forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        });
    </script>


</body>

</html>