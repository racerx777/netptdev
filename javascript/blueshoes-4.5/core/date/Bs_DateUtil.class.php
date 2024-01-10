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
define('BS_DATEUTIL_VERSION',      '4.5.$Revision: 1.2 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_DateUtil extends Bs_Object {var $_Bs_String;var $_now;function Bs_DateUtil() {parent::Bs_Object(); $this->setNow();}
function setNow() {$ts = time();$t = array(
'timestamp'  => $ts, 
'datetime'   => date('Y/m/d H:i:s', $ts), 
'date'       => date('Y/m/d', $ts), 
'time'       => date('H:i:s', $ts), 
'year'       => date('Y', $ts), 
'month'      => date('m', $ts), 
'day'        => date('d', $ts), 
'hour'       => date('H', $ts), 
'minute'     => date('i', $ts), 
'second'     => date('s', $ts)
);$this->_now = $t;}
function isValidDate($day, $month, $year) {return (bool)checkdate($month, $day, $year);}
function isLeapYear($year=null) {if (is_null($year)) $year = date('Y');if (strlen($year) != 4) return NULL;if (preg_match("/\D/",$year)) return NULL; return ((($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0));}
function isInLeapYear($date) {}
function isInFuture($date, $time=null) {if (is_null($time)) {return ($date > $this->_now['date']);} else {return ($date . ' ' . $time > $this->_now['datetime']);}
}
function isInPast($date, $time=null) {if (is_null($time)) {return ($date < $this->_now['date']);} else {return ($date . ' ' . $time < $this->_now['datetime']);}
}
function dayOfWeek($date) {if ($month > 2) {$month -= 2;} else {$month += 10;$year--;}
$day = ( floor((13 * $month - 1) / 5) +
$day + ($year % 100) +
floor(($year % 100) / 4) +
floor(($year / 100) / 4) - 2 *
floor($year / 100) + 77);return (($day - 7 * floor($day / 7)));}
function weekOfYear($date) {}
function weekOfYear($day,$month,$year)
{if(empty($year))
$year = Date_Calc::dateNow("%Y");if(empty($month))
$month = Date_Calc::dateNow("%m");if(empty($day))
$day = Date_Calc::dateNow("%d");$week_year = $year - 1501;$week_day = $week_year * 365 + floor($week_year / 4) - 29872 + 1
- floor($week_year / 100) + floor(($week_year - 300) / 400);$week_number =
floor((Date_Calc::julianDate($day,$month,$year) + floor(($week_day + 4) % 7)) / 7);return $week_number;} }
?>