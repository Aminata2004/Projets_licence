-- Rattrapage : jusqu'ici, assignPermissionsParDefautPourRole() (app/models/Permission.php)
-- n'attribuait aucune permission par défaut au rôle 'Admin' à la création du compte.
-- Sans contrôle réel côté contrôleurs, ça n'avait aucun effet visible ; ça en aura un dès
-- que les contrôleurs sensibles vérifient userHasPermission(). Ce script donne donc à tous
-- les comptes Admin déjà en base l'intégralité du catalogue de permissions existant, pour
-- ne pas leur couper l'accès à ce qu'ils utilisent déjà.
--
-- À exécuter une seule fois.

INSERT INTO user_permission (user_id, permission_id)
SELECT u.idUser, p.id_permision
FROM utilisateur u
CROSS JOIN permision p
WHERE u.droit = 'Admin'
  AND NOT EXISTS (
    SELECT 1 FROM user_permission up
    WHERE up.user_id = u.idUser AND up.permission_id = p.id_permision
  );
