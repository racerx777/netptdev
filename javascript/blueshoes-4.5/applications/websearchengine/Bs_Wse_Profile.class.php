<?php/********************************************************************************************
* BlueShoes Framework; This file is part of the php application framework.
* NOTE: This code is stripped (obfuscated). To get the clean documented code goto 
*       www.blueshoes.org and register for the free open source *DEVELOPER* version or 
*       buy the commercial version.
*       
*       In case you've already got the developer version, then this is one of the few 
*       packages/classes that is only available to *PAYING* customers.
*       To get it go to www.blueshoes.org and buy a commercial version.
* 
* @copyright www.blueshoes.org
* @author    Samuel Blume <sam at blueshoes dot org>
* @author    Andrej Arn <andrej at blueshoes dot org>
*/?><?php
require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_Profile.class.php');require_once($APP['path']['XPath']   . 'XPath.class.php');Class Bs_Wse_Profile extends Bs_Object {var $_APP;var $_bsDb;var $_indexBsDb;var $_xPath;var $_isProfile;var $profileName;var $_xml;var $indexIframes = 1;var $detectDublicatePages = TRUE;var $ignoreUrlsWithUser = TRUE;var $ignoreUrlsWithPass = TRUE;var $queryStringUrlLimit = 10;var $reindexIfUnchanged = 30;var $refetchAfter = 10;var $limitDomains;var $allowIgnore;var $allowUrls = array();var $ignoreUrls = array();var $categories;var $ignoreFileExtensions = array('zip', 'tar', 'tgz', 'gz', 'bz2', 'pdb', 'chm');var $weightProperties = array(
'domain'      => array('weight' => 50), 
'path'        => array('weight' => 100), 
'file'        => array('weight' => 100), 
'queryString' => array('weight' => 80), 
'title'       => array('weight' => 100), 
'description' => array('weight' => 40), 
'keywords'    => array('weight' => 5), 
'links'       => array('weight' => 100), 
'h1'          => array('weight' => 80), 
'h2'          => array('weight' => 70), 
'h3'          => array('weight' => 60), 
'h4'          => array('weight' => 50), 
'h5'          => array('weight' => 40), 
'h6'          => array('weight' => 30), 
'h7'          => array('weight' => 20), 
'h8'          => array('weight' => 10), 
'b'           => array('weight' => 10), 
'i'           => array('weight' => 8), 
'u'           => array('weight' => 8), 
'body'        => array('weight' => 5), 
'image'       => array('weight' => 5), 
);var $waitAfterIndex = 0;var $useKeywords = 1;var $useDescription = 1;function Bs_Wse_Profile() { parent::Bs_Object(); $this->_APP  = &$GLOBALS['APP'];$this->_isProfile =& new Bs_Is_Profile();}
function setDbByObj(&$bsDb) {unset($this->_bsDb);$this->_bsDb = &$bsDb;$this->_isProfile->setDbByObj($this->_bsDb);}
function setDbByDsn($dsn) {bs_lazyLoadClass('db/Bs_Db.class.php');$bsDb = &getDbObject($dsn);if (isEx($bsDb)) {$bsDb->stackTrace('was here in setDbByDsn()', __FILE__, __LINE__, 'fatal');return $bsDb;}
$this->_bsDb = &$bsDb;$this->_isProfile->setDbByObj($this->_bsDb);return TRUE;}
function &getIndexDbObj() {return $this->_indexBsDb;}
function load($profileName) {$sql  = "SELECT xml FROM Bs_Wse_Indexes WHERE caption = '{$profileName}'";$data = $this->_bsDb->getRow($sql);if (is_null($data)) return FALSE; if (isEx($data)) {$data->stackTrace('was here in load()', __FILE__, __LINE__);return $data;}
$this->profileName = $profileName;$this->_xml        = $data['xml'];$status = $this->_loadXpath();if (isEx($status)) {$status->stackTrace('was here in load()', __FILE__, __LINE__);return $status;}
$this->_indexBsDb =& $this->_bsDb; $this->_isProfile =& new Bs_Is_Profile();$this->_isProfile->setDbByObj($this->_bsDb);$status = $this->_isProfile->load($profileName);return TRUE;}
function create($profileName, $wseXml, $isXml) {$this->profileName = $profileName;$this->_xml         = $wseXml;$status = $this->_addProfile();if (isEx($status)) {$status->stackTrace('was here in create()', __FILE__, __LINE__);return $status;}
$status = $this->_createDbTables();if (isEx($status)) {$status->stackTrace('was here in create()', __FILE__, __LINE__);return $status;}
$status = $this->_isProfile->create($profileName, $isXml);return TRUE;}
function drop($profileName) {if (empty($profileName)) {return new Bs_Exception("profileName may not be empty in drop().", __FILE__, __LINE__);} elseif (strpos($profileName, '%') !== FALSE) {return new Bs_Exception("profileName may not include the '%' character in drop(). profileName was specified as: '" . $profileName . "'.", __FILE__, __LINE__);}
$tables = array('Queue', 'Links', 'Pages');foreach($tables as $tablePartName) {$sql = "DROP TABLE IF EXISTS Bs_Wse_{$profileName}_{$tablePartName}";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in drop()', __FILE__, __LINE__);return $status;}
}
$sql = "DELETE FROM Bs_Wse_Indexes WHERE caption = '{$profileName}'";$status = $this->_bsDb->countWrite($sql);if (is_numeric($status)) {$ret = ($status > 0);} else {$status->stackTrace('was here in drop()', __FILE__, __LINE__);$ret = $status;}
$status = $this->_isProfile->drop($profileName);return $ret;}
function prune($profileName) {$tables = array('Queue', 'Links', 'Pages');foreach($tables as $tablePartName) {$sql = "DELETE FROM Bs_Wse_{$profileName}_{$tablePartName}";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in prune()', __FILE__, __LINE__);return $status;}
}
$status = $this->_isProfile->prune($profileName);return TRUE;}
function reset() {unset($this->_xPath);unset($this->profileName);$status = $this->_isProfile->reset();}
function getProfileName() {return $this->profileName;}
function getCategoryForUrl($url, $urlParsed) {if (empty($this->categories) || !is_array($this->categories)) return '';foreach($this->categories as $arr) {switch (@$arr['type']) {case 'preg':
break;case 'ereg':
break;case 'file':
$t1 = (string)$arr['value'];$t2 = (string)@$urlParsed['file'];if ($t1 === $t2) return $arr['category'];break;default: if (strpos($url, $arr['value']) !== FALSE) return $arr['category'];}
}
return '';}
function checkDbTables() {$status = $this->_isProfile->checkDbTables();return $status;}
function _addProfile() {if (!$this->_bsDb->tableExists('Bs_Wse_Indexes', NULL, TRUE)) {$sql = "
CREATE TABLE IF NOT EXISTS Bs_Wse_Indexes (
caption varchar(20) NOT NULL DEFAULT '', 
xml     blob NOT NULL DEFAULT '', 
PRIMARY KEY caption (caption), 
UNIQUE (caption)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _addProfile()', __FILE__, __LINE__);return $status;}
}
$sql = "INSERT INTO Bs_Wse_Indexes SET caption = '{$this->profileName}', xml='" . addSlashes($this->_xml) . "'";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _addProfile()', __FILE__, __LINE__);return $status;}
return TRUE;}
function _createDbTables() {$sql = "
CREATE TABLE IF NOT EXISTS Bs_Wse_Indexes (
caption varchar(20) NOT NULL DEFAULT '', 
xml     blob NOT NULL DEFAULT '', 
PRIMARY KEY caption (caption), 
UNIQUE (caption)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
$sql = "
CREATE TABLE IF NOT EXISTS Bs_Wse_{$this->profileName}_Queue (
ID             INT UNSIGNED NOT NULL DEFAULT 0 AUTO_INCREMENT, 
sourceID       varchar(255) not null default '', 
todo           CHAR(1) NOT NULL DEFAULT 'a', 
PRIMARY KEY ID (ID), 
KEY sourceID(sourceID)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
$sql = "
CREATE TABLE IF NOT EXISTS Bs_Wse_{$this->profileName}_Pages (
ID                     int not null default 0 auto_increment, 
url                    varchar(255) not null default '', 
category               varchar(50)  not null default '', 
title                  varchar(255) not null default '', 
description            varchar(255) not null default '', 
contentSnapshot        blob not null, 
language               char(5) not null default '', 
mimeType               varchar(30)  not null default 'text/html', 
firstIndexDatetime     datetime not null default '0000-00-00 00:00:00', 
lastIndexDatetime      datetime not null default '0000-00-00 00:00:00', 
changeFrequency        tinyint not null default 0, 
contentMd5             CHAR(40) NOT NULL DEFAULT '', 
sizeBytes              int not null default 0, 
PRIMARY KEY  ID         (ID), 
KEY          url        (url), 
KEY          contentMd5 (contentMd5)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
$sql = "
CREATE TABLE IF NOT EXISTS Bs_Wse_{$this->profileName}_Links (
ID           int not null default 0 auto_increment, 
urlFrom      varchar(255) not null default '', 
urlTo        varchar(255) not null default '', 
caption      varchar(255) not null default '', 
isExternal   tinyint      not null default 0, 
PRIMARY KEY  ID (ID), 
KEY urlFrom  (urlFrom), 
KEY urlTo    (urlTo)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
$sql = "
CREATE TABLE IF NOT EXISTS Bs_Wse_{$this->profileName}_SearchLog (
ID           int not null default 0 auto_increment, 
searchString varchar(255) not null default '', 
numResults   smallint not null default 0, 
offset       smallint not null default 0, 
ip           varchar(60) not null default '', 
host         varchar(255) not null default '', 
userAgent    varchar(255) not null default '', 
PRIMARY KEY  ID (ID), 
KEY urlFrom  (searchString)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
return TRUE;}
function _loadXpath() {$xmlOpt = array(XML_OPTION_CASE_FOLDING=>TRUE, XML_OPTION_SKIP_WHITE=>FALSE);$xPath =& new XPath(FALSE, $xmlOpt);$xPath->setVerbose(FALSE);$status = $xPath->importFromString($this->_xml);if (isEx($status)) {$status->stackTrace('was here in _loadXpath()', __FILE__, __LINE__);return $status;}
$this->_xPath = &$xPath;return TRUE;}
}
?>