-- Empêche les doublons de numéro de billet (numeroBillets), qui n'était protégé par
-- aucune contrainte : deux ventes simultanées à la même seconde pouvaient générer le
-- même numéro (timestamp + rand(100,999) côté client, jamais revérifié côté serveur
-- avant cette correction — voir Add_billet::genererNumeroBilletUnique()).
--
-- À exécuter une seule fois. Si l'ALTER TABLE échoue avec une erreur de doublon,
-- exécuter d'abord la requête de diagnostic ci-dessous pour identifier et corriger
-- les lignes en conflit avant de relancer l'ALTER TABLE :
--
-- SELECT numeroBillets, COUNT(*) FROM billets GROUP BY numeroBillets HAVING COUNT(*) > 1;

ALTER TABLE billets ADD UNIQUE KEY uq_numeroBillets (numeroBillets);
