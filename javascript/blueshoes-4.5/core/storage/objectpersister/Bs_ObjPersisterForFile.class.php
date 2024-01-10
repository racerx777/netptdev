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
define('BS_OBJPERSISTERFORFILE_VERSION',      '4.5.$Revision: 1.2 $');require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'storage/objectpersister/Bs_ObjPersister.class.php');class Bs_ObjPersisterForFile extends Bs_ObjPersister {var $_path;var $_directory;function Bs_ObjPersisterForMySql(&$obj, $varSettings=NULL) {parent::Bs_ObjPersister($obj, &$varSettings);}
Bs_IniHandler.class.php
function setPath() {}
function getPath() {}
function setDirectory() {}
function getDirectory() {}
function dropDirectory() {}
function createDirectory() {}
function persist() {$t = $this->_getDbTableArray();if (!isSet($t['dbName'])) $t['dbName'] = NULL;$status = $this->bsDb->tableExists($t['dbTableName'], $t['dbName'], FALSE); if (isEx($status)) {$status->stackTrace('in persist().', __FILE__, __LINE__);return $status;}
if (!$status) {$status = $this->createTable();if (isEx($status)) {$status->stackTrace('in persist()', __FILE__, __LINE__);return $status;}
}
$persistData = &$this->_getPersistData();reset($persistData);if ((isSet($persistData['ID'])) && ($persistData['ID'] > 0)) {$sql      = 'UPDATE ';$idGiven  = TRUE;} else {$sql      = 'INSERT INTO ';$idGiven  = FALSE;if (isSet($persistData['ID'])) unset($persistData['ID']);}
$sql .= $this->_getDbTableString() . ' SET ';$firstLoop = TRUE;while (list($key) = each($persistData)) {if ($firstLoop) $firstLoop=FALSE; else $sql .= ', ';$fieldName = (($key === 'ID') || ($key === BS_OP_BLOB_HASH_NAME)) ? $key : BS_OP_FIELD_PREFIX . $key;$sql .= "{$fieldName} = '" . addSlashes($persistData[$key]) . "'";}
if ($idGiven) {$sql .= " WHERE ID = {$persistData['ID']}";$ret = $this->bsDb->write($sql);} else {$ret = $this->bsDb->idWrite($sql);if (is_int($ret)) {$this->setPersisterID($ret);}
}
if (isEx($ret)) {$ret->stackTrace('in persist(). i think you need to update your table...', __FILE__, __LINE__);return $ret;}
return TRUE;}
function unPersist($sqlSomething=NULL) {if (is_array($sqlSomething)) {$rsArray = &$sqlSomething;reset($rsArray);} else {if (is_string($sqlSomething)) {$sqlWhere = &$sqlSomething;} elseif ((isSet($this->_objToPersist->persisterID)) && ($this->_objToPersist->persisterID > 0)) {$sqlWhere = 'WHERE ID = ' . $this->_objToPersist->persisterID;} else {return FALSE;}
$query   = 'SELECT * FROM  ' . $this->_getDbTableString() . ' ' . $sqlWhere . ' LIMIT 1';$rsArray = &$this->bsDb->getRow($query);if (isEx($rsArray)) {$rsArray->stackTrace('in unpersist()', __FILE__, __LINE__);return $rsArray;} elseif (sizeOf($rsArray) == 0) {return FALSE;}
}
while (list($key) = each($rsArray)) {$prefixLength = strlen(BS_OP_FIELD_PREFIX);if (strpos($key, BS_OP_FIELD_PREFIX) === 0) {$rsArray2[substr($key, $prefixLength)] = &$rsArray[$key];} else {$rsArray2[$key] = &$rsArray[$key];}
}
$this->_setPersistData($rsArray2);return TRUE;}
}
?>