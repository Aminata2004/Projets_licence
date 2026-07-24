
<?php
class Permission extends Model
{
    // Catalogue des permissions correspondant aux écrans réels de l'admin (sidebar,
    // écrans Configuration...). La table permision n'a pas de seed SQL versionné : sur
    // une base neuve ou vidée, elle est vide et personne (pas même un super_admin
    // nouvellement créé) ne peut se voir assigner de permission tant qu'un admin n'en a
    // pas ajouté manuellement via l'écran "Ajouter permission". On la seed donc ici.
    private const NOMS_PERMISSIONS_PAR_DEFAUT = [
        'utilisateur_apercu',
        'Configuration_apercu',
        'Configuration_gestion_gare',
        'Configuration_gestion_escale',
        'Configuration_gestion_trajets',
        'Configuration_gestion_horaire',
        'Configuration_gestion_car/chauffeur',
        'Configuration_place/limite',
        'Caisse_creation',
        'Caisse_apercue',
        'Caisse_billant',
        'Billets_creation',
        'Billets_apercue',
        'Billets_validation',
        'Billets_historique',
        'Billets_notification',
        'colis_creation',
        'colis_envoi',
        'colis_mouvement',
        'colis_livraison',
        'colis_reclamation',
        'colis_historique',
        'Depenses_gestion',
        'Programme_Creation',
        'Programme_programmer_car',
        'Programme_programmation_voyage',
        'Programme_hors_programme',
    ];

    public function getAll()
    {
        return $this->FetchSelectAllWhere("id_permision, nom_permission", "permision", "1", []);
    }

    // Seede le catalogue par défaut si la table permision est vide (base neuve, vidage
    // total...). N'écrase rien si des permissions existent déjà, même partiellement.
    public function seedPermissionsParDefautSiVide(): void
    {
        if (!empty($this->getAll())) {
            return;
        }

        $sql = "INSERT INTO permision (nom_permission) VALUES (:nom_permission)";
        foreach (self::NOMS_PERMISSIONS_PAR_DEFAUT as $nom) {
            $this->insertion_update_simples($sql, [':nom_permission' => $nom]);
        }
    }
    // Récupère les IDs des permissions d'un utilisateur
    public function getUserPermissions($userId)
    {
        $sql = "SELECT permission_id FROM user_permission WHERE user_id = ?";
        $result = $this->select_data_table_join_where($sql, [$userId]);
        // Retourne un tableau d'IDs
        return array_map(function ($row) {
            return $row->permission_id;
        }, $result);
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
