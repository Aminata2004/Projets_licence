<?php
class  Liste_gares extends  Controller
{
    public function __construct()
    {
        $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
    }

    public function index()
    {
        $liste_gare = new Liste_gare();

        // Initialisation de la variable pour éviter les erreurs
        $listes = [];

        // Vérifie si l'utilisateur est connecté
        if (isset($_SESSION['droit'])) {
            $role = $_SESSION['droit'];

            // SuperAdmin → affiche toutes les gares
            if ($role === 'super_admin') {
                $listes = $liste_gare->SelectAllData('*', "agence");

                // Admin → affiche uniquement les gares de sa compagnie
            } elseif ($role === 'Admin' && isset($_SESSION['id_compagnie'])) {
                $id_compagnie = $_SESSION['id_compagnie'];

                $listes = $liste_gare->FetchSelectWheres(
                    '*',
                    'agence',
                    'id_compagnie = :id_compagnie',
                    ['id_compagnie' => $id_compagnie]
                );
            } else {
                // Pour d'autres rôles ou absence de idAgence
                $liste_gare->set_flash("Accès restreint ou données manquantes", "danger");
            }
        } else {
            $liste_gare->set_flash("Session expirée ou utilisateur non authentifié", "warning");
            $this->redirect("admin/Login/index");
            return;
        }

        // Affiche la vue
        $this->view('admin/liste_gare', ['listes' => $listes]);
    }

    // methode pour enregistre
    public function add_gares()
    {
        $liste_gare = new Liste_gare();

        if (isset($_POST["enregistre"])) {
            // var_dump($_POST);exit;
            $liste_gare->saveGares();
        }
        $this->view('admin/add_gare');
    }

    public function edit()
    {
        $liste_gare = new Liste_gare();
        if (isset($_POST['edit'])) {

            // extract($_POST);
            // var_dump($_POST); exit;
            $numeroGare = $_POST["numeroGare"];
            $localite = $_POST["localite"];
            $code = $_POST["code"];
            $tel = $_POST["tel"];
            $idAgence = $_POST["idAgence"];
            $liste_gare->editAgence(['idAgence' => $idAgence, 'numeroGare' => $numeroGare, 'localite' => $localite, 'code' => $code, 'tel' => $tel]);
            header("Location: " . BASE_URL . "/admin/Liste_gares/index");
            // exit;
        }
    }

  
}
