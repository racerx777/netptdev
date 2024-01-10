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
define('Bs_FILECACHE_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');require_once($APP['path']['core'] . 'util/Bs_UnitConverter.class.php');class Bs_FileCache extends Bs_Object {var $_bsFile;var $_cacheProp = array(
'maxCacheLifeTime' => 0,       'maxBufLifeTime'   => 10,      'maxBufSize'       => 0,       'freeBufSize'      => 0,       'verboseName'      => TRUE,    'storeDir'         => ''       );var $_fifoBuffer = array();var $_cacheVersion = NULL;function Bs_FileCache($cacheVersion=0) {parent::Bs_Object(); $this->_cacheVersion = $cacheVersion;if (!$this->setBufferSize('5%')) {$this->setBufferSize('100K');}
$this->_cacheProp['freeBufSize'] = $this->_cacheProp['maxBufSize'];}
function isModifiedSince($filePath, $since) {$cacheTime = $this->getLastModified($filePath);if ($cacheTime === FALSE) return TRUE; if ($cacheTime > $since) {return FALSE;}
return TRUE;}
function getLastModified($filePath) {if (empty($filePath)) return FALSE; $cacheFilePath = $this->_determinePathToCache($filePath);if (empty($cacheFilePath)) {return FALSE; }
$t = @filemtime($cacheFilePath);if (!$t) return FALSE;return gmmktime(date('H', $t), date('i', $t), date('s', $t), date('M', $t), date('d', $t), date('Y', $t));}
function fetch($filePath) {if (empty($filePath)) return FALSE;$status = FALSE;$addToFifo = FALSE;$dataStream = NULL;do {if (FALSE !== ($idx = $this->_getFiFoIndex($filePath))) {$cacheBlock = $this->_fifoBuffer[$idx];} else { $addToFifo = TRUE;$cacheFilePath = $this->_determinePathToCache($filePath);if (empty($cacheFilePath)) {Bs_Error::setError("Invalid parameter: \$filePath.", "ERROR");break; }
if (FALSE === ($cacheBlock = $this->_readCacheFile($cacheFilePath))) {break; }
}
$now = time();if (($cacheBlock['maxLifeTime'] > 0) AND ($cacheBlock['maxUnixTime'] < $now)) {$status = NULL;break; }
if (!$cacheBlock['originCheck']) {$status = TRUE;break; }
if (isSet($cacheBlock['fifoTimestamp'])) { if (($now - $cacheBlock['fifoTimestamp']) <= $this->_cacheProp['maxBufLifeTime']) {$status = TRUE;break; }
}
if (TRUE !== ($tmp=$this->_isCacheFileUptodate($filePath, $cacheFilePath))) {$status = ($tmp === FALSE) ? NULL : FALSE;break; }
$status = TRUE;} while(FALSE);if ($status) {if ($addToFifo) $this->_addToFiFo($cacheBlock); }
if ($status) return $cacheBlock['dataStream'];return $status;}
function store($filePath, $dataStream, $originCheck=TRUE, $maxLifeTime=NULL) {if (is_null($maxLifeTime)) $maxLifeTime = $this->_cacheProp['maxCacheLifeTime'];if (!$originCheck AND ($maxLifeTime<=0)) $maxLifeTime=3600*24;$status = FALSE;do {if (!is_string($dataStream)) {Bs_Error::setError("Invalid data to store. Must be a string. TIPP: Use PHP's serialize() to transform any data to a string.", "ERROR");break; }
$streamSize = strLen($dataStream);$cacheBlock = array (
'cacheVersion' => $this->_cacheVersion,  'valid'        => TRUE,                  'maxLifeTime'  => $maxLifeTime,          'maxUnixTime'  => time() + $maxLifeTime, 'originCheck'  => $originCheck,          'filePath'     => $filePath,             'streamSize'   => $streamSize,           'dataStream'   => $dataStream            );if (FALSE !== ($idx = $this->_getFiFoIndex($filePath))) {$this->_fifoBuffer[$idx]['valid'] = FALSE;}
if (FALSE === ($cacheFilePath = $this->_determinePathToCache($filePath))) {break; }
$this->_addToFiFo($cacheBlock);if (FALSE === $this->_writeCacheFile($cacheFilePath, $cacheBlock)) {break; }
$status = TRUE;} while(FALSE);return $status;}
function clearBuffer($filePath='') {if (empty($filePath)) {$this->_fifoBuffer = array();} else {if (FALSE !== ($idx = $this->_getFiFoIndex($filePath))) {$this->_fifoBuffer[$idx]['valid'] = FALSE;}
}
return TRUE;}
function flushFileCache() {$dir =& new Bs_Dir($this->_cacheProp['storeDir']);$dir->emptyDir(TRUE);}
function setBufferSize($newBufSize) {$status = FALSE;do {if (is_numeric($newBufSize)) {$this->_cacheProp['maxBufSize'] = $newBufSize;$status = TRUE;break; } 
$newBufSize = trim($newBufSize);if (substr($newBufSize,-1) === '%') {$val = trim(substr($newBufSize, 0, strLen($newBufSize)-1));if (!is_numeric($val)) break; if (FALSE === ($memSize = Bs_UnitConverter::unitStringToBytes(get_cfg_var('memory_limit')))) {break; }
if ($val>80) $val = 80;$this->_cacheProp['maxBufSize'] = (int) ($val * $memSize/100);} else {if (FALSE === ($newBufSize = Bs_UnitConverter::unitStringToBytes($newBufSize))) {break; }
$this->_cacheProp['maxBufSize'] = $newBufSize;}
$status = TRUE;} while(FALSE);if ($status) return $this->_cacheProp['maxBufSize'];return FALSE;}
function setBufferLifetime($sec=10) {if (!is_numeric($sec)) return FALSE;$this->_cacheProp['maxBufLifeTime'] = $sec;return TRUE;}
function setCacheLifeTime($sec=0) {if (!is_numeric($sec)) return FALSE;$this->_cacheProp['maxCacheLifeTime'] = $sec;return TRUE;}
function setDir($path='') {$status = FALSE;do {if (empty($path)) break;$path = str_replace("\\", '/', trim($path));  if (!file_exists($path)) {$dir =& new Bs_Dir();$status = $dir->mkpath($path);if (!$status) break;}
if (!is_dir($path) || !is_writeable($path)) break;if (substr($path, -1) !== '/')  $path .= '/';$this->_cacheProp['storeDir'] = $path;$status = TRUE;} while (FALSE);if (!$status) {$this->_cacheProp['storeDir'] = '';$status = TRUE;}
return $status;}
function setVerboseCacheNames($trueFalse = TRUE) {$this->_cacheProp['verboseName'] = $trueFalse;}
function _getFiFoIndex($filePath) {$fifoSize = sizeOf($this->_fifoBuffer);for ($i=0; $i<$fifoSize; $i++) {if ($this->_fifoBuffer[$i]['valid'] AND ($filePath === $this->_fifoBuffer[$i]['filePath'])) return $i;}
return FALSE;}
function _addToFiFo($cacheBlock) {$status = FALSE;do {if (!$this->_fifoFreeSpace($cacheBlock['streamSize'])) {break; }
$cacheBlock['fifoTimestamp'] = time();if (FALSE === ($idx = $this->_getFiFoIndex($cacheBlock['filePath']))) {$this->_cacheProp['freeBufSize'] -= $cacheBlock['streamSize'];$this->_fifoBuffer[] = $cacheBlock;} else {$this->_cacheProp['freeBufSize'] += $this->_fifoBuffer[$idx]['streamSize'] - $cacheBlock['streamSize'];$this->_fifoBuffer[$idx] = $cacheBlock;}
$status = TRUE;} while(FALSE);return $status;}
function _fifoFreeSpace($sizeOfNewData) {if ($this->_cacheProp['maxBufSize'] == -1) return TRUE;if ($this->_cacheProp['maxBufSize'] == 0) return FALSE;if ($this->_cacheProp['maxBufSize'] < $sizeOfNewData) return FALSE;$this->_fifoGarbageCollect();do {if ($this->_cacheProp['freeBufSize'] >= $sizeOfNewData) break;$this->_cacheProp['freeBufSize'] += $this->_fifoBuffer[0]['streamSize'];} while(array_shift($this->_fifoBuffer));return TRUE;}
function _fifoGarbageCollect() {$fiFoClone = $this->_fifoBuffer;$this->_fifoBuffer = array();$this->_cacheProp['freeBufSize'] = $this->_cacheProp['maxBufSize'];$fifoSize = sizeOf($fiFoClone);for ($i=0; $i<$fifoSize; $i++) {if ($fiFoClone[$i]['valid']) {$this->_fifoBuffer[] = $fiFoClone[$i];$this->_cacheProp['freeBufSize'] -= $fiFoClone[$i]['streamSize'];}
}
}
var $_determineFileNameHash = array();  function _determinePathToCache($filePath) {if (isSet($this->_determineFileNameHash[$filePath])) return $this->_determineFileNameHash[$filePath]; $tmpPos   = strrpos($filePath, '/');$basename = basename($filePath);$basenameLimited = (strlen($basename) > 60) ? substr($basename, 0, 60) : $basename;if (empty($this->_cacheProp['storeDir'])) {$path = substr($filePath, 0, $tmpPos) . '/_cache/';} else {$path = $this->_cacheProp['storeDir'];}
$md5 = md5($path . $filePath);$basename = $this->_cacheProp['verboseName'] ? $basenameLimited . '_' .  $md5 : $md5;$cacheFilePath = $path . $basename . '.cache';$this->_determineFileNameHash[$filePath] = $cacheFilePath;return $cacheFilePath;}
function _isCacheFileUptodate($filePath, $cacheFilePath) {$status = FALSE;$dbgInfo = '';do {if (!file_exists($filePath)) {Bs_Error::setError("'Up to date'-check problem. Missing origin-file [{$filePath}]. Maybe moved? End with 'out of date'.", "WARNING");$status = NULL;break; }
if (!file_exists($cacheFilePath)) {Bs_Error::setError("'Up to date'-check problem. Missing cache-file [{$cacheFilePath}]. Maybe deleted? End with 'out of date'.", "WARNING");$status = NULL;break; }
$originTime = filemtime($filePath);$cacheTime  = filemtime($cacheFilePath);if ($cacheTime <= $originTime) {$status = FALSE;break; }
$status = TRUE;  } while(FALSE);return $status;}
function _writeCacheFile($cacheFilePath, $cacheBlock) {static $bsFile;$status = FALSE;do {if (!file_exists($cacheFilePath)) {$subDir = substr($cacheFilePath, 0, strrpos($cacheFilePath, '/'));if (!file_exists($subDir))  {if(!mkdir($subDir, 0700)) {Bs_Error::setError("Failed to create cache sub-dir [{$subDir}].", "ERROR");break; }
}
}
if (empty($this->_bsFile)) {$this->_bsFile =& $bsFile;$this->_bsFile =& new Bs_File();}
if (!$this->_bsFile->exclusiveWrite(serialize($cacheBlock), $cacheFilePath)) {Bs_Error::setError("See previous error.", "ERROR");break;}
$status = TRUE;} while(FALSE);return $status;}
function _readCacheFile($cacheFilePath) {$status = FALSE;do {if (!($fp = @fopen($cacheFilePath, 'rb'))) {Bs_Error::setError("Failed to open cache-file [{$cacheFilePath}] for read.", "ERROR");break;}
$data = fread ($fp, filesize($cacheFilePath));@fclose($fp);if ($data == FALSE) {Bs_Error::setError("Failed to read cache-file [{$cacheFilePath}] for read.", "ERROR");break; }
$cacheBlock = unserialize($data);if ($this->_cacheVersion != $cacheBlock['cacheVersion']) {break; }
$status = TRUE;} while(FALSE);$xAtomObj->_tempContainer['xCacheFileUptodate'] = $status;if ($status) return $cacheBlock;return FALSE;}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_FileCache.class.php') {$cache =& new Bs_FileCache(3);$cache->setBufferSize('3k');$cache->setDir('r:/');$cache->setVerboseCacheNames(TRUE);$cache->setCacheLifeTime(1);$testData = serialize($cache);$cache->store('D:/tmp/diff.txt', $testData, FALSE, 3);$cache->store('D:/tmp/diff2.txt', $testData);dump($cache->_fifoBuffer);dump($cache->_cacheProp);$xxx = $cache->fetch('D:/tmp/diff.txt');   echo ($xxx === NULL ? "NULL<br>" : "DATA<br>");$xxx = $cache->fetch('D:/tmp/diff2.txt');  echo ($xxx === NULL ? "NULL<br>" : "DATA<br>");$xxx = $cache->fetch('D:/tmp/diff.txt');$xxx = $cache->fetch('D:/tmp/diff2.txt');$xxx = $cache->fetch('D:/tmp/diff.txt');$xxx = $cache->fetch('D:/tmp/diff2.txt');$xxx = $cache->fetch('D:/tmp/diff.txt');   echo ($xxx === NULL ? "NULL<br>" : "DATA<br>");sleep(2);$xxx = $cache->fetch('D:/tmp/diff.txt');   echo ($xxx === NULL ? "NULL<br>" : "DATA<br>");$xxx = $cache->fetch('D:/tmp/diff2.txt');  echo ($xxx === NULL ? "NULL<br>" : "DATA<br>");dump($cache->_fifoBuffer);dump($cache->_cacheProp);dump($xxx);}
?>