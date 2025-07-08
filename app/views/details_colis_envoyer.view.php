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
                            <li class="breadcrumb-item active text-primary" aria-current="page">Colis envoyés pour le car N° <?= htmlspecialchars($id_car) ?>
                                le <?= date('d/m/Y à H:i', strtotime($date_envoi)) ?>

                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">

                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body ">
                    <div class="">

                        <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header" style="width:100%">

                            <thead>
                                <tr>
                                    <th>Nom du colis</th>
                                    <th>Nature</th>
                                    <th>Valeur</th>
                                    <th>Frais de transaction</th>
                                    <th>Date d'envoi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($liste_colis as $colis): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($colis->nom_colis) ?></td>
                                        <td><?= htmlspecialchars($colis->nature) ?></td>
                                        <td><?= htmlspecialchars($colis->valeur) ?></td>
                                        <td><?= htmlspecialchars($colis->fraix_transaction) ?></td>
                                        <td><?= htmlspecialchars($colis->date_enregistre) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </table>

                        </table>
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


    <?php $this->view('partials/foot') ?>

</body>

</html>