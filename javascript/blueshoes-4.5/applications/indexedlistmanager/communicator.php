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
require_once($_SERVER["DOCUMENT_ROOT"]       . "../global.conf.php");require_once($APP['path']['plugins']         . "jsrs/JsrsServer.class.php");require_once($APP['path']['applications']         . 'indexedlistmanager/Bs_IndexedListManager.class.php');$ilm =& new Bs_IndexedListManager();$_REQUEST['dbSettingName'] = 'sm_mySQL_dsn';if (isSet($_REQUEST['dbSettingName'])) {include_once($APP['path']['core'] . 'db/Bs_Db.class.php');$dbObj = &getDbObject($APP['db'][$_REQUEST['dbSettingName']]);$ilm->setDb(&$dbObj);}
$ilm->tableName      = 'sm_supdoccriteria';$ilm->fieldNameKey   = 'ID';$ilm->fieldNameValue = 'caption';$ilm->unique         = 2;$ilm->minLength      = 1;$ilm->maxLength      = 255;$JsrsServer->propagateMethod($ilm, 'getList');$JsrsServer->propagateMethod($ilm, 'add');$JsrsServer->propagateMethod($ilm, 'edit');$JsrsServer->propagateMethod($ilm, 'delete');$JsrsServer->start();?>