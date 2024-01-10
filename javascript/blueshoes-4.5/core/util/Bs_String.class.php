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
define('BS_STRING_VERSION',      '4.5.$Revision: 1.6 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_String extends Bs_Object {function Bs_String() {parent::Bs_Object(); }
function left($haystack, $num=0) {if (($haystack == '') || ($num <= 0)) return '';return substr($haystack, 0, $num);}
function right($haystack, $num=0) {if ($num == '') return $haystack;if (($haystack == '') || ($num == 0)) return '';return substr($haystack, -$num);}
function mid($haystack, $start=1, $num=0) {if ($num == '') return $haystack;if (($haystack == '') || ($num == 0)) return '';if ($start == 0) $start = 1; return substr($haystack, $start -1, $num);}
function insert(&$hayStack, $addThis, $position) {if ($addThis == '') return TRUE;if ($position < 0) return FALSE;$lenHaystack = strlen($hayStack);if (($lenHaystack) < $position) return FALSE;if ($position == 0) {$hayStack = $addThis . $hayStack;return TRUE;}
if ($position == ($lenHaystack)) {$hayStack .= $addThis;return TRUE;}
$t  = $this->left($hayStack, $position);$t .= $addThis;$t .= $this->right($hayStack, $lenHaystack - $position);$hayStack = $t; return TRUE;}
function removeFromToInt($string, $from, $to) {$t = substr($string, 0, $from);$t .= substr($string, $to +1);return $t;}
function inStr($haystack, $needle) {if (strlen($needle) == 0) return FALSE;$pos = strpos($haystack, $needle);return ($pos !== FALSE);}
function inStrI($haystack, $needle) {return ($this->inStr(strToLower($haystack), strToLower($needle)));}
function startsWith($haystack, $needle, $ignoreSpaces=TRUE) {if ($ignoreSpaces) $haystack = ltrim($haystack);if (strlen($haystack) < strlen($needle)) return FALSE;if (strlen($needle) == 0) return FALSE;$haystack = ' ' . $haystack;$pos = strpos($haystack, $needle);if ($pos == 1) {return TRUE;} else {return FALSE;}
}
function startsWithI($haystack, $needle, $ignoreSpaces=TRUE) {return ($this->startsWith(strToLower($haystack), strToLower($needle), $ignoreSpaces));}
function endsWith($haystack, $needle, $ignoreSpaces=TRUE) {if ($ignoreSpaces) $haystack = trim($haystack);if (strlen($haystack) < strlen($needle)) return FALSE;if (strlen($needle) == 0) return FALSE;$endOfString = substr($haystack, -strlen($needle));if ($needle == $endOfString) {return TRUE;} else {return FALSE;}
}
function endsWithI($haystack, $needle, $ignoreSpaces=TRUE) {return ($this->endsWith(strToLower($haystack), strToLower($needle), $ignoreSpaces));}
function strrpos($haystack, $needle, $offset=NULL) {$lastPos = FALSE;$needleLength = strlen($needle);$i = 0;while (TRUE) {if ($lastPos !== FALSE) {$pos = strpos($haystack, $needle, $lastPos + $needleLength);} else {$pos = strpos($haystack, $needle);}
if ($pos === FALSE) return $lastPos;if ((!is_null($offset)) && ($pos > $offset)) return $lastPos;$lastPos = $pos;}
return $lastPos; }
function oneOf() {$numArgs = func_num_args();$argList = func_get_args();if ($numArgs > 1) {mt_srand((double)microtime()*1000000);$randVal = mt_rand(1, $numArgs);$ret = $argList[$randVal -1];} else if ($numArgs == 1) {$ret = $argList[0];} else {$ret = '';}
return $ret;}
Function hasSpecialChars($myString, $charSet=7, $myExceptions=NULL) {if ($myString == '') return FALSE;$exceptionString = '';if ((is_array($myExceptions)) && (sizeOf($myExceptions) > 0)) {$escapeChars = array('^', '.', '[', '$', '(', ')', '|', '*', '+', '?', '{', '\\');while (list($k) = each($myExceptions)) {if (in_array($myExceptions[$k], $escapeChars)) {$exceptionString .= '\\' . $myExceptions[$k];} else {$exceptionString .= $myExceptions[$k];}
}
}
$regExp = "^[a-zA-Z0-9" . $exceptionString . "]*$";return (bool)!ereg($regExp, $myString);}
function clean($str, $type='alphanum', $allowThese='') {if (!empty($allowThese)) {$escapeChars = array('^', '.', '[', '$', '(', ')', '|', '*', '+', '?', '{', '\\');foreach ($escapeChars as $escapeChar) {$allowThese = str_replace($escapeChar, "\\" . $escapeChar, $allowThese);}
}
switch($type){case 'alpha':
return(ereg_replace("[^a-zA-Z{$allowThese}]", '', $str));break;case 'num':
return(ereg_replace("[^0-9{$allowThese}]", '', $str));break;case 'noalpha':
return(ereg_replace("[a-zA-Z]", '', $str));break;case 'nonum':
return(ereg_replace("[0-9]", '', $str));break;case 'noalphanum':
return(ereg_replace("[0-9a-zA-Z]", '', $str));break;case 'nohtmlentities':
return(ereg_replace("&[[:alnum:]]{0,};", '', $str));break;default: return(ereg_replace("[^0-9a-zA-Z{$allowThese}]", '', $str));}
}
function normalize($param, $specialDouble=TRUE) {if ($specialDouble) {$ts = array('/Ф/','/ж/','/м/','/ф/','/і/','/ќ/','/Х/','/х/');$tn = array('AE', 'OE', 'UE', 'ae', 'oe', 'ue', 'AA', 'aa', );$param = preg_replace($ts, $tn, $param);}
$ts = array("/[Р-Х]/","/Ц/","/Ч/","/[Ш-Ы]/","/[Ь-Я]/","/а/","/б/","/[в-жи]/","/з/","/[й-м]/","/н/","/п/","/[р-х]/","/ц/","/ч/","/[ш-ы]/","/[ь-я]/","/№/","/ё/","/[ђ-іј]/","/ї/","/[љ-ќ]/","/[§-џ]/");$tn = array("A",      "AE", "C",  "E",      "I",      "D",  "N",  "O",       "X",  "U",      "Y",  "ss", "a",      "ae", "c",  "e",      "i",      "d",  "n",  "o",       "x",  "u",      "y");return preg_replace($ts, $tn, $param);}
function isUpper($string) {return !(bool)(ereg('[a-z]', $string));}
function isLower($string) {return !(bool)(ereg('[A-Z]', $string));}
function ucWords($string, $addChars=null) {if (is_null($addChars)) return ucWords($string); $defaultChars = array(chr(32), chr(12), chr(10), chr(13), chr(9), chr(11));$chars = array_merge($addChars, $defaultChars);$chars = array_flip($chars); $length   = strlen($string);$string   = strToUpper(substr($string, 0, 1)) . substr($string, 1); for ($i=1; $i<$length; $i++) {$char = $string[$i -1];if (isSet($chars[$char])) {$string = substr($string, 0, $i) . strToUpper(substr($string, $i, 1)) . substr($string, $i +1);}
}
return $string;}
function lcFirst($string) {$s = strlen($string);if ($s == 0) return '';if ($s == 1) return strToLower($string);return strToLower(substr($string, 0, 1)) . substr($string, 1);}
function studlyCapsToSeparated($word) {$ret       = '';$num       = strlen($word);$stack     = $word[0];$stackType = $this->_studlyCapsToSeparated_helper($word[0]);for ($i=1; $i<$num; $i++) {$doBreak = FALSE;$newStackType = $this->_studlyCapsToSeparated_helper($word[$i]);if ($newStackType == $stackType) {} else {if (!is_null($newStackType)) {$doBreak = TRUE;}
if (strlen($stack) > 1) {$lastTypeInStack = $this->_studlyCapsToSeparated_helper(substr($stack, -1));if ($lastTypeInStack == 'u') {if ($ret != '') $ret .= ' ';$ret .= substr($stack, 0, -1);$stackType = NULL; $stack     = substr($stack, -1) . $word[$i];continue;} else {$doBreak = TRUE;}
}
}
if ($doBreak) {if ($ret != '') $ret .= ' ';$ret   .= $stack;$stack  = '';}
$stack    .= $word[$i];$stackType = $this->_studlyCapsToSeparated_helper($word[$i]);}
if ($stack != '') {if ($ret != '') $ret .= ' ';$ret .= $stack;}
return ucWords($ret);}
function _studlyCapsToSeparated_helper($char) {$asc = ord($char);if (($asc >= 65) && ($asc <= 90)) {return 'u';} elseif (($asc >= 48) && ($asc <= 57)) {return 'n';} else {return NULL;}
}
function escapeForRegexp($string, $borderChar='/') {static $replace_pairs = NULL;if (empty($replace_pairs)) {$search  = array('\\', '^', '.', '[', '$', '(', ')', '|', '*', '+', '?', '{', ']', '}', '=', '!', '<', '>', ':');$replace_pairs[$borderChar] = $borderChar;foreach($search as $char) $replace_pairs[$char] = '\\' . $char;}
return strtr($string, $replace_pairs);}
function addLineNumbers(&$str, $start=1, $indent=3) {$line = explode("\n", $str);$size = sizeOf($line);$with  = strlen((string) ($start + $size -1));$indent = max($with, $indent);for ($i=0; $i<$size; $i++) {$line[$i] = str_pad((string)($i+$start), $indent, ' ', STR_PAD_LEFT) . ': ' . $line[$i];}
return implode("\n",$line);}
function sow($string, $insert, $increment) {$insert_len = strlen($insert);$string_len = strlen($string);$string_len_ending = $string_len + intval( $insert_len * ($string_len / $increment));$i = $increment - 1;while($string_len_ending > $i) {$string = substr($string, 0, $i) . $insert . substr($string, $i);$i = $i + $increment + $insert_len;}
return $string;}
function rot13($rot13text) {$rot13text_rotated = "";for ($i = 0; $i <= strlen($rot13text); $i++) {$k = ord(substr($rot13text, $i, 1));if ($k >= 97 and $k <= 109) {$k = $k + 13;} elseif ($k >= 110 and $k <= 122) {$k = $k - 13;} elseif ($k >= 65 and $k <= 77) {$k = $k + 13;} elseif ($k >= 78 and $k <= 90) {$k = $k - 13;} 
$rot13text_rotated = $rot13text_rotated . Chr($k);} 
return $rot13text_rotated;}
function booleanSearchQuery($searchString="", $searchFieldString="") {$searchFieldString = trim($searchFieldString);$searchString = strtolower(trim($searchString));$searchFieldArray = explode(" ", $searchFieldString);$searchArray      = explode(" ", $searchString); $searchString = str_replace("\\\"",    "",   $searchString);$searchString = str_replace("(",       "",   $searchString);$searchString = str_replace(")",       "",   $searchString);$searchString = str_replace("*",       "",   $searchString);$searchString = str_replace(" and ",   " +", $searchString);$searchString = str_replace(" und ",   " +", $searchString);$searchString = str_replace(" not ",   " -", $searchString);$searchString = str_replace(" nicht ", " -", $searchString);$i = 0;while(list($dev0, $val) = each($searchArray)) {if ($val != "") {if ($val[0] == "-") {$queryArray[$i]["operator"] = "AND NOT";$queryArray[$i]["string"]   = "LIKE '%" . substr($val, 1) . "%'";} elseif ($val[0] == "+") {$queryArray[$i]["operator"] = "AND";$queryArray[$i]["string"]   = "LIKE '%" . substr($val, 1) . "%'";} else {$queryArray[$i]["operator"] = "AND";$queryArray[$i]["string"]   = "LIKE '%" . $val . "%'";}
}
$i++;}
$ret = "";for ($i=0; $i<count($queryArray); $i++) {if ($i > 0) {$ret .= $queryArray[$i]["operator"] . " (";} else {$ret .= "(";}
for ($j=0; $j<count($searchFieldArray); $j++) {if ($j > 0) $ret .= "OR ";$ret .= "LOWER(" . $searchFieldArray[$j] . ") ";$ret .= $queryArray[$i]["string"] . " ";}
$ret .= ") ";}
return $ret;}
} $GLOBALS['Bs_String'] = new Bs_String(); ?>