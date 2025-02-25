-- phpMyAdmin SQL Dump
-- version 5.2.2-dev+20241218.440f6dd41bdeb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 22 jan. 2025 à 19:03
-- Version du serveur : 11.4.3-MariaDB-1
-- Version de PHP : 8.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `DragonX`
--
CREATE DATABASE IF NOT EXISTS `DragonX` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `ID` int(11) NOT NULL,
  `VALUE` varchar(50) NOT NULL,
  `PARENT_ID` int(11) DEFAULT NULL,
  `POSITION` int(11) NOT NULL,
  `LINK` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`ID`, `VALUE`, `PARENT_ID`, `POSITION`, `LINK`) VALUES
(1, 'ACTUS', NULL, 1, ''),
(2, 'PÔLES', NULL, 2, ''),
(3, 'EVENEMENTS', NULL, 3, ''),
(4, 'INSCRIPTION', NULL, 4, ''),
(5, 'Faire un don', NULL, 5, ''),
(6, 'CONTACT', NULL, 6, ''),
(7, 'Audiovisuel', 2, 1, ''),
(8, 'Média', 2, 2, ''),
(9, 'Musique', 2, 3, ''),
(10, 'Cinémantes', 3, 1, ''),
(11, 'Autres', 3, 2, '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
