-- Colonnes necessaires au workflow de validation des depenses (app/models/Depense.php)
-- et a leur deduction de la caisse individuelle d'un chef d'escale
-- (app/models/Depense.php::deduireCaisse()). Ces colonnes etaient utilisees par le code
-- mais absentes de la base : "Unknown column 'depense.statut'" / 'id_caisse_user'.

ALTER TABLE depense
    ADD COLUMN statut VARCHAR(20) NOT NULL DEFAULT 'en_attente' AFTER montant,
    ADD COLUMN id_caisse_user INT(11) NULL DEFAULT NULL AFTER id_caisse;

ALTER TABLE caisse_utilisateur
    ADD COLUMN montant_depense DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER montant_compte;

-- Les depenses deja liees a une caisse (ancien systeme, avant ce workflow de validation)
-- sont considerees comme deja traitees/deduites.
UPDATE depense SET statut = 'valide' WHERE id_caisse IS NOT NULL OR id_caisse_user IS NOT NULL;
