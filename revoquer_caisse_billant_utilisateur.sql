-- "Bilan de caisse" (Caisse::bilant_caisse_billets / bilant_caisse_colis) montre les
-- caisses de TOUTE la compagnie (Admin) ou de sa gare (chef d'escale) : aucun scope
-- n'existait pour un simple Utilisateur, qui voyait donc les caisses de tout le monde.
-- Caisse_billant retirée du catalogue par défaut du rôle Utilisateur (billetterie) : ce
-- script révoque la permission aux comptes existants qui l'ont déjà (un Utilisateur ne
-- doit voir que sa propre caisse via "Ma Caisse").
--
-- À exécuter une seule fois.

DELETE up FROM user_permission up
JOIN utilisateur u ON u.idUser = up.user_id
JOIN permision p ON p.id_permision = up.permission_id
WHERE u.droit = 'Utilisateur'
  AND p.nom_permission = 'Caisse_billant';
