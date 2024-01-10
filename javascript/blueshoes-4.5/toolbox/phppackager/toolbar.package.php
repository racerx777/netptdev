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
$packageName  = 'Bs_Toolbar';$baseFileName = 'blueshoes-toolbar';$short        = 'BlueShoes Toolbar only (stripped *). Ships with examples and images.';$version      = '4.3';$versions = array(
'stripped' => array(
array(
'path'          => '/javascript/components/toolbar/', 
'noStripRegEx'  => '~/examples|/ecg~i', 
'stripRegEx'    => '~(/form|/storage|/filemanager|/smartshop|/spreadsheet|/wysiwyg|/onom|/indexserver|/cms|/debe).*(\.php|\.js)~Ui', 
), 
array(
'path'     => '/images/buttons/', 
), 
), 
'developer' => array(
array(
'path'     => '/javascript/components/toolbar/', 
), 
array(
'path'     => '/images/buttons/', 
), 
), 
'commercial' => array(
array(
'path'     => '/javascript/components/toolbar/', 
), 
array(
'path'     => '/images/buttons/', 
), 
), 
);?>