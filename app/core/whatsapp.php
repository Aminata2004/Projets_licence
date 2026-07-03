<?php

// Construit un lien wa.me (clic-pour-envoyer) : ouvre WhatsApp avec le message déjà rempli,
// l'agent clique lui-même sur "Envoyer". Aucun envoi automatique côté serveur.
function whatsapp_number(?string $numero): string
{
    $chiffres = preg_replace('/\D+/', '', (string)$numero);

    if ($chiffres === '') {
        return '';
    }

    // Numéro local malien (8 chiffres) sans indicatif -> on ajoute l'indicatif Mali
    if (strlen($chiffres) === 8) {
        $chiffres = '223' . $chiffres;
    }

    return $chiffres;
}

function whatsapp_link(?string $numero, string $message): string
{
    $chiffres = whatsapp_number($numero);

    if ($chiffres === '') {
        return '';
    }

    return 'https://wa.me/' . $chiffres . '?text=' . rawurlencode($message);
}
