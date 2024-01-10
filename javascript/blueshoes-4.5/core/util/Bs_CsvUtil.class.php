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
define('BS_CSVUTIL_VERSION',   '4.5.$Revision: 1.2 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_CsvUtil extends Bs_Object {function Bs_CsvUtil() {parent::Bs_Object();}
function csvFileToArray($fullPath, $separator=';', $trim='none', $removeHeader=FALSE, $removeEmptyLines=FALSE, $checkMultiline=FALSE) {$fileContent = @file($fullPath);if (!$fileContent) return FALSE;while (list($k) = each($fileContent)) {if (substr($fileContent[$k], -2) == "\r\n") {$fileContent[$k] = substr($fileContent[$k], 0, -2);} elseif (substr($fileContent[$k], -1) == "\n") {$fileContent[$k] = substr($fileContent[$k], 0, -1);} elseif (substr($fileContent[$k], -1) == "\r") {$fileContent[$k] = substr($fileContent[$k], 0, -1);}
}
reset($fileContent);if ($checkMultiline) $fileContent = $this->_checkMultiline($fileContent);if (is_null($separator)) $separator = $this->guessSeparator($fileContent);return $this->csvArrayToArray($fileContent, $separator, $trim, $removeHeader, $removeEmptyLines);}
function csvStringToArray($string, $separator=';', $trim='none', $removeHeader=FALSE, $removeEmptyLines=FALSE, $checkMultiline=FALSE) {if (empty($string)) return array();$array = explode("\n", $string);while (list($k) = each($array)) {if (substr($array[$k], -1) == "\r") {$array[$k] = substr($array[$k], 0, -1);}
}
reset($array);if ((!is_array($array)) || empty($array)) return array();if ($checkMultiline) $array = $this->_checkMultiline($array);if (is_null($separator)) $separator = $this->guessSeparator($array);return $this->csvArrayToArray($array, $separator, $trim, $removeHeader, $removeEmptyLines);}
function csvArrayToArray($array, $separator=';', $trim='none', $removeHeader=FALSE, $removeEmptyLines=FALSE) {switch ($trim) {case 'none':
$trimFunction = FALSE;break;case 'left':
$trimFunction = 'ltrim';break;case 'right':
$trimFunction = 'rtrim';break;default: $trimFunction = 'trim';break;}
if (!is_string($separator)) $separator = ';';$sepLength = strlen($separator);if ($removeHeader) {array_shift($array);}
$ret = array();reset($array);while (list(,$line) = each($array)) {$offset    = 0;$lastPos   = 0;$lineArray = array();do {$pos = strpos($line, $separator, $offset);if ($pos === FALSE) {$lineArray[] = substr($line, $lastPos);break;}
$currentSnippet = substr($line, $lastPos, $pos-$lastPos);$numQuotes = substr_count($currentSnippet, '"');if (($numQuotes % 2) == 0) {$lineArray[] = substr($line, $lastPos, $pos-$lastPos);$lastPos = $pos + $sepLength;} else {}
$offset = $pos + $sepLength;} while (TRUE);if ($trimFunction !== FALSE) {while (list($k) = each($lineArray)) {$lineArray[$k] = $trimFunction($lineArray[$k]);}
reset($lineArray);}
while (list($k) = each($lineArray)) {if ((substr($lineArray[$k], 0, 1) == '"') && (substr($lineArray[$k], 1, 1) != '"') && (substr($lineArray[$k], -1) == '"')) {$lineArray[$k] = substr($lineArray[$k], 1, -1);}
$lineArray[$k] = str_replace('""', '"', $lineArray[$k]);}
reset($lineArray);$addIt = TRUE;if ($removeEmptyLines) {do {while (list($k) = each($lineArray)) {if (!empty($lineArray[$k])) break 2;}
$addIt = FALSE;} while (FALSE);reset($lineArray);}
if ($addIt) {$ret[] = $lineArray;}
}
return $ret;}
function arrayToCsvString($array, $separator=';', $trim='none', $removeEmptyLines=TRUE) {if (!is_array($array) || empty($array)) return '';switch ($trim) {case 'none':
$trimFunction = FALSE;break;case 'left':
$trimFunction = 'ltrim';break;case 'right':
$trimFunction = 'rtrim';break;default: $trimFunction = 'trim';break;}
$ret = array();reset($array);if (is_array(current($array))) {while (list(,$lineArr) = each($array)) {if (!is_array($lineArr)) {$ret[] = array();} else {$subArr = array();while (list(,$val) = each($lineArr)) {$val      = $this->_valToCsvHelper($val, $separator, $trimFunction);$subArr[] = $val;}
}
$ret[] = join($separator, $subArr);}
return join("\n", $ret);} else {while (list(,$val) = each($array)) {$val   = $this->_valToCsvHelper($val, $separator, $trimFunction);$ret[] = $val;}
return join($separator, $ret);}
}
function guessSeparator($cvsArray) {if (empty($cvsArray)) return FALSE;$testThese = array(';', ',', "\t", '|', ' ');foreach ($testThese as $char) {$numMatch = 0;$numLines = 0;foreach ($cvsArray as $line) {$numLines++;if (empty($line)) continue; if (strpos($line, $char) !== FALSE) {$numMatch++;if ($numMatch >= 4) return $char; } else {if (strlen($line) > 10) {break;}
}
}
if ($numLines == sizeOf($cvsArray)) {if ($numMatch > 0) return $char;}
}
return FALSE;}
function _valToCsvHelper($val, $separator, $trimFunction) {if ($trimFunction) $val = $trimFunction($val);$needQuote = FALSE;do {if (strpos($val, '"') !== FALSE) {$val = str_replace('"', '""', $val);$needQuote = TRUE;break;}
if (strpos($val, $separator) !== FALSE) {$needQuote = TRUE;break;}
if ((strpos($val, "\n") !== FALSE) || (strpos($val, "\r") !== FALSE)) { $needQuote = TRUE;break;}
} while (FALSE);if ($needQuote) {$val = '"' . $val . '"';}
return $val;}
function _checkMultiline($in) {$ret = array();$stack = FALSE;reset($in);while (list(,$line) = each($in)) {$c = substr_count($line, '"');if ($c % 2 == 0) {if ($stack === FALSE) {$ret[] = $line;} else {$stack .= "\n" . $line;}
} else {if ($stack === FALSE) {$stack = $line;} else {$ret[] = $stack . "\n" . $line;$stack = FALSE;}
}
}
return $ret;}
} ?>