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
define('BS_FTPCLIENTPHP_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'net/ftp/Bs_FtpClient.class.php');class Bs_FtpClientPhp extends Bs_FtpClient {var $_conn_id;function Bs_FtpClient() {parent::Bs_FtpClient(); }
function reset() {if ($this->isConnected()) {$this->quit();}
unset($this->_conn_id);parent::reset();}
function connect() {if ($this->isConnected()) {$this->quit();}
$connId = ftp_connect($this->host, $this->port);if (!$connId) {return FALSE;} else {$this->_conn_id     = $connId;$this->_isConnected = TRUE;return TRUE;}
}
function login() {return (bool)ftp_login($this->_conn_id, $this->username, $this->password);}
function pwd($useCache=TRUE) {if ($useCache && (!empty($this->_remotePath))) return $this->_remotePath;$t = ftp_pwd($this->_conn_id); if (!$t) {$this->_remotePath = NULL; return FALSE;} else {return $this->_remotePath = $t;}
}
function cdUp() {$t = ftp_cdup($this->_conn_id);if ($t) {$this->pwd;} else {return FALSE;}
}
function chDir($directory) {$t = ftp_chdir($this->_conn_id, $directory);if ($t) {$this->pwd;} else {return FALSE;}
}
function mkDir($directory) {return (bool)ftp_mkdir($this->_conn_id, $directory);}
function rmDir($directory) {return (bool)ftp_rmdir($this->_conn_id, $directory);}
function &nList($directory) {$t = ftp_nlist($this->_conn_id, $directory);if (!$t) return FALSE;return $t;}
function &rawList($directory, $parse=TRUE) {$t = ftp_rawlist($this->_conn_id, $directory);if (!is_array($t)) return FALSE; if (!$parse) {return $t;} else {$t = &$this->parseRawList($t); return $t;}
}
function sysType($useCache=TRUE) {if ($useCache && (!empty($this->_sysType))) return $this->_sysType;$t = ftp_systype($this->_conn_id);if (!$t) return FALSE;return $this->_sysType = $t;}
function pasv($param) {return (bool)ftp_pasv($this->_conn_id, $param);}
function get($localFile, $remoteFile, $mode=NULL) {if (is_null($mode)) $mode = &$this->transferMode;return (bool)ftp_get($this->_conn_id, $localFile, $remoteFile, $mode);}
function fGet($fp, $remoteFile, $mode=NULL) {if (is_null($mode)) $mode = &$this->transferMode;return (bool)ftp_fget($this->_conn_id, $fp, $remoteFile, $mode);}
function put($localFile, $remoteFile, $mode=NULL) {if (is_null($mode)) $mode = &$this->transferMode;return (bool)ftp_put($this->_conn_id, $remoteFile, $localFile, $mode);}
function fPut($fp, $remoteFile, $mode=NULL) {if (is_null($mode)) $mode = &$this->transferMode;return (bool)ftp_fput($this->_conn_id, $remoteFile, $fp, $mode);}
function size($remoteFile) {$t = ftp_size($this->_conn_id, $remoteFile);if (!$t) return FALSE;return $t;}
function lastMod($remoteFile) {$t = ftp_mdtm($this->_conn_id, $remoteFile);if (!$t) return FALSE;return $t;}
function rename($remoteFile, $newRemoteFile) {return (bool)ftp_rename($this->_conn_id, $remoteFile, $newRemoteFile);}
function delete($remoteFile) {return (bool)ftp_delete($this->_conn_id, $remoteFile);}
function site($command) {return (bool)ftp_site($this->_conn_id, $command);}
function quit() {$this->_isConnected = FALSE;$status = @ftp_quit($this->_conn_id); unset($this->_conn_id);}
} ?>