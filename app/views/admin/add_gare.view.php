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
                            <li class="breadcrumb-item active" aria-current="page">Gestion des gares</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="d-flex gap-2">

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
                            <h5 class="mb-0 fw-bold"><i class="bx bx-map me-2"></i>Espace d'enregistrement des gares</h5>
                        </div>
                        <div class="card-body">
                            <div class="p-4 border rounded-3">
                                <?php
                                    // Tout ou rien : s'il y a eu des erreurs, saveGares() renvoie TOUTES les
                                    // lignes soumises (pas seulement celles en échec), avec leurs valeurs et
                                    // les champs fautifs, puisque rien n'a été enregistré. Sinon un seul rang
                                    // vide par défaut.
                                    $lignesAffichees = !empty($lignesEnErreur) ? $lignesEnErreur : [
                                        ['localite' => '', 'numeroGare' => '', 'code' => '', 'tel' => '', 'erreur' => null, 'champs_en_erreur' => []],
                                    ];
                                ?>
                                <form id="formGares" method="post" novalidate>
                                    <!-- Lignes de gares, ajoutables dynamiquement -->
                                    <div id="garesRows">
                                        <?php foreach ($lignesAffichees as $ligne):
                                            $champsErreur = $ligne['champs_en_erreur'] ?? [];
                                        ?>
                                        <div class="row g-4 gare-row mb-3 pb-3 border-bottom">
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
                                                <label class="form-label fw-semibold">Code marchand de la gare <span class="text-danger">*</span></label>
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

                                    <div class="mt-2 d-flex gap-3">
                                        <button class="btn btn-primary fw-semibold d-flex align-items-center" type="submit" name="enregistre" id="submitGareBtn">
                                            <span class="spinner-border spinner-border-sm me-2 d-none" id="submitGareSpinner" role="status" aria-hidden="true"></span>
                                            <i class="bx bx-save fs-5 me-2" id="submitGareIcon"></i> Enregistrer
                                        </button>
                                        <a href="<?= BASE_URL ?>/admin/Liste_gares" class="btn btn-light fw-semibold border shadow-sm d-flex align-items-center" style="border-radius: 12px; padding: 0.6rem 1.5rem;">
                                            <i class="bx bx-list-ul fs-5 me-2"></i> Voir la liste
                                        </a>
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
    <script>
        // "Add to row" : permet de saisir plusieurs gares d'un coup avant d'enregistrer.
        // Le formulaire est en novalidate : toute la validation (champs requis + format du
        // numéro) est faite ici en JS, avec la classe Bootstrap "is-invalid" pour souligner
        // en rouge exactement les champs fautifs (cohérent avec le rendu après une erreur
        // serveur, où saveGares() renvoie déjà les champs en erreur par ligne).
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("formGares");
            const rowsContainer = document.getElementById("garesRows");
            const addBtn = document.getElementById("addGareRow");
            const submitBtn = document.getElementById("submitGareBtn");
            const submitSpinner = document.getElementById("submitGareSpinner");
            const submitIcon = document.getElementById("submitGareIcon");

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

            // Vide/rempli, tout champ obligatoire touché retrouve un état neutre dès que
            // l'utilisateur corrige, sans attendre un nouveau clic sur Enregistrer.
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
                // La ligne clonée peut être une ligne rejouée après erreur (avec son bandeau
                // d'erreur) : on ne veut pas le dupliquer sur la nouvelle ligne vide.
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
                // Empêche un double-clic d'envoyer le formulaire deux fois pendant l'enregistrement.
                submitBtn.disabled = true;
                submitSpinner.classList.remove("d-none");
                submitIcon.classList.add("d-none");
            });
        });
    </script>

</body>

</html>


