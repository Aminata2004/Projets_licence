<?php $this->view('admin/partials/headers') ?>

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        <?php  $this->view('admin/partials/navbar') 
        ?>
        <!--end top header-->

        <!--start sidebar -->
        <?php $this->view('admin/partials/sidebar') 
        ?>
        <!--end sidebar -->

        <!--start content-->
        <main class="page-content ">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">G-colis</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Livraison des colis</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/admin/Envoi_colis/liste_colis_envoyer" class="btn btn-primary split-bg-primary text-white"> Voir la liste</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <?php $this->view("admin/set_flash") ?>
            <div class="card">
                <div class="card-body">
                    <?php $this->view('admin/set_flash'); ?>
                    <!-- ========= Formulaire unique ========= -->
                    <form method="post" class="mb-4">
                        <div class="row g-3">
                            <!-- Champ Code -->
                            <div class="col-md-12">
                                <label class="form-label">Numéro de code</label>
                                <input type="text"
                                    name="code"
                                    class="form-control"
                                    placeholder="Numéro du code"
                                    value="<?= htmlspecialchars($_POST['code'] ?? $codeRecherche ?? '') ?>"
                                    required
                                    autocomplete="off">
                            </div>
                            <?php
                            $colis = $colis ?? null;
                            $peutLivrer = $peutLivrer ?? false;
                            $livraisonReussie = $livraisonReussie ?? false;
                            ?>

                            <?php if ($colis): ?>
                                <!-- Champ caché pour permettre la livraison -->
                                <input type="hidden" name="id_colis" value="<?= htmlspecialchars($colis['id_colis']) ?>">


                                <!-- ===== Détails du colis ===== -->
                                <div class="col-12">
                                    <div class="row mt-3">
                                        <!-- Expéditeur -->
                                        <div class="col-md-4 mb-3">
                                            <div class="card shadow-sm h-100">
                                                <h5 class="card-header">Expéditeur</h5>
                                                <div class="card-body">
                                                    <div class="mb-2">
                                                        <label class="form-label">Nom</label>
                                                        <input class="form-control" value="<?= $colis['expediteur'] ?>" readonly>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label">Téléphone</label>
                                                        <input class="form-control" value="<?= $colis['numero_exp'] ?>" readonly>
                                                    </div>
                                                    <label class="form-label">Email</label>
                                                    <input class="form-control" value="<?= $colis['email_exp'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Destinataire -->
                                        <div class="col-md-4 mb-3">
                                            <div class="card shadow-sm h-100">
                                                <h5 class="card-header">Destinataire</h5>
                                                <div class="card-body">
                                                    <div class="mb-2">
                                                        <label class="form-label">Nom</label>
                                                        <input class="form-control" value="<?= $colis['destinataire'] ?>" readonly>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label">Téléphone</label>
                                                        <input class="form-control" value="<?= $colis['numero_dest'] ?>" readonly>
                                                    </div>
                                                    <label class="form-label">Email</label>
                                                    <input class="form-control" value="<?= $colis['email_dest'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Colis -->
                                        <div class="col-md-4 mb-3">
                                            <div class="card shadow-sm h-100">
                                                <h5 class="card-header">Colis</h5>
                                                <div class="card-body">
                                                    <div class="mb-2">
                                                        <label class="form-label">Nom</label>
                                                        <input class="form-control" value="<?= $colis['nom_colis'] ?>" readonly>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label">Nature</label>
                                                        <input class="form-control" value="<?= $colis['nature'] ?>" readonly>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label class="form-label">Destination</label>
                                                            <input class="form-control" value="<?= $colis['localite'] ?>" readonly>
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label">Valeur</label>
                                                            <input class="form-control" value="<?= $colis['valeur'] ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <label class="form-label">Frais transaction</label>
                                                            <input class="form-control" value="<?= $colis['fraix_transaction'] ?>" readonly>
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label">Statut</label>
                                                            <input class="form-control" value="<?= $colis['status'] ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- /row -->
                                </div>
                            <?php endif; ?>

                            <!-- ===== Bouton dynamique ===== -->
                            <?php if ($livraisonReussie && $colis): ?>
                                <div class="col-12 mt-3">
                                    <div class="alert alert-success d-flex flex-wrap align-items-center justify-content-between gap-2 mb-0">
                                        <span><i class="bx bx-check-circle me-1"></i> Colis livré avec succès !</span>
                                        <?php
                                        $msgLivraison = "Bonjour " . ($colis['expediteur'] ?? '') . ", votre colis (code "
                                            . ($colis['code_colis'] ?? '') . ") a bien été remis à son destinataire. Merci de votre confiance.";
                                        $lienWhatsappLivraison = whatsapp_link($colis['whatsapp_exp'] ?? $colis['numero_exp'] ?? '', $msgLivraison);
                                        ?>
                                        <?php if ($lienWhatsappLivraison): ?>
                                            <a href="<?= htmlspecialchars($lienWhatsappLivraison) ?>" target="_blank" rel="noopener" class="btn btn-success">
                                                <i class="bx bxl-whatsapp me-1"></i> Confirmer la remise à l'expéditeur par WhatsApp
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-12 mt-3">
                                    <button type="submit"
                                        class="btn <?= $colis ? ($peutLivrer ? 'btn-success' : 'btn-secondary') : 'btn-primary' ?>"
                                        name="<?= $colis ? ($peutLivrer ? 'livrer' : 'envoi') : 'envoi' ?>"
                                        <?= $colis && !$peutLivrer ? 'disabled' : '' ?>>
                                        <?= $colis ? ($peutLivrer ? 'Valider la livraison' : 'Livraison impossible') : 'Valider' ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div> <!-- /row -->
                    </form>


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