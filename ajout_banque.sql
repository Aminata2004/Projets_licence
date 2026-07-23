-- Fonctionnalité "Dépôt en banque" : en fin de journée, l'admin peut demander au chef
-- d'escale de déposer une partie de la recette de sa caisse dans un compte banque de la
-- compagnie. Workflow à deux temps, sur demande explicite de l'utilisateur : le chef
-- d'escale crée une demande (en_attente), l'argent ne sort de la caisse qu'après
-- confirmation par un Admin/super_admin (confirme) ; l'admin peut aussi la rejeter
-- (rejete), auquel cas rien ne bouge financièrement.
--
-- À exécuter une seule fois.

CREATE TABLE banques (
  id_banque INT AUTO_INCREMENT PRIMARY KEY,
  id_compagnie INT NOT NULL,
  nom VARCHAR(100) NOT NULL,
  numero_compte VARCHAR(50) NULL,
  solde DECIMAL(12,2) NOT NULL DEFAULT 0,
  statut ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE depots_banque (
  id_depot INT AUTO_INCREMENT PRIMARY KEY,
  id_compagnie INT NOT NULL,
  id_agence INT NOT NULL,
  id_caisse INT NOT NULL,
  id_banque INT NOT NULL,
  montant DECIMAL(12,2) NOT NULL,
  reference VARCHAR(100) NULL,
  statut ENUM('en_attente', 'confirme', 'rejete') NOT NULL DEFAULT 'en_attente',
  motif_rejet VARCHAR(255) NULL,
  id_utilisateur_demandeur INT NOT NULL,
  id_utilisateur_validateur INT NULL,
  date_demande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_validation DATETIME NULL,
  FOREIGN KEY (id_banque) REFERENCES banques(id_banque)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
