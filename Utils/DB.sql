DROP DATABASE IF EXISTS `Stage_FMSH`;
CREATE DATABASE `Stage_FMSH` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `Stage_FMSH`;

CREATE TABLE `Fichiers_Upload` (
    `FileID` int AUTO_INCREMENT NOT NULL ,
    `Name` varchar(200)  NOT NULL ,
    `Filename` varchar(200)  NOT NULL ,
    `Type` char(4)  NOT NULL ,
    `Size` int  NOT NULL ,
    PRIMARY KEY (
        `FileID`
    )
);

CREATE TABLE `Indexation` (
    `WordID` int AUTO_INCREMENT NOT NULL ,
    `Word` varchar(200)  NOT NULL ,
    `Occurence` int  NOT NULL ,
    `FileID` int  NOT NULL ,
    PRIMARY KEY (
        `WordID`
    )
);

CREATE TABLE `Admin` (
    `AdminID` int AUTO_INCREMENT NOT NULL ,
    `Name` varchar(255)  NOT NULL ,
    `Mail` varchar(255)  NOT NULL ,
    `Password` varchar(255)  NOT NULL ,
    PRIMARY KEY (
        `AdminID`
    ),
    CONSTRAINT `uc_Admin_Name` UNIQUE (
        `Name`
    ),
    CONSTRAINT `uc_Admin_Mail` UNIQUE (
        `Mail`
    )
);

ALTER TABLE `Indexation` ADD CONSTRAINT `fk_Indexation_FileID` FOREIGN KEY(`FileID`)
REFERENCES `Fichiers_Upload` (`FileID`);

