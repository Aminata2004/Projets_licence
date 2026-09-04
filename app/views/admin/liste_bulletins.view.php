<?php $this->view('admin/partials/header') ?>

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
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Personnel</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/Salaires">Salaires</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bulletins générés</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <a href="javascript:history.back()"
                        class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                        <i class="bx bx-left-arrow-alt fs-5"></i> Retour
                    </a>
                </div>
            </div>
            <!--end breadcrumb-->

            <?php $this->view("admin/set_flash") ?>

            <div class="card config-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bx bx-receipt me-2"></i>Bulletins de paie générés</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header text-center mobile-card-table" style="width:100%">
                            <thead class="table-light text-center">
                                <tr>
                                    <th class="fw-semibold">Employé</th>
                                    <th class="fw-semibold">Poste</th>
                                    <th class="fw-semibold">Période</th>
                                    <th class="fw-semibold">Montant</th>
                                    <th class="fw-semibold">Généré le</th>
                                    <th class="fw-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listeBulletins as $bulletin): ?>
                                    <tr>
                                        <td data-label="Employé"><?= htmlspecialchars($bulletin->nom_affiche ?? '') ?></td>
                                        <td data-label="Poste"><?= htmlspecialchars($bulletin->poste ?? '') ?></td>
                                        <td data-label="Période"><?= htmlspecialchars($bulletin->periode) ?></td>
                                        <td data-label="Montant"><?= number_format((float) $bulletin->salaire_verse, 0, ',', ' ') ?> FCFA</td>
                                        <td data-label="Généré le"><?= date('d/m/Y à H:i', strtotime($bulletin->date_generation)) ?></td>
                                        <td data-label="Action">
                                            <a href="<?= BASE_URL ?>/admin/Salaires/telecharger_bulletin/<?= $bulletin->id_bulletin ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-download me-1"></i> Télécharger
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                <?php if (empty($listeBulletins)): ?>
                                    <tr>
                                        <td colspan="6" class="text-muted py-4">Aucun bulletin généré pour le moment.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
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

    <?php $this->view('admin/partials/foot') ?>

</body>

</html>
