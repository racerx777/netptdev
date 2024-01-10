-- phpMyAdmin SQL Dump
-- version 2.11.9.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 21, 2009 at 07:41 PM
-- Server version: 4.1.22
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `wsptn_development`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE IF NOT EXISTS `appointments` (
  `apid` int(11) NOT NULL auto_increment COMMENT 'Appointment Identifier',
  `appaid` int(11) NOT NULL default '0' COMMENT 'Patient Identifier',
  `apcrid` int(11) NOT NULL default '0' COMMENT 'Case Referral Identifier',
  `apdate` datetime default NULL COMMENT 'Appointment Date/Time',
  `apcnum` char(2) NOT NULL default '0' COMMENT 'Appointment Clinic',
  `apcnumdesired` char(2) NOT NULL default '' COMMENT 'Appointment Desired Clinic',
  `apstatuscode` char(3) default NULL COMMENT 'Appointment Status Code',
  `apnote` varchar(60) default NULL COMMENT 'Appointment Note',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`apid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `appointments`
--


-- --------------------------------------------------------

--
-- Table structure for table `attorneys`
--

CREATE TABLE IF NOT EXISTS `attorneys` (
  `atid` int(11) NOT NULL default '0' COMMENT 'Attorney Id',
  `atcontdate` datetime default NULL COMMENT 'Attorney Last Contact',
  `atrefnum` varchar(20) default NULL COMMENT 'Attorney Reference Number',
  `atnote` varchar(60) default NULL COMMENT 'Attorney Note',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`atid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attorneys`
--


-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE IF NOT EXISTS `cases` (
  `crid` int(11) NOT NULL auto_increment COMMENT 'Case Referral Identifier',
  `crinactive` tinyint(4) NOT NULL default '0' COMMENT 'Referral Active/Inactive',
  `crdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Referral Date',
  `crdmid` int(11) NOT NULL default '0' COMMENT 'Referring Doctor Id',
  `crrefnum` varchar(20) default NULL COMMENT 'Referring Doctor Reference Number',
  `crdxcode` char(3) NOT NULL default '' COMMENT 'Referrer Dx',
  `crinjurydate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Injury Date',
  `crinjurytypecode` char(3) NOT NULL default '' COMMENT 'Injury Type Code',
  `crpaid` int(11) NOT NULL default '0',
  `crcasetypecode` char(3) default NULL COMMENT 'Case Type',
  `crcasestatuscode` char(3) default NULL COMMENT 'Case Status',
  `crsurgerydate` datetime default NULL COMMENT 'Referrer Surgery Date',
  `crtherapytypecode` char(3) NOT NULL default '' COMMENT 'Therapy Type Code',
  `crnote` varchar(60) default NULL COMMENT 'Referal Note',
  `crconvertdate` datetime default NULL COMMENT 'Conversion Date',
  `crcanceldate` datetime default NULL COMMENT 'Cancelled Date',
  `crcancelreasoncode` char(3) default NULL COMMENT 'Cancelled Reason Code',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`crid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cases`
--


-- --------------------------------------------------------

--
-- Table structure for table `case_history`
--

CREATE TABLE IF NOT EXISTS `case_history` (
  `chid` int(11) NOT NULL auto_increment COMMENT 'Case Referral History Identifier',
  `chnote` varchar(60) default NULL COMMENT 'Cancalled Note',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`chid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `case_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `case_presecriptions`
--

CREATE TABLE IF NOT EXISTS `case_presecriptions` (
  `cpid` int(11) NOT NULL default '0' COMMENT 'Case Prescription Id',
  `cpcrid` int(11) default NULL COMMENT 'Case Referral Id',
  `cpbyuser` int(11) default NULL COMMENT 'Authorization User',
  `cpcontdate` datetime default NULL COMMENT 'Authorization Last Contact Date',
  `cpvisits` int(11) default NULL COMMENT 'Authorized Visits',
  `cpexpiredate` datetime default NULL COMMENT 'Authorization Expiration',
  `cpnote1` varchar(60) default NULL COMMENT 'Authorization Notes',
  `cpchangedate` datetime default NULL COMMENT 'Authorization Last Changed Date',
  `cpstatuscode` char(3) default NULL COMMENT 'Authorization Status',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`cpid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `case_presecriptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE IF NOT EXISTS `doctors` (
  `dmid` int(11) NOT NULL auto_increment COMMENT 'Doctor Identifier',
  `dminactive` tinyint(4) NOT NULL default '0' COMMENT 'Doctor Active/Inactive',
  `dmsname` varchar(6) NOT NULL default '' COMMENT 'Doctor Short Name',
  `dmfirst` varchar(30) default NULL COMMENT 'Doctor First Name',
  `dmlast` varchar(30) default NULL COMMENT 'Doctor Last Name',
  `dmdescphys` varchar(50) default NULL COMMENT 'Doctor Physical Description',
  `dmdob` datetime default NULL COMMENT 'Doctor Birthday',
  `dmdscode` char(3) default NULL COMMENT 'Doctor Type Specialty Code',
  `dmdclass` char(1) default NULL COMMENT 'Doctor Classification',
  `dmdescwork` varchar(50) default NULL COMMENT 'Doctor Description/Work Mix Notes',
  `dmwcmix` decimal(5,2) default NULL COMMENT 'Mix WC Percentage',
  `dmpimix` decimal(5,2) default NULL COMMENT 'Mix PI Percentage',
  `dmothermix` decimal(5,2) default NULL COMMENT 'Mix Other Percentage',
  `dmestrefer` int(11) default NULL COMMENT 'Sales Estimated Referrals per Month',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`dmid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`dmid`, `dminactive`, `dmsname`, `dmfirst`, `dmlast`, `dmdescphys`, `dmdob`, `dmdscode`, `dmdclass`, `dmdescwork`, `dmwcmix`, `dmpimix`, `dmothermix`, `dmestrefer`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(18, 0, 'SPOON', 'SUNNI', 'SPOON', '', '2005-05-05 00:00:00', 'NEU', 'A', '', 0.00, 0.00, 0.00, 123, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(19, 0, 'TEST', 'TEST', 'TEST', 'description', '1961-01-01 00:00:00', 'DO', 'B', 'Doctor tends to provide mostly WC Cases', 50.00, 40.00, 10.00, 1, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(14, 0, 'ADAM', 'JB', 'ADAM', 'description', '1961-01-01 00:00:00', 'DC', 'A', 'test', 75.75, 20.25, 4.00, 111, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_classes`
--

CREATE TABLE IF NOT EXISTS `doctor_classes` (
  `dccode` char(1) NOT NULL default '' COMMENT 'Doctor Class Code',
  `dcdesc` varchar(30) NOT NULL default '' COMMENT 'Doctor Class Description',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`dccode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor_classes`
--

INSERT INTO `doctor_classes` (`dccode`, `dcdesc`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
('A', 'MD Class A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('B', 'MD Class B', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('C', 'MD Class C', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('D', 'MD Class D', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('E', 'MD Class E', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('F', 'MD Class F', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_groups`
--

CREATE TABLE IF NOT EXISTS `doctor_groups` (
  `dgid` int(11) NOT NULL default '0' COMMENT 'Doctor Groups Identifier',
  `dginactive` tinyint(4) NOT NULL default '0' COMMENT 'Doctor Groups Active/Inactive',
  `dgname` varchar(50) default NULL COMMENT 'Doctor Groups Name',
  `dgnote` varchar(50) default NULL COMMENT 'Doctor Groups Note',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`dgid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor_groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `doctor_locations`
--

CREATE TABLE IF NOT EXISTS `doctor_locations` (
  `dlid` int(11) NOT NULL auto_increment COMMENT 'Location Id',
  `dlinactive` tinyint(4) NOT NULL default '0' COMMENT 'Location Active/Inactive',
  `dlsname` varchar(6) NOT NULL default '' COMMENT 'Location Short Name',
  `dlname` varchar(30) NOT NULL default '' COMMENT 'Location Name',
  `dldescphy` varchar(50) NOT NULL default '' COMMENT 'Location Physical Description',
  `dldlsid` int(11) default NULL COMMENT 'Location Primary Staff Contact Id',
  `dlphone` varchar(20) NOT NULL default '' COMMENT 'Location Phone Number',
  `dlemail` varchar(64) NOT NULL default '' COMMENT 'Location E-mail address',
  `dlfax` varchar(20) NOT NULL default '' COMMENT 'Location Fax Number',
  `dladdress` varchar(30) NOT NULL default '0' COMMENT 'Location Address',
  `dlcity` varchar(20) NOT NULL default '' COMMENT 'Location City',
  `dlstate` char(2) NOT NULL default '' COMMENT 'Location State',
  `dlzip` varchar(10) NOT NULL default '' COMMENT 'Location Zip Code',
  `dlterritory` int(11) default NULL COMMENT 'Location Territory',
  `dlofficehours` varchar(50) default NULL COMMENT 'Location Office Hours',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`dlid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `doctor_locations`
--


-- --------------------------------------------------------

--
-- Table structure for table `doctor_locations_staff`
--

CREATE TABLE IF NOT EXISTS `doctor_locations_staff` (
  `dlsid` int(11) NOT NULL default '0' COMMENT 'Staff Id',
  `dlsinactive` tinyint(4) NOT NULL default '0' COMMENT 'Staff Active/Inactive',
  `dlstitle` int(11) default NULL COMMENT 'Staff Title',
  `dlsfirst` varchar(30) default NULL COMMENT 'Staff First Name',
  `dlslast` varchar(30) default NULL COMMENT 'Staff Last Name',
  `dlsdescphys` varchar(50) default NULL COMMENT 'Staff Physical Description',
  `dlsdob` int(11) default NULL COMMENT 'Staff Birthday',
  `dlsphone` varchar(20) NOT NULL default '' COMMENT 'Personal Phone Number',
  `dlsemail` varchar(20) NOT NULL default '' COMMENT 'Personal E-mail address',
  `dlsfax` varchar(20) NOT NULL default '' COMMENT 'Personal Fax Number',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`dlsid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor_locations_staff`
--


-- --------------------------------------------------------

--
-- Table structure for table `doctor_relationships`
--

CREATE TABLE IF NOT EXISTS `doctor_relationships` (
  `drdmid` int(11) NOT NULL default '0' COMMENT 'Doctor Identifier',
  `drdlid` int(11) NOT NULL default '0' COMMENT 'Office Identifier',
  `drdgid` int(11) default NULL COMMENT 'Doctor Group Identifier',
  `drnote` varchar(50) default NULL COMMENT 'Doctor/Location Note',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`drdmid`,`drdlid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor_relationships`
--


-- --------------------------------------------------------

--
-- Table structure for table `doctor_specialties`
--

CREATE TABLE IF NOT EXISTS `doctor_specialties` (
  `dscode` char(3) NOT NULL default '' COMMENT 'Doctor Type/Specialty Code',
  `dsdesc` varchar(30) NOT NULL default '' COMMENT 'Doctor Type/Specialty Description',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`dscode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor_specialties`
--

INSERT INTO `doctor_specialties` (`dscode`, `dsdesc`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
('DC', 'Chiropractic/DC', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('DPM', 'Podiatry/DPM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('DO', 'Osteopath/DO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('GP', 'General/Family/GP/MD', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('INT', 'Internal Medicine/INT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('NEU', 'Neurology/Neurosurgery/NS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OCC', 'Occupational/OC', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('ORT', 'Orthopedic/OS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OTH', 'Other', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PM', 'Pain Management', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `employers`
--

CREATE TABLE IF NOT EXISTS `employers` (
  `emid` int(11) NOT NULL auto_increment COMMENT 'Employer Identifier',
  `emname` varchar(40) default NULL COMMENT 'Employer Name',
  `emaddress` int(11) default NULL COMMENT 'Employer Address',
  `emcity` varchar(30) NOT NULL default '0' COMMENT 'City',
  `emstate` varchar(30) NOT NULL default '0' COMMENT 'State',
  `emzip` varchar(30) NOT NULL default '0' COMMENT 'Zip',
  `emcontdat` datetime default NULL COMMENT 'Employer Last Contact Date',
  `emrefnum` varchar(20) default NULL COMMENT 'Employer Reference Number',
  `emnote` varchar(60) default NULL COMMENT 'Employer Note',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`emid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employers`
--


-- --------------------------------------------------------

--
-- Table structure for table `insurance_adjusters`
--

CREATE TABLE IF NOT EXISTS `insurance_adjusters` (
  `iaid` int(11) NOT NULL default '0' COMMENT 'Ins Adj Id',
  `iacontdate` datetime default NULL COMMENT 'Ins Adj Last Contact Date',
  `iarefnum` varchar(20) default NULL COMMENT 'Ins Adj Reference Number',
  `ianote` varchar(60) default NULL COMMENT 'Ins Adj Note',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`iaid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `insurance_adjusters`
--


-- --------------------------------------------------------

--
-- Table structure for table `insurance_companies`
--

CREATE TABLE IF NOT EXISTS `insurance_companies` (
  `icid` int(11) NOT NULL default '0' COMMENT 'Ins Adj Id',
  `iccontdate` datetime default NULL COMMENT 'Ins Adj Last Contact Date',
  `icrefnum` varchar(20) default NULL COMMENT 'Ins Adj Reference Number',
  `icnote` varchar(60) default NULL COMMENT 'Ins Adj Note',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`icid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `insurance_companies`
--


-- --------------------------------------------------------

--
-- Table structure for table `master_accounts`
--

CREATE TABLE IF NOT EXISTS `master_accounts` (
  `amid` int(11) NOT NULL auto_increment COMMENT 'Unique Identifier',
  `aminactive` tinyint(4) NOT NULL default '0' COMMENT 'Active/Inactive',
  `amreferraldate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Referrer Date',
  `amdrid` int(11) NOT NULL default '0' COMMENT 'Referrer Doctor Id',
  `amrefercontdate` datetime default NULL COMMENT 'Referrer Contact Date',
  `amreferrefnum` varchar(20) default NULL COMMENT 'Referrer Reference Number',
  `amrefernote1` varchar(60) default NULL COMMENT 'Referrer Note',
  `amreferdxcode` char(3) NOT NULL default '' COMMENT 'Referrer Dx',
  `amrefersurgerydate` datetime default NULL COMMENT 'Referrer Surgery Date',
  `aminjurydate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Injury Date',
  `aminjurytypecode` char(3) NOT NULL default '' COMMENT 'Injury Type Code',
  `amtherapytypecode` char(3) NOT NULL default '' COMMENT 'Therapy Type Code',
  `amapptdate` datetime default NULL COMMENT 'Appointment Date',
  `amapptcnum` char(2) NOT NULL default '0' COMMENT 'Appointment Clinic',
  `amapptcnumdesiredId` char(2) NOT NULL default '' COMMENT 'Appointment Desired Clinic',
  `amapptstatuscod` char(3) default NULL COMMENT 'Appointment Status Code',
  `amapptnote1` varchar(60) default NULL COMMENT 'Appointment Note',
  `amprefix` varchar(10) default NULL,
  `amfirst` varchar(30) default NULL COMMENT 'First Name',
  `ammiddle` varchar(30) default NULL COMMENT 'Middle',
  `amlast` varchar(30) default NULL COMMENT 'Last Name',
  `amsuffix` varchar(10) default NULL,
  `amsex` char(1) default NULL COMMENT 'Gender',
  `amssn` varchar(9) default NULL COMMENT 'Social Security Number',
  `amdob` datetime default NULL COMMENT 'Date of Birth',
  `amphone1` varchar(20) NOT NULL default '' COMMENT 'Phone Number',
  `amaddress1` varchar(30) NOT NULL default '0' COMMENT 'Address',
  `amcontdate` datetime default NULL COMMENT 'Last Contact Date',
  `amcasetypecode` char(3) default NULL COMMENT 'Case Type',
  `amcasestatuscode` char(3) default NULL COMMENT 'Case Status',
  `amnote1` varchar(60) default NULL COMMENT 'Note',
  `amreadmitrelocated` tinyint(4) default NULL COMMENT 'Re-Admit?',
  `amemployername` varchar(40) default NULL COMMENT 'Employer Name',
  `amemployeraddress1` int(11) default NULL COMMENT 'Employer Address',
  `amemployercontdat` datetime default NULL COMMENT 'Employer Last Contact Date',
  `amemployerrefnum` varchar(20) default NULL COMMENT 'Employer Reference Number',
  `amemployernote1` varchar(60) default NULL COMMENT 'Employer Note',
  `amatid` int(11) default NULL COMMENT 'Attorney Id',
  `amattycontdate` datetime default NULL COMMENT 'Attorney Last Contact',
  `amattyrefnum` varchar(20) default NULL COMMENT 'Attorney Reference Number',
  `amattynote1` varchar(60) default NULL COMMENT 'Attorney Note',
  `amthid` int(11) default NULL COMMENT 'Therapist Id',
  `amtherapistcontdate` datetime default NULL COMMENT 'Therapist Last Contact',
  `amtherapistrefnum` varchar(20) default NULL COMMENT 'Therapist Reference Number',
  `amtherapistnote1` varchar(60) default NULL COMMENT 'Therapist Note',
  `amiaid` int(11) default NULL COMMENT 'Ins Adj Id',
  `amiacontdate` datetime default NULL COMMENT 'Ins Adj Last Contact Date',
  `amiarefnum` varchar(20) default NULL COMMENT 'Ins Adj Reference Number',
  `amianote1` varchar(60) default NULL COMMENT 'Ins Adj Note',
  `amauthbyuser` int(11) default NULL COMMENT 'Authorization User',
  `amauthcontdate` datetime default NULL COMMENT 'Authorization Last Contact Date',
  `amauthvisits` int(11) default NULL COMMENT 'Authorized Visits',
  `amauthexpiredate` datetime default NULL COMMENT 'Authorization Expiration',
  `amauthnote1` varchar(60) default NULL COMMENT 'Authorization Notes',
  `amauthchangedate` datetime default NULL COMMENT 'Authorization Last Changed Date',
  `amauthstatuscode` char(3) default NULL COMMENT 'Authorization Status',
  `amcanceldate` datetime default NULL COMMENT 'Cancelled Date',
  `amcancelreasoncode` char(3) default NULL COMMENT 'Cancelled Reason Code',
  `amcancelnote1` varchar(60) default NULL COMMENT 'Cancalled Note',
  `ammarketer` int(11) default NULL COMMENT 'Marketer',
  `amdx` tinyint(4) default NULL COMMENT 'Dx Flag',
  `aminiteval` tinyint(4) default NULL COMMENT 'Init Eval Flag',
  `ampr2` tinyint(4) default NULL COMMENT 'PR2 Flag',
  `NextActionDate` datetime default NULL COMMENT 'Next Action Flag',
  `StillTreating` tinyint(4) default NULL COMMENT 'Still Treating Flag',
  `MDClass` char(1) default NULL,
  `PSP` tinyint(4) default NULL COMMENT 'Doctor Class',
  `reportprotocol` tinyint(4) default NULL COMMENT 'PSP?',
  `adjgross` decimal(15,2) default NULL COMMENT 'Extimated Value',
  `amreadmit` tinyint(4) default NULL COMMENT 'Re-admit Flag',
  `amreadmitposs` tinyint(4) default NULL COMMENT 'Possible Readmit',
  `amreadmitpotential` tinyint(4) default NULL COMMENT 'Potential Readmit',
  `amsrcref` varchar(16) default NULL COMMENT 'Source Reference',
  `amsrcid` varchar(16) default NULL COMMENT 'Source Key',
  `amsrcdat` datetime default NULL COMMENT 'Source Date',
  `amcrtJnam` varchar(10) default NULL COMMENT 'Created By Job Name',
  `amcrtJusr` varchar(64) default NULL COMMENT 'Created By Job User',
  `amcrtJnum` int(11) default NULL COMMENT 'Created By Job Number',
  `amcrtJdat` datetime default NULL COMMENT 'Created By Job Date',
  `amcrtJprg` varchar(64) default NULL COMMENT 'Created By Job Program',
  `amupdJnam` varchar(10) default NULL COMMENT 'Updated By Job Name',
  `amupdJusr` varchar(64) default NULL COMMENT 'Updated By Job User',
  `amupdJnum` int(11) default NULL COMMENT 'Updated By Job Number',
  `amupdJdat` datetime default NULL COMMENT 'Updated By Job Date',
  `amupdJprg` varchar(64) default NULL COMMENT 'Updated By Job Program',
  PRIMARY KEY  (`amid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `master_accounts`
--


-- --------------------------------------------------------

--
-- Table structure for table `master_casetypes`
--

CREATE TABLE IF NOT EXISTS `master_casetypes` (
  `ctminactive` tinyint(4) NOT NULL default '0',
  `ctmcode` char(3) NOT NULL default '',
  `ctmdescription` varchar(50) NOT NULL default '',
  `ctmpcs` int(11) NOT NULL default '0' COMMENT 'Procedural Coding System',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`ctmcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_casetypes`
--

INSERT INTO `master_casetypes` (`ctminactive`, `ctmcode`, `ctmdescription`, `ctmpcs`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(0, '6', 'Workers Comp', 1, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '5', 'Personal Injury', 1, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '8', 'Private Insurance', 3, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '61', 'Workers Comp (US Dept of Labor)', 3, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `master_clinics`
--

CREATE TABLE IF NOT EXISTS `master_clinics` (
  `cminactive` tinyint(4) NOT NULL default '0' COMMENT 'Record Inactive Flag',
  `cmcnum` char(3) NOT NULL default '' COMMENT 'Clinic Number',
  `cmname` varchar(50) NOT NULL default '' COMMENT 'Clinic Name',
  `cmemail` varchar(64) NOT NULL default '' COMMENT 'Clinic E-mail Address',
  `cmphone` varchar(20) NOT NULL default '' COMMENT 'Clinic Phone Number',
  `cmfax` varchar(20) default NULL COMMENT 'Clinic Fax Number',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Record Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Record Created by User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Record Created by Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Record Last Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Record Last Updated by User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Record Last Updated by Program',
  PRIMARY KEY  (`cmcnum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_clinics`
--

INSERT INTO `master_clinics` (`cminactive`, `cmcnum`, `cmname`, `cmemail`, `cmphone`, `cmfax`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(1, 'A5', 'Dragon Eye - SA', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'A4', 'Dragon Eye - CI', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'A3', 'Dragon Eye - MC', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '@@', 'Advanced Physicians Management', 'info@apmi.net', '(999) 999-9999', NULL, '2009-08-02 12:08:09', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'A1', 'Dragon Eye - LB', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'A2', 'Dragon Eye - MB', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'A6', 'Dragon Eye - AN', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'A7', 'Dragon Eye - RS', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'A8', 'Dragon Eye - HW', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'A9', 'Dragon Eye - MV', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'AB', 'Advantage Health-Burbank', '', '661-636-0903', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'AC', 'PT & Rehab - Alhambra', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'AD', 'Advanced Dynamic - Inglewood', '', '310-330-1177', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'AL', 'Advanced Dynamic - Los Angeles', '', '', '', '0000-00-00 00:00:00', '', '', '2009-08-21 13:01:26', 'WSUR', '/index.php'),
(1, 'AP', 'Advantage Health-Panor Cty Acu', '', '818-894-4437', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'AR', 'Alliance Rehab - Culver City', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'AT', 'Dragon Eye - TM', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'AV', 'Antelope Valley PT', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'AZ', 'Aliza Zisman', '', '310-963-6162', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'B1', 'Burnwall-Downey', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'B2', 'Burnwall-N Hollywood', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'B3', 'Porter Ranch Med Ctr Acu', '', '818-831-8000', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'BA', 'Porter Ranch Med Ctr', '', '818-831-8000', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'BB', 'Alliance Rehab - Burbank', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'BC', 'Alliance Rehab - Culver City', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'BG', 'Alliance Rehab - Glendale', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'BK', 'Bakersfield - Polonco', '', '661-636-0903', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'BP', 'Alliance Rehab - Pasadena', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'BS', 'Alliance Rehab - StaFe Springs', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'C1', 'Pacific Rehab - Culver City', '', '310-836-7650', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'C2', 'Aqua Therapy Plus-Norco', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'CC', 'Reliable Rehabilitation', '', '818-271-8724', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'CH', 'Pacific Empire Chiro-Chino Hls', '', '909-464-9880', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'CL', 'Covina Aquatic-Palmdale', '', '661-224-1044', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'CP', 'New West Rehab', '', '310-537-9638', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'CV', 'Nexus-Chua Vista', '', '619-420-0869', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'DK', 'SCV PT - Dan Kryzan', '', '661-284-1984', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'DR', 'Disability Rehab Center', '', '818-887-9111', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'EC', 'Nexus-El Cajon', '', '619-579-1625', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'EN', 'East West Med Ctr', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'F1', 'Worth Pro Chiro', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'F2', 'Strohbach Chiropractic, Inc.', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'FC', 'Industrial Quality Services', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'G1', 'Dynamic Pool Therapy-Glendale', '', '818-956-7852', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'G2', 'Dynamic PT - Glendale Land', '', '818-956-7852', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'G3', 'Dynamic PT - Glendale Acu', '', '818-956-7852', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'GG', 'Performance PT - GG', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'GH', 'Physicians Choice - Granda Hls', '', '310-309-3721', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'GM', 'Garden Grove Medical Group', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'GP', 'Genesis - Simi Valley', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'GS', 'Glendora Sports Medicine', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'GW', 'Gold Wellness - Los Angeles', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'H1', 'Avalon Rehab - Huntington Bch', '', '714-596-9799', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'H2', 'Huntington Beach PT', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'H3', 'Hospitality Plus-SanBernardino', '', '909-783-9400', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'H4', 'In Motion PT - Huntington Bch', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'H5', 'Avalon Rehab - Los Angeles', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'HA', 'Alternative Healthcare-DTLA', '', '213-380-2500', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'HB', 'Alternative Healthcare-HB', '', '714-596-9799', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'HD', 'High Desert Medical', '', '760-243-2311', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'IF', 'Indefree', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'IH', 'Ina Hocutt', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'IP', 'Imperial PT', '', '818-846-1441', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'JB', 'James Beers FCE', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'L1', 'Covina Aquatic - Covina', '', '626-915-2992', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'L2', 'H2O Fitness - Glendale', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'L3', 'H2O Fitness - Costa Mesa', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'L4', 'Covina Aquatic - Acu', '', '626-967-3553', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'LF', 'Advantage PT - Lake Forest', '', '949-305-8200', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'LH', 'Bauer PT', '', '949-588-7278', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'LS', 'Laguna PT', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'MD', 'Med Net Inc', '', '562-250-2002', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'MT', 'Mangan PT - Temecula', '', '951-296-0400', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'N3', 'Physicians Choice-GranHilsAcu', '', '310-309-3721', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'O1', 'Ortho Rehab - Van Nuys', '', '818-786-9012', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'O2', 'Ortho Rehab - West Hills', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'O3', 'Ortho Rehab - Thsnd Oaks', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'OP', 'Outlook Therapy Center', '', '626-440-0991', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'P1', 'PCH - Culver City', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'P2', 'PCH - Valencia', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'P3', 'Dynamic PT - PC Acupuncture', '', '818-343-2073', '', '0000-00-00 00:00:00', '', '', '2009-08-20 16:06:26', 'WSUR', '/index.php'),
(1, 'P4', 'Pacific Ortho - DTLA', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'P5', 'Pacific Ortho - East LA', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'PB', 'PhysioCare - Bakersfield', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'PC', 'Dynamic PT - Panorama Cty Land', '', '818-343-2073', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'PD', 'Proactive - Los Angeles', '', '213-977-9300', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'PF', 'Physiocare - Fresno', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'PG', 'Performance Rehab - Gardena', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'PH', 'PT & Rehab - Pasadena', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'PL', 'Proactive - Lancaster', '', '661-945-5999', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'PM', 'Post Rehabilitation Clinic', '', '951-485-2255', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'PS', 'Physiocare - Coalinga', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'PT', 'Performance Rehab - Torrance', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'PW', 'Physicians Choice - WLA', '', '310-309-3721', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'R1', 'Dynamic Pool Therapy-Reseda', '', '818-343-2073', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'RC', 'Alied PT - Rcho Cucamonga', '', '909-948-2080', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'RD', 'Robert Doer Hand Therapy', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'RF', 'Alied PT - Fontana', '', '909-948-2080', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'RN', 'PT RN Care - Los Angeles', '', '213-250-0078', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'RP', 'Rehab Plus', '', '714-961-8288', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'RS', 'South County P.T.', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'RV', 'Alied PT - Victorville', '', '760-245-5773', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'S1', 'South Bay Spine - Hawthorne', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'S2', 'South Bay Spine - Torrance', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'S3', 'Southland Spine - Costa Mesa', '', '714-751-8110', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'S4', 'Star PT - Corona', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'S5', 'Star PT - Murietta', '', '951-600-5881', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'S6', 'Star PT - Grand Terrace', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'SB', 'Skypark - Beverly Hills', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'SD', 'Nexus - San Diego', '', '619-297-4404', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'SE', 'PT Services - Pasadena', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'SH', 'Synergy - Woodland Hills', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'SM', 'Skypark - Santa Monica', '', '310-828-7239', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'SO', 'Aquatic & PT Rehab', '', '818-784-3838', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'SP', 'Skypark - Torrance', '', '310-373-5288', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'SR', 'Southland Spine Riverside', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'ST', 'Structural Wellness Ctr - ST', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'SW', 'Sandra Wong - San Clemente', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'T1', 'Dynamic Pool Therapy - Tujunga', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'TF', 'Physical Rehab - Fontana', '', '310-537-7600', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'TL', 'Physical Rehab Svcs (Lynwood)', '', '310-537-7600', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'TN', 'Physical Rehab - N Hollywood', '', '310-537-7600', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'TP', 'Physical Rehab - Palmdale', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'TR', 'Trinity Health Care', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'UI', 'Universal Inst-San Bernardino', '', '909-383-8090', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'V1', 'Water PT - LA', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'V2', 'Water PT - Westchester', '', '310-399-7522', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'VL', 'PT & Rehab - Van Nuys', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'W3', 'Physicians Choice - WLA Acu.', '', '310-309-3721', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'WB', 'Wellness Med - Burbank', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'WC', 'Wellness Med - Culver City', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'WE', 'Wellness Med - Encino', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'WH', 'BKP Chiro - N Hollywood', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'WL', 'BKP Chiro - Lancaster', '', '661-945-1899', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'WN', 'BKP Chiro - Northridge', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'WO', 'Western Ortho Rehab-Hawthorne', '', '323-779-7996', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'WP', 'Wellness Med - Beverly Hills', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'WR', 'PT & Rehab - West Hills', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'WV', 'Dynamic Pool Therapy-W Valley', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, '01', 'WestStar Long Beach', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, '02', 'WestStar Montebello', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, '03', 'WestStar Montclair', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, '04', 'WestStar City of Industry', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, '05', 'WestStar Santa Ana', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, '06', 'WestStar Anaheim', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, '07', 'WestStar Riverside', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, '08', 'WestStar Hawthorne', '', '', NULL, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `master_groups`
--

CREATE TABLE IF NOT EXISTS `master_groups` (
  `gminactive` tinyint(4) NOT NULL default '0',
  `gmcode` char(3) NOT NULL default '',
  `gmdescription` varchar(50) NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`gmcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_groups`
--

INSERT INTO `master_groups` (`gminactive`, `gmcode`, `gmdescription`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(0, 'BIM', 'Biofeedback/Isokinetics/Myofascial', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'BIT', 'Biofeedback/Isokinetics/Therapeutic Excercises', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'BMT', 'Biofeedback/Myofascial/Therapeutic Exercises', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'IM', 'Isokinetic/Myofascial', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'IT', 'Isokinetic/Therapeutic Exercises', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'MT', 'Myfascial/Therapeutic Exercises', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'P', 'Pool Therapy', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'A', 'Acupuncture', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `master_modalities`
--

CREATE TABLE IF NOT EXISTS `master_modalities` (
  `mminactive` tinyint(4) NOT NULL default '0',
  `mmcode` char(3) NOT NULL default '',
  `mmbillcode` char(3) NOT NULL default '',
  `mmdescription` varchar(50) NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`mmcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_modalities`
--

INSERT INTO `master_modalities` (`mminactive`, `mmcode`, `mmbillcode`, `mmdescription`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(0, 'TRA', '83', 'Traction', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'ELS', '73', 'Electrical Stimulation', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'TEN', '82', 'Tens', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'PB', '33', 'Paraffin Bath', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'WP', '75', 'Whirlpool', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'IC', '80', 'Ice Compression', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'IR', '77', 'Infrared', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'US', '72', 'Ultrasound', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'IRA', '177', 'Infrared', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '15A', '188', 'Additional 15 Minutes (Acupuncture)', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'MRS', '184', 'Myofascial Release/Soft Tissue', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'EA', '243', 'Electro Acupuncture', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '15P', '198', 'Additional 15 Minutes (Pool Therapy)', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `master_proceduralcodingsystems`
--

CREATE TABLE IF NOT EXISTS `master_proceduralcodingsystems` (
  `pcsminactive` tinyint(4) NOT NULL default '0',
  `pcsmid` int(11) NOT NULL auto_increment,
  `pcsmeffdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Effective Date',
  `pcsmcode` varchar(10) NOT NULL default '',
  `pcsmdescription` varchar(50) NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`pcsmid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `master_proceduralcodingsystems`
--

INSERT INTO `master_proceduralcodingsystems` (`pcsminactive`, `pcsmid`, `pcsmeffdate`, `pcsmcode`, `pcsmdescription`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(0, 1, '0000-00-00 00:00:00', 'CPT', 'AMA Current Procedural Coding System', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 2, '0000-00-00 00:00:00', 'HCPCS', 'HCFA Common Procedural Coding System', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 3, '0000-00-00 00:00:00', 'RVS', 'US Dept Of Labor RVS Codes', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 4, '0000-00-00 00:00:00', 'MC', 'Medicare Coding System', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `master_procedures`
--

CREATE TABLE IF NOT EXISTS `master_procedures` (
  `pminactive` tinyint(4) NOT NULL default '0',
  `pmcode` char(3) NOT NULL default '',
  `pmbillcode` char(3) NOT NULL default '',
  `pmdescription` varchar(50) default NULL,
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`pmcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_procedures`
--

INSERT INTO `master_procedures` (`pminactive`, `pmcode`, `pmbillcode`, `pmdescription`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(0, 'BIO', '402', 'Biofeedback', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'ISO', '95', 'Isokinetics', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'MYO', '84', 'Myofascial', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'TE', '81', 'Therapeutic Exercises', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'P', '199', 'Pool Therapy', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'A', '189', 'Acupuncture', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `master_submitstatus`
--

CREATE TABLE IF NOT EXISTS `master_submitstatus` (
  `ssminactive` tinyint(4) NOT NULL default '0',
  `ssmcode` char(3) character set latin1 NOT NULL default '',
  `ssmdescription` varchar(50) character set latin1 NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) collate latin1_general_ci NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) collate latin1_general_ci NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) collate latin1_general_ci NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) collate latin1_general_ci NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`ssmcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `master_submitstatus`
--

INSERT INTO `master_submitstatus` (`ssminactive`, `ssmcode`, `ssmdescription`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(0, '0', 'Data Entry - New Treatment', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '100', 'Submitted to WestStar', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '300', 'Review - Needs additional information', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '500', 'Patient Information Entry', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '700', 'Billing Information Entry', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '800', 'Billed', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '970', 'Revised', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '980', 'Ignored', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, '990', 'Cancelled', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `master_treatmenttypes`
--

CREATE TABLE IF NOT EXISTS `master_treatmenttypes` (
  `ttminactive` tinyint(4) NOT NULL default '0',
  `ttmcode` char(3) NOT NULL default '',
  `ttmdescription` varchar(50) NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`ttmcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_treatmenttypes`
--

INSERT INTO `master_treatmenttypes` (`ttminactive`, `ttmcode`, `ttmdescription`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(0, 'PT', 'Physical Therapy', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'OT', 'Occupational Therapy', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'A', 'Acupuncture', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'P', 'Pool Therapy', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `master_user`
--

CREATE TABLE IF NOT EXISTS `master_user` (
  `uminactive` tinyint(4) NOT NULL default '0',
  `umuser` varchar(16) NOT NULL default '',
  `umpass` varchar(32) NOT NULL default '',
  `umname` varchar(64) NOT NULL default '',
  `umemail` varchar(64) NOT NULL default '',
  `umclinic` char(2) NOT NULL default '',
  `umcreated` datetime NOT NULL default '0000-00-00 00:00:00',
  `umlastlogin` datetime default NULL,
  `umipaddress` varchar(15) NOT NULL default '',
  `umhomepage` varchar(20) NOT NULL default 'treatment',
  `umrole` int(11) NOT NULL default '0',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`umuser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_user`
--

INSERT INTO `master_user` (`uminactive`, `umuser`, `umpass`, `umname`, `umemail`, `umclinic`, `umcreated`, `umlastlogin`, `umipaddress`, `umhomepage`, `umrole`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(0, 'Administrator', '5ea160a8cf8103bc7e2798aaa9d5b4c0', 'Administrator', 'administrator@apmi.net', '@@', '2009-07-22 00:00:00', NULL, '999.999.999.999', 'user', 99, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'WSPatient', '1b92fca1d38914c54907c844ebae4d45', 'WestStar Patient Entry', 'testuser@email.com', '@@', '2009-08-08 15:57:35', NULL, '', 'patientdashboard', 21, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'sunnispoon', '5f4dcc3b5aa765d61d8327deb882cf99', 'Sunni Spoon', 'sspoon@e-easeinfosys.com', 'AL', '2009-07-22 14:48:30', NULL, '999.999.999.999', 'treatment', 10, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'WSBilling', '1b92fca1d38914c54907c844ebae4d45', 'WestStar Billing', 'testuser@email.com', '@@', '2009-08-07 12:59:13', NULL, '', 'billingdashboard', 22, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'jbadam', 'f22e137164f199b0a33023817263c17f', 'JB', 'jbadam@tacorporation.com', 'C1', '2009-08-07 13:23:21', NULL, '', 'treatment', 10, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'ClinicAdmin', 'd0ec09cd506a26162cf57a8af9855b91', 'Clinic Administrator', 'administrator@apmi.net', '@@', '2009-07-29 22:32:50', NULL, '', 'clinic', 99, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'sunnipatient', '5f4dcc3b5aa765d61d8327deb882cf99', 'sunni patient', 'testuser@email.com', 'AL', '2009-08-06 10:43:19', NULL, '', 'patient', 23, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'WSUR', '1b92fca1d38914c54907c844ebae4d45', 'WestStar UR', 'nancyv@apmi.net', '@@', '2009-08-04 19:21:28', NULL, '', 'treatmentdashboard', 23, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(1, 'sunniadmin', '5f4dcc3b5aa765d61d8327deb882cf99', 'Sunni Spoon Administrator Profile', 'sspoon@e-easeinfosys.com', '@@', '2009-08-04 17:19:35', NULL, '', 'treatment', 99, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'ClinicUser', '1b92fca1d38914c54907c844ebae4d45', 'A Clinic User', 'testuser@email.com', 'C1', '2009-08-08 12:59:41', NULL, '', 'treatment', 10, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'mgarcia', '5f4dcc3b5aa765d61d8327deb882cf99', 'Mona Garcia', 'mgarcia@apmi.net', '@@', '2009-08-12 17:23:19', NULL, '', 'customerservice', 25, '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `master_visittypes`
--

CREATE TABLE IF NOT EXISTS `master_visittypes` (
  `vtminactive` tinyint(4) NOT NULL default '0',
  `vtmcode` char(3) NOT NULL default '',
  `vtmdescription` varchar(50) NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`vtmcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_visittypes`
--

INSERT INTO `master_visittypes` (`vtminactive`, `vtmcode`, `vtmdescription`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(0, 'NPE', 'New Patient Evaluation', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'ST', 'Standard Treatment', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'REE', 'Re-Evaluation', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'RE', 'Re-Admit', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'REW', 'Re-Admit (w/o Report)', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'DC', 'Discharge', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(0, 'DCW', 'Discharge (w/o Report)', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `paid` int(11) NOT NULL auto_increment COMMENT 'Patient Identifier',
  `painactive` tinyint(4) NOT NULL default '0' COMMENT 'Patient Active/Inactive',
  `pafirst` varchar(30) default NULL COMMENT 'First Name',
  `pamiddle` varchar(30) default NULL COMMENT 'Middle',
  `palast` varchar(30) default NULL COMMENT 'Last Name',
  `pasex` char(1) default NULL COMMENT 'Gender',
  `passn` varchar(9) default NULL COMMENT 'Social Security Number',
  `padob` datetime default NULL COMMENT 'Date of Birth',
  `paphone` varchar(20) NOT NULL default '' COMMENT 'Phone Number',
  `paaddress` varchar(30) NOT NULL default '0' COMMENT 'Address',
  `pacity` varchar(30) NOT NULL default '0' COMMENT 'City',
  `pastate` char(2) NOT NULL default 'CA' COMMENT 'State',
  `pazip` varchar(9) NOT NULL default '' COMMENT 'Zip',
  `panote` varchar(60) default NULL COMMENT 'Patient Note',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`paid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`paid`, `painactive`, `pafirst`, `pamiddle`, `palast`, `pasex`, `passn`, `padob`, `paphone`, `paaddress`, `pacity`, `pastate`, `pazip`, `panote`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(1, 1, 'SUNNI', 'PAUL', 'SPOON', 'M', '555339999', '1967-05-03 00:00:00', '999-999-9999', '99999 ANYSTREET', 'ANYCITY', 'CA', '999990000', 'This is just a patient note edited', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(2, 0, 'J', 'B', 'ADAM', 'M', '999776666', '1967-01-01 00:00:00', '999-999-9999', '99999 ANYSTREET', 'ANYCITY', 'CA', '999990000', 'This is JB Patient', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(11, 0, '', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(12, 0, '', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(10, 0, 'SUNNI', 'PAUL', 'SPOON', 'M', '555334444', '1967-01-01 00:00:00', '999-999-9999', '99999 ANYSTREET', 'ANYCITY', 'CA', '999990000', 'This is just a patient note', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(9, 0, 'CONNIE', '', 'MEDRIOS', 'F', '555339999', '1967-01-01 00:00:00', '999-999-9999', '99999 ANYSTREET', 'ANYCITY', 'CA', '999990000', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `procedure_groups`
--

CREATE TABLE IF NOT EXISTS `procedure_groups` (
  `gmcode` char(3) NOT NULL default '',
  `pmcode` char(3) NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`gmcode`,`pmcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `procedure_groups`
--

INSERT INTO `procedure_groups` (`gmcode`, `pmcode`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
('BIM', 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('BIM', 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('BIM', 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('BIT', 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('BIT', 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('BIT', 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('BMT', 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('BMT', 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('BMT', 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('IM', 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('IM', 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('IT', 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('IT', 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('MT', 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('MT', 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('P', 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('A', 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `therapists`
--

CREATE TABLE IF NOT EXISTS `therapists` (
  `thid` int(11) NOT NULL default '0' COMMENT 'Therapist Id',
  `thcontdate` datetime default NULL COMMENT 'Therapist Last Contact',
  `threfnum` varchar(20) default NULL COMMENT 'Therapist Reference Number',
  `thnote` varchar(60) default NULL COMMENT 'Therapist Note',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`thid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `therapists`
--


-- --------------------------------------------------------

--
-- Table structure for table `treatmenttype_modalities`
--

CREATE TABLE IF NOT EXISTS `treatmenttype_modalities` (
  `ttmcode` char(3) NOT NULL default '',
  `mmcode` char(3) NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treatmenttype_modalities`
--

INSERT INTO `treatmenttype_modalities` (`ttmcode`, `mmcode`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
('PT', 'TRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'WP', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'IC', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'IR', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'US', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'TRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'WP', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'IC', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'IR', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'US', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('A', 'IRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('A', '15A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('A', 'MRS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('A', 'EA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('P', '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `treatmenttype_procedures`
--

CREATE TABLE IF NOT EXISTS `treatmenttype_procedures` (
  `ttmcode` char(3) NOT NULL default '',
  `pmcode` char(3) NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treatmenttype_procedures`
--

INSERT INTO `treatmenttype_procedures` (`ttmcode`, `pmcode`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
('PT', 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('A', 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('P', 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `treatmenttype_procedure_groups`
--

CREATE TABLE IF NOT EXISTS `treatmenttype_procedure_groups` (
  `ttmcode` char(3) NOT NULL default '',
  `gmcode` char(3) NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treatmenttype_procedure_groups`
--

INSERT INTO `treatmenttype_procedure_groups` (`ttmcode`, `gmcode`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
('PT', 'BIM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'BIT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'IM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'IT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('PT', 'MT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'BIM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'BIT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'IM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'IT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('OT', 'MT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('A', 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
('P', 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `treatment_header`
--

CREATE TABLE IF NOT EXISTS `treatment_header` (
  `thid` int(11) NOT NULL auto_increment,
  `thcnum` char(3) NOT NULL default '',
  `thdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `thpnum` varchar(6) NOT NULL default '',
  `thlname` varchar(30) NOT NULL default '',
  `thfname` varchar(30) NOT NULL default '',
  `thctmcode` char(3) NOT NULL default '0',
  `thvtmcode` char(3) NOT NULL default '0',
  `thttmcode` char(3) NOT NULL default '0',
  `thsbmstatus` int(11) NOT NULL default '0',
  `thsbmdate` datetime default NULL,
  `thsbmuser` varchar(16) NOT NULL default '',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`thid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=132 ;

--
-- Dumping data for table `treatment_header`
--

INSERT INTO `treatment_header` (`thid`, `thcnum`, `thdate`, `thpnum`, `thlname`, `thfname`, `thctmcode`, `thvtmcode`, `thttmcode`, `thsbmstatus`, `thsbmdate`, `thsbmuser`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(75, 'AL', '2009-08-03 00:00:00', '', 'TEST99', 'TEST99', '6', 'ST', 'P', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(76, 'AL', '1999-11-30 00:00:00', '', 'TEST98', 'TEST98', '6', 'ST', 'PT', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(70, 'AL', '2009-08-03 00:00:00', '', 'TEST12', 'TEST13', '6', 'ST', 'OT', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(69, 'AL', '2009-08-03 00:00:00', '', 'TEST12', 'TEST12', '6', 'ST', 'OT', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(68, 'AL', '2009-08-03 00:00:00', '', 'TEST11', 'TEST11', '6', 'ST', 'OT', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(67, 'AL', '2009-08-03 00:00:00', '', 'TEST10', 'TEST10', '6', 'ST', 'OT', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(66, 'AL', '2009-08-03 00:00:00', '', 'TEST9', 'TEST9', '6', 'ST', 'PT', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(65, 'AL', '2009-08-03 00:00:00', '', 'TEST8', 'TEST8', '6', 'ST', 'PT', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(64, 'AL', '2009-08-03 00:00:00', '', 'TEST7', 'TEST7', '6', 'ST', 'PT', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(63, 'AL', '2009-08-03 00:00:00', '', 'TEST', 'TEST', '6', 'ST', 'PT', 900, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(62, 'AL', '2009-07-13 00:00:00', '', 'TEST4', 'TEST4', '6', 'ST', 'PT', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(61, 'AL', '2009-07-03 00:00:00', '', 'TEST3', 'TEST3', '6', 'ST', 'PT', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(57, 'AL', '2009-08-03 00:00:00', '', 'TEST', 'TEST', '6', 'ST', 'PT', 900, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(58, 'AL', '2009-08-03 00:00:00', '', 'SPOON', 'SUNNI', '6', 'ST', 'A', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(59, 'AL', '2009-08-03 00:00:00', '', 'TEST', 'TEST', '6', 'ST', 'A', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(60, 'AL', '2008-08-03 00:00:00', '', 'TEST2', 'TEST', '6', 'ST', 'P', 700, '2009-08-03 19:02:44', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(78, 'AL', '2009-08-04 00:00:00', '', 'TEST2', 'TEST2', '6', 'ST', 'A', 700, '2009-08-04 16:39:03', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(79, 'AL', '2009-08-04 00:00:00', '', 'TEST3', 'TEST3', '6', 'ST', 'P', 700, '2009-08-04 16:39:03', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(80, 'AL', '2009-08-04 00:00:00', '', 'TEST4', 'TEST4', '6', 'ST', 'PT', 700, '2009-08-04 16:39:03', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(81, 'AL', '2008-08-04 00:00:00', '', 'TEST5', 'TEST5', '6', 'ST', 'P', 700, '2009-08-04 16:39:03', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(82, 'AL', '2009-07-17 00:00:00', '', 'TEST5', 'TEST5', '6', 'NPE', 'P', 700, '2009-08-04 16:39:03', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(83, 'AL', '2009-08-14 00:00:00', '', 'TEST6', 'TEST6', '6', 'ST', 'PT', 700, '2009-08-04 16:39:03', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(84, 'AL', '2009-08-04 00:00:00', '', 'TEST6', 'TEST6', '6', 'ST', 'A', 700, '2009-08-05 00:19:11', 'sunniadmin', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(85, 'AL', '2009-08-04 00:00:00', '', 'TEST7', 'TEST7', '6', 'ST', 'A', 700, '2009-08-05 00:19:11', 'sunniadmin', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(86, 'AL', '2009-08-04 00:00:00', '', 'TEST8', 'TEST8', '6', 'NPE', 'P', 700, '2009-08-05 00:19:11', 'sunniadmin', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(87, 'AL', '2009-08-04 00:00:00', '', 'TEST9', 'TEST9', '6', 'DC', 'P', 700, '2009-08-05 00:19:11', 'sunniadmin', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(88, 'AL', '2009-08-04 00:00:00', '', 'TEST10', 'TEST10', '5', 'ST', 'P', 700, '2009-08-05 00:19:11', 'sunniadmin', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(89, 'C1', '2009-08-04 00:00:00', '', 'TEST1', 'TEST1', '6', 'ST', 'P', 0, NULL, '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(90, 'AL', '2009-08-04 00:00:00', '', 'TETT', 'TEST', '6', 'ST', 'P', 700, '2009-08-05 00:19:11', 'sunniadmin', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(91, 'AL', '2009-08-04 00:00:00', '', 'TEST', 'TEST', '5', 'ST', 'P', 900, '2009-08-05 00:19:11', 'sunniadmin', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(92, 'AL', '2009-08-04 00:00:00', '', 'TEST', 'TEST', '5', 'NPE', 'A', 700, '2009-08-05 00:19:11', 'sunniadmin', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(93, 'AL', '2009-08-03 00:00:00', '', 'SPOON', 'BJ', '5', 'NPE', 'A', 700, '2009-08-05 00:19:11', 'sunniadmin', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(94, 'AL', '2009-08-06 00:00:00', '', 'SPOON', 'SUNNI', '6', 'ST', 'PT', 700, '2009-08-06 18:30:08', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(95, 'AL', '2009-08-07 00:00:00', '', 'SPOON', 'SUNNI', '5', 'REE', 'OT', 700, '2009-08-07 00:00:00', 'sunnispoon', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(100, 'AL', '2009-08-08 00:00:00', '', 'TEST', 'TEST', '6', 'ST', 'A', 700, '2009-08-08 15:37:31', 'ClinicUser', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(97, 'AL', '2009-08-07 00:00:00', '', 'VILLA', 'NANCY', '6', 'ST', 'A', 900, '2009-08-07 00:00:00', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(98, 'AL', '2009-08-07 00:00:00', '', 'VILLA', 'NANCY', '6', 'ST', 'P', 700, '2009-08-07 11:54:54', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(99, 'AL', '2009-08-07 00:00:00', '', 'TESTJB', 'TESTJB', '6', 'ST', 'P', 700, '2009-08-07 13:34:41', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(101, 'AL', '2009-08-08 00:00:00', '', 'TEST2', 'TEST', '6', 'ST', 'PT', 700, '2009-08-08 15:37:31', 'ClinicUser', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'AL', '2009-08-08 00:00:00', '', 'TEST3', 'TEST', '6', 'ST', 'PT', 700, '2009-08-08 15:37:31', 'ClinicUser', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(103, 'AL', '2009-08-08 00:00:00', '', 'SPOON', 'SUNNI', '6', 'ST', 'PT', 700, '2009-08-08 16:14:51', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(104, 'AL', '2009-08-08 00:00:00', '', 'SPOON', 'SPECIAL', '6', 'ST', 'PT', 900, '2009-08-08 18:34:49', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'AL', '2009-08-08 00:00:00', '', 'SPOON', 'SPECIAL2', '6', 'ST', 'OT', 900, '2009-08-08 19:01:27', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(106, 'AL', '2009-08-08 00:00:00', '', 'SPOON', 'SPECIAL3', '6', 'ST', 'OT', 900, '2009-08-08 19:03:32', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(107, 'AL', '2009-08-08 00:00:00', '', 'SPOON', 'SPECIAL4', '6', 'ST', 'PT', 700, '2009-08-08 19:04:22', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(108, 'AL', '2009-08-09 00:00:00', '', 'O''MALLEY', 'SUNNI', '6', 'ST', 'P', 900, '2009-08-09 08:33:28', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(109, 'AL', '2009-08-09 00:00:00', '', 'O''MALLEY', 'SUNNI', '6', 'ST', 'A', 500, '2009-08-09 08:34:15', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(110, 'AL', '2009-08-09 00:00:00', '', 'TEST', 'TEST', '6', 'NPE', 'PT', 900, '2009-08-09 08:42:14', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(111, 'AL', '2009-08-09 00:00:00', '', 'TEST', 'TEST', '6', 'REE', 'PT', 900, '2009-08-09 08:42:43', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(112, 'AL', '2009-08-09 00:00:00', '', 'TEST', 'TEST', '6', 'RE', 'PT', 900, '2009-08-09 08:42:57', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(113, 'AL', '2009-08-09 00:00:00', '', 'TEST', 'TEST', '6', 'REW', 'PT', 700, '2009-08-09 08:43:11', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(114, 'AL', '2009-08-09 00:00:00', '', 'TEST', 'TEST', '6', 'DC', 'PT', 900, '2009-08-09 08:43:30', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(115, 'AL', '2009-08-09 00:00:00', '', 'TEST', 'TEST', '6', 'DCW', 'PT', 900, '2009-08-09 08:43:44', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(117, 'AL', '2009-08-12 00:00:00', '', 'MEDEIROS', 'CONNIE', '6', 'ST', 'A', 700, '2009-08-12 15:54:49', 'ClinicUser', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(118, 'AL', '2009-08-12 00:00:00', '', 'ADAM', 'JB', '6', 'ST', 'PT', 500, '2009-08-12 15:54:49', 'ClinicUser', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(120, 'AL', '2009-08-14 00:00:00', '', 'ADAM', 'JB', '6', 'ST', 'P', 100, '2009-08-13 09:49:18', 'ClinicUser', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(121, 'AL', '2009-08-14 00:00:00', '', 'SANCHEZ', 'GLADYS', '6', 'ST', 'PT', 150, '2009-08-13 09:49:18', 'ClinicUser', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(122, '', '2009-08-14 00:00:00', '', 'SPOON', 'SUNNI', '6', 'ST', 'PT', 900, '2009-08-13 10:33:48', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(124, 'AL', '2009-08-14 00:00:00', '', 'TEST2', 'TEST2', '6', 'ST', 'PT', 100, '2009-08-19 08:25:20', 'jbadam', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(125, 'AL', '2009-08-14 00:00:00', '', 'TEST3', 'TEST3', '6', 'ST', 'P', 500, '2009-08-19 08:25:20', 'jbadam', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(126, 'AL', '2009-08-14 00:00:00', '', 'TEST4', 'TEST4', '6', 'ST', 'A', 100, '2009-08-19 08:25:20', 'jbadam', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(127, 'AL', '2009-08-19 00:00:00', '', 'MORA', 'CYNTHIA', '5', 'NPE', 'PT', 100, '2009-08-20 12:02:15', 'ClinicUser', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(128, 'AL', '2009-08-19 00:00:00', '', 'MCDANIELS', 'MIKE', '6', 'NPE', 'PT', 100, '2009-08-20 12:02:15', 'ClinicUser', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(129, 'AL', '2009-08-19 00:00:00', '', 'M', 'CONNIE', '6', 'ST', 'PT', 500, '2009-08-20 12:02:15', 'ClinicUser', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(130, 'C1', '2009-08-20 00:00:00', '', 'SPOON', 'SUNNI', '6', 'ST', 'A', 0, NULL, '', '2009-08-20 17:17:11', 'ClinicUser', '/index.php', '2009-08-20 18:03:23', 'ClinicUser', '/index.php'),
(131, 'G3', '2009-08-21 00:00:00', '', 'SPOON', 'SUNNI', '6', 'ST', 'PT', 100, '2009-08-21 12:30:47', '', '2009-08-21 12:30:47', 'Administrator', '/index.php', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `treatment_header_history`
--

CREATE TABLE IF NOT EXISTS `treatment_header_history` (
  `thhid` int(11) NOT NULL default '0',
  `thhdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `thhuser` varchar(16) NOT NULL default '',
  `thhhide` tinyint(4) NOT NULL default '0',
  `thhapplication` varchar(16) NOT NULL default '0',
  `thhmsg` varchar(10) NOT NULL default '0',
  `thhtext` varchar(60) NOT NULL default '0',
  `thhquery` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treatment_header_history`
--

INSERT INTO `treatment_header_history` (`thhid`, `thhdate`, `thhuser`, `thhhide`, `thhapplication`, `thhmsg`, `thhtext`, `thhquery`) VALUES
(101, '2009-08-08 16:01:39', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''101'''),
(102, '2009-08-08 15:56:19', 'billing', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''102'''),
(102, '2009-08-08 15:54:14', 'nvilla', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''102'''),
(101, '2009-08-08 15:54:11', 'nvilla', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [300]', 'UPDATE treatment_header SET thsbmStatus=''300'' WHERE thid=''101'''),
(100, '2009-08-08 15:54:00', 'nvilla', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''100'''),
(100, '2009-08-08 15:54:07', 'nvilla', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''100'''),
(100, '2009-08-08 16:02:02', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''100'''),
(100, '2009-08-08 16:02:06', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''100'''),
(101, '2009-08-08 16:02:10', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''101'''),
(102, '2009-08-08 16:02:13', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''102'''),
(100, '2009-08-08 16:02:43', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''100'''),
(101, '2009-08-08 16:02:45', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''101'''),
(102, '2009-08-08 16:02:47', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''102'''),
(61, '2009-08-08 16:04:05', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''61'''),
(62, '2009-08-08 16:04:07', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''62'''),
(59, '2009-08-08 16:04:08', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''59'''),
(84, '2009-08-08 16:04:10', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''84'''),
(86, '2009-08-08 16:04:13', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''86'''),
(88, '2009-08-08 16:04:14', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''88'''),
(91, '2009-08-08 16:04:16', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''91'''),
(95, '2009-08-08 16:04:18', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''95'''),
(81, '2009-08-08 16:04:45', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''81'''),
(61, '2009-08-08 16:04:48', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''61'''),
(62, '2009-08-08 16:04:49', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''62'''),
(82, '2009-08-08 16:04:51', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''82'''),
(68, '2009-08-08 16:04:53', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''68'''),
(59, '2009-08-08 16:04:54', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''59'''),
(78, '2009-08-08 16:04:56', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''78'''),
(79, '2009-08-08 16:04:57', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''79'''),
(80, '2009-08-08 16:04:59', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''80'''),
(84, '2009-08-08 16:05:05', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''84'''),
(86, '2009-08-08 16:05:06', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''86'''),
(88, '2009-08-08 16:05:08', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''88'''),
(91, '2009-08-08 16:05:09', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''91'''),
(94, '2009-08-08 16:05:10', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''94'''),
(95, '2009-08-08 16:05:12', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''95'''),
(98, '2009-08-08 16:05:14', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''98'''),
(99, '2009-08-08 16:05:16', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''99'''),
(81, '2009-08-08 16:05:34', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''81'''),
(61, '2009-08-08 16:05:36', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''61'''),
(62, '2009-08-08 16:05:37', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''62'''),
(82, '2009-08-08 16:05:38', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''82'''),
(68, '2009-08-08 16:05:39', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''68'''),
(67, '2009-08-08 16:05:40', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''67'''),
(66, '2009-08-08 16:05:41', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''66'''),
(65, '2009-08-08 16:05:42', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''65'''),
(64, '2009-08-08 16:05:43', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''64'''),
(63, '2009-08-08 16:05:44', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''63'''),
(57, '2009-08-08 16:05:45', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''57'''),
(58, '2009-08-08 16:05:46', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''58'''),
(59, '2009-08-08 16:05:47', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''59'''),
(93, '2009-08-08 16:05:48', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''93'''),
(78, '2009-08-08 16:05:49', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''78'''),
(79, '2009-08-08 16:05:51', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''79'''),
(80, '2009-08-08 16:06:02', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''80'''),
(90, '2009-08-08 16:06:03', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''90'''),
(91, '2009-08-08 16:06:04', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''91'''),
(88, '2009-08-08 16:06:06', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''88'''),
(92, '2009-08-08 16:06:07', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''92'''),
(84, '2009-08-08 16:06:08', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''84'''),
(85, '2009-08-08 16:06:09', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''85'''),
(86, '2009-08-08 16:06:10', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''86'''),
(87, '2009-08-08 16:06:11', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''87'''),
(94, '2009-08-08 16:06:12', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''94'''),
(95, '2009-08-08 16:06:14', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''95'''),
(98, '2009-08-08 16:06:15', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''98'''),
(99, '2009-08-08 16:06:16', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''99'''),
(83, '2009-08-08 16:06:18', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''83'''),
(97, '2009-08-08 16:06:45', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''97'''),
(97, '2009-08-08 16:06:47', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''97'''),
(97, '2009-08-08 16:07:07', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''97'''),
(0, '2009-08-08 16:14:51', 'WSUR', 0, 'treatment', 'UPDATE', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-08 0:0:0'', '''', ''SPOON'', ''SUNNI'', ''6'', ''ST'', ''PT'', ''100'', ''2009-08-08 16:14:51'', ''2009-08-08 16:14:51'', ''WSUR'')'),
(0, '2009-08-08 16:14:51', 'WSUR', 0, 'treatment', 'UPDATE', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIM'', ''2009-08-08 16:14:51'', ''WSUR'')'),
(0, '2009-08-08 16:14:51', 'WSUR', 0, 'treatment', 'UPDATE', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-08 16:14:51', 'WSUR', 0, 'treatment', 'UPDATE', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''ELS'', ''2009-08-08 16:14:51'', ''WSUR'')'),
(0, '2009-08-08 16:14:51', 'WSUR', 0, 'treatment', 'UPDATE', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''IC'', ''2009-08-08 16:14:51'', ''WSUR'')'),
(103, '2009-08-08 16:15:12', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''103'''),
(103, '2009-08-08 16:15:29', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''103'''),
(103, '2009-08-08 16:15:47', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [300]', 'UPDATE treatment_header SET thsbmStatus=''300'' WHERE thid=''103'''),
(103, '2009-08-08 16:16:05', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [150]', 'UPDATE treatment_header SET thsbmStatus=''150'' WHERE thid=''103'''),
(103, '2009-08-08 16:18:34', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''103'''),
(103, '2009-08-08 16:18:51', 'WSBilling', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''103'''),
(0, '2009-08-08 18:34:49', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-08 0:0:0'', '''', ''SPOON'', ''SPECIAL'', ''6'', ''ST'', ''PT'', ''100'', ''2009-08-08 18:34:49'', ''2009-08-08 18:34:49'', ''WSUR'')'),
(0, '2009-08-08 18:34:49', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIO'', ''2009-08-08 18:34:49'', ''WSUR'')'),
(104, '2009-08-08 18:45:22', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-08 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SPECIAL'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''PT'', updDate=''2009-08-08 18:45:22'', updUser=''WSUR'' WHERE thid=''104'''),
(104, '2009-08-08 18:45:22', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''104'''),
(104, '2009-08-08 18:45:22', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''104'''),
(104, '2009-08-08 18:45:22', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''104'''),
(104, '2009-08-08 18:45:22', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''104'', ''ISO'', ''2009-08-08 18:45:22'', ''WSUR'')'),
(104, '2009-08-08 18:50:41', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-08 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SPECIAL'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''PT'', updDate=''2009-08-08 18:50:41'', updUser=''WSUR'' WHERE thid=''104'''),
(104, '2009-08-08 18:50:41', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''104'''),
(104, '2009-08-08 18:50:41', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''104'''),
(104, '2009-08-08 18:50:41', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''104'''),
(104, '2009-08-08 18:50:41', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''104'', ''TE'', ''2009-08-08 18:50:41'', ''WSUR'')'),
(101, '2009-08-08 18:50:55', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-08 0:0:0'', thpnum='''', thlname=''TEST2'', thfname=''TEST'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''PT'', updDate=''2009-08-08 18:50:55'', updUser=''WSUR'' WHERE thid=''101'''),
(101, '2009-08-08 18:50:55', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''101'''),
(101, '2009-08-08 18:50:55', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''101'''),
(101, '2009-08-08 18:50:55', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''101'''),
(101, '2009-08-08 18:50:55', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''101'', ''BIO'', ''2009-08-08 18:50:55'', ''WSUR'')'),
(101, '2009-08-08 18:50:55', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''101'', ''ISO'', ''2009-08-08 18:50:55'', ''WSUR'')'),
(101, '2009-08-08 19:00:17', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-08 0:0:0'', thpnum='''', thlname=''TEST2'', thfname=''TEST'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''PT'', updDate=''2009-08-08 19:00:17'', updUser=''WSUR'' WHERE thid=''101'''),
(101, '2009-08-08 19:00:17', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''101'''),
(101, '2009-08-08 19:00:17', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''101'''),
(101, '2009-08-08 19:00:17', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''101'''),
(101, '2009-08-08 19:00:17', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''101'', ''BIO'', ''2009-08-08 19:00:17'', ''WSUR'')'),
(101, '2009-08-08 19:00:17', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''101'', ''ISO'', ''2009-08-08 19:00:17'', ''WSUR'')'),
(101, '2009-08-08 19:00:17', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''101'', ''MYO'', ''2009-08-08 19:00:17'', ''WSUR'')'),
(101, '2009-08-08 19:00:17', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''101'', ''TE'', ''2009-08-08 19:00:17'', ''WSUR'')'),
(101, '2009-08-08 19:00:26', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-08 0:0:0'', thpnum='''', thlname=''TEST2'', thfname=''TEST'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''PT'', updDate=''2009-08-08 19:00:26'', updUser=''WSUR'' WHERE thid=''101'''),
(101, '2009-08-08 19:00:26', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''101'''),
(101, '2009-08-08 19:00:26', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''101'''),
(101, '2009-08-08 19:00:26', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''101'''),
(101, '2009-08-08 19:00:26', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''101'', ''ISO'', ''2009-08-08 19:00:26'', ''WSUR'')'),
(101, '2009-08-08 19:00:26', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''101'', ''MYO'', ''2009-08-08 19:00:26'', ''WSUR'')'),
(101, '2009-08-08 19:00:26', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''101'', ''TE'', ''2009-08-08 19:00:26'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-08 0:0:0'', '''', ''SPOON'', ''SPECIAL2'', ''6'', ''ST'', ''OT'', ''100'', ''2009-08-08 19:01:27'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIM'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TRA'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''ELS'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TEN'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''PB'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''WP'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''IC'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''IR'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''US'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''MYO'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:01:27', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TE'', ''2009-08-08 19:01:27'', ''WSUR'')'),
(0, '2009-08-08 19:03:32', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-08 0:0:0'', '''', ''SPOON'', ''SPECIAL3'', ''6'', ''ST'', ''OT'', ''100'', ''2009-08-08 19:03:32'', ''2009-08-08 19:03:32'', ''WSUR'')'),
(0, '2009-08-08 19:03:32', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BMT'', ''2009-08-08 19:03:32'', ''WSUR'')'),
(0, '2009-08-08 19:03:32', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-08 19:03:32', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIO'', ''2009-08-08 19:03:32'', ''WSUR'')'),
(0, '2009-08-08 19:03:32', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''ISO'', ''2009-08-08 19:03:32'', ''WSUR'')'),
(0, '2009-08-08 19:03:32', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TE'', ''2009-08-08 19:03:32'', ''WSUR'')'),
(106, '2009-08-08 19:03:54', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''106'''),
(105, '2009-08-08 19:03:56', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''105'''),
(104, '2009-08-08 19:03:59', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''104'''),
(0, '2009-08-08 19:04:22', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-08 0:0:0'', '''', ''SPOON'', ''SPECIAL4'', ''6'', ''ST'', ''PT'', ''100'', ''2009-08-08 19:04:22'', ''2009-08-08 19:04:22'', ''WSUR'')'),
(0, '2009-08-08 19:04:22', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIM'', ''2009-08-08 19:04:22'', ''WSUR'')'),
(0, '2009-08-08 19:04:22', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-08 19:04:22', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIO'', ''2009-08-08 19:04:22'', ''WSUR'')'),
(0, '2009-08-08 19:04:22', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''ISO'', ''2009-08-08 19:04:22'', ''WSUR'')'),
(0, '2009-08-08 19:04:22', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''MYO'', ''2009-08-08 19:04:22'', ''WSUR'')'),
(107, '2009-08-08 19:04:41', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-08 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SPECIAL4'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''PT'', updDate=''2009-08-08 19:04:41'', updUser=''WSUR'' WHERE thid=''107'''),
(107, '2009-08-08 19:04:41', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''107'''),
(107, '2009-08-08 19:04:41', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''107'''),
(107, '2009-08-08 19:04:41', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''107'''),
(107, '2009-08-08 19:04:41', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''107'', ''BIO'', ''2009-08-08 19:04:41'', ''WSUR'')'),
(0, '2009-08-09 08:33:28', 'Administrator', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-09 0:0:0'', '''', ''O\\''MALLEY'', ''SUNNI'', ''6'', ''ST'', ''P'', ''100'', ''2009-08-09 08:33:28'', ''2009-08-09 08:33:28'', ''Administrator'')'),
(0, '2009-08-09 08:33:28', 'Administrator', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''P'', ''2009-08-09 08:33:28'', ''Administrator'')'),
(0, '2009-08-09 08:33:28', 'Administrator', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-09 08:33:28', 'Administrator', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''15P'', ''2009-08-09 08:33:28'', ''Administrator'')'),
(0, '2009-08-09 08:34:15', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-09 0:0:0'', '''', ''O\\''HURLEY'', ''TEST'', ''6'', ''ST'', ''A'', ''100'', ''2009-08-09 08:34:15'', ''2009-08-09 08:34:15'', ''WSUR'')'),
(0, '2009-08-09 08:34:15', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''A'', ''2009-08-09 08:34:15'', ''WSUR'')'),
(0, '2009-08-09 08:34:15', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(108, '2009-08-09 08:40:34', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-09 0:0:0'', thpnum='''', thlname=''O\\''MALLEY'', thfname=''S\\''UNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''P'', updDate=''2009-08-09 08:40:34'', updUser=''WSUR'' WHERE thid=''108'''),
(108, '2009-08-09 08:40:34', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''108'''),
(108, '2009-08-09 08:40:34', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''108'''),
(108, '2009-08-09 08:40:34', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''108'''),
(108, '2009-08-09 08:40:34', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''108'', ''15P'', ''2009-08-09 08:40:34'', ''WSUR'')'),
(108, '2009-08-09 08:40:34', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''108'', ''P'', ''2009-08-09 08:40:34'', ''WSUR'')'),
(108, '2009-08-09 08:40:34', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''108'')'),
(108, '2009-08-09 08:40:34', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''108'', ''P'', ''2009-08-09 08:40:34'', ''WSUR'')'),
(108, '2009-08-09 08:40:50', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-09 0:0:0'', thpnum='''', thlname=''O\\''MALLEY'', thfname=''S\\''UNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''P'', updDate=''2009-08-09 08:40:50'', updUser=''WSUR'' WHERE thid=''108'''),
(108, '2009-08-09 08:40:50', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''108'''),
(108, '2009-08-09 08:40:50', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''108'''),
(108, '2009-08-09 08:40:50', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''108'''),
(108, '2009-08-09 08:40:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''108'', ''15P'', ''2009-08-09 08:40:50'', ''WSUR'')'),
(108, '2009-08-09 08:40:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''108'', ''P'', ''2009-08-09 08:40:50'', ''WSUR'')'),
(108, '2009-08-09 08:40:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''108'')'),
(108, '2009-08-09 08:40:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''108'', ''P'', ''2009-08-09 08:40:50'', ''WSUR'')'),
(108, '2009-08-09 08:41:04', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-09 0:0:0'', thpnum='''', thlname=''O\\''MALLEY'', thfname=''S\\''UNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''P'', updDate=''2009-08-09 08:41:04'', updUser=''WSUR'' WHERE thid=''108'''),
(108, '2009-08-09 08:41:04', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''108'''),
(108, '2009-08-09 08:41:04', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''108'''),
(108, '2009-08-09 08:41:04', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''108'''),
(108, '2009-08-09 08:41:04', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''108'', ''15P'', ''2009-08-09 08:41:04'', ''WSUR'')'),
(108, '2009-08-09 08:41:04', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''108'', ''P'', ''2009-08-09 08:41:04'', ''WSUR'')'),
(108, '2009-08-09 08:41:04', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''108'')'),
(109, '2009-08-09 08:41:16', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-09 0:0:0'', thpnum='''', thlname=''O\\''HURLEY'', thfname=''TEST'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', updDate=''2009-08-09 08:41:16'', updUser=''WSUR'' WHERE thid=''109'''),
(109, '2009-08-09 08:41:16', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''109'''),
(109, '2009-08-09 08:41:16', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''109'''),
(109, '2009-08-09 08:41:16', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''109'''),
(109, '2009-08-09 08:41:16', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''109'', ''A'', ''2009-08-09 08:41:16'', ''WSUR'')'),
(109, '2009-08-09 08:41:16', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''109'')'),
(109, '2009-08-09 08:41:16', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''109'', ''A'', ''2009-08-09 08:41:16'', ''WSUR'')'),
(109, '2009-08-09 08:41:21', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-09 0:0:0'', thpnum='''', thlname=''O\\''HURLEY'', thfname=''TEST'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', updDate=''2009-08-09 08:41:21'', updUser=''WSUR'' WHERE thid=''109'''),
(109, '2009-08-09 08:41:21', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''109'''),
(109, '2009-08-09 08:41:21', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''109'''),
(109, '2009-08-09 08:41:21', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''109'''),
(109, '2009-08-09 08:41:21', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''109'', ''A'', ''2009-08-09 08:41:21'', ''WSUR'')'),
(109, '2009-08-09 08:41:21', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''109'')'),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-09 0:0:0'', thpnum='''', thlname=''O\\''HURLEY'', thfname=''TEST'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', updDate=''2009-08-09 08:41:27'', updUser=''WSUR'' WHERE thid=''109'''),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''109'''),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''109'''),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''109'''),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''109'', ''IRA'', ''2009-08-09 08:41:27'', ''WSUR'')'),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''109'', ''15A'', ''2009-08-09 08:41:27'', ''WSUR'')'),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''109'', ''MRS'', ''2009-08-09 08:41:27'', ''WSUR'')'),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''109'', ''EA'', ''2009-08-09 08:41:27'', ''WSUR'')'),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''109'', ''A'', ''2009-08-09 08:41:27'', ''WSUR'')'),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''109'')'),
(109, '2009-08-09 08:41:27', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''109'', ''A'', ''2009-08-09 08:41:27'', ''WSUR'')'),
(0, '2009-08-09 08:42:14', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-09 0:0:0'', '''', ''TEST'', ''TEST'', ''6'', ''NPE'', ''PT'', ''100'', ''2009-08-09 08:42:14'', ''2009-08-09 08:42:14'', ''WSUR'')'),
(0, '2009-08-09 08:42:14', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIM'', ''2009-08-09 08:42:14'', ''WSUR'')'),
(0, '2009-08-09 08:42:14', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-09 08:42:43', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-09 0:0:0'', '''', ''TEST'', ''TEST'', ''6'', ''REE'', ''PT'', ''100'', ''2009-08-09 08:42:43'', ''2009-08-09 08:42:43'', ''WSUR'')'),
(0, '2009-08-09 08:42:43', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIO'', ''2009-08-09 08:42:43'', ''WSUR'')'),
(0, '2009-08-09 08:42:57', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-09 0:0:0'', '''', ''TEST'', ''TEST'', ''6'', ''RE'', ''PT'', ''100'', ''2009-08-09 08:42:57'', ''2009-08-09 08:42:57'', ''WSUR'')'),
(0, '2009-08-09 08:42:57', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''ISO'', ''2009-08-09 08:42:57'', ''WSUR'')'),
(0, '2009-08-09 08:43:11', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-09 0:0:0'', '''', ''TEST'', ''TEST'', ''6'', ''REW'', ''PT'', ''100'', ''2009-08-09 08:43:11'', ''2009-08-09 08:43:11'', ''WSUR'')'),
(0, '2009-08-09 08:43:11', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''MYO'', ''2009-08-09 08:43:11'', ''WSUR'')'),
(0, '2009-08-09 08:43:30', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-09 0:0:0'', '''', ''TEST'', ''TEST'', ''6'', ''DC'', ''PT'', ''100'', ''2009-08-09 08:43:30'', ''2009-08-09 08:43:30'', ''WSUR'')'),
(0, '2009-08-09 08:43:30', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TE'', ''2009-08-09 08:43:30'', ''WSUR'')'),
(0, '2009-08-09 08:43:44', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-09 0:0:0'', '''', ''TEST'', ''TEST'', ''6'', ''DCW'', ''PT'', ''100'', ''2009-08-09 08:43:44'', ''2009-08-09 08:43:44'', ''WSUR'')'),
(0, '2009-08-09 08:43:44', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TE'', ''2009-08-09 08:43:44'', ''WSUR'')'),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-09 0:0:0'', thpnum='''', thlname=''O\\''MALLEY'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', updDate=''2009-08-09 09:04:29'', updUser=''WSUR'' WHERE thid=''109'''),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''109'''),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''109'''),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''109'''),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''109'', ''IRA'', ''2009-08-09 09:04:29'', ''WSUR'')'),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''109'', ''15A'', ''2009-08-09 09:04:29'', ''WSUR'')'),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''109'', ''MRS'', ''2009-08-09 09:04:29'', ''WSUR'')'),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''109'', ''EA'', ''2009-08-09 09:04:29'', ''WSUR'')'),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''109'', ''A'', ''2009-08-09 09:04:29'', ''WSUR'')'),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''109'')'),
(109, '2009-08-09 09:04:29', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''109'', ''A'', ''2009-08-09 09:04:29'', ''WSUR'')'),
(108, '2009-08-09 09:04:50', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-09 0:0:0'', thpnum='''', thlname=''O\\''MALLEY'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''P'', updDate=''2009-08-09 09:04:50'', updUser=''WSUR'' WHERE thid=''108'''),
(108, '2009-08-09 09:04:50', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''108'''),
(108, '2009-08-09 09:04:50', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''108''');
INSERT INTO `treatment_header_history` (`thhid`, `thhdate`, `thhuser`, `thhhide`, `thhapplication`, `thhmsg`, `thhtext`, `thhquery`) VALUES
(108, '2009-08-09 09:04:50', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''108'''),
(108, '2009-08-09 09:04:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''108'', ''15P'', ''2009-08-09 09:04:50'', ''WSUR'')'),
(108, '2009-08-09 09:04:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''108'', ''P'', ''2009-08-09 09:04:50'', ''WSUR'')'),
(108, '2009-08-09 09:04:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''108'')'),
(108, '2009-08-09 09:04:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''108'', ''P'', ''2009-08-09 09:04:50'', ''WSUR'')'),
(108, '2009-08-09 09:08:21', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''108'''),
(97, '2009-08-09 09:08:26', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''97'''),
(110, '2009-08-09 09:08:29', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''110'''),
(110, '2009-08-09 09:08:30', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''110'''),
(110, '2009-08-09 09:08:31', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''110'''),
(111, '2009-08-09 09:08:33', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''111'''),
(111, '2009-08-09 09:08:33', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''111'''),
(111, '2009-08-09 09:08:34', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''111'''),
(91, '2009-08-09 09:08:35', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''91'''),
(110, '2009-08-09 09:08:37', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''110'''),
(111, '2009-08-09 09:08:38', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''111'''),
(63, '2009-08-09 09:08:39', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''63'''),
(57, '2009-08-09 09:08:41', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''57'''),
(115, '2009-08-09 09:08:47', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''115'''),
(113, '2009-08-09 09:08:48', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''113'''),
(112, '2009-08-09 09:08:49', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''112'''),
(110, '2009-08-09 09:14:22', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''110'''),
(111, '2009-08-09 09:14:24', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''111'''),
(112, '2009-08-09 09:14:25', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''112'''),
(114, '2009-08-09 09:14:26', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''114'''),
(115, '2009-08-09 09:14:27', 'WSUR', 0, 'duplicatedashboa', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''115'''),
(113, '2009-08-09 09:23:05', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-09 0:0:0'', thpnum='''', thlname=''TEST'', thfname=''TEST'', thctmcode=''6'', thvtmcode=''REW'', thttmcode=''PT'', updDate=''2009-08-09 09:23:05'', updUser=''WSUR'' WHERE thid=''113'''),
(113, '2009-08-09 09:23:05', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''113'''),
(113, '2009-08-09 09:23:05', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''113'''),
(113, '2009-08-09 09:23:05', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''113'''),
(113, '2009-08-09 09:23:05', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''113'', ''BIO'', ''2009-08-09 09:23:05'', ''WSUR'')'),
(113, '2009-08-09 09:23:05', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''113'', ''ISO'', ''2009-08-09 09:23:05'', ''WSUR'')'),
(113, '2009-08-09 09:23:05', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''113'', ''MYO'', ''2009-08-09 09:23:05'', ''WSUR'')'),
(107, '2009-08-09 09:57:27', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''107'''),
(109, '2009-08-09 09:57:35', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [300]', 'UPDATE treatment_header SET thsbmStatus=''300'' WHERE thid=''109'''),
(0, '2009-08-10 08:59:07', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-10 0:0:0'', '''', ''TEST'', ''TEST'', ''6'', ''ST'', ''PT'', ''0'', NULL, ''2009-08-10 08:59:07'', ''jbadam'')'),
(0, '2009-08-10 08:59:07', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BMT'', ''2009-08-10 08:59:07'', ''jbadam'')'),
(0, '2009-08-10 08:59:07', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-10 08:59:07', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''WP'', ''2009-08-10 08:59:07'', ''jbadam'')'),
(0, '2009-08-10 08:59:07', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''IC'', ''2009-08-10 08:59:07'', ''jbadam'')'),
(14, '2009-08-11 14:09:33', 'Administrator', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''14'''),
(14, '2009-08-11 14:09:36', 'Administrator', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''14'''),
(14, '2009-08-11 14:09:38', 'Administrator', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''14'''),
(116, '2009-08-12 15:50:08', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''116'''),
(116, '2009-08-12 15:50:08', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''116'''),
(116, '2009-08-12 15:50:08', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''116'''),
(116, '2009-08-12 15:50:08', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Header', 'DELETE FROM treatment_header WHERE thid=''116'''),
(0, '2009-08-12 15:51:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-12 0:0:0'', '''', ''MEDEIROS'', ''CONNIE'', ''6'', ''ST'', ''A'', ''0'', NULL, ''2009-08-12 15:51:40'', ''ClinicUser'')'),
(0, '2009-08-12 15:51:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''A'', ''2009-08-12 15:51:40'', ''ClinicUser'')'),
(0, '2009-08-12 15:51:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-12 15:51:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''15A'', ''2009-08-12 15:51:40'', ''ClinicUser'')'),
(0, '2009-08-12 15:51:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''MRS'', ''2009-08-12 15:51:40'', ''ClinicUser'')'),
(0, '2009-08-12 15:52:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-12 0:0:0'', '''', ''ADAM'', ''JB'', ''6'', ''ST'', ''PT'', ''0'', NULL, ''2009-08-12 15:52:40'', ''ClinicUser'')'),
(0, '2009-08-12 15:52:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''IM'', ''2009-08-12 15:52:40'', ''ClinicUser'')'),
(0, '2009-08-12 15:52:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-12 15:52:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''ELS'', ''2009-08-12 15:52:40'', ''ClinicUser'')'),
(0, '2009-08-12 15:52:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''PB'', ''2009-08-12 15:52:40'', ''ClinicUser'')'),
(0, '2009-08-12 15:52:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''WP'', ''2009-08-12 15:52:40'', ''ClinicUser'')'),
(0, '2009-08-12 15:52:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''IC'', ''2009-08-12 15:52:40'', ''ClinicUser'')'),
(117, '2009-08-12 15:54:49', 'ClinicUser', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-12 15:54:49'', thsbmUser=''ClinicUser'' WHERE thid = ''117'''),
(118, '2009-08-12 15:54:49', 'ClinicUser', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-12 15:54:49'', thsbmUser=''ClinicUser'' WHERE thid = ''118'''),
(117, '2009-08-12 15:56:42', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''117'''),
(117, '2009-08-12 15:56:48', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''117'''),
(117, '2009-08-12 15:58:11', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''117'''),
(118, '2009-08-12 15:58:15', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''118'''),
(0, '2009-08-14 09:17:53', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-14 0:0:0'', '''', ''TEST'', ''TEST'', ''6'', ''ST'', ''P'', ''0'', NULL, ''2009-08-14 09:17:53'', ''ClinicUser'')'),
(0, '2009-08-14 09:17:53', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''P'', ''2009-08-14 09:17:53'', ''ClinicUser'')'),
(0, '2009-08-14 09:17:53', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-14 09:17:53', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''15P'', ''2009-08-14 09:17:53'', ''ClinicUser'')'),
(119, '2009-08-14 09:48:22', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''119'''),
(119, '2009-08-14 09:48:22', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''119'''),
(119, '2009-08-14 09:48:22', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''119'''),
(119, '2009-08-14 09:48:22', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Header', 'DELETE FROM treatment_header WHERE thid=''119'''),
(0, '2009-08-14 09:48:37', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-14 0:0:0'', '''', ''ADAM'', ''JB'', ''6'', ''ST'', ''P'', ''0'', NULL, ''2009-08-14 09:48:37'', ''ClinicUser'')'),
(0, '2009-08-14 09:48:37', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''P'', ''2009-08-14 09:48:37'', ''ClinicUser'')'),
(0, '2009-08-14 09:48:37', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-14 09:48:37', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''15P'', ''2009-08-14 09:48:37'', ''ClinicUser'')'),
(0, '2009-08-14 09:48:56', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-14 0:0:0'', '''', ''SANCHEZ'', ''GLADYS'', ''6'', ''ST'', ''PT'', ''0'', NULL, ''2009-08-14 09:48:56'', ''ClinicUser'')'),
(0, '2009-08-14 09:48:56', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIM'', ''2009-08-14 09:48:56'', ''ClinicUser'')'),
(0, '2009-08-14 09:48:56', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-14 09:48:56', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''ELS'', ''2009-08-14 09:48:56'', ''ClinicUser'')'),
(0, '2009-08-14 09:48:56', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TEN'', ''2009-08-14 09:48:56'', ''ClinicUser'')'),
(0, '2009-08-14 09:48:56', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''PB'', ''2009-08-14 09:48:56'', ''ClinicUser'')'),
(120, '2009-08-14 09:49:18', 'ClinicUser', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-14 09:49:18'', thsbmUser=''ClinicUser'' WHERE thid = ''120'''),
(121, '2009-08-14 09:49:18', 'ClinicUser', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-14 09:49:18'', thsbmUser=''ClinicUser'' WHERE thid = ''121'''),
(121, '2009-08-14 09:51:02', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [300]', 'UPDATE treatment_header SET thsbmStatus=''300'' WHERE thid=''121'''),
(121, '2009-08-14 09:51:26', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [300]', 'UPDATE treatment_header SET thsbmStatus=''300'' WHERE thid=''121'''),
(121, '2009-08-14 09:52:53', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [150]', 'UPDATE treatment_header SET thsbmStatus=''150'' WHERE thid=''121'''),
(109, '2009-08-14 09:52:55', 'WSPatient', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''109'''),
(0, '2009-08-14 10:33:48', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES('''', ''2009-08-14 0:0:0'', '''', ''SPOON'', ''SUNNI'', ''6'', ''ST'', ''PT'', ''100'', ''2009-08-14 10:33:48'', ''2009-08-14 10:33:48'', ''WSUR'')'),
(0, '2009-08-14 10:33:48', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TRA'', ''2009-08-14 10:33:48'', ''WSUR'')'),
(0, '2009-08-14 10:33:48', 'WSUR', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIO'', ''2009-08-14 10:33:48'', ''WSUR'')'),
(0, '2009-08-14 11:18:45', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-14 0:0:0'', '''', ''TEST'', ''TEST'', ''6'', ''ST'', ''P'', ''0'', NULL, ''2009-08-14 11:18:45'', ''jbadam'')'),
(0, '2009-08-14 11:18:45', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''P'', ''2009-08-14 11:18:45'', ''jbadam'')'),
(0, '2009-08-14 11:18:45', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-14 11:18:45', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''15P'', ''2009-08-14 11:18:45'', ''jbadam'')'),
(0, '2009-08-14 13:39:26', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-14 0:0:0'', '''', ''TEST2'', ''TEST2'', ''6'', ''ST'', ''PT'', ''0'', NULL, ''2009-08-14 13:39:26'', ''jbadam'')'),
(0, '2009-08-14 13:39:26', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BMT'', ''2009-08-14 13:39:26'', ''jbadam'')'),
(0, '2009-08-14 13:39:26', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-14 13:39:26', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''PB'', ''2009-08-14 13:39:26'', ''jbadam'')'),
(0, '2009-08-14 13:39:26', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''WP'', ''2009-08-14 13:39:26'', ''jbadam'')'),
(0, '2009-08-14 13:39:37', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-14 0:0:0'', '''', ''TEST3'', ''TEST3'', ''6'', ''ST'', ''P'', ''0'', NULL, ''2009-08-14 13:39:37'', ''jbadam'')'),
(0, '2009-08-14 13:39:37', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''P'', ''2009-08-14 13:39:37'', ''jbadam'')'),
(0, '2009-08-14 13:39:37', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-14 13:39:37', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''15P'', ''2009-08-14 13:39:37'', ''jbadam'')'),
(0, '2009-08-14 13:39:49', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-14 0:0:0'', '''', ''TEST4'', ''TEST4'', ''6'', ''ST'', ''A'', ''0'', NULL, ''2009-08-14 13:39:49'', ''jbadam'')'),
(0, '2009-08-14 13:39:49', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''A'', ''2009-08-14 13:39:49'', ''jbadam'')'),
(0, '2009-08-14 13:39:49', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-14 13:39:49', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''IRA'', ''2009-08-14 13:39:49'', ''jbadam'')'),
(0, '2009-08-14 13:39:49', 'jbadam', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''15A'', ''2009-08-14 13:39:49'', ''jbadam'')'),
(123, '2009-08-18 14:25:39', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''123'''),
(123, '2009-08-18 14:25:39', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''123'''),
(123, '2009-08-18 14:25:39', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''123'''),
(123, '2009-08-18 14:25:39', 'ClinicUser', 0, 'treatment', 'DELETE', 'Delete Treatment Header', 'DELETE FROM treatment_header WHERE thid=''123'''),
(117, '2009-08-18 16:50:36', 'WSBilling', 0, 'billingdashboard', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''117'''),
(117, '2009-08-18 16:50:41', 'WSBilling', 0, 'billingdashboard', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''117'''),
(117, '2009-08-18 16:50:42', 'WSBilling', 0, 'billingdashboard', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''117'''),
(117, '2009-08-18 16:50:45', 'WSBilling', 0, 'billingdashboard', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''117'''),
(117, '2009-08-18 16:50:46', 'WSBilling', 0, 'billingdashboard', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''117'''),
(107, '2009-08-18 16:51:33', 'WSBilling', 0, 'billingdashboard', 'UPDATE', 'Updated Treatment Status [700]', 'UPDATE treatment_header SET thsbmStatus=''700'' WHERE thid=''107'''),
(124, '2009-08-19 08:25:20', 'jbadam', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-19 08:25:20'', thsbmUser=''jbadam'' WHERE thid = ''124'''),
(125, '2009-08-19 08:25:20', 'jbadam', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-19 08:25:20'', thsbmUser=''jbadam'' WHERE thid = ''125'''),
(126, '2009-08-19 08:25:20', 'jbadam', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-19 08:25:20'', thsbmUser=''jbadam'' WHERE thid = ''126'''),
(124, '2009-08-19 08:26:14', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''124'''),
(124, '2009-08-19 08:26:18', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''124'''),
(124, '2009-08-19 08:27:21', 'WSBilling', 0, 'billingdashboard', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''124'''),
(124, '2009-08-19 08:27:45', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''124'''),
(124, '2009-08-19 08:27:49', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''124'''),
(124, '2009-08-19 08:28:36', 'WSBilling', 0, 'billingdashboard', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''124'''),
(0, '2009-08-19 08:35:31', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-19 0:0:0'', '''', ''MORA'', ''CYNTHIA'', ''5'', ''NPE'', ''PT'', ''0'', NULL, ''2009-08-19 08:35:31'', ''ClinicUser'')'),
(0, '2009-08-19 08:35:31', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIT'', ''2009-08-19 08:35:31'', ''ClinicUser'')'),
(0, '2009-08-19 08:35:31', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-19 08:35:31', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TEN'', ''2009-08-19 08:35:31'', ''ClinicUser'')'),
(0, '2009-08-19 08:35:31', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''PB'', ''2009-08-19 08:35:31'', ''ClinicUser'')'),
(0, '2009-08-19 08:35:31', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''WP'', ''2009-08-19 08:35:31'', ''ClinicUser'')'),
(127, '2009-08-19 08:37:40', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-19 0:0:0'', thpnum='''', thlname=''MORA'', thfname=''CYNTHIA'', thctmcode=''5'', thvtmcode=''NPE'', thttmcode=''PT'', updDate=''2009-08-19 08:37:40'', updUser=''ClinicUser'' WHERE thid=''127'''),
(127, '2009-08-19 08:37:40', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''127'''),
(127, '2009-08-19 08:37:40', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''127'''),
(127, '2009-08-19 08:37:40', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''127'''),
(127, '2009-08-19 08:37:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''127'', ''TEN'', ''2009-08-19 08:37:40'', ''ClinicUser'')'),
(127, '2009-08-19 08:37:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''127'', ''PB'', ''2009-08-19 08:37:40'', ''ClinicUser'')'),
(127, '2009-08-19 08:37:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''127'', ''BMT'', ''2009-08-19 08:37:40'', ''ClinicUser'')'),
(127, '2009-08-19 08:37:40', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''127'')'),
(124, '2009-08-19 08:40:50', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-14 0:0:0'', thpnum='''', thlname=''TEST2'', thfname=''TEST2'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''PT'', updDate=''2009-08-19 08:40:50'', updUser=''WSUR'' WHERE thid=''124'''),
(124, '2009-08-19 08:40:50', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''124'''),
(124, '2009-08-19 08:40:50', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''124'''),
(124, '2009-08-19 08:40:50', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''124'''),
(124, '2009-08-19 08:40:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''124'', ''PB'', ''2009-08-19 08:40:50'', ''WSUR'')'),
(124, '2009-08-19 08:40:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''124'', ''WP'', ''2009-08-19 08:40:50'', ''WSUR'')'),
(124, '2009-08-19 08:40:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''124'', ''BIO'', ''2009-08-19 08:40:50'', ''WSUR'')'),
(124, '2009-08-19 08:40:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''124'', ''ISO'', ''2009-08-19 08:40:50'', ''WSUR'')'),
(124, '2009-08-19 08:40:50', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''124'', ''TE'', ''2009-08-19 08:40:50'', ''WSUR'')'),
(124, '2009-08-19 08:41:07', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''124'''),
(124, '2009-08-19 08:42:20', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''124'''),
(120, '2009-08-19 08:43:27', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [300]', 'UPDATE treatment_header SET thsbmStatus=''300'' WHERE thid=''120'''),
(109, '2009-08-19 08:44:04', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''109'''),
(109, '2009-08-19 08:44:31', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''109'''),
(0, '2009-08-19 15:34:33', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-19 0:0:0'', '''', ''MCDANIELS'', ''MIKE'', ''6'', ''NPE'', ''PT'', ''0'', NULL, ''2009-08-19 15:34:33'', ''ClinicUser'')'),
(0, '2009-08-19 15:34:33', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIM'', ''2009-08-19 15:34:33'', ''ClinicUser'')'),
(0, '2009-08-19 15:34:33', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-19 15:34:33', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TEN'', ''2009-08-19 15:34:33'', ''ClinicUser'')'),
(0, '2009-08-19 15:34:33', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''PB'', ''2009-08-19 15:34:33'', ''ClinicUser'')'),
(128, '2009-08-19 15:34:58', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-19 0:0:0'', thpnum='''', thlname=''MCDANIELS'', thfname=''MIKE'', thctmcode=''6'', thvtmcode=''NPE'', thttmcode=''PT'', updDate=''2009-08-19 15:34:58'', updUser=''ClinicUser'' WHERE thid=''128'''),
(128, '2009-08-19 15:34:58', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''128'''),
(128, '2009-08-19 15:34:58', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''128'''),
(128, '2009-08-19 15:34:58', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''128'''),
(128, '2009-08-19 15:34:58', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''128'', ''TEN'', ''2009-08-19 15:34:58'', ''ClinicUser'')'),
(128, '2009-08-19 15:34:58', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''128'', ''PB'', ''2009-08-19 15:34:58'', ''ClinicUser'')'),
(128, '2009-08-19 15:34:58', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''128'', ''WP'', ''2009-08-19 15:34:58'', ''ClinicUser'')'),
(128, '2009-08-19 15:34:58', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''128'', ''BIM'', ''2009-08-19 15:34:58'', ''ClinicUser'')'),
(128, '2009-08-19 15:34:58', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''128'')'),
(128, '2009-08-19 15:35:13', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-19 0:0:0'', thpnum='''', thlname=''MCDANIELS'', thfname=''MIKE'', thctmcode=''6'', thvtmcode=''NPE'', thttmcode=''PT'', updDate=''2009-08-19 15:35:13'', updUser=''ClinicUser'' WHERE thid=''128'''),
(128, '2009-08-19 15:35:13', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''128'''),
(128, '2009-08-19 15:35:13', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''128'''),
(128, '2009-08-19 15:35:13', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''128'''),
(128, '2009-08-19 15:35:13', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''128'', ''TEN'', ''2009-08-19 15:35:13'', ''ClinicUser'')'),
(128, '2009-08-19 15:35:13', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''128'', ''PB'', ''2009-08-19 15:35:13'', ''ClinicUser'')'),
(128, '2009-08-19 15:35:13', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''128'', ''IC'', ''2009-08-19 15:35:13'', ''ClinicUser'')'),
(128, '2009-08-19 15:35:13', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''128'', ''BIM'', ''2009-08-19 15:35:13'', ''ClinicUser'')'),
(128, '2009-08-19 15:35:13', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''128'')'),
(124, '2009-08-19 15:48:21', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''AL'', thdate=''2009-08-14 0:0:0'', thpnum='''', thlname=''TEST2'', thfname=''TEST2'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''PT'', updDate=''2009-08-19 15:48:21'', updUser=''WSUR'' WHERE thid=''124'''),
(124, '2009-08-19 15:48:21', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''124'''),
(124, '2009-08-19 15:48:21', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''124'''),
(124, '2009-08-19 15:48:21', 'WSUR', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''124'''),
(124, '2009-08-19 15:48:21', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''124'', ''PB'', ''2009-08-19 15:48:21'', ''WSUR'')'),
(124, '2009-08-19 15:48:21', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''124'', ''WP'', ''2009-08-19 15:48:21'', ''WSUR'')'),
(124, '2009-08-19 15:48:21', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''124'', ''BIO'', ''2009-08-19 15:48:21'', ''WSUR'')'),
(124, '2009-08-19 15:48:21', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''124'', ''ISO'', ''2009-08-19 15:48:21'', ''WSUR'')'),
(124, '2009-08-19 15:48:21', 'WSUR', 0, 'treatment', 'INSERT', 'Added New Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, updDate, updUser) VALUES(''124'', ''TE'', ''2009-08-19 15:48:21'', ''WSUR'')'),
(124, '2009-08-19 15:49:01', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''124'''),
(124, '2009-08-19 15:51:26', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''124'''),
(125, '2009-08-19 15:51:51', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''125'''),
(125, '2009-08-19 15:52:34', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''125'''),
(0, '2009-08-19 16:15:22', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, updDate, updUser) VALUES(''AL'', ''2009-08-19 0:0:0'', '''', ''M'', ''CONNIE'', ''6'', ''ST'', ''PT'', ''0'', NULL, ''2009-08-19 16:15:22'', ''ClinicUser'')'),
(0, '2009-08-19 16:15:22', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''BIM'', ''2009-08-19 16:15:22'', ''ClinicUser'')'),
(0, '2009-08-19 16:15:22', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-19 16:15:22', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''ELS'', ''2009-08-19 16:15:22'', ''ClinicUser'')'),
(0, '2009-08-19 16:15:22', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''TEN'', ''2009-08-19 16:15:22'', ''ClinicUser'')'),
(127, '2009-08-20 11:47:41', 'ClinicUser', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-20 11:47:41'', thsbmUser=''ClinicUser'' WHERE thid = ''127'''),
(128, '2009-08-20 11:47:41', 'ClinicUser', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-20 11:47:41'', thsbmUser=''ClinicUser'' WHERE thid = ''128'''),
(129, '2009-08-20 11:47:41', 'ClinicUser', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-20 11:47:41'', thsbmUser=''ClinicUser'' WHERE thid = ''129'''),
(127, '2009-08-20 12:02:15', 'ClinicUser', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-20 12:02:15'', thsbmUser=''ClinicUser'' WHERE thid = ''127'''),
(128, '2009-08-20 12:02:15', 'ClinicUser', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-20 12:02:15'', thsbmUser=''ClinicUser'' WHERE thid = ''128'''),
(129, '2009-08-20 12:02:15', 'ClinicUser', 0, 'treatment', 'SUBMITTED', 'Treatment Submitted to Weststar.', 'UPDATE treatment_header set thsbmStatus=''100'', thsbmDate=''2009-08-20 12:02:15'', thsbmUser=''ClinicUser'' WHERE thid = ''129'''),
(127, '2009-08-20 16:13:48', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''127'''),
(128, '2009-08-20 16:13:50', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [300]', 'UPDATE treatment_header SET thsbmStatus=''300'' WHERE thid=''128'''),
(129, '2009-08-20 16:13:52', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''129'''),
(129, '2009-08-20 16:13:54', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [500]', 'UPDATE treatment_header SET thsbmStatus=''500'' WHERE thid=''129'''),
(127, '2009-08-20 16:20:09', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''127'''),
(120, '2009-08-20 16:45:12', 'WSPatient', 0, 'patientdashboard', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''120'''),
(128, '2009-08-20 16:45:13', 'WSPatient', 0, 'patientdashboard', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''128'''),
(128, '2009-08-20 16:46:03', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [300]', 'UPDATE treatment_header SET thsbmStatus=''300'' WHERE thid=''128'''),
(127, '2009-08-20 16:46:06', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [300]', 'UPDATE treatment_header SET thsbmStatus=''300'' WHERE thid=''127'''),
(127, '2009-08-20 16:46:10', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [300]', 'UPDATE treatment_header SET thsbmStatus=''300'' WHERE thid=''127'''),
(0, '2009-08-20 17:17:11', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, crtdate, crtuser, crtprog) VALUES(''C1'', ''2009-08-20 0:0:0'', '''', ''SPOON'', ''SUNNI'', ''6'', ''ST'', ''A'', ''0'', NULL, ''2009-08-20 17:17:11'', ''ClinicUser'', ''/index.php'' )'),
(0, '2009-08-20 17:17:11', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''A'', ''2009-08-20 17:17:11'', ''ClinicUser'')'),
(0, '2009-08-20 17:17:11', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=LAST_INSERT_ID())'),
(0, '2009-08-20 17:17:11', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''IRA'', ''2009-08-20 17:17:11'', ''ClinicUser'')'),
(0, '2009-08-20 17:17:11', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''15A'', ''2009-08-20 17:17:11'', ''ClinicUser'')'),
(0, '2009-08-20 17:17:11', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''MRS'', ''2009-08-20 17:17:11'', ''ClinicUser'')');
INSERT INTO `treatment_header_history` (`thhid`, `thhdate`, `thhuser`, `thhhide`, `thhapplication`, `thhmsg`, `thhtext`, `thhquery`) VALUES
(0, '2009-08-20 17:17:11', 'ClinicUser', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(LAST_INSERT_ID(), ''EA'', ''2009-08-20 17:17:11'', ''ClinicUser'')'),
(130, '2009-08-20 17:18:30', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', updDate=''2009-08-20 17:18:30'', updUser=''ClinicUser'' WHERE thid=''130'''),
(130, '2009-08-20 17:18:30', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:18:30', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:18:30', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:18:30', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''IRA'', ''2009-08-20 17:18:30'', ''ClinicUser'')'),
(130, '2009-08-20 17:18:30', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''MRS'', ''2009-08-20 17:18:30'', ''ClinicUser'')'),
(130, '2009-08-20 17:18:30', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''EA'', ''2009-08-20 17:18:30'', ''ClinicUser'')'),
(130, '2009-08-20 17:18:30', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:18:30'', ''ClinicUser'')'),
(130, '2009-08-20 17:18:30', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:28:27', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:28:27'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:28:27', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:28:27', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:28:27', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:28:27', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''IRA'', ''2009-08-20 17:28:27'', ''ClinicUser'')'),
(130, '2009-08-20 17:28:27', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''15A'', ''2009-08-20 17:28:27'', ''ClinicUser'')'),
(130, '2009-08-20 17:28:27', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''MRS'', ''2009-08-20 17:28:27'', ''ClinicUser'')'),
(130, '2009-08-20 17:28:27', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''EA'', ''2009-08-20 17:28:27'', ''ClinicUser'')'),
(130, '2009-08-20 17:28:27', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:28:27'', ''ClinicUser'')'),
(130, '2009-08-20 17:28:27', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:29:06', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:29:06'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:29:06', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:29:06', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:29:06', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:29:06', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''IRA'', ''2009-08-20 17:29:06'', ''ClinicUser'')'),
(130, '2009-08-20 17:29:06', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''MRS'', ''2009-08-20 17:29:06'', ''ClinicUser'')'),
(130, '2009-08-20 17:29:06', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''EA'', ''2009-08-20 17:29:06'', ''ClinicUser'')'),
(130, '2009-08-20 17:29:06', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:29:06'', ''ClinicUser'')'),
(130, '2009-08-20 17:29:06', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:29:18', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:29:18'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:29:18', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:29:18', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:29:18', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:29:18', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''15A'', ''2009-08-20 17:29:18'', ''ClinicUser'')'),
(130, '2009-08-20 17:29:18', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:29:18'', ''ClinicUser'')'),
(130, '2009-08-20 17:29:18', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:52:31', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:52:31'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:52:31', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:52:31', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:52:31', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:52:31', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''IRA'', ''2009-08-20 17:52:31'', ''ClinicUser'')'),
(130, '2009-08-20 17:52:31', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''15A'', ''2009-08-20 17:52:31'', ''ClinicUser'')'),
(130, '2009-08-20 17:52:31', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:52:31'', ''ClinicUser'')'),
(130, '2009-08-20 17:52:31', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:53:20', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:53:20'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:53:20', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:53:20', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:53:20', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:53:20', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''IRA'', ''2009-08-20 17:53:20'', ''ClinicUser'')'),
(130, '2009-08-20 17:53:20', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''15A'', ''2009-08-20 17:53:20'', ''ClinicUser'')'),
(130, '2009-08-20 17:53:20', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:53:20'', ''ClinicUser'')'),
(130, '2009-08-20 17:53:20', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:54:07', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:54:07'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:54:07', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:54:07', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:54:07', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:54:07', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''IRA'', ''2009-08-20 17:54:07'', ''ClinicUser'')'),
(130, '2009-08-20 17:54:07', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''15A'', ''2009-08-20 17:54:07'', ''ClinicUser'')'),
(130, '2009-08-20 17:54:07', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''MRS'', ''2009-08-20 17:54:07'', ''ClinicUser'')'),
(130, '2009-08-20 17:54:07', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:54:07'', ''ClinicUser'')'),
(130, '2009-08-20 17:54:07', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (SELECT tpg.thid, pg.pmcode, tpg.updDate, tpg.updUser FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:57:49', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:57:49'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:57:49', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:57:49', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:57:49', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:57:49', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''IRA'', ''2009-08-20 17:57:49'', ''ClinicUser'')'),
(130, '2009-08-20 17:57:49', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''15A'', ''2009-08-20 17:57:49'', ''ClinicUser'')'),
(130, '2009-08-20 17:57:49', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:57:49'', ''ClinicUser'')'),
(130, '2009-08-20 17:57:49', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, crtdate, crtuser, crtprog)(SELECT tpg.thid, pg.pmcode, tpg.crtdate, tpg.crtuser, tpg.crtprog FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:57:55', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:57:55'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:57:55', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:57:55', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:57:55', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:57:55', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''IRA'', ''2009-08-20 17:57:55'', ''ClinicUser'')'),
(130, '2009-08-20 17:57:55', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:57:55'', ''ClinicUser'')'),
(130, '2009-08-20 17:57:55', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, crtdate, crtuser, crtprog)(SELECT tpg.thid, pg.pmcode, tpg.crtdate, tpg.crtuser, tpg.crtprog FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:58:01', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:58:01'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:58:01', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:58:01', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:58:01', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:58:01', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''15A'', ''2009-08-20 17:58:01'', ''ClinicUser'')'),
(130, '2009-08-20 17:58:01', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''MRS'', ''2009-08-20 17:58:01'', ''ClinicUser'')'),
(130, '2009-08-20 17:58:01', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''EA'', ''2009-08-20 17:58:01'', ''ClinicUser'')'),
(130, '2009-08-20 17:58:01', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:58:01'', ''ClinicUser'')'),
(130, '2009-08-20 17:58:01', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, crtdate, crtuser, crtprog)(SELECT tpg.thid, pg.pmcode, tpg.crtdate, tpg.crtuser, tpg.crtprog FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:58:08', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:58:08'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:58:08', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:58:08', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:58:08', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:58:08', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''IRA'', ''2009-08-20 17:58:08'', ''ClinicUser'')'),
(130, '2009-08-20 17:58:08', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''15A'', ''2009-08-20 17:58:08'', ''ClinicUser'')'),
(130, '2009-08-20 17:58:08', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''MRS'', ''2009-08-20 17:58:08'', ''ClinicUser'')'),
(130, '2009-08-20 17:58:08', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''EA'', ''2009-08-20 17:58:08'', ''ClinicUser'')'),
(130, '2009-08-20 17:58:08', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:58:08'', ''ClinicUser'')'),
(130, '2009-08-20 17:58:08', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, crtdate, crtuser, crtprog)(SELECT tpg.thid, pg.pmcode, tpg.crtdate, tpg.crtuser, tpg.crtprog FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 17:59:26', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 17:59:26'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 17:59:26', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 17:59:26', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 17:59:26', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 17:59:26', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''IRA'', ''2009-08-20 17:59:26'', ''ClinicUser'')'),
(130, '2009-08-20 17:59:26', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, updDate, updUser) VALUES(''130'', ''MRS'', ''2009-08-20 17:59:26'', ''ClinicUser'')'),
(130, '2009-08-20 17:59:26', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, updDate, updUser) VALUES(''130'', ''A'', ''2009-08-20 17:59:26'', ''ClinicUser'')'),
(130, '2009-08-20 17:59:26', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, upddate, upduser, updprog)(SELECT tpg.thid, pg.pmcode, tpg.upddate, tpg.upduser, tpg.updprog FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(130, '2009-08-20 18:03:23', 'ClinicUser', 0, 'treatment', 'UPDATE', 'Updated Treatment', 'UPDATE treatment_header SET thcnum =''C1'', thdate=''2009-08-20 0:0:0'', thpnum='''', thlname=''SPOON'', thfname=''SUNNI'', thctmcode=''6'', thvtmcode=''ST'', thttmcode=''A'', upddate=''2009-08-20 18:03:23'', upduser=''ClinicUser'', updprog=''/index.php'' WHERE thid=''130'''),
(130, '2009-08-20 18:03:23', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Modalities', 'DELETE FROM treatment_modalities WHERE thid=''130'''),
(130, '2009-08-20 18:03:23', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedures', 'DELETE FROM treatment_procedures WHERE thid=''130'''),
(130, '2009-08-20 18:03:23', 'ClinicUser', 0, 'treatment', 'DELETE', 'Removed Previous Treatment Procedure Groups', 'DELETE FROM treatment_procedure_groups WHERE thid=''130'''),
(130, '2009-08-20 18:03:23', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, upddate, upduser, updprog) VALUES(''130'', ''IRA'', ''2009-08-20 18:03:23'', ''ClinicUser'', ''/index.php'')'),
(130, '2009-08-20 18:03:23', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedure Groups', 'INSERT INTO treatment_procedure_groups (thid, gmcode, upddate, upduser, updprog) VALUES(''130'', ''A'', ''2009-08-20 18:03:23'', ''ClinicUser'', ''/index.php'')'),
(130, '2009-08-20 18:03:23', 'ClinicUser', 0, 'treatment', 'INSERT', 'Added New Treatment Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, upddate, upduser, updprog)(SELECT tpg.thid, pg.pmcode, tpg.upddate, tpg.upduser, tpg.updprog FROM `treatment_procedure_groups` tpg JOIN `procedure_groups` pg ON tpg.gmcode=pg.gmcode WHERE tpg.thid=''130'')'),
(0, '2009-08-21 12:30:47', 'Administrator', 0, 'treatment', 'INSERT', 'Insert Treatment Header', 'INSERT INTO treatment_header (thcnum, thdate, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thsbmstatus, thsbmdate, crtdate, crtuser, crtprog) VALUES(''G3'', ''2009-08-21 0:0:0'', '''', ''SPOON'', ''SUNNI'', ''6'', ''ST'', ''PT'', ''100'', ''2009-08-21 12:30:47'', ''2009-08-21 12:30:47'', ''Administrator'', ''/index.php'' )'),
(0, '2009-08-21 12:30:47', 'Administrator', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, upddate, upduser, updprog) VALUES(LAST_INSERT_ID(), ''TRA'', ''2009-08-21 12:30:47'', ''Administrator'', ''/index.php'')'),
(0, '2009-08-21 12:30:47', 'Administrator', 0, 'treatment', 'INSERT', 'Insert Treatment Modalities', 'INSERT INTO treatment_modalities (thid, mmcode, upddate, upduser, updprog) VALUES(LAST_INSERT_ID(), ''WP'', ''2009-08-21 12:30:47'', ''Administrator'', ''/index.php'')'),
(0, '2009-08-21 12:30:47', 'Administrator', 0, 'treatment', 'INSERT', 'Insert Treatment Individual Procedures', 'INSERT INTO treatment_procedures (thid, pmcode, upddate, upduser, updprog) VALUES(LAST_INSERT_ID(), ''BIO'', ''2009-08-21 12:30:47'', ''Administrator'', ''/index.php'')'),
(127, '2009-08-21 12:53:00', 'WSPatient', 0, 'patientdashboard', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''127'''),
(128, '2009-08-21 12:53:01', 'WSPatient', 0, 'patientdashboard', 'UPDATE', 'Updated Treatment Status [100]', 'UPDATE treatment_header SET thsbmStatus=''100'' WHERE thid=''128'''),
(122, '2009-08-21 14:07:05', 'WSUR', 0, 'treatment', 'UPDATE', 'Updated Treatment Status [900]', 'UPDATE treatment_header SET thsbmStatus=''900'' WHERE thid=''122''');

-- --------------------------------------------------------

--
-- Table structure for table `treatment_modalities`
--

CREATE TABLE IF NOT EXISTS `treatment_modalities` (
  `thid` int(11) NOT NULL default '0',
  `mmcode` char(3) NOT NULL default '0',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`thid`,`mmcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treatment_modalities`
--

INSERT INTO `treatment_modalities` (`thid`, `mmcode`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(66, 'TRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(65, 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(62, 'IC', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(61, 'WP', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(62, 'WP', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(61, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(60, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(59, '15A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(57, 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(57, 'TRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(58, 'IRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(58, '15A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(66, 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(66, 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(69, 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(70, 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(70, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(70, 'WP', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(75, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(76, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(81, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(79, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(78, '15A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(82, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(84, 'IRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(86, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(87, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(88, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(89, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(90, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(91, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(92, 'IRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(92, '15A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(92, 'MRS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(92, 'EA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(93, '15A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(93, 'MRS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(94, 'TRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(94, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(94, 'IR', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(95, 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(95, 'WP', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(95, 'IR', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'TRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(97, 'IRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(98, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(99, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'TRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'WP', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'IC', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'IR', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'US', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(103, 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(103, 'IC', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'WP', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'IC', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'IR', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'US', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(108, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(109, '15A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(109, 'IRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(109, 'MRS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(109, 'EA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(117, '15A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(117, 'MRS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(118, 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(118, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(118, 'WP', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(118, 'IC', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(120, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(121, 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(121, 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(121, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(122, 'TRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(128, 'IC', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(124, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(125, '15P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(126, 'IRA', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(126, '15A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(127, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(127, 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(128, 'PB', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(128, 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(124, 'WP', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(129, 'ELS', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(129, 'TEN', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(131, 'WP', '0000-00-00 00:00:00', '', '', '2009-08-21 12:30:47', 'Administrator', '/index.php'),
(130, 'IRA', '0000-00-00 00:00:00', '', '', '2009-08-20 18:03:23', 'ClinicUser', '/index.php'),
(131, 'TRA', '0000-00-00 00:00:00', '', '', '2009-08-21 12:30:47', 'Administrator', '/index.php');

-- --------------------------------------------------------

--
-- Table structure for table `treatment_procedures`
--

CREATE TABLE IF NOT EXISTS `treatment_procedures` (
  `thid` int(11) NOT NULL default '0',
  `pmcode` char(3) NOT NULL default '0',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`thid`,`pmcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treatment_procedures`
--

INSERT INTO `treatment_procedures` (`thid`, `pmcode`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(75, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(76, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(76, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(76, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(78, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(79, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(80, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(80, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(80, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(81, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(82, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(83, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(83, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(83, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(84, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(85, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(86, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(87, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(88, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(89, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(90, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(91, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(92, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(93, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(94, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(94, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(94, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(95, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(95, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(95, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(100, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(97, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(98, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(99, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(101, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(103, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(103, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(103, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(104, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(101, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(101, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(106, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(106, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(106, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(106, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(108, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(107, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(109, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(110, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(110, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(110, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(111, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(112, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(113, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(114, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(115, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(113, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(113, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(118, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(117, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(118, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(120, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(121, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(121, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(121, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(122, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(124, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(124, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(125, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(126, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(127, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(127, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(127, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(128, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(128, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(128, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(124, 'TE', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(129, 'BIO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(129, 'ISO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(129, 'MYO', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(130, 'A', '0000-00-00 00:00:00', '', '', '2009-08-20 18:03:23', 'ClinicUser', '/index.php'),
(131, 'BIO', '0000-00-00 00:00:00', '', '', '2009-08-21 12:30:47', 'Administrator', '/index.php');

-- --------------------------------------------------------

--
-- Table structure for table `treatment_procedure_groups`
--

CREATE TABLE IF NOT EXISTS `treatment_procedure_groups` (
  `thid` int(11) NOT NULL default '0',
  `gmcode` char(3) NOT NULL default '0',
  `crtdate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Created Date',
  `crtuser` varchar(16) NOT NULL default '' COMMENT 'Created By User',
  `crtprog` varchar(16) NOT NULL default '' COMMENT 'Created By Program',
  `upddate` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Updated Date',
  `upduser` varchar(16) NOT NULL default '' COMMENT 'Updated By User',
  `updprog` varchar(16) NOT NULL default '' COMMENT 'Updated By Program',
  PRIMARY KEY  (`thid`,`gmcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treatment_procedure_groups`
--

INSERT INTO `treatment_procedure_groups` (`thid`, `gmcode`, `crtdate`, `crtuser`, `crtprog`, `upddate`, `upduser`, `updprog`) VALUES
(61, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(57, 'MT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(62, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(63, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(65, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(66, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(67, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(69, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(70, 'IT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(76, 'BIM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(75, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(78, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(79, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(80, 'BIM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(81, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(82, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(83, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(84, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(85, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(86, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(87, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(88, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(89, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(90, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(91, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(92, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(93, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(94, 'BIT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(95, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(100, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(97, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(98, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(99, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(105, 'BIM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(102, 'BIT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(103, 'BIM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(106, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(108, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(109, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(110, 'BIM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(117, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(118, 'IM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(120, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(121, 'BIM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(128, 'BIM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(125, 'P', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(126, 'A', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(127, 'BMT', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(129, 'BIM', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', ''),
(130, 'A', '0000-00-00 00:00:00', '', '', '2009-08-20 18:03:23', 'ClinicUser', '/index.php');
