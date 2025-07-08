<?php $this->view('partials/header') ?>

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
        <main class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Configuration</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Utilisateur</li>
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
                <div class="col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                Generale
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs flex-column vertical-tabs-3" role="tablist">
                                <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-break" role="tab"
                                            aria-current="page" href="<?= BASE_URL ?>/Compagnies"
                                            aria-selected="true">
                                            <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Compagnie
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <a class="nav-link active text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Configurations"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Utilisateur
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Liste_gares"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Gares
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-break mb-0" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Add_liste_escales"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Escale
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link  text-break mb-0" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Add_liste_trajets"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Trajets
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link  text-break mb-0" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Add_liste_horaire"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Horaire
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link  text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Cars_chauffeurs"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Cars & Chauffeurs
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link  text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Programmation_cars"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Programmation des cars
                                    </a>
                                </li>
                                   <li class="nav-item mt-2">
                                    <a class="nav-link  text-break" role="tab"
                                        aria-current="page" href="<?= BASE_URL ?>/Compagnies/place_limite"
                                        aria-selected="true">
                                        <i class="bx-shape-polygon me-2 align-middle d-inline-block"></i>Place limite
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-9">
                    <?php $this->view("set_flash") ?>
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                Espace d'enregistrement des utilisateurs
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="p-4 border rounded">
                                    <form class="row g-3 " method="post">
                                        <div class="col-md-6">
                                            <label for="validationCustom01" class="form-label">Utilisateurs</label>
                                            <input type="text" class="form-control" id="validationCustom01" placeholder="Nom & prenom" name="utilisateurs" required>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="validationCustom02" class="form-label">Email</label>
                                            <input type="text" class="form-control" id="validationCustom02" placeholder="Email" name="emailUser" required>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="validationCustomUsername" class="form-label">Mot de passe</label>
                                            <div class="input-group has-validation">
                                                <input type="password" class="form-control" id="" name="motPasse" required>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="validationCustom03" class="form-label">Confirmer mot de passe</label>
                                            <input type="password" class="form-control" id="validationCustom03" name="ConfirmermotPasse" required>

                                        </div>
                                        <!-- Champ Droit -->
                                        <div class="col-md-6">
                                            <label for="droitSelect" class="form-label">Droit</label>
                                            <select class="form-select" id="droitSelect" name="droit" required>
                                                <option data-display="Select">Le droit</option>
                                                <option value="Utilisateur">Utilisateur</option>
                                                <option value="Admin_regionale">Admin_regionale</option>
                                                <option value="Admin">Admin</option>
                                            </select>
                                        </div>

                                        <!-- Champ Gare -->
                                        <div class="col-md-6" id="gareField">
                                            <label for="gareSelect" class="form-label">Gare</label>
                                            <select class="form-select" id="gareSelect" name="id_agence">
                                                <option selected disabled value="">Choisissez une gare</option>
                                                <?php foreach ($listeGares as $listeGare): ?>
                                                    <option value="<?= htmlspecialchars($listeGare->idAgence); ?>">
                                                        <?= htmlspecialchars($listeGare->localite . ' ( ' . $listeGare->numeroGare . ' )'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>


                                        <!-- Champ Compagnie -->
                                        <div class="col-md-12" id="compagnieField" style="display: none;">
                                            <label for="compagnieSelect" class="form-label">Compagnie</label>
                                            <select class="form-select" id="compagnieSelect" name="id_compagnie">
                                                <option data-display="Select"></option>
                                                <?php foreach ($listeCompagnie as $listeCompagnies): ?>
                                                    <option value="<?= htmlspecialchars($listeCompagnies->id_compagnie); ?>">
                                                        <?= htmlspecialchars($listeCompagnies->nom_compagnie); ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit" name="saveutilisateur">Enregistre</button>
                                            <a href="<?= BASE_URL ?>/Configurations" class="btn btn-info">Voir la liste</a>

                                        </div>
                                    </form>
                                </div>
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


    <?php $this->view('partials/foot') ?>
    <!-- JS -->
    <script>
        document.getElementById('droitSelect').addEventListener('change', function() {
            const droit = this.value;
            const compagnieField = document.getElementById('compagnieField');
            const gareField = document.getElementById('gareField');

            if (droit === 'Admin') {
                compagnieField.style.display = 'block';
                gareField.style.display = 'none';
            } else {
                compagnieField.style.display = 'none';
                gareField.style.display = 'block';
            }
        });
    </script>
</body>

</html>