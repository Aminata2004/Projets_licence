<?php
class Permissions extends Controller
{
    public function index()
    {

        $this->view("asignerPermission");
    }
    
    // public function modifier_permission($id)
    // {
    //     $permissionModel = new Permission();

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $name = $_POST['name'] ?? null;

    //         if ($name) {
    //             $permissionModel->updatePermission($id, $name);
    //             $permissionModel->set_flash("Permission modifiée avec succès.", "success");
    //             $this->redirect("Permissions/ajouter_permission");
    //         } else {
    //             $permissionModel->set_flash("Veuillez remplir le champ.", "danger");
    //         }

    //         $this->redirect("Permissions/liste_permissions");
    //         return;
    //     }
    // }
    // public function delete($id)
    // {
    //     $permissionModel = new Permission();

    //     $assignedUsers = $permissionModel->countUsersWithPermission($id);

    //     if ($assignedUsers > 0) {
    //         $urlForceDelete = ROOT . "/Permissions/delete_force/$id";

    //         $permissionModel->set_flash(
    //             "Cette permission est encore assignée à $assignedUsers utilisateur(s). " .
    //                 "<a href='$urlForceDelete' class='btn btn-sm btn-danger ms-2 btn-force-delete'>Supprimer quand même</a>",
    //             'warning'
    //         );
    //         $this->redirect("Permissions/ajouter_permission");
    //         return;
    //     }

    //     $sql = 'DELETE FROM permissions WHERE id = :id';
    //     $params = [':id' => $id];
    //     $result = $permissionModel->insertion_update_simples($sql, $params);

    //     if ($result && $result->rowCount() > 0) {
    //         $permissionModel->set_flash("Suppression réussie", 'success');
    //     } else {
    //         $permissionModel->set_flash("Échec de la suppression", 'danger');
    //     }

    //     $this->redirect("Permissions/ajouter_permission");
    // }

    // public function delete_force($id)
    // {
    //     $permissionModel = new Permission();

    //     $sqlDeleteAssignations = "DELETE FROM user_permission WHERE permission_id = :id";
    //     $permissionModel->insertion_update_simples($sqlDeleteAssignations, [':id' => $id]);

    //     $sql = 'DELETE FROM permissions WHERE id = :id';
    //     $params = [':id' => $id];
    //     $result = $permissionModel->insertion_update_simples($sql, $params);

    //     if ($result && $result->rowCount() > 0) {
    //         $permissionModel->set_flash("Suppression assignation et permission réussie.", 'success');
    //     } else {
    //         $permissionModel->set_flash("Échec de la suppression forcée.", 'danger');
    //     }

    //     $this->redirect("Permissions/ajouter_permission");
    // }
    public function assigner($idUtilisateur)
    {
        //$utilisateurModel = new Utilisateur();
        $utilisateurModel = new Configuration();
        $permissionModel = new Permission();

         $utilisateur = $utilisateurModel->findById($idUtilisateur);
        $allPermissions = $permissionModel->getAll(); // À créer si besoin
        $userPermissions = $permissionModel->getUserPermissions($idUtilisateur); // À créer si besoin


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selected = $_POST['permissions'] ?? [];
            // Supprimer toutes les permissions actuelles
            $permissionModel->removeAllUserPermissions($idUtilisateur);
            // Réinsérer celles sélectionnées
            foreach ($selected as $permId) {
                $permissionModel->assignPermissionToUser($idUtilisateur, $permId);
            }
            // ✅ Flash message de succès
            // $permissionModel->set_flash("Permissions enregistrées avec succès.", "success");
            //     $this->view('admin/configuration/add_utilisateurs');// change selon ta route

                 $permissionModel->set_swal(
                        "👤 Permission ajouté !",
                        "Permission a été ajouté avec succès.",
                        "success",
                        "#0d6efd", // couleur primary pour l'icône et le bouton
                        BASE_URL . "/admin/Configurations" // redirection après confirmation
                    );
            
            // Redirection ou message de succès
        }

        $this->view('/admin/asssignier_permission', [
            'utilisateur' => $utilisateur,
            'allPermissions' => $allPermissions,
            'userPermissions' => $userPermissions
        ]);
    }
}
