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
define('BS_SS_XCATEGORY_VERSION',      '4.5.$Revision: 1.3 $');require_once($_SERVER['DOCUMENT_ROOT']     . '../global.conf.php');require_once($APP['path']['applications']  . 'smartshop/Bs_Ss_XAtom.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['XPath']     . 'XPath.class.php');class Bs_Ss_XCategory extends Bs_Ss_XAtom {var $_shop;var $_dir;var $_dataContainer = array(
'parentId'      => NULL, 
'orderId'       => NULL, 
'language'      => array(
), 
);var $_dataPath;var $_products = array();var $_productsLoaded = FALSE;var $_failedReason;function Bs_Ss_XCategory(&$shop) {parent::Bs_Ss_XAtom(__FILE__); $this->_shop = &$shop;$this->_atomContainer['storageMedia']  = 'file';$this->_atomContainer['fileExtension'] = 'xCat';$this->_atomContainer['element']       = 'category';}
function init($UID) {$this->_atomContainer['UID'] = $UID;$this->_dataPath = $UID . '/';return TRUE;}
function loadProducts($force=FALSE) {if (!$this->_productsLoaded || $force) {unset($this->_products); $this->_products = array();$this->_productsLoaded = FALSE;$this->_dir =& new Bs_Dir();$prodPath = $this->_shop->_dataContainer['data']['path'] . $this->_dataPath;$this->_dir->setFullPath($prodPath);$params = array(
'regEx' => '\.xProd$', 
'depth' => 0, 
'fileDirLink' => array('file'=>TRUE, 'dir'=>FALSE, 'filelink'=>TRUE, 'dirlink'=>FALSE), 
'returnType' => 'subpath'
);$list = &$this->_dir->getFileList($params);if ((is_array($list)) && !empty($list)) {foreach($list as $key => $subPath) {$prodUID = $this->_dataPath . $subPath;unset($prod);$prod = $this->_shop->clearingHouse->getProduct($prodUID);if (is_object($prod)) {$this->_products[$prodUID] = $prod;}
}
}
$this->_productsLoaded = TRUE;}
return TRUE;}
function _parseFormattedLine($xPath, $prodObj) {if ($xpvResult = $xPath->match($basePath . '//BS:IMAGE')) {foreach($xpvResult as $key => $value) {$prop = $xPath->getAttributes($value);if (!$prop || (empty($prop['NAME']))) {continue;}
$result = $prodObj->getImageFormatted($prop['NAME']);if ($result === FALSE) $result = '';$xPath->replaceChildByData($value, $result);}
}
if ($xpvResult = $xPath->match($basePath . '//BS:LANGUAGE')) {foreach($xpvResult as $key => $value) {$prop = $xPath->getAttributes($value);if (!$prop || (empty($prop['NAME']))) {continue;}
$result = $prodObj->getLanguageVar($prop['NAME']);if ($result === FALSE) $result = '';$xPath->replaceChildByData($value, $result);}
}
$hasDiversity = $prodObj->hasDiversity();if ($xpvResult = $xPath->match($basePath . '//BS:PRICE')) {foreach($xpvResult as $key => $value) {$isOk = FALSE;do { if ($hasDiversity) break;$result = $prodObj->getPrice(NULL, FALSE);if ($result === FALSE) break;$result = 'CHF ' . $result;$isOk = TRUE;} while (FALSE);if (!$isOk) {$result = 'blah';}
$xPath->replaceChildByData($value, $result);}
}
if ($xpvResult = $xPath->match($basePath . '//BS:BASKET')) {foreach($xpvResult as $key => $value) {$isOk = FALSE;do { if ($hasDiversity) break;$result = $this->getBasketLine();if ($result === FALSE) break;$isOk = TRUE;} while (FALSE);if (!$isOk) {$result = 'blah';}
$xPath->replaceChildByData($value, $result);}
}
$line = $xPath->exportAsXml();$line = str_replace('__UID__',       $prodObj->_atomContainer['UID'],               $line);$line = str_replace('__ORDERCODE__', $prodObj->_dataContainer['base']['orderCode'], $line);return $line;}
function getBasketLine() {$line = '<nobr><input type="text" name="bs_form[items][__UID__][__ORDERCODE__]" size="2" maxlength="3"> <input type="image" name="addToCart" src="/bf/image/coffeshop/cart_small_lightgray.gif" alt="In den Warenkorb"></nobr>';return $line;}
function getDesign($format) {return $this->_shop->getDesign($format);}
function getFormatted($format='overview', $onlyActive=TRUE) {$cacheDir      = $this->_shop->_dataContainer['data']['path'] . '_cache/';$cacheFullPath = $cacheDir . 'category_' . str_replace('/', '_', $this->_atomContainer['UID']) . '_' . $format . '_' . (int)$onlyActive;$cache =& new Bs_FileCache(3);$cache->setDir($cacheDir);$cache->fakeOrigFile = TRUE;$ret = $cache->fetch($cacheFullPath);if (($ret !== FALSE) && !is_null($ret)) {return $ret;} else {$ret = '';$this->loadProducts();if (empty($this->_products)) {$ret .= 'No products available in this category.'; } else {$ret .= '<form action="/en/shop/basket/" method="post">';$ret .= '<input type="hidden" name="bs_form[do]" value="add">';$design = $this->getDesign($format);$ret .= $design['pre'];while (list($k) = each($this->_products)) {$myLine = $this->_products[$k]->dirtyFormatting($design);$ret .= $myLine;}
$ret .= $design['post'];$ret .= '</form>';}
return $ret;}
}
function getFailedReason() {return $this->_failedReason;}
}
?>