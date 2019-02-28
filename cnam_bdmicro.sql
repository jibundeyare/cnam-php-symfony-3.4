-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  jeu. 28 fév. 2019 à 11:22
-- Version du serveur :  10.1.35-MariaDB
-- Version de PHP :  7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `cnam_bdmicro`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `numcat` int(2) UNSIGNED NOT NULL AUTO_INCREMENT,
  `libelle` varchar(15) NOT NULL,
  `tauxtva` decimal(4,2) DEFAULT NULL,
  PRIMARY KEY (`numcat`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`numcat`, `libelle`, `tauxtva`) VALUES
(1, 'Foo', '21.00'),
(2, 'Bar', '21.00'),
(3, 'Baz', '21.00'),
(4, 'lorem', '21.00');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `numclient` int(2) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  PRIMARY KEY (`numclient`),
  UNIQUE KEY `un_nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`numclient`, `nom`, `ville`) VALUES
(1, 'Leblanc', 'Nice'),
(2, 'Levert', 'Paris'),
(3, 'Lejaune', 'Rome'),
(4, 'Lerouge', 'Nice'),
(5, 'Lenoir', 'Paris'),
(6, 'Lebrun', 'Nice'),
(7, 'Foo', 'Lille'),
(8, 'Bar', 'Lille');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `codeprod` int(2) UNSIGNED NOT NULL AUTO_INCREMENT,
  `designation` varchar(20) DEFAULT NULL,
  `marque` varchar(20) NOT NULL,
  `prixunit` decimal(7,2) NOT NULL,
  `qtStock` smallint(3) DEFAULT NULL,
  `numcat` char(2) DEFAULT NULL,
  PRIMARY KEY (`codeprod`),
  KEY `fk_produit_categorie` (`numcat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `email`, `password_hash`) VALUES
(1, 'foo', 'foo@example.com', '$2y$10$2IaOiaNVvA4mnr4Ybc8HX.oEgiSaiT/GSfFU17Ll9N.Bl.NYuEj0S'),
(2, 'bar', 'bar@example.com', '$2y$10$GnF7q0HcbFxZ7VcupK4ohemLzw6QnfBn5UQIrVwy1oLbxfH9YVG2e'),
(3, 'baz', 'baz@example.org', '$2y$10$GlTDkrjQWG570Vm/PkjQP.Z2PDOqtq3jBY6ZgpHFpGq0oxr2AImny');

-- --------------------------------------------------------

--
-- Structure de la table `vente`
--

DROP TABLE IF EXISTS `vente`;
CREATE TABLE IF NOT EXISTS `vente` (
  `numclient` int(2) UNSIGNED NOT NULL AUTO_INCREMENT,
  `codeprod` smallint(2) NOT NULL,
  `datevente` date NOT NULL,
  `qt` smallint(2) NOT NULL,
  PRIMARY KEY (`numclient`,`codeprod`,`datevente`),
  KEY `fk_vente_produit` (`codeprod`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
