-- savePermission() (app/models/Add_liste_horaires.php) insérait dans `permision` sans
-- vérifier l'existence du nom au préalable : des permissions identiques (parfois avec des
-- espaces en trop) ont pu être créées plusieurs fois, et s'affichent en double dans les
-- écrans "Liste des permissions" et "Assignation de permissions". Ce script fusionne les
-- doublons vers l'id le plus ancien (le plus petit id_permision) par nom, avant de
-- supprimer les lignes en trop.
--
-- À exécuter une seule fois. Faire une sauvegarde de `permision` et `user_permission` avant
-- si possible.

-- 1) Réattribue vers l'id canonique les assignations qui pointent vers un id dupliqué,
--    sans créer de doublon (user_id, permission_id).
INSERT INTO user_permission (user_id, permission_id)
SELECT DISTINCT up.user_id, canon.keep_id
FROM user_permission up
JOIN permision p ON up.permission_id = p.id_permision
JOIN (
    SELECT TRIM(nom_permission) AS nom, MIN(id_permision) AS keep_id
    FROM permision
    GROUP BY TRIM(nom_permission)
) canon ON canon.nom = TRIM(p.nom_permission)
WHERE p.id_permision <> canon.keep_id
  AND NOT EXISTS (
    SELECT 1 FROM user_permission up2
    WHERE up2.user_id = up.user_id AND up2.permission_id = canon.keep_id
  );

-- 2) Supprime les assignations qui pointaient vers les ids dupliqués (redondantes avec
--    celles réattribuées à l'étape 1).
DELETE up FROM user_permission up
JOIN permision p ON up.permission_id = p.id_permision
JOIN (
    SELECT TRIM(nom_permission) AS nom, MIN(id_permision) AS keep_id
    FROM permision
    GROUP BY TRIM(nom_permission)
) canon ON canon.nom = TRIM(p.nom_permission)
WHERE p.id_permision <> canon.keep_id;

-- 3) Supprime les lignes de permision dupliquées (ne garde que l'id canonique par nom).
DELETE p FROM permision p
JOIN (
    SELECT TRIM(nom_permission) AS nom, MIN(id_permision) AS keep_id
    FROM permision
    GROUP BY TRIM(nom_permission)
) canon ON canon.nom = TRIM(p.nom_permission)
WHERE p.id_permision <> canon.keep_id;
