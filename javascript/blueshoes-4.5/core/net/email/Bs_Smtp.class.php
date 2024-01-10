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
define('BS_SMTP_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'net/Bs_NetApplication.class.php');define('BS_MAIL_ERROR',                     1);define('BS_MAIL_ERROR_SYNTAX',              2);define('BS_MAIL_ERROR_HOST',                3);define('BS_MAIL_ERROR_NO_SUCH_USER',        4);define('BS_MAIL_ERROR_NEW_ADDRESS',         5);define('BS_MAIL_ERROR_COMMUNICATION',       6);define('BS_MAIL_ERROR_NOT_CAPABLE',         7);define('BS_MAIL_ERROR_NEED_MORE_DATA',      8);define('BS_MAIL_ERROR_NOT_CONNECTED',       9);class Bs_Smtp extends Bs_NetApplication {var $host = 'localhost';var $port = 25;var $client = 'BlueShoes Mail 4.0';var $authenticate = FALSE;var $username;var $password;var $esmtp;var $localhost;var $_from;var $sender;var $_replyTo;var $_to;var $_cc;var $_bcc;var $subject;var $_origDate;var $priority;var $sensitivity;var $message;var $_attachment;var $_header;function Bs_Smtp() {parent::Bs_NetApplication(); $this->localhost     = $GLOBALS['HTTP_SERVER_VARS']['SERVER_NAME'];}
function addTo($email, $name=NULL) {if (is_null($name)) $name = $email;$this->_to[] = array('email'=>$email, 'caption'=>$name);}
function addCc($email, $name=NULL) {if (is_null($name)) $name = $email;$this->_cc[] = array('email'=>$email, 'caption'=>$name);}
function addBcc($email, $name=NULL) {if (is_null($name)) $name = $email;$this->_bcc[] = array('email'=>$email, 'caption'=>$name);}
function addFrom($email, $name=NULL) {if (is_null($name)) $name = $email;$this->_from[] = array($email, $name);}
function addReplyTo($email, $name=NULL) {if (is_null($name)) $name = $email;$this->_replyTo[] = array($email, $name);}
function setOrigDate($param) {$this->_origDate = $param;}
function setHeader($key, $value) {if (is_null($key)) {$this->_header['_noKey'][] = $value;} else {$this->_header[$key] = $value;}
}
function attach() {}
function send() {$status = $this->_connect();if (isEx($status)) {$status->stackTrace('was here in send()', __FILE__, __LINE__);return $status;}
if ($this->authenticate) $this->_authenticate();$isOk = FALSE;do { $status     = $this->_sendMail();if ($status !== TRUE) break;$statusRcpt = $this->_sendRcpt();if (isEx($statusRcpt)) {$statusRcpt->stackTrace('was here in send()', __FILE__, __LINE__);return $statusRcpt;} elseif ($statusRcpt[0] == 'none') {return $statusRcpt;}
$status = $this->_sendData(); if ($status !== TRUE) break;$isOk = TRUE;} while (FALSE);if (!$isOk) {if (isEx($status)) {$status->stackTrace('was here in send()', __FILE__, __LINE__);return $status;}
}
$status = $this->_sendQuit();$this->_disconnect();return $statusRcpt;}
function convertForOldies($string) {$from = array('ä',  'Ä',  'ö',  'Ö',  'ü',  'Ü',  '«', '»', 'à', '¢', 'é', 'è');$to   = array('ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue', '"', '"', 'a', 'c', 'e', 'e');$string = str_replace($from, $to, $string);return $string;}
function _sendEhlo() {if (!$this->_Bs_SocketClient->writeLine("EHLO {$this->localhost}")) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send EHLO command.", __FILE__, __LINE__);$line = $this->_Bs_SocketClient->readLine();if ($code=(int)substr($line, 0, 3) != 250) {return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, $code, "EHLO sent, expected 250, but response was: '{$line}'.", __FILE__, __LINE__);} else {do {$ehloLine    = substr($line, 4); $ehloKeyword = strtok($ehloLine, ' ');$ehloParam   = array();$tok = ($ehloKeyword);while ($tok) {$tok = strToUpper(strtok(' '));$ehloParam[] = $tok;}
$this->esmtp[strToUpper($ehloKeyword)] = $ehloParam;if (substr($line, 3, 1) != '-') break;$line = $this->_Bs_SocketClient->readLine();if ($code=(int)substr($line, 0, 3) != 250) {return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, $code, "EHLO sent, error while getting extended features. response was: '{$line}'.", __FILE__, __LINE__);}
} while (TRUE);return TRUE;}
}
function _sendHelo() {if (!$this->_Bs_SocketClient->writeLine("HELO {$this->localhost}")) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send HELO command.", __FILE__, __LINE__);$line = $this->_Bs_SocketClient->readLine();if ($code=(int)substr($line, 0, 3) != 250) {return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, $code, "HELO sent, expecded a 250, but response was: '{$line}'.", __FILE__, __LINE__);}
}
function _sendMail() {if (empty($this->_from)) {return $this->_raiseError(BS_MAIL_ERROR_NEED_MORE_DATA, NULL, "'from' email address is missing.", __FILE__, __LINE__);}
if (!$this->_Bs_SocketClient->writeLine("MAIL FROM: <{$this->_from[0][0]}>")) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send MAIL FROM command.", __FILE__, __LINE__);$line = $this->_Bs_SocketClient->readLine();if ($code=(int)substr($line, 0, 3) != 250) {return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, $code, "MAIL FROM sent, expecded a 250, but response was: '{$line}'.", __FILE__, __LINE__);}
return TRUE;}
function _sendRcpt() {$status = $this->_sendRcptHelperArrays($this->_to);if (isEx($status)) {$status->stackTrace('was here in _sendRcpt()', __FILE__, __LINE__);return $status;}
$status2 = $this->_sendRcptHelperArrays($this->_cc);if (isEx($status2)) {$status2->stackTrace('was here in _sendRcpt()', __FILE__, __LINE__);return $status2;}
$status3 = $this->_sendRcptHelperArrays($this->_bcc);if (isEx($status3)) {$status3->stackTrace('was here in _sendRcpt()', __FILE__, __LINE__);return $status3;}
if (($status[0] == 'empty') && ($status2[0] == 'empty') && ($status3[0] == 'empty')) {return $this->_raiseError(BS_MAIL_ERROR_NEED_MORE_DATA, NULL, "no receiver specified.", __FILE__, __LINE__);} elseif ((($status[0] == 'all') || ($status2[0] == 'all') || ($status2[0] == 'all')) && ((($status[0] == 'all') || ($status[0] == 'empty')) && (($status2[0] == 'all') || ($status2[0] == 'empty')) && (($status3[0] == 'all') || ($status3[0] == 'empty')))) {$t = 'all';} elseif ((($status[0] == 'all') || ($status[0] == 'some')) || (($status2[0] == 'all') || ($status2[0] == 'some')) || (($status3[0] == 'all') || ($status3[0] == 'some'))) {$t = 'some';} else {$t = 'none';}
return array($t, array_merge($status[1], $status2[1], $status3[1]), array_merge($status[2], $status2[2], $status3[2]));}
function _sendRcptHelperArrays(&$array) {if (!((is_array($array)) && (!empty($array)))) 
return array('empty', array(), array());reset($array);$worked      = FALSE; $hasAddress  = FALSE;$failedNewAddress = array();$workedNewAddress = array();while (list($k) = each($array)) {$hasAddress       = TRUE;$arrayElement     = &$array[$k];$firstForwardPath = &$arrayElement['email'];$forwardPath      = &$arrayElement['email'];$loopCount        = 0;do {if ($loopCount >= 5) {$failedNewAddress[$firstForwardPath] = $forwardPath;break;}
$loopCount++; if (empty($forwardPath)) {$failedNewAddress[$firstForwardPath] = '';} else {$status = $this->_sendRcptHelperNetwork($forwardPath);$arrayElement['status'] = $status[0];if (sizeOf($status) > 1) {$arrayElement['sub']['email'] = $status[1];if ($status[0]) {$workedNewAddress[$forwardPath] = $status[1];break;} else {$t = &$arrayElement['sub']; unset($arrayElement);       unset($forwardPath);        $arrayElement = &$t;$forwardPath  = $status[1];}
} elseif (!$status[0]) {$failedNewAddress[$forwardPath] = '';break;} else {$worked = TRUE;break;}
}
} while (TRUE);}
if (!$hasAddress) {$t = 'empty';} elseif ($worked && (empty($failedNewAddress))) {$t = 'all';} elseif ($worked && (!empty($failedNewAddress))) {$t = 'some';} else {$t = 'none';}
return array($t, $failedNewAddress, $workedNewAddress);}
function _sendRcptHelperNetwork($forwardPath) {if (!$this->_Bs_SocketClient->writeLine("RCPT TO: <{$forwardPath}>")) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send RCPT TO: command.", __FILE__, __LINE__);$line = $this->_Bs_SocketClient->readLine();$code = (int)substr($line, 0, 3);switch ($code) {case 250:
return array(TRUE);break;case 251:
return array(TRUE);break;case 551:
return array(FALSE);break;default: return array(FALSE);}
}
function _sendData() {$body = $this->message;$body = preg_replace("/([^\r]{1})\n/", "\\1\r\n", $body);$body = preg_replace("/\n\n/", "\n\r\n", $body);$body = preg_replace("/^(\..*)/", ".\\1", $body); if (!$this->_Bs_SocketClient->writeLine('DATA')) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send DATA command.", __FILE__, __LINE__);$line = $this->_Bs_SocketClient->readLine();if ($code=(int)substr($line, 0, 3) != 354) { return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, $code, "DATA command sent, expected 354, but response was: '{$line}'.", __FILE__, __LINE__);}
$headerArray = $this->_prepareHeader();while (list($k) = each($headerArray)) {if (empty($headerArray[$k])) continue; if (!$this->_Bs_SocketClient->writeLine($headerArray[$k])) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send header line.", __FILE__, __LINE__);}
if (!$this->_Bs_SocketClient->writeLine('')) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send header line.", __FILE__, __LINE__);$b = explode("\r\n", $body);foreach($b as $bodyLine) {if (!$this->_Bs_SocketClient->writeLine($bodyLine)) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send body.", __FILE__, __LINE__);}
if (!$this->_Bs_SocketClient->writeLine(".")) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send body.", __FILE__, __LINE__);$line = $this->_Bs_SocketClient->readLine();if ($code=(int)substr($line, 0, 3) != 250) {return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, $code, "body sent, expected 250, but response was: '{$line}'.", __FILE__, __LINE__);}
return TRUE;}
function _sendRset() {}
function _sendNoop() {}
function _sendQuit() {if (!$this->_Bs_SocketClient->writeLine('QUIT')) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send QUIT command.", __FILE__, __LINE__);$line = $this->_Bs_SocketClient->readLine();if ($code=(int)substr($line, 0, 3) != 221) {return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, $code, "QUIT command sent, expected 221, but response was: '{$line}'.", __FILE__, __LINE__);}
return TRUE;}
function _sendVrfy() {}
function _connect() {$status = Bs_NetApplication::connect($this->host, $this->port, $persistent=FALSE, $timeOut=NULL, $blocking=TRUE);if (isEx($status)) {$status->stackTrace('was here in connect()', __FILE__, __LINE__);return $status;}
$line = $this->_Bs_SocketClient->readLine();if ($code=(int)substr($line, 0, 3) != 220) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, $code, "conn opened, expecded a 220, but response was: '{$line}'.", __FILE__, __LINE__);$status = $this->_sendEhlo();if (isEx($status)) {$status = $this->_sendHelo();if (isEx($status)) {$status->stackTrace('was here in _connect()', __FILE__, __LINE__);return $status;}
}
return TRUE;}
function _disconnect() {$this->_Bs_SocketClient->writeLine('QUIT');  Bs_NetApplication::disconnect();}
function _authenticate() {if (isSet($this->esmtp['AUTH']) && (in_array('LOGIN', $this->esmtp['AUTH']))) {if (!$this->_Bs_SocketClient->writeLine('AUTH LOGIN')) 
return $this->_raiseError(BS_EMAILVALIDATOR_ERROR_COMMUNICATION, NULL, "unable to send AUTH LOGIN command.", __FILE__, __LINE__);$line = $this->_Bs_SocketClient->readLine();$code = (int)substr($line, 0, 3);switch ($code) {case 334:
break;case 504:
return FALSE;break;case 503:
return TRUE;break;default:
}
} else {return FALSE;}
}
function _prepareAddressList(&$array) {if ((!is_array($array)) || (empty($array))) return '';$ret = '';reset($array);while (list($k) = each($array)) {if (!empty($ret)) $ret .= ', ';$email = current($array[$k]);$name  = next($array[$k]);if (!empty($name)) {$ret .= '"' . $name . '" ';}
$ret .= '<' . $email . '>';}
return $ret;}
function _prepareHeader() {$ret = array();if (!empty($this->subject)) $ret[] = "Subject: {$this->subject}";if (isSet($this->_origDate)) { $ret[] = "Date: {$this->_origDate}";} else {$ret[] = 'Date: ' . gmdate("D, j M Y H:i:s +0000");}
$t = $this->_prepareAddressList($this->_from);if (!empty($t)) $ret[] = 'From: ' . $t;if (!empty($this->sender)) {$ret[] = "Sender: {$this->sender}";} elseif (sizeOf($this->_from) > 1) {$ret[] = "Sender: {$this->_from[0][0]}";}
$t = $this->_prepareAddressList($this->_replyTo);if (!empty($t)) $ret[] = 'Reply-To: ' . $t;$t = $this->_prepareAddressList($this->_to);if (!empty($t)) $ret[] = 'To: ' . $t;$t = $this->_prepareAddressList($this->_cc);if (!empty($t)) $ret[] = 'Cc: ' . $t;$t = $this->_prepareAddressList($this->_bcc);if (!empty($t)) $ret[] = 'Bcc: ' . $t;$ret[] = "X-Mailer: {$this->client}";if (!empty($this->_header)) {while (list($k) = each($this->_header)) {if ($k == '_noKey') {while (list($k2) = each($this->_header['_noKey'])) {$ret[] = $this->_header['_noKey'][$k2];}
} else {$ret[] = $k . ': ' . $this->_header[$k];}
}
}
return $ret;}
function reset() {unset($this->_from);unset($this->sender);unset($this->_replyTo);unset($this->_to);unset($this->_cc);unset($this->_bcc);unset($this->subject);unset($this->message);unset($this->_origDate);unset($this->_header);unset($this->_attachment);}
function &_raiseError($code=BS_EMAILVALIDATOR_ERROR, $nativeCode=NULL, $msg='', $file='', $line='', $weight='') {if (is_null($code)) $code = BS_EMAILVALIDATOR_ERROR; if (!is_null($nativeCode)) $msg .= " [nativecode={$nativeCode}]";return new Bs_Exception($msg, $file, $line, 'emailvalidator:'.$code, $weight);}
}
?>