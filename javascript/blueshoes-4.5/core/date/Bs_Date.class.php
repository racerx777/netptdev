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
define('BS_DATE_VERSION',      '4.5.$Revision: 1.2 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'util/Bs_String.class.php');class Bs_Date extends Bs_Object {var $_Bs_String;function Bs_Date() {parent::Bs_Object(); $this->_Bs_String = &$GLOBALS['Bs_String'];}
function now() {return $this->formatUnixTimestamp('us-1');}
function formatUnixTimestamp($style='eu-2', $unixTimestamp=NULL) {$format = $this->getFormatForStyle($style);if (is_null($unixTimestamp)) {return date($format);} else {return date($format, $unixTimestamp);}
}
function formatArray($style='eu-2', $array=NULL) {$format = $this->getFormatForStyle($style);if (is_null($array)) {return date($format);} else {return str_replace(array('Y', 'm', 'd', 'H', 'i', 's'), $array, $format);}
}
function getFormatForStyle($style='eu-2') {$style = strToLower($style);switch ($style) {case 'eu-1':
$format = "d.m.Y H:i:s";break;case 'eu-2':
$format = "d.m.Y H:i";break;case 'eu-3':
$format = "d.m.Y";break;case 'eu-4':
$format = "H:i:s";break;case 'eu-5':
$format = "H:i";break;case 'us-1':
$format = "Y/m/d H:i:s";break;case 'us-2':
$format = "Y/m/d H:i";break;case 'us-3':
$format = "Y/m/d";break;case 'us-4':
$format = "H:i:s";break;case 'us-5':
$format = "H:i";break;case 'sql-1':
$format = "Y-m-d H:i:s";break;case 'sql-2':
$format = "Y-m-d H:i";break;case 'sql-3':
$format = "Y-m-d";break;case 'sql-4':
$format = "H:i:s";break;case 'sql-5':
$format = "H:i";break;case 'ts':
case 'ts-1':
$format = "YmdHis";break;default:
$format = "d.m.Y H:i";}
return $format;}
function formatUsDatetime($style='eu-2', $usDatetime=NULL) {if (($usDatetime != NULL) && ($usDatetime != '')) {return $this->formatUnixTimestamp($style, $this->usDatetimeToUnixTimestamp($usDatetime));} else {return $this->formatUnixTimestamp($style);}
}
function formatEuDatetime($style='eu-2', $euDatetime=NULL) {if (($euDatetime != NULL) && ($euDatetime != '')) {return $this->formatUnixTimestamp($style, $this->euDatetimeToUnixTimestamp($euDatetime));} else {return $this->formatUnixTimestamp($style);}
}
function formatSqlDatetime($style='eu-2', $sqlDatetime=NULL) {if (($sqlDatetime != NULL) && ($sqlDatetime != '')) {return $this->formatUnixTimestamp($style, $this->sqlDatetimeToUnixTimestamp($sqlDatetime));} else {return $this->formatUnixTimestamp($style);}
}
function formatSqlTimestamp($style='eu-2', $sqlTimestamp=NULL) {if (($sqlTimestamp != NULL) && ($sqlTimestamp != '')) {return $this->formatUnixTimestamp($style, $this->sqlTimestampToUnixTimestamp($sqlTimestamp));} else {return $this->formatUnixTimestamp($style);}
}
function sqlTimestampToUnixTimestamp($sqlTimestamp) {$s = &$sqlTimestamp; return mktime($this->_Bs_String->mid($s, 9, 2), $this->_Bs_String->mid($s, 11, 2), $this->_Bs_String->mid($s, 13, 2), $this->_Bs_String->mid($s, 5, 2), $this->_Bs_String->mid($s, 7, 2), $this->_Bs_String->left($s, 4));}
function timeToUnixTimestamp($time) {return strToTime($time);}
function usDatetimeToUnixTimestamp($usDatetime) {$unixTimestamp = strToTime($usDatetime);if ($unixTimestamp == -1) {$funcArgs = func_get_args();return new Bs_Exception('not a valid usDatetime specified', __FILE__, __LINE__, $funcArgs);} else {return $unixTimestamp;}
}
function usDateToUnixTimestamp($usDate) {return strToTime($usDate);}
function usTimeToUnixTimestamp($usTime) {return $this->timeToUnixTimestamp($usTime);}
function sqlDatetimeToUnixTimestamp($sqlDatetime) {return strToTime($sqlDatetime);}
function sqlDateToUnixTimestamp($sqlDate) {return strToTime($sqlDate);}
function sqlTimeToUnixTimestamp($sqlTime) {return $this->timeToUnixTimestamp($sqlTime);}
function euDatetimeToUnixTimestamp($euDatetime) {$euDatetime = trim($euDatetime);$strlenEuDatetime = strlen($euDatetime); if (($strlenEuDatetime >= 12) && ($strlenEuDatetime <= 19) && (strpos($euDatetime, " ", 6) >= 6)) {$t = explode(" ", $euDatetime);if (is_array($t)) {if (sizeOf($t) == 2) {$partDate = trim($t[0]);$partTime = trim($t[1]);} elseif (sizeOf($t) > 2) {$tStep = FALSE;while(list($k, $v) = each($t)) {if (strlen($v) >= 4) {if ($tStep) {$partTime = trim($v);break;} else {$partDate = trim($v);$tStep    = TRUE;}
}
}
} else {return -1;}
} else {return -1;}
$timestampOnlyDate = $this->euDateToUnixTimestamp($partDate);if ($timestampOnlyDate == -1) return -1;$usDate            = $this->formatUnixTimestamp('us-3', $timestampOnlyDate);if ($usDate == -1) return -1;return $this->usDatetimeToUnixTimestamp($usDate . " " . $partTime);} elseif (($strlenEuDatetime >= 6) && ($strlenEuDatetime <= 10)) {return $this->euDateToUnixTimestamp($euDatetime);} else {return -1;}
}
function euDateToArray($euDate) {$ret = array('year'=>'0000', 'month'=>'00', 'day'=>'00', 'hour'=>'00', 'min'=>'00', 'sec'=>'00');if (strlen($euDate) >= 6) {if (strlen($euDate) == 10) {$ret['year']  = $this->_Bs_String->right($euDate, 4);$ret['month'] = $this->_Bs_String->mid($euDate, 4, 2);$ret['day']   = $this->_Bs_String->left($euDate, 2);} else {$separator = $this->getSeparator($euDate);if ($separator === FALSE) return FALSE; $array = explode($separator, $euDate);if (sizeOf($array) == 3) {$ret['day']   = $array[0];$ret['month'] = $array[1];$ret['year']  = $array[2];$ret = $this->cleanDateArray($ret);} else {return FALSE;}
}
if (checkdate($ret['month'], $ret['day'], $ret['year'])) {return $ret;} else {return FALSE;}
} else {return FALSE;}
}
function sqlDateToArray($sqlDate) {$ret = array('year'=>'0000', 'month'=>'00', 'day'=>'00', 'hour'=>'00', 'min'=>'00', 'sec'=>'00');if (strlen($sqlDate) >= 6) {if (strlen($sqlDate) == 10) {$ret['year']  = $this->_Bs_String->left($sqlDate, 4);$ret['month'] = $this->_Bs_String->mid($sqlDate, 6, 2);$ret['day']   = $this->_Bs_String->right($sqlDate, 2);} else {$separator = $this->getSeparator($sqlDate);if ($separator === FALSE) return FALSE; $array = explode($separator, $sqlDate);if (sizeOf($array) == 3) {$ret['day']   = $array[2];$ret['month'] = $array[1];$ret['year']  = $array[0];$ret = $this->cleanDateArray($ret);} else {return FALSE;}
}
if (checkdate($ret['month'], $ret['day'], $ret['year'])) {return $ret;} else {return FALSE;}
} else {return FALSE;}
}
function getSeparator($date) {if (strpos($date, '.')) {return '.';} elseif (strpos($date, '/')) {return '/';} elseif (strpos($date, '-')) {return '-';} elseif (strpos($date, ' ')) {return ' ';}
return FALSE;}
function cleanDateArray($arr) {if (!is_array($arr)) return array('year'=>'0000', 'month'=>'00', 'day'=>'00', 'hour'=>'00', 'min'=>'00', 'sec'=>'00');if (!isSet($arr['year']))  $arr['year']  = '0000';if (!isSet($arr['month'])) $arr['month'] = '00';if (!isSet($arr['day']))   $arr['day']   = '00';if (!isSet($arr['hour']))  $arr['hour']  = '00';if (!isSet($arr['min']))   $arr['min']   = '00';if (!isSet($arr['sec']))   $arr['sec']   = '00';if (strlen($arr['day'])   == 1) $arr['day']   = '0' . $arr['day'];if (strlen($arr['month']) == 1) $arr['month'] = '0' . $arr['month'];if (strlen($arr['year'])  == 2) {if ($arr['year'] < 30) {if (strlen($arr['year']) == 0) {$arr['year'] = '2000';} elseif (strlen($arr['year']) == 1) {$arr['year'] = '200' . $arr['year'];} else {$arr['year'] = '20' . $arr['year'];}
} else {$arr['year']  = "19" . $arr['year'];}
}
return $arr;}
function euDateToUnixTimestamp($euDate) {if (strlen($euDate) >= 6) {if (strlen($euDate) == 10) {$myYear  = $this->_Bs_String->right($euDate, 4);$myMonth = $this->_Bs_String->mid($euDate, 4, 2);$myDay   = $this->_Bs_String->left($euDate, 2);} else {$array = explode(".", $euDate);if (sizeOf($array) == 3) {$myDay   = $array[0];$myMonth = $array[1];$myYear  = $array[2];if (strlen($myDay)   == 1) $myDay   = "0" . $myDay;if (strlen($myMonth) == 1) $myMonth = "0" . $myMonth;if (strlen($myYear)  == 2) {if ($myYear < 30) {if (strlen($myYear) == 0) {$myYear = '2000';} elseif (strlen($myYear) == 1) {$myYear = '200' . $myYear;} else {$myYear = '20' . $myYear;}
} else {$myYear  = "19" . $myYear;}
}
} else {return -1;}
}
if (checkdate($myMonth, $myDay, $myYear)) {return mktime(0, 0, 0, $myMonth, $myDay, $myYear);} else {return -1;}
} else {return -1;}
}
function euTimeToUnixTimestamp($euTime) {return $this->timeToUnixTimestamp($euTime);}
function usDatetimeToEuDatetime($usDatetime='') {if ($usDatetime == '') return new Bs_Exception('no usDatetime specified', __FILE__, __LINE__);$timestamp = $this->usDatetimeToUnixTimestamp($usDatetime);if (isEx($timestamp)) {$timestamp->stackTrace('', __FILE__, __LINE__);return $timestamp;}
$unixTimestamp = $this->formatUnixTimestamp('eu-1', $timestamp);return $unixTimestamp;}
function usDateToEuDate($usDate='') {if ($usDate == '') return new Bs_Exception('no usDate specified', __FILE__, __LINE__);$timestamp = $this->usDateToUnixTimestamp($usDate);if (isEx($timestamp)) {$timestamp->stackTrace('', __FILE__, __LINE__);return $timestamp;}
if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('eu-3', $timestamp);}
function usDatetimeToSqlDatetime($usDatetime='') {if ($usDatetime == '') return -1;$timestamp = $this->usDatetimeToUnixTimestamp($usDatetime);if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('sql-1', $timestamp);}
function usDateToSqlDate($usDate='') {if ($usDate == '') return -1;$timestamp = $this->usDateToUnixTimestamp($usDate);if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('sql-3', $timestamp);}
function euDatetimeToUsDatetime($euDatetime='') {if ($euDatetime == '') return -1;$timestamp = $this->euDatetimeToUnixTimestamp($euDatetime);if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('us-1', $timestamp);}
function euDateToUsDate($euDate='') {if ($euDate == '') return -1;$timestamp = $this->euDateToUnixTimestamp($euDate);if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('us-3', $timestamp);}
function euDatetimeToSqlDatetime($euDatetime='') {if ($euDatetime == '') return -1;$timestamp = $this->euDatetimeToUnixTimestamp($euDatetime);if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('sql-1', $timestamp);}
function euDateToSqlDate($euDate='') {if ($euDate == '') return -1;$timestamp = $this->euDateToUnixTimestamp($euDate);if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('sql-3', $timestamp);}
function sqlDatetimeToUsDatetime($sqlDatetime='') {if ($sqlDatetime == '') return -1;$timestamp = $this->sqlDatetimeToUnixTimestamp($sqlDatetime);if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('us-1', $timestamp);}
function sqlDateToUsDate($sqlDate='') {if ($sqlDate == '') return -1;$timestamp = $this->sqlDateToUnixTimestamp($sqlDate);if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('us-3', $timestamp);}
function sqlDatetimeToEuDatetime($sqlDatetime) {if ($sqlDatetime == '') return -1;$timestamp = $this->sqlDatetimeToUnixTimestamp($sqlDatetime);if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('eu-1', $timestamp);}
function sqlDateToEuDate($sqlDate='') {if ($sqlDate == '') return -1;$timestamp = $this->sqlDateToUnixTimestamp($sqlDate);if ($timestamp == -1) return -1;return $this->formatUnixTimestamp('eu-3', $timestamp);}
function monthStringToNumber($month, $zeroFill=FALSE) {switch (strToLower(substr($month, 0, 3))) {case 'jan': case 'gen': case 'ene': if ($zeroFill) return '01';return 1;break;case 'feb': case 'fév': case 'fev': if ($zeroFill) return '02';return 2;break;case 'mar': case 'mär': case 'maa': if ($zeroFill) return '03';return 3;break;case 'apr': case 'avr': case 'abr': if ($zeroFill) return '04';return 4;break;case 'may': case 'mai': case 'mei': case 'maj': case 'mag': if ($zeroFill) return '05';return 5;break;case 'jun': case 'jui': case 'giu': if ($zeroFill) return '06';return 6;break;case 'jul': case 'jui': case 'lug': if ($zeroFill) return '07';return 7;break;case 'aug': case 'aoû': case 'aou': case 'ago': if ($zeroFill) return '08';return 8;break;case 'sep': case 'set': if ($zeroFill) return '09';return 9;break;case 'oct': case 'okt': case 'ott': case 'out': if ($zeroFill) return '10';return 10;break;case 'nov': if ($zeroFill) return '11';return 11;break;case 'dec': case 'déc': case 'dez': case 'dic': if ($zeroFill) return '12';return 12;break;default:
return 0;}
}
function monthToInt($month, $zeroFill=FALSE) {return $this->monthStringToNumber($month, $zeroFill);}
function monthNumberToString($month, $lang='en', $type='long') {switch ($lang) {case 'en':
switch ($month) {case 1:
return ($type == 'long') ? 'January' : 'Jan';break;case 1:
return ($type == 'long') ? 'February' : 'Feb';break;case 1:
return ($type == 'long') ? 'March' : 'Mar';break;case 1:
return ($type == 'long') ? 'April' : 'Apr';break;case 1:
return ($type == 'long') ? 'May' : 'May';break;case 1:
return ($type == 'long') ? 'June' : 'Jun';break;case 1:
return ($type == 'long') ? 'July' : 'Jul';break;case 1:
return ($type == 'long') ? 'August' : 'Aug';break;case 1:
return ($type == 'long') ? 'September' : 'Sep';break;case 1:
return ($type == 'long') ? 'October' : 'Oct';break;case 1:
return ($type == 'long') ? 'November' : 'Nov';break;case 1:
return ($type == 'long') ? 'December' : 'Dec';break;default:
return FALSE; }
break;default: return FALSE; }
}
}
$GLOBALS['Bs_Date'] = new Bs_Date(); ?>