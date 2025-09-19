<?php $this->view('admin/partials/headers') ?>
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
        <main class="page-content ">
            <!--breadcrumb-->
            <ticketsdiv class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Caisse</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Gestion des caisses </li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <?php if ($user->userHasPermission('Caisse_creation')) {?>
                            <a href="<?= BASE_URL ?>/admin/Caisse/add_caisse" class="btn btn-primary split-bg-primary text-white"> + Ajouter</a>
                            <?php }?> &nbsp;
                            <a href="javascript:history.back()" class="btn btn-primary "><i
                                    class="fadeIn animated bx bx-left-arrow-alt"></i></a>

                    </div>
                </div>
            </ticketsdiv>

            <div class="card">
                <div class="card-body">


                    <div class="tab-content py-3 table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Reference caisse</th>
                                    <th>Date d'enregistrement</th>
                                    <th>Agence</th>
                                    <th>Montant initials</th>
                                    <th>Montant du colis</th>
                                    <th>Montant de billets</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($liste_caisse as $caisse): ?>
                                    <?php
                                    $droit        = $_SESSION['droit'];
                                    $id_compagnie = $_SESSION['id_compagnie'];
                                    $ville        = $_SESSION['ville'];

                                    // Admin → seulement caisses de sa compagnie
                                    if ($droit === 'Admin' && $caisse->id_compagnie != $id_compagnie) continue;

                                    // Admin régional → seulement caisses de sa compagnie ET de sa ville
                                    if ($droit === 'Admin_regionale' && ($caisse->id_compagnie != $id_compagnie || $caisse->localite != $ville)) continue;

                                    // Super admin → aucune restriction
                                    ?>

                                    <!-- Ligne de la table : rouge si désactivée -->
                                    <tr class="text-center <?= $caisse->status_caisse == 0 ? 'table-danger' : '' ?>">
                                        <td><?= htmlspecialchars($caisse->reference_caise); ?></td>
                                        <td><?= htmlspecialchars($caisse->date_enregistrement); ?></td>
                                        <td><?= htmlspecialchars($caisse->localite . ' (' . $caisse->numeroGare . ' )'); ?></td>
                                        <td><?= htmlspecialchars(number_format($caisse->montant_initial, 2, ',', ' ')); ?> FCFA</td>
                                        <td><?= htmlspecialchars(number_format($caisse->montant_colis, 2, ',', ' ')); ?> FCFA</td>
                                        <td><?= htmlspecialchars(number_format($caisse->montant_billets, 2, ',', ' ')); ?> FCFA</td>
                                        <td>
                                            <!-- Modifier -->
                                            <a href="#" class="btn btn-sm btn-primary">
                                                <i class="fadeIn animated bx bx-edit-alt"></i>
                                            </a>

                                            <!-- Activer / Désactiver -->
                                            <?php if ($caisse->status_caisse == 1): ?>
                                                <a href="#" class="btn btn-sm btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#animationModal<?= $caisse->id_caisse ?>">
                                                    <i class="fadeIn animated bx bx-check-circle"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="#" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#animationModal<?= $caisse->id_caisse ?>">
                                                    <i class="fadeIn animated bx bx-block"></i>
                                                </a>
                                            <?php endif; ?>

                                        </td>
                                    </tr>

                                    <!-- Modal Activation/Désactivation -->
                                    <div class="modal fade animate__animated animate__slideInDown"
                                        id="animationModal<?= $caisse->id_caisse ?>"
                                        tabindex="-1"
                                        role="dialog"
                                        aria-labelledby="exampleModalCenterTitle"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="<?= BASE_URL ?>/admin/Caisse" method="post">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold text-primary" id="exampleModalCenterTitle">
                                                            Confirmation de <?= $caisse->status_caisse == 1 ? 'désactivation' : 'réactivation' ?>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <i class="nav-icon fa fa-exclamation-triangle text-danger" style="font-size: 60px;"></i>
                                                        <p class="mt-3">
                                                            Voulez-vous vraiment
                                                            <strong class="text-danger">
                                                                <?= $caisse->status_caisse == 1 ? 'désactiver' : 'activer' ?>
                                                            </strong>
                                                            la caisse <br>
                                                            <strong><?= htmlspecialchars($caisse->localite . ' (' . $caisse->numeroGare . ')') ?></strong> ?
                                                        </p>

                                                        <input type="hidden" name="id_caisse" value="<?= $caisse->id_caisse ?>">
                                                        <input type="hidden" name="newStatut" value="<?= $caisse->status_caisse == 1 ? 0 : 1 ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" name="valider" class="btn btn-primary">
                                                            Oui <?= $caisse->status_caisse == 1 ? 'Désactiver' : 'Activer' ?>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>



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

</body>

</html>