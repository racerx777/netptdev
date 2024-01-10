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
define('BS_ODBC_VERSION',      '4.5.$Revision: 1.2 $');require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_Db.class.php');class Bs_Odbc extends Bs_Db {var $_features = array(
'prepare'      => FALSE,
'pconnect'     => TRUE,
'transactions' => FALSE,
'innerSelects' => FALSE,
'insertId'     => FALSE, 'affectedRows' => FALSE, 'numRows'      => FALSE, 'numCols'      => TRUE,
'storedProc'   => FALSE
);var $format = array(
'date'       => "'Y-m-d'",       'datetime'   => "'Y-m-d H:i:s'", 'timestamp'  => "'YmdHis'",      );function Bs_Odbc() {parent::Bs_Db(); }
function connect($dsn, $persistent=TRUE) {if (isSet($this->_connected)) $this->disconnect(); $dsnInfo = (is_array($dsn)) ? $dsn : $this->parseDSN($dsn);if (!$dsnInfo) return $this->_odbcRaiseError(BS_DB_ERROR_INVALID_DSN, __FILE__, __LINE__, 'fatal');$connectFunction = ($persistent) ? 'odbc_pconnect' : 'odbc_connect';if ($dsnInfo['user'] && $dsnInfo['pass']) {$conn = @$connectFunction($dbHost, $dsnInfo['user'], $dsnInfo['pass']);} elseif ($dsnInfo['user']) {$conn = @$connectFunction($dbHost, $dsnInfo['user']);} else {$conn = @$connectFunction($dbHost);}
if ($conn === FALSE) {$this->_connected  = FALSE;$e        = $this->_odbcRaiseError(BS_DB_ERROR_INVALID_DSN, __FILE__, __LINE__, 'fatal', 'Connection failed. Maybe a wrong password?');} elseif (!is_resource($conn)) {$this->_connected  = FALSE;$e        = $this->_odbcRaiseError(BS_DB_ERROR_CONNECT_FAILED, __FILE__, __LINE__, 'fatal', $conn);} else { $this->_connected  = TRUE;$this->_connection = $conn;$this->_dsnInfo    = $dsnInfo;$this->_persistent = $persistent;}
if (!$this->_connected) { $funcArgs = func_get_args();$e->setStackParam('functionArgs', $funcArgs);return $e;}
return (int)(substr(strstr(((string)$conn), '#'), 1));}
function disconnect() {return parent::_disconnect('odbc_close');}
function _query($query) {$this->_lastQuery = $query;if (!$this->_connected) return $this->_odbcRaiseError(BS_DB_ERROR_NOT_CONNECTED, __FILE__, __LINE__, 'fatal');$ret = @odbc_exec($this->_connection, $query);if (!$ret) {return $this->_odbcRaiseError(NULL, __FILE__, __LINE__, 'fatal', "query was: '{$query}'");}
return $ret;}
function &fetchRow($result, $fetchMode=BS_DB_FETCHMODE_ASSOC) {if (!is_resource($result)) return $this->_odbcRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$cols = odbc_fetch_into($result, &$row);if ($cols == 0) {return $this->raiseError();}
if ($fetchmode == BS_DB_FETCHMODE_ORDERED) {return $row;} elseif ($fetchmode == BS_DB_FETCHMODE_ASSOC) {for ($i = 0; $i < count($row); $i++) {$colName = odbc_field_name($result, $i+1);$a[$colName] = $row[$i];}
return $a;} else {return $this->raiseError();}
}
function numCols($result) {if (!is_resource($result)) return $this->_odbcRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$cols = @odbc_num_fields($result);if (!$cols) {return $this->_odbcRaiseError(NULL, __FILE__, __LINE__);}
return $cols;}
function freeResult($result) {if (is_resource($result)) @odbc_free_result($result);}
function fieldName($result, $offset) {if (!is_resource($result)) return $this->_odbcRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @odbc_field_name($result, $offset);if (is_string($ret)) return $ret;return $this->_odbcRaiseError(NULL, __FILE__, __LINE__, '', "odbc_field_name() failed in fieldName() for field: '{$offset}'");}
function fieldLen($result, $offset) {if (!is_resource($result)) return $this->_odbcRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @odbc_field_len($result, $offset);if (is_int($ret)) return $ret;return $this->_odbcRaiseError(NULL, __FILE__, __LINE__, '', "odbc_field_len() failed in fieldLen() for field: '{$offset}'");}
function fieldType($result, $offset=1) {if (!is_resource($result)) return $this->_odbcRaiseError(BS_DB_ERROR_INVALID_RS, __FILE__, __LINE__, 'fatal');$ret = @odbc_field_type($result, $offset);if (is_string($ret)) return $ret;return $this->_odbcRaiseError(NULL, __FILE__, __LINE__, '', "odbc_field_type() failed in fieldType() for field: '{$offset}'");}
function listTables($dbName=NULL) {return NULL;}
function escapeString($query) {return str_replace("'", "''", $query);}
function nativeErrorCode() {$ret = odbc_error($this->_connection);if (is_numeric($ret)) return (integer)$ret;return 0;}
function nativeErrorMsg() {$ret = odbc_errormsg($this->_connection);if (is_string($ret)) return $ret;return '';}
function nativeError() {$code = odbc_error($this->_connection);if ((is_numeric($code)) && ($code > 0)) {return $code . ':' . odbc_errormsg($this->_connection);}
return '';}
function _odbcRaiseError($errNo=NULL, $file='', $line='', $weight='', $msg='') {if (is_null($errNo)) {$odbcErrno = (int)@odbc_error($this->_connection);if ($odbcErrno !== 0) {$odbcError = @odbc_errormsg($this->_connection);$bsErrno = $this->_dbErrorToBsError($odbcErrno);if ($bsErrno === FALSE) {return $this->_raiseError(NULL, $odbcErrno, $msg . $odbcError, $file, $line, $weight);} else {return $this->_raiseError($bsErrno, $odbcErrno, $msg . $odbcError, $file, $line, $weight);}
}
return $this->_raiseError(NULL, NULL, $msg, $file, $line, $weight);}
return $this->_raiseError($errNo, NULL, $msg, $file, $line, $weight);}
function _dbErrorToBsError($dbError) {return FALSE;}
} ?>