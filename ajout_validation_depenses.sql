-- Ajout du workflow de validation des dépenses pour le chef d'escale.
-- Les dépenses créées par un chef d'escale sont en attente et doivent être validées
-- par l'Admin avant d'être déduites de la caisse.
--
-- À exécuter une seule fois.

ALTER TABLE depense
    ADD COLUMN statut ENUM('en_attente', 'valide', 'rejete') NOT NULL DEFAULT 'valide';
