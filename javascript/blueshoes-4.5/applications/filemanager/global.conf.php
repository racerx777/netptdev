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
$_isWindows  = (strToLower(substr(PHP_OS,0,3)) === 'win');$_sdrChar = substr($_SERVER['DOCUMENT_ROOT'], -1);if (('/' !== $_sdrChar) OR ('\\' !== $_sdrChar)) $_SERVER['DOCUMENT_ROOT'] .= '/';if (empty($APP['path']['bsRoot'])) {$APP['path']['bsRoot'] = ''; if (empty($APP['path']['bsRoot'])) {if (preg_match('/bs-(\d\.\d{1,2})/', $_SERVER['DOCUMENT_ROOT'], $match)) {$APP['path']['bsRoot'] = "/usr/local/lib/php/blueshoes-{$match[1]}/";} else {$out = 'No PHP_BSROOT path given for Blue Shoes lib!'."\n";$out .='Please set environment variable PHP_BSROOT or the $APP[\'path\'][\'bsRoot\'] variable in this file '.__FILE__."\n";echo nl2br($out);user_error($out, E_USER_ERROR);}
}
}
if (($_isWindows) AND ($APP['path']['bsRoot'][1] !== ':')) {$APP['path']['bsRoot'] = substr(__FILE__,0,2) . $APP['path']['bsRoot'];}
include_once($APP['path']['bsRoot'] . 'blueshoes.ini.php');?>
