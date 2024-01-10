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
require_once($APP['path']['XPath'] . 'XPath.class.php');Class Bs_Is_Profile extends Bs_Object {var $_APP;var $_bsDb;var $_indexBsDb;var $_xPath;var $profileName;var $_xml;var $_source = array(
'type'     => 'db', 
'dsn'      => '-default-', 
'database' => '-default-', 
'table'    => '', 
);var $_fields;var $_minWordLength = 3;var $_maxWordLength = 30;var $_indexNumbers = TRUE;function Bs_Is_Profile() { parent::Bs_Object(); $this->_APP  = &$GLOBALS['APP'];}
function setDbByObj(&$bsDb) {unset($this->_bsDb);$this->_bsDb = &$bsDb;}
function setDbByDsn($dsn) {bs_lazyLoadClass('db/Bs_Db.class.php');$bsDb = &getDbObject($dsn);if (isEx($bsDb)) {$bsDb->stackTrace('was here in setDbByDsn()', __FILE__, __LINE__, 'fatal');return $bsDb;}
$this->_bsDb = &$bsDb;return TRUE;}
function &getIndexDbObj() {return $this->_indexBsDb;}
function load($profileName) {$sql  = "SELECT xml FROM Bs_Is_Indexes WHERE caption = '{$profileName}'";$data = $this->_bsDb->getRow($sql);if (is_null($data)) return FALSE; if (isEx($data)) {$data->stackTrace('was here in load()', __FILE__, __LINE__);return $data;}
$this->profileName = $profileName;$this->_xml        = $data['xml'];$status = $this->_loadXpath();if (isEx($status)) {$status->stackTrace('was here in load()', __FILE__, __LINE__);return $status;}
$this->_indexBsDb =& $this->_bsDb; $type     = $this->_xPath->getData('/BLUESHOES[1]/BS:INDEX[1]/BS:SOURCE[1]/BS:TYPE[1]');if ($type     === FALSE) $type     = 'db';$dsn      = $this->_xPath->getData('/BLUESHOES[1]/BS:INDEX[1]/BS:SOURCE[1]/BS:DSN[1]');if ($dsn      === FALSE) $dsn      = '-default-';$database = $this->_xPath->getData('/BLUESHOES[1]/BS:INDEX[1]/BS:SOURCE[1]/BS:DATABASE[1]');if ($database === FALSE) $database = '-default-';$table    = $this->_xPath->getData('/BLUESHOES[1]/BS:INDEX[1]/BS:SOURCE[1]/BS:TABLE[1]');$this->_source = array(
'type'     => $type, 
'dsn'      => $dsn, 
'database' => $database, 
'table'    => $table, 
);$minWordLength = $this->_xPath->getData('/BLUESHOES[1]/BS:INDEX[1]/BS:MINLENGTH[1]');if ($minWordLength === FALSE) $minWordLength = 3;$this->_minWordLength = $minWordLength;$maxWordLength = $this->_xPath->getData('/BLUESHOES[1]/BS:INDEX[1]/BS:MAXLENGTH[1]');if ($maxWordLength === FALSE) $maxWordLength = 30;$this->_maxWordLength = $maxWordLength;$t = $this->_xPath->match('/BLUESHOES[1]/BS:INDEX[1]/BS:FIELDS[1]');if ($t === FALSE) {$this->_loadDbFieldStructure();} else {$xpv = $this->_xPath->match('/BLUESHOES[1]/BS:INDEX[1]/BS:FIELDS[1]/BS:FIELD');$size = sizeOf($xpv);for ($i=0; $i<$size; $i++) {$element = $xpv[$i];$eleAttr = $this->_xPath->getAttributes($element);$fieldName = $eleAttr['NAME'];$doIndex   = $this->_xPath->getData($element . '/BS:INDEX[1]');if ($doIndex === FALSE) {} else {$doIndex = isTrue($doIndex);}
$weight   = $this->_xPath->getData($element . '/BS:WEIGHT[1]');if ($weight === FALSE) $weight = 50;$lang     = $this->_xPath->getData($element . '/BS:LANG[1]');if ($lang === FALSE) {$lang = array();} else {$lang = explode(';', $lang);}
$indexFiles = FALSE;$indexUrls  = FALSE;$foreignKey = FALSE;$this->_fields[$fieldName] = array(
'index'      => $doIndex, 
'weight'     => $weight, 
'lang'       => $lang, 
'indexFiles' => $indexFiles, 
'indexUrls'  => $indexUrls, 
'foreignKey' => $foreignKey, 
);}
}
return TRUE;}
function create($profileName, $xml) {$this->profileName = $profileName;$this->_xml         = $xml;$status = $this->_addProfile();if (isEx($status)) {$status->stackTrace('was here in create()', __FILE__, __LINE__);return $status;}
$status = $this->_createDbTables();if (isEx($status)) {$status->stackTrace('was here in create()', __FILE__, __LINE__);return $status;}
return TRUE;}
function drop($profileName) {if (empty($profileName)) {return new Bs_Exception("profileName may not be empty in drop().", __FILE__, __LINE__);} elseif (strpos($profileName, '%') !== FALSE) {return new Bs_Exception("profileName may not include the '%' character in drop(). profileName was specified as: '" . $profileName . "'.", __FILE__, __LINE__);}
$sql = "DROP TABLE IF EXISTS Bs_Is_{$profileName}_Queue";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in drop()', __FILE__, __LINE__);return $status;}
$sql = "DROP TABLE IF EXISTS Bs_Is_{$profileName}_Words";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in drop()', __FILE__, __LINE__);return $status;}
$sql = "DROP TABLE IF EXISTS Bs_Is_{$profileName}_wordToSource";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in drop()', __FILE__, __LINE__);return $status;}
$sql = "DROP TABLE IF EXISTS Bs_Is_{$profileName}_Collocations";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in drop()', __FILE__, __LINE__);return $status;}
$sql = "DELETE FROM Bs_Is_Indexes WHERE caption = '{$profileName}'";$status = $this->_bsDb->countWrite($sql);if (is_numeric($status)) {return ($status > 0);} else {$status->stackTrace('was here in drop()', __FILE__, __LINE__);return $status;}
}
function prune($profileName) {$sql = "DELETE FROM Bs_Is_{$profileName}_Queue";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in prune()', __FILE__, __LINE__);return $status;}
$sql = "DELETE FROM Bs_Is_{$profileName}_Words";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in prune()', __FILE__, __LINE__);return $status;}
$sql = "DELETE FROM Bs_Is_{$profileName}_wordToSource";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in prune()', __FILE__, __LINE__);return $status;}
$sql = "DELETE FROM Bs_Is_{$profileName}_Collocations";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in prune()', __FILE__, __LINE__);return $status;}
return TRUE;}
function reset() {unset($this->_xPath);unset($this->_fields);unset($this->profileName);}
function getProfileName() {return $this->profileName;}
function getSourceDbString() {$ret = '';if ($this->_source['database'] != '-default-') {$ret .= $this->_source['database'] . '.';}
$ret .= $this->_source['table'];return $ret;}
function getFieldSetting($fieldName, $setting) {return $this->_fields[$fieldName][$setting];}
function getFields() {return $this->_fields;}
function minWordLength() {return $this->_minWordLength;}
function maxWordLength() {return $this->_maxWordLength;}
function _loadXpath() {$xmlOpt = array(XML_OPTION_CASE_FOLDING=>TRUE, XML_OPTION_SKIP_WHITE=>FALSE);$xPath =& new XPath(FALSE, $xmlOpt);$xPath->setVerbose(FALSE);$status = $xPath->importFromString($this->_xml);if (isEx($status)) {$status->stackTrace('was here in _loadXpath()', __FILE__, __LINE__);return $status;}
$this->_xPath = &$xPath;return TRUE;}
function _loadDbFieldStructure() {$tblStruct = $this->_bsDb->getTableStructure($this->_source['table']);$this->_fields = $this->_loadDbFieldStructureHelper($tblStruct);return TRUE;}
function _loadDbFieldStructureHelper($tblStruct, $firstCall=TRUE) {$ret = array();while (list($fieldName) = each($tblStruct)) {$ret[$fieldName] = array();switch ($tblStruct[$fieldName]['type']) {case 'char':
case 'varchar':
$ret[$fieldName]['index']  = TRUE;$ret[$fieldName]['weight'] = 100;break;case 'blob':
$ret[$fieldName]['index'] = TRUE;$ret[$fieldName]['weight'] = 30;break;default:
$ret[$fieldName]['index'] = FALSE;$ret[$fieldName]['weight'] = 0;}
$ret[$fieldName]['indexFiles']  = FALSE;$ret[$fieldName]['indexUrls']   = FALSE;if ((!$firstCall) || ($tblStruct[$fieldName]['foreignKey'] === FALSE)) {$ret[$fieldName]['foreignKey']  = FALSE;} else {$ret[$fieldName]['index']  = TRUE; $ret[$fieldName]['weight'] = 10;   $foreignTblStruct = $this->_bsDb->getTableStructure($tblStruct[$fieldName]['foreignKey']['table'], $tblStruct[$fieldName]['foreignKey']['db']);$ret[$fieldName]['foreignKey']  = array(
'source' => array('dsn'=>null, 'database'=>$tblStruct[$fieldName]['foreignKey']['db'], 'table'=>$tblStruct[$fieldName]['foreignKey']['table']), 
'fields' => $this->_loadDbFieldStructureHelper($foreignTblStruct, FALSE), 
);}
}
return $ret;}
function checkDbTables() {return TRUE;}
function _addProfile() {if (!$this->_bsDb->tableExists('Bs_Is_Indexes', NULL, TRUE)) {$sql = "
CREATE TABLE IF NOT EXISTS Bs_Is_Indexes (
caption varchar(20) NOT NULL DEFAULT '', 
xml     blob NOT NULL DEFAULT '', 
PRIMARY KEY caption (caption), 
UNIQUE (caption)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _addProfile()', __FILE__, __LINE__);return $status;}
}
$sql = "INSERT INTO Bs_Is_Indexes SET caption = '{$this->profileName}', xml='" . addSlashes($this->_xml) . "'";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _addProfile()', __FILE__, __LINE__);return $status;}
return TRUE;}
function _createDbTables() {$sql = "
CREATE TABLE IF NOT EXISTS Bs_Is_{$this->profileName}_Queue (
ID             INT UNSIGNED NOT NULL DEFAULT 0 AUTO_INCREMENT, 
sourceID       varchar(255) not null default '', 
todo           CHAR(1) NOT NULL DEFAULT 'a', 
PRIMARY KEY ID (ID), 
KEY sourceID(sourceID)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
$sql = "
CREATE TABLE IF NOT EXISTS Bs_Is_{$this->profileName}_Words (
ID           int not null default 0 auto_increment, 
caption      varchar(40) not null default '', 
noitpac      varchar(40) not null default '', 
soundx       varchar(10) not null default '', 
stem         varchar(20) not null default '', 
languages    varchar(20) not null default '', 
useCount     int         not null default 0, 
searchCount  int         not null default 0, 
weight       smallint    not null default 0, 
PRIMARY KEY  ID (ID), 
UNIQUE KEY   caption (caption), 
KEY soundx   (soundx), 
KEY stem (stem), 
KEY useCount (useCount) 
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
$sql = "
create table IF NOT EXISTS Bs_Is_{$this->profileName}_WordToSource (
ID                  int not null default 0 auto_increment, 
wordID              int not null default 0, 
sourceID            varchar(255) not null default '', 
ranking             smallint not null default 0, 
rnbs_wordIDs        varchar(255) not null default '', 
primary key (ID), 
key(wordID), 
key sourceID (sourceID)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
$sql = "
create table IF NOT EXISTS Bs_Is_{$this->profileName}_Collocations (
ID                  int not null default 0 auto_increment, 
sourceID            varchar(255) not null default '', 
first_wordID        int not null default 0, 
second_wordID       int not null default 0, 
useCount            int not null default 0, 
ranking             smallint not null default 0, 
primary key (ID), 
key sourceID (sourceID), 
key first_wordID (first_wordID), 
key second_wordID (second_wordID), 
key useCount (useCount) 
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
return TRUE;}
}
?>