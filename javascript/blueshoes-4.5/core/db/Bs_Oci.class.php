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
define('BS_OCI_VERSION',      '4.5.$Revision: 1.2 $');require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_Db.class.php');require_once($GLOBALS['APP']['path']['pear'] . 'Date/Calc.php');class Bs_Oci extends Bs_Db {var $_databaseNames = NULL;var $_tableNames = NULL;var $_fieldNames = NULL;var $_iniVars = NULL;var $_statusVars = NULL;var $_currentlyOpenTransactionID = '';var $_lastStmt;var $_features = array(
'prepare'      => TRUE,
'pconnect'     => TRUE,
'transactions' => TRUE,
'innerSelects' => TRUE,
'insertId'     => FALSE,
'affectedRows' => TRUE,
'numRows'      => FALSE,
'numCols'      => TRUE,
'storedProc'   => TRUE
);var $format = array(
'date'       => "'Y-m-d'",          'datetime'   => "'Y-m-d, h:i:s A'", 'timestamp'  => "'Y-m-d, h:i:s A'", );function Bs_Oci() {parent::Bs_Db(); }
function connect($dsn, $persistent=TRUE) {if (isSet($this->_connected)) $this->disconnect(); $dsnInfo = (is_array($dsn)) ? $dsn : $this->parseDSN($dsn);if (!$dsnInfo) return $this->_ociRaiseError(BS_DB_ERROR_INVALID_DSN, __FILE__, __LINE__, 'fatal');static $defaultDsn = array('host'=>'localhost', 'port'=>'1521', 'syntax'=>'oci', 'type'=>'oci');$dsnInfo = array_merge($defaultDsn, $dsnInfo);if (!isSet($dsnInfo['tns'])) {return $this->_ociRaiseError(BS_DB_ERROR_INVALID_DSN, __FILE__, __LINE__, 'fatal');}
$tns    = &$dsnInfo['tns'];$user   = &$dsnInfo['user'];$pw     = &$dsnInfo['pass'];if (empty($dsnInfo['type'])) $dsnInfo['type'] = 'oci';$connectFunction = ($persistent) ? 'OCIPLogon' : 'OCINLogon'; $conn = @$connectFunction($user, $pw, $tns);if ($conn === FALSE) {$this->_connected  = FALSE;$e        = $this->_ociRaiseError(BS_DB_ERROR_INVALID_DSN, __FILE__, __LINE__, 'fatal', 'Connection failed. Maybe a wrong password?');} elseif (!is_resource($conn)) {$this->_connected  = FALSE;$e        = $this->_ociRaiseError(BS_DB_ERROR_CONNECT_FAILED, __FILE__, __LINE__, 'fatal', $conn);} else { $this->_connected  = TRUE;$this->_connection = $conn;$this->_dsnInfo    = $dsnInfo;$this->_persistent = $persistent;}
if (!$this->_connected) { $funcArgs = func_get_args();$e->setStackParam('functionArgs', $funcArgs);return $e;}
return (int)(substr(strstr(((string)$conn), '#'), 1));}
function disconnect() {return parent::_disconnect('OCILogOff');}
function selectDb($db) {if (!$this->_connected) return FALSE;return false;}
function _query($query) {$this->_lastQuery = $query;$this->_lastStmt  = null;if (!$this->_connected) return $this->_ociRaiseError(BS_DB_ERROR_NOT_CONNECTED, __FILE__, __LINE__, 'fatal');$stmt = @OCIParse($this->_connection, $query);$oerr = OCIError($stmt);if ($oerr['code']) {return $this->_ociRaiseError(BS_DB_ERROR_SYNTAX, __FILE__, __LINE__, 'fatal', "code: '{$oerr['code']}', msg: '{$oerr['message']}', " . 'OCIParse returned false for query: ' . $query);}
$this->_lastStmt = $stmt;$status = @OCIExecute($stmt);if (!$status) {$oerr = OCIError($stmt);return $this->_ociRaiseError(BS_DB_ERROR_SYNTAX, __FILE__, __LINE__, 'fatal', "code: '{$oerr['code']}', msg: '{$oerr['message']}', " . 'OCIExecute returned false for query: ' . $query);}
return $stmt;}
function &fetchRow($result, $fetchMode=BS_DB_FETCHMODE_ASSOC) {if (!is_resource($result)) return $this->_ociRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$row = array();if ($fetchMode == BS_DB_FETCHMODE_ASSOC) {@OCIFetchInto($result, &$row, OCI_ASSOC +OCI_RETURN_NULLS);} else {@OCIFetchInto($result, &$row, OCI_NUM +OCI_RETURN_NULLS);}
if (!$row) {$oerr = OCIError($result);if (!$oerr['code']) {return NULL; } else {return $this->_raiseError(NULL, $oerr['code'], $oerr['message'], __FILE__, __LINE__, 'fatal');}
}
return $row;}
function numCols($result) {if (!is_resource($result)) return $this->_ociRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$cols = @OCINumCols($result);if (!$cols) {return $this->_ociRaiseError(NULL, __FILE__, __LINE__);}
return $cols;}
function numRows($result) {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function affectedRows() {if ($this->isManipulation($this->_lastQuery)) {$ret = @OCIRowCount($this->_lastStmt);if ($ret >= 0) return $ret;return $this->_ociRaiseError(NULL, __FILE__, __LINE__, '', "OCIRowCount() failed in affectedRows(). last query was (hopefully): '{$this->_lastQuery}'");}
return FALSE;}
function insertId() {return $this->_raiseError(BS_DB_ERROR_NOT_CAPABLE);}
function freeResult($result) {@OCIFreeStatement($result); }
function getTableInfo($tblName, $dbName=NULL) {if (empty($dbName)) $dbName = $this->_dsnInfo['name'];if (empty($dbName)) return $this->_mysqlRaiseError(BS_DB_ERROR_NEED_MORE_DATA, __FILE__, __LINE__, '', "no db name available in listFields().");$arr = $this->getAll("SHOW TABLE STATUS FROM {$dbName} LIKE '{$tblName}'");if (isEx($arr)) {$arr->stackTrace('in getTableInfo()', __FILE__, __LINE__);return $arr;}
return $arr[0];}
function tableHasTransactions($tblName, $dbName=NULL) {return TRUE;}
function escapeString($query) {return str_replace("'", "''", $query);}
function formatDateForDb($date) {$date = $this->Bs_Date->sqlDateToArray($date);if ($date === FALSE) return "''";$date = $this->Bs_Date->sqlDateToUnixTimestamp($date);if (!$date || ($date == -1)) {return "''";}
return 'TO_DATE(' . date($this->format['date'], $date) . ",'YYYY-MM-DD')";}
function formatDatetimeForDb($datetime) {$datetime = $this->Bs_Date->sqlDatetimeToUnixTimestamp($datetime);return 'TO_DATE(' . date($this->format['datetime'], $datetime) . ",'RRRR-MM-DD, HH:MI:SS AM')";}
function formatTimestampForDb($timestamp) {$datetime = $this->Bs_Date->sqlDatetimeToUnixTimestamp($datetime);return 'TO_DATE(' . date($this->format['timestamp'], $datetime) . ",'RRRR-MM-DD, HH:MI:SS AM')";}
function _vendorSpecificWriteMod($query) {if (preg_match('/^\s*(INSERT|REPLACE)\s+/i', $query, $match)) { $writeType = strToUpper($match[0]);if (!preg_match('/VALUES/i', $query)) {$t = strToLower($query);$pos = strpos($query, ' set ');if ($pos) {$queryPartOne = substr($query, 0, $pos);}
}
}
return $query;}
function idWrite($query) {$query .= " returning ROWID into :rid";$this->_lastQuery = $query;$this->_lastStmt  = null;if (!$this->_connected) return $this->_ociRaiseError(BS_DB_ERROR_NOT_CONNECTED, __FILE__, __LINE__, 'fatal');$stmt = @OCIParse($this->_connection, $query);$oerr = OCIError($stmt);if ($oerr['code']) {return $this->_ociRaiseError(BS_DB_ERROR_SYNTAX, __FILE__, __LINE__, 'fatal', "code: '{$oerr['code']}', msg: '{$oerr['message']}', " . 'OCIParse returned false for query: ' . $query);}
$this->_lastStmt  = $stmt;$rowid = OCINewDescriptor($this->_connection, OCI_D_ROWID);    OCIBindByName($stmt, ":rid", &$rowid, -1, OCI_B_ROWID); $status = @OCIExecute($stmt);if (!$status) {$oerr = OCIError($stmt);return $this->_ociRaiseError(BS_DB_ERROR_SYNTAX, __FILE__, __LINE__, 'fatal', "code: '{$oerr['code']}', msg: '{$oerr['message']}', " . 'OCIExecute returned false for query: ' . $query);}
$stmt2 = OCIParse($this->_connection, "SELECT id FROM personen WHERE ROWID = :rid");OCIBindByName($stmt2, ":rid", &$rowid, -1, OCI_B_ROWID);$results2 = ociexecute($stmt2);ocifetch($stmt2);$erg = ociresult($stmt2, OCIColumnName($stmt2, 1));return (int)$erg;}
function isValidName($string) {if ($this->isReservedWord($string)) return FALSE;return TRUE;}
function isReservedWord($word) {$word = strToLower($word);$discourage = ' action bit date enum no text time timestamp ';$pos = strpos($discourage, ' ' . $word, ' ');if ($pos === false) {} else {return 1;}
static $disallowed = '  
action  add  aggregate  all  
alter  after  and  as  
ACCESS    
ADD                
ALL                
ALTER                
AND                
ANY                
AS                
ASC                
AUDIT    
BETWEEN                
BY                
CHAR                
CHECK                
CLUSTER    
COLUMN    
COMMENT    
COMPRESS    
CONNECT                
CREATE                
CURRENT                
DATE                
DECIMAL                
DEFAULT              
DELETE        
DESC        
DISTINCT        
DROP        
ELSE        
EXCLUSIVE  
EXISTS  
FILE  
FLOAT        
FOR        
FROM        
GRANT        
GROUP        
HAVING        
IDENTIFIED  
IMMEDIATE        
IN        
INCREMENT  
INDEX  
INITIAL  
INSERT        
INTEGER        
INTERSECT        
INTO        
IS        
LEVEL        
LIKE        
LOCK  
LONG  
MAXEXTENTS  
MINUS  
MLSLABEL  
MODE  
MODIFY  
NOAUDIT  
NOCOMPRESS  
NOT        
NOWAIT  
NULL        
NUMBER  
OF        
OFFLINE  
ON        
ONLINE  
OPTION        
OR        
ORDER        
PCTFREE  
PRIOR        
PRIVILEGES        
PUBLIC        
RAW  
RENAME  
RESOURCE  
REVOKE        
ROW  
ROWID  
ROWNUM  
ROWS        
SELECT        
SESSION        
SET        
SHARE  
SIZE        
SMALLINT        
START  
SUCCESSFUL  
SYNONYM  
SYSDATE  
TABLE        
THEN        
TO        
TRIGGER  
UID  
UNION        
UNIQUE        
UPDATE        
USER        
VALIDATE  
VALUES        
VARCHAR        
VARCHAR2  
VIEW        
WHENEVER        
WHERE  
WITH        
';$pos = strpos($disallowed, '  ' . $word . '  ');if ($pos === false) {} else {return 2;}
return 0;}
function nativeErrorCode() {$oerr = OCIError($this->_lastStmt);if ($oerr['code'] && is_numeric($ret)) return (integer)$oerr['code'];return 0;}
function &nativeErrorMsg() {$oerr = OCIError($this->_lastStmt);if ($oerr['code'] && is_string($oerr['message'])) return $oerr['message'];return '';}
function &nativeError() {$oerr = OCIError($this->_lastStmt);if ($oerr['code'] && is_numeric($ret)) {return $oerr['code'] . '.' . $oerr['message'];}
return '';}
function _ociRaiseError($errNo=NULL, $file='', $line='', $weight='', $msg='') {if (is_null($errNo)) {$rdbmsErrno = $this->nativeErrorCode();if ($rdbmsErrno !== 0) {$rdbmsError = $this->nativeErrorMsg();$bsErrno = $this->_dbErrorToBsError($rdbmsErrno);if ($bsErrno === FALSE) {return $this->_raiseError(NULL, $rdbmsErrno, $msg . $rdbmsError, $file, $line, $weight);} else {return $this->_raiseError($bsErrno, $rdbmsErrno, $msg . $rdbmsError, $file, $line, $weight);}
}
return $this->_raiseError(NULL, NULL, $msg, $file, $line, $weight);}
return $this->_raiseError($errNo, NULL, $msg, $file, $line, $weight);}
function _dbErrorToBsError($dbError) {if (isset($errorcode_map[$dbError])) return $errorcode_map[$dbError];return FALSE;}
} ?>