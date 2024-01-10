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
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_IndexServer.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_WebSearchEngine.class.php');$is =& new Bs_IndexServer();$wse =& new Bs_Is_WebSearchEngine();$wse->init($_REQUEST['profile']);$pageInfo = $wse->fetchPageInfoById($_REQUEST['pageID']);$data = array();$data[] = array_keys($pageInfo);$data[] = array_values($pageInfo);?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Unbenannt</title>
</head>
<body>
<?php
require_once($APP['path']['core'] . 'html/table/Bs_HtmlTable.class.php');require_once($APP['path']['core'] . 'html/table/Bs_HtmlTableWindrose.class.php');$htmlWindroseObj =& new Bs_HtmlTableWindrose();$htmlWindroseObj->read($APP['path']['toolbox'] . 'html/table/windroseEditor/styles/details.style');$tbl =& new Bs_HtmlTable($data);$tbl->flipData();$tbl->setWindroseStyle(&$htmlWindroseObj);$tbl->setTableAttr(array('cellspacing'=>'0', 'cellpadding'=>'0'));$tbl->setGlobalTdAttr(array('align'=>'left', 'valign'=>'top'));echo $tbl->renderTable();?>
</body>
</html>
