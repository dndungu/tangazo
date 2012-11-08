-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 08, 2012 at 07:57 PM
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

DROP TABLE IF EXISTS `brand`;
CREATE TABLE IF NOT EXISTS `brand` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `code` int(10) NOT NULL,
  `companyCode` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2004 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

DROP TABLE IF EXISTS `campaign`;
CREATE TABLE IF NOT EXISTS `campaign` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3927 ;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `code` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=934 ;

-- --------------------------------------------------------

--
-- Table structure for table `import`
--

DROP TABLE IF EXISTS `import`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `code` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `code` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `subSection`
--

DROP TABLE IF EXISTS `subSection`;
CREATE TABLE IF NOT EXISTS `subSection` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `sectionCode` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=158 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
