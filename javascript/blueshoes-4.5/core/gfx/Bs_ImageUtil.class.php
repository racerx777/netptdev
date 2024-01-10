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
define('BS_IMAGEUTIL_VERSION',         '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_ImageUtil extends Bs_Object {function Bs_ImageUtil() {parent::Bs_Object(); }
function resizeToFile($sourcefile, $thumbWidth, $thumbHeight, $targetfile, $jpegqual) {$picsize=getimagesize("$sourcefile");$sourceWidth  = $picsize[0];$sourceHeight = $picsize[1];$source_id = imageCreateFromJPEG("$sourcefile");if ($sourceWidth >= $sourceHeight) {$relation = $sourceWidth / $sourceHeight;$thumbHeight = $thumbHeight / $relation;} else {$relation = $sourceHeight / $sourceWidth;$thumbWidth = $thumbWidth / $relation;}
$target_id=imagecreatetruecolor($thumbWidth, $thumbHeight);$target_pic=imagecopyresampled($target_id,$source_id, 0,0,0,0, $thumbWidth, $thumbHeight, $sourceWidth, $sourceHeight);imagejpeg ($target_id,"$targetfile",$jpegqual);return true;}
function getImgInfoType($number) {switch ($number) {case 1:
return 'GIF';case 2:
return 'JPG';case 3:
return 'PNG';case 4:
return 'SWF';case 5:
return 'PSD';case 6:
return 'BMP';case 7:
return 'TIFF';case 8:
return 'TIFF';case 9:
return 'JPC';case 10:
return 'JP2';case 11:
return 'JPX';default:
return '?';}
}
}
?>