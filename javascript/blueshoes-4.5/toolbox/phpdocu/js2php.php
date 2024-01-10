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
require_once($_SERVER['DOCUMENT_ROOT']  . '../global.conf.php');require_once($GLOBALS['APP']['path']['core'] . 'file/Bs_Dir.class.php');require_once($GLOBALS['APP']['path']['core'] . 'file/Bs_File.class.php');require_once($GLOBALS['APP']['path']['core'] . 'file/Bs_FileSystem.class.php');class JsToPhp {var $_Bs_Dir = NULL; var $toConvertFiles = array();function JsToPhp($bsRootDir) {$this->_Bs_Dir  =& new Bs_Dir();$this->_Bs_File =& new Bs_File();$dirToConvert = $bsRootDir . 'javascript/';$params = array(
'fullPath'    => $dirToConvert, 
'regFunction' => 'preg_match', 
'regEx'       => '/.class.js$/i',
'fileDirLink' => array('dir'=>FALSE), 
'sort'        => TRUE,
'returnType'  => 'fulldir/file', 
);$this->toConvertFiles = $this->_Bs_Dir->getFileList($params);}
function _convert($nr) {$sourceFile = $this->toConvertFiles[$nr]['file'];$sourceDir = $this->toConvertFiles[$nr]['dir'];$file = $sourceDir . $sourceFile ;$targetDir = '/tmp/js2php/';$input = file_get_contents($file);$patterns = array(
"/\n\s*function\s+(Bs_.*)\s*\((.*)\)/",
"/\n\s*this\.(.*)=\s*function\s*\((.*)\)/",
"/\n\s*.*\.prototype\.(.*)=\s*function\s*\((.*)\)/",
"/\n\s*this\.(.*);/",
);$replace = array(
"\n".'class $1($2)',
"\n".'function $1($2)',
"\n".'function $1($2)',
"\n".'var $$1;',
);$out = preg_replace($patterns, $replace, $input);$out = str_replace("= new Array;", "= array();", $out);$this->_Bs_Dir->mkpath($targetDir);$pos = strpos($sourceDir, '/javascript/');$pathJunk = substr($sourceDir, $pos + strlen('/javascript/'));$fullTargetDir = $targetDir . $pathJunk;$this->_Bs_Dir->mkpath($fullTargetDir);$targetFile = $fullTargetDir . $sourceFile . '.php'; $this->_Bs_File->onewayWrite("<?" . "php\n" . $out . "\n?" . ">", $targetFile);return TRUE;}
function go() {while (list($k) = each($this->toConvertFiles)) {$this->_convert($k);}
}
}
$js2php = new JsToPhp($GLOBALS['APP']['path']['bsRoot']);$js2php->go();echo '<h1>done</h1>';?>