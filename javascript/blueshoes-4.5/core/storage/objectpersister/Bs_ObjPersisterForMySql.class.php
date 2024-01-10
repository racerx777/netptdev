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
define('BS_OBJPERSISTERFORMYSQL_VERSION',      '4.5.$Revision: 1.2 $');require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'storage/objectpersister/Bs_ObjPersister.class.php');require_once($APP['path']['core'] . 'db/Bs_MySql.class.php');class Bs_ObjPersisterForMySql extends Bs_ObjPersister {var $bsDb;var $_dbName;var $_dbTableName;function Bs_ObjPersisterForMySql(&$obj, $varSettings=NULL, $dbObject=NULL) {parent::Bs_ObjPersister($obj, $varSettings);if (is_null($dbObject)) {$this->bsDb = &$GLOBALS['bsDb'];} else {$this->bsDb = $dbObject;}
}
function setDbName($string) {$this->_dbName = $string;}
function setDbTableName($string=NULL) {if (is_null($string)) {$this->_dbTableName = BS_OP_TABLE_PREFIX . strToLower(get_class($this->_objToPersist));} else {$this->_dbTableName = $string;}
}
function _getDbTableString() {$ret = '';if (isSet($this->_dbName)) {$ret .= $this->_dbName . '.';}
if (!isSet($this->_dbTableName)) {$this->setDbTableName();}
$ret .= $this->_dbTableName;return $ret;}
function _getDbTableArray() {$ret = array();if (isSet($this->_dbName)) {$ret['dbName'] = $this->_dbName;}
if (!isSet($this->_dbTableName)) {$this->setDbTableName();}
$ret['dbTableName'] = $this->_dbTableName;return $ret;}
function dropTable() {$query = 'DROP TABLE IF EXISTS ' . $this->_getDbTableString();$ret = $this->_Bs_Db->write($query);if (isEx($ret)) {$ret->stackTrace('in dropTable()', __FILE__, __LINE__);return $ret;}
return TRUE;}
function createTable() {$query  = 'CREATE TABLE IF NOT EXISTS ' . $this->_getDbTableString() . ' (';$query .= 'ID INT UNSIGNED NOT NULL DEFAULT 0 AUTO_INCREMENT, ';  $queryKeys[] = 'PRIMARY KEY ID (ID)';$query .= BS_OP_BLOB_HASH_NAME . ' BLOB NOT NULL, '; $persistInfo = &$this->getPersistInfo();reset($persistInfo);foreach ($persistInfo as $key => $pI) {if ($key === 'ID') continue; if ($pI['mode'] === 'lonely') {$fieldName = $this->_getKeyName($key, $pI['name']);if (isSet($pI['metaType'])) {$query .= $this->_alterTableLineHelper($fieldName, $pI['metaType']) . ', ';if (($pI['index']) && (($pI['metaType'] != 'blob') && ($pI['metaType'] != 'stream'))) 
$queryKeys[] = "KEY {$fieldName} ({$fieldName})";} else {$query .= $fieldName . " BLOB, ";}
}
}
$query .= join(',', $queryKeys);$query .= ')';$ret = $this->bsDb->write($query);if (isEx($ret)) {$ret->stackTrace('in createTable()', __FILE__, __LINE__);return $ret;}
return TRUE;}
function _alterTableLineHelper($fieldName, $dataType) {$query = '';switch ($dataType) {case 'boolean':
$query .= $fieldName . ' SMALLINT NOT NULL DEFAULT 0';break;case 'integer':
$query .= $fieldName . ' INT NOT NULL DEFAULT 0';break;case 'double':
$query .= $fieldName . ' DOUBLE NOT NULL DEFAULT 0';break;case 'datetime':
$query .= $fieldName . ' DATETIME NOT NULL DEFAULT \'0000-00-00 00:00:00\'';break;case 'stream':
case 'blob':
$query .= $fieldName . ' BLOB NOT NULL';break;case 'string':
$query .= $fieldName . " VARCHAR(255) NOT NULL DEFAULT ''";break;default:
$query .= $fieldName . " BLOB";}
return $query;}
function updateTableStructure($checkOnly=FALSE) {$dbTableName = $this->_getDbTableString();$madeChanges = FALSE;$current = $this->bsDb->getTableProperties($dbTableName);$persistInfo = &$this->getPersistInfo();reset($persistInfo);while (list($key) = each($persistInfo)) {if (isSet($current[$k])) {} else {if ($persistInfo[$key]['mode'] === 'lonely') {$fieldName = $this->_getKeyName($key, $persistInfo[$key]['name']);if (isSet($persistInfo[$key]['metaType'])) {$sqlMiddle = $this->_alterTableLineHelper($fieldName, $persistInfo[$key]['metaType']);if (($persistInfo[$key]['index']) && (($persistInfo[$key]['metaType'] != 'blob') && ($persistInfo[$key]['metaType'] != 'stream'))) {$queryKeys[] = "KEY {$fieldName} ({$fieldName})";}
} else {$sqlMiddle = $fieldName . " BLOB";}
$madeChanges = TRUE;if (!$checkOnly) {$sqlA = "ALTER TABLE {$dbTableName} ADD " . $sqlMiddle;$status = $this->bsDb->write($sqlA);if (isEx($status)) {$status->stackTrace('was here in updateTableStructure()', __FILE__, __LINE__);return $status;}
}
}
}
}
return $madeChanges;}
function persist() {$t = $this->_getDbTableArray();if (!isSet($t['dbName'])) $t['dbName'] = NULL;$status = $this->bsDb->tableExists($t['dbTableName'], $t['dbName'], FALSE); if (isEx($status)) {$status->stackTrace('in persist().', __FILE__, __LINE__);return $status;}
if (!$status) {$status = $this->createTable();if (isEx($status)) {$status->stackTrace('in persist()', __FILE__, __LINE__);return $status;}
}
$persistData = &$this->_getPersistData();if ((isSet($persistData['ID'])) && ($persistData['ID'] > 0)) {$sql      = 'UPDATE ';$idGiven  = TRUE;} else {$sql      = 'INSERT INTO ';$idGiven  = FALSE;if (isSet($persistData['ID'])) unset($persistData['ID']);}
$sql .= $this->_getDbTableString() . ' SET ';$firstLoop = TRUE;foreach($persistData as $key => $val) {if ($firstLoop) $firstLoop=FALSE; else $sql .= ', ';if (($key === 'ID') || ($key === BS_OP_BLOB_HASH_NAME)) {$fieldName = $key;} else {$fieldName = $this->_getKeyName($key, $this->_persistInfo[$key]['name']);}
$sql .= "{$fieldName} = '" . addSlashes($val) . "'";}
if ($idGiven) {$sql .= " WHERE ID = {$persistData['ID']}";$statusFromWrite = $this->bsDb->write($sql);} else {$statusFromWrite = $this->bsDb->idWrite($sql);}
if (isEx($statusFromWrite)) {$status2 = $this->updateTableStructure($dbTableName, $persistData);if (isEx($status2)) {$status2->stackTrace('was here in persist()', __FILE__, __LINE__);return $status2;}
if ($status2) {$status = $this->bsDb->idWrite($sql);if (isEx($status)) {$status->stackTrace('was here in persist()', __FILE__, __LINE__);return $status;}
} else {$status->stackTrace('was here in persist()', __FILE__, __LINE__);return $status;}
}
if (is_int($statusFromWrite)) $this->setPersisterID($statusFromWrite); return TRUE;}
function unPersist($sqlSomething=NULL, $fullQuery=FALSE) {if (is_array($sqlSomething)) {$rsArray = &$sqlSomething;} else {if (is_string($sqlSomething)) {$sqlWhere = &$sqlSomething;} elseif ((isSet($this->_objToPersist->persisterID)) && ($this->_objToPersist->persisterID > 0)) {$sqlWhere = 'WHERE ID = ' . $this->_objToPersist->persisterID;} else {return FALSE;}
if ($fullQuery === TRUE) {$query = &$sqlSomething;} else {$query = 'SELECT * FROM  ' . $this->_getDbTableString() . ' ' . $sqlWhere . ' LIMIT 1';}
$rsArray = &$this->bsDb->getRow($query);if (isEx($rsArray)) {$rsArray->stackTrace('in unpersist()', __FILE__, __LINE__);return $rsArray;} elseif (sizeOf($rsArray) == 0) {return FALSE;}
}
foreach ($rsArray as $key => $val) {$prefixLength = strlen(BS_OP_FIELD_PREFIX);if (strpos($key, BS_OP_FIELD_PREFIX) === 0) {$rsArray2[substr($key, $prefixLength)] = &$rsArray[$key];} else {$rsArray2[$key] = &$rsArray[$key];}
}
$this->_setPersistData($rsArray2);return TRUE;}
}
?>