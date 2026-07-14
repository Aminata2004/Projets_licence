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
                            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/Partenariats"><i class="bx bx-arrow-back"></i> Demandes de partenariat</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($partenaire->nom_compagnie) ?></li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= htmlspecialchars($partenaire->nom_compagnie) ?></h5>
                    <small><?= htmlspecialchars($partenaire->email) ?><?= !empty($partenaire->telephone) ? ' • ' . htmlspecialchars($partenaire->telephone) : '' ?></small>
                </div>
                <div class="card-body">
                    <div id="discussionMessages" style="height: 55vh; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; padding: 10px;">
                        <?php if (empty($messages)): ?>
                            <p class="text-muted text-center my-auto">Aucun message pour le moment.</p>
                        <?php else: ?>
                            <?php foreach ($messages as $m): ?>
                                <?php $estAdmin = $m->auteur === 'admin'; ?>
                                <div style="max-width:70%; padding:12px 16px; border-radius:12px; font-size:0.9rem; <?= $estAdmin ? 'align-self:flex-end; background:#0f3b5e; color:white; border-bottom-right-radius:2px;' : 'align-self:flex-start; background:#ecf0f1; color:#2c3e50; border-bottom-left-radius:2px;' ?>">
                                    <?= nl2br(htmlspecialchars($m->message)) ?>
                                    <div style="font-size:0.7rem; opacity:0.7; margin-top:6px;"><?= date('d/m/Y H:i', strtotime($m->date_envoi)) ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <form method="post" class="d-flex gap-2 mt-3 pt-3 border-top">
                        <textarea name="message" class="form-control" rows="2" placeholder="Écrire une réponse..." required></textarea>
                        <button type="submit" name="envoyer_message" class="btn btn-primary align-self-end">
                            <i class="bx bx-send"></i> Envoyer
                        </button>
                    </form>
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
    <script>
        const box = document.getElementById('discussionMessages');
        box.scrollTop = box.scrollHeight;
    </script>
</body>
</html>
