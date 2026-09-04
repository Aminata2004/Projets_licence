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
                            <li class="breadcrumb-item active" aria-current="page">Salaires</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="d-flex gap-2">
                        <a href="<?= BASE_URL ?>/admin/Salaires/liste_bulletins" class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                            <i class="bx bx-receipt fs-5"></i> Bulletins générés
                        </a>
                        <?php if ($peutGerer): ?>
                        <button type="button" id="genererSelectionBtn" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" disabled
                            data-bs-toggle="modal" data-bs-target="#genererBulletinsMultiModal">
                            <i class="bx bx-receipt fs-5"></i> Générer pour la sélection
                        </button>
                        <button type="button" class="btn btn-success d-flex align-items-center gap-2 shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#addEmployeModal">
                            <i class="bx bx-plus-circle fs-5"></i> Ajouter (hors-système)
                        </button>
                        <?php endif; ?>
                        <a href="javascript:history.back()"
                            class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                            <i class="bx bx-left-arrow-alt fs-5"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <?php $this->view("admin/set_flash") ?>

            <div class="card config-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bx bx-money me-2"></i>Liste des salaires</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header text-center mobile-card-table" style="width:100%">
                            <thead class="table-light text-center">
                                <tr>
                                    <?php if ($peutGerer): ?>
                                    <th class="fw-semibold"><input type="checkbox" id="selectAllEmployes"></th>
                                    <?php endif; ?>
                                    <th class="fw-semibold">Nom &amp; prénom</th>
                                    <th class="fw-semibold">Poste</th>
                                    <th class="fw-semibold">Gare</th>
                                    <th class="fw-semibold">Salaire de base</th>
                                    <th class="fw-semibold">Statut</th>
                                    <th class="fw-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listeEmployes as $employe): ?>
                                    <?php $horsSysteme = empty($employe->id_utilisateur) && empty($employe->id_chauffeur); ?>
                                    <tr>
                                        <?php if ($peutGerer): ?>
                                        <td data-label=""><input type="checkbox" class="employe-checkbox" value="<?= $employe->id_employe ?>"></td>
                                        <?php endif; ?>
                                        <td data-label="Nom & prénom"><?= htmlspecialchars($employe->nom_affiche ?? '') ?></td>
                                        <td data-label="Poste"><?= htmlspecialchars($employe->poste) ?></td>
                                        <td data-label="Gare"><?= htmlspecialchars($employe->localite ?? 'Compagnie entière') ?></td>
                                        <td data-label="Salaire de base"><?= number_format((float) $employe->salaire_base, 0, ',', ' ') ?> FCFA</td>
                                        <td data-label="Statut">
                                            <?php if ($employe->statut === 'actif'): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Action">
                                            <?php if ($peutGerer): ?>
                                            <div class="dropdown">
                                                <a href="#" class="text-dark fs-5" data-bs-toggle="dropdown" aria-expanded="false">
                                                    &#8943;
                                                </a>
                                                <ul class="dropdown-menu shadow-sm">
                                                    <li>
                                                        <a class="dropdown-item edit-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editEmployeModal"
                                                            data-id="<?= $employe->id_employe ?>"
                                                            data-poste="<?= htmlspecialchars($employe->poste, ENT_QUOTES) ?>"
                                                            data-salaire="<?= htmlspecialchars($employe->salaire_base, ENT_QUOTES) ?>"
                                                            data-agence="<?= htmlspecialchars($employe->id_agence ?? '', ENT_QUOTES) ?>"
                                                            data-statut="<?= htmlspecialchars($employe->statut, ENT_QUOTES) ?>"
                                                            data-hors-systeme="<?= $horsSysteme ? '1' : '0' ?>"
                                                            data-nom="<?= htmlspecialchars($employe->nom_affiche ?? '', ENT_QUOTES) ?>"
                                                            href="#">
                                                            ✏️ Modifier
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item generer-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#genererBulletinModal"
                                                            data-id="<?= $employe->id_employe ?>"
                                                            data-nom="<?= htmlspecialchars($employe->nom_affiche ?? '', ENT_QUOTES) ?>"
                                                            href="#">
                                                            🧾 Générer un bulletin
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--end row-->
        </main>
        <!--end page main-->

        <?php if ($peutGerer): ?>
        <!-- Modal ajout employé hors-système -->
        <div class="modal fade" id="addEmployeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?= BASE_URL ?>/admin/Salaires" method="post">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Ajouter un employé hors-système</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted small">Pour le personnel sans compte dans l'application (gardien, balayeur, etc.).</p>
                            <div class="mb-3">
                                <label class="form-label">Nom &amp; prénom</label>
                                <input type="text" class="form-control" name="nom_prenom" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Poste</label>
                                <input type="text" class="form-control" name="poste" placeholder="Ex: Gardien, Balayeur..." required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Salaire de base (FCFA)</label>
                                <input type="number" class="form-control" name="salaire_base" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gare de rattachement</label>
                                <select class="form-select" name="id_agence">
                                    <option value="">-- Compagnie entière --</option>
                                    <?php foreach ($listeAgences as $agence): ?>
                                        <option value="<?= $agence->idAgence ?>"><?= htmlspecialchars($agence->localite) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary" name="ajouter">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal édition -->
        <div class="modal fade" id="editEmployeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?= BASE_URL ?>/admin/Salaires/update" method="post">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Modifier la fiche employé</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_employe" id="edit_id">

                            <div class="mb-3 d-none" id="editNomField">
                                <label class="form-label">Nom &amp; prénom</label>
                                <input type="text" class="form-control" name="nom_prenom" id="edit_nom">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Poste</label>
                                <input type="text" class="form-control" name="poste" id="edit_poste" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Salaire de base (FCFA)</label>
                                <input type="number" class="form-control" name="salaire_base" id="edit_salaire" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gare de rattachement</label>
                                <select class="form-select" name="id_agence" id="edit_agence">
                                    <option value="">-- Compagnie entière --</option>
                                    <?php foreach ($listeAgences as $agence): ?>
                                        <option value="<?= $agence->idAgence ?>"><?= htmlspecialchars($agence->localite) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select class="form-select" name="statut" id="edit_statut">
                                    <option value="actif">Actif</option>
                                    <option value="inactif">Inactif</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal génération de bulletin -->
        <div class="modal fade" id="genererBulletinModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?= BASE_URL ?>/admin/Salaires/generer_bulletin" method="post">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Générer un bulletin de paie</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_employe" id="generer_id">
                            <p>Employé : <strong id="generer_nom"></strong></p>
                            <div class="mb-3">
                                <label class="form-label">Période</label>
                                <input type="month" class="form-control" name="periode" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Générer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal génération groupée : les cases cochées sont injectées en champs cachés
             (ids_employes[]) par JS juste avant l'ouverture, cf. script plus bas. -->
        <div class="modal fade" id="genererBulletinsMultiModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?= BASE_URL ?>/admin/Salaires/generer_bulletin" method="post" id="formGenererMulti">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Générer les bulletins sélectionnés</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong id="generer_multi_count">0</strong> employé(s) sélectionné(s).</p>
                            <div class="mb-3">
                                <label class="form-label">Période</label>
                                <input type="month" class="form-control" name="periode" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Générer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

    </div>
    <!--end wrapper-->

    <?php $this->view('admin/partials/foot') ?>
    <?php if ($peutGerer): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".edit-btn").forEach(function(button) {
                button.addEventListener("click", function() {
                    document.getElementById("edit_id").value = this.dataset.id;
                    document.getElementById("edit_poste").value = this.dataset.poste;
                    document.getElementById("edit_salaire").value = this.dataset.salaire;
                    document.getElementById("edit_agence").value = this.dataset.agence || '';
                    document.getElementById("edit_statut").value = this.dataset.statut;

                    // Le nom n'est éditable que pour le personnel hors-système (sinon il
                    // vient du compte utilisateur ou de la fiche chauffeur associée) :
                    // "disabled" (pas juste caché) pour qu'il ne soit pas soumis du tout
                    // sinon (un champ desactivé est exclu du POST par le navigateur).
                    const editNomField = document.getElementById("editNomField");
                    const editNomInput = document.getElementById("edit_nom");
                    if (this.dataset.horsSysteme === '1') {
                        editNomField.classList.remove('d-none');
                        editNomInput.disabled = false;
                        editNomInput.value = this.dataset.nom;
                    } else {
                        editNomField.classList.add('d-none');
                        editNomInput.disabled = true;
                        editNomInput.value = '';
                    }
                });
            });

            document.querySelectorAll(".generer-btn").forEach(function(button) {
                button.addEventListener("click", function() {
                    document.getElementById("generer_id").value = this.dataset.id;
                    document.getElementById("generer_nom").textContent = this.dataset.nom;
                });
            });

            // Sélection multiple (cases à cocher) pour générer plusieurs bulletins d'un
            // coup, meme période pour tout le lot.
            const selectAll = document.getElementById("selectAllEmployes");
            const genererSelectionBtn = document.getElementById("genererSelectionBtn");
            const employeCheckboxes = () => document.querySelectorAll(".employe-checkbox");

            function majBoutonSelection() {
                const nbCoches = document.querySelectorAll(".employe-checkbox:checked").length;
                genererSelectionBtn.disabled = nbCoches === 0;
            }

            if (selectAll) {
                selectAll.addEventListener("change", function() {
                    employeCheckboxes().forEach(function(cb) { cb.checked = selectAll.checked; });
                    majBoutonSelection();
                });
            }
            employeCheckboxes().forEach(function(cb) {
                cb.addEventListener("change", majBoutonSelection);
            });

            // Juste avant l'ouverture du modal de génération groupée : injecte un champ
            // caché ids_employes[] par case cochée (le formulaire n'a lui-même aucune
            // case, seulement le tableau principal).
            const modalMulti = document.getElementById("genererBulletinsMultiModal");
            const formMulti = document.getElementById("formGenererMulti");
            modalMulti.addEventListener("show.bs.modal", function() {
                formMulti.querySelectorAll("input[name='ids_employes[]']").forEach(function(el) { el.remove(); });
                const coches = document.querySelectorAll(".employe-checkbox:checked");
                coches.forEach(function(cb) {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "ids_employes[]";
                    input.value = cb.value;
                    formMulti.appendChild(input);
                });
                document.getElementById("generer_multi_count").textContent = coches.length;
            });
        });
    </script>
    <?php endif; ?>

</body>

</html>
