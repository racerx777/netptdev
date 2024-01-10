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
define('BS_FTPCLIENTFACTORY_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');include_once($GLOBALS['APP']['path']['core'] . 'Net/Ftp/Bs_FtpClientPhp.class.php');class Bs_FtpClientFactory extends Bs_Object {function Bs_FtpClientFactory() {parent::Bs_Object(); }
function supports($what) {switch ($what) {case 'php':
return (extension_loaded('ftp')); case 'blueshoes':
return FALSE;default:
return NULL;}
}
function &produce($what) {switch ($what) {case 'php':
$t =& new Bs_FtpClientPhp();return $t; case 'blueshoes':
return NULL;default:
return NULL;}
}
} ?>