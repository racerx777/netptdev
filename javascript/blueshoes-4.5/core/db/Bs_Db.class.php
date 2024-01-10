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
define('BS_DB_VERSION',      '4.5.$Revision: 1.6 $');function &getDbObject($dsn=null) {if (is_null($dsn)) {if (isSet($GLOBALS['bsDb']) && is_object($GLOBALS['bsDb'])) {return $GLOBALS['bsDb'];} else {if (!isSet($GLOBALS['APP']['db']['main'])) {return new Bs_Exception('no dsn info given in global APP[db][main]', __FILE__, __LINE__, 'db:' . BS_DB_ERROR_INVALID_DSN . ':' . 'invalid DSN', '');}
$dsn = $APP['db']['main'];}
}
switch ($dsn['type']) {case 'mysql':
require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_MySql.class.php');$db =& new Bs_MySql();break;case 'mssql':
require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_MsSql.class.php');$db =& new Bs_MsSql();break;default:
return new Bs_Exception("db type '{$dsn['type']}' not supported.", __FILE__, __LINE__, 'db:' . BS_DB_ERROR_UNSUPPORTED . ':' . 'not supported', '');}
$status = $db->connect($dsn, TRUE); if (isEx($status)) {$status->stackTrace('was here in getDbObject()', __FILE__, __LINE__);return $status;}
return $db;}
require_once($APP['path']['core'] . 'util/Bs_System.class.php');require_once($APP['path']['core'] . 'date/Bs_Date.class.php');define('BS_DB_ERROR',                       1);define('BS_DB_ERROR_SYNTAX',                2);define('BS_DB_ERROR_CONSTRAINT',            3);define('BS_DB_ERROR_NOT_FOUND',             4);define('BS_DB_ERROR_ALREADY_EXISTS',        5);define('BS_DB_ERROR_UNSUPPORTED',           6);define('BS_DB_ERROR_MISMATCH',              7);define('BS_DB_ERROR_INVALID',               8);define('BS_DB_ERROR_NOT_CAPABLE',           9);define('BS_DB_ERROR_TRUNCATED',            10);define('BS_DB_ERROR_INVALID_NUMBER',       11);define('BS_DB_ERROR_INVALID_DATE',         12);define('BS_DB_ERROR_DIVZERO',              13);define('BS_DB_ERROR_NODBSELECTED',         14);define('BS_DB_ERROR_CANNOT_CREATE',        15);define('BS_DB_ERROR_CANNOT_DELETE',        16);define('BS_DB_ERROR_CANNOT_DROP',          17);define('BS_DB_ERROR_NOSUCHTABLE',          18);define('BS_DB_ERROR_NOSUCHFIELD',          19);define('BS_DB_ERROR_NEED_MORE_DATA',       20);define('BS_DB_ERROR_NOT_LOCKED',           21);define('BS_DB_ERROR_VALUE_COUNT_ON_ROW',   22);define('BS_DB_ERROR_INVALID_DSN',          23);define('BS_DB_ERROR_CONNECT_FAILED',       24);define('BS_DB_ERROR_CANNOT_SELECT_DB',     25);define('BS_DB_ERROR_NOT_CONNECTED',        26);define('BS_DB_ERROR_INVALID_RS',           27);define('BS_DB_FETCHMODE_ORDERED', 1);define('BS_DB_FETCHMODE_ASSOC', 2);class Bs_Db extends Bs_Object {var $_connection = NULL;var $_connected = FALSE;var $_persistent = FALSE;var $_dsnInfo = NULL;var $_lastQuery = NULL;var $Bs_Date;var $_Bs_System = NULL;var $_features = NULL;var $format = array(
'date'       => "'Y-m-d'",       'datetime'   => "'Y-m-d H:i:s'", 'timestamp'  => "'YmdHis'",      );function Bs_Db() {parent::Bs_Object(); $this->_Bs_System = &$GLOBALS['Bs_System'];$this->Bs_Date    = &$GLOBALS['Bs_Date'];}
function dbPing() {return $this->_connected;}
function &getDsn($key=NULL) {if (!empty($key)) {return $this->_dsnInfo[$key];} else {return $this->_dsnInfo;}
}
function provides($feature) {if (isSet($this->_features[$feature])) return $this->_features[$feature];return NULL;}
function getNumRecords($query, $ignoreLimit=TRUE) {$query = preg_replace('/SELECT.*FROM /i', 'SELECT COUNT(*) FROM ', $query);$query = preg_replace('/LIMIT.*/i', '', $query);$ret = $this->getOne($query);if (isEx($ret)) {$ret->stackTrace('in getNumRecords()', __FILE__, __LINE__);return $ret;}
return $ret;}
function getFieldValue($theTable, $fieldToSearch, $valueToSearch, $fieldToGet, $caseSensitive=TRUE) {if ($caseSensitive) {$sqlQ = "FROM $theTable WHERE $fieldToSearch = '$valueToSearch' LIMIT 1";} else {$valueToSearch = strToLower($valueToSearch);$sqlQ = "FROM $theTable WHERE lcase($fieldToSearch) = '$valueToSearch' LIMIT 1";}
if (is_array($fieldToGet)) {$fieldToGet = join(', ', $fieldToGet);$sqlQ = "SELECT $fieldToGet " . $sqlQ;$ret = $this->getRow($sqlQ, $row=0, BS_DB_FETCHMODE_ASSOC);} else {$sqlQ = "SELECT $fieldToGet " . $sqlQ;$ret = $this->getOne($sqlQ);}
if (isEx($ret)) {$ret->stackTrace('in getFieldValue()', __FILE__, __LINE__);return $ret;}
return $ret;}
function read($query) {$result = $this->_query($query);if (isEx($result)) {$result->stackTrace('in read()', __FILE__, __LINE__);}
return $result;}
function &rsRead($query) {$result = $this->_query($query);if (isEx($result)) {$result->stackTrace('in rsRead()', __FILE__, __LINE__);return $result;}
return new Bs_ResultSet($this, $result);}
function countRead($query) {$ret = $this->_query($query);if (isEx($ret)) {$ret->stackTrace('in countRead()', __FILE__, __LINE__);return $ret;}
$numRows = $this->numRows($ret);$this->freeResult($ret);if (isEx($numRows)) {$numRows->stackTrace('in countRead()', __FILE__, __LINE__);}
return $numRows;}
function write($query) {$query = $this->_vendorSpecificWriteMod($query);$result = $this->_query($query);if (isEx($result)) {$result->stackTrace('in write()', __FILE__, __LINE__);return $result;}
return TRUE;}
function _vendorSpecificWriteMod($query) {return $query;}
function countWrite($query) {$ret = $this->_query($query);if (isEx($ret)) {$ret->stackTrace('in countWrite()', __FILE__, __LINE__);return $ret;}
return $this->affectedRows();}
function idWrite($query) {$ret = $this->_query($query);if (isEx($ret)) {$ret->stackTrace('in idWrite()', __FILE__, __LINE__);return $ret;}
return (int)$this->insertId();}
function &getOne($source, $row=0, $col=0) {if (is_resource($source)) {$res = $source;} else {$res = $this->read($source);if (isEx($res)) {$res->stackTrace('in getOne()', __FILE__, __LINE__);return $res;}
}
if (is_null($this->setPointer($res, $row))) return NULL; $fetchMode = (is_int($col)) ? BS_DB_FETCHMODE_ORDERED : BS_DB_FETCHMODE_ASSOC;$row = $this->fetchRow($res, $fetchMode);if (isEx($row)) {$row->stackTrace('in getOne()', __FILE__, __LINE__);return $row; }
if (is_string($source)) $this->freeResult($res);$ret = NULL;if (isSet($row[$col])) {$ret = &$row[$col];}
return $ret;}
function &getRow($source, $row=-1, $fetchMode=BS_DB_FETCHMODE_ASSOC) {if (is_resource($source)) {$res = $source;} else {$res = $this->read($source);if (isEx($res)) {$res->stackTrace('in getRow()', __FILE__, __LINE__);return $res;}
}
if ($row >= 0) {if (is_null($this->setPointer($res, $row))) return NULL; }
$dataArray = &$this->fetchRow($res, $fetchMode); if (isEx($dataArray)) {$dataArray->stackTrace('in getRow()', __FILE__, __LINE__);return $dataArray;}
if (is_string($source)) $this->freeResult($res);return $dataArray;}
function &getCol($source, $col=0, $startRow=0, $amount=-1) {if ($amount==0) return NULL;if (is_resource($source)) {$res = $source;} else {$res = $this->read($source);if (isEx($res)) {$res->stackTrace('in getCol()', __FILE__, __LINE__);return $res;}
}
$fetchMode = (is_int($col)) ? BS_DB_FETCHMODE_ORDERED : BS_DB_FETCHMODE_ASSOC;$ret = array();if (is_null($this->setPointer($res, $startRow))) return NULL; $i = 0;while ($row = $this->fetchRow($res, $fetchMode)) {$i++;if (isEx($row)) {$ret = $row;$ret->stackTrace('in getCol()', __FILE__, __LINE__);break;}
$ret[] = isSet($row[$col]) ? $row[$col] : NULL;if (($amount>0) AND ($i>=$amount)) break;}
if (is_string($source)) $this->freeResult($res);return $ret;}
function &getAssoc($source, $forceArray=FALSE, $hashInsteadOfVector=FALSE) {if (is_resource($source)) {$res = $source;} else {$res = $this->read($source);if (isEx($res)) {$res->stackTrace('in getAssoc()', __FILE__, __LINE__);return $res;}
}
$cols = $this->numCols($res);if (isEx($cols)) {$cols->stackTrace('in getAssoc()', __FILE__, __LINE__);return $cols;}
if ($cols < 2) return $this->_raiseError(BS_DB_ERROR_TRUNCATED);$results = array();if (($cols > 2) || ($forceArray)) {$fetchMode = ($hashInsteadOfVector) ? BS_DB_FETCHMODE_ASSOC : BS_DB_FETCHMODE_ORDERED;while ($row = $this->fetchRow($res, $fetchMode)) {if (isEx($row)) {$results = $row;$results->stackTrace('in getAssoc()', __FILE__, __LINE__);break;}
if ($hashInsteadOfVector) {$results[current($row)] = array_slice($row, 1);} else {$results[$row[0]] = array_slice($row, 1);}
}
} else {while ($row = $this->fetchRow($res, BS_DB_FETCHMODE_ORDERED)) {if (isEx($row)) {$results = $row;$results->stackTrace('in getAssoc()', __FILE__, __LINE__);break;}
if ($hashInsteadOfVector) {$results[current($row)] = $row[1];} else {$results[$row[0]] = $row[1];}
}
}
if (is_string($source)) $this->freeResult($res);return $results;}
function &getAssoc2($source, $forceArray=FALSE) {if (is_resource($source)) {$res = $source;} else {$res = $this->read($source);if (isEx($res)) {$res->stackTrace('in getAssoc2()', __FILE__, __LINE__);return $res;}
}
$results = array();$cols = $this->numCols($res);if (isEx($cols)) {$cols->stackTrace('in getAssoc2()', __FILE__, __LINE__);return $cols;}
while ($row = $this->fetchRow($res, BS_DB_FETCHMODE_ASSOC)) {if (isEx($row)) {$results = $row;$results->stackTrace('in getAssoc2()', __FILE__, __LINE__);break;}
if (($cols > 1) || ($forceArray)) {while(list($k, $v) = each($row)) {$results[$k][] = $v;}
} else {$results = $row;}
}
if (is_string($source)) $this->freeResult($res);return $results;}
function &getAssoc3($source) {if (is_resource($source)) {$res = $source;} else {$res = $this->read($source);if (isEx($res)) {$res->stackTrace('in getAssoc3()', __FILE__, __LINE__);return $res;}
}
$results = array();while ($row = $this->fetchRow($res, BS_DB_FETCHMODE_ORDERED)) {if (isEx($row)) {$results = $row;$results->stackTrace('in getAssoc3()', __FILE__, __LINE__);break;}
$i       = 0;$lastKey = 0;foreach ($row as $v) {if ($i == 0) {if (!isSet($results[$v])) $results[$v] = array();$lastKey = $v;} else {$results[$lastKey][] = $v;}
$i++;}
}
if (is_string($source)) $this->freeResult($res);return $results;}
function &getAll($source, $fetchMode=BS_DB_FETCHMODE_ASSOC) {if (is_resource($source)) {$res = $source;} else {$res = $this->read($source);if (isEx($res)) {$res->stackTrace('in getAll()', __FILE__, __LINE__);return $res;}
}
$recArray = array();do {$row = &$this->fetchRow($res, $fetchMode);if (isEx($row)) {$recArray = $row;$recArray->stackTrace('in getAll()', __FILE__, __LINE__);break;}
if ($row) $recArray[] = &$row;} while($row);if (is_string($source)) $this->freeResult($res);return $recArray;}
function assertExtension($name) {if (extension_loaded($name)) return TRUE;$dlext = ($this->_Bs_System->isWindows()) ? '.dll' : '.so';@dl($name . $dlext);return (extension_loaded($name));}
function toString() {$info  = get_class($this);$info .=  ": (dbtype={$this->_dsnInfo['type']}, dbsyntax={$this->_dsnInfo['syntax']}) [connected=";$info .= ($this->_connected) ? 'true' : 'false';$info .=  "] [persistent=";$info .= ($this->_persistent) ? 'true' : 'false';$info .=  "] [currentDb=" . $this->_dsnInfo['name'] . '] ';$info .=  "[lastQuery=" . $this->_lastQuery . '] ';return $info;}
function escapeString($query) {return addSlashes($query);}
function formatDateForDb($date) {$date = $this->Bs_Date->sqlDateToUnixTimestamp($date);return date($this->format['date'], $date);}
function formatDatetimeForDb($datetime) {$datetime = $this->Bs_Date->sqlDatetimeToUnixTimestamp($datetime);return date($this->format['datetime'], $datetime);}
function formatTimestampForDb($timestamp) {$datetime = $this->Bs_Date->sqlDatetimeToUnixTimestamp($datetime);return date($this->format['timestamp'], $datetime);}
function &quoteArgs($arr, $glue=', ') {$ret = '';$been = FALSE;while(list($key, $val) = each($arr)){if ($been) $ret .= $glue;if (is_bool($val)) {$val = (int)$val; } else {$val = $this->escapeString($val);}
$ret .= "{$key}='{$val}'";$been = TRUE;}
return $ret;}
function isManipulation($query) {$ret = FALSE;if (preg_match('/^\s*(INSERT|UPDATE|DELETE|REPLACE|ALTER|DROP|CREATE)\s+/i', $query, $match)) {$ret = strToUpper($match[0]);}
return $ret;}
function &_raiseError($code=BS_DB_ERROR, $nativeCode=NULL, $msg=NULL, $file='', $line='', $weight='') {if (is_null($code)) $code = BS_DB_ERROR; if (is_null($msg) OR (trim($msg)=='')) $msg = "Last query was: '{$this->_lastQuery}'. ";if (!is_null($nativeCode)) $msg .= " [nativecode={$nativeCode}]";return new Bs_Exception($msg, $file, $line, 'db:'.$code.':'.$this->getErrorMessage($code), $weight);}
function getErrorMessage($errCode) {if (!is_numeric($errCode)) return FALSE;if (!isSet($errorMessages)) {static $errorMessages;$errorMessages = array(
BS_DB_ERROR                    => 'unknown error',
BS_DB_ERROR_ALREADY_EXISTS     => 'already exists',
BS_DB_ERROR_CANNOT_CREATE      => 'can not create',
BS_DB_ERROR_CANNOT_DELETE      => 'can not delete',
BS_DB_ERROR_CANNOT_DROP        => 'can not drop',
BS_DB_ERROR_CANNOT_SELECT_DB   => 'can not select database', 
BS_DB_ERROR_CONSTRAINT         => 'constraint violation',
BS_DB_ERROR_DIVZERO            => 'division by zero',
BS_DB_ERROR_INVALID            => 'invalid',
BS_DB_ERROR_INVALID_DATE       => 'invalid date or time',
BS_DB_ERROR_INVALID_NUMBER     => 'invalid number',
BS_DB_ERROR_INVALID_RS         => 'invalid resource/result set',
BS_DB_ERROR_MISMATCH           => 'mismatch',
BS_DB_ERROR_NODBSELECTED       => 'no database selected',
BS_DB_ERROR_NOSUCHFIELD        => 'no such field',
BS_DB_ERROR_NOSUCHTABLE        => 'no such table',
BS_DB_ERROR_NOT_CAPABLE        => 'DB backend not capable',
BS_DB_ERROR_NOT_FOUND          => 'not found',
BS_DB_ERROR_NOT_LOCKED         => 'not locked',
BS_DB_ERROR_NOT_CONNECTED      => 'not connected', 
BS_DB_ERROR_SYNTAX             => 'syntax error',
BS_DB_ERROR_UNSUPPORTED        => 'not supported',
BS_DB_ERROR_VALUE_COUNT_ON_ROW => 'value count on row',
BS_DB_ERROR_INVALID_DSN        => 'invalid DSN',
BS_DB_ERROR_CONNECT_FAILED     => 'connect failed',
BS_DB_ERROR_TRUNCATED          => 'truncated',
BS_DB_ERROR_NEED_MORE_DATA     => 'need more data'
);}
if (isSet($errorMessages[$errCode])) {return $errorMessages[$errCode];} else {return FALSE;}
}
function parseDsn($dsn) {return false;}
function _query($query) {user_error('_query() is an abstract function! It must be overloaded.', E_USER_ERROR);}
function autoCommit($on=FALSE) {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function startTransaction() {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function commit() {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function rollback() {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function setPointer($result, $absolutPos) {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function fetchRow($result, $fetchMode=BS_DB_FETCHMODE_ASSOC) {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function freeResult($result) {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function numCols($result) {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function numRows($result) {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function affectedRows() {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function insertId() {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function nativeErrorCode() {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function nativeErrorMsg() {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function _disconnect($disconnectFunctionName) {if (!$this->_connected) return TRUE;  if ($this->_persistent) return FALSE; $ret = (bool)$disconnectFunctionName($this->_connection);$this->_connected  = FALSE;$this->_persistent = FALSE;$this->_connection = NULL;if ($ret) {return $ret;}
return NULL;}
}
?>