--
-- Modify table structure for core table `accounts`
--

ALTER TABLE  `accounts` ADD  `code` INT( 10 ) NULL DEFAULT NULL AFTER  `id`;

ALTER IGNORE TABLE `accounts` ADD UNIQUE (`code`);

ALTER TABLE  `accounts` ADD  `importID` INT( 10 ) NOT NULL AFTER  `id`;

ALTER TABLE  `accounts` ADD  `creationTime` INT( 10 ) NOT NULL;


--
-- Table structure for table `msa_brand`
--

CREATE TABLE IF NOT EXISTS `msa_brand` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `companyCode` int(10) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `msa_campaign`
--

CREATE TABLE IF NOT EXISTS `msa_campaign` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `msa_import`
--

CREATE TABLE IF NOT EXISTS `msa_import` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `source` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `companies` int(10) NOT NULL,
  `brands` int(10) NOT NULL,
  `sections` int(10) NOT NULL,
  `subSections` int(10) NOT NULL,
  `media` int(10) NOT NULL,
  `campaigns` int(10) NOT NULL,
  `latency` int(20) NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `msa_media`
--

CREATE TABLE IF NOT EXISTS `msa_media` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `msa_section`
--

CREATE TABLE IF NOT EXISTS `msa_section` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `msa_subSection`
--

CREATE TABLE IF NOT EXISTS `msa_subSection` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `importID` int(10) NOT NULL,
  `sectionCode` int(10) NOT NULL,
  `code` int(10) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creationTime` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;