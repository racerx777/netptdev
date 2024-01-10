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
define('BS_SS_XPSTORAGE_VERSION',      '4.5.$Revision: 1.3 $');require_once($_SERVER['DOCUMENT_ROOT']     . '../global.conf.php');require_once($APP['path']['XPath']     . 'XPath.class.php');require_once($APP['path']['core'] . 'xml/Bs_XmlParser.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');require_once($APP['path']['core'] . 'file/Bs_FileCache.class.php');require_once($APP['path']['applications']  . 'smartshop/Bs_Ss_XClearingHouse.class.php');require_once($APP['path']['applications']  . 'smartshop/Bs_Ss_XProduct.class.php');require_once($APP['path']['applications']  . 'smartshop/Bs_Ss_XCategory.class.php');if (!defined('XT_NONE')) {define ('XT_NONE',              0);define ('XT_ERR_INIT',          1);   define ('XT_ERR_ASSEBLE',       2);   define ('XT_ERR_DETERMINE',     3);   define ('XT_ERR_NOMATCH',       4);   define ('XT_ERR_NODIR',         5);   define ('XT_ERR_GETFILE',       6);   define ('XT_ERR_UNMATCHEDBLOCEBO', 7);define ('XT_ERR_XPATHPARS',     8);   define ('XT_ERR_XPATHREPLACE',  9);   define ('XT_ERR_XPATHEXPORT',  10);   define ('XT_INFO_NOTALIVE', 1000);    define ('XT_INFO_INVALID', 1001);     define ('XT_INFO_NOACCESS', 1002);    define ('XT_INFO_CACHEFAILED', 1003); }
class Bs_Ss_XStorage extends Bs_Object {var $_APP;var $_xPath = NULL;var $_bsFile;var $_fileCache;var $dataDir;function Bs_Ss_XStorage() {parent::Bs_Object(); $this->_APP            = &$GLOBALS['APP'];static $_xPath = NULL;  $this->_xPath =& $_xPath; $this->_fileCache =& new Bs_FileCache(0);}
function loadXAtom(&$xAtomObj) {$status = FALSE;$UID          = &$xAtomObj->_atomContainer['UID'];$storageMedia = &$xAtomObj->_atomContainer['storageMedia'];if (empty($storageMedia)) {if (is_numeric($UID)) {$storageMedia = 'db';} else {$storageMedia = 'file';}
}
$tryBlock = 1;do { if ($storageMedia === 'db') {} else {if ($this->_loadFromCache($xAtomObj)){$status = TRUE; break; }
if (!$this->_initXPath($xAtomObj)) {$this->_failedReason = $xAtomObj->_failedReason;break $tryBlock; }
if (!$prop = $this->_xPath->getAttributes('/BLUESHOES[1]')) {$xAtomObj->_failedReason = "[".basename(__FILE__).":".__LINE__."] ". $this->_xPath->getLastError();break ; }
if (!empty($prop['VERSION']))   $xAtomObj->_atomContainer['bsHead']['version'] = $prop['VERSION'];$element = strToUpper($xAtomObj->_atomContainer['element']);$absXpath = '/BLUESHOES[1]/BS:'.$element.'[1]/BS:ALIVE[1]';if (!$prop = $this->_xPath->getAttributes($absXpath)) {} else {$xAtomObj->_atomContainer['alive']['isDeactivated'] = isTrue($prop['ISDEACTIVATED']);if (!empty($prop['STARTDATETIME'])) {$xAtomObj->_atomContainer['alive']['startDatetime'] = $prop['STARTDATETIME'];}
if (!empty($prop['ENDDATETIME'])) {$xAtomObj->_atomContainer['alive']['endDatetime'] = $prop['ENDDATETIME'];}
}
switch ($xAtomObj->_atomContainer['element']) {case 'product'  : $status = $this->_loadProduct($xAtomObj);  break;case 'category' : $status = $this->_loadCategory($xAtomObj); break;case 'shop'     : $status = $this->_loadShop($xAtomObj);     break;default :
$this->_failedReason = 'non-existing atom-element: ' . $xAtomObj->_dataContainer['element'];$status = FALSE;}
if (!$status) break;$this->_loadToCache($xAtomObj);$status = TRUE;}
} while (FALSE);  if ($status) {$xAtomObj->_atomContainer['isDataLoaded'] = TRUE;} else {$this->_failedReason = $xAtomObj->_failedReason;}
return $status;}
function _loadProduct(&$xAtomObj) {$status = FALSE;$tryBlock = 1;do { if (!$prop = $this->_xPath->getAttributes('/BLUESHOES[1]/BS:PRODUCT[1]/BS:BASE[1]')) {$xAtomObj->_failedReason = "[".basename(__FILE__).":".__LINE__."] ". $this->_xPath->getLastError();break $tryBlock; }
if (!empty($prop['ORDERCODE'])) $xAtomObj->_dataContainer['base']['orderCode'] = trim($prop['ORDERCODE']);if (!$prop = $this->_xPath->getAttributes('/BLUESHOES[1]/BS:PRODUCT[1]/BS:BASE[1]/BS:ORDER[1]')) {$xAtomObj->_failedReason = "[".basename(__FILE__).":".__LINE__."] ". $this->_xPath->getLastError();break $tryBlock; }
if (!empty($prop['MIN']))     $xAtomObj->_dataContainer['order']['min']   =  (int)trim($prop['MIN']);if (!empty($prop['STEPS']))   $xAtomObj->_dataContainer['order']['steps'] =  (int)trim($prop['STEPS']);$lang = $this->_loadXmlLanguage($xAtomObj, '/BLUESHOES[1]/BS:PRODUCT[1]/BS:BASE[1]');if (is_array($lang)) {$xAtomObj->_dataContainer['base']['language'] = $lang;} else {echo $xAtomObj->getFailedReason();}
$price = $this->_loadProductPrice($xAtomObj, '/BLUESHOES[1]/BS:PRODUCT[1]/BS:BASE[1]');if (is_array($price)) {$xAtomObj->_dataContainer['base']['price'] = $price;}
$image = $this->_loadXmlImage($xAtomObj, '/BLUESHOES[1]/BS:PRODUCT[1]/BS:BASE[1]');if (is_array($image)) {$xAtomObj->_dataContainer['base']['image'] = $image;}
if (!$xpvResult = $this->_xPath->match('/BLUESHOES[1]/BS:PRODUCT[1]/BS:DIVERSITY')) {} else {$tryBlock++;foreach($xpvResult as $key => $value) {if (!$prop = $this->_xPath->getAttributes($value)) {$xAtomObj->_failedReason = "[".basename(__FILE__).":".__LINE__."] ". $this->_xPath->getLastError();break $tryBlock; }
unset($newDiversity);$newDiversity = array();$xAtomObj->_dataContainer['diversity'][] = &$newDiversity;if (!empty($prop['ORDERCODE'])) $newDiversity['orderCode'] = trim($prop['ORDERCODE']);$lang = $this->_loadXmlLanguage($xAtomObj, $value);if (is_array($lang)) {$newDiversity['language'] = $lang;}
$price = $this->_loadProductPrice($xAtomObj, $value);if (is_array($price)) {$newDiversity['price'] = $price;}
$image = $this->_loadXmlImage($xAtomObj, $value);if (is_array($image)) {$newDiversity['image'] = $image;}
}
$tryBlock--;}
$status = TRUE; } while(FALSE); return $status;}
function _loadProductPrice(&$xAtomObj, $basePath) {$ret = array();$tryBlock = 1;do {if (!$prop = $this->_xPath->getAttributes($basePath . '/BS:PRICE[1]')) {break $tryBlock; }
$t = $xAtomObj->_emptyPriceProp;if (!empty($prop['VALUE']))        $t['value']        = trim($prop['VALUE']);if (!empty($prop['CURRENCY']))     $t['currency']     = trim($prop['CURRENCY']);if (!empty($prop['DENOMINATION'])) $t['denomination'] = trim($prop['DENOMINATION']);return $t;} while (FALSE);return FALSE;}
function _loadXmlImage(&$xAtomObj, $basePath) {$ret = array();$tryBlock = 1;do {if (!$xpvResult = $this->_xPath->match($basePath . '/BS:IMAGE')) {$xAtomObj->_failedReason = "[".basename(__FILE__).":".__LINE__."] ". $this->_xPath->getLastError();break $tryBlock; }
$tryBlock++;foreach($xpvResult as $key => $value) {if (!$prop = $this->_xPath->getAttributes($value)) {$xAtomObj->_failedReason = "[".basename(__FILE__).":".__LINE__."] ". $this->_xPath->getLastError();break $tryBlock; }
if (!empty($prop['NAME'])) {unset($t);$t = $xAtomObj->_emptyImageProp;if (!empty($prop['SRC']))    $t['src']    = trim($prop['SRC']);if (!empty($prop['WIDTH']))  $t['width']  = trim($prop['WIDTH']);if (!empty($prop['HEIGHT'])) $t['height'] = trim($prop['HEIGHT']);if (!empty($prop['ALT']))    $t['alt']    = trim($prop['ALT']);$ret[trim($prop['NAME'])] = $t;}
}
$tryBlock--;return $ret;} while (FALSE);return FALSE;}
function _loadXmlLanguage(&$xAtomObj, $basePath) {$ret = array();$tryBlock = 1;do {if (!$xpvResult = $this->_xPath->match($basePath . '/BS:LANGUAGE')) {$xAtomObj->_failedReason = "[".basename(__FILE__).":".__LINE__."] ". $this->_xPath->getLastError();break $tryBlock; }
$tryBlock++;foreach($xpvResult as $key => $value) {if (!$prop = $this->_xPath->getAttributes($value)) {$xAtomObj->_failedReason = "[".basename(__FILE__).":".__LINE__."] ". $this->_xPath->getLastError();break $tryBlock; }
if (!empty($prop['VALUE'])) {unset($langValue);$langValue = trim($prop['VALUE']);$ret[$langValue] = array();if (!$xpvResult2 = $this->_xPath->match($value . '/descendant::*')) {$xAtomObj->_failedReason = "[".basename(__FILE__).":".__LINE__."] ". $this->_xPath->getLastError();break $tryBlock; }
$tryBlock++;foreach($xpvResult2 as $key2 => $value2) {if (!$prop2 = $this->_xPath->getAttributes($value2)) {$xAtomObj->_failedReason = "[".basename(__FILE__).":".__LINE__."] ". $this->_xPath->getLastError();break $tryBlock; }
if (!empty($prop2['NAME'])) {$value = $this->_xPath->getData($value2);$ret[$langValue][strToLower(trim($prop2['NAME']))] = str_replace(array('<![CDATA[', ']]>'), "", $value);}
}
$tryBlock--;}
}
$tryBlock--;return $ret;} while (FALSE);return FALSE;}
function _loadCategory(&$xAtomObj) {}
function _loadShop(&$xAtomObj) {$status = FALSE;$tryBlock = 1;do { if ($prop = $this->_xPath->getAttributes('/BLUESHOES[1]/BS:SHOP[1]/BS:CONTACT[1]')) {if (!empty($prop['PHONE']))   $xAtomObj->_dataContainer['contact']['phone']  =  trim($prop['PHONE']);if (!empty($prop['FAX']))     $xAtomObj->_dataContainer['contact']['fax']    =  trim($prop['FAX']);if (!empty($prop['EMAIL']))   $xAtomObj->_dataContainer['contact']['email']  =  trim($prop['EMAIL']);}
$prop = $this->_xPath->getAttributes('/BLUESHOES[1]/BS:SHOP[1]/BS:DELIVERY[1]');if ($prop !== FALSE) {if ($prop = $this->_xPath->getAttributes('/BLUESHOES[1]/BS:SHOP[1]/BS:DELIVERY[1]/BS:MAIL[1]')) {$done = FALSE;do {if (!isTrue(@$prop['AUTOCALC'])) break;$xpvResult = $this->_xPath->match('/BLUESHOES[1]/BS:SHOP[1]/BS:DELIVERY[1]/BS:MAIL[1]/BS:POSTAGE');if (!$xpvResult) break;$postage = array();foreach($xpvResult as $key => $value) {if (!$prop = $this->_xPath->getAttributes($value)) {continue; }
unset($t);$t['aboveValue'] = (!empty($prop['ABOVEVALUE'])) ? (double)trim($prop['ABOVEVALUE']) : 0;$t['postage']    = (!empty($prop['POSTAGE']))    ? (double)trim($prop['POSTAGE'])    : 0;$t['fee']        = (!empty($prop['FEE']))        ? (double)trim($prop['FEE'])        : 0;$postage[] = $t;}
if (empty($postage)) break;$xAtomObj->_dataContainer['delivery']['mail'] = array(
'autoCalc' => TRUE, 
'postage'  => $postage
);$done = TRUE;} while (FALSE);if (!$done) $xAtomObj->_dataContainer['delivery']['mail'] = array('autoCalc' => FALSE);}
}
$prop = $this->_xPath->getAttributes('/BLUESHOES[1]/BS:SHOP[1]/BS:PAYMENT[1]');if ($prop !== FALSE) {if (!empty($prop['MINAMOUNT']) && ($prop['MINAMOUNT'] > 0)) {$xAtomObj->_dataContainer['payment']['minAmount'] = (double)trim($prop['MINAMOUNT']);}
$prop = $this->_xPath->getAttributes('/BLUESHOES[1]/BS:SHOP[1]/BS:PAYMENT[1]/BS:INVOICE[1]');if ($prop !== FALSE) {$done = FALSE;do {$xpvResult = $this->_xPath->match('/BLUESHOES[1]/BS:SHOP[1]/BS:PAYMENT[1]/BS:INVOICE[1]/BS:GROUP');if (!$xpvResult) break;$group = array();foreach($xpvResult as $key => $value) {if (!$prop = $this->_xPath->getAttributes($value)) {continue; }
unset($t); unset($name);$name           = (!empty($prop['NAME']))      ? trim($prop['NAME'])              : 'default';$t['minAmount'] = (!empty($prop['MINAMOUNT'])) ? (double)trim($prop['MINAMOUNT']) : 0;$t['maxAmount'] = (!empty($prop['MAXAMOUNT'])) ? (double)trim($prop['MAXAMOUNT']) : 0;$group[$name] = $t;}
if (empty($group)) break;$xAtomObj->_dataContainer['payment']['invoice'] = $group;$done = TRUE;} while (FALSE);if (!$done) $xAtomObj->_dataContainer['payment']['invoice'] = array();}
}
$xpvResult = $this->_xPath->match('/BLUESHOES[1]/BS:SHOP[1]/BS:LANGUAGE');if ($xpvResult) {foreach($xpvResult as $key => $value) {if (!$prop = $this->_xPath->getAttributes($value)) {$lang = ''; } else {$lang = trim($prop['VALUE']);}
$xpvResult2 = $this->_xPath->match($value . '/BS:DESIGN');if ($xpvResult2) {foreach($xpvResult2 as $key2 => $value2) {do {if (!$prop2 = $this->_xPath->getAttributes($value2)) {continue; }
$name = trim($prop2['NAME']);if (empty($name)) break;if (!$pre           = $this->_xPath->getData($value2 . '/BS:PRE'))  break;if (!$post          = $this->_xPath->getData($value2 . '/BS:POST')) break;if (!$line          = $this->_xPath->getData($value2 . '/BS:LINE')) break;$diversityline = $this->_xPath->getData($value2 . '/BS:DIVERSITYLINE');$formelement   = $this->_xPath->getData($value2 . '/BS:FORMELEMENT');$noformelement = $this->_xPath->getData($value2 . '/BS:NOFORMELEMENT');$xAtomObj->_dataContainer['language'][$lang]['design'][$name]['pre']           = $this->removeCdataTags(trim($pre));$xAtomObj->_dataContainer['language'][$lang]['design'][$name]['post']          = $this->removeCdataTags(trim($post));$xAtomObj->_dataContainer['language'][$lang]['design'][$name]['line']          = $this->removeCdataTags(trim($line));if ($diversityline != FALSE) $xAtomObj->_dataContainer['language'][$lang]['design'][$name]['diversityline'] = $this->removeCdataTags(trim($diversityline));if ($formelement   != FALSE) $xAtomObj->_dataContainer['language'][$lang]['design'][$name]['formelement']   = $this->removeCdataTags(trim($formelement));if ($noformelement != FALSE) $xAtomObj->_dataContainer['language'][$lang]['design'][$name]['noformelement'] = $this->removeCdataTags(trim($noformelement));} while (FALSE);}
}
}
}
} while (FALSE);return TRUE;}
function persist(&$xAtomObj) {$status = FALSE;if (empty($storageMedia)) {if (is_numeric($xAtomObj->_atomContainer['UID']) OR ($this->_APP['system']['storageMedia'] === 'db') ) {$storageMedia = 'db';} else {$storageMedia = 'file';}
}
do { if (!$xAtomObj->_atomContainer['isDataLoaded']) {$err = "No data loaded";break;}
if ($storageMedia === 'db') {}
else {$err = '';if (empty($xAtomObj->_atomContainer['cache']['absPathToXml'])) {$err = "empty path to XML";break;}
if (($out = $xAtomObj->toXml()) === FALSE) {$err = "toXML failed";break;}
$pathFile = $xAtomObj->_atomContainer['cache']['absPathToXml'];if (!is_object($this->_bsFile)) {static $bsFile;$this->_bsFile = &$bsFile;$this->_bsFile =& new Bs_File();}
if (!$this->_bsFile->exclusiveWrite($out, $pathFile)) {$err = "exclusiveWrite failed";break;}
$status = TRUE;} } while (FALSE);  return $status;}
function _loadFromCache(&$xAtomObj) {$status = FALSE;do {$fullPath = $this->dataDir . $xAtomObj->_atomContainer['UID']; if (FALSE === ($dataStream = $this->_fileCache->fetch($fullPath))) {if ($xAtomObj->fullTrace) {$errStr = $this->_fileCache->getLastError();if (!empty($errStr)) {$xAtomObj->traceMsg(XT_INFO_CACHEFAILED, $errStr, __LINE__, "_loadFromCache", __FILE__);}
}
break; }
$tmp = @unserialize($dataStream);if (empty($tmp)) {if ($xAtomObj->fullTrace) $xAtomObj->traceMsg(XT_INFO_CACHEFAILED, "Cache data was empty or invalid", __LINE__, "_loadFromCache", __FILE__);break; }
$xAtomObj->_atomContainer = $tmp['atom'];$xAtomObj->_dataContainer = $tmp['data'];$status = TRUE;} while(FALSE);return $status;}
function _loadToCache(&$xAtomObj) {$status = FALSE;do {$tmp = array(
'atom' => $xAtomObj->_atomContainer,
'data' => $xAtomObj->_dataContainer
);$fullPath = $this->dataDir . $xAtomObj->_atomContainer['UID']; if (FALSE === $this->_fileCache->store($fullPath, serialize($tmp))) {if ($xAtomObj->fullTrace) {$errStr = $this->_fileCache->getLastError();$xAtomObj->traceMsg(XT_INFO_CACHEFAILED, $errStr, __LINE__, "_loadToCache", __FILE__);}
break; }
$status = TRUE;} while(FALSE);return $status;}
function _initXPath(&$xAtomObj) {$status = FALSE;do { $xmlPathFile = $this->dataDir . $xAtomObj->_atomContainer['UID']; if (!file_exists($xmlPathFile)) {$xAtomObj->_failedReason = "file does not exist: '{$xmlPathFile}'.";break; }
if (!is_readable($xmlPathFile)) {$xAtomObj->_failedReason = "file is not readable: '{$xmlPathFile}'.";break; }
if (!is_object($this->_xPath)) {$xmlOpt = array(XML_OPTION_CASE_FOLDING=>TRUE, XML_OPTION_SKIP_WHITE=>TRUE);$this->_xPath =& new XPath(NULL, $xmlOpt);$this->_xPath->setVerbose(FALSE);}
$this->_xPath->reset();if (!$this->_xPath->importFromFile($xmlPathFile)) {$xAtomObj->_failedReason = "failed importing file into xPath: '{$xmlPathFile}'. ";$xAtomObj->_failedReason .= $this->_xPath->getLastError();break; }
$status = TRUE;} while(FALSE);return $status;}
function removeCdataTags($value) {return str_replace(array('<![CDATA[', ']]>'), '', $value);}
}
function fuck($t, $pre="") {while (list($k) = each($t)) {echo $pre . $t[$k]['name'] . '<br>';if (isSet($t[$k]['childNodes'])) {fuck($t[$k]['childNodes'], $pre . '&nbsp;&nbsp;&nbsp;&nbsp;');}
}
}
?>