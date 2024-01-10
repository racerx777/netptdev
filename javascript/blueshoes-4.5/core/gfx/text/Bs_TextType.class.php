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
require_once($APP['path']['core'] . 'net/Bs_Url.class.php');require_once($APP['path']['core'] . 'gfx/Bs_ColorCodes.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'file/Bs_FileUtil.class.php');require_once($APP['path']['core'] . 'util/Bs_IniHandler.class.php');define('BS_TEXTTYPE_VERSION',         '4.5.$Revision: 1.7 $');class Bs_TextType extends Bs_Object {var $Bs_Url;var $Bs_ColorCodes;var $fontDir = '../_fonts/';var $fontFace;var $fontAntiAlias;var $fontSize;var $_fontColor;var $_bgColor;var $bgDir;var $bgImage;var $imgWidth;var $imgHeight;var $posX;var $posY;var $xAlign = 'left';var $yAlign = 'top';var $angle = 0;var $imgType;var $lineSpacing = '1.0';var $_text;var $_img;var $store;var $profileName;function Bs_TextType() {if (!extension_loaded('gd')) {trigger_error("You must activate the GD-libraray for this class to work. Check the PHP doc!", E_USER_ERROR);}
parent::Bs_Object(); if (!isSet($GLOBALS['Bs_Url'])) $GLOBALS['Bs_Url'] =& new Bs_Url;$this->Bs_Url = &$GLOBALS['Bs_Url'];if (!isSet($GLOBALS['Bs_ColorCodes'])) $GLOBALS['Bs_ColorCodes'] =& new Bs_ColorCodes;$this->Bs_ColorCodes = &$GLOBALS['Bs_ColorCodes'];}
function destruct() {@Imagedestroy($this->_img);}
function doItYourself($defaults=array()) {foreach ($defaults as $key => $value) {$this->set($key, $value);}
$this->_readFromUrl();$status = $this->create();if ($status && $this->store) $this->save();$this->send();$this->destruct();}
function _readFromUrl() {$scriptUrl = $this->_getRequestedFilePath();if ($scriptUrl === FALSE) die('Error 404 - File Not Found');if (strpos($scriptUrl, '.png') === FALSE) {die('Error 404 - File Not Found');}
$urlChunks = explode('/', $scriptUrl);$fileName = array_pop($urlChunks);$start = FALSE;$properties = array();foreach ($urlChunks as $urlChunk) {if (!$start) {if ($urlChunk === 'textType') {$start = TRUE;continue;}
} else {$urlProp = explode('_', $urlChunk, 2);$properties[$urlProp[0]] = $urlProp[1];}
}
if (isSet($properties['profileName'])) {$this->loadProfile($properties['profileName']);}
foreach ($properties as $propName => $propValue) {$this->set($propName, $propValue);}
$pos = strrpos($fileName, '.');if ($pos !== FALSE) {$imgType  = strToLower(substr($fileName, $pos +1));$fileName = substr($fileName, 0, $pos);}
$this->setText($GLOBALS['Bs_FileUtil']->decodeFilename($fileName));if (isSet($imgType)) $this->imgType = $imgType; }
function setFromRequest() {if (isSet($_GET['text'])) {$text = $_GET['text'];} else {$text = $this->getRequestedFileName();$pos = strrpos($text, '.');if ($pos !== FALSE) {$imgType = strToLower(substr($text, $pos +1));$text = substr($text, 0, $pos);}
$text = $GLOBALS['Bs_FileUtil']->decodeFilename($text);}
if (isSet($imgType)) $this->imgType = $imgType; $get = $this->_getQuerystringVars();if (isSet($get['posX']))      $this->posX      = $get['posX'];if (isSet($get['fontColor'])) {$this->setFontColor($get['fontColor']);}
$this->setText($text);}
function _getQuerystringVars() {$pos = strpos($_SERVER['REQUEST_URI'], '?');if ($pos === FALSE) return array();parse_str(substr($_SERVER['REQUEST_URI'], $pos+1), $ret);return $ret;}
function _getRequestedFilePath() {$possibleVars = array('REDIRECT_SCRIPT_URI', 'SCRIPT_URI', 'SCRIPT_URL', 'REDIRECT_URL', 'REQUEST_URI', 'REDIRECT_SCRIPT_URL');foreach ($possibleVars as $possibleVar) {if (!empty($_SERVER[$possibleVar])) {return $_SERVER[$possibleVar];}
}
return FALSE;}
function getRequestedFileName() {$text = $this->_getRequestedFilePath();if ($text === FALSE) return FALSE;$pos = strrpos($text, '/');if ($pos !== FALSE) {$text = substr($text, $pos +1);}
return $text;}
function getRequestedTextCleaned() {$text = $this->getRequestedFileName();$pos = strrpos($text, '.');if ($pos !== FALSE) {$imgType = strToLower(substr($text, $pos +1));$text = substr($text, 0, $pos);}
$text = $GLOBALS['Bs_FileUtil']->decodeFilename($text);return $text;}
function setText($text) {if (!is_array($text)) {$text = array($text);}
$newText = array();while (list(,$line) = each($text)) {$textArr = preg_split("/[\r\n|\r|\n]/", $line);while (list(,$newLine) = each($textArr)) {$newText[] = $newLine;}
}
if (sizeOf($newText) > 1) {$this->imgHeight = $this->imgHeight * sizeOf($newText);}
$this->_text = join("\r\n", $newText);}
function create() {if (empty($this->fontSize))  $this->fontSize  = 20;if (empty($this->fontFace))  $this->fontFace  = 'VERDANA';if ((empty($this->imgWidth) || ($this->imgWidth === 'auto') || (substr($this->imgWidth, 0, 1) == '+')) || (empty($this->imgHeight) || ($this->imgHeight === 'auto') || (substr($this->imgHeight, 0, 1) == '+'))) {$widthHeight = $this->calculateImageSize();if (empty($this->imgWidth) || ($this->imgWidth === 'auto')) {$this->imgWidth  = $widthHeight[0]; } elseif (substr($this->imgWidth, 0, 1) == '+') {$this->imgWidth  = $widthHeight[0] + substr($this->imgWidth, 1);}
if (empty($this->imgHeight) || ($this->imgHeight === 'auto')) {if (substr($this->imgHeight, 0, 1) == '+') {$this->imgHeight = $widthHeight[1] + substr($this->imgHeight, 1);} else {$this->imgHeight = $widthHeight[1];if (((getType($this->_text) == 'array') && (sizeOf($this->_text) > 1)) || ((getType($this->_text) == 'string') && (strPos($this->_text, "\n") > 0))) {$this->imgHeight += 10;}
}
}
if (!isSet($this->posY)) {$this->posY = $widthHeight[2];}
}
if ($this->bgImage) {$bgImageFullPath = $_SERVER['DOCUMENT_ROOT'] . 'textType/_backgrounds/' . $this->bgImage;$imgSize = getimagesize($bgImageFullPath);$width  = (@is_numeric($this->imgWidth)  && $this->imgWidth  > $imgSize[0]) ? $this->imgWidth  : $imgSize[0];$height = (@is_numeric($this->imgHeight) && $this->imgHeight > $imgSize[1]) ? $this->imgHeight : $imgSize[1];$this->_img = imagecreate($width, $height);$bgImg = imagecreatefrompng($bgImageFullPath);imagecopy($this->_img, $bgImg, 0, 0, 0, 0, imagesx($bgImg), imagesy($bgImg)); } else {if (TRUE) {$this->_img = ImageCreate($this->imgWidth, $this->imgHeight);} else {$this->_img = imagecreatetruecolor($this->imgWidth, $this->imgHeight);}
}
if ($this->_bgColor) {$imgColors['bgColor'] = ImageColorAllocate($this->_img, $this->_bgColor[0], $this->_bgColor[1], $this->_bgColor[2]);} else {$imgColors['white'] = 'dummy';$imgColors['bgColor'] = &$imgColors['white'];}
$imgColors['white']      = ImageColorAllocate($this->_img, 255, 255, 255);$imgColors['nearwhite']  = ImageColorAllocate($this->_img, 254, 255, 255);$imgColors['black']      = ImageColorAllocate($this->_img, 0, 0, 0);$imgColors['nearblack']  = ImageColorAllocate($this->_img, 33, 48, 66);$imgColors['softblue']   = ImageColorAllocate($this->_img, 189, 199, 206);if (is_array($this->_fontColor)) {$imgColors['fontColor'] = ImageColorAllocate($this->_img, $this->_fontColor[0], $this->_fontColor[1], $this->_fontColor[2]);} else {$imgColors['fontColor'] = $imgColors['nearblack'];}
if (!$this->bgImage) {imagecolortransparent($this->_img, $imgColors['bgColor']);}
putenv("GDFONTPATH=" . $this->fontDir);$font = $this->fontFace; $text = $this->_text; if ($this->xAlign === 'right') {$string_size = ImageFtBbox($this->fontSize, 0, $font, $text, array('linespacing'=>$this->lineSpacing));$s_width  = $string_size[4];$s_height = $string_size[5];$status = ImageFtText($this->_img, $this->fontSize, 0, $this->imgWidth - $s_width - 1,  0 - $s_height, $imgColors['fontColor'], $font, $text, array("linespacing"=>$this->lineSpacing));} elseif ($this->angle != 0) {$status = ImageFtText($this->_img, $this->fontSize, $this->angle, $this->posX, $this->posY, $imgColors['fontColor'], $font, $text, array("linespacing"=>$this->lineSpacing));} else { $status = ImageFtText($this->_img, $this->fontSize, 0, $this->posX, $this->posY, $imgColors['fontColor'], $font, $text, array("linespacing"=>$this->lineSpacing));}
if (!$status) {if (!Imagestring($this->_img, 3, 1, 1, $text, $imgColors['fontColor'])) {return -1;}
return 0;}
return 1;}
function calculateImageSize() {putenv("GDFONTPATH=" . $this->fontDir);$arr = ImageFtBbox($this->fontSize, $this->angle, $this->fontFace, $this->_text, array('linespacing'=>$this->lineSpacing));$ret = array($arr[2] - $arr[0], $arr[1] - $arr[5]);if (isSet($this->posX)) $ret[0] += $this->posX;$ret[1] *= $this->lineSpacing; $numLines = sizeOf(explode("\r\n", $this->_text));$ret[2] = $ret[1] / $numLines;return $ret;}
function get() {}
function send() {if (version_compare(phpversion(), '4.3') >= 0) {header('Status: 200 OK', TRUE, 200);} else {header('HTTP/1.0 200 OK'); }
$imgType = (isSet($this->imgType)) ? $this->imgType : 'png';switch ($imgType) {case 'gif':
break;case 'jpg':
case 'jpeg': header("Content-type: image/jpg");Imagejpeg($this->_img);break;default: header("Content-type: image/png");Imagepng($this->_img);}
}
function generateFileName() {$serverVar = $this->_getRequestedFilePath();if ($serverVar === FALSE) return FALSE;$pos = strrpos($serverVar, '/');$filename = substr($serverVar, $pos +1);$pos = strpos($_SERVER['REQUEST_URI'], '?');if ($pos !== FALSE) {$filename .= '-' . substr($_SERVER['REQUEST_URI'], $pos+1);}
return $filename;}
function save($path=NULL, $pathIncludesFile=TRUE) {if (is_null($path) || !$pathIncludesFile) {$filename = $this->generateFileName();if (is_null($path)) {$serverVar = $this->_getRequestedFilePath();$pos       = strrpos($serverVar, '/');$path      = $_SERVER['DOCUMENT_ROOT'] . substr($serverVar, 0, $pos);}
$path .= '/' . $filename;}
$imgType = (isSet($this->imgType)) ? $this->imgType : 'png';$status = $this->_save($path, $imgType);if (!$status) {$dir =& new Bs_Dir();$pathStem = $dir->getPathStem($path);if (!file_exists($pathStem)) {$dir->mkpath($pathStem);}
return $this->_save($path, $imgType); }
return TRUE;}
function _save($fullPath, $imgType) {switch ($imgType) {case 'gif':
break;case 'jpg':
case 'jpeg': return (bool)@Imagejpeg($this->_img, $fullPath);break;default: return (bool)@Imagepng($this->_img, $fullPath);}
}
function loadProfile($profileName) {$iniFullPath = $_SERVER['DOCUMENT_ROOT'] . 'textType/_profiles/' . $profileName . '.ini';$ini =& new Bs_IniHandler();if (!$ini->loadFile($iniFullPath)) return FALSE;$iniData = $ini->get('');foreach ($iniData as $key => $value) {$this->set($key, $value);}
return TRUE;}
function setFontColor($color) {if (is_string($color)) {$hex = $this->Bs_ColorCodes->nameToHex($color);if ($hex === FALSE) $hex = $color; $this->_fontColor = $this->Bs_ColorCodes->hexToRgb($hex);} elseif (is_array($color)) {$this->_fontColor = $color;}
}
function setBgColor($color) {if (is_string($color)) {$hex = $this->Bs_ColorCodes->nameToHex($color);if ($hex === FALSE) $hex = $color; $this->_bgColor = $this->Bs_ColorCodes->hexToRgb($hex);} elseif (is_array($color)) {$this->_bgColor = $color;}
}
function set($param, $value) {switch ($param) {case 'fontSize':
case 'posX':
case 'posY':
case 'xAlign':
case 'yAlign':
case 'angle':
case 'imgType':
case 'imgWidth':
case 'imgHeight':
case 'fontAntiAlias':
case 'lineSpacing':
$this->$param = $value;break;case '_fontColor':
case 'fontColor':
$this->setFontColor($value);break;case '_bgColor':
case 'bgColor':
$this->setBgColor($value);break;case 'fontDir';case 'bgDir';$this->$param = $value;break;case 'fontFace';$this->$param = $value;break;case '_text';case 'text';$this->setText($value);break;case 'bgImage';$this->$param = $value;break;case 'store';$this->$param = isTrue($value);break;case 'profileName';$this->$param = $value;break;default:
}
}
}
?>