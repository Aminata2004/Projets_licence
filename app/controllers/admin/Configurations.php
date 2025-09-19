<?php
class Configurations extends Controller
{
    public function __construct()
    {
        $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
    }

    // public function index()
    // {
    //     $configuration = new Configuration();

    //     // Initialisation sécurisée
    //     $listes = [];
    //     // Vérifie si l'utilisateur est connecté
    //     if (isset($_SESSION['droit'])) {
    //         $role = $_SESSION['droit'];
    //         // SuperAdmin → affiche tous les utilisateurs
    //         if ($role === 'super_admin') {
    //             $listes = $configuration->SelectAllData(
    //                 '*',
    //                 'utilisateur 
    //              LEFT JOIN agence ON agence.idAgence = utilisateur.id_agence 
    //              LEFT JOIN compagnie ON compagnie.id_compagnie = agence.id_compagnie'
    //             );

    //             // Admin → affiche uniquement les utilisateurs de sa compagnie
    //         } elseif ($role === 'Admin' && isset($_SESSION['id_compagnie'])) {
    //             $id_compagnie = $_SESSION['id_compagnie'];

    //             $listes = $configuration->FetchSelectWheres(
    //                 '*',
    //                 'utilisateur 
    //              INNER JOIN agence ON agence.idAgence = utilisateur.id_agence 
    //              INNER JOIN compagnie ON compagnie.id_compagnie = agence.id_compagnie',
    //                 'agence.id_compagnie = :id_compagnie',
    //                 ['id_compagnie' => $id_compagnie]
    //             );
    //         } else {
    //             $configuration->set_flash("Accès restreint ou informations incomplètes.", "danger");
    //         }
    //     } else {
    //         $configuration->set_flash("Session expirée ou utilisateur non connecté.", "warning");
    //         $this->redirect("admin/Login/index");
    //         return;
    //     }


    //      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idUser'], $_POST['newStatut'])) {
    //         $id = (int)$_POST['idUser'];
    //         $status = (int)$_POST['newStatut'];

    //         $result = $configuration->insertion_update_simple("UPDATE utilisateur SET status = :status WHERE idUser = :id", [
    //             ":status" => $status,
    //             ":id" => $id
    //         ]);


    //     }

    //     // Appel de la vue
    //     $this->view('admin/configuration', ['liste' => $listes]);
    // }

    public function index()
    {
        $configuration = new Configuration();

        // Initialisation sécurisée
        $listes = [];

        // Vérifie si l'utilisateur est connecté
        if (isset($_SESSION['droit'])) {
            $role = $_SESSION['droit'];

            if ($role === 'super_admin') {
                $listes = $configuration->SelectAllData(
                    '*',
                    'utilisateur 
                LEFT JOIN agence ON agence.idAgence = utilisateur.id_agence 
                LEFT JOIN compagnie ON compagnie.id_compagnie = agence.id_compagnie'
                );
            } elseif ($role === 'Admin' && isset($_SESSION['id_compagnie'])) {
                $id_compagnie = $_SESSION['id_compagnie'];

                $listes = $configuration->FetchSelectWheres(
                    '*',
                    'utilisateur 
                INNER JOIN agence ON agence.idAgence = utilisateur.id_agence 
                INNER JOIN compagnie ON compagnie.id_compagnie = agence.id_compagnie',
                    'agence.id_compagnie = :id_compagnie',
                    ['id_compagnie' => $id_compagnie]
                );
            } else {
                $configuration->set_flash("Accès restreint ou informations incomplètes.", "danger");
            }
        } else {
            $configuration->set_flash("Session expirée ou utilisateur non connecté.", "warning");
            $this->redirect("admin/Login/index");
            return;
        }

        // Gestion du POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idUser'], $_POST['newStatut'])) {
            $id = (int)$_POST['idUser'];
            $status = (int)$_POST['newStatut'];

            $result = $configuration->insertion_update_simple(
                "UPDATE utilisateur SET status = :status WHERE idUser = :id",
                [
                    ":status" => $status,
                    ":id" => $id
                ]
            );

            // Si la mise à jour est réussie, on recharge la page
            if ($result !== false) {
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                $configuration->set_flash("Erreur lors de la mise à jour du statut.", "danger");
            }
        }

        // Affichage de la vue
        $this->view('admin/configuration', ['liste' => $listes]);
    }




    public function add_utilisateurs()
    {
        $configuration = new Configuration();

        // Récupérer les données des filières
        $listeCompagnie = $configuration->SelectAllData("*", "compagnie");

        if (isset($_POST["saveutilisateur"])) {
            // var_dump($_POST);exit;
            $configuration->saveUtilisateur();
        }

        $listeGares = [];

        if ($_SESSION['droit'] === 'Admin' && isset($_SESSION['id_compagnie'])) {
            $id_compagnie = $_SESSION['id_compagnie'];

            // Récupérer uniquement les gares liées à cette compagnie
            $listeGares = $configuration->FetchSelectWheres(
                '*',
                'agence',
                'id_compagnie = :id_compagnie',
                ['id_compagnie' => $id_compagnie]
            );
        } else {
            // SuperAdmin ou autres → voir toutes les gares
            $listeGares = $configuration->SelectAllData("*", "agence");
        }

        // Ensuite, envoie à la vue
        $this->view('admin/add_utilisateur', [
            'listeGares' => $listeGares,
            'listeCompagnie' => $listeCompagnie
        ]);
    }
}
