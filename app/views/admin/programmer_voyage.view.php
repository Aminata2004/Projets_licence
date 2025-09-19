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
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-programmer</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Programer du voyage</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/admin/Programmer_voyages/add_programmer" class="btn btn-info text-white"> + Ajouter</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <?php $this->view("admin/set_flash") ?>
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Depart</th>
                                    <th>Destination</th>
                                  
                                    <th>RDV</th>
                                    <th>Heure de depart</th>
                                    <th>Prix </th>
                                    <th>Escale(s)</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listeProgrammer as $listeProgrammers): ?>
                                    <?php if ($listeProgrammers->idDepart === $_SESSION['ville']): ?>
                                        <tr>
                                            <td><?= $listeProgrammers->idDepart ?></td>
                                            <td><?= $listeProgrammers->idDestination ?></td>

                                            <td><?= $listeProgrammers->rdv ?></td>
                                            <td><?= $listeProgrammers->heureDepart ?></td>
                                            <td><?= $listeProgrammers->prix ?></td>
                                            <td>
                                                <?= !empty($listeProgrammers->escales)
                                                    ? htmlspecialchars($listeProgrammers->escales)
                                                    : '<span class="text-muted">Aucune escale</span>' ?>
                                            </td>

                                            <td>
                                                <div class="dropdown ms-auto ">
                                                    <div class="btn-link" data-bs-toggle="dropdown">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                            version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                                fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                                <circle fill="#000000" cx="5" cy="12" r="2">
                                                                </circle>
                                                                <circle fill="#000000" cx="12" cy="12" r="2">
                                                                </circle>
                                                                <circle fill="#000000" cx="19" cy="12" r="2">
                                                                </circle>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item add-button"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#basicModale"
                                                            data-id="<?= $listeProgrammers->idProgrammer  ?>"
                                                            data-prix="<?= $listeProgrammers->prix   ?>"
                                                            data-iddepart="<?= $listeProgrammers->departLocalite ?>"
                                                            data-iddestination="<?= $listeProgrammers->destinationLocalite ?>"
                                                            href="">Modifer</a>
                                                        <a class="dropdown-item"
                                                            href="">Supprimer</a>
                                                    </div>
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