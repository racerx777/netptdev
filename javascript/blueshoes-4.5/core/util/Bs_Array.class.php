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
define("BS_ARRAY_VERSION",      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_Array extends Bs_Object {function Bs_Array() {parent::Bs_Object(); }
function explode($separator, $string, $limit=0) {$separator = join('', $separator);return split('[' . $separator . ']', $string); }
function maxSizeOfLevel($array, $level) {if (!is_array($array)) return 0;if ($level == 1) return sizeOf($array);$ret = 0;while (list($k) = each($array)) {$t = sizeOf($array[$k]);if ($t > $ret) $ret = $t;}
return $ret;}
function merge() {$ret = array();do {$params = func_get_args();if (!is_array($params) || empty($params)) break;while (list(,$p) = each($params)) {if (!is_array($p) || empty($p)) continue;while (list($k) = each($p)) {$ret[$k] = &$p[$k];}
}
} while (FALSE);return $ret;}
function array_merge_recursive($a1, $a2) {return $this->arrayMergeRecursive($a1, $a2);}
function arrayMergeRecursive($a1, $a2) {if (!is_array($a1)) {if (is_array($a2)) return $a2;return FALSE;}
if (!is_array($a2)) {return $a1;}
$newArray = $a1;foreach ($a2 as $key => $val) {if (is_array($val) && @is_array($newArray[$key])) {$newArray[$key] = $this->array_merge_recursive($newArray[$key], $val);} else {$newArray[$key] = $val;}
}
return $newArray;}
function arrayToCode(&$array, $name='$array') {$ret  = '';switch (getType($array)) {case 'object':
$ret .= "{$name} = '';\n";break;case 'array':
if (sizeOf($array) == 0) {$ret .= "{$name} = array();\n";} else {while (list($key, $val) = each($array)) {$ret .= $this->arrayToCode($val, "{$name}['{$key}']");}
}
break;case 'integer':
case 'double':
$ret .= "{$name} = {$array};\n";break;default:
$ret .= "{$name} = \"" . addSlashes($array) . "\";\n";}
return $ret;}
function arrayToText($data, $maxWidth=75, $stringSeparator=': ', $struct2=FALSE) {$ret = '';$maxKeySize = 0;reset($data);if ($struct2) {while (list($k) = each($data)) {$size = strlen($data[$k][0]);if ($size > $maxKeySize) $maxKeySize = $size;}
} else {while (list($k) = each($data)) {$size = strlen($k);if ($size > $maxKeySize) $maxKeySize = $size;}
}
$sizeLeftPart  = $maxKeySize + strlen($stringSeparator);$sizeRightPart = $maxWidth - $sizeLeftPart;reset($data);while (list($k) = each($data)) {if ($struct2) {$leftPart  = (string)$data[$k][0];$rightPart = (string)$data[$k][1];} else {$leftPart  = (string)$k;$rightPart = (string)$data[$k];}
$keySizeDiff = $maxKeySize - strlen($leftPart);$str = wordwrap($rightPart, $sizeRightPart, "\n", 1);if (strlen($str) > $sizeRightPart) {$t = explode("\n", $str);$str = array();while (list($i,$line) = each($t)) {if ($i > 0) $line = str_repeat(' ', $sizeLeftPart) . $line;$str[] = $line;}
$str = join("\n", $str);}
$ret .= $leftPart . str_repeat(' ', $keySizeDiff) . $stringSeparator . $str . "\n";}
return $ret;}
function inArray($needle, $haystack, $ignoreCase=TRUE, $ignoreSpaces=TRUE) {if (!is_array($haystack)) return FALSE;if ($ignoreCase)   $needle = strToLower($needle);if ($ignoreSpaces) $needle = trim($needle);reset($haystack);while (list($k, $v) = each($haystack)) {if ($ignoreCase)   $v = strToLower($v);if ($ignoreSpaces) $v = trim($v);if ($needle == $v) return TRUE;}
return FALSE;}
function max($array, $what='value') {if (!is_array($array)) return FALSE;$ret = FALSE;reset($array);while (list($k) = each($array)) {if (is_numeric($array[$k])) {if ($what == 'value') {if (($ret === FALSE) || ($array[$k] > $ret)) {$ret = $array[$k];}
} else {if (($ret === FALSE) || ($array[$k] > $array[$ret])) {$ret = $k;}
}
}
}
return ($what == 'value') ? (double)$ret : (string)$ret;}
function min($array, $what='value') {if (!is_array($array)) return FALSE;$ret = FALSE;reset($array);while (list($k) = each($array)) {if (is_numeric($array[$k])) {if ($what == 'value') {if (($ret === FALSE) || ($array[$k] < $ret)) {$ret = $array[$k];}
} else {if (($ret === FALSE) || ($array[$k] < $array[$ret])) {$ret = $k;}
}
}
}
if ($ret === FALSE) return FALSE;return ($what == 'value') ? (double)$ret : (string)$ret;}
function getLastKey($array) {end($array);return key($array);}
function setPos(&$array, $findKey) {if (is_numeric($findKey)) $findKey = (int)$findKey;reset($array);if (key($array) === $findKey) return;while (list($key) = each($array)) {if (key($array) === $findKey) {return TRUE;}
}
return FALSE;}
function getPos($array, $find, $findKey=TRUE, $ignoreCase=FALSE) {if (empty($array) || !is_array($array)) return FALSE;$lookfor = $findKey ? array_keys($array) : array_values($array);if ($ignoreCase) $find = strToLower($find);$size = sizeOf($lookfor);for($i=0; $i<$size; $i++) {if ($ignoreCase) {if (strToLower($lookfor[$i]) == $find) break;} else {if ($lookfor[$i] == $find) break;}
}
if ($i<$size) return $i;return FALSE;}
function guessType($array) {if (!is_array($array)) return NULL;if (sizeOf($array) == 0) return FALSE;reset($array);if (!is_numeric(key($array))) return 'hash';$i = 0;while (list($k) = each($array)) {if ($i++ > 100) break;if (!is_numeric($k)) return 'hash';}
if ($i < 100) { return 'vector';}
reset($array);if (key($array) === 0) return 'vector_guess';return 'hash_guess';}
function &padding($array, $pad_string=' ', $pad_length=0, $pad_type=STR_PAD_RIGHT) {if (!is_array($array)) return;if ($pad_length <=0) {reset($array);while(list($key) = each($array)) {$pad_length = max(strLen($array[$key]), $pad_length);}
}
reset($array);while(list($key) = each($array)) {$array[$key] = str_pad($array[$key], $pad_length, $pad_string, $pad_type);}
return $array;}
function &hashKeysToLower(&$hashArray) {if (!is_array($hashArray)) return array();if (version_compare(phpversion(), '4.2.0', '>=') >0) {return array_change_key_case($hashArray, CASE_LOWER);} else {$newHash = array();reset($hashArray);while (list($key) = each($hashArray)) {$newHash[strToLower($key)] = $hashArray[$key];}
return $newHash;}
}
function &hashKeysToUpper(&$hashArray) {if (!is_array($hashArray)) return array();if (version_compare(phpversion(), '4.2.0', '>=') >0) {return array_change_key_case($hashArray, CASE_UPPER);} else {$newHash = array();reset($hashArray);while (list($key) = each($hashArray)) {$newHash[strToUpper($key)] = $hashArray[$key];}
return $newHash;}
}
function copyValuesToKeys($arr) {$ret = array();foreach ($arr as $val) {$ret[(string)$val] = $val;}
return $ret;}
function splitKeyValue($array) {return array(array_keys($array), array_values($array));}
function intersect($arrayA, $arrayB) {$ret = array();$keys_A = array_keys($arrayA);$keys_B = array_keys($arrayB);$commenKeys = array_intersect($keys_A, $keys_B);foreach ($commenKeys as $key) {if ($arrayA[$key] === $arrayB[$key]) $ret[$key] = $arrayA[$key];}
return $ret;}
function diff($arrayA, $arrayB) {$ret = array();$commenArray = Bs_Array::intersect($arrayA, $arrayB);$commenKeys = array_keys($commenArray);foreach ($commenKeys as $key) {unset($arrayA[$key]);}
return $arrayA;}
function complement($arrayA, $arrayB) {$ret = array();$commenArray = Bs_Array::intersect($arrayA, $arrayB);$commenKeys = array_keys($commenArray);foreach($commenKeys as $key) {unset($arrayA[$key]);unset($arrayB[$key]);}
return array_merge($arrayA, $arrayB);}
function randVal($array) {if (is_array($array)) {$numArgs = sizeOf($array);if ($numArgs > 1) {mt_srand((double)microtime()*1000000);$randVal = mt_rand(1, $numArgs);$ret = $array[$randVal -1];} else if ($numArgs == 1) {$ret = $array[0];} else {$ret = '';}
} else {$ret = '';}
return $ret;}
function &reindex(&$arr, $startPos=0) {$newArr = array();reset($arr);while (list($k) = each($arr)) {$newArr[] = &$arr[$k];}
return $newArr;}
} $GLOBALS['Bs_Array'] =& new Bs_Array(); ?>