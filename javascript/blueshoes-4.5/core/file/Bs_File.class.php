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
define('BS_FILE_VERSION',         '4.5.$Revision: 1.2 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'file/Bs_FileSystem.class.php');class Bs_File extends Bs_FileSystem {function Bs_File($fullPath='') {parent::Bs_FileSystem();$this->_fullPath = $this->getRealPath($fullPath);}
function cp() {$name = '_cp' . func_num_args();list($a, $b, $c) = func_get_args();return $this->$name($a, $b, $c);}
function _cp2(&$newFullPath, $returnType='bool') {$success = TRUE;if (! isSet($this->_fullPath) OR ($this->_fullPath=='')) {$success = FALSE;} else {$success = FALSE;if ($newFullPath = $this->getRealPath($newFullPath)) {if (copy($this->_fullPath, $newFullPath)) {$success = TRUE;}
}
}
if ($returnType == 'bool') {return $success;} elseif ($success) {return new Bs_File($newFullPath);} else {return new Bs_Exception("could not copy file '{$this->_fullPath}' to '{$newFullPath}'", __FILE__, __LINE__);}
}
function _cp3(&$newFileName, $newPath=NULL, $returnType='bool') {if (isSet($newPath)) {$newFullPath = $newPath;} else {$newFullPath = $this->getPathStem($this->_fullPath);if ($newFullPath === FALSE) {if ($returnType == 'bool') {return FALSE;} else {return new Bs_Exception("could not copy file: i don't know which file to copy.", __FILE__, __LINE__);}
}
}
$newFullPath .= $newFileName;return $this->_cp2($newFullPath, $returnType);}
function move($oldFullPath, $newFullPath, $overwrite=TRUE) {if (!is_readable($oldFullPath)) return FALSE; if ($overwrite) {if (file_exists($newFullPath)) {$status = @unlink($newFullPath);if (!$status) return FALSE;}
}
return @rename($oldFullPath, $newFullPath);}
function rm() {$status = TRUE;if (!empty($this->_fullPath)) {$status = (bool)unlink($this->_fullPath);}
return $status;}
function create($fullPath) {}
function readAll($fullPath=NULL) {if (empty($fullPath)) $fullPath = $this->_fullPath;if ($fp = fopen($fullPath, 'rb')) {$fileData = fread($fp, fileSize($fullPath));@fclose($fp);return $fileData;} else {return FALSE;}
}
function onewayWrite($string, $fullPath=NULL) {return $this->_write($string, $fullPath, 'wb');}
function onewayAppend($string, $fullPath=NULL) {return $this->_write($string, $fullPath, 'a');}
function _write($string, $fullPath, $mode) {if (is_null($fullPath)) $fullPath = $this->_fullPath;$fp = @fopen($fullPath, $mode);if (!$fp) return FALSE;$status = @fwrite($fp, $string);@fclose($fp); if ($status !== FALSE) return TRUE;return FALSE;}
function exclusiveWrite($string, $fullPath) {if (is_null($fullPath)) $fullPath = $this->_fullPath;$status = FALSE;do {$openParam =  file_exists($fullPath) ? "rb+" : "wb";if (($fp = @fopen($fullPath, $openParam)) === FALSE) {Bs_Error::setError("Failed to open file [{$fullPath}].", "ERROR");break;}
$lockTry = 0; $lockOk = TRUE;while (($lockOk = flock($fp, LOCK_EX+LOCK_NB)) == FALSE) {if ($lockTry++ > 3) break;sleep(1);}
if (!$lockOk) {Bs_Error::setError("Failed exclusivly lock the file [{$fullPath}] even after '{$lockTry}' trys.", "ERROR");break;}
@ftruncate($fp, 0);if (!@fwrite($fp, $string, strLen($string))) {Bs_Error::setError("Failed to write to file [{$fullPath}]; after successfully locking it.)", "ERROR");break;}
$status = TRUE;} while(FALSE);if (!empty($fp)) @fclose($fp);return $status;}
function write($string) {}
function append($string) {}
function toString($cr="\n") {$this->_getInfo();reset ($this->attr);while (list($key, $val) = each($this->attr)) {$out .= $key . "\t=\t" . $val . $cr;}
return $out;}
function toHtml() {return ($this->toString($cr="<br>\n"));}
} ?>