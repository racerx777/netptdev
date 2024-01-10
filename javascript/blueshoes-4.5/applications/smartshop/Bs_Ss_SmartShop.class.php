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
define('BS_SS_SMARTSHOP_VERSION',      '4.5.$Revision: 1.4 $');require_once($_SERVER['DOCUMENT_ROOT']     . '../global.conf.php');require_once($APP['path']['applications']  . 'smartshop/Bs_Ss_XClearingHouse.class.php');require_once($APP['path']['applications']  . 'smartshop/Bs_Ss_XAtom.class.php');class Bs_Ss_SmartShop extends Bs_Ss_XAtom {var $clearingHouse;var $_dataContainer = array(
'contact' => array(
'phone' => NULL, 
'fax' => NULL, 
'email' => NULL, 
), 
'delivery' => array(
), 
'payment' => array(
'minAmount' => 0, ), 
);var $language = 'en';var $settings;var $_failedReason;function Bs_Ss_SmartShop() {parent::Bs_Ss_XAtom(__FILE__); $this->clearingHouse =& new Bs_Ss_XClearingHouse($this);$this->_atomContainer['storageMedia']  = 'file';$this->_atomContainer['fileExtension'] = 'xShop';$this->_atomContainer['element']       = 'shop';}
function init($UID) {$status = parent::init($UID);if (!$status) {} else {$t = &$this->_dataContainer;$t['data']['source'] = 'file';$t['data']['path']   = $this->settings['dataDir'];unset($t);}
return $status;}
function getCategories($start, $sub=0) {}
function getCategory($UID) {$cat = $this->clearingHouse->getCategory($UID);$cat->loadProducts();return 'category';}
function getCategoryFormatted($UID, $format='overview') {do { $cat = $this->clearingHouse->getCategory($UID);if (!is_object($cat)) break;$catFormatted = $cat->getFormatted($format);if ($catFormatted === FALSE) {$this->_failedReason = $cat->getFailedReason();return FALSE;} else {return $catFormatted;}
} while (TRUE);return FALSE;}
function getBasket() {return 'basket';}
function getProductFormatted($UID, $format=NULL) {do { $prod = $this->clearingHouse->getProduct($UID);if (!is_object($prod)) break;return $prod->getFormatted($format);} while (TRUE);return FALSE;}
function getDesign($format) {if (isSet($this->_dataContainer['language'][$this->language]['design'][$format]['pre'])) {$pre           = $this->_dataContainer['language'][$this->language]['design'][$format]['pre'];$post          = $this->_dataContainer['language'][$this->language]['design'][$format]['post'];$line          = $this->_dataContainer['language'][$this->language]['design'][$format]['line'];$formelement   = @$this->_dataContainer['language'][$this->language]['design'][$format]['formelement'];$noformelement = @$this->_dataContainer['language'][$this->language]['design'][$format]['noformelement'];$diversityline = @$this->_dataContainer['language'][$this->language]['design'][$format]['diversityline'];$ret = array('pre'=>$pre, 'post'=>$post, 'line'=>$line, 'formelement'=>$formelement, 'noformelement'=>$noformelement, 'diversityline'=>$diversityline);return $ret;} elseif (isSet($this->_dataContainer['language']['']['design'][$format]['pre'])) {$pre           = $this->_dataContainer['language']['']['design'][$format]['pre'];$post          = $this->_dataContainer['language']['']['design'][$format]['post'];$line          = $this->_dataContainer['language']['']['design'][$format]['line'];$formelement   = @$this->_dataContainer['language']['']['design'][$format]['formelement'];$noformelement = @$this->_dataContainer['language']['']['design'][$format]['noformelement'];$diversityline = @$this->_dataContainer['language']['']['design'][$format]['diversityline'];$ret = array('pre'=>$pre, 'post'=>$post, 'line'=>$line, 'formelement'=>$formelement, 'noformelement'=>$noformelement, 'diversityline'=>$diversityline);return $ret;} else {switch ($format) {case 'overview':
$pre  = '<table border="0" width="100%" cellpadding="2" cellspacing="0">';$post = '</table>';$line = '
<tr>
<td rowspan="2">__IMAGE_OVERVIEW__</td>
<td><b>__CAPTION__</b></td>
<td>__PRICE_CURRENCY__ __PRICE_VALUE__</td>
<td rowspan="2"><nobr><input type="text" name="bs_form[items][__ID__][__ORDERCODE__]" size="2" maxlength="4"> <input type="image" name="addToCart" src="/_bsImages/applications/smartshop/cart_viola_open_small_right2left_round.gif" alt="add to cart"></nobr></td>
</tr>
<tr>
<td colspan="1">__DESCRIPTION__</td>
<td><font size="1" style="font-size:10px;">Code __ORDERCODE__</font></td>
</tr>
<tr>
<td colspan="100%"><img src="/img/dotBlue.gif" width="100%" height="1"></td>
</tr>
';$diversityline = $line;return array('pre'=>$pre, 'post'=>$post, 'line'=>$line, 'diversityline'=>$diversityline);case 'detail':
$pre  = '<table border="0" width="100%" cellpadding="2" cellspacing="0">';$post = '</table>';$line = '
<tr>
<td>detail view</td>
</tr>
';return array($pre, $post, $line);case 'basket':
$pre  = '<table border="0" width="100%" cellpadding="2" cellspacing="0">';$post = '</table>';$line = '
<tr>
<td>__IMAGE_OVERVIEW__</td>
<td><b>__CAPTION__</b><br>__DESCRIPTION__</td>
<td><nobr>__PRICE_CURRENCY__ __PRICE_VALUE__</nobr><br><nobr><font size="1" style="font-size:10px;">Code __ORDERCODE__</font></nobr></td>
<td><nobr><input type="text" name="bs_form[items][__ID__][__ORDERCODE__]" size="2" maxlength="4" value="__AMOUNT__"> <input type="image" name="addToCart" src="/_bsImages/applications/smartshop/cart/viola/bgWhite_25x25_rightToLeft_roundWheel.gif" alt="add to cart"></nobr></td>
</tr>
<tr>
<td colspan="4"><img src="/img/dotBlue.gif" width="100%" height="1"></td>
</tr>
';$diversityline = '
<tr>
<td>__IMAGE_OVERVIEW__</td>
<td><b>__CAPTION__ __CAPTIONADD__</b><br>__DESCRIPTION__ __DESCRIPTIONADD__</td>
<td><nobr>__PRICE_CURRENCY__ __PRICE_VALUE__</nobr><br><nobr><font size="1" style="font-size:10px;">Code __ORDERCODE__</font></nobr></td>
<td><nobr><input type="text" name="bs_form[items][__ID__][__ORDERCODE__]" size="2" maxlength="4" value="__AMOUNT__"> <input type="image" name="addToCart" src="/_bsImages/applications/smartshop/cart/viola/bgWhite_25x25_rightToLeft_roundWheel.gif" alt="add to cart"></nobr></td>
</tr>
<tr>
<td colspan="4"><img src="/img/dotBlue.gif" width="100%" height="1"></td>
</tr>
';return array('pre'=>$pre, 'post'=>$post, 'line'=>$line, 'diversityline'=>$diversityline);case 'basketUneditable':
$pre  = '<table border="0" width="100%" cellpadding="2" cellspacing="0">';$post = '</table>';$line = '
<tr>
<td rowspan="2">__IMAGE_OVERVIEW__</td>
<td><b>__CAPTION__</b></td>
<td>__PRICE_CURRENCY__ __PRICE_VALUE__</td>
<td rowspan="2"><input type="hidden" name="bs_form[items][__ID__][__ORDERCODE__]" size="2" maxlength="4" value="__AMOUNT__">__AMOUNT__</td>
</tr>
<tr>
<td colspan="1">__DESCRIPTION__</td>
<td><font size="1" style="font-size:10px;">Code __ORDERCODE__</font></td>
</tr>
<tr>
<td colspan="100%"><img src="/img/dotBlue.gif" width="100%" height="1"></td>
</tr>
';$diversityline = $line;return array('pre'=>$pre, 'post'=>$post, 'line'=>$line, 'diversityline'=>$diversityline);case 'smallBasket':
$pre  = '<table border="0" cellpadding="4" cellspacing="0">';$post = '</table>';$line = '
<tr>
<td align="right" valign="top">__AMOUNT__</td>
<td align="left"  valign="top">__CAPTION__</td>
</tr>
';$diversityline = $line;return array('pre'=>$pre, 'post'=>$post, 'line'=>$line, 'diversityline'=>$diversityline);default:
return 'murphy';}
}
}
function getFailedReason() {return $this->_failedReason;}
}
?>