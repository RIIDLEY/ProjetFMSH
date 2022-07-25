-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 25 juil. 2022 à 13:33
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `stage_fmsh`
--
CREATE DATABASE IF NOT EXISTS `stage_fmsh` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `stage_fmsh`;

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `AdminID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) COLLATE utf8_bin NOT NULL,
  `Password` varchar(250) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`AdminID`),
  UNIQUE KEY `uc_Admin_Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`AdminID`, `Name`, `Password`) VALUES
(1, 'coucou', '$2y$10$ctEpzNp/JoP/wUkA5CBIZenb8LFZiy0IIZxI05c6dR.OcVk1V4lMu');

-- --------------------------------------------------------

--
-- Structure de la table `fichiers_upload`
--

DROP TABLE IF EXISTS `fichiers_upload`;
CREATE TABLE IF NOT EXISTS `fichiers_upload` (
  `FileID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `Description` varchar(255) COLLATE utf8_bin NOT NULL,
  `Tags` varchar(255) COLLATE utf8_bin NOT NULL,
  `Filename` varchar(200) COLLATE utf8_bin NOT NULL,
  `TranscriptFile` varchar(255) COLLATE utf8_bin NOT NULL,
  `Type` char(4) COLLATE utf8_bin NOT NULL,
  `Size` int(11) NOT NULL,
  PRIMARY KEY (`FileID`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `indexation`
--

DROP TABLE IF EXISTS `indexation`;
CREATE TABLE IF NOT EXISTS `indexation` (
  `WordID` int(11) NOT NULL AUTO_INCREMENT,
  `Word` varchar(200) COLLATE utf8_bin NOT NULL,
  `Occurence` int(11) NOT NULL,
  `FileID` int(11) NOT NULL,
  PRIMARY KEY (`WordID`),
  KEY `fk_Indexation_FileID` (`FileID`)
) ENGINE=InnoDB AUTO_INCREMENT=769 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `indexation`
--
ALTER TABLE `indexation`
  ADD CONSTRAINT `fk_Indexation_FileID` FOREIGN KEY (`FileID`) REFERENCES `fichiers_upload` (`FileID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
