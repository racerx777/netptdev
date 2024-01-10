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
define('BS_MYSQL_VERSION',      '4.5.$Revision: 1.3 $');require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_Db.class.php');class Bs_MySql extends Bs_Db {var $_databaseNames = NULL;var $_tableNames = NULL;var $_fieldNames = NULL;var $_iniVars = NULL;var $_statusVars = NULL;var $_currentlyOpenTransactionID = '';var $_features = array(
'prepare'      => FALSE,
'pconnect'     => TRUE,
'transactions' => FALSE,
'innerSelects' => FALSE,
'insertId'     => TRUE,
'affectedRows' => TRUE,
'numRows'      => TRUE,
'numCols'      => TRUE,
'storedProc'   => FALSE
);var $format = array(
'date'       => "'Y-m-d'",       'datetime'   => "'Y-m-d H:i:s'", 'timestamp'  => "'YmdHis'",      );function Bs_MySql() {parent::Bs_Db(); }
function autoCommit($on=FALSE) {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function startTransaction($transactionId='') {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE); if ((!empty($transactionId)) AND empty($this->_currentlyOpenTransactionID)) {$this->_currentlyOpenTransactionID = $transactionId;}
}
function commit($transactionId='') {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE); if (empty($this->_currentlyOpenTransactionID)) {} elseif ($this->_currentlyOpenTransactionID === $transactionId) {$this->_currentlyOpenTransactionID = '';} else {}
}
function rollback($transactionId='') {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE); if (empty($this->_currentlyOpenTransactionID)) {} elseif ($this->_currentlyOpenTransactionID === $transactionId) {$this->_currentlyOpenTransactionID = '';} else {}
}
function connect($dsn, $persistent=TRUE) {if (isSet($this->_connected)) $this->disconnect(); $dsnInfo = (is_array($dsn)) ? $dsn : $this->parseDSN($dsn);if (!$dsnInfo) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_DSN, __FILE__, __LINE__, 'fatal');static $defaultDsn = array('host'=>'localhost', 'port'=>'3306', 'syntax'=>'mysql', 'type'=>'mysql');$dsnInfo = array_merge($defaultDsn, $dsnInfo);if (empty($dsnInfo['type'])) $dsnInfo['type'] = 'mysql';$dbHost = ($dsnInfo['host']) ? $dsnInfo['host'] : 'localhost';$connectFunction = ($persistent) ? 'mysql_pconnect' : 'mysql_connect';if ($dsnInfo['user'] && $dsnInfo['pass']) {$conn = @$connectFunction($dbHost, $dsnInfo['user'], $dsnInfo['pass']);} elseif ($dsnInfo['user']) {$conn = @$connectFunction($dbHost, $dsnInfo['user']);} else {$conn = @$connectFunction($dbHost);}
if ($conn === FALSE) {$this->_connected  = FALSE;$e        = $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_DSN, __FILE__, __LINE__, 'fatal', 'Connection failed. Maybe a wrong password?');} elseif (!is_resource($conn)) {$this->_connected  = FALSE;$e        = $this->_mysqlRaiseError(BS_DB_ERROR_CONNECT_FAILED, __FILE__, __LINE__, 'fatal', $conn);} else { $this->_connected  = TRUE;$this->_connection = $conn;$this->_dsnInfo    = $dsnInfo;$this->_persistent = $persistent;}
if (!$this->_connected) { $funcArgs = func_get_args();$e->setStackParam('functionArgs', $funcArgs);return $e;}
if (!empty($dsnInfo['name'])) {if (!mysql_select_db($dsnInfo['name'], $conn)) {return $this->_mysqlRaiseError(BS_DB_ERROR_CANNOT_SELECT_DB, __FILE__, __LINE__, 'fatal', "desired db was: '{$dsnInfo['name']}'");}
}
return (int)(substr(strstr(((string)$conn), '#'), 1));}
function disconnect() {return parent::_disconnect('mysql_close');}
function selectDb($db) {if (!$this->_connected) return FALSE;if (mysql_select_db($db, $this->_connection)) {$this->_dsnInfo['name'] = $db;return TRUE;} else {return FALSE;}
}
function _query($query) {$this->_lastQuery = $query;if (!$this->_connected) return $this->_mysqlRaiseError(BS_DB_ERROR_NOT_CONNECTED, __FILE__, __LINE__, 'fatal');$ret = @mysqli_query($dbhandle,$query, $this->_connection);if (!$ret) {return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, 'fatal', "query was: '{$query}'");}
return $ret;}
function subSelect($queryWithSubquery) {preg_match('/^(.*\s+IN\s*)\(\s*(SELECT\s+.*)\)(.*$)/Ui', $queryWithSubquery, $regs);if (sizeOf($regs) == 0) { return $this->_query($queryWithSubquery);}
$result = $this->_query($regs[2]);if (isEx($result)) {$result->stackTrace('in subSelect() [Inner select failed]', __FILE__, __LINE__);return $result;}
$idList = '(';$firstLoop = TRUE;while ($row = @mysql_fetch_row($result)) {if ($firstLoop) $firstLoop=FALSE; else $idList .= ',';$idList .= $row[0];}
$errNo = @mysql_errno($this->_connection); if ($errNo != 0) {$errMsg = @mysql_error($this->_connection);return $this->_raiseError(NULL, $errNo, $errMsg, __FILE__, __LINE__, 'fatal');}
$idList .= ')';$newQuery = $regs[1] . $idList . $regs[3];$result = $this->_query($newQuery);if (isEx($result)) {$result->stackTrace('in subSelect() [Outer select failer]', __FILE__, __LINE__);}
return $result;}
function &fetchRow($result, $fetchMode=BS_DB_FETCHMODE_ASSOC) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');if ($fetchMode == BS_DB_FETCHMODE_ASSOC) {$row = @mysqli_fetch_assoc($result);} else {$row = @mysql_fetch_row($result);}
if (!$row) {$errNo = @mysql_errno($this->_connection);if ($errNo === 0) return NULL; $errMsg = @mysql_error($this->_connection);return $this->_raiseError(NULL, $errNo, $errMsg, __FILE__, __LINE__, 'fatal');}
return $row;}
function numCols($result) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$cols = @mysql_num_fields($result);if (!$cols) {return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__);}
return $cols;}
function numRows($result) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$rows = @mysqli_num_rows($result);if (is_null($rows)) {return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysqli_num_rows() failed in numRows()");}
return $rows;}
function affectedRows() {if ($this->isManipulation($this->_lastQuery)) {$ret = @mysql_affected_rows($this->_connection);if ($ret >= 0) return $ret;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_affected_rows() failed in affectedRows(). last query was (hopefully): '{$this->_lastQuery}'");}
return FALSE;}
function insertId() {return (int)@mysql_insert_id($this->_connection);}
function freeResult($result) {if (is_resource($result)) @mysql_free_result($result);}
function fieldName($result, $offset) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_field_name($result, $offset);if (is_string($ret)) return $ret;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_field_name() failed in fieldName() for field: '{$offset}'");}
function tableName($result, $offset) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_field_table($result, $offset);if (is_string($ret)) return $ret;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_field_table() failed in tableName() for table: '{$offset}'");}
function tableName2($result, $offset) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_tablename($result, $offset);if (is_string($ret)) return $ret;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_tablename() failed in tableName2() for table: '{$offset}'");}
function databaseName($result, $offset) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_db_name($result, $offset);if (is_string($ret)) return $ret;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_db_name() failed in databaseName().");}
function fieldLen($result, $offset) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_field_len($result, $offset);if (is_int($ret)) return $ret;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_field_len() failed in fieldLen() for field: '{$offset}'");}
function fieldFlags($result, $offset, $format='string') {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_field_flags($result, $offset);if (is_string($ret)) {switch ($format) {case 'vector':
return explode(' ', $ret);break;case 'hash':
$t = array('not_null'       => FALSE, 
'auto_increment' => FALSE, 
'primary_key'    => FALSE, 
'unique_key'     => FALSE, 
'multiple_key'   => FALSE, 
'unsigned'       => FALSE, 
'zerofill'       => FALSE, 
'binary'         => FALSE, 
'blob'           => FALSE, 'enum'           => FALSE, 'timestamp'      => FALSE  );$t2 = explode(' ', $ret);for ($i=sizeOf($t2)-1; $i>=0;$i--) {$t[$t2[$i]] = TRUE;}
return $t;break;default: return $ret;}
}
return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_field_flags() failed in fieldFlags() for field: '{$offset}'");}
function hasFieldFlag($result, $offset, $flag) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$t = $this->fieldFlags($result, $offset, 'hash');return (isSet($t[$flag])) ? $t[$flag] : NULL;}
function fieldType($result, $offset=0) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_field_type($result, $offset);if (is_string($ret)) return $ret;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_field_type() failed in fieldType() for field: '{$offset}'");}
function listDatabases() {$ret = @mysql_list_dbs($this->_connection);if (is_resource($ret)) return $ret;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_list_dbs() failed in listTables().");}
function listTables($dbName=NULL) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in listTables().");$ret = @mysql_list_tables($dbName, $this->_connection);if (is_resource($ret)) return $ret;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_list_tables() failed in listTables() with dbName: '{$dbName}'.");}
function listFields($tableName, $dbName=NULL) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in listFields().");$ret = @mysql_list_fields($dbName, $tableName, $this->_connection);if (is_resource($ret)) return $ret;$err = '';if ($ret<0) {GLOBAL $phperrmsg;$err = " phpErrMsg: '{$phperrmsg}'";} 
return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_list_fields() failed in listFields() with dbName: '{$dbName}' and tableName: '{$tableName}'.{$err}");}
function &fetchDatabaseNames($format='vector', $useCache=TRUE) {$ret = NULL;if (($useCache) && (is_array($this->_databaseNames))) {if ($format == 'vector') return $this->_databaseNames;$ret = join(', ', $this->_databaseNames);return $ret;}
$result = @mysql_list_dbs($this->_connection);if (isEx($result)) {$result->stackTrace('in fetchDatabaseNames()', __FILE__, __LINE__);return $result;}
$dbCount = $this->numRows($result);if (isEx($dbCount)) {$dbCount->stackTrace('in fetchDatabaseNames()', __FILE__, __LINE__);return $dbCount;}
$ret = array();for ($i=0; $i < $dbCount; $i++) {$t = $this->databaseName($result, $i);if (isEx($t)) {$t->stackTrace('in fetchDatabaseNames()', __FILE__, __LINE__);return $t;}
$ret[] = $t;}
$this->freeResult($result);$this->_databaseNames = $ret; if ($format != 'vector') {return join(', ', $ret);} else {return $this->_databaseNames;} 
}
function &fetchTableNames($dbName=NULL, $format='vector', $useCache=TRUE) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in fetchTableNames().");$ret = NULL;if (($useCache) && (is_array($this->_tableNames)) && (isSet($this->_tableNames[$dbName]))) {if ($format == 'vector') return $this->_tableNames[$dbName];$ret = join(', ', $this->_tableNames[$dbName]);return $ret;}
$result = $this->listTables($dbName);if (isEx($result)) {$result->stackTrace('in fetchTableNames()', __FILE__, __LINE__);return $result;}
$tableCount = $this->numRows($result);if (isEx($tableCount)) {$tableCount->stackTrace('in fetchTableNames()', __FILE__, __LINE__);return $tableCount;}
$ret = array();for ($i=0; $i < $tableCount; $i++) {$t = $this->tableName2($result, $i);if (isEx($t)) {$t->stackTrace('in fetchTableNames()', __FILE__, __LINE__);return $t;}
$ret[] = $t;}
$this->freeResult($result);$this->_tableNames[$dbName] = $ret; if ($format != 'vector') {return join(', ', $ret);} else {return $this->_tableNames[$dbName];}
}
function &fetchFieldNames($tblName, $dbName=NULL, $format='vector', $useCache=TRUE) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in fetchFieldNames().");$ret = NULL;if ($useCache && (is_array($this->_fieldNames)) && (isSet($this->_fieldNames[$dbName][$tblName]))) {if ($format == 'vector') return $this->_fieldNames[$dbName][$tblName];$ret = join(', ', $this->_fieldNames[$dbName][$tblName]);return $ret;}
$result = $this->listFields($tblName);if (isEx($result)) {$result->stackTrace('in fetchFieldNames()', __FILE__, __LINE__);return $result;}
$fieldCount = $this->numCols($result);if (isEx($fieldCount)) {$fieldCount->stackTrace('in fetchFieldNames()', __FILE__, __LINE__);return $fieldCount;}
$ret = array();for ($i=0; $i < $fieldCount; $i++) {$t = $this->fieldName($result, $i);if (isEx($t)) {$t->stackTrace('in fetchFieldNames()', __FILE__, __LINE__);return $t;}
$ret[] = $t;}
$this->freeResult($result);$this->_fieldNames[$dbName][$tblName] = $ret; if ($format != 'vector') {return join(', ', $ret);} else {return $this->_fieldNames[$dbName][$tblName];} 
}
function &getDbStructure($dbName=NULL, $useCache=TRUE) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in fetchFieldNames().");$ret = array();$tableList = &$this->fetchTableNames($dbName, $format='vector', $useCache);if (isEx($tableList)) {$tableList->stackTrace('in getDbStructur()', __FILE__, __LINE__);return $tableList;}
$count = sizeOf($tableList);for ($i=0; $i<$count; $i++) {$fieldList = &$this->fetchFieldNames($tableList[$i], $dbName, $format='vector', $useCache);if (isEx($fieldList)) {$fieldList->stackTrace('in getDbStructur()', __FILE__, __LINE__);return $fieldList;}
}
return $this->_fieldNames[$dbName];}
function fetchField($result, $offset=NULL) {if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');if (is_null($offset)) {$ret = @mysql_fetch_field($result);} else {$ret = @mysql_fetch_field($result, $offset);}
if (is_object($ret)) return $ret;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_fetch_field() failed in fetchField() with offset: '{$offset}'.");}
function fieldExists($lookups, $tableName, $dbName=NULL, $useCache=TRUE) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in fieldExists().");if (empty($lookups)) return TRUE;if (is_string($lookups)) $lookups = array($lookups);$lookups = array_unique($lookups);$availableFields = $this->fetchFieldNames($tableName, $dbName, 'vector', $useCache);if (isEx($availableFields)) {$availableFields->stackTrace('in fieldExists()', __FILE__, __LINE__);return $availableFields;}
$intersec = array_intersect($availableFields, $lookups);return (sizeOf($intersec) === sizeOf($lookups));}
function tableExists($lookups, $dbName=NULL, $useCache=TRUE) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in tableExists().");if (empty($lookups)) return TRUE;if (is_string($lookups)) $lookups = array($lookups);$availableTables = $this->fetchTableNames($dbName, 'vector', $useCache);if (isEx($availableTables)) {$availableTables->stackTrace('in tableExists()', __FILE__, __LINE__);return $availableTables;}
if ($this->_Bs_System->isWindows()) {$lookups = array_flip($lookups);$lookups = array_change_key_case($lookups);$lookups = array_flip($lookups);$availableTables = array_flip($availableTables);$availableTables = array_change_key_case($availableTables);$availableTables = array_flip($availableTables);} else {$lookups = array_unique($lookups);}
$intersec = array_intersect($availableTables, $lookups);return (sizeOf($intersec) === sizeOf($lookups));}
function databaseExists($lookups, $useCache=TRUE) {if (empty($lookups)) return TRUE;if (is_string($lookups)) $lookups = array($lookups);$availableDbs = $this->fetchDatabaseNames('vector', $useCache);if (isEx($availableDbs)) {$availableDbs->stackTrace('in databaseExists()', __FILE__, __LINE__);return $availableDbs;}
if ($this->_Bs_System->isWindows()) {$lookups = array_flip($lookups);$lookups = array_change_key_case($lookups);$lookups = array_flip($lookups);$availableDbs = array_flip($availableDbs);$availableDbs = array_change_key_case($availableDbs);$availableDbs = array_flip($availableDbs);} else {$lookups = array_unique($lookups);}
$intersec = array_intersect($availableDbs, $lookups);return (sizeOf($intersec) === sizeOf($lookups));}
function getTableStructure($tblName, $dbName=NULL) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in getTableStructure().");$keyDataList = &$this->getAll("SHOW KEYS FROM {$dbName}.{$tblName}", BS_DB_FETCHMODE_ASSOC);if (isEx($keyDataList)) {$keyDataList->stackTrace('in getTableStructure()', __FILE__, __LINE__);return $keyDataList;}
$fieldDef_2 = array();foreach ($keyDataList as $keyData) {$filedName = $keyData['Column_name'];if (isSet($fieldDef_2[$filedName])) continue;$keyData['fulltext'] = (bool)(stristr($keyData['Comment'], 'fulltext') !== FALSE);$fieldDef_2[$filedName] = $keyData;}
$colDataList = &$this->getAll("SHOW COLUMNS FROM {$dbName}.{$tblName}", BS_DB_FETCHMODE_ASSOC);if (isEx($colDataList)) {$colDataList->stackTrace('in getTableStructure()', __FILE__, __LINE__);return $colDataList;}
$ret = array();$lSize = sizeOf($colDataList);for ($i=0; $i<$lSize; $i++) {$fieldDef = $colDataList[$i];$t['type']     = 'unknown';  $t['length']   = 0;          $t['enum']     = FALSE;      preg_match('/(\w+)(?:\((.*)\)|)/', $fieldDef['Type'], $regs);if (isSet($regs[1]) AND strLen($regs[1]) >0) {$t['type'] = $regs[1];if (@strstr($regs[2], ',') !== FALSE) { if (in_array($regs[1], array('enum', 'set'))) {$enumArray = explode(',', $regs[2]);$t['length'] = sizeOf($enumArray);$t['enum']   = $enumArray;} else {$numericArray = explode(',', $regs[2]);$t['length'] = $numericArray[0] + $numericArray[1];}
} else {if (isSet($regs[2]) AND is_numeric($regs[2])) $t['length'] = (integer)$regs[2];}
}
static $byteLeng = array (
'date' => 3, 'time' => 3, 'datetime' => 8,
'tinyblob'=> 255, 'tinytext'=> 255,
'blob'=> 65535, 'text'=> 65535,
'mediumblob'=> 16777215,'mediumtext'=> 16777215,
'longblob'=> 4294967295,'longtext'=> 4294967295,
);if (isSet($byteLeng[$t['type']]))  $t['length'] = $byteLeng[$t['type']];if ($fieldDef['Default'] == 'NULL') {$t['default'] = NULL;} else {$t['default'] = $fieldDef['Default'];}
$t['notNull']        = (bool) (empty($fieldDef['Null']));$t['primaryKey']     = (bool) (strpos($fieldDef['Key'],   'PRI')      !== FALSE);$t['multipleKey']    = (bool) (strpos($fieldDef['Key'],   'MUL')      !== FALSE);if (isSet($fieldDef_2[$fieldDef['Field']])) {$fieldName = $fieldDef['Field'];$t['fulltext']       = $fieldDef_2[$fieldName]['fulltext'];} else {$t['fulltext'] = FALSE;}
$t['unique']         = (bool) (strpos($fieldDef['Extra'], 'unique')   !== FALSE);$t['unsigned']       = (bool) (strpos($fieldDef['Extra'], 'unsigned') !== FALSE);$t['zerofill']       = (bool) (strpos($fieldDef['Extra'], 'zerofill') !== FALSE);$t['binary']         = (bool) (strpos($fieldDef['Extra'], 'binary')   !== FALSE);$t['autoIncrement']  = (bool) (strpos($fieldDef['Extra'], 'auto_increment') !== FALSE);$tmpFieldName = $fieldDef['Field'];if ($t['primaryKey'] || ($tmpFieldName == 'ID')) {$t['foreignKey']     = FALSE;} elseif ((substr($tmpFieldName, -2) == 'ID') || (substr($tmpFieldName, -3) == 'IDs')) {if (substr($tmpFieldName, -3) == 'IDs') {$multiple = TRUE;$idStrLen = 3;} else {$multiple = FALSE;$idStrLen = 2;}
$pos = strpos($tmpFieldName, '__');if ($pos !== FALSE) $tmpFieldName = substr($tmpFieldName, $pos +2);$pos = strpos($tmpFieldName, '_');if ($pos !== FALSE) {$dbName    = substr($tmpFieldName, 0, $pos);$tableName = substr($tmpFieldName, $pos +1, -$idStrLen);} else {$dbName    = null;$tableName = substr($tmpFieldName, 0, -$idStrLen);}
if ($this->tableExists($tableName, $dbName, TRUE)) { $t['foreignKey'] = array(
'db'       => $dbName, 
'table'    => $tableName, 
'multiple' => $multiple, 
);} else {$t['foreignKey']     = FALSE;}
} else {$t['foreignKey']     = FALSE;}
$ret[$fieldDef['Field']] = $t;}
return $ret;}
function updateTableStructure($structure, $tblName, $dbName=NULL) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in updateTableStructure().");static $defaultFieldAttr  = array(
'type'         => 'varchar', 
'length'        => NULL,
'enum'          => FALSE,
'default'       => NULL,
'notNull'       => TRUE,
'primaryKey'    => FALSE,
'multipleKey'   => FALSE,
'fulltext'      => FALSE,
'unique'        => FALSE,
'unsigned'      => FALSE,
'zerofill'      => FALSE,
'binary'        => FALSE,
'autoIncrement' => FALSE,
'foreignKey'    => array(),
);$FULLTEXT_KEY = 'fullTextIndexName';$tblStructureChanged = FALSE;do {$fulltextArr = array();if (!$this->tableExists($tblName, $dbName, FALSE)) {$tblStructureChanged = TRUE;if (!empty($dbName)) {$dbString = "{$dbName}.{$tblName}";} else {$dbString = $tblName;}
$sql = "CREATE TABLE {$dbString} (";$sqlA = array();$primaryKey = '';$uniqueArr = $indexArr = array();foreach ($structure as $fieldName => $fieldDef) {$fieldDef = array_merge($defaultFieldAttr, $fieldDef);if ($fieldDef['primaryKey'] OR $fieldDef['unique'] OR $fieldDef['multipleKey'] OR $fieldDef['fulltext']) $fieldDef['notNull'] = TRUE;$sqlA[] = $this->_createAlterLine($fieldName, $fieldDef);if ($fieldDef['primaryKey']) {$primaryKey = $fieldName;} elseif ($fieldDef['unique']) {$uniqueArr[] = $fieldName;} elseif ($fieldDef['multipleKey']) {  $indexArr[] = $fieldName;}
if ($fieldDef['fulltext']) $fulltextArr[] = $fieldName;;} $sql .= join(', ', $sqlA);if (!empty($primaryKey))  {$sql .= ", PRIMARY KEY ($primaryKey)";} 
foreach($uniqueArr as $idxName) $sql .= ", UNIQUE $idxName ($idxName)";foreach($indexArr as $idxName)  $sql .= ", INDEX $idxName ($idxName)";if (!empty($fulltextArr)) $sql .= ", FULLTEXT {$FULLTEXT_KEY} (".join(', ', $fulltextArr).")";$sql .= ")";$dbState = $this->write($sql);if (isEx($dbState)) {$dbState->stackTrace('in updateTableStructure()', __FILE__, __LINE__);return $dbState;}
break; } 
$currentStruct = $this->getTableStructure($tblName, $dbName);$dbString = (isSet($dbName) && !empty($dbName)) ? $dbName.'.'.$tblName : $tblName;$previousField = '';foreach ($structure as $fieldName => $fieldDef) {$fieldDef = array_merge($defaultFieldAttr, $fieldDef);if (!isSet($currentStruct[$fieldName])) {$tblStructureChanged = TRUE;$sql  = "ALTER TABLE {$dbString} ADD ";$sql .= $this->_createAlterLine($fieldName, $fieldDef);$sql .= empty($previousField) ? " FIRST": " AFTER $previousField";$dbState = $this->write($sql);if (isEx($dbState)) {$dbState->stackTrace('in updateTableStructure()', __FILE__, __LINE__);return $dbState;}
}
$previousField = $fieldName;}
if ($tblStructureChanged) $currentStruct = $this->getTableStructure($tblName, $dbName);$fullTextIndexDroped = FALSE;$rebuildIndexList = array();foreach ($structure as $fieldName => $fieldDef) {$fieldDef = array_merge($defaultFieldAttr, $fieldDef);if ($fieldDef['primaryKey'] OR $fieldDef['unique'] OR $fieldDef['multipleKey'] OR $fieldDef['fulltext']) $fieldDef['notNull'] = TRUE;if ($fieldDef['fulltext']) $fulltextArr[] = $fieldName;;$orginalDef = $currentStruct[$fieldName];$removeIndex = $doConvert = FALSE;if (($orginalDef['type'] !== $fieldDef['type']) OR ($orginalDef['length'] <= $fieldDef['length'])) {if ($orginalDef['type'] === $fieldDef['type']) { if (strstr($orginalDef['type'], 'blob')!==FALSE OR strstr($orginalDef['type'], 'text')!==FALSE) {$doConvert = FALSE; } elseif (($orginalDef['length'] == $fieldDef['length'])  ) {$doConvert = FALSE; } else {$doConvert = TRUE;}
} else {$doConvert = TRUE;}
}
if ( ($orginalDef['primaryKey']  != $fieldDef['primaryKey'])
OR ($orginalDef['fulltext']    != $fieldDef['fulltext'])
OR ($orginalDef['multipleKey'] != $fieldDef['multipleKey'])
OR ($orginalDef['unique']      != $fieldDef['unique']) ) {$removeIndex = TRUE;}
if ($doConvert OR $removeIndex) {$tblStructureChanged = TRUE;if (!$fullTextIndexDroped) { $fullTextIndexDroped = TRUE;$sql  = "ALTER TABLE {$dbString} DROP INDEX $FULLTEXT_KEY";$this->write($sql); }
$rebuildIndexList[$fieldName] = $fieldDef; if ($orginalDef['primaryKey']) {$sql  = "ALTER TABLE {$dbString} DROP PRIMARY KEY";} else {$sql  = "ALTER TABLE {$dbString} DROP INDEX $fieldName";}
$this->write($sql); }
if ($doConvert) {$sql = "ALTER TABLE {$dbString} CHANGE $fieldName ";$sql .= $this->_createAlterLine($fieldName, $fieldDef);$this->write($sql); }
}  if ($fullTextIndexDroped) {if (!empty($fulltextArr)) {$sql = "ALTER TABLE {$dbString} ADD FULLTEXT {$FULLTEXT_KEY} (".join(', ', $fulltextArr).")";$this->write($sql); }
$fullTextIndexDroped = FALSE;}
foreach ($rebuildIndexList as $fieldName => $fieldDef) {$sql = FALSE;if ($fieldDef['primaryKey']) {$sql  = "ALTER TABLE {$dbString} DROP PRIMARY KEY, ADD PRIMARY KEY($fieldName)";} elseif ($fieldDef['multipleKey']) {  $sql  = "ALTER TABLE {$dbString} ADD INDEX($fieldName)";} elseif ($fieldDef['unique']) {$sql  = "ALTER TABLE {$dbString} ADD UNIQUE($fieldName)";}
if ($sql) {$dbState = $this->write($sql);if (isEx($dbState)) {$dbState->stackTrace('in updateTableStructure()', __FILE__, __LINE__);return $dbState;}
}
}
$tblStructureChanged = TRUE;} while(FALSE);return $tblStructureChanged;}
function _createAlterLine($fieldName, $fieldDef) {$ret = $fieldName;$allowDefault = TRUE;$defaultVal = isSet($fieldDef['default']) ? $fieldDef['default'] : NULL;$defaultLength = FALSE;switch (strToLower($fieldDef['type'])) {case 'char':
case 'varchar':
$defaultLength = isSet($fieldDef['length']) ? (int)$fieldDef['length'] : 255;break;case 'blob':
case 'text':
case 'mediumblob':
case 'mediumtext':
case 'longblob':
case 'longtext':
case 'tinyblob':
case 'tinytext':
$allowDefault = FALSE;break;case 'int':
case 'tinyint':
case 'smallint':
case 'mediumint':
case 'integer':
case 'bigint':
case 'float':
case 'double':
case 'double precision':
case 'real':
case 'decimal':
case 'numeric':
$defaultLength = null;break;default:
}
$ret .= ' '.$fieldDef['type'];if ($defaultLength)               $ret .= "({$defaultLength})";if (@$fieldDef['unsigned'])       $ret .= ' UNSIGNED';if (!empty($fieldDef['notNull'])) $ret .= ' NOT NULL';if ($allowDefault) {if (is_null($defaultVal) AND empty($fieldDef['notNull'])) {$ret .= ' DEFAULT NULL';} elseif (!is_null($defaultVal)) {$ret .= " DEFAULT '{$defaultVal}'";}
}
if (@$fieldDef['autoIncrement']) $ret .= ' AUTO_INCREMENT';return $ret;}
function getTableProperties($tblName, $dbName=NULL) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in getTableProperties().");$data = &$this->getAll("SHOW COLUMNS FROM {$dbName}.{$tblName}", BS_DB_FETCHMODE_ASSOC);if (isEx($data)) {$data->stackTrace('in getTableProperties()', __FILE__, __LINE__);return $data;}
$ret = array();while(list($k)   = each($data)) {$t['Type']     = 'unknown';  $t['Length']   = '';         preg_match('/(\w+)(?:\((.*)\)|)/', $data[$k]['Type'], $regs);if (isSet($regs[1]) AND strLen($regs[1]) >0) {$t['Type']     = $regs[1];if ($regs[1] === 'enum') {$t['Length'] = sizeOf(explode(',', $regs[2]));$t['Enum'] = $regs[2];} else {if (isSet($regs[2]) AND is_numeric($regs[2])) $t['Length'] = (integer)$regs[2];}
}
$t['Null']     = $data[$k]['Null'];$t['Key']      = $data[$k]['Key'];$t['Default']  = $data[$k]['Default'];$t['Extra']    = $data[$k]['Extra'];  $ret[$data[$k]['Field']] = $t;}
return $ret;}
function getTableLastmod($tblName, $dbName=NULL) {$data = $this->getTableInfo($tblName, $dbName);if (is_array($data) && isSet($data['Update_time'])) {return $data['Update_time'];} elseif (isEx($data)) {$data->stackTrace('was here in getTableLastmod().', __FILE__, __LINE__);return $data;} else {return $this->_mysqlRaiseError(BS_DB_ERROR, __FILE__, __LINE__, 'fatal', "was not able to read the lastmod datetime of the db: '{$dbName}' table: '{$tblName}'.");}
}
function getTablesLastmod($dbs) {$ret = FALSE;if (is_array($dbs) && !empty($dbs)) {reset($dbs);while (list($k) = each($dbs)) {if (isSet($dbs[$k]['db']) && !empty($dbs[$k]['db'])) {$datetime = $this->getTableLastmod($dbs[$k]['table'], $dbs[$k]['db']);} else {$datetime = $this->getTableLastmod($dbs[$k]['table']);}
if (isEx($datetime)) {$datetime->stackTrace('was here in getTablesLastmod().', __FILE__, __LINE__);return $datetime;}
if (($ret === FALSE) || ($datetime > $ret)) {$ret = $datetime;}
}
}
return $ret;}
function getTableInfo($tblName, $dbName=NULL) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in listFields().");$arr = $this->getAll("SHOW TABLE STATUS FROM {$dbName} LIKE '{$tblName}'");if (isEx($arr)) {$arr->stackTrace('in getTableInfo()', __FILE__, __LINE__);return $arr;}
return $arr[0];}
function getTableType($tblName, $dbName=NULL) {$tableInfo = $this->getTableInfo($tblName, $dbName);if (isEx($tableInfo)) {$tableInfo->stackTrace('in getTableType()', __FILE__, __LINE__);return $tableInfo;}
return $tableInfo['Type'];}
function tableHasTransactions($tblName, $dbName=NULL) {$tableType = getTableType($tblName, $dbName);if (isEx($tableType)) {$tableType->stackTrace('in tableHasTransactions()', __FILE__, __LINE__);return $tableType;}
switch ($tableType) {case 'BDB':
case 'INNOBASE':
case 'GEMINI':
return TRUE;break;default:
return FALSE;}
}
function serverSupportsTableType($tableType) {switch (strToUpper($tableType)) {case 'MYISAM':
case 'MERGE':
case 'HEAP':
return TRUE;break;case 'ISAM':
$support = $this->getIniVar('have_isam');break;case 'BDB':
$support = $this->getIniVar('have_bdb');break;case 'INNOBASE':
$support = $this->getIniVar('have_innobase');break;case 'GEMINI':
$support = $this->getIniVar('have_gemini');break;default:
return NULL;}
if (isEx($support)) {$support->stackTrace('in serverSupportsTableType()', __FILE__, __LINE__);return $support;}
if ($support == 'YES') return TRUE;return FALSE;}
function getOpenTables($dbName=NULL, $return='vector') {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in fetchTableNames().");$resArray = $this->getAll("SHOW OPEN TABLES", BS_DB_FETCHMODE_ORDERED);if (isEx($resArray)) {$resArray->stackTrace('in getOpenTables()', __FILE__, __LINE__);return $resArray;}
$ret = array();if ($return == 'extended') {while(list($k) = each($resArray)) {$cached = '?';$inUse  = '?';do { $t      = explode(', ', $resArray[$k][1]);if ((!is_array($t)) || (sizeOf($t) != 2)) break;$t2     = explode('=', $t[0]);if ((!is_array($t)) || (sizeOf($t) != 2)) break;$cached = $t2[1];$t2     = explode('=', $t[1]);if ((!is_array($t)) || (sizeOf($t) != 2)) break;$inUse  = $t2[1];} while (FALSE);$ret[] = array('name' => $resArray[$k][0], 'cached' => $cached, 'in_use' => $inUse);}
} else {while(list($k) = each($resArray)) {$ret[] = $resArray[$k][0];}
}
if ($return == 'string') return join(', ', $ret);return $ret;}
function getIniVar($key=NULL, $useCache=TRUE) {if (($useCache) && (is_array($this->_iniVars))) {if (is_null($key)) return $this->_iniVars;return (isSet($this->_iniVars[$key])) ? $this->_iniVars[$key] : NULL;}
$iniVars = $this->getAssoc("SHOW VARIABLES");if (isEx($iniVars)) {$iniVars->stackTrace('in getIniVar()', __FILE__, __LINE__);return $iniVars;}
$this->_iniVars = $iniVars; if (is_null($key)) return $iniVars;return (isSet($iniVars[$key])) ? $iniVars[$key] : NULL;}
function getStatusVar($key=NULL, $useCache=TRUE) {if (($useCache) && (is_array($this->_statusVars))) {if (is_null($key)) return $this->_statusVars;return (isSet($this->_statusVars[$key])) ? $this->_statusVars[$key] : NULL;}
$statusVars = $this->getAssoc("SHOW STATUS");if (isEx($statusVars)) {$statusVars->stackTrace('in getStatusVar()', __FILE__, __LINE__);return $statusVars;}
$this->_statusVars = $statusVars; if (is_null($key)) return $statusVars;return (isSet($statusVars[$key])) ? $statusVars[$key] : NULL;}
function getClientInfo() {return mysql_get_client_info();}
function getHostInfo() {return mysql_get_host_info($this->_connection);}
function getProtocolInfo() {return mysql_get_proto_info($this->_connection);}
function getServerInfo() {return mysql_get_server_info($this->_connection);}
function escapeString($query) {return mysql_escape_string($query);}
function setPointer($result, $absolutPos) {$ret = @mysql_data_seek($result, $absolutPos);if ($ret === TRUE) return TRUE;if (!is_resource($result)) return $this->_mysqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');if ((!is_int($absolutPos)) || ($absolutPos < 0)) return NULL;$t = $this->numRows($result);if ((is_int($t)) && ($t < $absolutPos)) return NULL;return $this->_mysqlRaiseError(NULL, __FILE__, __LINE__, '', "setPointer() failed with param num: '{$absolutPos}'");}
function isValidName($string) {if ($this->isReservedWord($string)) return FALSE;return TRUE;}
function isReservedWord($word) {$word = strToLower(trim($word));static $discourage = array('action', 'bit', 'date', 'enum', 'no', 'text', 'time', 'timestamp');if (in_array($word, $discourage)) {return 1;}
static $disallowed = array (
'action',           'add',              'aggregate',        'all',
'alter',            'after',            'and',              'as',
'asc',              'avg',              'avg_row_length',   'auto_increment',
'between',          'bigint',           'bit',              'binary',
'blob',             'bool',             'both',             'by',
'cascade',          'case',             'char',             'character',
'change',           'check',            'checksum',         'column',
'columns',          'comment',          'constraint',       'create',
'cross',            'current_date',     'current_time',     'current_timestamp',
'data',             'database',         'databases',        'date',
'datetime',         'day',              'day_hour',         'day_minute',
'day_second',       'dayofmonth',       'dayofweek',        'dayofyear',
'dec',              'decimal',          'default',          'delayed',
'delay_key_write',  'delete',           'desc',             'describe',
'distinct',         'distinctrow',      'double',           'drop',
'end',              'else',             'escape',           'escaped',
'enclosed',         'enum',             'explain',          'exists',
'fields',           'file',             'first',            'float',
'float4',           'float8',           'flush',            'foreign',
'from',             'for',              'full',             'function',
'global',           'grant',            'grants',           'group',
'having',           'heap',             'high_priority',    'hour',
'hour_minute',      'hour_second',      'hosts',            'identified',
'ignore',           'in',               'index',            'infile',
'inner',            'insert',           'insert_id',        'int',
'integer',          'interval',         'int1',             'int2',
'int3',             'int4',             'int8',             'into',
'if',               'is',               'isam',             'join',
'key',              'keys',             'kill',             'last_insert_id',
'leading',          'left',             'length',           'like',
'lines',            'limit',            'load',             'local',
'lock',             'logs',             'long',             'longblob',
'longtext',         'low_priority',     'max',              'max_rows',
'match',            'mediumblob',       'mediumtext',       'mediumint',
'middleint',        'min_rows',         'minute',           'minute_second',
'modify',           'month',            'monthname',        'myisam',
'natural',          'numeric',          'no',               'not',
'null',             'on',               'optimize',         'option',
'optionally',       'or',               'order',            'outer',
'outfile',          'pack_keys',        'partial',          'password',
'precision',        'primary',          'procedure',        'process',
'processlist',      'privileges',       'read',             'real',
'references',       'reload',           'regexp',           'rename',
'replace',          'restrict',         'returns',          'revoke',
'rlike',            'row',              'rows',             'second',
'select',           'set',              'show',             'shutdown',
'smallint',         'soname',           'sql_big_tables',   'sql_big_selects',
'sql_low_priority_updates','sql_log_off',      'sql_log_update',   'sql_select_limit',
'sql_small_result', 'sql_big_result',   'sql_warnings',     'straight_join',
'starting',         'status',           'string',           'table',
'tables',           'temporary',        'terminated',       'text',
'then',             'time',             'timestamp',        'tinyblob',
'tinytext',         'tinyint',          'trailing',         'to',
'type',             'use',              'using',            'unique',
'unlock',           'unsigned',         'update',           'usage',
'values',           'varchar',          'variables',        'varying',
'varbinary',        'with',             'write',            'when',
'where',            'year',             'year_month',       'zerofill'
);if (in_array($word, $disallowed)) {return 2;}
return 0;}
function nativeErrorCode() {$ret = mysql_errno($this->_connection);if (is_numeric($ret)) return (integer)$ret;return 0;}
function &nativeErrorMsg() {$ret = mysql_error($this->_connection);if (is_string($ret)) return $ret;return '';}
function &nativeError() {$code = mysql_errno($this->_connection);if ((is_numeric($code)) && ($code > 0)) {return $code . ':' . mysql_error($this->_connection);}
return '';}
function _mysqlRaiseError($errNo=NULL, $file='', $line='', $weight='', $msg='') {if (is_null($errNo)) {$mysqlErrno = (int)@mysql_errno($this->_connection);if ($mysqlErrno !== 0) {$mysqlError = @mysql_error($this->_connection);$bsErrno = $this->_dbErrorToBsError($mysqlErrno);if ($bsErrno === FALSE) {return $this->_raiseError(NULL, $mysqlErrno, $msg . $mysqlError, $file, $line, $weight);} else {return $this->_raiseError($bsErrno, $mysqlErrno, $msg . $mysqlError, $file, $line, $weight);}
}
return $this->_raiseError(NULL, NULL, $msg, $file, $line, $weight);}
return $this->_raiseError($errNo, NULL, $msg, $file, $line, $weight);}
function _dbErrorToBsError($dbError) {static $errorcode_map = array(
1004 => BS_DB_ERROR_CANNOT_CREATE,
1005 => BS_DB_ERROR_CANNOT_CREATE,
1006 => BS_DB_ERROR_CANNOT_CREATE,
1007 => BS_DB_ERROR_ALREADY_EXISTS,
1008 => BS_DB_ERROR_CANNOT_DROP,
1046 => BS_DB_ERROR_NODBSELECTED,
1050 => BS_DB_ERROR_ALREADY_EXISTS,
1051 => BS_DB_ERROR_NOSUCHTABLE,
1054 => BS_DB_ERROR_NOSUCHFIELD,
1062 => BS_DB_ERROR_ALREADY_EXISTS,
1064 => BS_DB_ERROR_SYNTAX,
1100 => BS_DB_ERROR_NOT_LOCKED,
1136 => BS_DB_ERROR_VALUE_COUNT_ON_ROW,
1146 => BS_DB_ERROR_NOSUCHTABLE,
);if (isset($errorcode_map[$dbError])) return $errorcode_map[$dbError];return FALSE;}
} ?>