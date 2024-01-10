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
define('BS_SESSIONFILE_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'net/http/session/Bs_Session.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');class Bs_SessionFile extends Bs_Session {var $_file;var $_path;var $filePrefix = 'bs_sess_';function Bs_SessionFile($path=NULL, $gc=NULL, $ttl=30) {parent::Bs_Session($gc, $ttl);if (empty($path)) {$this->_path = ini_get('session.save_path'); } else {$this->_path = $path;}
if (!is_dir($this->_path)) {$dir =& new Bs_Dir();$dir->mkpath($this->_path);}
if (!is_dir($this->_path) || !is_writeable($this->_path)) {$msg = "Unable to store session data. Invalid dir: '{$this->_path}'. ";if (!is_dir($this->_path)) {$msg .= "Does not exsist.";} elseif (!is_writeable($this->_path)) {$msg .= "No write access.";}
trigger_error($msg, E_USER_WARNING);}
$this->_file =& new Bs_File();$this->_path = $this->_file->standardizePath($this->_path);if (substr($this->_path, -1) != '/') $this->_path .= '/';}
function _checkIntegrity($sid) {$status = FALSE;do {$file = $this->_path . $this->filePrefix . $sid;if (!file_exists($file)) break; if (!is_readable($file)) break; $obsoletTime = time() - $this->_ttl*60;if (filemtime($file) < $obsoletTime) {@unlink($file);break; }
$status = TRUE;} while(FALSE);return $status;}
function destroy() {parent::destroy();return TRUE;}
function read() {$realPath = $this->_file->getRealPath($this->_path . $this->filePrefix . $this->_sid);if ($realPath === FALSE) return FALSE;$this->_file->setFullPath($realPath);$status = $this->_file->readAll();$this->_data = unSerialize($status);parent::read();return TRUE;}
function write() {if (is_null($this->_data)) {}
$string = serialize($this->_data);return $this->_file->onewayWrite($string, $this->_path . $this->filePrefix . $this->_sid);}
function gc() {$dir =& new Bs_Dir($this->_path);$listParams = array(
'regEx'      => '^bs_sess_.{32}$', 'depth'      => 0, 
'returnType' => 'subpath'
);$fileList = &$dir->getFileList($listParams);if (isEx($fileList)) {$fileList->stackTrace('was here()', __FILE__, __LINE__);} else {$obsoletTime = time() - $this->_ttl*60;foreach ($fileList as $filename) {$file = $this->_path . $filename;if (filemtime($file) >= $obsoletTime) continue;unlink($file);}
}
}
}
?>