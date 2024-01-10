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
define('BS_TEXTUTIL_VERSION',      '4.5.$Revision: 1.9 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'util/Bs_String.class.php');require_once($APP['path']['core'] . 'util/Bs_Array.class.php');class Bs_TextUtil extends Bs_Object {var $_Bs_String;var $_Bs_Array;function Bs_TextUtil() {parent::Bs_Object(); $this->_Bs_String =& $GLOBALS['Bs_String'];$this->_Bs_Array  =& $GLOBALS['Bs_Array'];}
function getLanguageDependentValue($var, $lang='en') {if (is_array($var) && (substr($this->_Bs_Array->guessType($var), 0, 4) == 'hash')) {if (isSet($var[$lang])) {return $var[$lang]; }
if (strlen($lang) > 2) {$t = substr($lang, 0, 2);if (isSet($var[$t])) return $var[$t];}
if (isSet($var[''])) return $var[''];if (sizeOf($var) == 0) return ''; reset($var);return current($var);} else {return $var;}
}
function parseSearchQuery($string) {$ret = array();if (empty($string)) return $ret;$string = strToLower($string);$string = $this->_Bs_String->normalize($string);$string = ' ' . $string; $string = str_replace(" and ",   " &", $string); $string = str_replace(" und ",   " &", $string); $string = str_replace(" + ",     " &", $string); $string = str_replace(" +",      " &", $string); $string = str_replace(" or ",    " |", $string); $string = str_replace(" oder ",  " |", $string); $string = str_replace(" not ",   " !", $string); $string = str_replace(" nicht ", " !", $string); $string = str_replace(" - ",     " !", $string); $string = str_replace(" -",      " !", $string); $line      = $string;$separator = ' ';$sepLength = strlen($separator);$offset    = 0;$lastPos   = 0;$lineArray = array();do {$pos = strpos($line, $separator, $offset);if ($pos === FALSE) {$val = trim(substr($line, $lastPos));if (!empty($val)) $lineArray[] = $val;break;}
$currentSnippet = substr($line, $lastPos, $pos-$lastPos);$numQuotes = substr_count($currentSnippet, '"');if ($numQuotes % 2 == 0) {$val = trim(substr($line, $lastPos, $pos-$lastPos));if (!empty($val)) $lineArray[] = $val;$lastPos = $pos + $sepLength;} else {}
$offset = $pos + $sepLength;} while (TRUE);while (list($k) = each($lineArray)) {$lineArray[$k] = str_replace('"', '', $lineArray[$k]);}
reset($lineArray);$searchArray = $lineArray;while (list(,$word) = each($searchArray)) {if (empty($word)) continue;$prefix = substr($word, 0, 1);switch ($prefix) {case '&':
case '!':
case '|':
$operator = $prefix;$word = substr($word, 1);break;default:
$operator = '|';}
if (substr($word, 0, 1) == '~') {$fuzzy = TRUE;$word  = substr($word, 1);} else {$fuzzy = FALSE;}
$word   = str_replace('~', '', $word); $phrase = $word;$word   = explode(' ', $word);$ret[] = array('phrase'=>$phrase, 'words'=>$word, 'operator'=>$operator, 'fuzzy'=>$fuzzy);}
return $ret;}
function parseSearchQuery2($string) {$ret = array();if (empty($string)) return $ret;$string = strToLower($string);$string = $this->_Bs_String->normalize($string);$string = ' ' . $string; $string = str_replace(" and ",   " &", $string); $string = str_replace(" und ",   " &", $string); $string = str_replace(" + ",     " &", $string); $string = str_replace(" +",      " &", $string); $string = str_replace(" or ",    " |", $string); $string = str_replace(" oder ",  " |", $string); $string = str_replace(" not ",   " !", $string); $string = str_replace(" nicht ", " !", $string); $string = str_replace(" - ",     " !", $string); $string = str_replace(" -",      " !", $string); $stringJunks = array();$stringCopy  = $string;$lastPos     = 0;do {$posBracket = strpos($stringCopy, '(', $lastPos);if ($posBracket === FALSE) {$stringJunks[] = $stringCopy;break;} else {if ((substr_count(substr($stringCopy, 0, $posBracket), '"') % 2) == 1) {$lastPos = $posBracket +1;continue;}
if ($posBracket > 0) {$lastChar = substr($stringCopy, $posBracket -1, 1);if (($lastChar == '&') || ($lastChar == '|') || ($lastChar == '!') || ($lastChar == '~')) {$posBracket--; }
}
$posBracket2 = strpos($stringCopy, ')', $posBracket);if ($posBracket2 === FALSE) $posBracket2 = strlen($stringCopy) +1;$lastPos = $posBracket2;$stringJunks[] = substr($stringCopy, 0, $posBracket);$stringJunks[] = substr($stringCopy, $posBracket, $posBracket2 -$posBracket +1);if (strlen($stringCopy) <= $posBracket2 +1) {break;} else {$stringCopy = substr($stringCopy, $posBracket2 +1);$lastPos -= $posBracket2;}
}
} while (TRUE);$inBrackets = FALSE;foreach ($stringJunks as $line) {$inBrackets = (substr($line, -1) === ')');if ($inBrackets) {$beforeBracketChar = substr($line, 0, 1);if (in_array($beforeBracketChar, array('&', '|', '!'))) {$beforeBracketOperator = $beforeBracketChar;$line = substr($line, 1); } else {$beforeBracketOperator = '&'; }
} else {$beforeBracketOperator = FALSE;}
$line = str_replace('(', '', $line);$line = str_replace(')', '', $line);unset($bracketsArr);$bracketsArr = array();$separator = ' ';$sepLength = strlen($separator);$offset    = 0;$lastPos   = 0;$lineArray = array();do {$pos = strpos($line, $separator, $offset);if ($pos === FALSE) {$val = trim(substr($line, $lastPos));if (!empty($val)) $lineArray[] = $val;break;}
$currentSnippet = substr($line, $lastPos, $pos-$lastPos);$numQuotes = substr_count($currentSnippet, '"');if ($numQuotes % 2 == 0) {$val = trim(substr($line, $lastPos, $pos-$lastPos));if (!empty($val)) $lineArray[] = $val;$lastPos = $pos + $sepLength;} else {}
$offset = $pos + $sepLength;} while (TRUE);while (list($k) = each($lineArray)) {$lineArray[$k] = str_replace('"', '', $lineArray[$k]);}
reset($lineArray);$searchArray = $lineArray;$near = FALSE;if (!isSet($whileI)) $whileI = -1;while (list(,$word) = each($searchArray)) {if (empty($word)) continue;$prefix = substr($word, 0, 1);switch ($prefix) {case '&':
case '!':
case '|':
$operator = $prefix;$word = substr($word, 1);break;default:
$operator = '&'; }
if (substr($word, 0, 1) == '~') {$fuzzy = TRUE;$word  = substr($word, 1);} else {$fuzzy = FALSE;}
$phrase = $word;if ($phrase === 'near') {$near = $whileI;continue;}
$word2  = explode(' ', $word);$word   = array();foreach ($word2 as $wordString) {if (substr($wordString, -1) === '#') {$wordString = substr($wordString, 0, -1);$stem = TRUE;} else {$stem = FALSE;}
if (substr($wordString, 0, 1) === '~') {$wordString = substr($wordString, 1);$fuzzyOnWord = TRUE;} else {$fuzzyOnWord = FALSE;}
$part = (strpos($wordString, '*') !== FALSE);$word[] = array('word'=>$wordString, 'stem'=>$stem, 'fuzzy'=>$fuzzyOnWord, 'part'=>$part);}
$neighbor = (sizeOf($word) > 1);if (empty($bracketsArr)) {if (($inBrackets && ($beforeBracketOperator === '|')) || ($operator === '|')) {if (!isSet($ret[$whileI]['list'])) {$ret[$whileI]['list'] = array();}
if (isSet($ret[$whileI]['phrase'])) {$tempOperator = (($ret[$whileI]['operator'] === '&') || ($ret[$whileI]['operator'] === '!')) ? '|' : $ret[$whileI]['operator'];$ret[$whileI]['list'][] = array('phrase'=>$ret[$whileI]['phrase'], 'words'=>$ret[$whileI]['words'], 'operator'=>$tempOperator, 'fuzzy'=>$ret[$whileI]['fuzzy'], 'near'=>$ret[$whileI]['near'], 'neighbor'=>$ret[$whileI]['neighbor']);unset($ret[$whileI]['phrase']);unset($ret[$whileI]['words']);unset($ret[$whileI]['neighbor']);}
$ref = array();$ret[$whileI]['list'][] = &$ref;} else {$ref = array();$ret[] = &$ref;$whileI++;}
}
if ($inBrackets) {if (!isSet($ref['list'])) {$ref['list'] = &$bracketsArr; $ref['operator'] = $beforeBracketOperator; $ref['fuzzy']    = $fuzzy;$ref['near']     = $near;}
if ((sizeOf($bracketsArr) == 1) && (($operator === '|') || ($operator === '!')) && ($bracketsArr[0]['operator'] === '&')) {$bracketsArr[0]['operator'] = '|';}
$bracketsArr[] = array('phrase'=>$phrase, 'words'=>$word, 'operator'=>$operator, 'fuzzy'=>$fuzzy, 'near'=>$near, 'neighbor'=>$neighbor);} else {$ref = array('phrase'=>$phrase, 'words'=>$word, 'operator'=>$operator, 'fuzzy'=>$fuzzy, 'near'=>$near, 'neighbor'=>$neighbor);}
unset($ref);$near = FALSE;}
}
return $ret;}
function ordinal($num=1) {$ords = array("th","st","nd","rd");$val = $num;if ((($num%=100)>9 && $num<20) || ($num%=10)>3) $num=0;return $val . $ords[$num];}
function pluralS($count) {return ($count==1) ? '' : 's';}
function shortenString($text, $length, $suffix='...') {$length_text = strlen($text);$length_symbol = strlen($suffix);if($length_text <= $length || $length_text <= $length_symbol || $length <= $length_symbol) {return($text);} else {return(substr($text, 0, $length - $length_symbol) . $suffix);}
}
function abbreviateString($text, $maxLength = 12) {if ($maxLength<2) $maxLength=2;$words = preg_split('/\s+/', $text); $abbrWords = array();$sW = sizeOf($words);for ($i=0; $i<$sW; $i++) {$firstChar =  $words[$i][0];if (preg_match('/^[A-ZÄÖÜ]/', $firstChar)) { $abbrWords[$i] = $firstChar . '.';} else {$abbrWords[$i] = '';}
}
$newWords = $words;for ($i=$sW-1; $i>=0; $i--) {if (strLen(implode(' ', $newWords)) > $maxLength) {if (empty($abbrWords[$i])) {unset($newWords[$i]);} else {$newWords[$i] = $abbrWords[$i];}
} else {break;}
}
while (strLen(implode(' ', $newWords)) > $maxLength) {array_pop($newWords);}
return implode(' ', $newWords);}
function longestCommonSubstring($string1, $string2) {$L = array();$length1 = strlen($string1);$length2 = strlen($string2);for ($i = $length1; $i >= 0; $i--)
{for ($j = $length2; $j >= 0; $j--)
{if ($string1[$i] == '' || $string2[$j] == '') $L[$i][$j] = 0;elseif ($string1[$i] == $string2[$j]) $L[$i][$j] =
1 + $L[$i + 1][$j + 1];else $L[$i][$j] = max($L[$i + 1][$j], $L[$i][$j + 1]);}
}
$substring = '';$i = 0;$j = 0;while ($i < $length1 && $j < $length2)
{if ($string1[$i] == $string2[$j])
{$substring .= $string1[$i];$i++; $j++;}
elseif ($L[$i + 1][$j] >= $L[$i][$j + 1]) $i++;else $j++;}
return $substring;}
function percentUppercase($string) {$lower = strToLower($string);$lev   = levenshtein($string, $lower);return (int)($lev / strlen($string) * 100);}
}
$GLOBALS['Bs_TextUtil'] =& new Bs_TextUtil();if (basename($_SERVER['PHP_SELF']) == 'Bs_TextUtil.class.php') {$testAbbrTxt = array("Hi, my Name is Sam", "The Quick Red Fox", "Wath is the Q in QWW?");echo "<pre>\n";foreach ($testAbbrTxt as $txt) {echo "Abbreviate '{$txt}' :  '". Bs_TextUtil::abbreviateString($txt, 12) ."'<br>\n";}
}
?>