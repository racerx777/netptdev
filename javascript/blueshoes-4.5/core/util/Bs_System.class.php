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
define('BS_SYSTEM_VERSION',      '4.5.$Revision: 1.2 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'util/Bs_String.class.php');class Bs_System extends Bs_Object {var $_Bs_String;var $_isWindows;function Bs_System() {parent::Bs_Object(); $this->_Bs_String = &$GLOBALS['Bs_String'];}
function isWindows() {if ((isSet($this->_isWindows)) && (is_bool($this->_isWindows))) return $this->_isWindows;return $this->_isWindows = (bool) (strToLower(substr(PHP_OS,0,3)) === 'win');}
function getOs() {return PHP_OS;}
function getOsVersion() {}
function getSystemName() {if (!isSet($_ENV)) {  $_ENV = $GLOBALS['HTTP_ENV_VARS'];}
if (isSet($_ENV['COMPUTERNAME'])) {return $_ENV['COMPUTERNAME'];}
return FALSE;}
function systemCheckup() {}
function getUptime() {}
function getCpuStat($maxWaitTime=3000) {function _getCpuStat_readData() {do {$t = file('/proc/stat');if (!isSet($t[0])) break; $t = explode(' ', $t[0]);if (!is_array($t) || (sizeOf($t) < 6)) break; return $t;} while (FALSE);return FALSE;}
function _getCpuStat_fileChanged($compareTimestamp, $maxWaitTime) {$waitStep = $maxWaitTime / 100;       if ($waitStep > 100) $waitStep = 100; for ($i=0; $i<$waitStep; $i++) {      clearstatcache();$newTimestamp = filemtime('/proc/stat');if ($newTimestamp > $compareTimestamp) return TRUE;usleep(100000);}
return FALSE;}
do {$readOne = _getCpuStat_readData();if ($readOne === FALSE) break;$readOneTime = filemtime('/proc/stat');if (!_getCpuStat_fileChanged($readOneTime, $maxWaitTime)) break; $readTwo = _getCpuStat_readData();if ($readTwo === FALSE) break; $userTime   = $readTwo[2] - $readOne[2];$niceTime   = $readTwo[3] - $readOne[3];$systemTime = $readTwo[4] - $readOne[4];$idleTime   = $readTwo[5] - $readOne[5];$total = $userTime + $niceTime + $systemTime + $idleTime;$ret = array();$ret['user']      = round($userTime   / $total * 100, 2);$ret['system']    = round($niceTime   / $total * 100, 2);$ret['nice']      = round($systemTime / $total * 100, 2);$ret['idle']      = round($idleTime   / $total * 100, 2);$ret['available'] = $ret['nice'] + $ret['idle'];return $ret;} while (FALSE);return FALSE;}
function getCpuAvailable() {$t = $this->getCpuStat();if ($t === FALSE) return FALSE;return $t['available'];}
function getCpuInfo() {}
function getMemory() {}
function getLoadAvg() {do {$t = file('/proc/loadavg');if (!isSet($t[0])) break; $t = explode(' ', $t[0]);if (!is_array($t) || (sizeOf($t) < 3)) break; return $t;} while (FALSE);return FALSE;}
function getSerial() {do {$t = file('/proc/serialnumber');if (!isSet($t[0])) break; return trim($t[0]);} while (FALSE);return FALSE;}
function getVersion() {}
}
$GLOBALS['Bs_System'] =& new Bs_System(); ?>