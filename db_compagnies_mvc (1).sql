-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 03 sep. 2025 à 11:24
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_compagnies_mvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

CREATE TABLE `agence` (
  `idAgence` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `localite` varchar(250) NOT NULL,
  `numeroGare` varchar(11) NOT NULL,
  `tel` varchar(250) NOT NULL,
  `libele` varchar(149) NOT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`idAgence`, `code`, `localite`, `numeroGare`, `tel`, `libele`, `id_compagnie`) VALUES
(1, 88888, 'Segou', 'Gare I', '88 88 88 88', 'fdfddfdf', 1),
(2, 8888822, 'Segou', 'Gare II', '74 89 03 74', 'ssddfffhfhff', 1),
(3, 888881, 'Bamako', 'Gare1', '78 67 90 77', 'ssddfffhfhff', 1),
(4, 1881111, 'Koulikoro', 'Gare I', '74 89 03 73', 'ssddfffhfhff', 1),
(5, 432229, 'Kaye', 'Gare I', '74 89 03 79', 'ssddfffhfhff', 1);

-- --------------------------------------------------------

--
-- Structure de la table `billets`
--

CREATE TABLE `billets` (
  `idBillets` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `numeroBillets` varchar(200) NOT NULL,
  `jourVoyage` date NOT NULL,
  `Heur_departs` time NOT NULL,
  `nombrePassages` int(11) NOT NULL,
  `destinationId` varchar(240) NOT NULL,
  `departId` varchar(240) NOT NULL,
  `date_expiration` date NOT NULL,
  `numeroPlace` varchar(49) DEFAULT NULL,
  `date_reservation` date DEFAULT NULL,
  `status_billets` varchar(100) DEFAULT NULL,
  `status_reservation` varchar(80) NOT NULL,
  `validation_billets` varchar(100) DEFAULT NULL,
  `date_repporte` date DEFAULT NULL,
  `id_compagnie` int(11) NOT NULL,
  `delait_reservation` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `billets`
--

INSERT INTO `billets` (`idBillets`, `id_client`, `idUser`, `numeroBillets`, `jourVoyage`, `Heur_departs`, `nombrePassages`, `destinationId`, `departId`, `date_expiration`, `numeroPlace`, `date_reservation`, `status_billets`, `status_reservation`, `validation_billets`, `date_repporte`, `id_compagnie`, `delait_reservation`) VALUES
(1, 1, 5, 'SMT1116490903971', '2025-09-03', '23:00:00', 2, 'Bamako', 'Segou', '2025-09-10', '1-2', '2025-09-03', NULL, 'en_ligne', 'valider', NULL, 1, '2025-09-03 09:47:40'),
(2, 2, 5, 'SMT0921410903205', '2025-09-03', '23:00:00', 2, 'Bamako', 'Segou', '2025-09-10', '3-4', '2025-09-03', NULL, 'presentiel', 'valider', NULL, 1, NULL),
(3, 3, 5, 'SMT0922150903316', '2025-09-03', '23:00:00', 1, 'Fana', 'Segou', '2025-09-10', '5', '2025-09-03', NULL, 'presentiel', 'valider', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `car`
--

CREATE TABLE `car` (
  `id_car` int(11) NOT NULL,
  `numero_car` int(11) NOT NULL,
  `matriculle` varchar(100) NOT NULL,
  `nbr_place` int(11) NOT NULL,
  `nbr_place_reserve` int(11) DEFAULT NULL,
  `programmer_car` varchar(40) NOT NULL,
  `id_compagnie` int(11) NOT NULL,
  `status_car` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `car`
--

INSERT INTO `car` (`id_car`, `numero_car`, `matriculle`, `nbr_place`, `nbr_place_reserve`, `programmer_car`, `id_compagnie`, `status_car`) VALUES
(1, 201, 'Man2091', 40, 0, 'on', 1, 'Bamako'),
(2, 204, 'Manfd234', 30, 0, 'on', 1, 'Bamako'),
(3, 209, 'AerrnSWE', 60, 5, 'on', 1, 'Bamako');

-- --------------------------------------------------------

--
-- Structure de la table `chauffeur`
--

CREATE TABLE `chauffeur` (
  `id_chauffeur` int(11) NOT NULL,
  `nom_prenom` varchar(200) NOT NULL,
  `numero` varchar(40) NOT NULL,
  `id_car` int(11) NOT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chauffeur`
--

INSERT INTO `chauffeur` (`id_chauffeur`, `nom_prenom`, `numero`, `id_car`, `id_compagnie`) VALUES
(1, 'Sory', '78890987', 1, 1),
(2, 'Sidy', '7890877', 2, 1),
(3, 'Adou', '78087442', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `idClient` int(11) NOT NULL,
  `Client` varchar(250) NOT NULL,
  `numeroClient` varchar(250) DEFAULT NULL,
  `numeroPaiement` varchar(20) DEFAULT NULL,
  `emailClient` varchar(250) DEFAULT NULL,
  `codeQuere` varchar(250) DEFAULT NULL,
  `montant_payer` varchar(100) DEFAULT NULL,
  `date_enregistrement` date NOT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`idClient`, `Client`, `numeroClient`, `numeroPaiement`, `emailClient`, `codeQuere`, `montant_payer`, `date_enregistrement`, `id_compagnie`) VALUES
(1, 'yydddiallo', '90 90 90 99', '89 00 99 90', 'amitacompt90@gmail.com', NULL, '6 000', '2025-09-03', 1),
(2, 'yydddiallo', NULL, NULL, NULL, NULL, '6 000 FCFA', '2025-09-03', 1),
(3, 'yydddiallo', NULL, NULL, NULL, NULL, '2 000 FCFA', '2025-09-03', 1);

-- --------------------------------------------------------

--
-- Structure de la table `colis`
--

CREATE TABLE `colis` (
  `id_colis` int(11) NOT NULL,
  `nom_colis` varchar(100) DEFAULT NULL,
  `nature` varchar(100) DEFAULT NULL,
  `provient_de` varchar(100) DEFAULT NULL,
  `id_agence` int(11) DEFAULT NULL,
  `valeur` int(11) DEFAULT NULL,
  `fraix_transaction` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_expediteur` int(11) DEFAULT NULL,
  `id_destinataire` int(11) DEFAULT NULL,
  `date_enregistrement` date DEFAULT NULL,
  `code_colis` varchar(255) DEFAULT NULL,
  `num_gare` varchar(100) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `date_livraison` date DEFAULT NULL,
  `reclamer` int(11) DEFAULT NULL,
  `date_reclamer` date DEFAULT NULL,
  `livre` varchar(255) DEFAULT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `colis`
--

INSERT INTO `colis` (`id_colis`, `nom_colis`, `nature`, `provient_de`, `id_agence`, `valeur`, `fraix_transaction`, `id_utilisateur`, `id_expediteur`, `id_destinataire`, `date_enregistrement`, `code_colis`, `num_gare`, `status`, `date_livraison`, `reclamer`, `date_reclamer`, `livre`, `id_compagnie`) VALUES
(1, 'colis', ' nature', 'Bamako', 2, 100, 1000, 4, 1, 1, '2025-08-20', 'PSXSN7', 'Gare I', 'enregistre', NULL, NULL, NULL, NULL, 1),
(2, 'colis', ' nature', 'Bamako', 2, 100, 1000, 4, 2, 2, '2025-08-20', '3SAP64', 'Gare I', 'enregistre', NULL, NULL, NULL, NULL, 1),
(3, 'colis', ' nature', 'Bamako', 2, 111, 1000, 4, 3, 3, '2025-08-20', '4SFGU9', 'Gare I', 'enregistre', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `compagnie`
--

CREATE TABLE `compagnie` (
  `id_compagnie` int(11) NOT NULL,
  `nom_compagnie` varchar(255) NOT NULL,
  `libele` varchar(255) NOT NULL,
  `slogant` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compagnie`
--

INSERT INTO `compagnie` (`id_compagnie`, `nom_compagnie`, `libele`, `slogant`, `logo`) VALUES
(1, 'Air-niono', 'air-niono fdssdsldldoifd', 'le transport est notre affaire', 'logo_68b1a04d7de5a3.90273593.jpg'),
(2, 'Somatra', 'somatrafdgfghgfhhhg', 'le transport est notre affaire', 'logo_68b1a10d8629f4.70048161.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `destinataires`
--

CREATE TABLE `destinataires` (
  `id_destinataire` int(11) NOT NULL,
  `destinataire` varchar(100) DEFAULT NULL,
  `numero_dest` varchar(100) DEFAULT NULL,
  `email_dest` varchar(255) DEFAULT NULL,
  `id_exp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `destinataires`
--

INSERT INTO `destinataires` (`id_destinataire`, `destinataire`, `numero_dest`, `email_dest`, `id_exp`) VALUES
(1, 'yyy diallo', '90 89 89 89', 'tangarazarahou@gmail.com', 1),
(2, 'yyy diallo', '90 89 89 89', 'tangarazarahou@gmail.com', 2),
(3, 'yyy diallo', '90 89 89 89', 'tangarazarahou@gmail.com', 3);

-- --------------------------------------------------------

--
-- Structure de la table `envoi`
--

CREATE TABLE `envoi` (
  `id_envoi` int(11) NOT NULL,
  `id_coli` varchar(100) DEFAULT NULL,
  `id_car` int(11) DEFAULT NULL,
  `date_enregistre` datetime DEFAULT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `envoi`
--

INSERT INTO `envoi` (`id_envoi`, `id_coli`, `id_car`, `date_enregistre`, `id_compagnie`) VALUES
(1, '2', 202, '2025-08-12 15:24:47', 1),
(2, '1', 202, '2025-08-12 15:24:47', 1),
(3, '3', 202, '2025-08-15 10:28:33', 1),
(4, '2', 202, '2025-08-15 10:28:33', 1),
(5, '1', 202, '2025-08-15 10:28:33', 1);

-- --------------------------------------------------------

--
-- Structure de la table `escale`
--

CREATE TABLE `escale` (
  `id_escale` int(11) NOT NULL,
  `escales` varchar(255) NOT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `escale`
--

INSERT INTO `escale` (`id_escale`, `escales`, `id_compagnie`) VALUES
(1, 'Fana', 1),
(2, 'Fana', 2),
(3, 'Niono', 2),
(4, 'Diola', 1);

-- --------------------------------------------------------

--
-- Structure de la table `expediteurs`
--

CREATE TABLE `expediteurs` (
  `id_expediteur` int(11) NOT NULL,
  `expediteur` varchar(100) DEFAULT NULL,
  `numero_exp` varchar(100) DEFAULT NULL,
  `email_exp` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `expediteurs`
--

INSERT INTO `expediteurs` (`id_expediteur`, `expediteur`, `numero_exp`, `email_exp`) VALUES
(1, 'cccc ami', '89 78 09 89', 'rokhayadjire@gmail.com'),
(2, 'Ami diallo', '89 78 09 89', 'rokhayadjire@gmail.com'),
(3, 'cccc ami', '89 78 09 89', 'rokhayadjire@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

CREATE TABLE `horaire` (
  `id_heure` int(11) NOT NULL,
  `heuredepart` time NOT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `horaire`
--

INSERT INTO `horaire` (`id_heure`, `heuredepart`, `id_compagnie`) VALUES
(1, '05:00:00', 1),
(2, '06:00:00', 1),
(3, '07:00:00', 1),
(4, '10:00:00', 1),
(5, '11:00:00', 1),
(6, '14:00:00', 1),
(7, '16:00:00', 1),
(8, '17:00:00', 1),
(9, '19:00:00', 1),
(10, '20:00:00', 1),
(11, '23:00:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `liaison_car_trajet`
--

CREATE TABLE `liaison_car_trajet` (
  `id_liaison` int(11) NOT NULL,
  `id_car` int(11) NOT NULL,
  `id_trajets` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `liaison_car_trajet`
--

INSERT INTO `liaison_car_trajet` (`id_liaison`, `id_car`, `id_trajets`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 4),
(6, 2, 5),
(7, 3, 1),
(8, 3, 3),
(9, 1, 3),
(10, 1, 1),
(11, 2, 2),
(12, 2, 1),
(13, 2, 5),
(14, 3, 2),
(15, 3, 3),
(16, 3, 1),
(17, 3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `ligneTrajet`
--

CREATE TABLE `ligneTrajet` (
  `id_trajet_ligne` int(11) NOT NULL,
  `id_escales` int(11) NOT NULL,
  `id_trajets` int(11) NOT NULL,
  `prix_escale` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ligneTrajet`
--

INSERT INTO `ligneTrajet` (`id_trajet_ligne`, `id_escales`, `id_trajets`, `prix_escale`) VALUES
(1, 1, 1, 2000),
(2, 4, 1, 2400);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_envoi`
--

CREATE TABLE `ligne_envoi` (
  `id_ligne_envoi` int(11) NOT NULL,
  `numero_car` int(11) NOT NULL,
  `dates` datetime NOT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `place_minumale`
--

CREATE TABLE `place_minumale` (
  `id_place_minumale` int(11) NOT NULL,
  `place_minumale` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `place_minumale`
--

INSERT INTO `place_minumale` (`id_place_minumale`, `place_minumale`) VALUES
(1, 15);

-- --------------------------------------------------------

--
-- Structure de la table `programmation_voyage`
--

CREATE TABLE `programmation_voyage` (
  `id_programmation` int(11) NOT NULL,
  `id_car_programmer` int(11) NOT NULL,
  `id_horaire` time NOT NULL,
  `id_trajet` varchar(100) NOT NULL,
  `localite_user` varchar(100) DEFAULT NULL,
  `date_enregistre` date NOT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `programmation_voyage`
--

INSERT INTO `programmation_voyage` (`id_programmation`, `id_car_programmer`, `id_horaire`, `id_trajet`, `localite_user`, `date_enregistre`, `id_compagnie`) VALUES
(1, 201, '23:00:00', 'Bamako', 'Segou', '2025-09-02', 1),
(2, 204, '20:00:00', 'Kaye', 'Segou', '2025-09-02', 1),
(3, 209, '19:00:00', 'Bamako', 'Segou', '2025-09-02', 1),
(4, 201, '20:00:00', 'Bamako', 'Segou', '2025-09-03', 1),
(5, 204, '19:00:00', 'Bamako', 'Segou', '2025-09-03', 1),
(6, 209, '23:00:00', 'Bamako', 'Segou', '2025-09-03', 1);

-- --------------------------------------------------------

--
-- Structure de la table `programmer`
--

CREATE TABLE `programmer` (
  `idProgrammer` int(11) NOT NULL,
  `idDepart` varchar(250) NOT NULL,
  `idDestination` varchar(250) NOT NULL,
  `heureDepart` time NOT NULL,
  `rdv` time NOT NULL,
  `prix` int(11) NOT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `programmer`
--

INSERT INTO `programmer` (`idProgrammer`, `idDepart`, `idDestination`, `heureDepart`, `rdv`, `prix`, `id_compagnie`) VALUES
(1, 'Segou', 'Bamako', '23:00:00', '22:15:00', 3000, 1);

-- --------------------------------------------------------

--
-- Structure de la table `reference_car`
--

CREATE TABLE `reference_car` (
  `id_reference` int(11) NOT NULL,
  `id_car` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reference_car`
--

INSERT INTO `reference_car` (`id_reference`, `id_car`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL,
  `nom_prenom` varchar(250) NOT NULL,
  `idDepart` varchar(250) NOT NULL,
  `idDestination` varchar(250) NOT NULL,
  `nombrePassager` int(11) NOT NULL,
  `date` date NOT NULL,
  `horaire` time NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `email` varchar(250) NOT NULL,
  `id_compagnie` int(11) NOT NULL,
  `prix` double NOT NULL,
  `prixTotal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `suivis`
--

CREATE TABLE `suivis` (
  `idSuivis` int(11) NOT NULL,
  `place_totals` int(11) NOT NULL,
  `place_reserve` int(11) NOT NULL,
  `destination` varchar(240) NOT NULL,
  `heur_depart` time NOT NULL,
  `date_reservation` date NOT NULL,
  `depart` varchar(100) NOT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trajet`
--

CREATE TABLE `trajet` (
  `idTrajet` int(11) NOT NULL,
  `depart` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `id_compagnie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trajet`
--

INSERT INTO `trajet` (`idTrajet`, `depart`, `destination`, `id_compagnie`) VALUES
(1, 'Segou', 'Bamako', 1),
(2, 'Bamako', 'Koulikoro', 1),
(3, 'Kaye', 'Bamako', 1),
(4, 'Segou', 'Kaye', 1),
(5, 'Segou', 'Koulikoro', 1),
(6, 'Koulikoro', 'Kaye', 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idUser` int(11) NOT NULL,
  `utilisateurs` varchar(250) NOT NULL,
  `droit` varchar(250) NOT NULL,
  `contact` int(11) DEFAULT NULL,
  `motPasse` varchar(100) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `emailUser` varchar(250) NOT NULL,
  `id_agence` varchar(100) DEFAULT NULL,
  `id_compagnie` varchar(100) DEFAULT NULL,
  `profile` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUser`, `utilisateurs`, `droit`, `contact`, `motPasse`, `status`, `emailUser`, `id_agence`, `id_compagnie`, `profile`) VALUES
(1, 'Aminata diallo', 'super_admin', 90249438, '$2y$12$P2DQY37kv0nqRTZrfN2kEuwWCdnu7DRLGeuocbWDPKRDCX7j/aGSy', 1, 'amitacompt90@gmail.com', NULL, NULL, NULL),
(3, 'Zara ', 'Admin', NULL, '$2y$12$P2DQY37kv0nqRTZrfN2kEuwWCdnu7DRLGeuocbWDPKRDCX7j/aGSy', 1, 'zara@gmail.com', NULL, '1', NULL),
(4, 'zeina', 'Admin', NULL, '$2y$10$O6DTJmH0y/VXR552i9V8OOFT/Y23yxmJxSqb7I5QWnWZKBy2QESWe', 1, 'zei@gmail.com', NULL, '2', NULL),
(5, 'Rokiatou Djire', 'Admin_regionale', NULL, '$2y$10$kEIEBuUKQdfcXmyjxqiC8Ob7AT1jN52DMXKrHspfgWhCtbfvbVNZC', 1, 'rokia@gmail.com', '1', '1', NULL),
(6, 'Oumar', 'Admin_regionale', NULL, '$2y$10$XDzwMYBrxSiX4.hdcVPhxu5jE/WeZteZu0LfhdsuORlhzMTomTPfS', 1, 'oumar@gmail.com', '3', '1', NULL),
(7, 'Oumou', 'Utilisateur', NULL, '$2y$10$jQEgl61RLOrCkOZN0Qa.NuC/lxpis7YD7bARC4zKMIzD/FeSIzESe', 1, 'oumou@gmail.com', '2', '1', NULL),
(8, 'Hawa', 'Utilisateur', NULL, '$2y$10$j3nynxsZHQnLGZYsYmdGV.uecPL4lQaGMfrP7/jcKXOaE3I34nSR2', 1, 'awa@gmail.com', '3', '1', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agence`
--
ALTER TABLE `agence`
  ADD PRIMARY KEY (`idAgence`);

--
-- Index pour la table `billets`
--
ALTER TABLE `billets`
  ADD PRIMARY KEY (`idBillets`);

--
-- Index pour la table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id_car`);

--
-- Index pour la table `chauffeur`
--
ALTER TABLE `chauffeur`
  ADD PRIMARY KEY (`id_chauffeur`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`idClient`);

--
-- Index pour la table `colis`
--
ALTER TABLE `colis`
  ADD PRIMARY KEY (`id_colis`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_destinataire` (`id_destinataire`),
  ADD KEY `id_expediteur` (`id_expediteur`);

--
-- Index pour la table `compagnie`
--
ALTER TABLE `compagnie`
  ADD PRIMARY KEY (`id_compagnie`);

--
-- Index pour la table `destinataires`
--
ALTER TABLE `destinataires`
  ADD PRIMARY KEY (`id_destinataire`),
  ADD KEY `id_exp` (`id_exp`);

--
-- Index pour la table `envoi`
--
ALTER TABLE `envoi`
  ADD PRIMARY KEY (`id_envoi`);

--
-- Index pour la table `escale`
--
ALTER TABLE `escale`
  ADD PRIMARY KEY (`id_escale`);

--
-- Index pour la table `expediteurs`
--
ALTER TABLE `expediteurs`
  ADD PRIMARY KEY (`id_expediteur`);

--
-- Index pour la table `horaire`
--
ALTER TABLE `horaire`
  ADD PRIMARY KEY (`id_heure`);

--
-- Index pour la table `liaison_car_trajet`
--
ALTER TABLE `liaison_car_trajet`
  ADD PRIMARY KEY (`id_liaison`);

--
-- Index pour la table `ligneTrajet`
--
ALTER TABLE `ligneTrajet`
  ADD PRIMARY KEY (`id_trajet_ligne`);

--
-- Index pour la table `ligne_envoi`
--
ALTER TABLE `ligne_envoi`
  ADD PRIMARY KEY (`id_ligne_envoi`);

--
-- Index pour la table `place_minumale`
--
ALTER TABLE `place_minumale`
  ADD PRIMARY KEY (`id_place_minumale`);

--
-- Index pour la table `programmation_voyage`
--
ALTER TABLE `programmation_voyage`
  ADD PRIMARY KEY (`id_programmation`);

--
-- Index pour la table `programmer`
--
ALTER TABLE `programmer`
  ADD PRIMARY KEY (`idProgrammer`);

--
-- Index pour la table `reference_car`
--
ALTER TABLE `reference_car`
  ADD PRIMARY KEY (`id_reference`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`);

--
-- Index pour la table `suivis`
--
ALTER TABLE `suivis`
  ADD PRIMARY KEY (`idSuivis`);

--
-- Index pour la table `trajet`
--
ALTER TABLE `trajet`
  ADD PRIMARY KEY (`idTrajet`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agence`
--
ALTER TABLE `agence`
  MODIFY `idAgence` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `billets`
--
ALTER TABLE `billets`
  MODIFY `idBillets` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `car`
--
ALTER TABLE `car`
  MODIFY `id_car` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `chauffeur`
--
ALTER TABLE `chauffeur`
  MODIFY `id_chauffeur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `idClient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `colis`
--
ALTER TABLE `colis`
  MODIFY `id_colis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `compagnie`
--
ALTER TABLE `compagnie`
  MODIFY `id_compagnie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `destinataires`
--
ALTER TABLE `destinataires`
  MODIFY `id_destinataire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `envoi`
--
ALTER TABLE `envoi`
  MODIFY `id_envoi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `escale`
--
ALTER TABLE `escale`
  MODIFY `id_escale` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `expediteurs`
--
ALTER TABLE `expediteurs`
  MODIFY `id_expediteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `horaire`
--
ALTER TABLE `horaire`
  MODIFY `id_heure` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `liaison_car_trajet`
--
ALTER TABLE `liaison_car_trajet`
  MODIFY `id_liaison` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `ligneTrajet`
--
ALTER TABLE `ligneTrajet`
  MODIFY `id_trajet_ligne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `ligne_envoi`
--
ALTER TABLE `ligne_envoi`
  MODIFY `id_ligne_envoi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `place_minumale`
--
ALTER TABLE `place_minumale`
  MODIFY `id_place_minumale` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `programmation_voyage`
--
ALTER TABLE `programmation_voyage`
  MODIFY `id_programmation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `programmer`
--
ALTER TABLE `programmer`
  MODIFY `idProgrammer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `reference_car`
--
ALTER TABLE `reference_car`
  MODIFY `id_reference` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `suivis`
--
ALTER TABLE `suivis`
  MODIFY `idSuivis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trajet`
--
ALTER TABLE `trajet`
  MODIFY `idTrajet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `destinataires`
--
ALTER TABLE `destinataires`
  ADD CONSTRAINT `destinataires_ibfk_1` FOREIGN KEY (`id_exp`) REFERENCES `expediteurs` (`id_expediteur`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
