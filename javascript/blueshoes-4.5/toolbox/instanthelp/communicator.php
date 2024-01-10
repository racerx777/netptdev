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
require_once($_SERVER["DOCUMENT_ROOT"]       . "../global.conf.php");require_once($APP['path']['plugins']         . "jsrs/JsrsServer.class.php");require_once($APP['path']['plugins']         . 'instanthelp/Bs_Ih_InstantHelp.class.php');$ih =& new Bs_Ih_InstantHelp();if (isSet($_REQUEST['dbSettingName'])) {include_once($APP['path']['core'] . 'db/Bs_Db.class.php');$dbObj = &getDbObject($APP['db'][$_REQUEST['dbSettingName']]);$ih->setDb(&$dbObj);}
$JsrsServer->propagateMethod($ih, 'getText');$JsrsServer->start();?>