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
define('BS_HTMLTAG_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');class Bs_HtmlTag extends Bs_Object {var $htmlUtil;var $_tagString;var $_tagTokens;var $_tagType;var $valueDelimiter = '"';var $useXmlStop;var $useCommentStyle = 0;var $caseFolding;function Bs_HtmlTag($tag=FALSE) {parent::Bs_Object(); $this->htmlUtil = &$GLOBALS['Bs_HtmlUtil'];if (is_string($tag)) $this->setTag($tag);}
function setTag($tag) {unset($this->_tagString);unset($this->_tagTokens);unset($this->_tagType);$this->_tagString = $tag;$this->_cleanTag();$this->_parseXmlStop();$this->_parseCommentStyle();$this->_parseTagType();$this->_parseTagTokens();}
function getTag() {$tag = '<';if ($this->useCommentStyle) {if ($this->useCommentStyle == 1) {$tag .= '!--';} elseif ($this->useCommentStyle == 2) {$tag .= '!';}
}
$tag .= $this->_tagType;while (list($k) = each($this->_tagTokens)) {if (is_bool($this->_tagTokens[$k])) {$tag .= ' ' . $k;} else {$tag .= ' ' . $k . '=' . $this->valueDelimiter . $this->_tagTokens[$k] . $this->valueDelimiter;}
}
if ($this->useCommentStyle == 1) {$tag .= '--';}
if ($this->_useXmlStop) $tag .= '/';$tag .= '>';return $tag;}
function getTagType() {return $this->_tagType;}
function setTagType($tagType) {if ($this->caseFolding == 'u') {$tagType = strToUpper($tagType);} else {$tagType = strToLower($tagType);}
$this->_tagType = $tagType;}
function getToken($key, $ignoreCaseFolding=FALSE) {if (!$ignoreCaseFolding) {if (is_string($this->caseFolding)) {if ($this->caseFolding == 'u') {$key = strToUpper($key);} else {$key = strToLower($key);}
}
}
if (isSet($this->_tagTokens[$key])) {return $this->_tagTokens[$key];}
return null;}
function getTokens($ignoreCaseFolding=FALSE) {if (!is_array($this->_tagTokens)) return FALSE;$ret = array();foreach ($this->_tagTokens as $key => $value) {if (!$ignoreCaseFolding) {if (is_string($this->caseFolding)) {if ($this->caseFolding == 'u') {$key = strToUpper($key);} else {$key = strToLower($key);}
}
}
$ret[$key] = $value;}
return $ret;}
function setToken($key, $value) {if (is_string($this->caseFolding)) {if ($this->caseFolding == 'u') {$key = strToUpper($key);} else {$key = strToLower($key);}
}
if (!($value === TRUE)) $value = (string)$value; $this->_tagTokens[$key] = $value;}
function deleteToken($key) {if (is_string($this->caseFolding)) {if ($this->caseFolding == 'u') {$key = strToUpper($key);} else {$key = strToLower($key);}
}
if (isSet($this->_tagTokens[$key])) {unset($this->_tagTokens[$key]);return TRUE;}
return FALSE;}
function hasToken($key, $ignoreCaseFolding=FALSE) {return (bool)($this->getToken($key, $ignoreCaseFolding) != null);}
function _reset() {unset($this->_tagString);unset($this->_tagTokens);unset($this->_tagType);$this->valueDelimiter = '"';unset($this->useXmlStop);$this->useCommentStyle = 0;unset($this->caseFolding);}
function _cleanTag() {}
function _parseXmlStop() {$tag    = &$this->_tagString;$strLen = strlen($tag);$useXml = ($tag[$strLen -2] == '/') ? TRUE: FALSE;if ($useXml) {$tag = substr($tag, 0, -2) . substr($tag, -1, 1);}
if (!is_bool($this->useXmlStop)) $this->useXmlStop = $useXml;}
function _parseCommentStyle() {$tag       = &$this->_tagString;$strLen    = strlen($tag);$startPart = substr($tag, 1, 3); if ($startPart == '!--') {$useComment = 1;$tag = '<' . substr($tag, 4, -4) . '>'; } elseif ($startPart == '!') {$tag = '<' . substr($tag, 2); $useComment = 2;} else {$useComment = -1;}
if (!is_int($this->useCommentStyle)) $this->useCommentStyle = $useComment;}
function _parseTagType() {$tag    = $this->_tagString;$end    = strpos($tag, ' ', 1); $start  = ($tag[1] == '!') ? 2 : 1; $result = '';if ($end === FALSE) {$strLen = strlen($tag);$end = ($tag[$strLen -2] == '/') ? $strLen -2 : $strLen -1;$result = substr($tag, $start, $end -$start);} else {$result = substr($tag, $start, $end -$start);}
if (is_string($this->caseFolding)) {if ($this->caseFolding == 'u') {$result = strToUpper($result);} else {$result = strToLower($result);}
}
$this->_tagType = $result;}
function _parseTagTokens() {$tag = &$this->_tagString;$this->_tagTokens = $this->__parseTag($tag);}
function __parseTag($tag, $debug=FALSE) {if ($debug) echo '<br><br>TAG BLOCK<br>tag is: ' . htmlSpecialChars($tag) . "<br>\n";$t = strpos($tag, ' ', 1); if ($t === FALSE) {}
$tagType = substr($tag, 1, $t -1); if ($debug) echo 'tagType is: ' . $tagType . "<br>\n";$tagLength = strlen($tag);if ($debug) echo 'tagLength is: ' . $tagLength . "<br>\n";$lastPos = 0;$properties = array();do { do { $posStartName = strpos($tag, ' ', $lastPos); if ($posStartName === FALSE) break 2; do { $posStartName++;if ($posStartName >= $tagLength) break 3;$myChar  = $tag[$posStartName];$myAscii = ord($myChar);if ( (($myAscii >= 97) AND ($myAscii <= 122))  OR  (($myAscii >= 65) AND ($myAscii <= 90))  OR  (($myAscii >= 48) AND ($myAscii <= 57)) ) {break;} elseif ($myChar == '>') {break 3; }
} while (TRUE);unset($array);$t = strpos($tag, '=', $posStartName);if ($t !== FALSE) $array[] = $t;$t = strpos($tag, ' ', $posStartName);if ($t !== FALSE) $array[] = $t;$t = strpos($tag, '>', $posStartName);if ($t !== FALSE) $array[] = $t;if (is_array($array)) {sort($array);reset($array);$posEndName = current($array);$propName = substr($tag, $posStartName, $posEndName - $posStartName);switch ($tag[$posEndName]) {case '=':
break;case ' ':
if ($debug) echo '- 1) single param is: ' . $propName . "<br>\n";$properties[$propName] = TRUE;$lastPos = $posEndName;break 2; case '>':
if (substr($propName, -1) == '/') $propName =  substr($propName, 0, -1);if ($debug) echo '- 2) single param is: ' . substr($propName, 0, -1) . "<br>\n";$properties[$propName] = TRUE;break 3; default:
if ($debug) echo '- *MURPHY*' . '<br>';break 3; }
} else {$propName = substr($tag, $posStartName, $tagLength - $posStartName);if (substr($propName, -1) == '/') $propName =  substr($propName, 0, -1);if ($debug) echo '- 3) single param is: ' . $propName . "<br>\n";$properties[$propName] = TRUE;break 2; }
$posStartValue = $posEndName +1;$t = $tag[$posStartValue];switch ($t) {case '"': $posStartValue++;$propType = '"';break;case "'": $posStartValue++;$propType = "'";break;case ' ': $properties[$propName] = '';if ($debug) echo '- 4) param is: ' . $propName . " = '' (no value)<br>\n";$lastPos = $posStartValue;break 2; break;case '>': $properties[$propName] = '';if ($debug) echo '- 5) param is: ' . $propName . " = '' (no value)<br>\n";break 3; default: $propType = ' '; }
$posEndValue = strpos($tag, $propType, $posStartValue); if ($posEndValue === FALSE) {switch ($propType) {case ' ':
$propValue = trim(substr($tag, $posStartValue, $tagLength - $posStartValue -1));if (substr($propValue, -1) == '/') $propValue =  substr($propValue, 0, -1); $propValue = $this->htmlUtil->htmlEntitiesUndo($propValue);if ($debug) echo '- 7) param is: ' . $propName . " = '" . $propValue . "'<br>\n";$properties[$propName] = $propValue;break 3; case '"':
case "'":
if ($debug) echo "check your html/xml code!<br>\n";break 3;default:
if ($debug) echo "*MURPHY*<br>\n";}
}
$propValue = $this->htmlUtil->htmlEntitiesUndo(substr($tag, $posStartValue, $posEndValue - $posStartValue));if (is_string($this->caseFolding)) {if ($this->caseFolding == 'u') {$propName = strToUpper($propName);} else {$propName = strToLower($propName);}
}
if ($debug) echo '- 6) param is: ' . $propName . " = '" . $propValue . "'<br>\n";$properties[$propName] = $propValue;$lastPos = $posEndValue;} while (FALSE);} while (TRUE);if ($debug) echo '<br><br>';return $properties;}
function __parseProperties($propertyString, $debug=FALSE) {$t = ' ' . $propertyString;return $this->__parseTag($t, $debug);}
}
?>