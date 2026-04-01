-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 31 mars 2026 à 09:26
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
-- Structure de la table `Commentaires`
--

CREATE TABLE `Commentaires` (
  `Id_commentaire` bigint NOT NULL,
  `Commentaire` varchar(200) NOT NULL,
  `Id_offre` bigint NOT NULL,
  `Id_utilisateur` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Competences`
--

CREATE TABLE `Competences` (
  `Id_competence` bigint NOT NULL,
  `Nom` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Domaines`
--

CREATE TABLE `Domaines` (
  `Id_domaine` bigint NOT NULL,
  `Nom` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Entreprises`
--

CREATE TABLE `Entreprises` (
  `Id_entreprise` bigint NOT NULL,
  `Nom` varchar(50) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Telephone` varchar(50) DEFAULT NULL,
  `Descriptif` varchar(50) DEFAULT NULL,
  `Id_ville` bigint NOT NULL,
  `Id_utilisateur` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Exercer_dans`
--

CREATE TABLE `Exercer_dans` (
  `Id_entreprise` bigint NOT NULL,
  `Id_domaine` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Exiger`
--

CREATE TABLE `Exiger` (
  `Id_offre` bigint NOT NULL,
  `Id_competence` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Notes`
--

CREATE TABLE `Notes` (
  `Id_note` bigint NOT NULL,
  `Note` int NOT NULL,
  `Id_offre` bigint NOT NULL,
  `Id_entreprise` bigint NOT NULL,
  `Id_utilisateur` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Offres`
--

CREATE TABLE `Offres` (
  `Id_offre` bigint NOT NULL,
  `Descriptif` text,
  `Date_debut` date DEFAULT NULL,
  `Date_fin` date DEFAULT NULL,
  `Duree` varchar(50) DEFAULT NULL,
  `Renumeration` varchar(50) DEFAULT NULL,
  `Id_entreprise` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Postuler`
--

CREATE TABLE `Postuler` (
  `Id_utilisateur` bigint NOT NULL,
  `Id_offre` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Sessions`
--

CREATE TABLE `Sessions` (
  `Id_session` bigint NOT NULL,
  `Niveau_permission` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Types_compte`
--

CREATE TABLE `Types_compte` (
  `Id_type_compte` bigint NOT NULL,
  `Nom` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateurs`
--

CREATE TABLE `Utilisateurs` (
  `Id_utilisateur` bigint NOT NULL,
  `Nom` varchar(50) DEFAULT NULL,
  `Prenom` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Mdp` varchar(50) DEFAULT NULL,
  `Telephone` varchar(17) DEFAULT NULL,
  `Cv` tinyint(1) DEFAULT NULL,
  `Lettre_motivation` tinyint(1) DEFAULT NULL,
  `Id_session` bigint NOT NULL,
  `Id_ville` bigint NOT NULL,
  `Id_type_compte` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Villes`
--

CREATE TABLE `Villes` (
  `Id_ville` bigint NOT NULL,
  `Nom` varchar(50) DEFAULT NULL,
  `CP` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Vouloir`
--

CREATE TABLE `Vouloir` (
  `Id_utilisateur` bigint NOT NULL,
  `Id_offre` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Commentaires`
--
ALTER TABLE `Commentaires`
  ADD PRIMARY KEY (`Id_commentaire`),
  ADD KEY `Id_offre` (`Id_offre`),
  ADD KEY `Id_utilisateur` (`Id_utilisateur`);

--
-- Index pour la table `Competences`
--
ALTER TABLE `Competences`
  ADD PRIMARY KEY (`Id_competence`);

--
-- Index pour la table `Domaines`
--
ALTER TABLE `Domaines`
  ADD PRIMARY KEY (`Id_domaine`);

--
-- Index pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  ADD PRIMARY KEY (`Id_entreprise`),
  ADD UNIQUE KEY `Id_utilisateur` (`Id_utilisateur`),
  ADD KEY `Id_ville` (`Id_ville`);

--
-- Index pour la table `Exercer_dans`
--
ALTER TABLE `Exercer_dans`
  ADD PRIMARY KEY (`Id_entreprise`,`Id_domaine`),
  ADD KEY `Id_domaine` (`Id_domaine`);

--
-- Index pour la table `Exiger`
--
ALTER TABLE `Exiger`
  ADD PRIMARY KEY (`Id_offre`,`Id_competence`),
  ADD KEY `Id_competence` (`Id_competence`);

--
-- Index pour la table `Notes`
--
ALTER TABLE `Notes`
  ADD PRIMARY KEY (`Id_note`),
  ADD KEY `Id_offre` (`Id_offre`),
  ADD KEY `Id_entreprise` (`Id_entreprise`),
  ADD KEY `Id_utilisateur` (`Id_utilisateur`);

--
-- Index pour la table `Offres`
--
ALTER TABLE `Offres`
  ADD PRIMARY KEY (`Id_offre`),
  ADD KEY `Id_entreprise` (`Id_entreprise`);

--
-- Index pour la table `Postuler`
--
ALTER TABLE `Postuler`
  ADD PRIMARY KEY (`Id_utilisateur`,`Id_offre`),
  ADD KEY `Id_offre` (`Id_offre`);

--
-- Index pour la table `Sessions`
--
ALTER TABLE `Sessions`
  ADD PRIMARY KEY (`Id_session`);

--
-- Index pour la table `Types_compte`
--
ALTER TABLE `Types_compte`
  ADD PRIMARY KEY (`Id_type_compte`);

--
-- Index pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`Id_utilisateur`),
  ADD KEY `Id_session` (`Id_session`),
  ADD KEY `Id_ville` (`Id_ville`),
  ADD KEY `Id_type_compte` (`Id_type_compte`);

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
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Commentaires`
--
ALTER TABLE `Commentaires`
  MODIFY `Id_commentaire` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Competences`
--
ALTER TABLE `Competences`
  MODIFY `Id_competence` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Domaines`
--
ALTER TABLE `Domaines`
  MODIFY `Id_domaine` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  MODIFY `Id_entreprise` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Notes`
--
ALTER TABLE `Notes`
  MODIFY `Id_note` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Offres`
--
ALTER TABLE `Offres`
  MODIFY `Id_offre` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Sessions`
--
ALTER TABLE `Sessions`
  MODIFY `Id_session` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Types_compte`
--
ALTER TABLE `Types_compte`
  MODIFY `Id_type_compte` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `Id_utilisateur` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Villes`
--
ALTER TABLE `Villes`
  MODIFY `Id_ville` bigint NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Commentaires`
--
ALTER TABLE `Commentaires`
  ADD CONSTRAINT `Commentaires_ibfk_1` FOREIGN KEY (`Id_offre`) REFERENCES `Offres` (`Id_offre`),
  ADD CONSTRAINT `Commentaires_ibfk_2` FOREIGN KEY (`Id_utilisateur`) REFERENCES `Utilisateurs` (`Id_utilisateur`);

--
-- Contraintes pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  ADD CONSTRAINT `Entreprises_ibfk_1` FOREIGN KEY (`Id_ville`) REFERENCES `Villes` (`Id_ville`),
  ADD CONSTRAINT `Entreprises_ibfk_2` FOREIGN KEY (`Id_utilisateur`) REFERENCES `Utilisateurs` (`Id_utilisateur`);

--
-- Contraintes pour la table `Exercer_dans`
--
ALTER TABLE `Exercer_dans`
  ADD CONSTRAINT `Exercer_dans_ibfk_1` FOREIGN KEY (`Id_entreprise`) REFERENCES `Entreprises` (`Id_entreprise`),
  ADD CONSTRAINT `Exercer_dans_ibfk_2` FOREIGN KEY (`Id_domaine`) REFERENCES `Domaines` (`Id_domaine`);

--
-- Contraintes pour la table `Exiger`
--
ALTER TABLE `Exiger`
  ADD CONSTRAINT `Exiger_ibfk_1` FOREIGN KEY (`Id_offre`) REFERENCES `Offres` (`Id_offre`),
  ADD CONSTRAINT `Exiger_ibfk_2` FOREIGN KEY (`Id_competence`) REFERENCES `Competences` (`Id_competence`);

--
-- Contraintes pour la table `Notes`
--
ALTER TABLE `Notes`
  ADD CONSTRAINT `Notes_ibfk_1` FOREIGN KEY (`Id_offre`) REFERENCES `Offres` (`Id_offre`),
  ADD CONSTRAINT `Notes_ibfk_2` FOREIGN KEY (`Id_entreprise`) REFERENCES `Entreprises` (`Id_entreprise`),
  ADD CONSTRAINT `Notes_ibfk_3` FOREIGN KEY (`Id_utilisateur`) REFERENCES `Utilisateurs` (`Id_utilisateur`);

--
-- Contraintes pour la table `Offres`
--
ALTER TABLE `Offres`
  ADD CONSTRAINT `Offres_ibfk_1` FOREIGN KEY (`Id_entreprise`) REFERENCES `Entreprises` (`Id_entreprise`);

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
  ADD CONSTRAINT `Utilisateurs_ibfk_1` FOREIGN KEY (`Id_session`) REFERENCES `Sessions` (`Id_session`),
  ADD CONSTRAINT `Utilisateurs_ibfk_2` FOREIGN KEY (`Id_ville`) REFERENCES `Villes` (`Id_ville`),
  ADD CONSTRAINT `Utilisateurs_ibfk_3` FOREIGN KEY (`Id_type_compte`) REFERENCES `Types_compte` (`Id_type_compte`);

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
