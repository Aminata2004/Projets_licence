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
          
            <div class="page-breadcrumb d-flex align-items-center justify-content-between mb-3">
                <!-- Titre + Fil d'Ariane -->
                <div class="d-flex align-items-center">
                    <div class="breadcrumb-title pe-3  ">
                        <i class="bx bx-calendar-check me-1"></i> G-Programme
                    </div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0 small">
                                <li class="breadcrumb-item">
                                    <a href="<?= BASE_URL ?>/admin/dashboard" class="text-decoration-none text-muted">
                                        <i class="bx bx-home-alt"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active " aria-current="page">Programmes du voyage</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="btn-group gap-1">
                    <a href="<?= BASE_URL ?>/admin/Programmer_voyages/add_programmer"
                        class="btn btn-sm btn-success shadow-sm">
                        <i class="bx bx-plus-circle me-1"></i> Ajouter
                    </a>

                    <a href="javascript:history.back()"
                        class="btn btn-sm btn-outline-primary shadow-sm">
                        <i class="bx bx-left-arrow-alt"></i> Retour
                    </a>
                </div>
            </div>

            <?php $this->view("admin/set_flash") ?>
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bx bx-calendar-check me-1"></i> Liste des programmes de voyage
                </div>
                <div class="card-body">
                    <div class="table-responsive shadow-sm rounded-3">
                        <table id="example"
                            class="table table-striped table-hover align-middle table-bordered mb-0">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Départ</th>
                                    <th>Destination</th>
                                    <th>RDV</th>
                                    <th>Heure de départ</th>
                                    <th>Prix</th>
                                    <th>Escale(s)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php foreach ($listeProgrammer as $listeProgrammers): ?>
                                    <?php if ($_SESSION['droit'] === 'Admin' || $listeProgrammers->departLocalite === $_SESSION['ville']): ?>
                                        <tr>
                                            <td><?= $listeProgrammers->departLocalite .' ( ' . $listeProgrammers->numeroGare1 . ' ) ' ?></td>
                                            <td><?= $listeProgrammers->destinationLocalite  .' ( ' . $listeProgrammers->numeroGare2 . ' ) ' ?></td>
                                            <td><?= $listeProgrammers->rdv ?></td>
                                            <td><?= $listeProgrammers->heureDepart ?></td>
                                            <td><strong class="text-danger"><?= number_format($listeProgrammers->prix, 0, ',', ' ') ?> FCFA</strong></td>
                                            <td>
                                                <?= !empty($listeProgrammers->escales)
                                                    ? '<span class="text-dark">' . htmlspecialchars($listeProgrammers->escales) . '</span>'
                                                    : '<span class="text-muted fst-italic">Aucune escale</span>' ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light border-0 shadow-sm" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                                        <li>
                                                            <a class="dropdown-item add-button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#basicModale"
                                                                data-id="<?= $listeProgrammers->idProgrammer  ?>"
                                                                data-prix="<?= $listeProgrammers->prix   ?>"
                                                                data-heuredepart="<?= htmlspecialchars($listeProgrammers->heureDepart) ?>"
                                                                data-rdv="<?= htmlspecialchars($listeProgrammers->rdv) ?>"
                                                                data-iddepart="<?= htmlspecialchars($listeProgrammers->departLocalite) ?>"
                                                                data-iddestination="<?= htmlspecialchars($listeProgrammers->destinationLocalite) ?>"
                                                                data-escales="<?= htmlspecialchars(json_encode($listeProgrammers->escalesDetails), ENT_QUOTES) ?>"
                                                                href="#">
                                                                <i class="bi bi-pencil-square text-primary me-2"></i>Modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger delete-button"
                                                                href="<?= BASE_URL ?>/admin/Programmer_voyages/delete/<?= $listeProgrammers->idProgrammer ?>">
                                                                <i class="bi bi-trash3 me-2"></i>Supprimer
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif ?>
                                <?php endforeach ?>
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
    <!-- Modal  pour Ajout-->
    <div class="modal fade" id="basicModale" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel1">
                        <i class="bi bi-pencil-square me-1"></i> Modifier le trajet :
                        <span id="modalTrajetRoute"></span>
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close" style="color: white;"></button>
                </div>
                <form action="<?= BASE_URL ?>/admin/Programmer_voyages/edit" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-clock me-1"></i>Heure de départ</label>
                                <select class="form-select" name="heureDepart" id="nameheuredepart" onchange="calculerRDVModifier()" required>
                                    <?php foreach ($listehoraire as $horaire): ?>
                                        <option value="<?= htmlspecialchars($horaire->heuredepart) ?>">
                                            <?= htmlspecialchars($horaire->heuredepart) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-alarm me-1"></i>RDV</label>
                                <input type="time" class="form-control" name="rdv" id="namerdv" required>
                            </div>
                            <div class="col mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-cash-coin me-1"></i>Prix du trajet</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" value="" name="prix" id="nameprix" required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>
                            <input type="hidden" name="idProgrammer" id="nameidtrajet">
                        </div>
                        <div id="escalePricesContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" name="edit"><i class="bi bi-check-circle me-1"></i>Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end row-->

</body>
<script>
    // Recalcule automatiquement le RDV (heure de départ - 45 min) quand l'heure de départ change
    function calculerRDVModifier() {
        let heureDepart = document.getElementById("nameheuredepart").value;
        let rdvInput = document.getElementById("namerdv");

        if (heureDepart) {
            let [heures, minutes] = heureDepart.split(':').map(Number);

            minutes -= 45;
            if (minutes < 0) {
                minutes += 60;
                heures -= 1;
            }
            if (heures < 0) {
                heures += 24;
            }

            let heureRDV = (heures < 10 ? "0" : "") + heures + ":" + (minutes < 10 ? "0" : "") + minutes;
            rdvInput.value = heureRDV;
        }
    }

    $(document).ready(function() {
        // Lorsque le bouton "Modifier" est cliqué
        $('.add-button').click(function() {
            // Récupérer les attributs de données du lien cliqué
            var idtrajet = $(this).data('id');
            var prix = $(this).data('prix');
            var heuredepart = $(this).data('heuredepart');
            var rdv = $(this).data('rdv');
            var iddepart = $(this).data('iddepart');
            var iddestination = $(this).data('iddestination');
            var escales = $(this).data('escales'); // tableau d'objets {id_escale, escales, prix_escale}

            // Remplir le champ du modal avec les données
            $('#nameidtrajet').val(idtrajet);
            $('#nameprix').val(prix);
            $('#nameheuredepart').val(heuredepart);
            $('#namerdv').val(rdv);
            $('#modalTrajetRoute').text(iddepart + ' → ' + iddestination);

            // Générer les champs de prix pour chaque escale du trajet
            var container = $('#escalePricesContainer');
            container.empty();
            if (Array.isArray(escales) && escales.length > 0) {
                container.append('<label class="form-label fw-semibold mt-2"><i class="bi bi-signpost-2 me-1"></i>Frais des escales</label>');
                escales.forEach(function(escale) {
                    var group = $('<div class="input-group mb-2"></div>');
                    group.append($('<span class="input-group-text"></span>').text(escale.escales));
                    group.append($('<input type="number" class="form-control">').attr('name', 'prix_escale[' + escale.id_escale + ']').val(escale.prix_escale ?? 0));
                    group.append('<span class="input-group-text">FCFA</span>');
                    container.append(group);
                });
            }

            // Afficher le modal
            $('#basicModale').modal('show');
        });

        // Suppression avec SweetAlert
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const deleteUrl = this.getAttribute('href');

                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Ce programme et ses éventuelles escales/affectations de car seront également supprimés. Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>

</html>