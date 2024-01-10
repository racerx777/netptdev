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
define('BS_LOGGER_VERSION',      '4.5.$Revision: 1.2 $');require_once($APP['path']['core'] . 'lang/Bs_Error.class.php');define('BS_LOGGER_PROPFILE', 'Bs_Logger.conf.php');define('BS_LOG_INT_ERR',         'Internal Error');        define('BS_LOG_INT_USAGE_ERR',   'Internal Usage Error');  define('BS_LOG_INT_MURPHY_ERR' , 'Internal Murphy Error'); define('BS_LOG_ERR',         'Error');            define('BS_LOG_USAGE_ERR',   'Usage Error');      define('BS_LOG_MURPHY_ERR' , 'Murphy Error');     define('BS_LOG_WARNING',      'Warning');      define('BS_LOG_HACK_WARNING', 'Hack Warning'); define('BS_LOG_INFO',         'Info');            function bsLogger_LazyInclude() {global $APP;require_once($APP['path']['core'] . 'db/Bs_MySql.class.php');}
class Bs_Logger {var $_logProperty = NULL;var $_APP;var $_readyToLog =  FALSE;var $_targetDirOK = FALSE;var $_targetDbOK = FALSE;var $_logDir = NULL;var $_bsDb = NULL;function Bs_Logger() {}
function _init() {$this->_APP = &$GLOBALS['APP'];if (!empty($_SERVER['DOCUMENT_ROOT'])) {if ($this->loadPropertyFile($_SERVER['DOCUMENT_ROOT'] . '../' . BS_LOGGER_PROPFILE)) {$fileLogUsed = $dbLogUsed = FALSE;$size = sizeOf($this->_logProperty);for ($i=0; $i<$size; $i++) {$prop = $this->_logProperty[$i];if (!$prop['active']) continue; if ($prop['target'] == 'file') {$fileLogUsed = TRUE;} elseif ($prop['target'] == 'db') {$dbLogUsed = TRUE;}
}
if ($fileLogUsed AND (!empty($_SERVER['DOCUMENT_ROOT']))) {$this->setDir($_SERVER['DOCUMENT_ROOT'] . '../bs_log/');} 
if ($dbLogUsed AND isSet($GLOBALS['bsDb']) AND (!empty($this->_APP['db']['main']))) {$this->setDb($this->_APP['db']['main']);}
}
}
$this->_readyToLog = TRUE;}
function log($msg, $msgType, $_line_, $_func_='-?-', $_file_='-?-', $test=FALSE) {$_func_ = 'log';static $sid;if (!$this->_readyToLog) $this->_init();$_file_ = basename($_file_);$status = TRUE;if (empty($sid)) {if (isSet($GLOBALS['bsSession'])) $sid = $GLOBALS['bsSession']->getSid();if (empty($sid)) $sid =  'none';}
$size = sizeOf($this->_logProperty);for ($i=0; $i<$size; $i++) {$prop = &$this->_logProperty[$i];$prop['hit'] = FALSE;$target = $prop['target'];if (!$prop['active']) continue; if ($test) {$prop['hit'] = eval($prop['regEx']);continue;}
if ((!$this->_targetDbOK) AND ($target=='db')) continue; if ((!$this->_targetDirOK) AND ($target=='file')) continue; if (eval($prop['regEx'])) {if ($target == 'file') {   if (empty($prop['fp'])) {$prop['fp'] = fopen($this->_logDir . $prop['targetName'],  'ab');}
if ($prop['fp']) {fwrite($prop['fp'], date('r') . "\t["  . $_file_ ."::{$_func_} near line {$_line_}] $msgType: $msg" . (empty($sid) ? "" : "\n   [SID: {$sid}]") ."\n");} else { $prop['active'] = FALSE;Bs_Error::setError("fopen(".$this->_logDir.$prop['targetName'].", 'a') failed.", 'ERROR', __LINE__, 'log', __FILE__);$status = FALSE;}
} 
elseif ($target == 'db') {  $sql = 'INSERT INTO ' . $prop['targetName'] . ' VALUES  (' 
. "'', NULL, '"                  . $_line_ . "', '"
. $_func_ . "', '"
. $_file_ . "', '"
. addslashes($msgType) . "', '"
. addslashes($msg) . "', '"
. $sid . "')";$ret = $this->_bsDb->write($sql);if (isEx($ret)) {Bs_Error::setError('SQL error:' . $ret->stackDump('return'), 'ERROR', __LINE__, $_func_, __FILE__);$prop['active'] = FALSE;$status = FALSE;}
}
if ($prop['hitNquit']) break; }
}
return $status;}
function getTargetStatus($targetKey=NULL) {if (!$this->_readyToLog) $this->_init();$targetStat = array('dir'=>$this->_targetDirOK, 'db'=>$this->_targetDbOK);if (empty($targetKey)) {return $targetStat;} elseif (isSet($targetStat[$targetKey])) {return $targetStat[$targetKey];} else {return FALSE;}
}
function setDb($dsn) {$_func_ = 'setDb';$this->_targetDbOK = FALSE;do { if (empty($dsn)) { Bs_Error::setError('Empty DSN (DB Server Name - hash) and no default found.', 'ERROR', __LINE__, $_func_, __FILE__);break; }
if (!isSet($this->_bsDb)) {bsLogger_LazyInclude(); if (!isSet($GLOBALS['bsDb'])) {Bs_Error::setError('No DB Object given and no default found.', 'ERROR', __LINE__, $_func_, __FILE__);break; }
$this->_bsDb = &$GLOBALS['bsDb'];}
$dbStatus = $this->_bsDb->connect($dsn);if (isEx($dbStatus)) {Bs_Error::setError('Connection to DB failed. Exception:' . $dbStatus->stackDump('return'), 'ERROR', __LINE__, $_func_, __FILE__);break; }
$this->_targetDbOK = TRUE;} while (FALSE); return $this->_targetDbOK;}
function setDir($logDir) {$_func_ = 'setDir';$this->_targetDirOK = FALSE;do { if (empty($logDir)) { Bs_Error::setError("Empty log directory given (Empty parameter).", 'ERROR');break; }
if (!is_dir($logDir)) {if (!mkdir($logDir, 0700)) {Bs_Error::setError("Failed to create BS log directioy:[{$logDir}].", 'ERROR');break; }
} else {if (!is_writeable($logDir)) {Bs_Error::setError("Not able to write to BS log directioy:[{$logDir}].", 'ERROR');break; }
}
$this->_targetDirOK = TRUE;} while (FALSE); if ($this->_targetDirOK) $this->_logDir = $logDir;return $this->_targetDirOK;}
function createLogTable($tblName='') {$_func_ = 'createLogTable';$status = FALSE;if (!$this->_targetDbOK) {Bs_Error::setError("DB status is not OK. Stopped logging.", 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
if (!eregi('log$', $tblName)) $tblName .= 'Log';$sql = "CREATE TABLE IF NOT EXISTS {$tblName} ("
. 'ID INT UNSIGNED NOT NULL DEFAULT 0 AUTO_INCREMENT, '
. 'ts TIMESTAMP,'
. 'phpLine      INT NOT NULL DEFAULT -1, '
. 'phpFunc      VARCHAR(64) NOT NULL DEFAULT \'\', '
. 'phpFile      VARCHAR(64) NOT NULL DEFAULT \'\', '
. 'msgType      VARCHAR(32) NOT NULL DEFAULT \'\', '
. 'msg          VARCHAR(255) NOT NULL DEFAULT \'\', '
. 'sid          VARCHAR(64) NOT NULL DEFAULT \'\', '
. 'PRIMARY KEY ID (ID), '
. 'INDEX phpFunc (phpFunc), '
. 'INDEX phpFile (phpFile), '
. 'INDEX msgType (msgType) '
. ')';do { $ret = $this->_bsDb->write($sql);if (isEx($ret)) {Bs_Error::setError("Failed creating log table [$tblName]. SQL error: " . $ret->stackDump('return'), 'ERROR', __LINE__, $_func_, __FILE__);break; }
$status = TRUE;} while (FALSE); return $status;}
function dropLogTable($tblName='') {$_func_ = 'dropLogTable';$status = FALSE;if (!$this->_targetDbOK) {Bs_Error::setError("DB status is not OK. Stopped logging.", 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
if (!eregi('log$', $tblName)) $tblName .= 'Log';$sql = "DROP TABLE IF EXISTS {$tblName}";do { $ret = $this->_bsDb->write($sql);if (isEx($ret)) {Bs_Error::setError("Failed dropping log table [$tblName]. SQL error: " . $ret->stackDump('return'), 'ERROR', __LINE__, $_func_, __FILE__);break; }
$status = TRUE;} while (FALSE); return $status;}
function loadPropertyFile($fileAndPath) {$status = FALSE;do { if (is_file($fileAndPath)) {include($fileAndPath);$this->_logProperty = &$bs_logger_property;} else {Bs_Error::setError("Logger Ini-File [{$fileAndPath}] not found.", 'ERROR', __LINE__, 'loadPropertyFile', __FILE__);$this->_logProperty = array();break; }
$status = TRUE;} while (FALSE); return $status;}
function loadPropertyArray($prop) {$this->_logProperty = $prop;return TRUE;}
function test($msg, $msgType, $_line_, $_func_, $_file_, $logProperty) {$oldProp = $this->_logProperty;$this->_logProperty = $logProperty;$this->log($msg, $msgType, $_line_, $_func_, $_file_, $test=TRUE);$ret = $this->_logProperty;$this->_logProperty = $oldProp;return $ret;}
}
$GLOBALS['Bs_Logger'] =& new Bs_Logger(); function bs_logIt($msg, $msgType, $_line_, $_func_, $_file_) {$GLOBALS['Bs_Logger']->log($msg, $msgType, $_line_, $_func_, $_file_);}
if (basename($_SERVER['PHP_SELF']) == 'Bs_Logger.class.php') {ini_set('error_reporting', E_ALL);echo "Debug start";$now = explode(' ', microtime());echo "a";$logger = &$GLOBALS['Bs_Logger'];$dsn = array(
'name'     => '',
'host'     => 'localhost',
'port'     => '3306',
'user'     => "bs_blueshoes_1",
'pass'     => "bs_blueshoes_1",
'socket'   => '',
'syntax'   => 'mysql',
'type'     => 'mysql'
);if (!$logger->setDir('/var/www/bs-4.0/core/lang/bs_log/')) echo join('<br>', Bs_Error::getLastErrors()) .'<br>';if (!$logger->setDb($GLOBALS['APP']['db']['ecg'])) echo join('<br>', Bs_Error::getLastErrors()) .'<br>';if (!$logger->loadPropertyFile('./' . BS_LOGGER_PROPFILE)) echo join('<br>', Bs_Error::getLastErrors()) .'<br>';if (!$logger->createLogTable('test')) echo join('<br>', Bs_Error::getLastErrors()) .'<br>';echo '<pre>';echo '</pre>';if (!$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__)) echo join('<br>', Bs_Error::getLastErrors()) .'<br>';$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$logger->log('testmeldung', BS_LOG_INT_ERR ,__LINE__, 'test', __FILE__);$last = explode(' ', microtime());$totTime = (round( (($last[1] -$now[1]) + ($last[0] - $now[0]))*1000 ));echo $totTime . 'ms';}
?>
