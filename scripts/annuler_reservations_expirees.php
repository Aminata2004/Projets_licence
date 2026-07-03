<?php

// Tâche planifiée (cron) : annule les réservations en ligne non validées dans les 30 minutes
// et libère les places correspondantes. Reprend la logique de annuler_reservations.sql.
// Usage : php scripts/annuler_reservations_expirees.php

define('ROOT', dirname(__DIR__));

require_once ROOT . '/app/core/env.php';
require_once ROOT . '/app/core/config.php';
require_once ROOT . '/app/core/database.php';

$db = (new Database())->connect();

$db->beginTransaction();

try {
    // 1) Libère les places du car pour les billets d'aujourd'hui expirés
    $stmt1 = $db->prepare("
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
            AND b.delait_reservation <= NOW()
    ");
    $stmt1->execute();
    $annulesAujourdhui = $stmt1->rowCount();

    // 2) Libère les places de la file d'attente (suivis) pour les billets de demain ou plus tard
    $stmt2 = $db->prepare("
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
            AND b.jourVoyage > CURDATE()
    ");
    $stmt2->execute();
    $annulesAVenir = $stmt2->rowCount();

    $db->commit();

    $ligne = sprintf(
        "[%s] Réservations expirées annulées : %d (aujourd'hui) + %d (à venir)\n",
        date('Y-m-d H:i:s'),
        $annulesAujourdhui,
        $annulesAVenir
    );
    file_put_contents(ROOT . '/logs/annulation.log', $ligne, FILE_APPEND);
    echo $ligne;
} catch (Throwable $e) {
    $db->rollBack();
    $erreur = sprintf("[%s] ERREUR annulation réservations : %s\n", date('Y-m-d H:i:s'), $e->getMessage());
    file_put_contents(ROOT . '/logs/annulation.log', $erreur, FILE_APPEND);
    fwrite(STDERR, $erreur);
    exit(1);
}
