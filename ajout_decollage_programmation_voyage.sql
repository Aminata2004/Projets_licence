
-- Ajoute le suivi du "décollage" (départ réel) d'un voyage programmé : jusqu'ici rien
-- ne distinguait "le bus est programmé" de "le bus a physiquement quitté la gare avec
-- ses passagers embarqués". Sert de signal fiable pour l'écran Embarquement (bouton
-- "Faire décoller le bus", qui désactive ensuite le bouton "Embarquer" pour ce trajet),
-- au lieu de se fier uniquement à l'heure de départ prévue (un bus peut partir en
-- avance ou en retard).
--
-- N'affecte pas le mécanisme existant car.status_car ('En_transit_...') utilisé par
-- Programmation_voyage::validerArrivee()/getCarsInTransit() et par le blocage des
-- transferts entre gares (Transfert_gare.php) : ce sont deux suivis distincts pour
-- l'instant.
--
-- À exécuter une seule fois.

ALTER TABLE programmation_voyage
    ADD COLUMN decolle_le DATETIME NULL AFTER statut,
    ADD COLUMN decolle_par INT NULL AFTER decolle_le;
