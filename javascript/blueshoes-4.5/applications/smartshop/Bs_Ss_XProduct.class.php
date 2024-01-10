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
define('BS_SS_XPRODUCT_VERSION',      '4.5.$Revision: 1.4 $');require_once($_SERVER['DOCUMENT_ROOT']     . '../global.conf.php');require_once($APP['path']['applications']  . 'smartshop/Bs_Ss_XAtom.class.php');class Bs_Ss_XProduct extends Bs_Ss_XAtom {var $_shop;var $_dataContainer = array();var $_emptyImageProp = array(
'src'    => '', 
'width'  => NULL, 
'height' => NULL, 
'alt'    => NULL
);var $_emptySeealsoProp = array(
'type'  => NULL, 'UID'   => NULL, );var $_emptyPriceProp = array(
'value'        => NULL, 
'currency'     => NULL, 
'denomination' => NULL, 
);var $_fileFullPath;function Bs_Ss_XProduct(&$shop) {parent::Bs_Ss_XAtom(__FILE__); $this->_shop = &$shop;$this->_xStorage->dataDir = &$this->_shop->settings['dataDir'];$this->_atomContainer['storageMedia']  = 'file';$this->_atomContainer['fileExtension'] = 'xProd';$this->_atomContainer['element']       = 'product';}
function init($UID) {$status = parent::init($UID);$this->_xStorage->dataDir = &$this->_shop->settings['dataDir'];if (!$status) {$t = $this->getFailedReason();}
return $status;}
function hasDiversity() {return (bool)(!empty($this->_dataContainer['diversity']));}
function getOrdercodes() {$ret = array();if (isSet($this->_dataContainer['base']['orderCode'])) {$ret[] = $this->_dataContainer['base']['orderCode'];}
if (!empty($this->_dataContainer['diversity'])) {reset($this->_dataContainer['diversity']);while (list($k) = each($this->_dataContainer['diversity'])) {if (isSet($this->_dataContainer['diversity'][$k]['orderCode'])) {$ret[] = $this->_dataContainer['diversity'][$k]['orderCode'];}
}
}
return $ret;}
function getPrice($orderCode=NULL, $fallBack=TRUE) {return $this->_getData("['price']['value']", $orderCode, $fallBack);}
function getCurrency($orderCode=NULL, $fallBack=TRUE) {return $this->_getData("['price']['currency']", $orderCode, $fallBack);}
function getCaption($orderCode=NULL, $fallBack=TRUE) {return $this->_getData("['language']['de']['caption']", $orderCode, $fallBack);}
function getCaptionAdd($orderCode=NULL, $fallBack=TRUE) {return $this->_getData("['language']['de']['captionadd']", $orderCode, $fallBack);}
function getFullCaption($orderCode=NULL, $fallBack=TRUE) {$one = $this->getCaption($orderCode,    $fallBack);$two = $this->getCaptionAdd($orderCode, $fallBack);if (!empty($two)) return $one . ' ' . $two;return $one;}
function getDescription($orderCode=NULL, $fallBack=TRUE) {return $this->_getData("['language']['de']['description']", $orderCode, $fallBack);}
function getDescriptionAdd($orderCode=NULL, $fallBack=TRUE) {return $this->_getData("['language']['de']['descriptionadd']", $orderCode, $fallBack);}
function getOrdercode($orderCode=NULL, $fallBack=TRUE) {return $this->_getData("['orderCode']", $orderCode, $fallBack);}
function _getData($var, $orderCode, $fallBack) {if (!is_null($orderCode)) {$diversityData = $this->_getDiversityData($orderCode);if (is_array($diversityData)) {$tmpVar = @eval("return \$diversityData{$var};");if (isSet($tmpVar)) { return $tmpVar;}
if (!$fallBack) return FALSE;}
}
$tmpVar = @eval("return \$this->_dataContainer['base']{$var};");if (isSet($tmpVar)) { return $tmpVar;}
return FALSE;}
function _getDiversityData($orderCode) {if (!empty($this->_dataContainer['diversity'])) {reset($this->_dataContainer['diversity']);while (list($k) = each($this->_dataContainer['diversity'])) {if ($this->_dataContainer['diversity'][$k]['orderCode'] == $orderCode) {return $this->_dataContainer['diversity'][$k];}
}
}
return FALSE;}
function getLanguageVar($varName) {return $this->_getData("['language']['" . $this->_shop->language . "'][$varName]", NULL, TRUE);}
function getImageFormatted($imgName) {if (isSet($this->_dataContainer['base']['image'][$imgName])) {$img = $this->_dataContainer['base']['image'][$imgName];$ret = '<image src="' . $img['src'] . '" border="0"';if (!is_null($img['width']))  $ret .= ' width="'  . $img['width']  . '"';if (!is_null($img['height'])) $ret .= ' height="' . $img['height'] . '"';if (!is_null($img['alt']))    $ret .= ' alt="'    . $img['alt']    . '"';$ret .= '>';return $ret;} else {return FALSE;}
}
function dirtyFormatting($design, $givenOrderCode=NULL) {if (!is_null($givenOrderCode)) {$orderCode = $givenOrderCode;if (($orderCode != $this->getOrdercode()) && isSet($design['diversityline'])) {$line = $design['diversityline'];} else {$line = $design['line'];}
} else {$orderCode = $this->getOrdercode();$line      = $design['line'];}
$data = array();$data['__ORDERCODE__']      = $orderCode;$data['__ID__']             = $this->getUid();$data['__CAPTION__']        = $this->getCaption($orderCode);$data['__CAPTIONADD__']     = $this->getCaptionAdd($orderCode);$data['__DESCRIPTION__']    = $this->getDescription($orderCode);$data['__DESCRIPTIONADD__'] = $this->getDescriptionAdd($orderCode);$data['__IMAGE_OVERVIEW__'] = $this->getImageFormatted('overview');$data['__AMOUNT__']         = $GLOBALS['bs_ss_customer']->basket->getAmount($orderCode);if (empty($orderCode)) {$data['__PRICE_VALUE__']     = '';$data['__PRICE_CURRENCY__']  = '';$data['__FORM_ELEMENT__']    = '';if (isSet($design['noformelement'])) {$data['__NO_FORM_ELEMENT__'] = $this->_formatLine($design['noformelement'], $data);} else {$data['__NO_FORM_ELEMENT__'] = '';}
} else {$data['__PRICE_VALUE__']     = $this->getPrice($orderCode);$data['__PRICE_CURRENCY__']  = $this->getCurrency($orderCode);if (isSet($design['formelement'])) {$data['__FORM_ELEMENT__']    = $this->_formatLine($design['formelement'], $data);} else {$data['__FORM_ELEMENT__']    = '';}
$data['__NO_FORM_ELEMENT__'] = '';}
$line = $this->_formatLine($line, $data);$diversityStr = '';if (is_null($givenOrderCode) && $this->hasDiversity() && isSet($design['diversityline'])) {$orderCodes = $this->getOrdercodes();foreach ($orderCodes as $diversityOrderCode) {$diversityStr .= $this->dirtyFormattingOfDiversity($design, $diversityOrderCode);  }
}
$line = str_replace('__DIVERSITY__', $diversityStr, $line);return $line;}
function dirtyFormattingOfDiversity($design, $diversityOrderCode) {$data = array();$data['__ORDERCODE__']       = $diversityOrderCode;$data['__ID__']              = $this->getUid();$data['__PRICE_VALUE__']     = $this->getPrice($diversityOrderCode);$data['__PRICE_CURRENCY__']  = $this->getCurrency($diversityOrderCode);$data['__CAPTION__']         = $this->getCaption($diversityOrderCode);$data['__CAPTIONADD__']      = $this->getCaptionAdd($diversityOrderCode);$data['__DESCRIPTION__']     = $this->getDescription($diversityOrderCode);$data['__DESCRIPTIONADD__']  = $this->getDescriptionAdd($diversityOrderCode);$data['__IMAGE_OVERVIEW__']  = $this->getImageFormatted('overview');$data['__AMOUNT__']          = $GLOBALS['bs_ss_customer']->basket->getAmount($diversityOrderCode);if (isSet($design['formelement'])) {$data['__FORM_ELEMENT__']    = $this->_formatLine($design['formelement'], $data);} else {$data['__FORM_ELEMENT__']    = '';}
$data['__NO_FORM_ELEMENT__'] = '';$line = (isSet($design['diversityline'])) ? $design['diversityline'] : $design['line'];return $this->_formatLine($line, $data);}
function _formatLine($formatLine, $data) {foreach ($data as $key => $value) {$formatLine = str_replace($key, $value, $formatLine);}
return $formatLine;}
function getFormatted($format) {$ret = '';$base = $this->_dataContainer['base'];$imgWidth  = ($base['image']['big']['width'])  ? $base['image']['big']['width']  : 370;$imgHeight = ($base['image']['big']['height']) ? $base['image']['big']['height'] : 270;$longDesc  = ($base['language']['de']['longdescription']) ? $base['language']['de']['longdescription'] : '&nbsp;';$ret .= <<< EOD
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
    <title>{$base['language']['de']['caption']}</title>
    <link rel="stylesheet" href="/bf/css/text_inhlat21.css">
    <script language="javascript">
      <!--
        function toTop() {
          window.focus();
        }
      //-->
    </script>
  </head>
  <!--<div style="width:10; height:250;"></div>-->
  <body bgcolor="#E6E6E6" onLoad="toTop();">
    <form action="/bf/03_02_basket.htm" method="post" target="content">
    <input type="hidden" name="bs_form[do]" value="add">
    <table border="0" cellpadding="0" cellspacing="0">
      <tr><td colspan='100%'><iframe src="{$base['image']['big']['src']}" width="{$imgWidth}" height="{$imgHeight}" marginwidth="0" marginheight="0" align="left" scrolling="no" frameborder="0"></iframe></td></tr>
      <tr><td colspan='100%'><img src='/bf/image/dummy.gif' width='1' height='20' border='0'></td></tr>
      <tr><td colspan='100%'><b>{$base['language']['de']['caption']}</b></td></tr>
      <tr><td colspan='100%'>{$base['language']['de']['description']}</td></tr>
      <tr><td colspan='100%'>{$longDesc}</td></tr>
EOD;
if (empty($this->_dataContainer['diversity'])) {$ret .= '<tr>';$ret .= '  <td>Best.-Nr. ' . $base['orderCode'] . '</td>';$ret .= '  <td align="right" rowspan="2"><nobr><input type="text" name="bs_form[items][' . $this->_atomContainer['UID'] . '][' . $base['orderCode'] . ']" size="2" maxlength="3"> <input type="image" name="addToCart" src="/bf/image/coffeshop/cart_small_halflightgray.gif" alt="in den Warenkorb"></nobr></td></tr>';$ret .= '<tr><td><b>CHF ' . $base['price']['value'] . '</b></td></tr>';} else {if (!empty($base['price']['value'])) {$ret .= '<tr><td><b>CHF ' . $base['price']['value'] . '</b></td></tr>';}
$ret .= "<tr><td colspan='100%'><img src='/bf/image/dummy.gif' width='1' height='20' border='0'></td></tr>";reset($this->_dataContainer['diversity']);while (list($k) = each($this->_dataContainer['diversity'])) {$div = $this->_dataContainer['diversity'][$k];$ret .= '<tr><td colspan="100%"><b>' . $div['language']['de']['caption'] . '</b></td></tr>';$ret .= '<tr><td colspan="100%">' . $div['language']['de']['description'] . '</td></tr>';$ret .= '<tr>';$ret .= '  <td>Best.-Nr. ' . $div['orderCode'] . '</td>';$ret .= '  <td align="right" rowspan="2"><nobr><input type="text" name="bs_form[items][' . $this->_atomContainer['UID'] . '][' . $div['orderCode'] . ']" size="2" maxlength="3"> <input type="image" name="addToCart" src="/bf/image/coffeshop/cart_small_halflightgray.gif" alt="in den Warenkorb"></nobr></td></tr>';if (!empty($div['price']['value'])) {$ret .= '<tr><td><b>CHF ' . $div['price']['value'] . '</b></td></tr>';} else {$ret .= '<tr><td>&nbsp;</td></tr>';}
$ret .= "<tr><td colspan='100%'><img src='/bf/image/dummy.gif' width='1' height='20' border='0'></td></tr>";}
}
$ret .= <<< EOD
        </td>
      </tr>
      <tr>
        <td colspan="100%" align="center" valign="baseline"><a href="javascript:window.close()">Fenster schliessen</a></td>
      </tr>
    </table>
    </form>
  </body>
</html>
EOD;
return $ret;}
}
?>