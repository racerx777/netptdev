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
$pos     = strpos(__FILE__, 'index.php');$tmpFile = substr(__FILE__, 0, $pos);$APP['path']['bsRoot'] = str_replace("\\", '/', realpath($tmpFile . '../../')) . '/';$APP['path']['applications'] = $APP['path']['bsRoot'] . 'applications/';require_once($APP['path']['applications'] . 'filemanager/global.conf.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');$file =& new Bs_File();$dir  =& new Bs_Dir();$basePath = $dir->realPath($fileManagerSettings['basePath']); $fileManagerSettingsDefault = array(
'showRelative'            => TRUE, 
'maxFileUploadSize'       => '1000000', 
'maxFilesToShow'          => 300, 
'jsBasePath'              => '/_bsJavascript/', 
'imgBasePath'             => '/_bsImages/', 
'language'                => 'en', 
'fileExtensions'          => '__FILE__', 
);$fileManagerSettings = array_merge($fileManagerSettingsDefault, $fileManagerSettings);$fileManagerSettings['imgPath']                 = $fileManagerSettings['imgBasePath'] . 'applications/filemanager/';$fileManagerSettings['fileExtensionsImagePath'] = $fileManagerSettings['imgBasePath'] . 'fileExtensions/';require_once($APP['path']['core'] . 'text/Bs_LanguageHandler.class.php');$Bs_LanguageHandler =& new Bs_LanguageHandler();$t = &$Bs_LanguageHandler->determineLanguage($GLOBALS['APP']['path']['applications'] . 'filemanager/lang/text', $fileManagerSettings['language']);if (is_null($t)) die('Cannot load language: ' . $fileManagerSettings['language']);list($lang, $path) = $t;$langHash = &$Bs_LanguageHandler->readLanguage($path);if (isSet($_REQUEST['fullPath'])) {$path = $_REQUEST['fullPath'];} else {if (isSet($_REQUEST['path'])) {$subPath = $dir->realPath($_REQUEST['path']);} else {$subPath = '';}
$path = $basePath . $subPath;}
$path = $dir->getRealPath($path);if (substr($path, 0, strlen($basePath)) != $basePath) {$commentString  = '';$commentString .= $langHash['error']['pathNotAllowed'] . "<br>\n";                $commentString .= $langHash['misc']['desiredPath']     . ": " . $path . "<br>\n"; die($commentString);}
if (isSet($_GET['showPage'])) {switch ($_GET['showPage']) {case 'top':
case 'upload':
case 'command':
case 'status':
case 'file':
case 'dir':
case 'editor':
case 'viewer':
case 'searchForm':
case 'menuOld':
include($APP['path']['bsRoot'] . 'applications/filemanager/' . $_GET['showPage'] . '.php');break;}
} else {include($APP['path']['bsRoot'] . 'applications/filemanager/frameset.php');}
function moveFile($from, $to) {if ($GLOBALS['fileManagerSettings']['showRelative']) {$from = $GLOBALS['basePath'] . $from;$to   = $GLOBALS['basePath'] . $to;}
$status = $GLOBALS['file']->move($from, $to);return (bool)$status;}
function copyFile($from, $to) {if ($GLOBALS['fileManagerSettings']['showRelative']) {$from = $GLOBALS['basePath'] . $from;$to   = $GLOBALS['basePath'] . $to;}
$status = @copy($from, $to);return (bool)$status;}
function makePathAbsolute($path) {if ($GLOBALS['fileManagerSettings']['showRelative']) {return $GLOBALS['basePath'] . $path;}
return $path;}
?>