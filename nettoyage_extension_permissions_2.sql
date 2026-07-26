-- Suite du nettoyage du système de permissions (après nettoyage_doublons_permissions.sql
-- et rattrapage_permissions_admin.sql). Audit réalisé directement sur la base : la table
-- permision contenait 41 lignes pour un catalogue PHP de 27 noms utilisés dans le code.
--
-- À exécuter une seule fois, dans l'ordre des sections ci-dessous.

-- ─────────────────────────────────────────────────────────────────────────
-- 1) Fusionne les 3 permissions "doublons de nom" héritées de l'ancien fichier de
--    sidebar mort (app/views/admin/partials/seidebarall.php, jamais inclus nulle part)
--    vers leur équivalent canonique utilisé par le vrai menu.
-- ─────────────────────────────────────────────────────────────────────────

INSERT INTO user_permission (user_id, permission_id)
SELECT DISTINCT up.user_id, canon.id_permision
FROM user_permission up
JOIN permision old ON old.id_permision = up.permission_id
JOIN permision canon ON canon.nom_permission = CASE old.nom_permission
    WHEN 'Programmer_Creation' THEN 'Programme_Creation'
    WHEN 'ProgrammationCar_Creation' THEN 'Programme_programmer_car'
    WHEN 'ProgrammationVoyage_Creation' THEN 'Programme_programmation_voyage'
END
WHERE old.nom_permission IN ('Programmer_Creation', 'ProgrammationCar_Creation', 'ProgrammationVoyage_Creation')
  AND NOT EXISTS (
    SELECT 1 FROM user_permission up2
    WHERE up2.user_id = up.user_id AND up2.permission_id = canon.id_permision
  );

DELETE up FROM user_permission up
JOIN permision old ON old.id_permision = up.permission_id
WHERE old.nom_permission IN ('Programmer_Creation', 'ProgrammationCar_Creation', 'ProgrammationVoyage_Creation');

DELETE FROM permision
WHERE nom_permission IN ('Programmer_Creation', 'ProgrammationCar_Creation', 'ProgrammationVoyage_Creation');

-- ─────────────────────────────────────────────────────────────────────────
-- 2) Supprime les 2 permissions qui ne correspondent à aucune fonctionnalité du code
--    (aucune suppression de colis, aucune notification de suivi n'existent dans l'app).
-- ─────────────────────────────────────────────────────────────────────────

DELETE up FROM user_permission up
JOIN permision p ON p.id_permision = up.permission_id
WHERE p.nom_permission IN ('colis_supprimer', 'Suivis_notification');

DELETE FROM permision
WHERE nom_permission IN ('colis_supprimer', 'Suivis_notification');

-- ─────────────────────────────────────────────────────────────────────────
-- 3) Rattrapage : les 9 permissions fines nouvellement câblées dans le code
--    (Billets_annulation, Billets_impression, Billets_rapport, Billets_reporte,
--    Caisse_modifier, colis_apercue, utilisateur_creation, utilisateur_modifier,
--    utilisateur_active/desactive) reçues automatiquement par tout compte qui a déjà
--    la permission "parente" du module, pour ne rien changer au comportement actuel.
-- ─────────────────────────────────────────────────────────────────────────

INSERT INTO user_permission (user_id, permission_id)
SELECT DISTINCT up.user_id, fine.id_permision
FROM user_permission up
JOIN permision parent ON parent.id_permision = up.permission_id
JOIN permision fine ON fine.nom_permission IN (
    'Billets_annulation', 'Billets_impression', 'Billets_rapport', 'Billets_reporte'
)
WHERE parent.nom_permission = 'Billets_creation'
  AND NOT EXISTS (
    SELECT 1 FROM user_permission up2
    WHERE up2.user_id = up.user_id AND up2.permission_id = fine.id_permision
  );

INSERT INTO user_permission (user_id, permission_id)
SELECT DISTINCT up.user_id, fine.id_permision
FROM user_permission up
JOIN permision parent ON parent.id_permision = up.permission_id
JOIN permision fine ON fine.nom_permission = 'Caisse_modifier'
WHERE parent.nom_permission = 'Caisse_creation'
  AND NOT EXISTS (
    SELECT 1 FROM user_permission up2
    WHERE up2.user_id = up.user_id AND up2.permission_id = fine.id_permision
  );

INSERT INTO user_permission (user_id, permission_id)
SELECT DISTINCT up.user_id, fine.id_permision
FROM user_permission up
JOIN permision parent ON parent.id_permision = up.permission_id
JOIN permision fine ON fine.nom_permission = 'colis_apercue'
WHERE parent.nom_permission = 'colis_creation'
  AND NOT EXISTS (
    SELECT 1 FROM user_permission up2
    WHERE up2.user_id = up.user_id AND up2.permission_id = fine.id_permision
  );

INSERT INTO user_permission (user_id, permission_id)
SELECT DISTINCT up.user_id, fine.id_permision
FROM user_permission up
JOIN permision parent ON parent.id_permision = up.permission_id
JOIN permision fine ON fine.nom_permission IN (
    'utilisateur_creation', 'utilisateur_modifier', 'utilisateur_active/desactive'
)
WHERE parent.nom_permission = 'utilisateur_apercu'
  AND NOT EXISTS (
    SELECT 1 FROM user_permission up2
    WHERE up2.user_id = up.user_id AND up2.permission_id = fine.id_permision
  );
