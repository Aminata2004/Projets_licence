<?php
function afficherBadgeStatus($status)
{
    switch ($status) {
        case 'enregistre':
            return '<span class="badge bg-primary">Prise en charge</span>';
        case 'en_cours':
            return '<span class="badge bg-warning">En cours</span>';
        case 'recu':
            return '<span class="badge bg-success">Colis reçu</span>';
        case 'livre':
            return '<span class="badge bg-info">Colis livré</span>';
        case null:
        case '':
            return '<span class="badge bg-secondary">En attente</span>';
        default:
            return '<span class="badge bg-danger">Inconnu</span>';
    }
}
?>
