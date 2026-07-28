-- Embarquement numerique (remplace la case a cocher papier) + demande de report pour un
-- client non embarque, soumise a validation Admin (meme principe que les demandes
-- d'annulation deja existantes : status_billets = 'annulation_demandee').
-- cf. app/models/Liste_du_jour.php, app/controllers/admin/Liste_du_jours.php

ALTER TABLE billets
    ADD COLUMN statut_embarquement VARCHAR(20) NULL DEFAULT NULL AFTER status_billets,
    ADD COLUMN embarque_le DATETIME NULL DEFAULT NULL AFTER statut_embarquement,
    ADD COLUMN embarque_par INT(11) NULL DEFAULT NULL AFTER embarque_le,
    ADD COLUMN nouvelle_date_demandee DATE NULL DEFAULT NULL AFTER annule_par,
    ADD COLUMN nouvelle_heure_demandee TIME NULL DEFAULT NULL AFTER nouvelle_date_demandee,
    ADD COLUMN demande_report_par INT(11) NULL DEFAULT NULL AFTER nouvelle_heure_demandee,
    ADD COLUMN demande_report_le DATETIME NULL DEFAULT NULL AFTER demande_report_par,
    ADD COLUMN report_transmis_par INT(11) NULL DEFAULT NULL AFTER demande_report_le,
    ADD COLUMN report_transmis_le DATETIME NULL DEFAULT NULL AFTER report_transmis_par;

-- statut_embarquement : NULL (pas encore) ou 'embarque'
-- status_billets prend en plus desormais deux valeurs, pour un flux de report a deux
-- etapes (comme demande par un chef d'escale) :
--   'report_demande'   -> etape 1, en attente d'examen par le chef d'escale de la gare concernee
--   'report_transmis'  -> etape 2, transmis par le chef d'escale, en attente de validation Admin
-- Un rejet (a n'importe quelle etape) remet status_billets a NULL.
