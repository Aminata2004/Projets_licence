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
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-reservation</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Liste des tickets </li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/admin/Add_billets" class="btn btn-primary split-bg-primary text-white"> + Ajouter</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-body border-top border-primary border-1">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" href="<?= BASE_URL ?>/admin/Liste_tickets" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-list-check font-20"></i> <!-- Liste avec coches -->
                                    </div>
                                    <div class="tab-title">Liste actuelle</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/Liste_du_jours" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-calendar-event font-19"></i> <!-- Icône calendrier -->
                                    </div>
                                    <div class="tab-title">Liste du jour</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/Liste_de_demains" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-time-five font-19"></i> <!-- Icône horloge -->
                                    </div>
                                    <div class="tab-title">Liste de demain</div>
                                </div>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <div class="tab-content py-3 table-responsive">
                        <table id="example" class="table table-striped table-bordered mobile-card-table" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Client</th>
                                    <th>Destionation</th>
                                    <th>Nbr de place</th>
                                    <th>heure de depart</th>
                                    <th>Jour de voyage</th>
                                    <th>Date d'expiration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Affiche uniquement les billets du jour dont l'heure de départ correspond
                                // au créneau "en cours" (calculé côté contrôleur à partir des heures réellement
                                // programmées par la compagnie, cf. Liste_du_jour::getHeureDepartCourante()).
                                foreach ($listeClients as $listeClient) {
                                    if ($listeClient->jourVoyage == date("Y-m-d") && $heureCourante !== null && $listeClient->Heur_departs == $heureCourante) {
                                ?>
                                        <tr class="text-center">
                                            <td data-label="Client"><?= $listeClient->Client ?></td>
                                            <td data-label="Destionation"><?= $listeClient->destinationId ?></td>
                                            <td data-label="Nbr de place"><?= $listeClient->nombrePassages ?></td>
                                            <td data-label="Heure de depart"><?= $listeClient->Heur_departs ?></td>
                                            <td data-label="Jour de voyage"><?= $listeClient->jourVoyage ?></td>
                                            <td data-label="Date d'expiration"><?= $listeClient->date_expiration ?></td>
                                            <td data-label="Action">
                                                <i class="bx bx-edit text-primary fs-4 cursor-pointer edit-billet-btn"
                                                    title="Modifier"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalModificationBillet"
                                                    data-idbillets="<?= htmlspecialchars($listeClient->idBillets, ENT_QUOTES) ?>"
                                                    data-idclient="<?= htmlspecialchars($listeClient->id_client, ENT_QUOTES) ?>"
                                                    data-client="<?= htmlspecialchars($listeClient->Client, ENT_QUOTES) ?>"
                                                    data-expiration="<?= htmlspecialchars($listeClient->date_expiration, ENT_QUOTES) ?>">
                                                </i>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>

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


    <!-- modal pour la modification du billet -->
    <div class="modal fade" id="modalModificationBillet" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Modifier le billet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
                </div>
                <form method="post" action="<?= BASE_URL ?>/admin/Liste_tickets/edit">
                    <div class="modal-body">
                        <input type="hidden" name="idBillets" id="edit_idBillets">
                        <input type="hidden" name="id_client" id="edit_id_client">
                        <div class="mb-3">
                            <label for="edit_Client" class="form-label">Nom du client<span class="text-danger ms-2">*</span></label>
                            <input type="text" class="form-control" id="edit_Client" name="Client" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_date_expiration" class="form-label">Date d'expiration<span class="text-danger ms-2">*</span></label>
                            <input type="date" class="form-control" id="edit_date_expiration" name="date_expiration" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" name="edit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- fin modal modification -->

    <?php $this->view('admin/partials/foot') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-billet-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    document.getElementById('edit_idBillets').value = this.dataset.idbillets;
                    document.getElementById('edit_id_client').value = this.dataset.idclient;
                    document.getElementById('edit_Client').value = this.dataset.client;
                    document.getElementById('edit_date_expiration').value = this.dataset.expiration;
                });
            });
        });
    </script>

</body>

</html>