<?php $this->view('admin/partials/headers') ?>

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        <?php $this->view('admin/partials/navbar')
        ?>
        <!--end top header-->

        <!--start sidebar -->
        <?php $this->view('admin/partials/sidebar')
        ?>
        <!--end sidebar -->

        <!--start content-->
        <main class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-reservation</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Enregistrement de ticket</li>
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
                <div class="col-xl-12 mx-auto">
                    <?php $this->view("admin/set_flash") ?>
                    <?php $this->view("admin/helpers") ?>
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0 text-primary">Reservation des billets</h5>
                        </div>

                        <div class="card-body">
                            <div class="form-validation">
                                <form class="needs-validation" method="POST">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="mb-3 row">
                                                <label class="col-lg-4 col-form-label"
                                                    for="validationCustom06">Nom et
                                                    Prénom
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="" name="Client"
                                                        value="" required
                                                        placeholder="Nom & Prénom">
                                                    <div class="invalid-feedback">
                                                        Veuillez entrez le nom et le prenom du client.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-lg-4 col-form-label"
                                                    for="validationCustom05">Destination
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <!-- Champ select pour choisir la destination -->
                                                    <select class="form-select" name="destination" id="destinationSelect">
                                                        <option value="">Choisissez une destination</option>
                                                        <?php foreach ($destinations as $i => $dest): ?>
                                                            <option value="<?= $i ?>" data-id="<?= $dest['nom'] ?>">
                                                                <?= htmlspecialchars($dest['nom']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                    <div class="invalid-feedback">
                                                        Veuillez choisir la destination du client.
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="destinationId" id="hiddenDestinationId">

                                            <!-- Date de voyage (min = aujourd’hui, max = demain) -->
                                            <div class="mb-3 row">
                                                <label class="col-lg-4 col-form-label" for="jourVoyage">Date de voyage
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="date"
                                                        class="form-control"
                                                        id="jourVoyage"
                                                        name="jourVoyage"
                                                        required>
                                                    <div class="invalid-feedback">Veuillez saisir la date de départ.</div>
                                                </div>
                                            </div>

                                            <!-- Heure de départ -->
                                            <div class="mb-3 row">
                                                <label class="col-lg-4 col-form-label" for="programmeSelect">Heure de départ
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8" style="margin-top:20px;">
                                                    <select name="programme" class="form-select" id="programmeSelect">
                                                        <option value="">Sélectionnez une heure</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Escales (cases à cocher) -->
                                            <div class="mb-3 row">
                                                <label class="col-lg-4 col-form-label" for="escalesList">Escales</label>
                                                <div class="col-lg-8" id="escalesList" style="margin-top:8px"></div>
                                                <input type="hidden" id="escaleSelected" name="escaleNom" value="">
                                            </div>

                                            <!-- Champ Nombre de passagers -->
                                            <div class="mb-3 row" id="nombrePassagersRow">
                                                <label class="col-lg-4 col-form-label" for="nombrePassagers">
                                                    Nombre de passagers <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="number" class="form-control"
                                                        id="nombrePassagers" name="nombrePassages"
                                                        placeholder="Nombre de passagers"
                                                        oninput="calculerPrixTotal()">
                                                    <div class="invalid-feedback">
                                                        Veuillez entrer le nombre de passagers.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label class="col-lg-4 col-form-label" for="prixTotal">
                                                    Prix <span class="text-danger">*</span>
                                                </label>

                                                <!-- Prix automatique -->
                                                <div class="col-lg-8" id="prixAutoBox">
                                                    <input type="hidden" class="form-control" id="prix" name="prix_unitaire" readonly>
                                                    <input type="text" class="form-control" id="prixTotal" name="montant_payer" readonly>
                                                </div>

                                                <!-- Prix manuel pour escale -->
                                                <div class="col-lg-8 d-none" id="prixManuelBox" style="margin-top:10px;">
                                                    <input type="number" class="form-control" id="prixManuel" name="montant_payers" placeholder="Saisir prix l'escale">
                                                </div>
                                            </div>


                                            <div class="mb-3 row">
                                                <label class="col-lg-4 col-form-label" for="validationCustom06">
                                                    Numero de billets
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control"
                                                        id="validationCustom06" name="numeroBillets"
                                                        value="<?= genererNumeroBillet() ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <div class="col-lg-8 ms-auto">
                                                    <button type="submit" name="save" class="btn btn-primary px-4">Enregistre</button>

                                                    <a href="<?= BASE_URL ?>/admin/Liste_tickets"><button type="button"
                                                            class="btn btn-primary px-4 split-bg-primary ">
                                                            Liste clients
                                                        </button></a>

                                                </div>
                                            </div>
                                        </div>
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
        /* --------- 1) Définir min/max du champ date (au chargement) --------- */
        const dateInput = document.getElementById('jourVoyage');



        (function initDateLimits() {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);

            const toISO = d => d.toISOString().slice(0, 10); // YYYY-MM-DD
            dateInput.min = toISO(today);
            dateInput.max = toISO(tomorrow);

            // Fixer la valeur par défaut à aujourd'hui
            dateInput.value = dateInput.min;
        })();
        // function pour calculer le prix

        // Fonction pour calculer le prix automatiquement
        function calculerPrixTotal() {
            const selectedOption = programmeSelect.options[programmeSelect.selectedIndex];
            const prixUnitaire = parseFloat(selectedOption.dataset.prix || 0);
            const nbPassagers = parseInt(document.getElementById('nombrePassagers').value) || 0;

            const total = prixUnitaire * nbPassagers;

            document.getElementById('prix').value = prixUnitaire;
            document.getElementById('prixTotal').value = isNaN(total) ? '' : `${total.toLocaleString()} FCFA`;
        }

        // Fonction pour afficher ou cacher le champ prix manuel selon escales cochées
        function gererAffichagePrixSelonEscale() {
            const escalesCochees = document.querySelectorAll('input[name="escales[]"]:checked');

            if (escalesCochees.length > 0) {
                prixAutoBox.classList.add('d-none');
                prixManuelBox.classList.remove('d-none');
            } else {
                prixAutoBox.classList.remove('d-none');
                prixManuelBox.classList.add('d-none');
            }
        }

        /* --------- 2) Variables déjà remontées du back-end --------- */
        // destinations = [{ nom, programmes:[{id_programme, heureDepart, escales:[{id,escale_nom}]}] }, ...]
        const destinations = <?= json_encode($destinations) ?>;
        const destinationSelect = document.getElementById('destinationSelect'); // supposé déjà présent
        const programmeSelect = document.getElementById('programmeSelect');
        const escalesList = document.getElementById('escalesList');

        const prixManuelInput = document.getElementById('prixManuel');
        const prixManuelBox = document.getElementById('prixManuelBox');

        // Quand une escale est cochée, on stocke son nom, on masque destinationId ou on laisse intact,
        // et on affiche la boîte pour le prix manuel.
        escalesList.addEventListener('change', () => {
            const checkedEscale = escalesList.querySelector('input[type="radio"]:checked');
            const escaleInput = document.getElementById('escaleSelected');

            if (checkedEscale) {
                escaleInput.value = checkedEscale.value; // met le nom de l'escale sélectionnée
                prixManuelBox.classList.remove('d-none');
                prixManuelInput.value = checkedEscale.dataset.prix || 0; // optionnel : prix automatique
            } else {
                escaleInput.value = '';
                prixManuelBox.classList.add('d-none');
                prixManuelInput.value = '';
            }
        });



        /* --------- 3) Helpers --------- */
        const dateEqToday = sel => sel === (new Date().toISOString().slice(0, 10));
        const hourIsPast = (hstr) => {
            const [h, m] = hstr.split(':').map(Number);
            const now = new Date();
            return (h < now.getHours()) || (h === now.getHours() && m <= now.getMinutes());
        };

        /* --------- 4) Mise à jour du select des heures en fonction :  - destination  - date --------- */
        function updateProgrammeOptions() {
            const destIdx = destinationSelect.value;
            const selectedDate = dateInput.value;
            const isToday = dateEqToday(selectedDate);

            const programmes = (destinations[destIdx] ?? {}).programmes ?? [];

            programmeSelect.innerHTML =
                '<option value="">Sélectionnez une heure</option>' +
                programmes
                .filter(p => !isToday || !hourIsPast(p.heureDepart))
                .map(p =>
                    `<option value="${p.heureDepart}" 
                     data-prix="${p.prix}" 
                     data-escales='${JSON.stringify(p.escales)}'>
                 ${p.heureDepart}
             </option>`
                ).join('');



            /* Si aucune heure disponible pour aujourd’hui => petite alerte utilisateur */
            if (programmeSelect.options.length === 1 && isToday) {
                programmeSelect.innerHTML +=
                    '<option disabled>(Plus aucune heure pour aujourd’hui)</option>';
            }

            escalesList.innerHTML = ''; // reset escales
        }

        destinationSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const destinationId = selectedOption.dataset.id;
            document.getElementById('hiddenDestinationId').value = destinationId;
            updateProgrammeOptions(); // toujours garder ça
        });

        function gererAffichagePrixSelonEscale() {
            const checkedEscales = escalesList.querySelectorAll('input[type="checkbox"]:checked');
            if (checkedEscales.length > 0) {
                const escale = checkedEscales[0];
                document.getElementById('escaleSelected').value = escale.value;

                // Récupérer le prix depuis l'attribut data-prix
                prixManuelInput.value = escale.dataset.prix || '';
                prixManuelBox.classList.remove('d-none');
            } else {
                document.getElementById('escaleSelected').value = '';
                prixManuelInput.value = '';
                prixManuelBox.classList.add('d-none');
            }
        }

        // Attacher l’événement
        escalesList.addEventListener('change', gererAffichagePrixSelonEscale);



        /* --------- 5) Afficher les escales lorsque l’on choisit une heure --------- */
        // Quand on change de programme / heure de départ
        programmeSelect.addEventListener('change', function() {
            const escales = JSON.parse(this.options[this.selectedIndex].dataset.escales || '[]');

            escalesList.innerHTML =
                escales.length ?
                escales.map(e => `
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio"
                    name="escale" id="escale_${e.id}" 
                    value="${e.escale_nom}"
                    data-prix="${e.prix_escale || 0}">
                <label class="form-check-label" for="escale_${e.id}">
                    ${e.escale_nom} - ${e.prix_escale || 0} FCFA
                </label>
            </div>
        `).join('') :
                '<em>Pas d\'escale pour ce trajet.</em>';



            // Re-attacher les événements de changement sur chaque checkbox escale
            document.querySelectorAll('input[name="escales[]"]').forEach(chk => {
                chk.addEventListener('change', gererAffichagePrixSelonEscale);
            });

            calculerPrixTotal();
            gererAffichagePrixSelonEscale(); // au cas où aucune escale n'est listée
        });

        /* --------- 6) Rafraîchir les heures quand  - la destination change  - la date change --------- */
        destinationSelect.addEventListener('change', updateProgrammeOptions);
        dateInput.addEventListener('change', updateProgrammeOptions);

        /* --------- 7) Premier remplissage (si destination déjà choisie) --------- */
        updateProgrammeOptions();


        document.addEventListener('DOMContentLoaded', () => {
            const escalesList = document.getElementById('escalesList');
            const prixInput = document.getElementById('prix');
            const prixTotalInput = document.getElementById('prixTotal');
            const passagersInput = document.getElementById('nombrePassagers');
            const programmeSelect = document.getElementById('programmeSelect');

            let nbPassagers = parseInt(passagersInput.value) || 1;

            // Écoute les changements du nombre de passagers
            passagersInput.addEventListener('input', () => {
                nbPassagers = parseInt(passagersInput.value) || 1;
                calculerPrix();
            });

            // Affiche les escales du programme sélectionné
            function afficherEscales() {
                const selectedOption = programmeSelect.options[programmeSelect.selectedIndex];
                if (!selectedOption) {
                    escalesList.innerHTML = '';
                    return;
                }

                const escales = JSON.parse(selectedOption.dataset.escales || '[]');

                escalesList.innerHTML = escales.length ?
                    escales.map(e => `
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"
                        name="escale" id="escale_${e.id}" 
                        value="${e.escale_nom}"
                        data-prix="${e.prix_escale || 0}">
                    <label class="form-check-label" for="escale_${e.id}">
                        ${e.escale_nom} - ${e.prix_escale || 0} FCFA
                    </label>
                </div>
            `).join('') :
                    '<em>Pas d\'escale pour ce trajet.</em>';
            }

            // Calculer le prix (trajet ou escale) * nombre de passagers
            function calculerPrix() {
                const selectedOption = programmeSelect.options[programmeSelect.selectedIndex];
                if (!selectedOption) return;

                const prixProgramme = parseFloat(selectedOption.dataset.prix || 0);

                // Si une escale est cochée, on prend son prix
                const escaleSelectionnee = escalesList.querySelector('input[type="radio"]:checked');
                const prixUnitaire = escaleSelectionnee ?
                    parseFloat(escaleSelectionnee.dataset.prix || 0) :
                    prixProgramme;

                const total = prixUnitaire * nbPassagers;

                prixInput.value = prixUnitaire || '';
                prixTotalInput.value = total > 0 ? `${total.toLocaleString('fr-FR')} FCFA` : '';
            }

            // Recalcul automatique si on change d’escale
            escalesList.addEventListener('change', calculerPrix);

            // Recalcul automatique si on change de programme
            programmeSelect.addEventListener('change', () => {
                afficherEscales();
                calculerPrix();
            });

            // Premier calcul au chargement
            afficherEscales();
            calculerPrix();
        });
    </script>

</body>

</html>