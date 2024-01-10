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
define('BS_FILECONVERTEREXCEL_VERSION',      '4.5.$Revision: 1.4 $');require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'file/converter/Bs_FileConverter.class.php');require_once($APP['path']['XPath'] . 'XPath.class.php');class Bs_FileConverterExcel extends Bs_FileConverter {function Bs_FileConverterExcel() {parent::Bs_FileConverter(); }
function capable($functionName) {switch (strToLower($functionName)) {case 'htmltotext':
return TRUE;default:
return NULL;}
return TRUE;}
function xlsStreamToHtmlString($source) {$fullPath = str_replace("\\", '/', $source);if (strpos($source, ' ') !== FALSE) $source = '"' . $source . '"'; $cmd = $this->_APP['path']['xlhtml'] . " {$source}";$ret = shell_exec($cmd);return $ret;}
function xlsToHtml($source) {return $this->xlsStreamToHtmlString($source);}
function xlsToXml($fullPath) {$fullPath = str_replace("\\", '/', $fullPath);if (strpos($fullPath, ' ') !== FALSE) $fullPath = '"' . $fullPath . '"'; $cmd = $this->_APP['path']['xlhtml'] . " -xml {$fullPath}";$ret = shell_exec($cmd);return $ret;}
function xlsStreamToArray($source) {$xml = $this->xlsToXml($source);$xmlOpt = array(XML_OPTION_CASE_FOLDING=>TRUE, XML_OPTION_SKIP_WHITE=>FALSE);$xPath =& new XPath(NULL, $xmlOpt);$xPath->setVerbose(FALSE);if (!$xPath->importFromString($xml)) {return FALSE; }
$ret = array();$xpv = $xPath->match('/excel_workbook[1]/sheets[1]/sheet[1]/rows[1]/row');while (list(,$key) = each($xpv)) {$xpvCell = $xPath->match($key . '/cell');$t = array();while (list(,$keyCell) = each($xpvCell)) {$t[] = $xPath->getData($keyCell);}
$ret[] = $t;}
return $ret;}
function xlsToArray() {return $this->xlsStreamToArray();}
function xlsToCsv($fullPath) {$array = $this->xlsToArray($fullPath);if (!is_array($array)) return FALSE;$ret = '';while (list($k) = each($array)) {$ret .= join(';', $array[$k]) . "\n";}
return $ret;}
}
?>