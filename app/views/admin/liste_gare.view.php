<?php $this->view('admin/partials/header') ?>
<?php $user = new Configuration($_SESSION['id_utilisateur']) ?>
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
                <div class="breadcrumb-title pe-3">Configuration</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Gares</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="d-flex gap-2">
                        <button type="button" id="btnOuvrirAjouterGare" class="btn btn-success d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAjouterGare">
                            <i class="bx bx-plus-circle fs-5"></i> Ajouter
                        </button>
                        <a href="javascript:history.back()"
                            class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                            <i class="bx bx-left-arrow-alt fs-5"></i> Retour
                        </a>

                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                                        <div class="col-12 col-xxl-3">
          <div class="card config-card">
            <div class="card-header">
              <div class="card-title">
                <i class="bx bx-cog fs-4 me-2"></i> Paramètres Généraux
              </div>
            </div>
            <div class="card-body p-3">
              <ul class="nav nav-tabs flex-column vertical-tabs-custom" role="tablist">
                <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                  <li class="nav-item">
                    <a class="nav-link  text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Compagnies"
                      aria-selected="true">
                      <i class="bx bx-buildings me-2 align-middle d-inline-block"></i>Compagnie
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($user->userHasPermission('utilisateur_apercu')) { ?>
                  <li class="nav-item">
                    <a class="nav-link  text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Configurations"
                      aria-selected="true">
                      <i class="bx bx-user me-2 align-middle d-inline-block"></i>Utilisateur
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_gare')) { ?>
                  <li class="nav-item">
                    <a class="nav-link active text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Liste_gares"
                      aria-selected="true">
                      <i class="bx bx-home me-2 align-middle d-inline-block"></i>Gares
                    </a>
                  </li>
                <?php } ?>
                
                <?php if ($user->userHasPermission('Configuration_gestion_escale')) { ?>
                  <li class="nav-item">
                    <a class="nav-link  text-break mb-0" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_escales"
                      aria-selected="true">
                      <i class="bx bx-map-pin me-2 align-middle d-inline-block"></i>Escale
                    </a>
                  </li>
                <?php } ?>
            
                
                <?php if ($user->userHasPermission('Configuration_gestion_horaire')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link  text-break mb-0" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_horaire"
                      aria-selected="true">
                      <i class="bx bx-time me-2 align-middle d-inline-block"></i>Horaire
                    </a>
                  </li>
                <?php } ?>
                <?php if ($user->userHasPermission('Configuration_gestion_car/chauffeur')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link  text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Cars_chauffeurs"
                      aria-selected="true">
                      <i class="bx bx-car me-2 align-middle d-inline-block"></i>Cars & Chauffeurs
                    </a>
                  </li>
                <?php } ?>
                
                <?php if ($_SESSION['droit'] === 'super_admin'): ?>
                <li class="nav-item mt-2">
                  <a class="nav-link  text-break mb-0" role="tab"
                    aria-current="page" href="<?= BASE_URL ?>/admin/Add_liste_horaire/add_permission"
                    aria-selected="true">
                    <i class="bx bx-shield-quarter me-2 align-middle d-inline-block"></i>Permission
                  </a>
                </li>
                <?php endif; ?>
                
                <?php if ($user->userHasPermission('Configuration_place/limite')) { ?>
                  <li class="nav-item mt-2">
                    <a class="nav-link  text-break" role="tab"
                      aria-current="page" href="<?= BASE_URL ?>/admin/Compagnies/place_limite"
                      aria-selected="true">
                      <i class="bx bx-chair me-2 align-middle d-inline-block"></i>Place limite
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
                <div class="col-12 col-xxl-9">
                    <?php $this->view("admin/set_flash") ?>
                    <div class="card config-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="bx bx-buildings me-2"></i>Liste des gares</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered table-hover-effect table-custom-header text-center mobile-card-table" style="width:100%">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>N gare</th>
                                            <th>Localite</th>
                                            <th>Code marchant</th>
                                            <th>Tel</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($listes as $liste) : ?>
                                            <tr>
                                                <td data-label="N gare"><?= $liste->numeroGare ?></td>
                                                <td data-label="Localite"><?= $liste->localite ?></td>
                                                <td data-label="Code marchant"><?= $liste->code ?></td>
                                                <td data-label="Tel"><?= $liste->tel ?></td>
                                                <td data-label="Statut">
                                                    <?php if(isset($liste->status) && $liste->status == 0): ?>
                                                        <span class="badge bg-danger">Suspendue</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class=" " data-label="Action">
                                                    <div class="dropup text-center">
                                                        <a href="#" class="-toggle text-dark text-decoration-none fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                                                            &#8943; <!-- Trois points horizontaux -->
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu">
                                                            <a class="dropdown-item edit-btn"
                                                                data-bs-toggle="modal" data-bs-target="#exampleDangerModal1"
                                                                data-numeros="<?= htmlspecialchars($liste->idAgence, ENT_QUOTES) ?>"
                                                                data-numero="<?= htmlspecialchars($liste->numeroGare, ENT_QUOTES) ?>"
                                                                data-localite="<?= htmlspecialchars($liste->localite, ENT_QUOTES) ?>"
                                                                data-code="<?= htmlspecialchars($liste->code, ENT_QUOTES) ?>"
                                                                data-tel="<?= htmlspecialchars($liste->tel, ENT_QUOTES) ?>"
                                                                href="">Modifier</a>
                                                            <a class="dropdown-item" href="<?= BASE_URL ?>/admin/Liste_gares/suspend/<?= $liste->idAgence ?>">
                                                                <?= (isset($liste->status) && $liste->status == 0) ? 'Activer' : 'Suspendre' ?>
                                                            </a>
                                                            <a class="dropdown-item text-danger delete-button" href="<?= BASE_URL ?>/admin/Liste_gares/delete/<?= $liste->idAgence ?>">Supprimer</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
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
    <!--model pour la modification-->
    <div class="modal fade" id="exampleDangerModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Modification du gare</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dart">
                    <form class="row g-3 "
                        method="post" action="<?= BASE_URL ?>/admin/Liste_gares/edit">
                        <input type="hidden" name="idAgence" id="inputnumeros">
                        <div class="col-md-6">
                            <label for="tel" class="form-label">Numero du gare
                            </label>
                            <input type="text" class="form-control"
                                value="" name="numeroGare" id="inputnumero"
                                placeholder="" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tel" class="form-label">Localite
                            </label>
                            <input type="text" class="form-control"
                                value="" name="localite"
                                placeholder="" required id="inputlocalite">
                        </div>
                        <div class="col-md-6">
                            <label for="tel" class="form-label">Code marchant du gare
                            </label>
                            <input type="text" class="form-control"
                                value="" name="code"
                                placeholder="" required id="inputcode">
                        </div>
                        <div class="col-md-6">
                            <label for="bsValidation10"
                                class="form-label">Numero d'orange</label>
                            <input type="text" class="form-control" id="tel"
                                maxlength="11" oninput="verifierNumero()"
                                value="" name="tel" placeholder="" required>
                            <small id="messageErreur" class="text-danger"></small>
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" name="edit"
                                    class="btn btn-primary px-4">Modifier</button>
                                <a href="" class="btn btn-info">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- la fin du model de modification -->

    <?php
        // Rejoue la modal d'ajout pré-remplie après une tentative en échec (voir
        // Liste_gares::add_gares(), pattern POST -> Redirect -> GET) : $lignesEnErreur
        // vient de la session, consommée une seule fois par le contrôleur.
        $lignesAffichees = !empty($lignesEnErreur) ? $lignesEnErreur : [
            ['localite' => '', 'numeroGare' => '', 'code' => '', 'tel' => '', 'erreur' => null, 'champs_en_erreur' => []],
        ];
    ?>
    <!--modal pour l'ajout de gares-->
    <div class="modal fade" id="modalAjouterGare" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Ajouter une gare</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formGares" method="post" action="<?= BASE_URL ?>/admin/Liste_gares/add_gares" novalidate>
                        <!-- Champ caché plutôt que name="enregistre" sur le bouton : si le bouton est
                             désactivé (pour afficher le spinner) au moment où le navigateur construit
                             les données du formulaire, son name/value est exclu de l'envoi et le
                             contrôleur (qui vérifie isset($_POST['enregistre'])) ne voit jamais la
                             soumission. Un champ caché reste envoyé quel que soit l'état du bouton. -->
                        <input type="hidden" name="enregistre" value="1">
                        <div id="garesRows">
                            <?php foreach ($lignesAffichees as $ligne):
                                $champsErreur = $ligne['champs_en_erreur'] ?? [];
                            ?>
                            <div class="row g-3 gare-row mb-3 pb-3 border-bottom">
                                <?php if (!empty($ligne['erreur'])): ?>
                                    <div class="col-12">
                                        <div class="alert alert-danger py-2 px-3 mb-0 small"><?= htmlspecialchars($ligne['erreur']) ?></div>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Localité <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= in_array('localite', $champsErreur, true) ? 'is-invalid' : '' ?>" name="localite[]" placeholder="Ex: Ségou" required autocomplete="off" value="<?= htmlspecialchars($ligne['localite']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Numéro de gare <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= in_array('numeroGare', $champsErreur, true) ? 'is-invalid' : '' ?>" name="numeroGare[]" placeholder="Ex: Gare I" required autocomplete="off" value="<?= htmlspecialchars($ligne['numeroGare']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Code marchand <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= in_array('code', $champsErreur, true) ? 'is-invalid' : '' ?>" name="code[]" placeholder="Ex: 123489" required autocomplete="off" value="<?= htmlspecialchars($ligne['code']) ?>">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Numéro Orange (Mobile Money) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control gare-tel <?= in_array('tel', $champsErreur, true) ? 'is-invalid' : '' ?>" maxlength="11" name="tel[]" placeholder="Ex: 78907812" required autocomplete="off" value="<?= htmlspecialchars($ligne['tel']) ?>">
                                    <small class="text-danger mt-1 d-block gare-tel-error"></small>
                                </div>
                                <div class="col-md-1 d-flex align-items-start justify-content-end">
                                    <button type="button" class="btn btn-outline-danger remove-row-btn mt-4 <?= count($lignesAffichees) <= 1 ? 'd-none' : '' ?>" title="Retirer cette ligne">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="button" id="addGareRow" class="btn btn-sm btn-outline-primary mb-3">
                            <i class="bx bx-plus"></i> Ajouter une ligne
                        </button>

                        <div class="modal-footer border-0 px-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                            <button class="btn btn-primary fw-semibold d-flex align-items-center" type="submit" id="submitGareBtn">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="submitGareSpinner" role="status" aria-hidden="true"></span>
                                <i class="bx bx-save fs-5 me-2" id="submitGareIcon"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- fin du modal d'ajout -->

    <?php $this->view('admin/partials/foot') ?>
    <script src="<?= BASE_URL ?>/mon_js/scrip_agence.js"></script>
    <script src="<?= BASE_URL ?>/mon_js/alert_delete.js"></script>
    <script>
        // "Add to row" pour la modal d'ajout de gares : voir add_gare.view.php (ancienne
        // page, conservée telle quelle pour référence) pour le détail des choix (novalidate
        // + validation JS complète, classes .gare-tel/.gare-tel-error scopées par ligne car
        // les lignes sont dupliquées dynamiquement).
        document.addEventListener("DOMContentLoaded", function() {
            const modalEl = document.getElementById("modalAjouterGare");
            const ouvrirBtn = document.getElementById("btnOuvrirAjouterGare");
            const form = document.getElementById("formGares");
            const rowsContainer = document.getElementById("garesRows");
            const addBtn = document.getElementById("addGareRow");
            const submitBtn = document.getElementById("submitGareBtn");
            const submitSpinner = document.getElementById("submitGareSpinner");
            const submitIcon = document.getElementById("submitGareIcon");

            // Ouverture/fermeture de la modal en JS pur (classes Bootstrap manipulées à la
            // main), sans passer par le composant bootstrap.Modal ni son data-API. Ça évite
            // de dépendre du bon chargement/initialisation de bootstrap.bundle.min.js pour
            // cette seule fonctionnalité : si data-bs-toggle échoue pour une raison qui nous
            // échappe côté navigateur, ce bouton reste garanti de fonctionner.
            let backdropEl = null;
            function ouvrirModal() {
                modalEl.classList.add("show");
                modalEl.style.display = "block";
                modalEl.removeAttribute("aria-hidden");
                modalEl.setAttribute("aria-modal", "true");
                document.body.classList.add("modal-open");
                if (!backdropEl) {
                    backdropEl = document.createElement("div");
                    backdropEl.className = "modal-backdrop fade show";
                    document.body.appendChild(backdropEl);
                }
            }
            function fermerModal() {
                modalEl.classList.remove("show");
                modalEl.style.display = "none";
                modalEl.setAttribute("aria-hidden", "true");
                modalEl.removeAttribute("aria-modal");
                document.body.classList.remove("modal-open");
                if (backdropEl) {
                    backdropEl.remove();
                    backdropEl = null;
                }
            }

            ouvrirBtn.addEventListener("click", ouvrirModal);
            modalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(btn) {
                btn.addEventListener("click", fermerModal);
            });
            modalEl.addEventListener("click", function(e) {
                if (e.target === modalEl) {
                    fermerModal();
                }
            });

            function toggleRemoveButtons() {
                const rows = rowsContainer.querySelectorAll(".gare-row");
                rows.forEach(function(row) {
                    row.querySelector(".remove-row-btn").classList.toggle("d-none", rows.length <= 1);
                });
            }

            function formaterNumero(numero) {
                numero = numero.replace(/\D/g, '');
                if (numero.length > 8) {
                    numero = numero.substring(0, 8);
                }
                return numero.replace(/(\d{2})(?=\d)/g, '$1 ');
            }

            function verifierNumeroRow(input) {
                const erreur = input.closest(".gare-row").querySelector(".gare-tel-error");
                const numero = input.value.replace(/\D/g, '');
                input.value = formaterNumero(numero);

                let valide = true;
                let message = "";
                if (/^[1234]/.test(numero)) {
                    message = "Le numéro ne doit pas commencer par 1, 2, 3 ou 4.";
                    valide = false;
                } else if (numero.length !== 8) {
                    message = "Le numéro doit contenir exactement 8 chiffres.";
                    valide = false;
                }

                erreur.textContent = message;
                input.classList.toggle("is-invalid", !valide);
                return valide;
            }

            rowsContainer.addEventListener("input", function(e) {
                if (!e.target.matches("input")) {
                    return;
                }
                if (e.target.classList.contains("gare-tel")) {
                    verifierNumeroRow(e.target);
                } else if (e.target.value.trim() !== "") {
                    e.target.classList.remove("is-invalid");
                }
            });

            addBtn.addEventListener("click", function() {
                const firstRow = rowsContainer.querySelector(".gare-row");
                const newRow = firstRow.cloneNode(true);
                newRow.querySelectorAll("input").forEach(function(input) {
                    input.value = "";
                    input.classList.remove("is-invalid");
                });
                newRow.querySelector(".gare-tel-error").textContent = "";
                const alerte = newRow.querySelector(".alert-danger");
                if (alerte) {
                    alerte.closest(".col-12").remove();
                }
                rowsContainer.appendChild(newRow);
                toggleRemoveButtons();
            });

            rowsContainer.addEventListener("click", function(e) {
                const btn = e.target.closest(".remove-row-btn");
                if (btn) {
                    btn.closest(".gare-row").remove();
                    toggleRemoveButtons();
                }
            });

            function validerFormulaire() {
                let valide = true;
                let premierChampInvalide = null;

                rowsContainer.querySelectorAll(".gare-row").forEach(function(row) {
                    row.querySelectorAll("input[required]").forEach(function(input) {
                        const vide = input.value.trim() === "";
                        input.classList.toggle("is-invalid", vide);
                        if (vide) {
                            valide = false;
                            premierChampInvalide = premierChampInvalide || input;
                        }
                    });

                    const telInput = row.querySelector(".gare-tel");
                    if (telInput.value.trim() !== "" && !verifierNumeroRow(telInput)) {
                        valide = false;
                        premierChampInvalide = premierChampInvalide || telInput;
                    }
                });

                if (!valide && premierChampInvalide) {
                    premierChampInvalide.scrollIntoView({ behavior: "smooth", block: "center" });
                    premierChampInvalide.focus();
                }

                return valide;
            }

            form.addEventListener("submit", function(e) {
                if (!validerFormulaire()) {
                    e.preventDefault();
                    return;
                }
                submitBtn.disabled = true;
                submitSpinner.classList.remove("d-none");
                submitIcon.classList.add("d-none");
            });

            <?php if (!empty($lignesEnErreur)): ?>
            // Une tentative précédente a échoué (redirigée ici par le contrôleur) : on
            // rouvre directement la modal pré-remplie, l'utilisateur n'a rien à recliquer.
            ouvrirModal();
            <?php endif; ?>
        });
    </script>

</body>

</html>