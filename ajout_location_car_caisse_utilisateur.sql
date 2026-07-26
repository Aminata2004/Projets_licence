-- Corrige la location de cars : elle ne connaissait que l'ancienne caisse de gare
-- (table "caisse"), pas la caisse individuelle du chef d'escale (table
-- "caisse_utilisateur"). Resultat : un chef d'escale ayant bien ouvert SA caisse se
-- voyait quand meme refuser la validation par l'Admin avec "aucune caisse n'est ouverte".
-- cf. app/models/Location_car.php, meme principe que app/models/Depense.php
-- (id_caisse / id_caisse_user determines et stockes des la creation, credites tels
-- quels a la validation, sans re-verification).

ALTER TABLE location_car
    ADD COLUMN id_caisse INT(11) NULL DEFAULT NULL AFTER id_car,
    ADD COLUMN id_caisse_user INT(11) NULL DEFAULT NULL AFTER id_caisse;

-- Le credit d'une location validee pour un chef d'escale se fait sur SA caisse
-- individuelle (caisse_utilisateur), qui n'avait pas encore cette colonne
-- (montant_depense y existe deja, meme principe).
ALTER TABLE caisse_utilisateur
    ADD COLUMN montant_location DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER montant_depense;
