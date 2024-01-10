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
define('BS_IS_CONVERTERPDF_VERSION',      '4.5.$Revision: 1.2 $');require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core']    . 'file/converter/Bs_FileConverterPdf.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_ConverterHtml.class.php');class Bs_Is_ConverterPdf extends Bs_Object {var $Bs_FileConverterPdf;var $Bs_Is_ConverterHtml;function Bs_Is_ConverterPdf() {parent::Bs_Object(); $this->Bs_FileConverterPdf =& new Bs_FileConverterPdf();$this->Bs_Is_ConverterHtml =& new Bs_Is_ConverterHtml();}
function streamToArray($fileFullPath) {$status = $this->Bs_FileConverterPdf->streamToHtmlString($fileFullPath);if ($status === FALSE) return FALSE;$status = $this->Bs_Is_ConverterHtml->stringToArray($status);if ($status['values']['title'] === 'þÿ') $status['values']['title'] = ''; return $status;}
function stringToArray($string) {$fileFullPath = tempnam('/tmp', 'bs_tmp_');$handle = fopen($fileFullPath, 'wb');fwrite($handle, $string);fclose($handle);$ret = $this->streamToArray($fileFullPath);@unlink($fileFullPath);return $ret;}
function stringToHtmlString($string) {$fullPath    = tempnam('/tmp', 'bs_');$handle      = fopen($fullPath, 'wb');fwrite($handle, $string);fclose($handle);$ret = $this->streamToHtmlString($fullPath);@unlink($fullPath);return $ret;}
function streamToHtmlString($fileFullPath) {$status = $this->Bs_FileConverterPdf->streamToHtmlString($fileFullPath);if ($status === FALSE) return FALSE;$status = str_replace('<title></title>', 'þÿ', $status); return $status; }
}
?>