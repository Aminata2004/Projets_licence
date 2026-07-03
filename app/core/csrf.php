<?php

// Protection CSRF minimale : un jeton par session, à inclure dans les formulaires sensibles
// avec csrf_field() et à vérifier à la réception avec csrf_verify().

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_verify(): bool
{
    $envoye = $_POST['csrf_token'] ?? '';
    $attendu = $_SESSION['csrf_token'] ?? '';

    return $envoye !== '' && $attendu !== '' && hash_equals($attendu, $envoye);
}
