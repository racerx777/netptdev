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
define('BS_HTMLUTIL_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_HtmlUtil extends Bs_Object {function Bs_HtmlUtil() {parent::Bs_Object(); }
function charToHtml($param='', $reverse=FALSE) {if ($param == '') return $param;static $lookFor = array(
'0','1','2','3','4','5','6','7','8','9',
'A','B','C','D','E','F','G','H','I','J', 
'K','L','M','N','O','P','Q','R','S','T', 
'U','V','W','X','Y','Z', 
'a','b','c','d', 'e','f','g','h','i', 
'j','k','l','m','n', 'o','p','q','r', 
's','t','u','v','w','x','y', 'z', 
'@'
);$replaceWith = array();$size = sizeOf($lookFor);for($i=0; $i<$size; $i++) {$replaceWith[$lookFor[$i]] = '&#' . ord($lookFor[$i]) . ';';}
if ($reverse) {$reverseReplace = array_flip($replaceWith);return strtr($param, $reverseReplace);}
return strtr($param, $replaceWith);}
function filterForJavaScript($param) {if ($param === '') return ''; static $replaceWith     = array("\\"=>"\\\\",  "\""=>"\\\"",  "'"=>"\\'",   "\n"=>"\\n",  "\f"=>"\\f", "\r"=>"\\\r");return strtr($param, $replaceWith);}
function filterForHtml($param, $quoteTrans=ENT_COMPAT) {if ($param == '') return '';$param = htmlspecialchars($param, $quoteTrans);static $replaceWith     = array("\n" => '&#10;', "\r" => '&#13;');return strtr($param, $replaceWith);}
function filterForHtmlUndo($param, $quoteTrans=ENT_COMPAT) {if ($param == '') return '';$trans = array_flip(get_html_translation_table(HTML_ENTITIES, $quoteTrans));$trans['&#10;'] = "\n";$trans['&#13;'] = "\r";return strtr($param, $trans);}
function jsAlert($value) {$ret  = "<script language='javascript'>\n";$ret .= "alert(' " . $this->filterForJavaScript($value) . "')\n";$ret .= "</script>\n";return $ret;}
function arrayToJsArray($array, $nameOfJsArray='myArray', $forceVector=FALSE, $makeVarGlobal=FALSE, $firstCall=TRUE) {if ($firstCall && !$makeVarGlobal) {$ret  = "var {$nameOfJsArray} = new Array();\n";} else {$ret  = "{$nameOfJsArray} = new Array();\n";}
if (!empty($array) && is_array($array)) {$i=0;foreach($array as $key => $val) {if (is_array($val)) {$key2 = ($forceVector OR is_integer($key)) ? $i : "'" . $this->filterForJavaScript($key) . "'";$ret .= $this->arrayToJsArray($array[$key], $nameOfJsArray . "[". $key2 . "]", $forceVector, $makeVarGlobal, FALSE);} else {if (getType($val) == 'boolean') {$val = boolToString($val);} else {$val = '"' . $this->filterForJavaScript($val) . '"';}
$ret .= $nameOfJsArray;$ret .= ($forceVector OR is_integer($key)) ? "[{$i}] = {$val};\n" : "['{$key}'] = {$val};\n";}
$i++;}
}
return $ret;}
function arrayToHtmlSelect($myArray, $selected='') {$ret = '';if (!is_array($myArray)) return $ret;reset($myArray);$zeroBased = (key($myArray)===0) ? TRUE : FALSE;while(list($key) = each($myArray)) {$val = &$myArray[$key];if ($zeroBased) {$key = $val;} 
$selString = '';if (is_array($selected)) {if (in_array($key, $selected)) $selString = ' selected';} else {if ($key == $selected) $selString = ' selected';}
$ret .= "<option value='{$key}'{$selString}>{$val}</option>\n";}
return $ret;}
function arrayToHiddenFormFields($data, $varName='') {if (empty($data) OR !is_array($data)) return '';$ret = '';foreach($data as $key => $val) {if (!empty($varName)) {$fieldName = $varName . '[' . $key . ']';} else {$fieldName = $key;}
if (is_array($val)) {$ret .= $this->arrayToHiddenFormFields($val, $fieldName);} else {switch(getType($val)) {case 'boolean':
$fieldVal = $val ? '1' : '0';break;case 'string':
$fieldVal = $this->filterForHtml($val);break;default:
$fieldVal = $val;}
$ret .= "<input type=\"hidden\" name=\"{$fieldName}\" value=\"{$fieldVal}\">\n";}
}
return $ret;}
function arrayToFormFieldNames($data, $varName='') {if (!is_array($data)) return array();$ret = array();foreach($data as $key => $val) {if (!empty($varName)) {$fieldName = $varName . '[' . $key . ']';} else {$fieldName = $key;}
if (is_array($val)) {$ret = array_merge($this->arrayToFormFieldNames($val, $fieldName), $ret);} else {$ret[] = $fieldName;}
}
return $ret;}
function htmlEntities2($string) {return $this->htmlEntitiesUndo($string);}
function htmlEntitiesUndo($string) {if (strlen($string) < 5) return $string; static $trans;$trans = array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES));$string = strtr($string, $trans);return preg_replace('/&#(\d+);/me', "chr('\\1')", $string); }
function &parseStyleStr(&$styleStr) {$styleHash = array();if (!is_string($styleStr)) return $styleHash;$tmp = trim($styleStr);if ($tmp=='') return $styleHash;$elements = explode(';', $styleStr);$elemSize = sizeOf($elements);for ($i=0; $i<$elemSize; $i++) {$tmp = trim($elements[$i]);if ($tmp == '') continue;$stylePair = explode(':',$tmp);$name = strToLower(trim($stylePair[0]));$val  = &$stylePair[1];if (isSet($val)) {if ($name != '') $styleHash[$name] = trim($val);} else {if ($name != '') $styleHash[$name] = NULL;}
}
return  $styleHash;}
function &parseAttrStr(&$attrStr) {$attrHash = array();if (!is_string($attrStr)) return $attrHash;$regEx_ValuePair = '|([^\s]+)\s*=\s*[\'"](.*)[\'"]|U';preg_match_all($regEx_ValuePair, $attrStr, $regs);     $noApostrophe = preg_replace ($regEx_ValuePair, '', $attrStr); $attrSize = sizeOf($regs[1]);  for ($i=0; $i<$attrSize; $i++) {$attrHash[strToLower($regs[1][$i])] = $regs[2][$i];}
$regEx_ValuePair = '|([^\s]+)\s*=\s*([^\s]+)|';preg_match_all($regEx_ValuePair, $noApostrophe, $regs);     $singleAttr = preg_replace ($regEx_ValuePair, '', $attrStr); $attrSize = sizeOf($regs[1]);  for ($i=0; $i<$attrSize; $i++) {$attrHash[strToLower($regs[1][$i])] = $regs[2][$i];}
preg_match_all('|(\w+)|', $singleAttr, $regs); $attrSize = sizeOf($regs[1]);   for ($i=0; $i<$attrSize; $i++) {$attrHash[strToLower($regs[1][$i])] = NULL;}
return $attrHash;}
}
$GLOBALS['Bs_HtmlUtil'] =& new Bs_HtmlUtil(); ?>