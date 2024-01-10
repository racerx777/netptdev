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
define('BS_SS_BASKET_VERSION',      '4.5.$Revision: 1.4 $');require_once($_SERVER['DOCUMENT_ROOT']      . '../global.conf.php');require_once($APP['path']['core'] . 'util/Bs_Number.class.php');require_once($APP['path']['core'] . 'html/form/Bs_Form.class.php');require_once($APP['path']['core'] . 'html/form/specialfields/Bs_FormFieldCountryList.class.php');require_once($APP['path']['core'] . 'html/form/specialfields/Bs_FormFieldEmail.class.php');require_once($APP['path']['core'] . 'html/form/specialfields/Bs_FormFieldSex.class.php');require_once($APP['path']['core'] . 'html/form/specialfields/Bs_FormFieldFirstname.class.php');require_once($APP['path']['core'] . 'html/form/specialfields/Bs_FormFieldLastname.class.php');require_once($APP['path']['core'] . 'net/email/Bs_Smtp.class.php');class Bs_Ss_Basket extends Bs_Object {var $_APP;var $Bs_Number;var $_products = array();var $_shop;var $_bsForm;var $basketUrl = '/en/shop/basket/';var $_outputFormat;var $_inputVars;var $serializeType = 'wddx';var $mailOrderFrom = 'andrej@blueshoes.org';var $mailOrderTo   = 'andrej@blueshoes.org';var $onLoadCode;var $includeOnce;function Bs_Ss_Basket(&$shop) {parent::Bs_Object(); $this->_shop = &$shop;$this->_APP  = &$GLOBALS['APP'];if (!isSet($GLOBALS['Bs_Number'])) {$this->Bs_Number =& new Bs_Number();$GLOBALS['Bs_Number'] = &$this->Bs_Number;} else {$this->Bs_Number = &$GLOBALS['Bs_Number'];}
}
function doItYourself() {$outputFormat = 'standard';if (!is_null($_REQUEST) && isSet($_REQUEST['bs_form']['do'])) {$this->_inputVars = &$_REQUEST;switch ($this->_inputVars['bs_form']['do']) {case 'add':
if (is_array($this->_inputVars['bs_form']['items'])) {while (list($UID, $item) = each($this->_inputVars['bs_form']['items'])) {while (list($orderCode, $number) = each($item)) {$this->add($UID, $orderCode, $number);}
}
}
break;case 'change':
break;case 'remove':
$orderCode = $this->_inputVars['bs_form']['orderCode'];$this->remove($orderCode);break;case 'flush':
$this->flush();break;case 'basket':
if (@$this->_inputVars['bs_form']['btn']['order'] != '') {$this->_recalc($this->_inputVars['bs_form']['items']);$outputFormat = 'form';} elseif (@$this->_inputVars['bs_form']['btn']['recalc'] != '') {$this->_recalc($this->_inputVars['bs_form']['items']);} elseif (@$this->_inputVars['bs_form']['btn']['submit'] != '') {$outputFormat = 'form';} elseif (@$this->_inputVars['bs_form']['btn']['back']   != '') {} elseif (@$this->_inputVars['bs_form']['btn']['flush']  != '') {$this->flush();} else {$this->_recalc($this->_inputVars['bs_form']['items']);$outputFormat = 'form';}
break;default:
}
}
$this->_outputFormat = $outputFormat;}
function output() {switch ($this->_outputFormat) {case 'form':
return $this->getFormattedUneditable();default: return $this->getFormatted();}
}
function _recalc($formInputVars) {$this->_products = array();if (is_array($formInputVars) && !empty($formInputVars)) {while (list($UID) = each($formInputVars)) {while (list($orderCode, $number) = each($formInputVars[$UID])) {$this->add($UID, $orderCode, $number);}
}
}
}
function add($UID, $orderCode, $number) {if (getType($number) != 'integer') {if (is_numeric($number)) {$number = (int)$number;} else {return FALSE;}
}
if (isSet($this->_products[$orderCode])) {$this->_products[$orderCode]['number'] += $number;} else {$this->_products[$orderCode] = array(
'UID'    => $UID, 
'number' => $number
);}
return TRUE;}
function change($orderCode, $number) {if (getType($number) != 'integer') {if (is_numeric($number)) {$number = (int)$number;} else {return FALSE;}
}
if (isSet($this->_products[$orderCode])) {$this->_products[$orderCode]['number'] = $number;return TRUE;}
return FALSE;}
function remove($orderCode) {if (isSet($this->_products[$orderCode])) {unset($this->_products[$orderCode]);}
}
function flush() {$this->_products = array();}
function _savePersonalInfoAsCookie($cookieArray) {if ($this->serializeType == 'php') {$serial = (serialize($cookieArray));} else { $packet_id = wddx_packet_start("PHP");wddx_add_vars($packet_id, 'cookieArray');$serial = (wddx_packet_end($packet_id));}
bsSetCookie('smartshopUserData', $serial, time()+3600000, "/");}
function getNumItems() {return sizeOf($this->_products);}
function getValue($orderCode=NULL, $decimal=2) {$value    = 0;$tryBlock = 1;$isOk     = FALSE;do { if (!is_null($orderCode)) {if (!isSet($this->_products[$orderCode])) {break $tryBlock;}
$numProducts = $this->_products[$orderCode]['number'];$prodObj = $this->_shop->clearingHouse->getProduct($this->_products[$orderCode]['UID']);if (is_object($prodObj)) {$prodPrice   = $prodObj->getPrice($orderCode);$value += $numProducts * $prodPrice;$isOk = TRUE;break $tryBlock;} else {break $tryBlock;}
} else {if (is_array($this->_products)) {reset($this->_products);$tryBlock++;while (list($k) = each($this->_products)) {unset($prodObj);$prodObj = $this->_shop->clearingHouse->getProduct($this->_products[$k]['UID']);if (is_object($prodObj)) {$numProducts = $this->_products[$k]['number'];$prodPrice   = $prodObj->getPrice($k);$value += $numProducts * $prodPrice;} else {}
}
}
$tryBlock--;$isOk = TRUE;}
} while (FALSE);return $this->Bs_Number->roundNoTrim($value, 2);}
function getAmount($orderCode) {if (isSet($this->_products[$orderCode])) {return $this->_products[$orderCode]['number'];}
return 0;}
function _parseFormattedLine($xPath, $prodObj) {if ($xpvResult = $xPath->match($basePath . '//BS:IMAGE')) {foreach($xpvResult as $key => $value) {$prop = $xPath->getAttributes($value);if (!$prop || (empty($prop['NAME']))) {continue;}
$result = $prodObj->getImageFormatted($prop['NAME']);if ($result === FALSE) $result = '';$xPath->replaceChildByData($value, $result);}
}
if ($xpvResult = $xPath->match($basePath . '//BS:LANGUAGE')) {foreach($xpvResult as $key => $value) {$prop = $xPath->getAttributes($value);if (!$prop || (empty($prop['NAME']))) {continue;}
$result = $prodObj->getLanguageVar($prop['NAME']);if ($result === FALSE) $result = '';$xPath->replaceChildByData($value, $result);}
}
$hasDiversity = $prodObj->hasDiversity();if ($xpvResult = $xPath->match($basePath . '//BS:PRICE')) {foreach($xpvResult as $key => $value) {$isOk = FALSE;do { if ($hasDiversity) break;$result = $prodObj->getPrice(NULL, FALSE);if ($result === FALSE) break;$result = 'USD ' . $result;$isOk = TRUE;} while (FALSE);if (!$isOk) {$result = 'blah';}
$xPath->replaceChildByData($value, $result);}
}
if ($xpvResult = $xPath->match($basePath . '//BS:BASKET')) {foreach($xpvResult as $key => $value) {$isOk = FALSE;do { if ($hasDiversity) break;$result = $this->getBasketLine();if ($result === FALSE) break;$isOk = TRUE;} while (FALSE);if (!$isOk) {$result = 'blah';}
$xPath->replaceChildByData($value, $result);}
}
if ($xpvResult = $xPath->match($basePath . '//BS:PRODUCTTOTAL')) {foreach($xpvResult as $key => $value) {$isOk = FALSE;do { if ($hasDiversity) break;$result = $this->getValue($prodObj->_dataContainer['base']['orderCode']);if ($result === FALSE) break;$isOk = TRUE;} while (FALSE);if (!$isOk) {$result = 'blah';}
$xPath->replaceChildByData($value, $result);}
}
$line = $xPath->exportAsXml();$line = str_replace('__UID__',         $prodObj->_atomContainer['UID'],               $line);$line = str_replace('__ORDERCODE__',   $prodObj->_dataContainer['base']['orderCode'], $line);$line = str_replace('__ORDERAMOUNT__', $this->_products[$prodObj->_dataContainer['base']['orderCode']]['number'], $line);return $line;}
function getDesign($format) {return $this->_shop->getDesign($format);}
function getFormatted() {$ret = '';if (!empty($this->_products)) {$design = $this->getDesign('basket');$ret .= '<form action="' . $this->basketUrl . '" method="post">';$ret .= '<input type="hidden" name="bs_form[do]" value="basket">';$ret .= $design['pre'];reset($this->_products);while (list($orderCode) = each($this->_products)) {unset($prodObj);$prodObj = $this->_shop->clearingHouse->getProduct($this->_products[$orderCode]['UID']);if (is_object($prodObj)) {$myLine = $prodObj->dirtyFormatting($design, $orderCode);$ret .= $myLine;}
}
$ret .= $design['post'];$ret .= '<b>Total USD ' . $this->getValue() . '<br>';$ret .= $this->_getBasketSubText();$ret .= '<input type="submit" name="bs_form[btn][recalc]" value="Recalculate" class="button" style="width:150;"> ';$ret .= '<input type="submit" name="bs_form[btn][flush]"  value="Cancel order" class="button" style="width:150;"> ';$minAmountIsOk = TRUE;if (isSet($this->_shop->_dataContainer['payment']['minamount']['client'])) {$minAmount = $this->_shop->_dataContainer['payment']['minamount']['client'];if (!empty($minAmount) && ($minAmount > 0)) {if ((double)$this->getValue() < (double)$minAmount) {$minAmountIsOk = FALSE;}
}
}
if ($minAmountIsOk) {$ret .= '<input type="submit" name="bs_form[btn][order]"  value="Order now" class="button" style="width:150;">';} else {$ret .= '<input type="button" name="bs_form[btn][order]"  value="Order now" class="button" style="width:150;" onClick="alert(\'Mindestbestellwert nicht erreicht\');">';}
$ret .= '</form>';} else {$ret .= 'Your basket is empty.';}
return $ret;}
function getFormattedUneditable() {$ret = '';if (!empty($this->_products)) {$design = $this->getDesign('basketUneditable');$retPre  = '';$retPre .= '<input type="hidden" name="bs_form[do]" value="basket">';$retPre .= $design['pre'];reset($this->_products);while (list($orderCode) = each($this->_products)) {unset($prodObj);$prodObj = $this->_shop->clearingHouse->getProduct($this->_products[$orderCode]['UID']);if (is_object($prodObj)) {$myLine = $prodObj->dirtyFormatting($design, $orderCode);$retPre .= $myLine;}
}
$retPre .= $design['post'];$retPre .= '<br><b>Total in USD ' . $this->getValue() . '</b><br><br>';$retPre .= $this->_getBasketSubText();$retPost  = '<br>';$retPost .= '<input type="submit" name="bs_form[btn][submit]" value="Submit order"  class="button" style="width:150;"> ';$retPost .= '<input type="submit" name="bs_form[btn][back]"   value="Back to basket"  class="button" style="width:150;"> ';$retPost .= '<input type="submit" name="bs_form[btn][flush]"  value="Cancel order" class="button" style="width:150;"> ';$formOut = $this->_treatOrderForm();if (strpos($formOut, '<bs_ss_form_pre/>') !== FALSE) {$t = str_replace('<bs_ss_form_pre/>',  $retPre, $formOut);} else {$t = str_replace('<bs_after_formopen_tag/>',  $retPre, $formOut);}
if (strpos($t, '<bs_ss_form_post/>') !== FALSE) {$t = str_replace('<bs_ss_form_post/>', $retPost, $t);} else {$t = str_replace('<bs_before_formclose_tag/>', $retPost, $t);}
$ret .= $t;} else {$ret .= 'Your shopping basket is empty.'; }
return $ret;}
function _getAsCsv($separator=';') {$ret = '';if (!empty($this->_products)) {$ret .= "Code{$separator}Caption{$separator}Amount{$separator}Price{$separator}Total\n";reset($this->_products);while (list($orderCode) = each($this->_products)) {unset($prodObj);$prodObj = $this->_shop->clearingHouse->getProduct($this->_products[$orderCode]['UID']);if (is_object($prodObj)) {$base = $prodObj->_dataContainer['base'];$ret .= "{$orderCode}{$separator}" . $prodObj->getFullCaption($orderCode) . "{$separator}{$this->_products[$orderCode]['number']}{$separator}" . $prodObj->getPrice($orderCode) . $separator . $this->getValue($orderCode) . "\n";} else {}
}
$ret .= "\nTOTAL: " . $this->getValue() . "\n\n";} else {$ret .= "The shopping basket is empty.\n"; }
return $ret;}
function _getAsPlaintext() {$ret = '';if (!empty($this->_products)) {reset($this->_products);while (list($orderCode) = each($this->_products)) {unset($prodObj);$prodObj = $this->_shop->clearingHouse->getProduct($this->_products[$orderCode]['UID']);if (is_object($prodObj)) {$ret .= "Code: {$orderCode}\n";$ret .= "Caption: " . $prodObj->getFullCaption($orderCode) . "\n"; $ret .= "Amount: " . $this->_products[$orderCode]['number'] . "\n"; $ret .= "Price: " . $prodObj->getPrice($orderCode) . "\n"; $ret .= "Total: " . $this->getValue($orderCode) . "\n\n"; } else {}
}
$ret .= "TOTAL: " . $this->getValue() . "\n\n";} else {$ret .= "The shopping basket is empty.\n"; }
return $ret;}
function getProductsAsHiddenFormFieldsForPaypal() {$ret = '';if (!empty($this->_products)) {$productCounter = 0;reset($this->_products);while (list($orderCode) = each($this->_products)) {unset($prodObj);$prodObj = $this->_shop->clearingHouse->getProduct($this->_products[$orderCode]['UID']);if (is_object($prodObj)) {$productCounter++;$ret .= '<input type="hidden" name="item_name_'   . $productCounter . '" value="' . $prodObj->getFullCaption($orderCode) . '">';$ret .= '<input type="hidden" name="item_number_' . $productCounter . '" value="' . $orderCode . '">';$ret .= '<input type="hidden" name="amount_'      . $productCounter . '" value="' . $prodObj->getPrice($orderCode) . '">';$ret .= '<input type="hidden" name="quantity_'    . $productCounter . '" value="' . $this->_products[$orderCode]['number'] . '">';$ret .= '<input type="hidden" name="shipping_'    . $productCounter . '" value="0">';$ret .= '<input type="hidden" name="shipping2_'   . $productCounter . '" value="0">';$ret .= '<input type="hidden" name="handling_'    . $productCounter . '" value="0">';} else {}
}
} else {return FALSE;}
return $ret;}
function _getBasketSubText($format='html') {$ret  = '';if ($format == 'html') {$ret .= '';} else {$ret .= "";}
return $ret;}
function getAsLayer() {$ret = '';if (!empty($this->_products)) {$ret .= <<< EOD
<move:iframez id="basket" class="basket" style="width:188; height:248;">
<table width="188" height="248" border="0" cellspacing="0" cellpadding="6" background="/bf/image/coffeshop/layerBg.gif">
  <tr><td>&nbsp;</td></tr>
</table>
<iframe src="/admin/SmartShopIframe.php" name="basketIframe" id="basketIframe" marginwidth="0" marginheight="0" align="left" frameborder="0" style="border:0px; position:absolute; top:27; left:9; width:172; height:215; z-index:1"></iframe>
</move:iframez>
EOD;
}
return $ret;}
function getLayerIframe() {$ret  = '';$ret .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">';$ret .= '<tr>';$ret .= '  <td height="20" bgcolor="#B8B8B8" valign="baseline"><!img src="/_bsImages/applications/smartshop/cart_small_gray.gif" width="22" height="19" border="0" align="baseline" alt=""></td>';$ret .= '  <td height="20" bgcolor="#B8B8B8" valign="baseline"><a href="' . $this->basketUrl . '">Open basket</a></td>';$ret .= '</tr>';$ret .= '<tr><td colspan="100%" valign="bottom">';if (is_array($this->_products)) {$design = $this->getDesign('smallBasket');$ret .= $design['pre'];reset($this->_products);while (list($orderCode) = each($this->_products)) {unset($prodObj);$prodObj = $this->_shop->clearingHouse->getProduct($this->_products[$orderCode]['UID']);if (is_object($prodObj)) {$myLine = $prodObj->dirtyFormatting($design, $orderCode);$ret .= $myLine;}
}
$ret .= $design['post'];} else {$ret .= ''; }
$ret .= '</td></tr>';$ret .= '<tr><td height="1" colspan="100%" bgcolor="black"></td></tr>';$ret .= '<tr>';$ret .= '  <td height="20" bgcolor="#B8B8B8" valign="baseline"><!img src="/_bsImages/applications/smartshop/cart_small_gray_flipped.gif" width="22" height="19" border="0" align="baseline" alt=""></td>';$ret .= '  <td height="20" bgcolor="#B8B8B8" valign="baseline">USD ' . $this->getValue() . '</td>';$ret .= '</tr>';$ret .= '<tr><td height="1" colspan="100%" bgcolor="black"></td></tr>';$ret .= '<tr><td height="20" colspan="100%"><center><a href="' . $this->basketUrl . '">Order now</a></center></td></tr>';$ret .= '</table>';return $ret;}
function getAsIcon() {$ret = '';if (!empty($this->_products)) {$ret .= '<br><br><br>';$ret .= '<table border="0" cellpadding="0" cellspacing="0"><tr><td align="center"><a href="' . $this->basketUrl . '"><img src="/bf/image/coffeshop/cart_small_white_flipped.gif" width="22" height="19" border="0"></a></td></tr><tr><td align="center"><a href="' . $this->basketUrl . '">Mein Warenkorb</a></td></tr></table>';}
return $ret;}
function _treatOrderForm() {$this->_loadOrderForm();$ret = '';if (@$this->_inputVars['bs_form']['step'] == '2') {$this->_bsForm->setReceivedValues($this->_inputVars);$this->_bsForm->postLoadTrigger(); $isOk = $this->_bsForm->validate();if ($isOk) {$valuesArray = $this->_bsForm->getValuesArray(TRUE, 'valueDisplay', TRUE);$clientInfo = "Customer data:\n\n";while (list($k) = each($valuesArray)) {$clientInfo .= "{$valuesArray[$k][0]} => {$valuesArray[$k][1]}\n";}
$productInfo  = "Products:\n\n";$productInfo .= $this->_getAsCsv();$productInfo .= $this->_getBasketSubText('text');$mailTextShop  = "New order like this:\n\n\n";$mailTextShop .= $clientInfo . "\n\n";$mailTextShop .= $productInfo . "\n\n";$mailTextShop .= "This is an automatically generated email message.\n";$mailTextShop .= "The customer received a similar message with the same data to \n";$mailTextShop .= "the address '{$valuesArray['email'][1]}'.\n\n\n";$mailTextShop .= "Statistics:\n\n";$useTime = time() - $this->_inputVars['bs_form']['startTimestamp'];$mailTextShop .= "The customer needed {$this->_inputVars['bs_form']['viewCount']} submit(s) und {$useTime} seconds to fill in \n";$mailTextShop .= "the order form completely.\n";$remoteAddr = (isSet($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';$host = (isSet($_SERVER['REMOTE_HOST'])) ? $_SERVER['REMOTE_HOST'] : @getHostByAddr($remoteAddr);$mailTextShop .= "IP-Address: {$remoteAddr} {$host}\n";$mailTextShop .= "Browser/OS: {$_SERVER['HTTP_USER_AGENT']}\n";$smtp =& new Bs_Smtp();$smtp->host = $this->_APP['smtp']['host'];$smtp->subject = "Order from the eShop";$smtp->message = $mailTextShop;$smtp->addFrom($this->mailOrderFrom, "BlueShoes Shop");$mailTo = ($this->mailOrderTo == 'DEBUG') ? $valuesArray['email'][1] : $this->mailOrderTo;$smtp->addTo($mailTo, $mailTo);$status = $smtp->send();if (isEx($status)) {$ret .= "<font color=red>Transmission to our email address {$mailTo} failed!</font>";} else {$mailTextCustomer  = "";$titleDone = FALSE;do {if (!isSet($valuesArray['billTitle'][1])    || empty($valuesArray['billTitle'][1]))    break;if (!isSet($valuesArray['billLastname'][1]) || empty($valuesArray['billLastname'][1])) break;if ($valuesArray['billTitle'][1] == 'Herr') {$mailTextCustomer .= "Dear Mr. " . $valuesArray['billLastname'][1];$titleDone = TRUE;} elseif ($valuesArray['billTitle'][1] == 'Frau') {$mailTextCustomer .= "Dear Miss "  . $valuesArray['billLastname'][1];$titleDone = TRUE;}
} while (FALSE);if (!$titleDone) {$mailTextCustomer .= "Dear Customer";}
$mailTextCustomer .= "\n\n";$mailTextCustomer .= "We have received your order for the following products:\n\n";$mailTextCustomer .= $this->_getAsPlaintext();$mailTextCustomer .= $this->_getBasketSubText('text') . "\n";$mailTextCustomer .= "Thank you.\n\n";$mailTextCustomer .= "Best regards.\n";$mailTextCustomer .= "optimaize gmbh / BlueShoes\n";$smtp->reset();$smtp->host = $this->_APP['smtp']['host'];$smtp->subject = "Your order";$smtp->message = $mailTextCustomer;$smtp->addFrom($this->mailOrderFrom, "BlueShoes Shop");$smtp->addTo($valuesArray['email'][1], $valuesArray['email'][1]);$status = $smtp->send();if (isEx($status)) {$ret .= "<font color=red>Transmission to your email address '{$valuesArray['email'][1]}' failed!</font> Order will continue anyway.<br><br>";}
$ret .= '<b>Thank you</b> for your order, it has been sent to us. <br>';$ret .= 'A confirmation email has been sent to you as well.<br><br>';$ret .= 'How would you like to make the payment?<br><br>';$paypalForm = '
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<!input type="hidden" name="cmd" value="_ext-enter">
<!input type="hidden" name="redirect_cmd" value="_xclick">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="upload" value="1">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="return" value="http://www.blueshoes.org/">
<!input type="hidden" name="image_url" value="http://www.blueshoes.org/logo.gif">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="business" value="wwmfpp@blueshoes.org">
<input type="hidden" name="email" value="' . $valuesArray['email'][1] . '">
<input type="hidden" name="first_name" value="' . $valuesArray['firstname'][1] . '">
<input type="hidden" name="last_name" value="' . $valuesArray['lastname'][1] . '">
<input type="hidden" name="address1" value="">
<input type="hidden" name="address2" value="">
<input type="hidden" name="city" value="' . $valuesArray['location'][1] . '">
<!input type="hidden" name="country" value="ch">
<!input type="hidden" name="state" value="">
<input type="hidden" name="zip" value="' . $valuesArray['zip'][1] . '">';$paypalForm .= $this->getProductsAsHiddenFormFieldsForPaypal();$ret .= '
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
<td valign="top" width="48%" bgcolor="' . $GLOBALS['APP']['color']['veryLightBlue'] . '">
<b>1) Using the service from paypal.com, credit cards accepted:</b><br><br>';$ret .= $paypalForm;$ret .= '
<input type="image" src="/_bsImages/applications/smartshop/paypal/btnPaypal.gif" name="submit" alt="Make payment with PayPal"><br><br>
<input type="image" src="/_bsImages/applications/smartshop/paypal/cards.gif" name="submit" alt="Make payment with PayPal"><br><br>
(click the button to proceed)
</form>
</td>
<td width="4%">&nbsp;</td>
<td valign="top" width="48%" bgcolor="' . $GLOBALS['APP']['color']['veryLightBlue'] . '">
<b>2) By manual bank transfer:</b><br><br>';$ret .= 'Amount in USD: ' . $this->getValue() . '<br>';$ret .= 'Exchange rate: 1.44280 <font size="1">(updated 2002/12/15 from http://www.xe.com/)</font><br>';$amountChf = round($this->getValue() * 1.44280);$ret .= 'Amount in CHF: ' . $amountChf . '.-<br>';$ret .= 'Bank costs for receiving foreign payments, and for additional handling: CHF 30.-<br>';$ret .= 'Please send CHF ' . round($amountChf + 30) . '.- to:<br><br>';$ret .= '
<!--Switzerland:-->
<table border="0" cellspacing="0" cellpadding="2">
<tr>
<td>Owner:</td>
<td>optimaize gmbh</td>
</tr>
<tr>
<td>IBAN:</td>
<td>CH50 0482 3027 4364 5100 1</td>
</tr>
<tr>
<td>Account Number:</td>
<td>4823-274364-51-1</td>
</tr>
<tr>
<td>Bank:</td>
<td>Credit Suisse Zug, Clearing 4823</td>
</tr>
</table>
<br><br>
<!--
France:
<table border="0" cellspacing="0" cellpadding="2">
<tr>
<td>To:</td>
<td>optimaize gmbh</td>
</tr>
<tr>
<td>Account:</td>
<td></td>
</tr>
<tr>
<td>Bank:</td>
<td></td>
</tr>
</table>
<br><br>
USA:
<table border="0" cellspacing="0" cellpadding="2">
<tr>
<td>To:</td>
<td>optimaize gmbh</td>
</tr>
<tr>
<td>Account:</td>
<td></td>
</tr>
<tr>
<td>Bank:</td>
<td></td>
</tr>
</table>
<br><br>
-->
</td>
</tr>
</table><br>
';}
} else {$ret .= $this->_bsForm->getForm(TRUE);$this->includeOnce = $this->_bsForm->getIncludeOnce('array');$this->onLoadCode  = $this->_bsForm->getOnLoadCode(FALSE); }
} else {$ret .= $this->_getOrderForm();$this->includeOnce = $this->_bsForm->getIncludeOnce('array');$this->onLoadCode  = $this->_bsForm->getOnLoadCode(FALSE); }
return $ret;}
function _getOrderForm() {$this->_bsForm->setBsFormData($this->_getVars['bs_form']);$ret = $this->_bsForm->getForm();return $ret;}
function _loadOrderForm() {$this->_bsForm =& new Bs_Form();$this->_bsForm->internalName  = "shopOrder";$this->_bsForm->name          = "shopOrder";$this->_bsForm->language      = 'en';$this->_bsForm->serializeType = "php"; $this->_bsForm->saveToDb      = TRUE;$this->_bsForm->mustFieldsVisualMode = 'starRight';$this->_bsForm->useAccessKeys        = TRUE;$this->_bsForm->useJsFile            = TRUE;$this->_bsForm->jumpToFirstError     = TRUE;$this->_bsForm->buttons              = FALSE;$this->_bsForm->mode                 = 'add';$this->_bsForm->onEnter              = 'tab';$this->_bsForm->advancedStyles       = array(
'captionMust'      => '', 
'captionMustOkay'  => '', 
'captionMustWrong' => 'formError', 
'captionMay'       => '', 
'captionMayOkay'   => '', 
'captionMayWrong'  => 'formError', 
'fieldMust'        => '', 
'fieldMustOkay'    => '', 
'fieldMustWrong'   => '', 
'fieldMay'         => '', 
'fieldMayOkay'     => '', 
'fieldMayWrong'    => ''
);unset($container);$container =& new Bs_FormContainer();$container->name         = "company";$container->caption      = array('en'=>'Company', 'de'=>'Firma');$container->orderId      = 1000;$container->mayToggle    = FALSE;$this->_bsForm->elementContainer->addElement($container);$element =& new Bs_FormFieldText();$element->name           = 'company';$element->saveToDb         = TRUE;$element->caption        = array('en'=>'Company', 'de'=>'Firma');$element->editability    = 'always';$element->orderId        = 700;$element->must           = TRUE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name           = 'zip';$element->saveToDb         = TRUE;$element->caption        = array('en'=>'Zipcode', 'de'=>'PLZ');$element->editability    = 'always';$element->orderId        = 600;$element->must           = TRUE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name           = 'location';$element->saveToDb         = TRUE;$element->caption        = array('en'=>'Location', 'de'=>'Ort');$element->editability    = 'always';$element->orderId        = 500;$element->must           = TRUE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldCountryList();$element->name           = 'country';$element->saveToDb         = TRUE;$element->caption        = array('en'=>'Country', 'de'=>'Land');$element->editability    = 'always';$element->orderId        = 400;$element->must           = TRUE;$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "person";$container->caption      = array('en'=>'Person', 'de'=>'Person');$container->orderId      = 950;$container->mayToggle    = FALSE;$this->_bsForm->elementContainer->addElement($container);$element =& new Bs_FormFieldSex();$element->name             = 'sex';$element->saveToDb         = TRUE;$element->optionsHard = array('en'=>array('1'=>'Mr',       '2'=>'Miss'), 
'de'=>array('1'=>'Herr',     '2'=>'Frau'), 
'fr'=>array('1'=>'Monsieur', '2'=>'Madame'), 
'it'=>array('1'=>'Signore',  '2'=>'Signora') 
);$element->orderId          = 1000;$element->must             = TRUE;$element->firstnameField   = 'firstname';$container->addElement($element);unset($element);$element =& new Bs_FormFieldFirstname();$element->name          = 'firstname';$element->saveToDb         = TRUE;$element->editability   = 'always';$element->orderId       = 900;$element->must          = TRUE;$element->lastnameField = 'lastname';$element->sexField      = 'sex';$container->addElement($element);unset($element);$element =& new Bs_FormFieldLastname();$element->name           = 'lastname';$element->saveToDb         = TRUE;$element->editability    = 'always';$element->orderId        = 800;$element->must           = TRUE;$element->firstnameField = 'firstname';$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "contact";$container->caption      = array('en'=>'Contact', 'de'=>'Kontakt');$container->orderId      = 900;$container->mayToggle    = FALSE;$this->_bsForm->elementContainer->addElement($container);$element =& new Bs_FormFieldEmail();$element->name           = 'email';$element->saveToDb         = TRUE;$element->editability    = 'always';$element->orderId        = 1000;$element->must           = TRUE;$element->disallowFreemail();$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name           = 'phone';$element->saveToDb         = TRUE;$element->caption        = array('en'=>'Phone', 'de'=>'Telefon');$element->editability    = 'always';$element->orderId        = 900;$element->must           = FALSE;$container->addElement($element);unset($element);$element =& new Bs_FormFieldText();$element->name           = 'fax';$element->saveToDb         = TRUE;$element->caption        = array('en'=>'Fax', 'de'=>'Fax');$element->editability    = 'always';$element->orderId        = 800;$element->must           = FALSE;$container->addElement($element);unset($element);unset($container);$container =& new Bs_FormContainer();$container->name         = "usage";$container->caption      = array('en'=>'Intended usage', 'de'=>'Geplante Nutzung');$container->orderId      = 800;$container->mayToggle    = FALSE;$this->_bsForm->elementContainer->addElement($container);$element =& new Bs_FormFieldText();$element->name           = 'usageUrl';$element->saveToDb         = TRUE;$element->caption        = array('en'=>'URL', 'de'=>'URL');$element->editability    = 'always';$element->orderId        = 1000;$element->must           = FALSE;$element->minLength      = 11;$element->mustStartWith  = 'http://';$container->addElement($element);unset($element);$element =& new Bs_FormFieldTextarea();$element->name           = 'usageDescription';$element->saveToDb         = TRUE;$element->caption        = array('en'=>'Description', 'de'=>'Beschreibung');$element->editability    = 'always';$element->orderId        = 900;$element->must           = FALSE;$element->minLength      = 0;$element->cols           = 35;$container->addElement($element);unset($element);}
}
?>
