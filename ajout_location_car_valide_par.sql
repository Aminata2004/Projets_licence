-- Permet d'afficher le nom reel de l'Admin qui a valide une location (mention
-- "Signature (P.O. <nom>)" sur la facture) au lieu du mot generique "Admin".
-- cf. app/models/Location_car.php::validerLocation()

ALTER TABLE location_car
    ADD COLUMN id_valide_par INT(11) NULL DEFAULT NULL AFTER statut;
