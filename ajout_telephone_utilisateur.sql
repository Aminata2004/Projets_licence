
-- Ajoute le numéro de téléphone au compte utilisateur : jusqu'ici seul l'email était
-- collecté, or le chef d'escale/admin a régulièrement besoin de joindre un employé
-- directement par téléphone (astreinte, urgence guichet). Champ optionnel, affiché
-- dans le formulaire de création, la liste des utilisateurs et la liste des employés.
--
-- À exécuter une seule fois.

ALTER TABLE utilisateur
    ADD COLUMN telephone VARCHAR(20) NULL AFTER emailUser;
