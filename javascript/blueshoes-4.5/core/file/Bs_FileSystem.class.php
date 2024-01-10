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
define('BS_FILESYSTEM_VERSION',   '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'util/Bs_System.class.php');require_once($APP['path']['core'] . 'util/Bs_String.class.php');class Bs_FileSystem extends Bs_Object {var $_Bs_System;var $_fullPath = '';var $_lastFetchedPath = '';var $_lastFetchedFileAttr = array();function Bs_FileSystem() {parent::Bs_Object();$this->_Bs_System =& $GLOBALS['Bs_System'];}
function getFileAttr($path='', $clearCache=FALSE) {if ($path == '') return FALSE;if (! file_exists($path)) return FALSE; if ($clearCache) {clearStatCache();$this->_lastFetchedPath = '';}
$aNewDir = FALSE;if ($this->_Bs_System->isWindows()) { if (strCaseCmp($this->_lastFetchedPath, $path) != 0) $aNewDir = TRUE;} else {if ($this->_lastFetchedPath !== $path) $aNewDir = TRUE;}
if (!$aNewDir) return $this->_lastFetchedFileAttr;$this->_lastFetchedPath = $path;$fileAttr = &$this->_lastFetchedFileAttr;$fileAttr = array();$fileAttr['fileName']      = '';  $fileAttr['fileExtension'] = '';  $fileAttr['path']          = $this->standardizePath($path);$fileAttr['pathStem']      = $this->getPathStem($path);$fileAttr['realPath']      = $this->getRealPath($path);$fileAttr['type']          = filetype($path);  $fileAttr['size']          = filesize($path);  $fileAttr['accessTime']    = fileatime($path); $fileAttr['modTime']       = filemtime($path);$fileAttr['groupID']       = filegroup($path);$fileAttr['ownerID']       = fileowner($path);$fileAttr['permissions']   = fileperms($path);$fileAttr['isDirectory']   = is_dir($path);$fileAttr['isFile']        = is_file($path);$fileAttr['isLink']        = $this->isLink($path); $fileAttr['isExecutable']  = is_executable($path);$fileAttr['isReadable']    = is_readable($path);$fileAttr['isWriteable']   = is_writeable($path);if ($fileAttr['isFile']) {$fileAttr['fileName']      = $this->getFileName($path);$fileAttr['fileExtension'] = $this->getFileExtension($path);} elseif ($fileAttr['isDirectory'] OR $fileAttr['isLink']) {}
return $fileAttr;}
function standardizePath($path) {return str_replace("\\", '/', trim($path));  }
function getCwd() {return Bs_FileSystem::standardizePath(getcwd());}
function getRealPath($path='') {if (!$pathHash = Bs_FileSystem::getRealPathSplit($path)) return FALSE;return $pathHash['realPath'];}
function realPath($path) {$pathHash = Bs_FileSystem::realPathSplit($path);return $pathHash['realPath'];}
function getRealPathSplit($path='') {$pathHash = Bs_FileSystem::realPathSplit($path);if (!file_exists($pathHash['realPath'])) return FALSE;if (@is_dir($pathHash['realPath'])) {  if (!empty($pathHash['file'])) {    $pathHash['realPath'] .= '/';$pathHash['pathCore'] .= $pathHash['file'] . '/';$pathHash['tailDir']   = $pathHash['file'];$pathHash['file']      = '';}
}
return $pathHash;}
function realPathSplit($path) {$path = trim($path);$ret = array('pathRoot'=>'', 
'pathCore'=>'', 
'file'=>'',
'tailDir'=>'', 
'realPath'=>'');do { if (empty($path))  break; if (($pos=strPos($path, ':')) !== FALSE) {$ret['pathRoot'] = substr($path, 0, $pos+1);$path = substr($path, $pos+1);  }
if (strPos($path, '//') === 0) {$ret['pathRoot'] .= '//';      } elseif ($path[0]==='/') {$ret['pathRoot'] .= '/';       }
$path  = preg_replace(array(';/\./;', ';[/\\\\]+;', ';^(?:\.)/;', ';/\.$;'), array('/','/','','/'), $path);$newPathlets = array();$newPathletPos  = 0;$pathlets     = explode('/', $path);$pathletSize  = sizeOf($pathlets);for ($i=0; $i<$pathletSize; $i++) {switch ($pathlets[$i]) {case '..':
$newPathletPos--;  if ($newPathletPos<0) {$newPathletPos = 0;$newPathlets[0] = '';}
$pathlets[$i] = '';  break;case '.':
case '' :
$pathlets[$i] = '';break;  default:
$newPathlets[$newPathletPos] = $pathlets[$i];$newPathletPos++; }
}
if ($newPathletPos==0) {$ret['file'] = $pathlets[$pathletSize-1];break; }
if (!empty($pathlets[$pathletSize-1])) {$ret['file'] = $pathlets[$pathletSize-1];$newPathletPos--;}
$pathCore = '';for ($i=0; $i<$newPathletPos; $i++) {$pathCore .= $newPathlets[$i] . '/';if ($i == ($newPathletPos-1)) $ret['tailDir'] = $newPathlets[$i];}
$ret['pathCore'] = $pathCore;} while(FALSE);$ret['realPath'] = $ret['pathRoot'] . $ret['pathCore'] . $ret['file'];return $ret;}
function setFullPath($workingDir) {$aNewDir = FALSE;if ($this->_Bs_System->isWindows()) {if (strCaseCmp($workingDir, $this->_fullPath) != 0) $aNewDir = TRUE;} else {if ($workingDir !== $this->_fullPath) $aNewDir = TRUE;}
if ($aNewDir) {$this->_fullPath = $workingDir;return TRUE;$this->_open();}
return FALSE;}
function getFullPath() {return $this->_fullPath;}
function getPathStem($path) {$ret = Bs_FileSystem::standardizePath($path); if (substr($ret, -1) === '/') return $ret;$ret = dirname($ret);if (substr($ret, -1) !== '/') $ret .= '/';return $ret;}
function getFileExtension($fullPath=NULL) {if (is_null($fullPath)) $fullPath = $this->_fullPath;$fullPath = Bs_FileSystem::basename($fullPath);$dotPos = strrpos($fullPath, '.');if ($dotPos === FALSE) {$extention = '';} else {$extention = substr($fullPath, $dotPos+1);}
if ((strToLower($extention) == 'lnk') && ($this->_Bs_System->isWindows())) {return $this->getFileExtension(substr($fullPath, 0, -4));}
return $extention;}
function getWithoutFileExtension($fullPath=NULL) {if (is_null($fullPath)) $fullPath = $this->_fullPath;$basename = Bs_FileSystem::basename($fullPath);$dotPos = strrpos($basename, '.');if ($dotPos === FALSE) {return $basename;}
$extention = substr($basename, $dotPos+1);if (($extention == 'lnk') && ($this->_Bs_System->isWindows())) {return $this->getWithoutFileExtension(substr($basename, 0, -4));}
return substr($basename, 0, -4);}
function basename($path) {$path   =  Bs_FileSystem::standardizePath($path); if (empty($path)) return '';if (substr($path,-1) === '/') return '';$lastPosSlash = strrpos($path, '/');if ($lastPosSlash === FALSE) return $path;return substr($path, $lastPosSlash +1);}
function getFileName($path) {return basename($path);}
function isValidFilename($fileName, $os='all') {}
function makeValidFileName($fileName, $os='all') {switch ($os) {case 'win':
$fileName = str_replace('\\', '', $fileName);$fileName = str_replace('/',  '', $fileName);$fileName = str_replace(':',  '', $fileName);$fileName = str_replace('*',  '', $fileName);$fileName = str_replace('?',  '', $fileName);$fileName = str_replace('"',  '', $fileName);$fileName = str_replace('<',  '', $fileName);$fileName = str_replace('>',  '', $fileName);$fileName = str_replace('|',  '', $fileName);if (substr($fileName, 0, 1) == ' ') $fileName = substr($fileName, 1);     if (substr($fileName, -1)   == ' ') $fileName = substr($fileName, 0, -1); break;case 'all':
case 'linux':
default:
$fileName = str_replace('_', ' ', $fileName);$fileName = preg_replace(";[[:space:]]+;s", '_', $fileName);$fileName = Bs_String::clean($fileName, 'alphanum', '_.');$fileName = preg_replace(";_+;s", '_', $fileName);break;}
return $fileName;}
function isValidPath($path, $system=NULL) {}
function isValidFullPath($fullPath, $system=NULL) {}
function isLink($fullPath=null) {if (is_null($fullPath)) $fullPath = $this->_fullPath;if ($this->_Bs_System->isWindows()) {return (substr($fullPath, -4) == '.lnk');} else {return (bool)is_link($fullPath);}
}
} $GLOBALS['Bs_FileSystem'] =& new Bs_FileSystem(); $GLOBALS['Bs_FileSystem'] =& new Bs_FileSystem(); ?>