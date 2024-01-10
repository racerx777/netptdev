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
require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");require_once($APP['path']['core'] . 'plugins/InstantHelp/Bs_Ih_InstantHelp.class.php');require_once($APP['path']['core'] . 'storage/Bs_Debedoo.class.php');$ih =& new Bs_Ih_InstantHelp();$ih->createDict('dummy');$d =& new Bs_Debedoo();$d->setLanguage('de');$d->internalName = 'InstantHelpEditor';$d->dbTableName  = 'BsInstantHelp';$d->setGetVars($GLOBALS['HTTP_GET_VARS']);$d->setPostVars($GLOBALS['HTTP_POST_VARS']);$d->addHeadString    = "BS Instant Help Editor";$out = $d->doItYourself();if (isEx($out)) {$out->stackDump('die');} else {echo $out;}
?>