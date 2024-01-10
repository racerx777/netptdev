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
define('BS_DIR_VERSION',         '4.5.$Revision: 1.2 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'file/Bs_FileSystem.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');define('BS_DIR_UNLIM_DEPTH',   -99);class Bs_Dir extends Bs_FileSystem {var $_dirObj;function Bs_Dir($workingDir=NULL) {parent::Bs_FileSystem(); if (!is_null($workingDir)) $this->setFullPath($workingDir);}
function destruct() {$this->_close();}
function setFullPath($workingDir) {if (parent::setFullPath($workingDir)) {return $this->_open();}
return FALSE;}
function mkpath($path) {if (empty($path)) return FALSE;if (strlen($path) < 3) {return TRUE; } elseif (is_dir($path)) {return TRUE;} elseif (dirname($path) == $path) {return TRUE;}
return ($this->mkpath(dirname($path)) and @mkdir($path, 0775));}
function _open() {$this->_close();if (! isSet($this->_fullPath)) return FALSE;$this->_dirObj = dir($this->_fullPath);if ($this->_dirObj) {return TRUE;} else {unSet($this->_dirObj);return FALSE;}
}
function _close() {if (isSet($this->_dirObj)) {@$this->_dirObj->close(); unSet($this->_dirObj);}
}
function getFileList($params=array()) {static $defaultFileDirLink = array('file'=>TRUE, 'dir'=>TRUE, 'filelink'=>TRUE, 'dirlink'=>TRUE);static $defaultSettings = array(
'regFunction' => 'ereg',
'regEx'       => '', 
'regWhere'    => 'file', 
'depth'       => BS_DIR_UNLIM_DEPTH, 
'followLinks' => FALSE, 
'sort'        => FALSE,
'returnType'  => 'fullpath'
);$files = array();$dirs  = array();$currentPath = '';if (!isSet($params['_defaults_OK_'])) {if (isSet($params['fileDirLink']) AND is_array($params['fileDirLink'])) {$fileDirLink = array_merge($defaultFileDirLink, $params['fileDirLink']);} else {$fileDirLink = $defaultFileDirLink;}
unset($params['fileDirLink']);if (isSet($params) AND is_array($params)) {$t = array_merge($defaultSettings, $params);} else {$t = $defaultSettings;}
$t['fileDirLink'] = $fileDirLink;if (empty($t['fullPath'])) {if (empty($this->_fullPath)) {return new Bs_Exception("Cannot return a file list: 'fullpath' was not set.", __FILE__, __LINE__);} else {$t['fullPath'] = $this->_fullPath;}
}
$tmp = $t['fullPath'];if (FALSE === ($t['fullPath'] = Bs_FileSystem::getRealPath($tmp))) {return new Bs_Exception("[{$tmp}] is not an exsisting File nor a Dir", __FILE__, __LINE__);}
$t['_currentSubPath_']  = '';$t['_currentDir_']  = '';$t['_currentDepth_']    = 0;$t['_useRegEx_']    = (!empty($t['regFunction']) AND !empty($t['regEx']));$t['_defaults_OK_'] = TRUE;} else {$t = $params;$t['_currentDepth_']++;$t['_currentSubPath_'] = empty($t['_currentSubPath_']) ? $t['_currentDir_'] .'/': $t['_currentSubPath_'] . $t['_currentDir_'] . '/';}
$currentPath = $t['fullPath'];if (!$dirObj = @dir($currentPath)) {return new Bs_Exception("cannot open connection to PHP's dirhandler for path: [{$t['fullPath']}]", __FILE__, __LINE__);}
while(FALSE !== ($file = $dirObj->read())) {if (($file == '.') || ($file == '..')) {continue; }
if (is_dir($currentPath . $file)) {$dirs[] = $file;} elseif (is_file($currentPath . $file)) {$files[] = $file;}
if ($t['sort']) {sort($dirs);sort($files);}
}
@$dirObj->close();$t['_fileList_']    = array();foreach ($files as $file) {$fileFullPath = $currentPath . $file;if ($t['fileDirLink']['file'] && ($t['fileDirLink']['filelink'] || (!$this->isLink($fileFullPath)))) {$doIt = TRUE;if ($t['_useRegEx_']) {$matchStr = ($t['regWhere'] == 'dir') ? $fileFullPath : $file;$doIt = $t['regFunction']($t['regEx'], $matchStr);}
if ($doIt) {switch($t['returnType']) {case 'object':
$t['_fileList_'][] =& new Bs_File($fileFullPath);break;case 'subpath':
$t['_fileList_'][] = !empty($t['_currentSubPath_']) ? $t['_currentSubPath_'] . $file : $file;break;case 'fulldir/file':
$t['_fileList_'][] = array('dir'=>$currentPath, 'file'=>$file);break;case 'subdir/file':
case 'subdir/file2': $t['_fileList_'][] = array('dir'=>(!empty($t['_currentSubPath_'])) ? $t['_currentSubPath_'] : '', 'file'=>$file);break;case 'nested':
$t['_fileList_'][$file] = FALSE;break;case 'nested2':
$t['_fileList_'][] = $file;break;default: $t['_fileList_'][] = $fileFullPath;}
}
}
}
foreach ($dirs as $dir) {$dirFullPath = $currentPath . $dir;if (substr($dirFullPath, -1) !== '/') $dirFullPath .= '/';$t['_currentDir_'] = $dir;if ($t['fileDirLink']['dir'] && ($t['fileDirLink']['dirlink'] || (!$this->isLink($dirFullPath)))) {$doIt = TRUE;if ($t['_useRegEx_']) {$matchStr = ($t['regWhere'] == 'dir') ? $dirFullPath : $dir;$doIt = $t['regFunction']($t['regEx'], $matchStr);}
if ($doIt) {switch($t['returnType']) {case 'object':
$t['_fileList_'][] =& new Bs_Dir($dirFullPath);break;case 'subpath':
$t['_fileList_'][] = !empty($t['_currentSubPath_']) ? $t['_currentSubPath_'] . $dir .'/': $dir .'/';break;case 'fulldir/file':
$t['_fileList_'][] = array('dir'=>$dirFullPath, 'file'=>'');break;case 'subdir/file':
$t['_fileList_'][] = array('dir'=>(isSet($t['_currentSubPath_'])) ? $t['_currentSubPath_'] . $dir .'/': $dir, 'file'=>'');break;case 'subdir/file2':
$t['_fileList_'][] = array('dir'=>(isSet($t['_currentSubPath_'])) ? $t['_currentSubPath_']  : '', 'file'=>$dir);break;case 'nested':
case 'nested2':
$t['_fileList_'][$dir] = array();break;default: $t['_fileList_'][] = $dirFullPath;}
}
}
if ((($t['depth'] == BS_DIR_UNLIM_DEPTH) || ($t['depth'] > $t['_currentDepth_'])) && (is_readable($dirFullPath)) && (($t['followLinks']) || (!$this->isLink($dirFullPath)))) { $t['fullPath'] = $dirFullPath;$dirList2 = $this->getFileList($t);if (isEx($dirList2)) {$dirList2->stackTrace('', __FILE__, __LINE__);return $dirList2;} else {if (($t['returnType'] === 'nested') || ($t['returnType'] === 'nested2')) {$t['_fileList_'][$dir] = $dirList2;} else {$t['_fileList_'] = array_merge($t['_fileList_'], $dirList2);}
}
}
}
return $t['_fileList_'];} function emptyDir($recursive=FALSE, $regExp=NULL) {$depth = ($recursive) ? BS_DIR_UNLIM_DEPTH : 0;$params = array(
'depth'       => $depth, 
);if (!is_null($regExp)) {$params['regEx']       = $regExp;$params['regFunction'] = 'preg';$params['regWhere']    = 'file';}
$fileList = $this->getFileList($params);if (isEx($fileList)) {$fileList->stackTrace('in emptyDir()', __FILE__, __LINE__);return $fileList;}
while (list(,$fullPath) = each($fileList)) {$status = @unlink($fullPath);}
return TRUE;}
function clearCache() {clearstatcache();}
function getFreeDiskSpace() {}
function rm($fullPath=NULL, $recursive=TRUE) {clearstatcache(); if (is_null($fullPath)) $fullPath = $this->_fullPath;if (!is_dir($fullPath) || !is_readable($fullPath)) return FALSE;@exec("rm -rf '{$fullPath}'"); @exec("rmdir/S/Q \"{$fullPath}\"");return TRUE;}
function isEmpty() {}
function mv() {}
function create() {}
function search() {}
function searchAndReplace() {}
function cp($oldname, $newname) {if (is_file($oldname)) {$perms = fileperms($oldname);return (bool)(copy($oldname, $newname) && chmod($newname, $perms));} elseif (is_dir($oldname)) {$this->cpDir($oldname, $newname);} else {return FALSE;}
}
function cpDir($oldname, $newname, $ignoreDirs=NULL, $isWindows=NULL) {if (!is_dir($newname)) {mkdir($newname);}
$dir = opendir($oldname);while ($file = readdir($dir)) {if (($file == '.') || ($file == '..')) continue;if (!empty($ignoreDirs)) {if (is_null($isWindows)) $isWindows = $GLOBALS['Bs_System']->isWindows();if ($isWindows) {$fileLower = strToLower($file);$doIgnore = FALSE;foreach ($ignoreDirs as $iDir) {if (strToLower($iDir) === $fileLower) {$doIgnore = TRUE;break;}
}
if ($doIgnore) continue;} else {if (in_array($file, $ignoreDirs, TRUE)) continue;}
}
if (is_dir("$oldname/$file")) {$this->cpDir("$oldname/$file", "$newname/$file", $ignoreDirs, $isWindows);} else {$this->cp("$oldname/$file", "$newname/$file");}
}
closedir($dir);return TRUE;}
} ?>