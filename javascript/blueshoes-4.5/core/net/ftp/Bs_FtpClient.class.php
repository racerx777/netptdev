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
define('BS_FTPCLIENT_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'date/Bs_Date.class.php');class Bs_FtpClient extends Bs_Object {var $_Bs_Date;var $_isConnected = FALSE;var $host;var $port = 21;var $anonymous = TRUE;var $username;var $password;var $transferMode = FTP_ASCII;var $_remotePath;var $_localPath;var $_sysType;function Bs_FtpClient() {parent::Bs_Object(); $this->_Bs_Date =& $GLOBALS['Bs_Date'];}
function reset() {unset($this->host);$this->port      = 21;$this->anonymous = TRUE;unset($this->username);unset($this->password);unset($this->_remotePath);unset($this->_localPath);unset($this->_sysType);$this->transferMode = FTP_ASCII;$this->_isConnected = FALSE;}
function isConnected() {return $this->_isConnected;}
function connect() {return FALSE; }
function login() {return FALSE; }
function pwd($useCache=TRUE) {return FALSE; }
function localPwd() {return FALSE; }
function cdUp() {return FALSE; }
function localCdUp() {return FALSE; }
function chDir($directory) {return FALSE; }
function localChDir() {return FALSE; }
function mkDir($directory) {return FALSE; }
function localMkDir($directory) {return FALSE; }
function rmDir($directory) {return FALSE; }
function rmDirRec($directory) {if ($directory != '') {$ar_files = $this->nList($directory);if (is_array($ar_files)) {for ($i=0; $i<count($ar_files); $i++) {$st_file = $ar_files[$i];if ($this->size($directory . "/" . $st_file) == -1) {$this->rmDirRec($directory. "/". $st_file);} else {$this->delete($directory . "/" . $st_file);}
}
}
$this->rmDir($directory);}
}
function localRmDir($directory) {return FALSE; }
function localRmDirRec() {return FALSE; }
function &nList($directory) {return FALSE; }
function localNlist($directory) {return FALSE; }
function &rawList($directory, $parse=TRUE) {return FALSE; }
function &parseRawList(&$rawList) {if (is_array($rawList)) {$ret = array();while (list($k) = each($rawList)) {$t = split(' {1,}', $rawList[$k], 9);if (is_array($t) && (sizeOf($t) == 9)) { unset($ret2);$ret2['name']  = $t[8];      $ret2['size']  = (int)$t[4]; $month = $this->_Bs_Date->monthToInt($t[5], TRUE);$day   = (strlen($t[6]) == 2) ? $t[6] : '0' . $t[6];if (strlen($t[7]) == 4) {$ret2['date'] = $t[7] . '/' . $month . '/' . $day;} else {$ret2['date'] = date('Y') . '/' . $month . '/' . $day . ' ' . $t[7];}
$ret2['attr']  = $t[0];      $ret2['type']  = ($t[0][0] == '-') ? 'file' : 'dir';$ret2['dirno'] = (int)$t[1]; $ret2['user']  = $t[2];      $ret2['group'] = $t[3];      $ret[] = $ret2;} else {}
}
return $ret;}
return FALSE;}
function &localRawList($localDir) {return FALSE; }
function sysType($useCache=TRUE) {return FALSE; }
function pasv($param) {return FALSE; }
function get($localFile, $remoteFile, $mode=NULL) {return FALSE; }
function fGet($fp, $remoteFile, $mode=NULL) {return FALSE; }
function put($localFile, $remoteFile, $mode=NULL) {return FALSE; }
function fPut($fp, $remoteFile, $mode=NULL) {return FALSE; }
function fileExists($remoteFile) {return null;}
function localExists() {return null;}
function dirExists() {return null;}
function localDirExists() {return null;}
function size($remoteFile) {return FALSE; }
function localSize($localFile) {return FALSE; }
function lastMod($remoteFile) {return FALSE; }
function localLastMod($localFile) {return FALSE; }
function rename($remoteFile, $newRemoteFile) {return FALSE; }
function localRename($localFile, $newLocalFile) {return FALSE; }
function delete($remoteFile) {return FALSE; }
function localDelete($localFile) {return FALSE; }
function site($command) {return FALSE; }
function quit() {return FALSE; }
function synchronizeFile($localFile, $remoteFile, $direction='both') {return FALSE; }
function synchronizeDir($localDir, $remoteDir, $depth=0, $direction='both') {return FALSE; }
} ?>