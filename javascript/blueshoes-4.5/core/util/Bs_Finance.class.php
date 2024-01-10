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
define('BS_FINANCE_VERSION',      '4.5.$Revision: 1.2 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_Finance extends Bs_Object {function Bs_Finance() {parent::Bs_Object(); }
function toPrice($price=0, $centsSeparateChar='.', $thousandsSeparateChar="'") {$price = trim($price);if (strlen($price) == 0) {$price = "000";} elseif (strlen($price) == 1) {$price = "00" . $price;} elseif (strlen($price) == 2) {$price = "0" . $price;}
$newPrice = round($price) / 100;$pricePoint  = strpos($newPrice, ".");$priceLength = strlen($newPrice);if ($pricePoint == 0) {$newPrice .= ".00";} elseif (($priceLength - $pricePoint == 2) AND ($pricePoint > 0)) {$newPrice .= "0";}
if ($centsSeparateChar != '.') $newPrice = str_replace('.', $centsSeparateChar, $newPrice);return $newPrice;}
function toPercent($number=0, $separateChar='.', $percentSign='%') {$number = trim($number);if (strlen($number) == 0) {$number = "000";} elseif (strlen($number) == 1) {$number = "00" . $number;} elseif (strlen($number) == 2) {$number = "0" . $number;}
$newPercent = round($number) / 100;$percentPoint  = strpos($newPercent, ".");$percentLength = strlen($newPercent);if ($percentPoint == 0) {$newPercent .= ".00";} elseif (($percentLength - $percentPoint == 2) AND ($percentPoint > 0)) {$newPercent .= "0";}
if ($separateChar != '.') $newPercent = str_replace('.', $separateChar, $newPercent);return $newPercent . $percentSign;}
function ccVal($Num, $Name='n/a') {$GoodCard = true;$Num = ereg_replace("[^[:digit:]]", "", $Num);switch ($Name) {case "mcd" : 
$GoodCard = ereg("^5[1-5].{14}$", $Num);break;case "vis" : 
$GoodCard = ereg("^4.{15}$|^4.{12}$", $Num);break;case "amx" : 
$GoodCard = ereg("^3[47].{13}$", $Num);break;case "dsc" : 
$GoodCard = ereg("^6011.{12}$", $Num);break;case "dnc" : 
$GoodCard = ereg("^30[0-5].{11}$|^3[68].{12}$", $Num);break;case "jcb" : 
$GoodCard = ereg("^3.{15}$|^2131|1800.{11}$", $Num);break;} 
$Num = strrev($Num);$Total = 0;for ($x=0; $x<strlen($Num); $x++) {$digit = substr($Num,$x,1);if ($x/2 != floor($x/2)) {$digit *= 2;if (strlen($digit) == 2)  
$digit = substr($digit,0,1) + substr($digit,1,1);} 
$Total += $digit;} 
if ($GoodCard && $Total % 10 == 0) return TRUE; else return FALSE;}
} ?>