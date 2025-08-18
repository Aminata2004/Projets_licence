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
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-programmer</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Enregistrement du programmer</li>
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
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-header px-4 py-3">
                        <h5 class="mb-0">Gestion du programmer</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="bsValidation1" class="form-label">Depart<span
                                            class="text-danger scale5 ms-2">*</span></label>
                                    <select id="choixAgence" name="idDepart" class="form-control">
                                        <option></option> <!-- option vide pour permettre le placeholder -->
                                        <?php
                                        $localites_vues = []; // tableau pour suivre les localités déjà affichées

                                        foreach ($liste_agence as $liste_agences):
                                            $localite = $liste_agences->localite;

                                            // Vérifie si cette localité a déjà été affichée
                                            if (in_array($localite, $localites_vues)) {
                                                continue; // on saute si elle est déjà dans la liste
                                            }

                                            // Sinon, on l'affiche et on la marque comme vue
                                            $localites_vues[] = $localite;
                                        ?>
                                            <option value="<?= htmlspecialchars($localite); ?>">
                                                <?= htmlspecialchars($localite); ?>
                                            </option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Escale</label>
                                    <select class="form-control multiple-select" multiple="multiple" placeholder="Choisissez un ou plusieurs escale" name="idEscale[]">
                                        <?php foreach ($listeEscale as $listeEscales): ?>
                                            <option
                                                value="<?= htmlspecialchars($listeEscales->id_escale); ?>">
                                                <?= htmlspecialchars($listeEscales->escales) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="bsValidation1" class="form-label">Destination<span
                                            class="text-danger scale5 ms-2">*</span></label>
                                    <select id="choixAgences" name="idDestination" class="form-control">
                                        <option></option> <!-- option vide pour permettre le placeholder -->
                                        <?php
                                        $localites_vues = []; // tableau pour suivre les localités déjà affichées

                                        foreach ($liste_agence as $liste_agences):
                                            $localite = $liste_agences->localite;

                                            // Vérifie si cette localité a déjà été affichée
                                            if (in_array($localite, $localites_vues)) {
                                                continue; // on saute si elle est déjà dans la liste
                                            }

                                            // Sinon, on l'affiche et on la marque comme vue
                                            $localites_vues[] = $localite;
                                        ?>
                                            <option value="<?= htmlspecialchars($localite); ?>">
                                                <?= htmlspecialchars($localite); ?>
                                            </option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 ">
                                    <label for="bsValidation10" class="form-label">Heur de depart<span
                                            class="text-danger scale5 ms-2">*</span></label>


                                    <select class="single-select" id="heureDepart" name="heureDepart" onchange="calculerRDV()">
                                        <option value="United States">Toutes les heures</option>
                                        <?php foreach ($listehoraire as $listehoraires): ?>
                                            <option value="<?= $listehoraires->heuredepart ?>">
                                                <?= $listehoraires->heuredepart ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>

                                </div>

                                <div class="col-md-6">
                                    <label for="bsValidation12" class="form-label">RDV
                                        <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="rdv" name="rdv"
                                        value="" required>
                                </div>

                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label for="bsValidation3" class="form-label">Fraix de transport<span
                                            class="text-danger scale5 ms-2">*</span></label>
                                    <input type="number" class="form-control" id="" name="prix"
                                        value="" required>

                                </div>
                            </div>

                            <div class="col-md-12 text-end mt-3">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4"
                                        name="enregistre">Enregistre</button>
                                    <a href="<?= BASE_URL?>/admin/Programmer_voyages"
                                        class="btn btn-primary split-bg-primary px-4"> voir le liste</a>

                                </div>
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
        function calculerRDV() {
            let heureDepart = document.getElementById("heureDepart").value;
            let rdvInput = document.getElementById("rdv");

            if (heureDepart) {
                // Convertir l'heure de départ en heures et minutes
                let [heures, minutes] = heureDepart.split(':').map(Number);

                // Soustraire 45 minutes
                minutes -= 45;
                if (minutes < 0) {
                    minutes += 60;
                    heures -= 1;
                }

                // Formatage pour affichage (ajout de zéro si nécessaire)
                let heureRDV = (heures < 10 ? "0" : "") + heures + ":" + (minutes < 10 ? "0" : "") + minutes;

                // Mettre la valeur calculée dans le champ RDV
                rdvInput.value = heureRDV;
            }
        }
    </script>
</body>

</html>