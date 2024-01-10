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
define('Bs_URLCACHE_VERSION',      '4.5.$Revision: 1.5 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'file/Bs_FileCache.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'net/Bs_Url.class.php');require_once($APP['path']['core'] . 'util/Bs_System.class.php');define('BS_UC_ERROR',                       1);define('BS_UC_ERROR_REQUEST_METHOD',        2);define('BS_UC_ERROR_IN_OUTPUT',             3);define('BS_UC_ERROR_NOT_CACHED',            4);define('BS_UC_ERROR_CACHE_EXPIRED',         5);define('BS_UC_ERROR_TMP_DIR',               6);class Bs_UrlCache extends Bs_Object {var $Bs_Url;var $Bs_System;var $_bsFileCache;var $baseCacheDir;var $excludeMask;var $includeMask;var $lifetimeSecs = 600;var $cacheControl = 'private';var $checkRequest = TRUE;var $checkForErrors = TRUE;var $ignoreCaseInPath;var $ignoreCaseInQuerystring = 0;var $ignoreCaseInUsername = FALSE;var $_url;var $_fileKey;function Bs_UrlCache() {parent::Bs_Object(); $this->Bs_Url    = &$GLOBALS['Bs_Url'];$this->Bs_System = &$GLOBALS['Bs_System'];$this->_loadFileCache();}
function setUrl($url=NULL) {if (is_null($url)) {$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];}
$this->_url = $url;list($path, $file) = $this->getCacheFilePath($url);$cacheDir = $this->_getAbsoluteCacheDir($path);if (isEx($cacheDir)) {return $cacheDir;}
$this->_fileKey = $file;$status = $this->_bsFileCache->setDir($cacheDir);if (!$status) {$errorMsg = $this->_bsFileCache->getLastError();return $this->_raiseError(BS_UC_ERROR, $errorMsg, __FILE__, __LINE__); }
return TRUE;}
function isNotModified() {if (!empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {$askedUnixTime = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);if (!$this->isModifiedSince($askedUnixTime)) {header('HTTP/1.1 304 Not Modified');header('Date: '. $_SERVER['HTTP_IF_MODIFIED_SINCE']); exit;  }
}
}
function treatHeaders($expireMinutes=5) {if (!$this->checkRequest || $this->isEmptyGetRequest()) {$expDate = gmdate('D, d M Y H:i:s', mktime(date('H'), date('i') +$expireMinutes)) . ' GMT';header('Expires: ' . $expDate);$lastMod = $this->getLastModified();if ($lastMod === FALSE) {$lastMod = mktime(date('H'), date('i')+1); }
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastMod) . ' GMT');header('Pragma: ' . (($this->cacheControl == 'public') ? '' : 'no-') . 'cache'); header('Cache-Control: ' . $this->cacheControl);}
}
function cachePage($content, $lifetimeSecs=null) {if ($this->checkRequest && !$this->isEmptyGetRequest()) {return $this->_raiseError(BS_UC_ERROR_REQUEST_METHOD, NULL, __FILE__, __LINE__); }
if (($this->checkForErrors) && (strpos($content, 'error') !== FALSE)) {return $this->_raiseError(BS_UC_ERROR_IN_OUTPUT, NULL, __FILE__, __LINE__); }
$lifetimeSecs = (is_null($lifetimeSecs)) ? $this->lifetimeSecs : $lifetimeSecs;$status = $this->_bsFileCache->store($this->_fileKey, serialize($content), FALSE, $lifetimeSecs);if ($status === TRUE) {return TRUE;} else {$errorMsg = $this->_bsFileCache->getLastError();return $this->_raiseError(BS_UC_ERROR, $errorMsg, __FILE__, __LINE__); }
}
function isModifiedSince($since) {return $this->_bsFileCache->isModifiedSince($this->_fileKey, $since);}
function getLastModified() {return $this->_bsFileCache->getLastModified($this->_fileKey);}
function isPageCached() {}
function getPage() {if ($this->checkRequest && !$this->isEmptyGetRequest()) return FALSE; $data = $this->_bsFileCache->fetch($this->_fileKey);if (is_null($data)) {return FALSE; } elseif ($data === FALSE) {return FALSE; } else {return unserialize($data);}
}
function isEmptyGetRequest() {if ($_SERVER['REQUEST_METHOD'] != 'GET') return FALSE;if (empty($_GET)) return TRUE;$numGetParams = sizeOf($_GET);if (isSet($_GET['NSPATH']))      $numGetParams--;if (isSet($_GET['storeCache']))  $numGetParams--;if (isSet($_GET['ignoreCache'])) $numGetParams--;if ($numGetParams == 0) return TRUE;return FALSE;}
function flushFileCache($onlyThisFolder=TRUE) {if ($onlyThisFolder) {$this->_bsFileCache->flushFileCache();return TRUE;} else {$dir =& new Bs_Dir;return $dir->rm($this->getCacheBaseDir());}
}
function _loadFileCache() {$this->_bsFileCache =& new Bs_FileCache();$this->_bsFileCache->setBufferSize(0); $this->_bsFileCache->setVerboseCacheNames(FALSE); }
function getCacheFilePath($url) {$urlParsed = $this->Bs_Url->parseUrlExtended($url);$urlParsed['scheme'] = strToLower($urlParsed['scheme']); $urlParsed['host']   = strToLower($urlParsed['host']);   if (isSet($urlParsed['user']) && $this->ignoreCaseInUsername) {$urlParsed['user'] = strToLower($urlParsed['user']); }
if (!isSet($urlParsed['query'])) $urlParsed['query'] = '';if ($this->ignoreCaseInQuerystring == 1) $urlParsed['query'] = strToLower($urlParsed['query']); do {if (isSet($this->ignoreCaseInPath)) {if (!$this->ignoreCaseInPath) break;} else {if (!$this->Bs_System->isWindows()) break;}
$urlParsed['directory'] = strToLower($urlParsed['directory']); $urlParsed['file']      = strToLower($urlParsed['file']);      } while (FALSE);if (!isSet($urlParsed['port'])) $urlParsed['port'] = '80';if (!isSet($urlParsed['user'])) $urlParsed['user'] = '_nobody_';$ret[] = $urlParsed['scheme'] . '/' . $urlParsed['host'] . '/' . $urlParsed['port'] . '/' . $urlParsed['user'] . $urlParsed['directory'];$ret[] = $urlParsed['file'] . '?' . $urlParsed['query'];return $ret;}
function getCacheBaseDir() {$urlParsed = $this->Bs_Url->parseUrlExtended($this->_url);$urlParsed['scheme'] = strToLower($urlParsed['scheme']); $urlParsed['host']   = strToLower($urlParsed['host']);   if (!isSet($urlParsed['port'])) $urlParsed['port'] = '80';return $this->baseCacheDir . $urlParsed['scheme'] . '/' . $urlParsed['host'] . '/' . $urlParsed['port'] . '/';}
function _getAbsoluteCacheDir($domainPath) {if (isSet($this->baseCacheDir)) {return $this->baseCacheDir . $domainPath;} else {$ret = getTmp();if ($ret === FALSE) {return $this->_raiseError(BS_UC_ERROR_TMP_DIR, null, __FILE__, __LINE__); }
return $ret . $domainPath;}
}
function &_raiseError($code=BS_DB_ERROR, $msg=NULL, $file='', $line='', $weight='') {if (is_null($code)) $code = BS_UC_ERROR;if (is_null($msg)) $msg = '';return new Bs_Exception($msg, $file, $line, 'uc:'.$code.':'.$this->getErrorMessage($code), $weight);}
function getErrorMessage($errCode) {if (!is_numeric($errCode)) return FALSE;if (!isSet($errorMessages)) {static $errorMessages;$errorMessages = array(
BS_UC_ERROR                 => 'unknown error.',
BS_UC_ERROR_REQUEST_METHOD  => 'wrong request method, has to be GET (not POST or HEAD).',
BS_UC_ERROR_IN_OUTPUT       => 'there was an error in the generated page content (eg a parse error) and we do not cache that.',
BS_UC_ERROR_NOT_CACHED      => 'the page for the url you are looking for has not been cached.',
BS_UC_ERROR_CACHE_EXPIRED   => 'the cache for that url expired.',
BS_UC_ERROR_TMP_DIR         => 'no temp dir, temp dir not detected, access problem or something.', 
);}
if (isSet($errorMessages[$errCode])) {return $errorMessages[$errCode];} else {return FALSE;}
}
}
?>