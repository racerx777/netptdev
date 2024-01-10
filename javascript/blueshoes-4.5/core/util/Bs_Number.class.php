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
define('BS_NUMBER_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_Number extends Bs_Object {function Bs_Number() {parent::Bs_Object(); }
function isNumericLarge($s) {return is_numeric($s);}
function roundNoTrim($value, $precision=2) {$ret  = round($value, $precision);$fill = 0;do {if ($precision > 0) {$dotPos = strpos($ret, '.');if ($dotPos === FALSE) {$ret .= '.';$fill = $precision;break;}
$fill = $precision - strlen(substr($ret, $dotPos +1));if ($fill < 0) $fill = 0; }
} while (FALSE);for ($i=0; $i<$fill; $i++) {$ret .= '0';}
return (string)$ret;}
function hexToBin($source) {$strlen = strlen($source);for ($i=0; $i<strlen($source); $i=$i+2) {$bin .= chr(hexdec(substr($source, $i,2)));}
return $bin;}
function gcd($a, $b) {while ( $b != 0) {$remainder = $a % $b;$a = $b;$b = $remainder;}
return abs ($a);}
function decimalToFraction($number) {list($whole, $numerator) = explode('.', $number);$denominator = 1 . str_repeat (0, strlen ($numerator));$GCD = gcd($numerator, $denominator);$numerator /= $GCD;$denominator /= $GCD;return sprintf('%d <sup>%d</sup>/<sub>%d</sub>', $whole, $numerator, $denominator);}
} $GLOBALS['Bs_Number'] =& new Bs_Number(); ?>