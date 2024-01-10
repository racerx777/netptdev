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
require_once($_SERVER["DOCUMENT_ROOT"]  . "../global.conf.php");require_once($APP['path']['core']       . 'gfx/chart/Bs_Chart.class.php');$data = array(5, 10, 7, 8, 6, 14, 20);$c =& new Bs_Chart();$formRet = $c->treatForm();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>BlueShoes Chart Wizard</title>
<?php
echo $c->_form->includeOnceToHtml($formRet['include']);echo $c->_form->onLoadCodeToHtml($formRet['onLoad']);echo $formRet['head'];?>
</head>
<body bgcolor="#D6D3CE">
<h2>Chart Wizard</h2>
<?php
if (!empty($formRet['errors'])) echo $formRet['errors'];echo $formRet['form'];?>
</body>
</html>
