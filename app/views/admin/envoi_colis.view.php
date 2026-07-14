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
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-colis</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Envoi des colis</li>
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
            <?php $this->view("admin/set_flash") ?>
            <div class="card">
                <form action="" method="post">
                    <div class="card-body border-top border-primary border-4">
                        <div class="col-4">
                            <select name="id_car_selectionner" class="form-control">
                                <option disabled selected>Choisir un car</option>
                                <?php foreach ($listeprogrammer as $car): ?>
                                    <option value="<?= htmlspecialchars($car['id_car_programmer']) ?>">
                                        <?= htmlspecialchars('Car ' . $car['id_car_programmer'] . ' — Destination :  ' . $car['id_trajet'] . ' — Départ ' . $car['heuredepart']) ?>

                                    </option>
                                <?php endforeach; ?>
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
                                <button class="btn btn-primary" type="submit" name="envoi">Enregistre</button>
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


    <?php $this->view('admin/partials/foot') ?>
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