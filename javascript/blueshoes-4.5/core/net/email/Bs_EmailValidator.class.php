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
define('BS_EMAILVALIDATOR_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT']      . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'net/Bs_NetApplication.class.php');require_once($APP['path']['core'] . 'net/email/Bs_EmailUtil.class.php');define('BS_EMAILVALIDATOR_ERROR',                     1);define('BS_EMAILVALIDATOR_ERROR_SYNTAX',              2);define('BS_EMAILVALIDATOR_ERROR_HOST',                3);define('BS_EMAILVALIDATOR_ERROR_NO_SUCH_USER',        4);define('BS_EMAILVALIDATOR_ERROR_NEW_ADDRESS',         5);define('BS_EMAILVALIDATOR_ERROR_COMMUNICATION',       6);define('BS_EMAILVALIDATOR_ERROR_NOT_CAPABLE',         7);define('BS_EMAILVALIDATOR_WARNING_NEW_ADDRESS',    1000);class Bs_EmailValidator extends Bs_NetApplication {var $Bs_EmailUtil;var $_Bs_System;var $bsDb;var $regExp = '~^[0-9a-z_\-\.]+@[0-9a-z][\-\.0-9a-z]*\.[a-z]{2,4}\.?$~Ui';var $port = 25;var $serverName;var $senderAddress;var $newAddress;function Bs_EmailValidator() {$this->Bs_NetApplication(); $this->Bs_EmailUtil  = &$GLOBALS['Bs_EmailUtil'];$this->_Bs_System    = &$GLOBALS['Bs_System'];$this->serverName    = $GLOBALS['HTTP_SERVER_VARS']['SERVER_NAME'];$this->senderAddress = 'dummy@' . $GLOBALS['HTTP_SERVER_VARS']['SERVER_NAME'];if (isSet($GLOBALS['bsDb'])) $this->bsDb = &$GLOBALS['bsDb'];}
function validateSyntax($email) {return (bool)(preg_match($this->regExp, $email));}
function validateHost($email) {if (preg_match($this->regExp, $email)) {if ($this->_Bs_System->isWindows()) return BS_EMAILVALIDATOR_ERROR_NOT_CAPABLE; $host = substr(strstr($check[0], '@'), 1) . '.';if (getMxRr($host, $validate_email_temp)) return TRUE;if (checkDnsRr($host, 'ANY')) return TRUE;return BS_EMAILVALIDATOR_ERROR_HOST;}
return BS_EMAILVALIDATOR_ERROR_SYNTAX;}
function validateMailbox($email) {unset($this->newAddress);$t = $this->Bs_EmailUtil->parse($email);if (!$t) return BS_EMAILVALIDATOR_ERROR_SYNTAX;list($user, $host) = $t;if (((is_object($this->_Bs_SocketClient)) && ($this->_Bs_SocketClient->getState())) && ($this->host == $host)) {$this->_useTempConnection = FALSE;$connObj = &$this->_Bs_SocketClient;} else {$this->_useTempConnection = TRUE;if ((is_null($host)) || (is_null($this->port))) 
return $this->_raiseError(BS_SOCKETCLIENT_ERROR_NEED_MORE_DATA, NULL, "host was: '{$host}', port was: '{$this->port}'.", __FILE__, __LINE__);$status = $this->connect($host, $this->port);if (isEx($status)) {$status->stackTrace('was here in validateMailbox()', __FILE__, __LINE__);return $status;}
$connObj = &$this->_Bs_SocketClient_Temp;}
$errCode = 0;do { if (!$connObj->writeLine("MAIL FROM:{$this->senderAddress}")) {$errCode = BS_EMAILVALIDATOR_ERROR_COMMUNICATION;break;}
$line = $connObj->readLine();if ((int)substr($line, 0, 3) != 250) {$errCode = BS_EMAILVALIDATOR_ERROR_COMMUNICATION;break;}
if (!$connObj->writeLine("RCPT TO:{$email}")) {$errCode = BS_EMAILVALIDATOR_ERROR_COMMUNICATION;break;}
if (!$line = $connObj->readLine()) {$errCode = BS_EMAILVALIDATOR_ERROR_COMMUNICATION;break;}
switch ((int)substr($line, 0, 3)) {case 250:
break 2;case 251:  $this->newAddress = '';$errCode = BS_EMAILVALIDATOR_WARNING_NEW_ADDRESS;break 2;case 551:  $this->newAddress = '';$errCode = BS_EMAILVALIDATOR_ERROR_NEW_ADDRESS;break 2;case 550: $errCode = BS_EMAILVALIDATOR_ERROR_NO_SUCH_USER;break 2;default:
$errCode = BS_EMAILVALIDATOR_ERROR_COMMUNICATION;break 2;}
} while (FALSE);return ($errCode > 0) ? $errCode : TRUE;}
function usesFreemailProvider($email) {$pos = strpos($email, '@');if ($pos !== FALSE) { $email = substr($email, $pos +1);}
$numDot = substr_count($email, '.');if ($numDot > 1) {$pos = strrpos($email, '.');$pos = strrpos(substr($email, 0, $pos), '.');$email = substr($email, $pos +1);}
if (isSet($this->bsDb)) {$sql = "SELECT * FROM BsKb.FreemailProvider WHERE domain LIKE '{$email}'";$numRecs = $this->bsDb->countRead($sql);if (is_numeric($numRecs)) {if ($numRecs > 0) return TRUE;return FALSE;}
}
return FALSE;}
function connect($host, $port=NULL, $persistent=FALSE, $timeOut=NULL, $blocking=TRUE) {$status = Bs_NetApplication::connect($host, $port, $persistent, $timeOut, $blocking);if (isEx($status)) {$status->stackTrace('was here in connect()', __FILE__, __LINE__);return $status;}
if ($this->_useTempConnection) {$connObj  = &$this->_Bs_SocketClient_Temp;} else {$connObj  = &$this->_Bs_SocketClient;}
$line = $connObj->readLine();if ($code=(int)substr($line, 0, 3) != 220) 
return $this->_raiseError($code=BS_EMAILVALIDATOR_ERROR_COMMUNICATION, $code, "conn opened, expecded a 220, but response was: '{$line}'.", __FILE__, __LINE__);if (!$connObj->writeLine("HELO {$this->serverName}")) 
return $this->_raiseError($code=BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send HELO command.", __FILE__, __LINE__);$line = $connObj->readLine();if ($code=(int)substr($line, 0, 3) != 250) 
return $this->_raiseError($code=BS_EMAILVALIDATOR_ERROR_COMMUNICATION, $code, "HELO sent, expecded a 250, but response was: '{$line}'.", __FILE__, __LINE__);return TRUE;}
function disconnect() {if ($this->_useTempConnection) {$connObj  = &$this->_Bs_SocketClient_Temp;} else {$connObj  = &$this->_Bs_SocketClient;}
$connObj->writeLine('QUIT');  Bs_NetApplication::disconnect();}
function &_raiseError($code=BS_EMAILVALIDATOR_ERROR, $nativeCode=NULL, $msg='', $file='', $line='', $weight='') {if (is_null($code)) $code = BS_EMAILVALIDATOR_ERROR; if (!is_null($nativeCode)) $msg .= " [nativecode={$nativeCode}]";return new Bs_Exception($msg, $file, $line, 'emailvalidator:'.$code, $weight);}
function getErrorMessage($errCode) {if (!is_long($errCode)) return FALSE;if (!isset($errorMessages)) {static $errorMessages;$errorMessages = array(
BS_EMAILVALIDATOR_ERROR                => 'unknown error', 
BS_EMAILVALIDATOR_ERROR_SYNTAX         => 'syntax error in email address', 
BS_EMAILVALIDATOR_ERROR_HOST           => 'host error: no dns record found.', 
BS_EMAILVALIDATOR_ERROR_NO_SUCH_USER   => 'no such user on that mailserver', 
BS_EMAILVALIDATOR_ERROR_NEW_ADDRESS    => 'new address given', 
BS_EMAILVALIDATOR_ERROR_COMMUNICATION  => 'communication error to the mailserver'
);}
if (isset($errorMessages[$errCode])) {return $errorMessages[$errCode];} else {return FALSE;}
}
}
?>