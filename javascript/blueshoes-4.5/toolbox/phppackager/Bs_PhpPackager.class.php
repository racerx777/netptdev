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
require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");require_once($APP['path']['core'] . 'util/Bs_Stripper.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');class Bs_PhpPackager extends Bs_Object {var $stripper;var $dir;var $defautConfig = array(
'includeRegex'  => '~.*~',
'excludeRegex'  => '',
'order'         => 'nostrip',
'noStripRegEx'  => '', 
'stripRegEx'    => '~\.php|\.js~',
);function Bs_PhpPackager() {parent::Bs_Object(); $this->stripper =& new Bs_Stripper();$this->dir      =& new Bs_Dir();}
function go($baseSourceDir, $baseTargetDir, $configData) {$status = FALSE;do { foreach ($this->defautConfig as $key => $val) {if (empty($configData[$key])) $configData[$key] = $val;}
$fileListParam = array(
'fullPath'    => $baseSourceDir, 
'regFunction' => 'preg_match',
'regEx'       => $configData['includeRegex'],
'returnType'  => 'subdir/file',
);$fileList=$this->dir->getFileList($fileListParam);if (isEx($fileList)) {$fileList->stackDump('die');}
XR_dump($fileList, __LINE__); foreach($fileList as $fileLocation) {if (empty($fileLocation['file'])) $dirHandler->mkpath($targetDir.$fileLocation['dir']);if (TRUE) {$stripper->stripFile();} else {copy();}
}
$status = TRUE;} while(FALSE);if (!$status) {echo $err;return FALSE;}
return TRUE;}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_PhpPackager.class.php') {$configData =  array(
'path'          => '/javascript/components/toolbar/', 
'noStripRegEx'  => '~/examples|/ecg~i', 
'stripRegEx'    => '~(/form|/storage|/filemanager|/smartshop|/spreadsheet|/wysiwyg|/onom|/indexserver|/cms|/debe).*(\.php|\.js)~Ui', 
);$packager = new Bs_PhpPackager();$packager->go('c:/usr/local/lib/php/blueshoes-4.4/javascript/components/toolbar/', 'c:/Temp/pack1/', $configData);}
?>