<?php

class Depenses extends Controller
{
    public function __construct()
    {
        $this->requireLogin();

        // Réservé à Admin/chef_d_escale/PDG (comme dans la sidebar) : Depenses_gestion seul
        // ne suffit pas, sinon un Utilisateur simple à qui cette permission serait assignée
        // par erreur y aurait quand même accès.
        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'chef_d_escale', 'PDG'], true)) {
            (new Configuration())->set_flash("Accès refusé.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $this->requirePermission('Depenses_gestion');
    }

    public function index()
    {
        $model = new Depense();
        $id_compagnie = $_SESSION['id_compagnie'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_depense'])) {
            $model->saveDepense();
            header("Location: " . BASE_URL . "/admin/Depenses");
            exit;
        }

        // Liste des gares, utilisée par l'Admin pour choisir la gare d'une dépense locale
        $listeAgences = [];
        if ($_SESSION['droit'] === 'Admin') {
            $listeAgences = $model->FetchSelectWheres(
                '*',
                'agence',
                'id_compagnie = :id_compagnie',
                ['id_compagnie' => $id_compagnie]
            );
        }

        $this->view('admin/depenses', [
            'listeDepenses' => $model->getDepenses(),
            'listeAgences'  => $listeAgences,
            'categories'    => Depense::CATEGORIES
        ]);
    }

    public function benefice()
    {
        $model = new Depense();

        if (!in_array($_SESSION['droit'] ?? null, ['Admin', 'PDG', 'secretaire'], true)) {
            $model->set_flash("Accès réservé à l'Admin de la compagnie.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $periode = $_GET['periode'] ?? 'jour';
        if (!in_array($periode, ['jour', 'mois', 'tout'], true)) {
            $periode = 'jour';
        }

        $this->view('admin/benefice', [
            'periode'  => $periode,
            'benefice' => $model->getBenefice($periode)
        ]);
    }

    public function valider($id = null)
    {
        if ($id === null) {
            header("Location: " . BASE_URL . "/admin/Depenses");
            exit;
        }

        $model = new Depense();
        if (($_SESSION['droit'] ?? null) !== 'Admin') {
            $model->set_flash("Accès réservé à l'Admin de la compagnie.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $model->validerDepense($id);
        header("Location: " . BASE_URL . "/admin/Depenses");
        exit;
    }

    public function rejeter($id = null)
    {
        if ($id === null) {
            header("Location: " . BASE_URL . "/admin/Depenses");
            exit;
        }

        $model = new Depense();
        if (($_SESSION['droit'] ?? null) !== 'Admin') {
            $model->set_flash("Accès réservé à l'Admin de la compagnie.", "danger");
            header("Location: " . BASE_URL . "/admin/Homes/home");
            exit;
        }

        $model->rejeterDepense($id);
        header("Location: " . BASE_URL . "/admin/Depenses");
        exit;
    }
}
