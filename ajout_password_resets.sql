-- Table des jetons de réinitialisation de mot de passe.
-- Avant cette table, "mot de passe oublié" changeait le mot de passe en ne vérifiant que
-- la présence de l'email en base, sans jamais prouver que le demandeur possède cette boîte
-- mail (pas de code, pas de lien à usage unique) : n'importe qui connaissant un email pouvait
-- prendre le contrôle du compte correspondant. Voir Profils::mot_passe_oublie()/reset().
--
-- token_hash stocke un hash SHA-256 du jeton (jamais le jeton en clair, comme pour un mot de
-- passe) : le jeton en clair n'est envoyé que par email et n'existe jamais tel quel en base.

CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(250) NOT NULL,
    token_hash VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);
