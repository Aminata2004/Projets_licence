<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Espace partenaire - TransGest</title>
    <link href="<?= BASE_URL ?>/assets_site/css/inter.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets_site/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f4f6fa; color: #1a1f2e; line-height: 1.5; }
        :root {
            --primary: #0f3b5e; --primary-dark: #0a2a44; --secondary: #e67e22;
            --gray-light: #ecf0f1; --gray: #7f8c8d; --dark: #2c3e50;
            --shadow: 0 2px 10px rgba(0,0,0,0.05); --shadow-md: 0 5px 20px rgba(0,0,0,0.08); --radius: 8px; --radius-lg: 12px;
        }
        .container { max-width: 900px; margin: 0 auto; padding: 0 24px; }
        section { padding: 40px 0; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 28px; border-radius: var(--radius); font-weight: 600; font-size: 0.9rem; cursor: pointer; border: none; text-decoration: none; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-outline { background: transparent; border: 1px solid var(--primary); color: var(--primary); }

        .discussion-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .discussion-card { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); display: flex; flex-direction: column; height: 60vh; }
        .discussion-messages { flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 14px; }
        .msg { max-width: 70%; padding: 12px 16px; border-radius: var(--radius-lg); font-size: 0.9rem; line-height: 1.5; }
        .msg-partenaire { align-self: flex-end; background: var(--primary); color: white; border-bottom-right-radius: 2px; }
        .msg-admin { align-self: flex-start; background: var(--gray-light); color: var(--dark); border-bottom-left-radius: 2px; }
        .msg-meta { font-size: 0.7rem; opacity: 0.7; margin-top: 6px; }
        .discussion-empty { text-align: center; color: var(--gray); margin: auto; }
        .discussion-form { display: flex; gap: 12px; padding: 16px 24px; border-top: 1px solid #eee; }
        .discussion-form textarea { flex: 1; resize: none; border: 1px solid #ddd; border-radius: var(--radius); padding: 10px 14px; font-family: inherit; font-size: 0.9rem; }
        .discussion-form textarea:focus { outline: none; border-color: var(--secondary); }
    </style>
</head>
<body>

<?php $this->view('site/partials/nav') ?>

<section>
    <div class="container">
        <div class="discussion-header">
            <div>
                <h2 style="margin-bottom:4px;">Espace partenaire</h2>
                <p style="color: var(--gray); font-size: 0.85rem;">Bonjour <?= htmlspecialchars($_SESSION['partenaire_nom']) ?>, discutez avec notre équipe ici.</p>
            </div>
            <a href="<?= BASE_URL ?>/site/EspacePartenaire/deconnexion" class="btn btn-outline"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>

        <div class="discussion-card">
            <div class="discussion-messages" id="discussionMessages">
                <?php if (empty($messages)): ?>
                    <p class="discussion-empty">Envoyez votre premier message pour démarrer la discussion avec notre équipe.</p>
                <?php else: ?>
                    <?php foreach ($messages as $m): ?>
                        <div class="msg <?= $m->auteur === 'partenaire' ? 'msg-partenaire' : 'msg-admin' ?>">
                            <?= nl2br(htmlspecialchars($m->message)) ?>
                            <div class="msg-meta"><?= date('d/m/Y H:i', strtotime($m->date_envoi)) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <form method="POST" class="discussion-form">
                <textarea name="message" rows="2" placeholder="Écrire un message..." required></textarea>
                <button type="submit" name="envoyer_message" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</section>

<script>
    const box = document.getElementById('discussionMessages');
    box.scrollTop = box.scrollHeight;
</script>
</body>
</html>
