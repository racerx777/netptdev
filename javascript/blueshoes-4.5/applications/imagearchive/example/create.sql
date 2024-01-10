CREATE TABLE `exampleCugImageArchive` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `user` varchar(20) NOT NULL default '',
  `pass` varchar(20) NOT NULL default '',
  `isActive` tinyint(4) NOT NULL default '0',
  `startDatetime` datetime default NULL,
  `endDatetime` datetime default NULL,
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`)
) TYPE=MyISAM;
