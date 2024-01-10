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
if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['lib']  . 'XPath/XPath.class.php');require_once($APP['path']['core'] . 'file/Bs_FileCache.class.php');class Bs_HnXmlHandler extends Bs_Object {var $_xPath;var $_navData;var $_fileCache;function Bs_HnXmlHandler() {parent::Bs_Object();$xmlOptions   = array(XML_OPTION_CASE_FOLDING => TRUE, XML_OPTION_SKIP_WHITE => TRUE);$this->_xPath =& new XPath(FALSE, $xmlOptions);$this->_xPath->setVerbose(FALSE);$this->_fileCache =& new Bs_FileCache();$this->_fileCache->setDir(getTmp());            $this->_fileCache->setBufferSize('1000k');      $this->_fileCache->setVerboseCacheNames(TRUE);  }
function getNavData() {if (!isSet($this->_navData)) return FALSE;return $this->_navData;}
function getAllUrls($arr=NULL) {$ret = array();if (is_null($arr)) $arr = $this->_navData;foreach ($arr as $navArr) {$ret[] = $navArr['url'];if (!empty($navArr['children']) && is_array($navArr['children'])) {$ret = array_merge($ret, $this->getAllUrls($navArr['children']));}
}
return array_unique($ret);}
function addNavElement($otherNode, $relation, $caption, $url) {return TRUE;}
function loadDataByXmlFile($fileFullPath) {$this->_xPath->reset();unset($this->_navData);if ($this->_loadFromCache($fileFullPath)) return;if (!$this->_xPath->importFromFile($fileFullPath)) {$eMsg = $this->_xPath->getLastError();Bs_Error::setError($msg, 'ERROR', __LINE__, 'loadDataByXmlFile', __FILE__);return FALSE;}
$this->_loadXmlData();$this->_storeToCache($fileFullPath);return TRUE;}
function loadDataByXmlString($str) {}
function _loadXmlData($_path=NULL, $firstCall=TRUE) {if (is_null($_path)) $_path = '/BLUESHOES[1]/BS:NAVIGATION[1]/BS:LINK';$navData = array();if ($xpvBlock = $this->_xPath->match($_path)) {foreach($xpvBlock as $xp) {$navElement = array();$navElement['caption']  = $this->_xPath->getData($xp . '/BS:CAPTION[1]');$navElement['url']      = $this->_xPath->getData($xp . '/BS:URL[1]');$navElement['key']      = $this->_xPath->getData($xp . '/BS:KEY[1]');$navElement['target']   = $this->_xPath->getData($xp . '/BS:TARGET[1]');$childrenXp = $xp . '/BS:CHILDREN[1]/BS:LINK';if ($children = $this->_xPath->match($childrenXp)) { $navElement['children'] = $this->_loadXmlData($childrenXp, FALSE);}
$navData[] = $navElement;}
}
if ($firstCall) {$this->_navData = $navData;} else {return $navData;}
}
function _loadFromCache($fileFullPath) {$status = FALSE;do {if (!($dataStream = $this->_fileCache->fetch($fileFullPath))) {break; }
$tmp = @unserialize($dataStream);if (empty($tmp)) {break; }
$this->_navData = $tmp;$status = TRUE;} while(FALSE);return $status;}
function _storeToCache($fileFullPath) {$status = FALSE;do {$tmp = $this->_navData;if (FALSE === $this->_fileCache->store($fileFullPath, serialize($tmp))) {break; }
$status = TRUE;} while(FALSE);return $status;}
}
?>