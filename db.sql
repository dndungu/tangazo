-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 09, 2012 at 01:12 PM
-- Server version: 5.5.28-0ubuntu0.12.04.2
-- PHP Version: 5.4.8-1~precise+1

SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tangazo`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE IF NOT EXISTS `brand` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `companyCode` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19172 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE IF NOT EXISTS `campaign` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `campaignCode` int(20) NOT NULL,
  `companyCode` int(10) NOT NULL,
  `brandCode` int(10) NOT NULL,
  `sectionCode` int(10) NOT NULL,
  `subSectionCode` int(10) NOT NULL,
  `mediaCode` int(10) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `week` int(2) NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `campaignCode` (`campaignCode`),
  KEY `sectionCode` (`sectionCode`),
  KEY `companyCode` (`companyCode`),
  KEY `mediaCode` (`mediaCode`),
  KEY `subSectionCode` (`subSectionCode`),
  KEY `brandCode` (`brandCode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=78506 ;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7288 ;

-- --------------------------------------------------------

--
-- Table structure for table `import`
--

CREATE TABLE IF NOT EXISTS `import` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `source` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `companies` int(10) NOT NULL,
  `brands` int(10) NOT NULL,
  `sections` int(10) NOT NULL,
  `subSections` int(10) NOT NULL,
  `media` int(10) NOT NULL,
  `campaigns` int(10) NOT NULL,
  `latency` int(20) NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=164 ;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE IF NOT EXISTS `section` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `subSection`
--

CREATE TABLE IF NOT EXISTS `subSection` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `sectionCode` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=196 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
