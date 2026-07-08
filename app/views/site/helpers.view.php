<?php
function afficherBadgeStatus($status)
{
    switch ($status) {
        case 'enregistre':
            return '<span class="badge bg-primary">Prise en charge</span>';
        case 'en_cours':
            return '<span class="badge bg-warning">En attente</span>';
        case 'recu':
            return '<span class="badge bg-success">Reçu par la gare de destination</span>';
        case 'livre':
            return '<span class="badge bg-info">Livré</span>';
        case null:
        case '':
            return '<span class="badge bg-secondary">En attente</span>';
        default:
            return '<span class="badge bg-danger">Inconnu</span>';
    }
}

 function genererNumeroBillet() {
    // Préfixe fixe
    $prefix = "SMT";

    // Date au format : heure+minute+seconde+mois+jour
    $datePart = date("Hismd"); // H=heure, i=minute, s=seconde, m=mois, d=jour

    // Un petit suffixe aléatoire pour garantir l'unicité
    $randomPart = rand(100, 999); // 3 chiffres aléatoires

    // Concaténer le tout
    $numeroBillet = $prefix . $datePart . $randomPart;

    return $numeroBillet;
}
?>
