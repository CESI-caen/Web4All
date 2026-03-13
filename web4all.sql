-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 13 mars 2026 à 13:40
-- Version du serveur : 8.0.45-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `web4all`
--

-- --------------------------------------------------------

--
-- Structure de la table `Commenter`
--

CREATE TABLE `Commenter` (
  `Id_utilisateur` int NOT NULL,
  `Id_offre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Créer`
--

CREATE TABLE `Créer` (
  `Id_utilisateur` int NOT NULL,
  `Id_offre` int NOT NULL,
  `Id_entreprise` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Entreprises`
--

CREATE TABLE `Entreprises` (
  `Id_entreprise` int NOT NULL,
  `Nom_entreprise` varchar(50) NOT NULL,
  `Domaine` varchar(50) NOT NULL,
  `Email_RH` varchar(50) NOT NULL,
  `Telephone_entreprise` varchar(50) NOT NULL,
  `Descriptif` varchar(50) NOT NULL,
  `Id_ville` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Offres`
--

CREATE TABLE `Offres` (
  `Id_offre` int NOT NULL,
  `Descriptif` text,
  `Debut` date NOT NULL,
  `Fin` date NOT NULL,
  `Duree` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Postuler`
--

CREATE TABLE `Postuler` (
  `Id_utilisateur` int NOT NULL,
  `Id_offre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Types`
--

CREATE TABLE `Types` (
  `Id_type` int NOT NULL,
  `Nom_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateurs`
--

CREATE TABLE `Utilisateurs` (
  `Id_utilisateur` int NOT NULL,
  `Nom_utilisateurs` varchar(50) NOT NULL,
  `Prenom_utilisateurs` varchar(50) NOT NULL,
  `Email_utilisateurs` varchar(100) NOT NULL,
  `mot_de_passe` varchar(50) NOT NULL,
  `Telephone_utilisateurs` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Id_ville` varchar(50) NOT NULL,
  `Id_type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Villes`
--

CREATE TABLE `Villes` (
  `Id_ville` varchar(50) NOT NULL,
  `Nom_ville` varchar(50) NOT NULL,
  `CP` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Vouloir`
--

CREATE TABLE `Vouloir` (
  `Id_utilisateur` int NOT NULL,
  `Id_offre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Commenter`
--
ALTER TABLE `Commenter`
  ADD PRIMARY KEY (`Id_utilisateur`,`Id_offre`),
  ADD KEY `Id_offre` (`Id_offre`);

--
-- Index pour la table `Créer`
--
ALTER TABLE `Créer`
  ADD PRIMARY KEY (`Id_utilisateur`,`Id_offre`,`Id_entreprise`),
  ADD KEY `Id_offre` (`Id_offre`),
  ADD KEY `Id_entreprise` (`Id_entreprise`);

--
-- Index pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  ADD PRIMARY KEY (`Id_entreprise`),
  ADD KEY `Id_ville` (`Id_ville`);

--
-- Index pour la table `Offres`
--
ALTER TABLE `Offres`
  ADD PRIMARY KEY (`Id_offre`);

--
-- Index pour la table `Postuler`
--
ALTER TABLE `Postuler`
  ADD PRIMARY KEY (`Id_utilisateur`,`Id_offre`),
  ADD KEY `Id_offre` (`Id_offre`);

--
-- Index pour la table `Types`
--
ALTER TABLE `Types`
  ADD PRIMARY KEY (`Id_type`);

--
-- Index pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`Id_utilisateur`),
  ADD KEY `Id_ville` (`Id_ville`),
  ADD KEY `Id_type` (`Id_type`);

--
-- Index pour la table `Villes`
--
ALTER TABLE `Villes`
  ADD PRIMARY KEY (`Id_ville`);

--
-- Index pour la table `Vouloir`
--
ALTER TABLE `Vouloir`
  ADD PRIMARY KEY (`Id_utilisateur`,`Id_offre`),
  ADD KEY `Id_offre` (`Id_offre`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Commenter`
--
ALTER TABLE `Commenter`
  ADD CONSTRAINT `Commenter_ibfk_1` FOREIGN KEY (`Id_utilisateur`) REFERENCES `Utilisateurs` (`Id_utilisateur`),
  ADD CONSTRAINT `Commenter_ibfk_2` FOREIGN KEY (`Id_offre`) REFERENCES `Offres` (`Id_offre`);

--
-- Contraintes pour la table `Créer`
--
ALTER TABLE `Créer`
  ADD CONSTRAINT `Créer_ibfk_1` FOREIGN KEY (`Id_utilisateur`) REFERENCES `Utilisateurs` (`Id_utilisateur`),
  ADD CONSTRAINT `Créer_ibfk_2` FOREIGN KEY (`Id_offre`) REFERENCES `Offres` (`Id_offre`),
  ADD CONSTRAINT `Créer_ibfk_3` FOREIGN KEY (`Id_entreprise`) REFERENCES `Entreprises` (`Id_entreprise`);

--
-- Contraintes pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  ADD CONSTRAINT `Entreprises_ibfk_1` FOREIGN KEY (`Id_ville`) REFERENCES `Villes` (`Id_ville`);

--
-- Contraintes pour la table `Postuler`
--
ALTER TABLE `Postuler`
  ADD CONSTRAINT `Postuler_ibfk_1` FOREIGN KEY (`Id_utilisateur`) REFERENCES `Utilisateurs` (`Id_utilisateur`),
  ADD CONSTRAINT `Postuler_ibfk_2` FOREIGN KEY (`Id_offre`) REFERENCES `Offres` (`Id_offre`);

--
-- Contraintes pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD CONSTRAINT `Utilisateurs_ibfk_1` FOREIGN KEY (`Id_ville`) REFERENCES `Villes` (`Id_ville`),
  ADD CONSTRAINT `Utilisateurs_ibfk_2` FOREIGN KEY (`Id_type`) REFERENCES `Types` (`Id_type`);

--
-- Contraintes pour la table `Vouloir`
--
ALTER TABLE `Vouloir`
  ADD CONSTRAINT `Vouloir_ibfk_1` FOREIGN KEY (`Id_utilisateur`) REFERENCES `Utilisateurs` (`Id_utilisateur`),
  ADD CONSTRAINT `Vouloir_ibfk_2` FOREIGN KEY (`Id_offre`) REFERENCES `Offres` (`Id_offre`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
