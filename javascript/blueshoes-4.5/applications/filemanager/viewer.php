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
require_once($APP['path']['core'] . 'file/Bs_FileSystem.class.php');require_once($APP['path']['core'] . 'file/Bs_FileUtil.class.php');$Bs_FileSystem =& new Bs_FileSystem();$mimeType = $GLOBALS['Bs_FileUtil']->getMimeType($Bs_FileSystem->getFileExtension($_REQUEST['file']));if ($mimeType) {header("Content-type: {$mimeType}");}
header("Content-Disposition: attachment; filename={$_REQUEST['file']}");$filePath = $path . $_REQUEST['file'];@readfile($filePath);?>