SET time_zone = "+00:00";

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
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `companyCode` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) TYPE=MyISAM  AUTO_INCREMENT=19172 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

DROP TABLE IF EXISTS `campaign`;
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
) TYPE=MyISAM  AUTO_INCREMENT=78506 ;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`),
  FULLTEXT KEY `name` (`name`)
) TYPE=MyISAM  AUTO_INCREMENT=7288 ;

-- --------------------------------------------------------

--
-- Table structure for table `import`
--

DROP TABLE IF EXISTS `import`;
CREATE TABLE IF NOT EXISTS `import` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `source` varchar(255) NOT NULL,
  `companies` int(10) NOT NULL,
  `brands` int(10) NOT NULL,
  `sections` int(10) NOT NULL,
  `subSections` int(10) NOT NULL,
  `media` int(10) NOT NULL,
  `campaigns` int(10) NOT NULL,
  `latency` int(20) NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`)
) TYPE=MyISAM  AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) TYPE=MyISAM  AUTO_INCREMENT=164 ;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) TYPE=MyISAM  AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `subSection`
--

DROP TABLE IF EXISTS `subSection`;
CREATE TABLE IF NOT EXISTS `subSection` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `sectionCode` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) TYPE=MyISAM  AUTO_INCREMENT=196 ;

