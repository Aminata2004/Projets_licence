-- Nouvelle fonctionnalite : location de cars (hors trajets/itineraires programmes).
-- cf. app/models/Location_car.php, app/controllers/admin/Locations_cars.php

CREATE TABLE IF NOT EXISTS location_car (
    id_location INT(11) NOT NULL AUTO_INCREMENT,
    id_compagnie INT(11) NOT NULL,
    id_agence_depart INT(11) NOT NULL,
    destination VARCHAR(150) NOT NULL,
    id_car INT(11) NOT NULL,
    nom_client VARCHAR(100) NOT NULL,
    prenom_client VARCHAR(100) NOT NULL,
    telephone_client VARCHAR(30) NOT NULL,
    date_depart DATE NOT NULL,
    date_retour_prevu DATE NOT NULL,
    frais_location INT(11) NOT NULL,
    statut VARCHAR(20) NOT NULL DEFAULT 'en_attente',
    id_utilisateur INT(11) NOT NULL,
    date_enregistrement DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_location),
    KEY id_compagnie (id_compagnie),
    KEY id_agence_depart (id_agence_depart),
    KEY id_car (id_car)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Revenu genere par les locations, credite a la caisse de la gare de depart au moment
-- de la validation (meme principe que montant_billets / montant_colis).
ALTER TABLE caisse
    ADD COLUMN montant_location INT(11) NOT NULL DEFAULT 0 AFTER montant_depense;
