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
define('BS_ARRAYDB_VERSION',      '4.5.$Revision: 1.2 $');class Bs_ArrayDb extends Bs_Object {var $_dataArray;var $_bsDb;var $_tableName;function Bs_ArrayDb() {parent::Bs_Object(); $this->_bsDb      = &$GLOBALS['bsDb'];}
function init($data) {do {if (!is_array($data)) break; if (empty($data))     break; $this->_dataArray = &$data;$struct = $this->_determineArrayStructure();if (!is_array($struct)) break; $status = $this->_createTempTable($struct);if (!$status) break; $status = $this->_feedDb();if (!$status) break; return TRUE;} while (FALSE);return FALSE;}
function query($sqlQuery) {$sqlQuery = str_replace('[array]', $this->_tableName, $sqlQuery);return $this->_bsDb->getAll($sqlQuery);}
function _determineArrayStructure() {$struct = array();$someRecord = current($this->_dataArray);while (list($field) = each($someRecord)) {$struct[$field] = array(
'type' => 'number', 
'size' => 0, 
);}
reset($this->_dataArray);while (list(,$record) = each($this->_dataArray)) {while (list($field) = each($record)) {$size    = strlen($record[$field]);$numeric = is_numeric($record[$field]);if ($size > $struct[$field]['size'])                    $struct[$field]['size'] = $size;if (!$numeric && ($struct[$field]['type'] == 'number')) $struct[$field]['type'] = 'string';}
}
return $struct;}
function _createTempTable($struct) {do {$tableName = $this->_createRandomTableName();$sqlC  = "CREATE TEMPORARY TABLE {$tableName} (";$fieldDefs = array();while (list($fieldName) = each($struct)) {$t = $this->_getInternalFieldName($fieldName) . ' ';if ($struct[$fieldName]['type'] == 'number') {$t .= "INT NOT NULL DEFAULT 0";} else {$t .= "VARCHAR(255) NOT NULL DEFAULT ''";}
$fieldDefs[] = $t;}
$sqlC .= join(', ', $fieldDefs);$sqlC .= ')';$status = $this->_bsDb->write($sqlC);if (!$status) break; $this->_tableName = $tableName;return TRUE;} while (FALSE);return FALSE;}
function _feedDb() {$sqlInsertBase = "INSERT INTO {$this->_tableName} SET ";reset($this->_dataArray);while (list(,$record) = each($this->_dataArray)) {$sqlI = array();while (list($field) = each($record)) {$sqlI[] = $this->_getInternalFieldName($field) . "='" . addSlashes($record[$field]) . "'";}
$sql = $sqlInsertBase . join(', ', $sqlI);$status = $this->_bsDb->write($sql);}
return TRUE;}
function _createRandomTableName() {return 'bs_' . 'temp';}
function _getInternalFieldName($externalFieldName) {return $externalFieldName;}
}
?>