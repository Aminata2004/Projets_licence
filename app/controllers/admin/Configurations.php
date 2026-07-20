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

        // Seuls super_admin et Admin gèrent les comptes utilisateurs. Sans ce garde-fou,
        // un simple compte "Utilisateur" connecté pouvait poster idUser=<son propre id>&droit=super_admin
        // sur ce même endpoint et s'auto-promouvoir (le code précédent ne faisait qu'un set_flash
        // sans exit avant de continuer vers les blocs POST plus bas).
        $role = $_SESSION['droit'] ?? null;
        if (!in_array($role, ['super_admin', 'Admin'], true)) {
            $configuration->set_flash("Accès restreint.", "danger");
            $this->redirect("admin/Homes/home");
            return;
        }

        $id_compagnie = $_SESSION['id_compagnie'] ?? null;

        // Un Admin ne peut agir que sur les utilisateurs de sa propre compagnie ; jamais
        // se/les promouvoir Admin ou super_admin (seul un super_admin peut attribuer ces droits).
        $droitsAutorises = $role === 'super_admin'
            ? ['super_admin', 'Admin', 'Utilisateur', 'chef_d_escale']
            : ['Utilisateur', 'chef_d_escale'];

        // Gestion du POST : changement de statut
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idUser'], $_POST['newStatut'])) {
            $id = (int)$_POST['idUser'];
            $status = (int)$_POST['newStatut'];

            if ($role === 'Admin' && !$this->utilisateurAppartientCompagnie($configuration, $id, $id_compagnie)) {
                $configuration->set_flash("Action non autorisée.", "danger");
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            $result = $configuration->insertion_update_simple(
                "UPDATE utilisateur SET status = :status WHERE idUser = :id",
                [
                    ":status" => $status,
                    ":id" => $id
                ]
            );

            // Si la mise à jour est réussie, on recharge la page
            if ($result !== false) {
                $configuration->set_flash("Statut mis à jour avec succès.", "success");
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                $configuration->set_flash("Erreur lors de la mise à jour du statut.", "danger");
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editUtilisateur'])) {
            $idUser = (int)$_POST['idUser'];
            $utilisateurs = $_POST['utilisateurs'];
            $emailUser = $_POST['emailUser'];
            $droit = $_POST['droit'];

            if (($role === 'Admin' && !$this->utilisateurAppartientCompagnie($configuration, $idUser, $id_compagnie))
                || !in_array($droit, $droitsAutorises, true)
            ) {
                $configuration->set_flash("Action non autorisée.", "danger");
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            $profile = ($droit === 'Utilisateur') ? ($_POST['profile'] ?? null) : null;

            $updateFields = "utilisateurs = :utilisateurs, emailUser = :emailUser, droit = :droit, profile = :profile";
            $params = [
                ":utilisateurs" => $utilisateurs,
                ":emailUser" => $emailUser,
                ":droit" => $droit,
                ":profile" => $profile,
                ":id" => $idUser
            ];

            if (!empty($_POST['motPasse'])) {
                $updateFields .= ", motPasse = :motPasse";
                $params[":motPasse"] = password_hash($_POST['motPasse'], PASSWORD_DEFAULT);
            }

            $result = $configuration->insertion_update_simple(
                "UPDATE utilisateur SET $updateFields WHERE idUser = :id",
                $params
            );

            if ($result !== false) {
                $configuration->set_flash("Utilisateur modifié avec succès.", "success");
            } else {
                $configuration->set_flash("Erreur lors de la modification de l'utilisateur.", "danger");
            }
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $userColumns = 'utilisateur.idUser, utilisateur.utilisateurs, utilisateur.emailUser, utilisateur.motPasse,
            utilisateur.droit, utilisateur.profile, utilisateur.status, agence.numeroGare';

        if ($role === 'super_admin') {
            $listes = $configuration->SelectAllData(
                $userColumns,
                'utilisateur
            LEFT JOIN agence ON agence.idAgence = utilisateur.id_agence
            LEFT JOIN compagnie ON compagnie.id_compagnie = agence.id_compagnie'
            );
        } else {
            $listes = $configuration->FetchSelectWheres(
                $userColumns,
                'utilisateur
            INNER JOIN agence ON agence.idAgence = utilisateur.id_agence
            INNER JOIN compagnie ON compagnie.id_compagnie = agence.id_compagnie',
                'agence.id_compagnie = :id_compagnie',
                ['id_compagnie' => $id_compagnie]
            );
        }

        // Affichage de la vue
        $this->view('admin/configuration', ['liste' => $listes]);
    }

    // Vérifie que l'utilisateur ciblé appartient bien à la compagnie de l'Admin connecté
    // (les comptes super_admin/Admin utilisent utilisateur.id_compagnie directement,
    // cf. Configuration::saveUtilisateur()).
    private function utilisateurAppartientCompagnie(Configuration $configuration, $idUser, $id_compagnie)
    {
        $cible = $configuration->getUserById($idUser);
        return $cible && (int)($cible['id_compagnie'] ?? 0) === (int)$id_compagnie;
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
