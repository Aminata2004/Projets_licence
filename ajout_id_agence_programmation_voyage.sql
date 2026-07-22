-- programmation_voyage identifiait un car "de cette ville" via localite_user (nom de
-- ville uniquement), jamais par gare précise (numeroGare/idAgence). Or plusieurs gares
-- d'une même compagnie peuvent partager la même localité (ex. "Segou" Gare I et Gare II).
-- Résultat : si deux gares programment un car vers la même destination à la même heure
-- le même jour, la résolution du car dans Add_billet::saveBillets() (LIMIT 1 sur
-- localite_user) et le comptage des places vendues dans
-- Programmation_voyage::countPlacesVendues() pouvaient mélanger les deux gares.
--
-- id_agence lève l'ambiguïté : c'est désormais la clé d'appariement utilisée partout où
-- localite_user servait auparavant à identifier "le car de cette gare" (localite_user est
-- conservé pour l'affichage/compatibilité mais n'est plus la source de vérité).
--
-- statut permet d'annuler proprement le voyage d'une gare (ex. après un transfert de
-- passagers vers une autre gare) sans supprimer la ligne, pour préserver l'intégrité
-- référentielle avec l'historique des transferts et la traçabilité.
--
-- À exécuter une seule fois.

ALTER TABLE programmation_voyage
    ADD COLUMN id_agence INT NULL AFTER localite_user,
    ADD COLUMN statut ENUM('active', 'annulee') NOT NULL DEFAULT 'active' AFTER id_compagnie;

-- Backfill des lignes existantes par correspondance localite_user + id_compagnie -> agence.
-- Les lignes pour lesquelles la ville correspond à plusieurs gares restent NULL (ambiguës,
-- impossible de déduire laquelle a posteriori) : à corriger manuellement au cas par cas.
UPDATE programmation_voyage pv
JOIN (
    SELECT localite, id_compagnie
    FROM agence
    GROUP BY localite, id_compagnie
    HAVING COUNT(*) = 1
) villeUnique ON villeUnique.localite = pv.localite_user AND villeUnique.id_compagnie = pv.id_compagnie
JOIN agence a ON a.localite = pv.localite_user AND a.id_compagnie = pv.id_compagnie
SET pv.id_agence = a.idAgence
WHERE pv.id_agence IS NULL;

-- Diagnostic : lignes restées ambiguës après le backfill (ville avec plusieurs gares).
-- SELECT * FROM programmation_voyage WHERE id_agence IS NULL;
