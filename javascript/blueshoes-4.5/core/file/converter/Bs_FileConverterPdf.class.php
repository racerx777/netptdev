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
define('BS_FILECONVERTERPDF_VERSION',      '4.5.$Revision: 1.3 $');require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'file/converter/Bs_FileConverter.class.php');class Bs_FileConverterPdf extends Bs_FileConverter {var $pathToPdfToHtmlExe;function Bs_FileConverterPdf() {parent::Bs_FileConverter(); if (isSet($GLOBALS['APP']['path']['pdftohtml'])) $this->pathToPdfToHtmlExe = $GLOBALS['APP']['path']['pdftohtml'];}
function capable($functionName) {switch (strToLower($functionName)) {case 'pdftohtml':
return TRUE;default:
return NULL;}
return TRUE;}
function streamToHtmlString($fullPath, $zoom=1.5, $firstPage=NULL, $lastPage=NULL, $complex=FALSE) {if (!isSet($this->pathToPdfToHtmlExe))       return FALSE;if (!file_exists($this->pathToPdfToHtmlExe)) return FALSE;if (strpos($fullPath, '://') !== FALSE) {if (strToLower(substr($fullPath, 0, 7)) !== 'file://') {$fileContent = file_get_contents($fullPath);$fullPath    = tempnam('/tmp', 'bs_');$handle      = fopen($fullPath, 'wb');fwrite($handle, $fileContent);fclose($handle);$doUnlink = TRUE;}
}
if (strpos($fullPath, ' ') !== FALSE) $fullPath = '"' . $fullPath . '"'; $cmd = $this->pathToPdfToHtmlExe . " {$fullPath}";$cmd .= " -zoom {$zoom}";if ($firstPage) $cmd .= " -f {$firstPage}";if ($lastPage)  $cmd .= " -l {$lastPage}";if ($complex)   $cmd .= " -c";$cmd .= " -i";      $cmd .= " -stdout"; $ret = shell_exec($cmd);if (@$doUnlink) @unlink($fullPath);return $ret;}
function pdfToHtmlString($fullPath, $zoom=1.5, $firstPage=NULL, $lastPage=NULL, $complex=FALSE) {return $this->streamToHtmlString($fullPath, $zoom, $firstPageL, $lastPageL, $complex); }
function toHtmlFile() {}
}
?>