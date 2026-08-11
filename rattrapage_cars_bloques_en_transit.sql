-- Debloque les cars restes marques "En_transit_XXX" en base sans jamais avoir recu de
-- decolle_le (voyages crees avant l'ajout de la colonne decolle_le, cf.
-- ajout_decollage_programmation_voyage.sql). Ces cars sont invisibles dans l'interface :
-- ni "disponibles" (getProgrammationCars() exclut tout status_car En_transit_%), ni
-- "en approche" (getCarsInTransit() exige decolle_le non NULL).
--
-- Ce script suppose que ces voyages sont deja arrives a destination (confirme
-- manuellement) : il enregistre coup sur coup le decollage et l'arrivee, comme l'aurait
-- fait le flux normal (decollerCar() + validerArrivee()), et libere donc le car a
-- destination. NE PAS lancer si un des cars listes n'est en realite jamais parti :
-- utiliser plutot l'action "Jamais parti" du nouvel ecran "Cars bloques"
-- (/admin/Programmation_voyages) pour ceux-la, au cas par cas.
--
-- A executer une seule fois. Etape 1 = verification (relire le resultat avant de
-- continuer), etape 2 et 3 = correction.

-- 1) Verification : liste des cars concernes avant correction
SELECT c.id_car, c.numero_car, c.status_car, c.id_compagnie,
       pv.id_programmation, pv.date_enregistre, pv.id_horaire, pv.id_trajet AS destination,
       pv.localite_user AS origine
FROM car c
JOIN programmation_voyage pv
  ON pv.id_car_programmer = c.id_car
 AND pv.id_trajet = SUBSTRING(c.status_car, 12)
 AND pv.statut = 'active'
WHERE c.status_car LIKE 'En\_transit\_%'
  AND pv.decolle_le IS NULL;

-- 2) Backfill du decollage : horodate a l'heure programmee du trajet (date_enregistre +
--    id_horaire), la meilleure approximation possible faute de connaitre l'heure reelle.
UPDATE programmation_voyage pv
JOIN car c
  ON c.id_car = pv.id_car_programmer
 AND pv.id_trajet = SUBSTRING(c.status_car, 12)
SET pv.decolle_le = TIMESTAMP(pv.date_enregistre, pv.id_horaire)
WHERE c.status_car LIKE 'En\_transit\_%'
  AND pv.statut = 'active'
  AND pv.decolle_le IS NULL;

-- 3) Validation de l'arrivee : le car redevient disponible a sa destination.
--    A executer seulement APRES l'etape 2 (qui depend encore de l'ancien status_car).
UPDATE car
SET status_car = SUBSTRING(status_car, 12)
WHERE status_car LIKE 'En\_transit\_%';

