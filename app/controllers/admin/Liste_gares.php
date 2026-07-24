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

        // Lignes en échec du dernier essai d'ajout (voir add_gares()) : stockées en session
        // le temps de la redirection PRG, consommées une seule fois ici pour rouvrir la
        // modal pré-remplie avec les valeurs saisies et les champs fautifs en rouge.
        $lignesEnErreur = $_SESSION['gares_lignes_en_erreur'] ?? [];
        unset($_SESSION['gares_lignes_en_erreur']);

        // Affiche la vue
        $this->view('admin/liste_gare', ['listes' => $listes, 'lignesEnErreur' => $lignesEnErreur]);
    }

    // methode pour enregistre : POST -> Redirect -> GET, comme les autres formulaires
    // "add to row" (escales/horaires/cars), pour un cycle de soumission fiable (pas de
    // resoumission accidentelle au rechargement) et une page de liste unique.
    public function add_gares()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["enregistre"])) {
            $liste_gare = new Liste_gare();
            $lignesEnErreur = $liste_gare->saveGares();
            if (!empty($lignesEnErreur)) {
                $_SESSION['gares_lignes_en_erreur'] = $lignesEnErreur;
            }
        }
        header("Location: " . BASE_URL . "/admin/Liste_gares/index");
        exit;
    }

    public function edit()
    {
        $liste_gare = new Liste_gare();
        if (isset($_POST['edit'])) {

            // extract($_POST);
             //var_dump($_POST); exit;
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


    public function suspend($idAgence = null)
    {
        if ($idAgence) {
            $liste_gare = new Liste_gare();
            $liste_gare->suspendGare($idAgence);
        }
        header("Location: " . BASE_URL . "/admin/Liste_gares/index");
        exit;
    }

    public function delete($idAgence = null)
    {
        if ($idAgence) {
            $liste_gare = new Liste_gare();
            $liste_gare->deleteGare($idAgence);
        }
        header("Location: " . BASE_URL . "/admin/Liste_gares/index");
        exit;
    }
}
