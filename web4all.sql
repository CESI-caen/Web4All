-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 01 avr. 2026 à 14:35
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
  `Telephone` varchar(20) DEFAULT NULL,
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
-- Structure de la table `Noter_1`
--

CREATE TABLE `Noter_1` (
  `Id_utilisateur` bigint NOT NULL,
  `Id_offre` bigint NOT NULL,
  `Note` int NOT NULL,
  `Commentaire` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Noter_2`
--

CREATE TABLE `Noter_2` (
  `Id_utilisateur` bigint NOT NULL,
  `Id_entreprise` bigint NOT NULL,
  `Note` int NOT NULL,
  `Commentaire` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Offres`
--

CREATE TABLE `Offres` (
  `Id_offre` bigint NOT NULL,
  `Nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Descriptif` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Date_debut` date NOT NULL,
  `Date_fin` date NOT NULL,
  `Duree` int NOT NULL,
  `Renumeration` decimal(10,2) NOT NULL,
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
  `Genre` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Mdp` varchar(255) DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Ecole` varchar(50) DEFAULT NULL,
  `Cv` tinyint(1) DEFAULT NULL,
  `Lettre_motivation` tinyint(1) DEFAULT NULL,
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

--
-- Déchargement des données de la table `Villes`
--

INSERT INTO `Villes` (`Id_ville`, `Nom`, `CP`) VALUES
(1, 'Ain', '01000'),
(2, 'Aisne', '02000'),
(3, 'Allier', '03000'),
(4, 'Alpes-de-Haute-Provence', '04000'),
(5, 'Hautes-Alpes', '05000'),
(6, 'Alpes-Maritimes', '06000'),
(7, 'Ardèche', '07000'),
(8, 'Ardennes', '08000'),
(9, 'Ariège', '09000'),
(10, 'Aube', '10000'),
(11, 'Aude', '11000'),
(12, 'Aveyron', '12000'),
(13, 'Bouches-du-Rhône', '13000'),
(14, 'Calvados', '14000'),
(15, 'Cantal', '15000'),
(16, 'Charente', '16000'),
(17, 'Charente-Maritime', '17000'),
(18, 'Cher', '18000'),
(19, 'Corrèze', '19000'),
(20, 'Côte-d\'Or', '21000'),
(21, 'Côtes-d\'Armor', '22000'),
(22, 'Creuse', '23000'),
(23, 'Dordogne', '24000'),
(24, 'Doubs', '25000'),
(25, 'Drôme', '26000'),
(26, 'Eure', '27000'),
(27, 'Eure-et-Loir', '28000'),
(28, 'Finistère', '29000'),
(29, 'Corse-du-Sud', '20000'),
(30, 'Haute-Corse', '20200'),
(31, 'Gard', '30000'),
(32, 'Haute-Garonne', '31000'),
(33, 'Gers', '32000'),
(34, 'Gironde', '33000'),
(35, 'Hérault', '34000'),
(36, 'Ille-et-Vilaine', '35000'),
(37, 'Indre', '36000'),
(38, 'Indre-et-Loire', '37000'),
(39, 'Isère', '38000'),
(40, 'Jura', '39000'),
(41, 'Landes', '40000'),
(42, 'Loir-et-Cher', '41000'),
(43, 'Loire', '42000'),
(44, 'Haute-Loire', '43000'),
(45, 'Loire-Atlantique', '44000'),
(46, 'Loiret', '45000'),
(47, 'Lot', '46000'),
(48, 'Lot-et-Garonne', '47000'),
(49, 'Lozère', '48000'),
(50, 'Maine-et-Loire', '49000'),
(51, 'Manche', '50000'),
(52, 'Marne', '51000'),
(53, 'Haute-Marne', '52000'),
(54, 'Mayenne', '53000'),
(55, 'Meurthe-et-Moselle', '54000'),
(56, 'Meuse', '55000'),
(57, 'Morbihan', '56000'),
(58, 'Moselle', '57000'),
(59, 'Nièvre', '58000'),
(60, 'Nord', '59000'),
(61, 'Oise', '60000'),
(62, 'Orne', '61000'),
(63, 'Pas-de-Calais', '62000'),
(64, 'Puy-de-Dôme', '63000'),
(65, 'Pyrénées-Atlantiques', '64000'),
(66, 'Hautes-Pyrénées', '65000'),
(67, 'Pyrénées-Orientales', '66000'),
(68, 'Bas-Rhin', '67000'),
(69, 'Haut-Rhin', '68000'),
(70, 'Rhône', '69000'),
(71, 'Haute-Saône', '70000'),
(72, 'Saône-et-Loire', '71000'),
(73, 'Sarthe', '72000'),
(74, 'Savoie', '73000'),
(75, 'Haute-Savoie', '74000'),
(76, 'Paris', '75000'),
(77, 'Seine-Maritime', '76000'),
(78, 'Seine-et-Marne', '77000'),
(79, 'Yvelines', '78000'),
(80, 'Deux-Sèvres', '79000'),
(81, 'Somme', '80000'),
(82, 'Tarn', '81000'),
(83, 'Tarn-et-Garonne', '82000'),
(84, 'Var', '83000'),
(85, 'Vaucluse', '84000'),
(86, 'Vendée', '85000'),
(87, 'Vienne', '86000'),
(88, 'Haute-Vienne', '87000'),
(89, 'Vosges', '88000'),
(90, 'Yonne', '89000'),
(91, 'Territoire de Belfort', '90000'),
(92, 'Essonne', '91000'),
(93, 'Hauts-de-Seine', '92000'),
(94, 'Seine-Saint-Denis', '93000'),
(95, 'Val-de-Marne', '94000'),
(96, 'Val-d\'Oise', '95000'),
(97, 'Guadeloupe', '97100'),
(98, 'Martinique', '97200'),
(99, 'Guyane', '97300'),
(100, 'La Réunion', '97400'),
(101, 'Mayotte', '97600');

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
-- Index pour la table `Noter_1`
--
ALTER TABLE `Noter_1`
  ADD PRIMARY KEY (`Id_utilisateur`,`Id_offre`),
  ADD KEY `Id_offre` (`Id_offre`);

--
-- Index pour la table `Noter_2`
--
ALTER TABLE `Noter_2`
  ADD PRIMARY KEY (`Id_utilisateur`,`Id_entreprise`),
  ADD KEY `Id_entreprise` (`Id_entreprise`);

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
-- Index pour la table `Types_compte`
--
ALTER TABLE `Types_compte`
  ADD PRIMARY KEY (`Id_type_compte`);

--
-- Index pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`Id_utilisateur`),
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
-- AUTO_INCREMENT pour la table `Offres`
--
ALTER TABLE `Offres`
  MODIFY `Id_offre` bigint NOT NULL AUTO_INCREMENT;

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
  MODIFY `Id_ville` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- Contraintes pour les tables déchargées
--

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
-- Contraintes pour la table `Noter_1`
--
ALTER TABLE `Noter_1`
  ADD CONSTRAINT `Noter_1_ibfk_1` FOREIGN KEY (`Id_utilisateur`) REFERENCES `Utilisateurs` (`Id_utilisateur`),
  ADD CONSTRAINT `Noter_1_ibfk_2` FOREIGN KEY (`Id_offre`) REFERENCES `Offres` (`Id_offre`);

--
-- Contraintes pour la table `Noter_2`
--
ALTER TABLE `Noter_2`
  ADD CONSTRAINT `Noter_2_ibfk_1` FOREIGN KEY (`Id_utilisateur`) REFERENCES `Utilisateurs` (`Id_utilisateur`),
  ADD CONSTRAINT `Noter_2_ibfk_2` FOREIGN KEY (`Id_entreprise`) REFERENCES `Entreprises` (`Id_entreprise`);

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
  ADD CONSTRAINT `Utilisateurs_ibfk_1` FOREIGN KEY (`Id_ville`) REFERENCES `Villes` (`Id_ville`),
  ADD CONSTRAINT `Utilisateurs_ibfk_2` FOREIGN KEY (`Id_type_compte`) REFERENCES `Types_compte` (`Id_type_compte`);

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
