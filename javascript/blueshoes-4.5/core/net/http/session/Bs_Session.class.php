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
define('BS_SESSION_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'lang/Bs_Logger.class.php');require_once($APP['path']['core'] . 'lang/Bs_Misc.lib.php');class Bs_Session extends Bs_Object {var $_sid;var $sidName = 'bs_session';var $_data;var $_hasChanged = FALSE;var $_gc;var $_ttl;var $_techType;function Bs_Session($gc, $ttl) {parent::Bs_Object();$this->_gc  = $gc;$this->_ttl = $ttl;mt_srand((double)microtime() * 1000000);bs_registerShutdownMethod(__LINE__, __FILE__, $this, 'write');}
function init($techType) {if ($techType == 0) $techType = 1; $this->_techType = $techType;switch ($techType) {case 1:
if (!empty($GLOBALS['HTTP_COOKIE_VARS'][$this->sidName])) {$sid = $GLOBALS['HTTP_COOKIE_VARS'][$this->sidName];} elseif (!empty($GLOBALS['HTTP_GET_VARS'][$this->sidName])) {$sid = $GLOBALS['HTTP_GET_VARS'][$this->sidName];}
break;case 2:
if (!empty($GLOBALS['HTTP_COOKIE_VARS'][$this->sidName])) {$sid = $GLOBALS['HTTP_COOKIE_VARS'][$this->sidName];}
break;case 3:
if (!empty($GLOBALS['HTTP_GET_VARS'][$this->sidName])) {$sid = $GLOBALS['HTTP_GET_VARS'][$this->sidName];}
break;}
if (isSet($sid)) {if ($this->_checkIntegrity($sid)) {$this->_sid = $sid;if ($this->read() === TRUE) {return TRUE;} else {unset($this->_sid);}
}
}
return FALSE;}
function _checkIntegrity($sid) {return TRUE;}
function start($type='cookie') {if ($this->_gc > 0) {$randVal = mt_rand(1, 100);if ($randVal <= $this->_gc) $this->gc();}
$this->_sid = md5(uniqid(mt_rand()));if ($type != 'query') {bsSetCookie($this->sidName, $this->_sid, "", "/", "", 0);} else {registerOutputHandler('rewriteUrlSession');}
$this->_hasChanged = TRUE;}
function destroy() {unset($this->_data);bsSetCookie($this->sidName, '', time() - 50000, "/", "", 0);}
function read() {$this->_hasChanged = FALSE;}
function write() {$this->_hasChanged = FALSE;}
function getSid() {if (is_null($this->_sid)) return NULL;return $this->_sid;}
function setSid($sid) {if (isSet($this->_sid)) $this->_hasChanged = TRUE;$this->_sid = $sid;}
function register($key, $value) {$this->_data[$key] = &$value;$this->_hasChanged = TRUE;}
function unRegister($key) {unset($this->_data[$key]);$this->_hasChanged = TRUE;}
function isRegistered($key) {return (bool)(isSet($this->_data[$key]));}
function &getVar($key) {return $this->_data[$key];}
function gc() {}
}
?>