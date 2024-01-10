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
define('BS_LITTLESISTER_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');class Bs_LittleSister extends Bs_Object {var $cookieName = 'SESS10N';var $_userId;var $logPath;var $doAutoCreate = FALSE;var $blobFormat;function Bs_LittleSister() {parent::Bs_Object();}
function doItYourself() {if (!$this->_lookupUser()) {if ($this->doAutoCreate) {$this->setCookie();} else {return FALSE;}
}
$this->logRequest();return TRUE;}
function _lookupUser() {if (isSet($_COOKIE[$this->cookieName])) {$this->_userId = $_COOKIE[$this->cookieName];return TRUE;}
return FALSE;}
function setCookie($userId=NULL) {if (is_null($userId)) {$userId = md5(uniqid(mt_rand()));}
bsSetCookie($this->cookieName, $userId, time() + (86400 *360), "/");$this->_userId = $userId;}
function logRequest() {$logLine  = '';$logLine .= date('Y-m-d H:i:s') . ' ';$logLine .= $_SERVER['REQUEST_METHOD'] . ' ';$logLine .= $_SERVER['REQUEST_URI'] . ' ';if ($_SERVER['REQUEST_METHOD'] == 'POST') {$postKey = md5(uniqid(mt_rand()));$logLine .= '<post key=' . $postKey . ' >';$logLine .= "\n";$logLine .= dump($_POST, true);$logLine .= "\n";$logLine .= '</post key=' . $postKey . '>';}
$logLine .= "\n";$filePath = $this->_getLogPath() . $this->_userId . '/';$fileName = $GLOBALS['bsSession']->_sid . '.log'; if (!is_dir($filePath)) {$dir =& new Bs_Dir();$dir->mkpath($filePath);}
$file =& new Bs_File();$file->onewayAppend($logLine, $filePath . $fileName);}
function logThis() {}
function _getLogPath() {if (isSet($this->logPath)) {$logPath = $this->logPath;} else {$logPath = $GLOBALS['APP']['path']['site'] . 'data/littleSister/';}
return $logPath;}
}
?>