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
define('BS_DBWRAPPER_VERSION',      '4.5.$Revision: 1.3 $');class Bs_DbWrapper extends Bs_Object {var $_bsDb;var $_dbName;var $_dbTableName;var $_recordData;function Bs_DbWrapper() {parent::Bs_Object(); if (is_object($GLOBALS['bsDb'])) $this->_bsDb = &$GLOBALS['bsDb'];}
function setDbObj(&$dbObj) {unset($this->_bsDb);$this->_bsDb = &$dbObj;}
function setDbName($dbName) {$this->_dbName = $dbName;}
function setDbTableName($tableName) {$this->_dbTableName = &$tableName;}
function &getData($key=null) {if (!is_null($key)) {if (isSet($this->_recordData[$key])) {return $this->_recordData[$key];}
} else {return $this->_recordData;}
return null;}
function _isInitialized() {$ret = FALSE;do {if (!is_object($this->_bsDb))    break;if (!isSet($this->_dbTableName)) break;$ret = TRUE;} while (FALSE);return $ret;}
function _hasData() {if (!isSet($this->_recordData)) return FALSE;return TRUE;}
function initByID($id, $idFieldName='ID') {if (!is_numeric($id)) return FALSE;$sql   = "SELECT * FROM " . $this->_dbTableName . " WHERE {$idFieldName}={$id} LIMIT 1";$row   = $this->_bsDb->getRow($sql);return $this->initByData($row);}
function initByData($data) {if (!is_array($data)) return FALSE;$this->_recordData = $data;return TRUE;}
function dbInsert() {if (!$this->_isInitialized()) return FALSE;if (!$this->_hasData())       return FALSE;$temp = $this->_recordData;if (isSet($temp['ID']))        unset($temp['ID']);        if (isSet($temp['timestamp'])) unset($temp['timestamp']); $sql = "INSERT INTO " . $this->_dbTableName . " SET %s";$sql = $this->sqlPrepareByArray($sql, $temp);return $this->_bsDb->idWrite($sql);}
function dbUpdate() {if (!$this->_isInitialized()) return FALSE;if (!$this->_hasData())       return FALSE;$temp = $this->_recordData;$sql = "REPLACE " . $this->_dbTableName . " SET %s";$sql = $this->sqlPrepareByArray($sql, $temp);return $this->_bsDb->write($sql);}
function dbDelete() {if (!$this->_isInitialized()) return FALSE;if (!$this->_hasData())      return FALSE;$id = $this->_recordData['ID'];$sql = "DELETE FROM {$this->_dbTableName} WHERE ID={$id}";return $this->_bsDb->write($sql);}
function _extractTablesFromStr($sqlStr='') {$tb_List = array();$out_List = array();preg_match_all("/(\w+\.\w+)\.\w+/", $sqlStr, $regs);  $hits = sizeOf($regs[1]);for ($i=0; $i < $hits; $i++) {$tbName = &$regs[1][$i];$out_List[$tbName] = TRUE;}
preg_match_all("/(\w+)\.\w+\.\w+/", $sqlStr, $regs);  $hits = sizeOf($regs[1]);for ($i=0; $i < $hits; $i++) {$db_Name = &$regs[1][$i];$db_List[$db_Name] = TRUE;}
preg_match_all("/(\w+)\.\w+/", $sqlStr, $regs);  $hits = sizeOf($regs[1]);for ($i=0; $i < $hits; $i++) {$tb_Name = &$regs[1][$i];if (isSet($db_List[$tb_Name])) continue; $out_List[$tb_Name] = TRUE;}
return $out_List;}
function dbCount($whereCondition, $selectAddition='') {if (!$this->_isInitialized()) return FALSE;$tbList = $this->_extractTablesFromStr($whereCondition);$tbList[$this->tableName] = $this->_dbTableName;$fromClaus = '';$tbCount =  sizeOf($tbList);$i=0;while (list($tbName) = each($tbList)) {$i++;$fromClaus .= $tbName;$fromClaus .= ($i<$tbCount) ?  "," : " ";}
$sql = "SELECT " . $selectAddition . " count(*) FROM " . $fromClaus . $whereCondition;$row = $this->_bsDb->getRow($sql);if (isEx($row)) {$row->stackTrace('in dbCount()', __FILE__, __LINE__);return $row;}
return (int)$row['count(*)'];}
function getRecordList($fieldArray, $whereCondition="", &$orderArray, $offset=0, $maxAmount=-1, $selectAddition="") {if (!$this->_isInitialized()) return FALSE;$db = &$this->_bsDb;$ret    = array();$sqlStr = '';if (is_array($fieldArray)) {$sqlStr .= "SELECT " . $selectAddition . " %s FROM ";$sqlStr = $this->sqlPrepareByArray($sqlStr, $fieldArray);} else {if ($fieldArray == "") $fieldArray = "*";$sqlStr .= "SELECT " . $selectAddition . " " . $fieldArray . " FROM ";}
$tbList = $this->_extractTablesFromStr($whereCondition);$tbList = array_merge($tbList, $this->_extractTablesFromStr($sqlStr));$tbList[$this->tableName] =  $this->tableName; $fromClaus = "";$tbCount =  sizeOf($tbList);$i=0;while (list($tbName) = each($tbList)) {$i++;$fromClaus .= $tbName;$fromClaus .= ($i<$tbCount) ?  "," : " ";}
$sqlStr .= $fromClaus . $whereCondition;if (isSet($orderArray) AND is_array($orderArray)) $sqlStr .= " " . $this->sqlArrayToOrderBy($orderArray);if ($offset > 0) {$sqlStr .= " LIMIT " . $offset;if ($maxAmount > 0) $sqlStr .=  "," . $maxAmount;} else {if ($maxAmount > 0) $sqlStr .= " LIMIT " . $maxAmount;}
$ret = $db->getAll($sqlStr);if (isEx($ret)) {$ret->stackTrace('in getRecordList()', __FILE__, __LINE__);return $ret;}
return ($ret);}
function rlArrayToHashArray(&$rlArray, $hashKey="") {$ret = array();if ((!is_array($rlArray)) OR ($hashKey=="")) {echo "INTERNAL ERROR: in file: " . __FILE__ . " line : ". __LINE__ . "<br>";if (!is_array($rlArray)) echo " Param 1 is NOT an Array as expected! <br>";if ($hashKey=="") echo " Param 2 was not set or is empty! <br>";return;}
$size = sizeOf($rlArray);for ($i=0; $i<$size; $i++) {$ret[$rlArray[$i][$hashKey]] = &$rlArray[$i];}
return $ret;}
function sqlPrepareByArray($sqlFormatStr="", &$theArray) {$i=1; $arraySize=sizeOf($theArray);$out="";if (!is_array($theArray)) {echo "INTERNAL ERROR: Wrong use of sqlPrepareByArray (not an Array) in " . __FILE__ . " Line " .__LINE__; return ($out);}
reset($theArray);if (eregi("SELECT[ |\t]*\%s", $sqlFormatStr)) {  $isAnSimpleArray = isSet($theArray[0]); while (list ($key, $val) = each ($theArray)) {if ($isAnSimpleArray) $key=$val;if ($key=="") continue; $out .= $key;$out .= ($i<$arraySize) ? ", " : " ";$i++;}       
} 
elseif (eregi("[ |\t]SET[ |\t]*\%s", $sqlFormatStr)) { while (list ($key, $val) = each ($theArray)) {if ($key=="") continue; $out .= $key ."=" ."'" . addslashes($val) ."'";$out .= ($i<$arraySize) ? ", " : " ";$i++;}   
}
else {echo "INTERNAL ERROR: Wrong use of sqlPrepareByArray (wrong $sqlFormatStr) in " . __FILE__ . " Line " .__LINE__;}
reset($theArray);$out = sprintf($sqlFormatStr, $out);return $out;}
function sqlArrayToOrderBy (&$theArray) {if (!is_array($theArray)) {echo "INTERNAL ERROR: Wrong use of sqlArrayToOrderBy (not an Array) in " . __FILE__ . " Line " .__LINE__."<BR>"; var_dump($theArray);}
$i=1; $arraySize=sizeOf($theArray);reset($theArray);$isAnSimpleArray = isSet($theArray[0]); while (list($key, $val) = each($theArray)) {if ($isAnSimpleArray) $key="";if ($val=="") continue; $out .= $key . " " . $val;$out .= ($i<$arraySize) ? "," : " ";$i++;}
if (sizeOf($out)>0) {$out = " ORDER BY " . $out;}
return ($out);}
}
?>