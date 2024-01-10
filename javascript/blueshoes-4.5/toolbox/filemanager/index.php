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
die('This example is disabled by default because of security reasons. Switch it on yourself. File: ' . __FILE__ . ' Line: ' . __LINE__);$fileManagerSettings = array(
'basePath'                => $_SERVER['DOCUMENT_ROOT'], 'showRelative'            => TRUE, 
'maxFileUploadSize'       => '1000000', 
'jsBasePath'              => '/_bsJavascript/', 
'imgBasePath'             => '/_bsImages/', 
'language'                => 'en', 
);include('/usr/local/lib/php/blueshoes-4.4/applications/filemanager/index.php');?>