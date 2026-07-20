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
                <div class="breadcrumb-title pe-3">G-colis</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Reclamation de colis</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="<?= BASE_URL ?>/Envoi_colis/liste_colis_envoyer" class="btn btn-primary split-bg-primary text-white"> Voir la liste</a> &nbsp;
                        <a href="javascript:history.back()" class="btn btn-primary "><i
                                class="fadeIn animated bx bx-left-arrow-alt"></i></a>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <?php $this->view("admin/set_flash") ?>
            
            <div class="row">
                <!-- Zone de recherche et détails -->
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-bold"><i class="bx bx-search text-primary me-2"></i>Rechercher un colis</h6>
                        </div>
                        <div class="card-body">
                            <form action="<?= BASE_URL ?>/admin/Reclamations" method="post" class="mb-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Entrez le code du colis (ex: C-12345)" name="code" required />
                                    <button class="btn btn-primary" type="submit" name="envoi">Chercher</button>
                                </div>
                            </form>

                            <?php if (isset($colisData) && $colisData): ?>
                                <div class="bg-light p-3 rounded-3 border">
                                    <h6 class="fw-bold text-primary mb-3">Détails du colis trouvé</h6>
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                                            <span class="text-muted">Code:</span>
                                            <span class="fw-bold"><?= htmlspecialchars($colisData->code_colis) ?></span>
                                        </li>
                                        <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                                            <span class="text-muted">Nature:</span>
                                            <span class="fw-bold"><?= htmlspecialchars($colisData->nature) ?></span>
                                        </li>
                                        <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                                            <span class="text-muted">Valeur déclarée:</span>
                                            <span class="fw-bold"><?= number_format($colisData->valeur, 0, ',', ' ') ?> FCFA</span>
                                        </li>
                                        <li class="list-group-item bg-transparent px-0">
                                            <span class="text-muted d-block mb-1">Expéditeur:</span>
                                            <span class="fw-bold d-block"><i class="bx bx-user me-1"></i><?= htmlspecialchars($colisData->expediteur_nom) ?> (<?= htmlspecialchars($colisData->expediteur_tel) ?>)</span>
                                        </li>
                                    </ul>

                                    <?php if ($colisData->reclamer == 1): ?>
                                        <div class="alert alert-warning mb-0">
                                            <i class="bx bx-info-circle"></i> Ce colis fait déjà l'objet d'une réclamation.
                                        </div>
                                    <?php else: ?>
                                        <form action="<?= BASE_URL ?>/admin/Reclamations" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id_colis" value="<?= $colisData->id_colis ?>">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Motif de la réclamation <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="motif_reclamation" rows="3" placeholder="Ex: Colis endommagé pendant le transport..." required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Montant à rembourser (FCFA)</label>
                                                <input type="number" class="form-control" name="montant_remboursement" min="0" value="<?= $colisData->valeur ?>">
                                                <small class="text-muted">Par défaut: valeur déclarée du colis</small>
                                            </div>
                                            <button type="submit" name="submit_reclamation" class="btn btn-danger w-100 fw-bold">Soumettre la réclamation</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Liste des réclamations -->
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-bold"><i class="bx bx-list-ul text-primary me-2"></i>Réclamations en cours</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Code Colis</th>
                                            <th>Motif</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($liste_reclamations)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">Aucune réclamation trouvée.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($liste_reclamations as $rec): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($rec->date_reclamer)) ?></td>
                                                    <td class="fw-bold text-primary"><?= htmlspecialchars($rec->code_colis) ?></td>
                                                    <td><small><?= htmlspecialchars(substr($rec->motif_reclamation, 0, 30)) ?>...</small></td>
                                                    <td class="fw-bold"><?= number_format($rec->montant_remboursement, 0, ',', ' ') ?></td>
                                                    <td>
                                                        <?php 
                                                            $badge = 'bg-warning';
                                                            if($rec->status_reclamation == 'Remboursé') $badge = 'bg-success';
                                                            if($rec->status_reclamation == 'Rejeté') $badge = 'bg-danger';
                                                        ?>
                                                        <span class="badge <?= $badge ?>"><?= htmlspecialchars($rec->status_reclamation ?? 'En attente') ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if(isset($_SESSION['droit']) && in_array($_SESSION['droit'], ['Admin', 'chef_d_escale'])): ?>
                                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#statusModal<?= $rec->id_colis ?>">Gérer</button>
                                                            
                                                            <!-- Modal Gestion Statut -->
                                                            <div class="modal fade" id="statusModal<?= $rec->id_colis ?>" tabindex="-1" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Gérer la réclamation</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p><strong>Colis:</strong> <?= htmlspecialchars($rec->code_colis) ?></p>
                                                                            <p><strong>Motif complet:</strong><br><?= nl2br(htmlspecialchars($rec->motif_reclamation)) ?></p>
                                                                            <form action="<?= BASE_URL ?>/admin/Reclamations" method="post">
                                                                                <?= csrf_field() ?>
                                                                                <input type="hidden" name="id_colis_status" value="<?= $rec->id_colis ?>">
                                                                                <div class="mb-3">
                                                                                <label class="form-label">Changer le statut</label>
                                                                                <select name="status_reclamation" class="form-select status-select" data-id="<?= $rec->id_colis ?>">
                                                                                    <option value="En attente" <?= $rec->status_reclamation == 'En attente' ? 'selected' : '' ?>>En attente</option>
                                                                                    <option value="Remboursé" <?= $rec->status_reclamation == 'Remboursé' ? 'selected' : '' ?>>Remboursé</option>
                                                                                    <option value="Rejeté" <?= $rec->status_reclamation == 'Rejeté' ? 'selected' : '' ?>>Rejeté</option>
                                                                                </select>
                                                                            </div>
                                                                            
                                                                            <?php if(isset($_SESSION['droit']) && $_SESSION['droit'] === 'Admin'): ?>
                                                                            <div class="mb-3 admin-caisse-div" id="admin-caisse-<?= $rec->id_colis ?>" style="<?= $rec->status_reclamation == 'Remboursé' ? '' : 'display:none;' ?>">
                                                                                <label class="form-label text-danger fw-bold"><i class="bx bx-wallet"></i> Débiter quelle caisse ouverte ?</label>
                                                                                <select name="admin_id_caisse" class="form-select border-danger">
                                                                                    <option value="">-- Choisissez la caisse à débiter --</option>
                                                                                    <?php
                                                                                    // Filtrer : uniquement caisse départ ou destination de CE colis
                                                                                    $caissesFiltered = array_filter($caisses_ouvertes ?? [], function($c) use ($rec) {
                                                                                        return $c->localite === $rec->provient_de || $c->localite === $rec->destination;
                                                                                    });
                                                                                    ?>
                                                                                    <?php if(!empty($caissesFiltered)): ?>
                                                                                        <?php foreach($caissesFiltered as $c):
                                                                                            $total = ($c->montant_billets ?? 0) + ($c->montant_colis ?? 0) - ($c->montant_rembourse ?? 0) - ($c->montant_depense ?? 0);
                                                                                            $label = ($c->localite === $rec->provient_de) ? 'Départ' : 'Destination';
                                                                                        ?>
                                                                                        <option value="<?= $c->id_caisse ?>">
                                                                                            Gare <?= htmlspecialchars($c->localite) ?> (<?= $label ?>) — Solde: <?= number_format($total, 0, ',', ' ') ?> F
                                                                                        </option>
                                                                                        <?php endforeach; ?>
                                                                                    <?php else: ?>
                                                                                        <option value="">⚠️ Aucune caisse ouverte pour la gare de départ ou de destination.</option>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                                <small class="text-muted">Seules les caisses de la gare de départ et de destination sont proposées.</small>
                                                                            </div>
                                                                            <?php endif; ?>
                                                                            
                                                                            <button type="submit" name="update_status" class="btn btn-primary w-100">Mettre à jour</button>
                                                                        </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-muted small"><i class="bx bx-lock-alt"></i> Non autorisé</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
        // Afficher/Cacher la sélection de caisse pour l'Admin quand "Remboursé" est sélectionné
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let adminDiv = document.getElementById('admin-caisse-' + id);
                if(adminDiv) {
                    adminDiv.style.display = (this.value === 'Remboursé') ? 'block' : 'none';
                }
            });
        });
    </script>
</body>

</html>