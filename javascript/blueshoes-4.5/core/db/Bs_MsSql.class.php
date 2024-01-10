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
define('BS_MSSQL_VERSION',      '4.5.$Revision: 1.1.1.1 $');require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_Db.class.php');class Bs_MsSql extends Bs_Db {var $_databaseNames = NULL;var $_tableNames = NULL;var $_fieldNames = NULL;var $_iniVars = NULL;var $_statusVars = NULL;var $_currentlyOpenTransactionID = '';function Bs_MsSql() {parent::Bs_Db(); $this->_features = array(
'prepare'      => FALSE, 'pconnect'     => TRUE,
'transactions' => TRUE,
'innerSelects' => TRUE,
'insertId'     => TRUE,
'affectedRows' => TRUE,
'numRows'      => TRUE,
'numCols'      => TRUE,
'storedProc'   => TRUE
);}
function autoCommit($on=FALSE) {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function startTransaction($transactionId='') {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE); if ((!empty($transactionId)) AND empty($this->_currentlyOpenTransactionID)) {$this->_currentlyOpenTransactionID = $transactionId;}
}
function commit($transactionId='') {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE); if (empty($this->_currentlyOpenTransactionID)) {} elseif ($this->_currentlyOpenTransactionID === $transactionId) {$this->_currentlyOpenTransactionID = '';} else {}
}
function rollback($transactionId='') {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE); if (empty($this->_currentlyOpenTransactionID)) {} elseif ($this->_currentlyOpenTransactionID === $transactionId) {$this->_currentlyOpenTransactionID = '';} else {}
}
function connect($dsn, $persistent=TRUE) {if (isSet($this->_connected)) $this->disconnect(); $dsnInfo = (is_array($dsn)) ? $dsn : $this->parseDSN($dsn);if (!$dsnInfo) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_DSN, __FILE__, __LINE__, 'fatal');static $defaultDsn = array('host'=>'localhost', 'port'=>'1433', 'syntax'=>'mssql', 'type'=>'mssql');$dsnInfo = array_merge($defaultDsn, $dsnInfo);$dbHost = ($dsnInfo['host']) ? $dsnInfo['host'] : 'localhost';$user   = &$dsnInfo['user'];$pw     = &$dsnInfo['pass'];if (empty($dsnInfo['type'])) $dsnInfo['type'] = 'mssql';$this->assertExtension($dsnInfo['type']);$connectFunction = ($persistent) ? 'mssql_pconnect' : 'mssql_connect';if (($dbHost) && ($user) && ($pw)) {$conn = @$connectFunction($dbHost, $user, $pw);} elseif (($dbHost) && ($user)) {$conn = @$connectFunction($dbHost, $user);} elseif ($dbHost) {$conn = @$connectFunction($dbHost);} else {$conn = FALSE;}
if ($conn === FALSE) {$this->_connected  = FALSE;$e        = $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_DSN, __FILE__, __LINE__, 'fatal', 'Connection failed. Maybe a wrong password?');} elseif (!is_resource($conn)) {$this->_connected  = FALSE;$e        = $this->_mssqlRaiseError(BS_DB_ERROR_CONNECT_FAILED, __FILE__, __LINE__, 'fatal', $conn);} else { $this->_connected  = TRUE;$this->_connection = $conn;$this->_dsnInfo    = $dsnInfo;$this->_persistent = $persistent;}
if (!$this->_connected) { $funcArgs = func_get_args();$e->setStackParam('functionArgs', $funcArgs);return $e;}
if (!empty($dsnInfo['name'])) {if (!mssql_select_db($dsnInfo['name'], $conn)) {return $this->_mssqlRaiseError(BS_DB_ERROR_CANNOT_SELECT_DB, __FILE__, __LINE__, 'fatal', "desired db was: '{$dsnInfo['name']}'");}
}
return (int)(substr(strstr(((string)$conn), '#'), 1));}
function disconnect() {if (!$this->_connected) return TRUE;  if ($this->_persistent) return FALSE; $ret = (bool)mssql_close($this->_connection);$this->_connected  = FALSE;$this->_persistent = FALSE;$this->_connection = NULL;if ($ret) {return $ret;}
return NULL;}
function selectDb($db) {if (!$this->_connected) return FALSE;if (mssql_select_db($db, $this->_connection)) {$this->_dsnInfo['name'] = $db;return TRUE;} else {return FALSE;}
}
function _query($query) {$this->_lastQuery = $query;if (!$this->_connected) return $this->_mssqlRaiseError(BS_DB_ERROR_NOT_CONNECTED, __FILE__, __LINE__, 'fatal');$ret = @mssql_query($query, $this->_connection);if (!$ret) {return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, 'fatal', "query was: '{$query}'");}
return $ret;}
function &fetchRow($result, $fetchMode=BS_DB_FETCHMODE_ASSOC) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');if ($fetchMode == BS_DB_FETCHMODE_ASSOC) {$row = @mysqli_fetch_assoc($result);} else {$row = @mssql_fetch_row($result);}
if (!$row) {$errNo = $this->nativeErrorCode();if ($errNo === 0) return NULL; $errMsg = $this->nativeErrorMsg();return $this->_raiseError(NULL, $errNo, $errMsg, __FILE__, __LINE__, 'fatal');}
return $row;}
function numCols($result) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$cols = @mssql_num_fields($result);if (!$cols) {return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__);}
return $cols;}
function numRows($result) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$rows = @mssql_num_rows($result);if (is_null($rows)) {return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mssql_num_rows() failed in numRows()");}
return $rows;}
function affectedRows() {if ($this->isManipulation($this->_lastQuery)) {$ret = mssql_rows_affected($this->_connection);if ($ret >= 0) return $ret;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mssql_affected_rows() failed in affectedRows(). last query was (hopefully): '{$this->_lastQuery}'");}
return FALSE;}
function insertId() {$id = $this->getOne("SELECT @@IDENTITY AS 'Identity'");if (is_numeric($id)) return (int)$id;return 0;}
function freeResult($result) {if (is_resource($result)) @mssql_free_result($result);}
function fieldName($result, $offset) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mssql_field_name($result, $offset);if (is_string($ret)) return $ret;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mssql_field_name() failed in fieldName() for field: '{$offset}'");}
function tableName($result, $offset) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_field_table($result, $offset);if (is_string($ret)) return $ret;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_field_table() failed in tableName() for table: '{$offset}'");}
function tableName2($result, $offset) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_tablename($result, $offset);if (is_string($ret)) return $ret;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_tablename() failed in tableName2() for table: '{$offset}'");}
function databaseName($result, $offset) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_db_name($result, $offset);if (is_string($ret)) return $ret;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_db_name() failed in databaseName() for table: '{$offset}'");}
function fieldLen($result, $offset) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_field_len($result, $offset);if (is_int($ret)) return $ret;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_field_len() failed in fieldLen() for field: '{$offset}'");}
function fieldFlags($result, $offset, $format='string') {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mysql_field_flags($result, $offset);if (is_string($ret)) {switch ($format) {case 'vector':
return explode(' ', $ret);break;case 'hash':
$t = array('not_null'       => FALSE, 
'auto_increment' => FALSE, 
'primary_key'    => FALSE, 
'unique_key'     => FALSE, 
'multiple_key'   => FALSE, 
'unsigned'       => FALSE, 
'zerofill'       => FALSE, 
'binary'         => FALSE, 
'blob'           => FALSE, 'enum'           => FALSE, 'timestamp'      => FALSE  );$t2 = explode(' ', $ret);while(list($k) = each($t2)) {$t[$t2[$k]] = TRUE;}
return $t;break;default: return $ret;}
}
return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_field_flags() failed in fieldFlags() for field: '{$offset}'");}
function hasFieldFlag($result, $offset, $flag) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$t = $this->fieldFlags($result, $offset, 'hash');return (isSet($t[$flag])) ? $t[$flag] : NULL;}
function fieldType($result, $offset=0) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @mssql_field_type($result, $offset);if (is_string($ret)) return $ret;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mssql_field_type() failed in fieldType() for field: '{$offset}'");}
function listDatabases() {$ret = $this->_query("SELECT CATALOG_NAME FROM INFORMATION_SCHEMA.SCHEMATA ORDER BY CATALOG_NAME");if (is_resource($ret)) return $ret;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "query on 'INFORMATION_SCHEMA.SCHEMATA' failed in listDatabases().");}
function listTables($dbName=NULL) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mssqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in listTables().");$ret = $this->_query("SELECT TABLE_NAME FROM {$dbName}.INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");if (is_resource($ret)) return $ret;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "query on 'INFORMATION_SCHEMA.TABLES' failed in listTables() with dbName: '{$dbName}'.");}
function listFields($tableName, $dbName=NULL) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mssqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in listFields().");$ret = @mysql_list_fields($dbName, $tableName, $this->_connection);if (is_resource($ret)) return $ret;$err = '';if ($ret<0) {GLOBAL $phperrmsg;$err = " phpErrMsg: '{$phperrmsg}'";} 
return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mysql_list_fields() failed in listFields() with dbName: '{$dbName}' and tableName: '{$tableName}'.{$err}");}
function &fetchDatabaseNames($format='vector', $useCache=TRUE) {$ret = NULL;if (($useCache) && (is_array($this->_databaseNames))) {if ($format == 'vector') return $this->_databaseNames;$ret = join(', ', $this->_databaseNames);return $ret;}
$result = $this->listDatabases();if (isEx($result)) {$result->stackTrace('in fetchDatabaseNames()', __FILE__, __LINE__);return $result;}
$ret = $this->getCol($result);$this->_databaseNames = $ret; if ($format != 'vector') {return join(', ', $ret);} else {return $this->_databaseNames;}
}
function &fetchTableNames($dbName=NULL, $format='vector', $useCache=TRUE) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mssqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in fetchTableNames().");$ret = NULL;if (($useCache) && (is_array($this->_tableNames)) && (isSet($this->_tableNames[$dbName]))) {if ($format == 'vector') return $this->_tableNames[$dbName];$ret = join(', ', $this->_tableNames[$dbName]);return $ret;}
$result = $this->listTables($dbName);if (isEx($result)) {$result->stackTrace('in fetchTableNames()', __FILE__, __LINE__);return $result;}
$ret = $this->getCol($result);$this->_tableNames[$dbName] = $ret; if ($format != 'vector') {return join(', ', $ret);} else {return $this->_tableNames[$dbName];} 
}
function &fetchFieldNames($tblName, $dbName=NULL, $format='vector', $useCache=TRUE) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mssqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in fetchFieldNames().");$ret = NULL;if ($useCache && (is_array($this->_fieldNames)) && (isSet($this->_fieldNames[$dbName][$tblName]))) {if ($format == 'vector') return $this->_fieldNames[$dbName][$tblName];$ret = join(', ', $this->_fieldNames[$dbName][$tblName]);return $ret;}
$result = $this->listFields($tblName);if (isEx($result)) {$result->stackTrace('in fetchFieldNames()', __FILE__, __LINE__);return $result;}
$fieldCount = $this->numCols($result);if (isEx($fieldCount)) {$fieldCount->stackTrace('in fetchFieldNames()', __FILE__, __LINE__);return $fieldCount;}
$ret = array();for ($i=0; $i < $fieldCount; $i++) {$t = $this->fieldName($result, $i);if (isEx($t)) {$t->stackTrace('in fetchFieldNames()', __FILE__, __LINE__);return $t;}
$ret[] = $t;}
$this->freeResult($result);$this->_fieldNames[$dbName][$tblName] = $ret; if ($format != 'vector') {return join(', ', $ret);} else {return $this->_fieldNames[$dbName][$tblName];} 
}
function &getDbStructure($dbName=NULL, $useCache=TRUE) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mssqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in fetchFieldNames().");$ret = array();$tableList = &$this->fetchTableNames($dbName, $format='vector', $useCache);if (isEx($tableList)) {$tableList->stackTrace('in getDbStructur()', __FILE__, __LINE__);return $tableList;}
$count = sizeOf($tableList);for ($i=0; $i<$count; $i++) {$fieldList = &$this->fetchFieldNames($tableList[$i], $dbName, $format='vector', $useCache);if (isEx($fieldList)) {$fieldList->stackTrace('in getDbStructur()', __FILE__, __LINE__);return $fieldList;}
}
return $this->_fieldNames[$dbName];}
function fetchField($result, $offset=NULL) {if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');if (is_null($offset)) {$ret = @mssql_fetch_field($result);} else {$ret = @mssql_fetch_field($result, $offset);}
if (is_object($ret)) return $ret;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "mssql_fetch_field() failed in fetchField() with offset: '{$offset}'.");}
function fieldExists($fieldName, $tableName, $dbName=NULL, $useCache=TRUE) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mssqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in fieldExists().");$fieldArray = $this->fetchFieldNames($tableName, $dbName, 'vector', $useCache);if (isEx($fieldArray)) {$fieldArray->stackTrace('in fieldExists()', __FILE__, __LINE__);return $fieldArray;}
if (is_string($fieldName)) {return in_array($fieldName, $fieldArray);} elseif (is_array($fieldName)) {reset($fieldName);while(list($k) = each($fieldName)) {if (!(in_array($fieldName[$k], $fieldArray))) return FALSE;}
return TRUE;} else {return FALSE;}
}
function tableExists($tableName, $dbName=NULL, $useCache=TRUE) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mssqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in tableExists().");if ($this->_Bs_System->isWindows()) {$tblString = $this->fetchTableNames($dbName, 'string', $useCache);if (isEx($tblString)) {$tblString->stackTrace('in tableExists()', __FILE__, __LINE__);return $tblString;}
if (is_array($tableName)) {reset($tableName);while(list($k) = each($tableName)) {$tableName[$k] = strToLower($tableName[$k]);}
} else {$tableName = strToLower($tableName);}
$tblArray  = explode(', ', strToLower($tblString));} else {$tblArray = $this->fetchTableNames($dbName, 'vector', $useCache);if (isEx($tblArray)) {$tblArray->stackTrace('in tableExists()', __FILE__, __LINE__);return $tblArray;}
}
if (is_string($tableName)) {return in_array($tableName, $tblArray);} elseif (is_array($tableName)) {reset($tableName);while(list($k) = each($tableName)) {if (!(in_array($tableName[$k], $tblArray))) return FALSE;}
return TRUE;} else {return FALSE;}
}
function databaseExists($dbName, $useCache=TRUE) {if ($this->_Bs_System->isWindows()) {$dbString = $this->fetchDatabaseNames('string', $useCache);if (isEx($dbString)) {$dbString->stackTrace('in databaseExists()', __FILE__, __LINE__);return $dbString;}
if (is_array($dbName)) {reset($dbName);while(list($k) = each($dbName)) {$dbName[$k] = strToLower($dbName[$k]);}
} else {$dbName = strToLower($dbName);}
$dbArray  = explode(', ', strToLower($dbString));} else {$dbArray = $this->fetchDatabaseNames('vector', $useCache);if (isEx($dbArray)) {$dbArray->stackTrace('in databaseExists()', __FILE__, __LINE__);return $dbArray;}
}
if (is_string($dbName)) {return in_array($dbName, $dbArray);} elseif (is_array($dbName)) {reset($dbName);while(list($k) = each($dbName)) {if (!(in_array($dbName[$k], $dbArray))) return FALSE;}
return TRUE;} else {return FALSE;}
}
function getTableInfo($tblName, $dbName=NULL) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mssqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in listFields().");$arr = $this->getAssoc2("SHOW TABLE STATUS FROM {$dbName} LIKE '{$tblName}'");if (isEx($arr)) {$arr->stackTrace('in getTableInfo()', __FILE__, __LINE__);return $arr;}
return $arr;}
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
function getOpenTables($dbName=NULL, $return='vector') {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mssqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in fetchTableNames().");$resArray = $this->getAll("SHOW OPEN TABLES", BS_DB_FETCHMODE_ORDERED);if (isEx($resArray)) {$resArray->stackTrace('in getOpenTables()', __FILE__, __LINE__);return $resArray;}
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
function escapeString($query) {$query = str_replace("'", "''", $query);$query = str_replace('"', '""', $query);return $query;}
function setPointer($result, $absolutPos) {$ret = @mssql_data_seek($result, $absolutPos);if ($ret === TRUE) return TRUE;if (!is_resource($result)) return $this->_mssqlRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');if ((!is_int($absolutPos)) || ($absolutPos < 0)) return NULL;$t = $this->numRows($result);if ((is_int($t)) && ($t < $absolutPos)) return NULL;return $this->_mssqlRaiseError(NULL, __FILE__, __LINE__, '', "setPointer() failed with param num: '{$absolutPos}'");}
function isValidName($string) {if ($this->isReservedWord($string)) return FALSE;return TRUE;}
function isReservedWord($word) {$word = strToLower($word);$discourage = ' action bit date enum no text time timestamp ';$pos = strpos($discourage, ' ' . $word, ' ');if ($pos === false) {} else {return 1;}
static $disallowed = '  
action  add  aggregate  all  
alter  after  and  as  
asc  avg  avg_row_length  auto_increment  
between  bigint  bit  binary  
blob  bool  both  by  
cascade  case  char  character  
change  check  checksum  column  
columns  comment  constraint  create  
cross  current_date  current_time  current_timestamp  
data  database  databases  date  
datetime  day  day_hour  day_minute  
day_second  dayofmonth  dayofweek  dayofyear  
dec  decimal  default  delayed  
delay_key_write  delete  desc  describe  
distinct  distinctrow  double  drop  
end  else  escape  escaped  
enclosed  enum  explain  exists  
fields  file  first  float  
float4  float8  flush  foreign  
from  for  full  function  
global  grant  grants  group  
having  heap  high_priority  hour  
hour_minute  hour_second  hosts  identified  
ignore  in  index  infile  
inner  insert  insert_id  int  
integer  interval  int1  int2  
int3  int4  int8  into  
if  is  isam  join  
key  keys  kill  last_insert_id  
leading  left  length  like  
lines  limit  load  local  
lock  logs  long  longblob  
longtext  low_priority  max  max_rows  
match  mediumblob  mediumtext  mediumint  
middleint  min_rows  minute  minute_second  
modify  month  monthname  myisam  
natural  numeric  no  not  
null  on  optimize  option  
optionally  or  order  outer  
outfile  pack_keys  partial  password  
precision  primary  procedure  process  
processlist  privileges  read  real  
references  reload  regexp  rename  
replace  restrict  returns  revoke  
rlike  row  rows  second  
select  set  show  shutdown  
smallint  soname  sql_big_tables  sql_big_selects  
sql_low_priority_updates  sql_log_off  sql_log_update  sql_select_limit  
sql_small_result  sql_big_result  sql_warnings  straight_join  
starting  status  string  table  
tables  temporary  terminated  text  
then  time  timestamp  tinyblob  
tinytext  tinyint  trailing  to  
type  use  using  unique  
unlock  unsigned  update  usage  
values  varchar  variables  varying  
varbinary  with  write  when  
where  year  year_month  zerofill  ';$pos = strpos($disallowed, '  ' . $word . '  ');if ($pos === false) {} else {return 2;}
return 0;}
function nativeErrorCode() {$errCode = $this->getOne('select @@ERROR as ErrorCode');if (is_int($errCode)) return $errCode;return -1;}
function nativeErrorMsg() {$ret = mssql_get_last_message();if (is_string($ret)) return $ret;return '';}
function nativeError() {$code = $this->nativeErrorCode();if ((is_numeric($code)) && ($code !== 0)) {return $code . ':' . $this->nativeErrorMsg($this->_connection);}
return '';}
function _mssqlRaiseError($errNo=NULL, $file='', $line='', $weight='', $msg='') {if (is_null($errNo)) {$mssqlErrno = $this->nativeErrorCode();if ($mssqlErrno !== 0) { $mssqlError = $this->nativeErrorMsg();$bsErrno = $this->_dbErrorToBsError($mssqlErrno);if ($bsErrno === FALSE) {return $this->_raiseError(NULL, $mssqlErrno, $msg . $mssqlError, $file, $line, $weight);} else {return $this->_raiseError($bsErrno, $mssqlErrno, $msg . $mssqlError, $file, $line, $weight);}
}
return $this->_raiseError(NULL, NULL, $msg, $file, $line, $weight);}
return $this->_raiseError($errNo, NULL, $msg, $file, $line, $weight);}
function _dbErrorToBsError($dbError) {static $errorcode_map = array(
102  => BS_DB_ERROR_SYNTAX,         2714 => BS_DB_ERROR_ALREADY_EXISTS, );if (isset($errorcode_map[$dbError])) return $errorcode_map[$dbError];return FALSE;}
} ?>