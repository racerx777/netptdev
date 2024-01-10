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
define('BS_UNITCONVERTER_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');define('U_KB', 1024);                   define('U_MB', 1048576);                define('U_GB', 1073741824);             define('U_TB', 1099511627776);          define('U_PB', 1125899906842624);       define('U_EB', 1152921504606846976);    class Bs_UnitConverter extends Bs_Object {function Bs_UnitConverter() {parent::Bs_Object(); }
function length($from, $to, $value, $precision=3) {$data = array(
'cables'        => '182.88',                  'cm'            => '0.01',                    'chains'        => '20.1168',                 'dm'            => '0.1',                     'ells'          => '0.875',                   'fathoms'       => '1.8288',                  'feet'          => '0.3048',                  'furlongs'      => '201.168',                 'hands'         => '0.106',                   'hm'            => '100',                     'inches'        => '0.0254',                  'km'            => '1000',                    'm'             => '1',                       'miles'         => '1609.344',                'milesNautical' => '1852',                    'mm'            => '0.001',                   'nanometers'    => '1e-9',                    'yards'         => '0.9144'                   );do { if (!isSet($data[$from])) break;if (!isSet($data[$to]))   break;$valFrom = $data[$from];$valTo   = $data[$to];$value = round($value / $valTo * $valFrom, $precision);return (string)$value;} while (FALSE);return FALSE;}
function temperature($from, $to, $value, $precision=3) {switch ($from) {case 'celsius':
$value += 273.15;break;case 'fahrenheit':
$value = 5 / 9 * ($value + 459.67);break;case 'kelvin':
break;case 'rankine':
$value = 5 / 9 * $value;break;case 'reaumure':
case 'réaumure':
$value = (5 / 4 * $value) + 273.15;break;default:
return FALSE;}
switch ($to) {case 'celsius':
$value -= 273.15;break;case 'fahrenheit':
$value = (9 / 5 * $value) - 459.67;break;case 'kelvin':
break;case 'rankine':
$value = 9 / 5 * $value;break;case 'reaumure':
case 'réaumure':
$value = 4 / 5 * ($value - 273.15);break;default:
return FALSE;}
return (string)round($value, $precision);}
function bitsAndBytes($from, $to, $value, $round=2) {$data = array(
'bits'       => 0.125,             'bytes'      => 1,                 'kilobits'   => 128,               'kilobytes'  => U_KB,              'megabits'   => 131072,            'megabytes'  => U_MB,              'gigabits'   => 134217728,         'gigabytes'  => U_GB,              'terabits'   => 137438953472,      'terabytes'  => U_TB,              'petabits'   => 140737488355328,   'petabytes'  => U_PB,              'exabits'    => 144115188075855872,'exabytes'   => U_EB               );return (string)round(($value * $data[$from] / $data[$to]), $round);}
function toUsefulBitAndByteString($bytes) {$data = array(                                    
'bytes'      => 1,      'kilobytes'  => U_KB,   'megabytes'  => U_MB,   'gigabytes'  => U_GB,   'terabytes'  => U_TB,   'petabytes'  => U_PB,   'exabytes'   => U_EB    );if ($bytes > $data['exabytes']) {$val = Bs_UnitConverter::bitsAndBytes('bytes', 'exabytes', $bytes);$short = ' EB';} elseif ($bytes > $data['petabytes']) {$val = Bs_UnitConverter::bitsAndBytes('bytes', 'petabytes', $bytes);$short = ' PB';} elseif ($bytes > $data['terabytes']) {$val = Bs_UnitConverter::bitsAndBytes('bytes', 'terabytes', $bytes);$short = ' TB';} elseif ($bytes > $data['gigabytes']) {$val = Bs_UnitConverter::bitsAndBytes('bytes', 'gigabytes', $bytes);$short = ' GB';} elseif ($bytes > $data['megabytes']) {$val = Bs_UnitConverter::bitsAndBytes('bytes', 'megabytes', $bytes);$short = ' MB';} elseif ($bytes > $data['kilobytes']) {$val = Bs_UnitConverter::bitsAndBytes('bytes', 'kilobytes', $bytes);$short = ' KB';} else {$val = $bytes;$short = ' B';}
if (is_numeric($val) && strpos($val, '.')) {$val = (double)$val;}
if (is_double($val)) {$val = round($val, 2);}
return $val . $short;}
function unitStringToBytes($unitStr) {if (is_numeric($unitStr)) {return round($unitStr);}
if (!is_string($unitStr)) return FALSE;if (!preg_match('/([0-9.]*)\s*(.*)/', $unitStr, $regs)) return FALSE;$val  = $regs[1];$unit = $regs[2];switch(strToUpper($unit)) {case 'K': 
case 'KB': 
$ret = U_KB * $val; break;case 'M': 
case 'MB': 
$ret = U_MB * $val; break;case 'G': 
case 'GB': 
$ret = U_GB * $val; break;case 'T': 
case 'TB': 
$ret = U_TB * $val; break;case 'P': 
case 'PB': 
$ret = U_PB * $val; break;case 'E': 
case 'EB': 
$ret = U_EB * $val; break;case '':
$ret = $val; break;default : 
return FALSE;}
return  $ret;}
}
?>