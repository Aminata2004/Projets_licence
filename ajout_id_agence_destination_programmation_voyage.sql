-- id_trajet identifiait la destination d'un voyage par simple nom de ville (varchar),
-- jamais par gare précise (numeroGare/idAgence) — même problème que pour le départ
-- (voir ajout_id_agence_programmation_voyage.sql). Si une ville a plusieurs gares
-- (ex. "Bamako" Gare I et Gare II), rien ne permettait de savoir laquelle est visée.
--
-- id_agence_destination lève l'ambiguïté : c'est désormais la clé d'appariement utilisée
-- pour retrouver la gare précise de destination (id_trajet est conservé pour
-- l'affichage/compatibilité mais n'est plus la seule source de vérité).
--
-- À exécuter une seule fois.

ALTER TABLE programmation_voyage
    ADD COLUMN id_agence_destination INT NULL AFTER id_trajet;

-- Backfill des lignes existantes par correspondance avec le trajet fixe (programmer) qui a
-- servi à les valider à l'enregistrement (voir Programmation_voyage::insertProgrammation()).
-- Les lignes pour lesquelles la correspondance reste ambiguë (plusieurs trajets programmer
-- possibles) restent NULL : à corriger manuellement au cas par cas.
UPDATE programmation_voyage pv
JOIN (
    SELECT p.idDepart, p.heureDepart, p.id_compagnie, a2.localite, p.idDestination
    FROM programmer p
    JOIN agence a2 ON p.idDestination = a2.idAgence
    GROUP BY p.idDepart, p.heureDepart, p.id_compagnie, a2.localite
    HAVING COUNT(*) = 1
) trajetUnique
    ON trajetUnique.idDepart = pv.id_agence
   AND trajetUnique.heureDepart = pv.id_horaire
   AND trajetUnique.id_compagnie = pv.id_compagnie
   AND trajetUnique.localite = pv.id_trajet
SET pv.id_agence_destination = trajetUnique.idDestination
WHERE pv.id_agence_destination IS NULL;

-- Diagnostic : lignes restées ambiguës après le backfill (destination avec plusieurs gares).
-- SELECT * FROM programmation_voyage WHERE id_agence_destination IS NULL;
