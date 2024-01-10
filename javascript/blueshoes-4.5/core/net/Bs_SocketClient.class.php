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
define('BS_SOCKETCLIENT_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'util/Bs_System.class.php');define('BS_SOCKETCLIENT_DISCONNECTED',      0);define('BS_SOCKETCLIENT_CONNECTED',         1);define('BS_SOCKETCLIENT_SENT',              2);define('BS_SOCKETCLIENT_REQUEST_SENT',      3);  define('BS_SOCKETCLIENT_GOT_REPLY',         4);define('BS_SOCKETCLIENT_GOT_REPLY_HEADER',  5);  define('BS_SOCKETCLIENT_GOT_REPLY_CONTENT', 6);  define('BS_SOCKETCLIENT_ERROR',                     1);define('BS_SOCKETCLIENT_ERROR_NEED_MORE_DATA',      2);define('BS_SOCKETCLIENT_ERROR_CONNECT_FAILED',      3);define('BS_SOCKETCLIENT_ERROR_NOT_CONNECTED',       4);define('BS_SOCKETCLIENT_ERROR_WHILE_READ',          5);define('BS_SOCKETCLIENT_ERROR_WHILE_WRITE',         6);class Bs_SocketClient extends Bs_Object {var $_Bs_System;var $host = NULL;var $port = 80;var $_blocking = TRUE;var $persistent = FALSE;var $timeOut = 0;var $_connection = NULL;var $_state = BS_SOCKETCLIENT_DISCONNECTED;var $lineLength = 2048;function Bs_SocketClient() {parent::Bs_Object(); $this->_Bs_System = &$GLOBALS['Bs_System'];}
function setBlocking($mode=TRUE, $connection=NULL) {$mode = (bool)$mode; if (is_null($connection)) {if ($this->_blocking === $mode) return TRUE; if ($mode) {$this->_blocking = TRUE;$ret             = TRUE;} else {if ($this->_Bs_System->isWindows()) {$ret = FALSE;} else {$this->_blocking = FALSE;$ret             = TRUE;}
}
if (($ret) && (is_resource($this->_connection))) 
@set_socket_blocking($this->_connection, $mode);return $ret;} else { if ($mode) return TRUE; if (!is_resource($connection)) return FALSE;if ($this->_Bs_System->isWindows()) {return FALSE;} else {@set_socket_blocking($connection, FALSE);return TRUE;}
}
}
function isBlocking() {return $this->_blocking;}
function getState() {return $this->_state;}
function setState($state) {$this->_state = $state;}
function connect($host, $port, $persistent=FALSE, $timeOut=0, $blocking=TRUE, $return='bool') {if ((is_resource($this->_connection)) && ($return == 'bool')) {@fclose($this->_connection);$this->_connection = NULL;$this->_state      = BS_SOCKETCLIENT_DISCONNECTED;}
if (strspn($host, '.0123456789') == strlen($host)) {} else {$host = gethostbyname($host);}
$openFunc = ($persistent) ? 'pfsockopen' : 'fsockopen';if ((is_int($timeOut)) && ($timeOut > 0)) {$fp = @$openFunc($host, $port, &$errNo, &$errStr, $timeOut);} else {$fp = @$openFunc($host, $port, &$errNo, &$errStr);}
if (!$fp) {if ($return == 'bool') $this->_state = BS_SOCKETCLIENT_DISCONNECTED;return $this->_raiseError(BS_SOCKETCLIENT_ERROR_CONNECT_FAILED, $errNo, $errStr, __FILE__, __LINE__);} else {if ($return == 'resource') {$this->setBlocking($blocking, $fp);return $fp;} else {$this->host        = $host;$this->port        = $port; $this->timeOut     = $timeOut;$this->persistent  = $persistent;$this->_connection = $fp;$this->_state = BS_SOCKETCLIENT_CONNECTED;$this->setBlocking($blocking);return TRUE;}
}
}
function reconnect() {if (is_null($this->host)) return FALSE;if ($this->connect($this->host, $this->port, $this->persistent, $this->timeOut, $this->_blocking)) {return TRUE;} else {return FALSE;}
}
function disconnect($connection=NULL) {if (is_null($connection)) {@fclose($this->_connection);$this->_connection = NULL;$this->_state      = BS_SOCKETCLIENT_DISCONNECTED;} else {@fclose($connection);}
}
function eof($connection=NULL) {if (is_null($connection)) {return feof($this->_connection);} else {return feof($connection);}
}
function readLine($removeCrlf=TRUE, $connection=NULL) {if (is_null($connection)) $connection = $this->_connection;for ($line='';;) {if (@feof($connection)) {if ($line !== '') return $line;return NULL;}
if (!($part=@fgets($connection, $this->lineLength))) {if ($line !== '') return $line;return NULL;}
$line .= $part;$length = strlen($line);if (($length >= 2) && (substr($line, $length -2, 2) == "\r\n")) {if ($removeCrlf) $line = substr($line, 0, $length -2);return $line;} elseif (($length >= 1) && (substr($line, $length -1, 1) == "\n")) {if ($removeCrlf) $line = substr($line, 0, $length -1);return $line;}
}
}
function readAll($connection=NULL) {if (is_null($connection)) $connection = $this->_connection;$ret = '';while (!@feof($connection)) 
$ret .= fread($this->fp, $this->lineLength);return $ret;}
function gets($size, $connection=NULL) {if (is_null($connection)) $connection = $this->_connection;return @fgets($connection, $size);}
function read($size, $connection=NULL) {if (is_null($connection)) $connection = $this->_connection;return @fread($connection, $size);}
function writeLine($line, $crLf="\r\n", $connection=NULL) {if (is_null($connection)) $connection = $this->_connection;return (bool)(fwrite($connection, $line . $crLf));}
function write($data, $connection=NULL) {if (is_null($connection)) $connection = $this->_connection;return (bool)(fwrite($connection, $data));}
function &_raiseError($code=BS_SOCKETCLIENT_ERROR, $nativeCode=NULL, $msg='', $file='', $line='', $weight='') {if (is_null($code)) $code = BS_SOCKETCLIENT_ERROR; if (!is_null($nativeCode)) $msg .= " [nativecode={$nativeCode}]";return new Bs_Exception($msg, $file, $line, 'socket:'.$code, $weight);}
function getErrorMessage($errCode) {if (!is_long($errCode)) return FALSE;if (!isset($errorMessages)) {static $errorMessages;$errorMessages = array(
BS_SOCKETCLIENT_ERROR                    => 'unknown error', 
BS_SOCKETCLIENT_ERROR_NEED_MORE_DATA     => 'need more data', 
BS_SOCKETCLIENT_ERROR_CONNECT_FAILED     => 'connect failed', 
BS_SOCKETCLIENT_ERROR_NOT_CONNECTED      => 'not connected', 
BS_SOCKETCLIENT_WARNING                  => 'unknown warning'
);}
if (isset($errorMessages[$errCode])) {return $errorMessages[$errCode];} else {return FALSE;}
}
}
?>