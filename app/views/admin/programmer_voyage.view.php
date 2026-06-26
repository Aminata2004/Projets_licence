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
                                                                data-iddepart="<?= $listeProgrammers->departLocalite ?>"
                                                                data-iddestination="<?= $listeProgrammers->destinationLocalite ?>"
                                                                href="#">
                                                                <i class="bi bi-pencil-square text-primary me-2"></i>Modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#">
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
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel1">Modifier le prix du trajet :
                        <?= $listeProgrammers->idDepart . '     ' . $listeProgrammers->idDestination ?></h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close" style="color: white;"></button>
                </div>
                <form action="<?= BASE_URL ?>/Programmer_voyages/edit" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Prix</label>
                                <input type="text" class="form-control" value="" name="prix" id="nameprix">
                            </div>
                            <input type="hidden" name="idProgrammer" id="nameidtrajet">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="edit">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end row-->

</body>
<script>
    $(document).ready(function() {
        // Lorsque le bouton "Ajouter" est cliqué
        $('.add-button').click(function() {
            // Récupérer les attributs de données du lien cliqué
            var idtrajet = $(this).data('id');
            var prix = $(this).data('prix');

            // Remplir le champ du modal avec les données
            $('#nameidtrajet').val(idtrajet);
            $('#nameprix').val(prix);

            // Afficher le modal
            $('#basicModale').modal('show');
        });
    });
</script>

</html>