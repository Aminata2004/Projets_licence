<?php $this->view('admin/partials/header') ?>

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
                            <li class="breadcrumb-item active" aria-current="page">Demandes de partenariat</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <?php $this->view("admin/set_flash") ?>

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Compagnies en discussion pour un partenariat</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Compagnie</th>
                                    <th>Contact</th>
                                    <th>Dernier message</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($partenaires)): foreach ($partenaires as $p): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($p->nom_compagnie) ?></td>
                                        <td>
                                            <?= htmlspecialchars($p->email) ?><br>
                                            <?php if (!empty($p->telephone)): ?>
                                                <small class="text-muted"><?= htmlspecialchars($p->telephone) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td style="max-width:280px; white-space:pre-wrap;">
                                            <?= $p->dernier_message ? htmlspecialchars($p->dernier_message) : '—' ?>
                                        </td>
                                        <td><?= $p->date_dernier_message ? date('d/m/Y H:i', strtotime($p->date_dernier_message)) : '—' ?></td>
                                        <td class="text-center">
                                            <?php if ($p->dernier_auteur === 'partenaire'): ?>
                                                <span class="badge bg-warning text-dark">En attente de réponse</span>
                                            <?php elseif ($p->dernier_auteur === 'admin'): ?>
                                                <span class="badge bg-success">Répondu</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Aucun message</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= BASE_URL ?>/admin/Partenariats/discussion/<?= $p->id_partenaire ?>" class="btn btn-primary btn-sm">
                                                <i class="bx bx-message-dots"></i> Discuter
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Aucune compagnie n'a encore créé de compte partenaire.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <!--end page main-->

        <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
        <!--end overlay-->
    </div>
    <!--end wrapper-->

    <?php $this->view('admin/partials/foot') ?>
</body>
</html>
