-- Bug corrigé dans app/models/Configuration.php::saveUtilisateur() : la méthode utilisait
-- $pdo->lastInsertId() sur une connexion qui n'avait jamais exécuté l'INSERT réel (celui-ci
-- passait par une autre connexion ouverte par insertion_update_simples()). lastInsertId()
-- valait donc toujours 0, et le nouvel utilisateur recevait ses permissions par défaut sous
-- l'identifiant fantôme 0 au lieu du sien — il se retrouvait sans aucune permission malgré
-- le message de succès affiché. Ce script répare les dégâts déjà causés en base :
--
-- 1) Supprime les lignes user_permission orphelines (dont user_id = 0 ou pointant vers un
--    compte supprimé depuis).
-- 2) Attribue rétroactivement les permissions par défaut de son rôle à tout utilisateur qui
--    n'en a actuellement AUCUNE (signe qu'il a été créé pendant que ce bug était actif).
--
-- À exécuter une seule fois, après avoir déployé le correctif de Configuration.php.

-- ─────────────────────────────────────────────────────────────────────────
-- 1) Nettoyage des lignes orphelines
-- ─────────────────────────────────────────────────────────────────────────

DELETE FROM user_permission
WHERE user_id NOT IN (SELECT idUser FROM utilisateur);

-- ─────────────────────────────────────────────────────────────────────────
-- 2) Rattrapage : super_admin / Admin → tout le catalogue
-- ─────────────────────────────────────────────────────────────────────────

INSERT INTO user_permission (user_id, permission_id)
SELECT u.idUser, p.id_permision
FROM utilisateur u
CROSS JOIN permision p
WHERE u.droit IN ('super_admin', 'Admin')
  AND NOT EXISTS (SELECT 1 FROM user_permission up WHERE up.user_id = u.idUser)
  AND NOT EXISTS (
    SELECT 1 FROM user_permission up2
    WHERE up2.user_id = u.idUser AND up2.permission_id = p.id_permision
  );

-- ─────────────────────────────────────────────────────────────────────────
-- 3) Rattrapage : chef_d_escale (tout sauf Programme_Creation / Programme_programmer_car)
-- ─────────────────────────────────────────────────────────────────────────

INSERT INTO user_permission (user_id, permission_id)
SELECT u.idUser, p.id_permision
FROM utilisateur u
CROSS JOIN permision p
WHERE u.droit = 'chef_d_escale'
  AND NOT EXISTS (SELECT 1 FROM user_permission up WHERE up.user_id = u.idUser)
  AND p.nom_permission NOT IN ('Programme_Creation', 'Programme_programmer_car')
  AND p.nom_permission IN (
    'utilisateur_apercu','Configuration_apercu','Configuration_gestion_gare',
    'Configuration_gestion_escale','Configuration_gestion_trajets','Configuration_gestion_horaire',
    'Configuration_gestion_car/chauffeur','Configuration_place/limite',
    'Caisse_creation','Caisse_apercue','Caisse_billant','Caisse_modifier',
    'Billets_creation','Billets_apercue','Billets_validation','Billets_historique',
    'Billets_notification','Billets_annulation','Billets_impression','Billets_rapport','Billets_reporte',
    'colis_creation','colis_envoi','colis_mouvement','colis_livraison','colis_reclamation',
    'colis_historique','colis_apercue',
    'Depenses_gestion','Programme_programmation_voyage','Programme_hors_programme'
  );

-- ─────────────────────────────────────────────────────────────────────────
-- 4) Rattrapage : Utilisateur simple, service Billetterie
-- ─────────────────────────────────────────────────────────────────────────

INSERT INTO user_permission (user_id, permission_id)
SELECT u.idUser, p.id_permision
FROM utilisateur u
CROSS JOIN permision p
WHERE u.droit = 'Utilisateur' AND u.profile = 'billet'
  AND NOT EXISTS (SELECT 1 FROM user_permission up WHERE up.user_id = u.idUser)
  AND p.nom_permission IN (
    'Billets_creation','Billets_apercue','Billets_validation','Billets_historique',
    'Billets_notification','Billets_impression','Billets_rapport','Billets_reporte',
    'Caisse_creation','Caisse_apercue','Caisse_billant','Caisse_modifier'
  );

-- ─────────────────────────────────────────────────────────────────────────
-- 5) Rattrapage : Utilisateur simple, service Colis / Courrier
-- ─────────────────────────────────────────────────────────────────────────

INSERT INTO user_permission (user_id, permission_id)
SELECT u.idUser, p.id_permision
FROM utilisateur u
CROSS JOIN permision p
WHERE u.droit = 'Utilisateur' AND u.profile = 'colis'
  AND NOT EXISTS (SELECT 1 FROM user_permission up WHERE up.user_id = u.idUser)
  AND p.nom_permission IN (
    'colis_creation','colis_envoi','colis_mouvement','colis_livraison',
    'colis_reclamation','colis_historique','colis_apercue'
  );
