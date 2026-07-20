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
        <main class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Gestion des caisses</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Ajout </li>
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
            <div class="row">
                <div class="col-xxl-12 col-xl-12 mx-auto">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="card shadow-lg rounded-3 border-0">
                        <div class="card-header d-flex justify-content-between align-items-center rounded-top border-bottom">
                            <h5 class="mb-0 fw-bold text-primary">Espace d'enregistrement des caisse</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="p-4 border rounded-3">
                                <form class="row g-3" method="post">
                                    <?= csrf_field() ?>

                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="form-label fw-semibold">Reference caisse</label>
                                        <input type="text" class="form-control" id="validationCustom01" value="RF<?= date('md')?>" name="reference_caise" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="validationCustom02" class="form-label fw-semibold">Agence</label>
                                        <select class="single-select" id="droitSelect" name="id_agence" required>
                                        <option value="United States" disabled selected></option>
                                         <?php foreach ($listes as $liste): ?>
                                            <option value="<?= htmlspecialchars($liste->idAgence); ?>">
                                                <?= htmlspecialchars($liste->localite . ' ( ' . $liste->numeroGare . ' )'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="validationCustomUsername" class="form-label fw-semibold">date enregistrement</label>
                                        <div class="input-group has-validation">
                                            <input type="date" class="form-control" name="date_enregistrement" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="validationCustom03" class="form-label fw-semibold">Montant initial</label>
                                        <input type="number" class="form-control" name="montant_initial" required>
                                    </div>

                                    <!-- Champ Droit -->
                                    <!-- <div class="col-md-6">
                                        <label for="droitSelect" class="form-label fw-semibold">Droit</label>
                                        <select class="form-select" id="droitSelect" name="droit" required>
                                            <option value="" disabled selected>Le droit</option>
                                            <option value="Utilisateur">Utilisateur</option>
                                            <option value="chef_d_escale">Chef d'escale</option>
                                            <option value="Admin">Admin</option>
                                        </select>
                                    </div> -->

                                    <!-- Champ Gare -->
                                    <!-- <div class="col-md-6" id="gareField">
                                        <label for="gareSelect" class="form-label fw-semibold">Gare</label>
                                        <select class="form-select" id="gareSelect" name="id_agence">
                                            <option selected disabled value="">Choisissez une gare</option>
                                            <?php foreach ($listeGares as $listeGare): ?>
                                                <option value="<?= htmlspecialchars($listeGare->idAgence); ?>">
                                                    <?= htmlspecialchars($listeGare->localite . ' ( ' . $listeGare->numeroGare . ' )'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div> -->

                                    <!-- Champ Compagnie -->
                                    <!-- <div class="col-md-12" id="compagnieField" style="display: none;">
                                        <label for="compagnieSelect" class="form-label fw-semibold">Compagnie</label>
                                        <select class="form-select" id="compagnieSelect" name="id_compagnie">
                                            <option value="" disabled selected>Sélectionnez une compagnie</option>
                                            <?php foreach ($listeCompagnie as $listeCompagnies): ?>
                                                <option value="<?= htmlspecialchars($listeCompagnies->id_compagnie); ?>">
                                                    <?= htmlspecialchars($listeCompagnies->nom_compagnie); ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div> -->

                                    <div class="col-12 mt-3 d-flex gap-2">
                                        <button class="btn btn-outline-primary fw-semibold" type="submit" name="saveAgence">Enregistrer</button>
                                        <a href="<?= BASE_URL ?>/admin/Caisse" class="btn btn-outline-secondary fw-semibold">Voir la liste</a>
                                    </div>

                                </form>
                            </div>
                        </div>
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
    <!-- JS -->

</body>
</html>