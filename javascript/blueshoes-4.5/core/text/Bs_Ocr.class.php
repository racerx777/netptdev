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
define('BS_OCR_VERSION',      '4.5.$Revision: 1.11 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');class Bs_Ocr extends Bs_Object {var $imageMagickPath = '';var $gocrPath = '';var $magickFileTypes = array('art', 'avs', 'bmp', 'cgm', 'dcx', 'dib', 'dpx', 'emf', 'epdf', 'epi', 'eps', 'eps2', 'eps3', 'epsf', 'epsi', 'ept', 'fax', 'fig', 'gif', 'jng', 'jpeg', 'jpg', 'miff', 'mng', 'pbm', 'pcx', 'pict', 'pix', 'png', 'pnm', 'ppm', 'ps', 'ps2', 'ps3', 'psd', 'ptif', 'svg', 'tiff', 'tif', 'wmf');function Bs_Ocr() {parent::Bs_Object(); }
function fileToString($fileFullPath) {if (!file_exists($fileFullPath)) return FALSE;if (!is_readable($fileFullPath)) return FALSE;$dir =& new Bs_Dir();$fileExtension = $dir->getFileExtension($fileFullPath);if (!in_array(strToLower($fileExtension), $this->magickFileTypes)) return FALSE;$pgmFullPath = substr($fileFullPath, 0, -4) . '_' . $fileExtension . '.pgm';$txtFullPath = substr($fileFullPath, 0, -4) . '_' . $fileExtension . '.txt';$cmd = '"' . $this->imageMagickPath . 'convert" ' . $fileFullPath . ' ' . $pgmFullPath . ' 2>&1';$status = shell_exec($cmd);if (!file_exists($pgmFullPath)) return FALSE;$cmd = '"' . $this->gocrPath . 'gocr" ' . $pgmFullPath . ' > ' . $txtFullPath . ' 2>&1';$status = shell_exec($cmd);if (file_exists($txtFullPath)) {return file_get_contents($txtFullPath);} else {return FALSE;}
}
}
?>