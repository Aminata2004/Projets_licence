<?php

// Gestion des comptes banque de la compagnie (Admin/super_admin uniquement) : les chefs
// d'escale choisissent parmi ces comptes lors d'une demande de dépôt (voir Depots_banque.php).
class Banques extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    public function index()
    {
        $model = new Banque();

        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'super_admin'], true)) {
            $model->set_flash("Accès refusé.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['creer_banque'])) {
                $model->creerBanque();
            } elseif (isset($_POST['modifier_banque'])) {
                $model->modifierBanque();
            }
            header("Location: " . BASE_URL . "/admin/Banques");
            exit;
        }

        $this->view('admin/banques', [
            'listeBanques' => $model->getBanques($_SESSION['id_compagnie'])
        ]);
    }

    // Historique des mouvements (entrées/sorties) d'un compte banque précis.
    public function mouvement($id_banque)
    {
        $model = new Banque();

        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'super_admin'], true)) {
            $model->set_flash("Accès refusé.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $banque = $model->getBanque($id_banque, $_SESSION['id_compagnie']);
        if (!$banque) {
            $model->set_flash("Compte banque introuvable.", "danger");
            header("Location: " . BASE_URL . "/admin/Banques");
            exit;
        }

        $this->view('admin/banque_mouvements', [
            'banque'          => $banque,
            'listeMouvements' => $model->getMouvementsBanque($id_banque, $_SESSION['id_compagnie'])
        ]);
    }
}
