

-- UPDATE billets
-- SET validation_billets = 'annule'
-- WHERE validation_billets = 'en_attente'
--   AND delait_reservation <= NOW();





-- Mettre à jour les billets expirés et restaurer les places dans le car correspondant
-- UPDATE billets b
-- JOIN programmation_voyage p
    
--     On b.departId = p.localite_user
--     AND b.jourVoyage = p.date_enregistre
--     AND b.Heur_departs = p.id_horaire
-- JOIN car c
--     ON p.id_car_programmer = c.numero_car
-- SET 
--     b.validation_billets = 'annule',
--     c.nbr_place_reserve = c.nbr_place_reserve -  b.nombrePassages
-- WHERE 
--     b.validation_billets = 'en_attente'
--     AND b.delait_reservation <= NOW();


UPDATE billets b
JOIN programmation_voyage p
    ON b.departId = p.localite_user
    AND b.jourVoyage = p.date_enregistre
    AND b.Heur_departs = p.id_horaire
JOIN car c
    ON p.id_car_programmer = c.numero_car
    AND b.id_compagnie = c.id_compagnie
SET 
    b.validation_billets = 'annule',
    c.nbr_place_reserve = c.nbr_place_reserve - b.nombrePassages
WHERE 
    b.validation_billets = 'en_attente'
    AND b.delait_reservation <= NOW();


    UPDATE suivis s
JOIN billets b
    ON b.departId = s.depart
    AND b.jourVoyage = s.date_reservation
    AND b.Heur_departs = s.heur_depart
    AND b.id_compagnie = s.id_compagnie
SET 
    s.place_reserve = s.place_reserve - b.nombrePassages,
    b.validation_billets = 'annule'
WHERE 
    b.validation_billets = 'en_attente'
    AND b.delait_reservation <= NOW()
    AND b.jourVoyage > CURDATE(); -- billets pour demain ou plus tard


