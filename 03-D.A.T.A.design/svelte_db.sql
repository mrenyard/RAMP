-- phpMyAdmin SQL Dump
-- version 2.11.3deb1ubuntu1.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 18, 2011 at 12:41 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.18

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
CREATE SCHEMA IF NOT EXISTS `svelte_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `svelte_db`;

--
-- Database: `svelte_db`
--
GRANT INSERT,UPDATE,SELECT on svelte_db.* TO 'svelte-web'@'localhost';


-- --------------------------------------------------------
--
-- Table structure for table `Country`
--
CREATE TABLE IF NOT EXISTS `Country` (
  `code` varchar(2) UNIQUE NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

GRANT SELECT ON svelte_db.Country TO 'svelte-web'@'localhost';


-- --------------------------------------------------------
--
-- Dumping data for table `Country`
--
INSERT INTO Country
  (code, name)
 VALUES
  ('AT', 'AUSTRIA'),
  ('BE', 'BELGIUM'),
  ('HR', 'CROATIA'),
  ('CY', 'CYPRUS'),
  ('CZ', 'CZECH REPUBLIC'),
  ('DK', 'DENMARK'),
  ('EE', 'ESTONIA'),
  ('FI', 'FINLAND'),
  ('FR', 'FRANCE'),
  ('DE', 'GERMANY'),
  ('GR', 'GREECE'),
  ('HU', 'HUNGARY'),
  ('IS', 'ICELAND'),
  ('IE', 'IRELAND'),
  ('IT', 'ITALY'),
  ('LV', 'LATVIA'),
  ('LT', 'LITHUANIA'),
  ('LU', 'LUXEMBOURG'),
  ('MT', 'MALTA'),
  ('NL', 'NETHERLANDS'),
  ('PL', 'POLAND'),
  ('SK', 'SLOVAKIA'),
  ('SI', 'SLOVENIA'),
  ('ES', 'SPAIN'),
  ('SE', 'SWEDEN'),
  ('TR', 'TURKEY'),
  ('UK', 'UNITED KINGDOM');


-- --------------------------------------------------------
--
-- Table structure for table `Person`
--
CREATE TABLE IF NOT EXISTS `Person` (
  `uname` varchar(45) UNIQUE NOT NULL,
  `honorificPrefix` varchar(45) default NULL,
  `givenName` varchar(45) default NULL,
  `additionalNames` varchar(45) default NULL,
  `familyName` varchar(45) default NULL,
  `postOfficeBox` varchar(45) default NULL,
  `streetAddress` varchar(45) default NULL,
  `extendedAddress` varchar(45) default NULL,
  `locality` varchar(45) default NULL,
  `region` varchar(45) default NULL,
  `postalCode` varchar(45) default NULL,
  `country` varchar(2),
  `telephone` varchar(45) default NULL,
  `mobile` varchar(45) default NULL,
  `fax` varchar(45) default NULL,
  PRIMARY KEY  (`uname`),
    KEY `fk_Person_countryCode` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


-- --------------------------------------------------------
--
-- Table structure for table `Account`
--
CREATE TABLE IF NOT EXISTS `Account` (
  `auPK` varchar(45) UNIQUE NOT NULL,
  `email` varchar(45) UNIQUE NOT NULL,
  `accountTypeID` int(11) NOT NULL default '1',
  `password` varchar(45) NOT NULL,
  `resetPasswordTimeStamp` timestamp DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (`auPK`),
   KEY `fk_Account_auPK` (`auPK`),
   KEY `fk_Account_email` (`email`),
   KEY `fk_Account_typeID` (`accountTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------
--
-- Table structure for table `AccountType`
--
CREATE TABLE IF NOT EXISTS `AccountType` (
  `enum` int(11) NOT NULL,
  `name` varchar(45) UNIQUE NOT NULL,
  PRIMARY KEY  (`enum`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

GRANT SELECT ON svelte_db.AccountType TO 'svelte-web'@'localhost';


--
-- Data for table `AccountType`
--
INSERT INTO `AccountType` (`enum`, `name`) VALUES
(1, 'Registered'),
(2, 'User'),
(3, 'Affiliate'),
(4, 'Administrator'),
(5, 'Administrator Manager'),
(6, 'Systems Administrator');


-- --------------------------------------------------------
--
-- Constraints for table `Account`
--
ALTER TABLE `Account`
  ADD CONSTRAINT `fk_Account_typeID` FOREIGN KEY (`accountTypeID`) REFERENCES `AccountType` (`enum`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--
-- Constraints for table `Person`
--
ALTER TABLE `Person`
  ADD CONSTRAINT `fk_Person_countryCode` FOREIGN KEY (`country`) REFERENCES `Country` (`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

