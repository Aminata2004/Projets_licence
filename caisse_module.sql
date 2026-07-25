-- ============================================================
-- Module de gestion de caisse individuelle
-- Auteur : Antigravity – 2026
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- 1. Caisse individuelle par utilisateur et par journée
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `caisse_utilisateur` (
  `id_caisse_user`  INT(11)         NOT NULL AUTO_INCREMENT,
  `id_utilisateur`  INT(11)         NOT NULL,
  `id_agence`       INT(11)         NOT NULL,
  `id_compagnie`    INT(11)         NOT NULL,
  `date_service`    DATE            NOT NULL,
  `heure_ouverture` DATETIME        NOT NULL,
  `heure_fermeture` DATETIME        DEFAULT NULL,
  `montant_initial` DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
  `total_billets`   DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
  `total_colis`     DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
  `nb_billets`      INT(11)         NOT NULL DEFAULT 0,
  `nb_colis`        INT(11)         NOT NULL DEFAULT 0,
  `montant_compte`  DECIMAL(12,2)   DEFAULT NULL,
  `ecart`           DECIMAL(12,2)   DEFAULT NULL,
  `statut`          ENUM('ouverte','fermee','versee') NOT NULL DEFAULT 'ouverte',
  `reference`       VARCHAR(40)     NOT NULL,
  PRIMARY KEY (`id_caisse_user`),
  UNIQUE KEY `reference_unique` (`reference`),
  -- Un utilisateur ne peut avoir qu'une seule caisse ouverte par jour
  UNIQUE KEY `user_date_active` (`id_utilisateur`, `date_service`, `statut`),
  KEY `idx_utilisateur` (`id_utilisateur`),
  KEY `idx_agence` (`id_agence`),
  KEY `idx_date_service` (`date_service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 2. Versements (opérateur → chef d'escale)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `versements_caisse` (
  `id_versement`    INT(11)         NOT NULL AUTO_INCREMENT,
  `id_caisse_user`  INT(11)         NOT NULL,
  `id_emetteur`     INT(11)         NOT NULL,
  `id_chef_escale`  INT(11)         NOT NULL,
  `id_agence`       INT(11)         NOT NULL,
  `id_compagnie`    INT(11)         NOT NULL,
  `montant`         DECIMAL(12,2)   NOT NULL,
  `date_versement`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut`          ENUM('en_attente','valide','rejete') NOT NULL DEFAULT 'en_attente',
  `commentaire`     TEXT            DEFAULT NULL,
  `date_validation` DATETIME        DEFAULT NULL,
  PRIMARY KEY (`id_versement`),
  KEY `idx_caisse_user` (`id_caisse_user`),
  KEY `idx_chef_escale` (`id_chef_escale`),
  KEY `idx_agence` (`id_agence`),
  KEY `idx_statut` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 3. Journal de toutes les opérations (traçabilité)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `journal_caisse` (
  `id_journal`      INT(11)         NOT NULL AUTO_INCREMENT,
  `id_caisse_user`  INT(11)         NOT NULL,
  `id_utilisateur`  INT(11)         NOT NULL,
  `type_operation`  ENUM('ouverture','billet','colis','fermeture','versement','annulation') NOT NULL,
  `reference_op`    VARCHAR(100)    DEFAULT NULL,
  `montant`         DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
  `libelle`         VARCHAR(255)    DEFAULT NULL,
  `date_heure`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_journal`),
  KEY `idx_caisse_user` (`id_caisse_user`),
  KEY `idx_utilisateur` (`id_utilisateur`),
  KEY `idx_date_heure` (`date_heure`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 4. Clôtures d'escale (chef d'escale)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `clotures_escale` (
  `id_cloture`      INT(11)         NOT NULL AUTO_INCREMENT,
  `id_chef_escale`  INT(11)         NOT NULL,
  `id_agence`       INT(11)         NOT NULL,
  `id_compagnie`    INT(11)         NOT NULL,
  `date_cloture`    DATE            NOT NULL,
  `total_billets`   DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
  `total_colis`     DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
  `total_versements`DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
  `total_ecarts`    DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
  `statut`          ENUM('brouillon','validee') NOT NULL DEFAULT 'brouillon',
  `rapport_json`    TEXT            DEFAULT NULL,
  `date_creation`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cloture`),
  KEY `idx_agence` (`id_agence`),
  KEY `idx_date_cloture` (`date_cloture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 5. Ajout de id_caisse_user aux tables existantes
--    (rattachement de chaque opération à la caisse individuelle)
-- --------------------------------------------------------
ALTER TABLE `billets`
  ADD COLUMN IF NOT EXISTS `id_caisse_user` INT(11) DEFAULT NULL;

ALTER TABLE `colis`
  ADD COLUMN IF NOT EXISTS `id_caisse_user` INT(11) DEFAULT NULL;

COMMIT;
