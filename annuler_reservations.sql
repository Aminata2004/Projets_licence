-- Annulation automatique des réservations en ligne non validées dans les 30 minutes
-- (delait_reservation dépassé) + restauration des places réservées.
-- Exécuté chaque minute par cron (voir crontab -l).
--
-- Les deux UPDATE de restauration sont pré-agrégés (SUM ... GROUP BY) avant la
-- soustraction : un UPDATE multi-tables qui joint plusieurs billets expirés sur la
-- même ligne car/suivis n'applique de façon fiable que la dernière soustraction
-- rencontrée, pas la somme des deux — d'où l'agrégation préalable.

-- 1) Restaurer les places dans le car déjà programmé (billets pour aujourd'hui)
UPDATE car c
JOIN (
    SELECT p.id_car_programmer AS id_car, SUM(b.nombrePassages) AS total_annule
    FROM billets b
    JOIN programmation_voyage p
        ON b.departId = p.localite_user
        AND b.jourVoyage = p.date_enregistre
        AND b.Heur_departs = p.id_horaire
        AND b.id_compagnie = p.id_compagnie
    WHERE b.validation_billets = 'en_attente'
      AND b.delait_reservation <= NOW()
    GROUP BY p.id_car_programmer
) expired ON c.id_car = expired.id_car
SET c.nbr_place_reserve = GREATEST(c.nbr_place_reserve - expired.total_annule, 0);

-- 2) Restaurer le compteur suivis pour les réservations de demain (car pas encore programmé)
UPDATE suivis s
JOIN (
    SELECT b.departId AS depart, b.jourVoyage AS date_reservation, b.Heur_departs AS heur_depart,
           b.id_compagnie, SUM(b.nombrePassages) AS total_annule
    FROM billets b
    WHERE b.validation_billets = 'en_attente'
      AND b.delait_reservation <= NOW()
      AND b.jourVoyage > CURDATE()
    GROUP BY b.departId, b.jourVoyage, b.Heur_departs, b.id_compagnie
) expired
    ON s.depart = expired.depart
    AND s.date_reservation = expired.date_reservation
    AND s.heur_depart = expired.heur_depart
    AND s.id_compagnie = expired.id_compagnie
SET s.place_reserve = GREATEST(s.place_reserve - expired.total_annule, 0);

-- 3) Marquer les billets expirés comme annulés (en dernier, une fois les places restaurées)
UPDATE billets
SET validation_billets = 'annule'
WHERE validation_billets = 'en_attente'
  AND delait_reservation <= NOW();
