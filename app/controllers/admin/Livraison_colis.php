<?php

class Livraison_colis extends Controller
{
    public function __construct()
    {
        $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
    }

    public function index()
    {
        $colisModel = new Livraisons_colis();

        // 1) Livrer le colis
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['livrer'])) {

            $idColis = (int)($_POST['id_colis'] ?? 0);
            if ($idColis <= 0) {
                $colisModel->set_flash('Colis introuvable.', 'danger');
                header("Location: " . BASE_URL . "/admin/Livraison_colis");
                exit();
            }

            $colis = $colisModel->getById($idColis);
            if (!$colis) {
                $colisModel->set_flash('Colis introuvable.', 'danger');
                header("Location: " . BASE_URL . "/admin/Livraison_colis");
                exit();
            }

            // Vérification des droits
            $droit      = $_SESSION['droit'] ?? '';
            $memeVille  = $colis['localite'] === ($_SESSION['ville'] ?? '');
            $memeGare   = $colis['numero_gare'] === ($_SESSION['numero_gare'] ?? '');
            $bonStatut  = $colis['status'] === 'recu';

            $peutLivrer = match ($droit) {
                'chef_d_escale' => $memeVille && $bonStatut,
                'utilisateur'     => $memeVille && $memeGare && $bonStatut,
                default           => false,
            };
            if (!$peutLivrer) {
                $colisModel->set_flash("Pas les droits pour livrer.", 'danger');
                header("Location: " . BASE_URL . "/admin/Livraison_colis");
                exit();
            }

            // Mise à jour du statut de livraison
            $updated = $colisModel->livrer($idColis);
            if (!$updated) {
                $colisModel->set_flash("Erreur lors de la mise à jour du statut.", 'danger');
                header("Location: " . BASE_URL . "/admin/Livraison_colis");
                exit();
            }

            $colisModel->set_flash("Colis livré avec succès !", 'primary');

            // On réaffiche la page avec le colis (désormais livré) pour proposer tout de suite
            // le bouton WhatsApp de confirmation à l'expéditeur (plus d'email automatique).
            $colis['status'] = 'livre';
            $this->view('admin/livraison_colis', [
                'colis' => $colis,
                'peutLivrer' => false,
                'codeRecherche' => $colis['code_colis'] ?? '',
                'livraisonReussie' => true,
            ]);
            return;
        }

        // 2) Recherche de colis par code
      $colis = null;
$peutLivrer = false;

// Recherche déclenchée soit par le formulaire (POST 'envoi'), soit par un lien
// direct depuis la liste des colis reçus (GET ?code=...)
$recherche = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoi']))
    ? ($_POST['code'] ?? '')
    : ($_GET['code'] ?? null);

if ($recherche !== null) {
    $code = trim($recherche);

    if ($code === '') {
        $colisModel->set_flash("Merci de renseigner un code colis.", 'danger');
    } else {
        $colis = $colisModel->findByCode($code);

        if (!$colis || !is_array($colis)) {
            $colisModel->set_flash("Le code colis n’existe pas.", 'danger');
            $colis = null;
        } else {
            // Vérification droits
            $droit = $_SESSION['droit'] ?? '';
            $memeVille = isset($colis['localite']) && $colis['localite'] === ($_SESSION['ville'] ?? '');
            $memeGare  = isset($colis['numero_gare']) && $colis['numero_gare'] === ($_SESSION['numero_gare'] ?? '');
            $bonStatut = isset($colis['status']) && $colis['status'] === 'recu';

            $peutLivrer = match ($droit) {
                'chef_d_escale' => $memeVille && $bonStatut,
                'utilisateur'     => $memeVille && $memeGare && $bonStatut,
                default           => false,
            };
        }
    }
}

// Toujours passer $colis et $peutLivrer à la vue
$this->view('admin/livraison_colis', [
    'colis' => $colis,
    'peutLivrer' => $peutLivrer,
    'codeRecherche' => $recherche !== null ? trim($recherche) : ''
]);

    }
}
