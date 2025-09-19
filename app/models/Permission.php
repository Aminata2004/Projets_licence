
<?php
class Permission extends Model
{
  public function getAll()
{
    return $this->FetchSelectAllWhere("id_permision, nom_permission", "permision", "1", []);
}
    // Récupère les IDs des permissions d'un utilisateur
    public function getUserPermissions($userId)
    {
        $sql = "SELECT permission_id FROM user_permission WHERE user_id = ?";
        $result = $this->select_data_table_join_where($sql, [$userId]);
        // Retourne un tableau d'IDs
        return array_map(function($row) { return $row->permission_id; }, $result);
    }
    // Récupère tous les modules
     public function removeAllUserPermissions($userId)
    {
        $sql = "DELETE FROM user_permission WHERE user_id = ?";
        $this->insertion_update_simples($sql, [$userId]);
    }

  // Assigne une permission à un utilisateur, uniquement si elle n'existe pas
public function assignPermissionToUser($userId, $permissionId)
{
    // Vérifier si la permission existe déjà pour cet utilisateur
    $exists = $this->FetchSelectWhere2(
        "*",
        "user_permission",
        "user_id = :user_id AND permission_id = :permission_id",
        [
            ':user_id' => $userId,
            ':permission_id' => $permissionId
        ]
    );

    if (empty($exists)) {
        // Si elle n'existe pas, on l'insère
        $sql = "INSERT INTO user_permission (user_id, permission_id) VALUES (?, ?)";
        $this->insertion_update_simples($sql, [$userId, $permissionId]);
    }
}
public function updatePermission($id, $name)
{
    return $this->insertion_update_simples(
        "UPDATE permissions SET name = :name WHERE id = :id",
        [':name' => $name, ':id' => $id]
    );
}
public function countUsersWithPermission($permissionId)
{
    $sql = "SELECT COUNT(*) as total FROM user_permission WHERE permission_id = ?";
    $result = $this->select_data_table_join_where($sql, [$permissionId]);
    return $result[0]->total ?? 0;
}
    // Autres méthodes pour gérer les permissions...
}
 