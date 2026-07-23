<?php

// Dépôt en banque : le chef d'escale (sa propre gare) ou l'Admin (gare au choix) demande
// le dépôt d'une partie d'une caisse ; l'Admin confirme ou rejette ensuite depuis
// enAttente(). Voir app/models/Banque.php pour la logique métier et ajout_banque.sql
// pour le schéma.
class Depots_banque extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    // Formulaire de nouvelle demande + historique. Le chef d'escale ne voit que sa
    // gare ; l'Admin choisit la gare concernée (comme pour une dépense locale).
    public function index()
    {
        $model = new Banque();
        $droit = $_SESSION['droit'] ?? null;

        if (!in_array($droit, ['chef_d_escale', 'Admin'], true)) {
            $model->set_flash("Accès refusé.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['creer_demande'])) {
            $model->creerDemande();
            header("Location: " . BASE_URL . "/admin/Depots_banque");
            exit;
        }

        $listeAgences = [];
        if ($droit === 'Admin') {
            $listeAgences = $model->FetchSelectWheres(
                '*',
                'agence',
                'id_compagnie = :id_compagnie',
                ['id_compagnie' => $_SESSION['id_compagnie']]
            );
        }

        $this->view('admin/depots_banque', [
            'listeBanques'  => $model->getBanquesActives($_SESSION['id_compagnie']),
            'listeAgences'  => $listeAgences,
            'listeDemandes' => $model->getHistorique()
        ]);
    }

    // Admin/super_admin : demandes en attente de validation, pour toute la compagnie.
    public function enAttente()
    {
        $model = new Banque();

        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'super_admin'], true)) {
            $model->set_flash("Accès refusé.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $this->view('admin/depots_banque_attente', [
            'listeDemandes' => $model->getDemandesEnAttente($_SESSION['id_compagnie'])
        ]);
    }

    public function confirmer($id_depot)
    {
        $model = new Banque();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->confirmerDemande($id_depot);
        }
        header("Location: " . BASE_URL . "/admin/Depots_banque/enAttente");
        exit;
    }

    public function rejeter($id_depot)
    {
        $model = new Banque();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->rejeterDemande($id_depot);
        }
        header("Location: " . BASE_URL . "/admin/Depots_banque/enAttente");
        exit;
    }

    // Historique complet (tous statuts), visible par les 3 rôles, filtré par gare pour
    // le chef d'escale.
    public function historique()
    {
        $model = new Banque();

        if (!in_array($_SESSION['droit'] ?? null, ['chef_d_escale', 'Admin', 'super_admin'], true)) {
            $model->set_flash("Accès refusé.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $this->view('admin/depots_banque_historique', [
            'listeDemandes' => $model->getHistorique()
        ]);
    }
}
