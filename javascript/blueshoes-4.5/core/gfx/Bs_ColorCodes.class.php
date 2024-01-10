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
define('BS_COLORCODES_VERSION',         '4.5.$Revision: 1.3 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_ColorCodes extends Bs_Object {var $_webSafe;var $_namedColors;var $_predefinedColors;function Bs_ColorCodes() {parent::Bs_Object(); }
function hexToRgb($hex) {$hex = $this->_cleanCode($hex);return array(hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));}
function rgbToHex($rgb) {$r = dechex($rgb[0]);if (strlen($r) < 2) $r = '0' . $r;$g = dechex($rgb[1]);if (strlen($g) < 2) $g = '0' . $g;$b = dechex($rgb[2]);if (strlen($b) < 2) $b = '0' . $b;return $r . $g . $b;}
function nameToHex($colorName) {$colorName = strToLower($colorName);$this->_createNamedColors();if (isSet($this->_namedColors[$colorName])) return $this->_namedColors[$colorName];return FALSE;}
function isDark($colorCode) {$colorCode = $this->_cleanCode($colorCode);$char = substr($colorCode, 2, 1);return (is_numeric($char) && ($char < 8));}
function getWebSafe() {$this->_createWebSafe();return $this->_webSafe;}
function makeWebSafe($colorCode) {$colorCode = $this->_cleanCode($colorCode);$ret       = '';for ($i=0; $i<3; $i++) {$char = $colorCode[($i*2)];switch ($char) {case '0': case '1':
$newChar = 0;break;case '2': case '3': case '4':
$newChar = 3;break;case '5': case '6': case '7':
$newChar = 6;break;case '8': case '9': case 'A':
$newChar = 9;break;case 'B': case 'C': case 'D':
$newChar = 'C';break;default:
$newChar = 'F';break;}
$ret .= $newChar . $newChar;}
return $ret;}
function isGray($colorCode) {$colorCode = $this->_cleanCode($colorCode);$r = substr($colorCode, 0, 2);$g = substr($colorCode, 2, 2);$b = substr($colorCode, 4, 2);return (($r == $g) && ($r == $b));}
function toGray($colorCode) {$colorCode = $this->_cleanCode($colorCode);$rgb = $this->hexToRgb($colorCode);$av = ($rgb[0] + $rgb[1] + $rgb[2]) /3;return $this->rgbToHex(array($av, $av, $av));}
function dumpWebSafe() {$this->_createWebSafe();$ret = '<table><tr>';$i=0;foreach ($this->_webSafe as $dev0 => $color) {if ($i == 6) {$i = 0;$ret .= '</tr><tr>';}
$fontColor = ($this->isDark($color)) ? 'white' : 'black';$ret .= "<td bgcolor='{$color}'><font color='{$fontColor}' face='arial' size='2'>{$color}</font></td>";$i++;}
$ret .= '</tr></table>';return $ret;}
function isWebSafe($colorCode) {$colorCode = $this->_cleanCode($colorCode);$this->_createWebSafe();return in_array($colorCode, $this->_webSafe);}
function createDifferentColors($num, $noWhite=TRUE, $returnRgb=FALSE, $usePredefined=TRUE) {$colors = array();if ($usePredefined) {$this->_loadPredefinedColors();$i=0;foreach($this->_predefinedColors as $cCode) {$colors[] = $cCode;$i++;if ($i == $num) return $colors;}
$num -= sizeOf($this->_predefinedColors);}
$r = 0;$g = 0;$b = 0;$numSteps = ceil(log10($num)/log10(3));if (($numSteps * $numSteps * $numSteps) < $num) $numSteps++;if ($numSteps >= 2) {$step = ceil(255 / ($numSteps -1)); } else {$step = 255;}
for ($i=0; $i<$numSteps; $i++) {$r = $i * $step;if ($r > 255) $r = 255;for ($j=0; $j<$numSteps; $j++) {$g = $j * $step;if ($g > 255) $g = 255;for ($k=0; $k<$numSteps; $k++) {$b = $k * $step;if ($b > 255) $b = 255;if (($noWhite) && (($r==255) && ($b==255) && ($g==255))) {$r=240; $g=240; $b=240;}
if ($returnRgb) {$colors[] = array($r, $g, $b);} else {$colors[] = $this->rgbToHex(array($r, $g, $b));}
}
}
}
return $colors;}
function _cleanCode($colorCode) {$colorCode = strToUpper($colorCode);if ($colorCode[0] == '#') $colorCode = substr($colorCode, 1);return $colorCode;}
function _createWebSafe() {if (!is_array($this->_webSafe)) {$x = array('00', '33', '66', '99', 'CC', 'FF');static $colors = array();for ($i=0; $i<6; $i++) {$colLeft = $x[$i];for ($j=0; $j<6; $j++) {$colMid = $x[$j];for ($k=0; $k<6; $k++) {$colors[] = $colLeft . $colMid . $x[$k];}
}
}
$this->_webSafe = &$colors;}
}
function _loadPredefinedColors() {if (empty($this->_predefinedColors) OR !is_array($this->_predefinedColors)) {static $c = array(
'FF0000', '0000FF', 'FFFF00', '00FF00', 'FF00FF', 'FF9900', 
'996600', '66CCFF', '009966', '990099', 'FFFF99', '999966', 
'FF6666', '00FFCC', '990000', '99FF99', '666666', 'FF99FF', 
'999900', '000099', '006600', 
);$this->_predefinedColors = &$c;}
}
function _createNamedColors() {if (!is_array($this->_namedColors)) {static $c = array();$c['aliceblue'] = 'F0F8FF';$c['antiquewhite'] = 'FAEBD7';$c['aqua'] = '00FFFF';$c['aquamarine'] = '7FFFD4';$c['azure'] = 'F0FFFF';$c['beige'] = 'F5F5DC';$c['bisque'] = 'FFE4C4';$c['black'] = '000000';$c['blanchedalmond'] = 'FFEBCD';$c['blue'] = '0000FF';$c['blueviolet'] = '8A2BE2';$c['brown'] = 'A52A2A';$c['burlywood'] = 'DEB887';$c['cadetblue'] = '5F9EA0';$c['chartreuse'] = '7FFF00';$c['chocolate'] = 'D2691E';$c['coral'] = 'FF7F50';$c['cornflowerblue'] = '6495ED';$c['cornsilk'] = 'FFF8DC';$c['crimson'] = 'DC143C';$c['cyan'] = '00FFFF';$c['darkblue'] = '00008B';$c['darkcyan'] = '008B8B';$c['darkgoldenrod'] = 'B8860B';$c['darkgray'] = 'A9A9A9';$c['darkgreen'] = '006400';$c['darkkhaki'] = 'BDB76B';$c['darkmagenta'] = '8B008B';$c['darkolivegreen'] = '556B2F';$c['darkorange'] = 'FF8C00';$c['darkorchid'] = '9932CC';$c['darkred'] = '8B0000';$c['darksalmon'] = 'E9967A';$c['darkseagreen'] = '8FBC8F';$c['darkslateblue'] = '483D8B';$c['darkslategray'] = '2F4F4F';$c['darkturquoise'] = '00CED1';$c['darkviolet'] = '9400D3';$c['deeppink'] = 'FF1493';$c['deepskyblue'] = '00BFFF';$c['dimgray'] = '696969';$c['dodgerblue'] = '1E90FF';$c['feldspar'] = 'D19275';$c['firebrick'] = 'B22222';$c['floralwhite'] = 'FFFAF0';$c['forestgreen'] = '228B22';$c['fuchsia'] = 'FF00FF';$c['gainsboro'] = 'DCDCDC';$c['ghostwhite'] = 'F8F8FF';$c['gold'] = 'FFD700';$c['goldenrod'] = 'DAA520';$c['gray'] = '808080';$c['green'] = '008000';$c['greenyellow'] = 'ADFF2F';$c['honeydew'] = 'F0FFF0';$c['hotpink'] = 'FF69B4';$c['indianred'] = 'CD5C5C';$c['indigo'] = '4B0082';$c['ivory'] = 'FFFFF0';$c['khaki'] = 'F0E68C';$c['lavender'] = 'E6E6FA';$c['lavenderblush'] = 'FFF0F5';$c['lawngreen'] = '7CFC00';$c['lemonchiffon'] = 'FFFACD';$c['lightblue'] = 'ADD8E6';$c['lightcoral'] = 'F08080';$c['lightcyan'] = 'E0FFFF';$c['lightgoldenrodyellow'] = 'FAFAD2';$c['lightgrey'] = 'D3D3D3';$c['lightgreen'] = '90EE90';$c['lightpink'] = 'FFB6C1';$c['lightsalmon'] = 'FFA07A';$c['lightseagreen'] = '20B2AA';$c['lightskyblue'] = '87CEFA';$c['lightslateblue'] = '8470FF';$c['lightslategray'] = '778899';$c['lightsteelblue'] = 'B0C4DE';$c['lightyellow'] = 'FFFFE0';$c['lime'] = '00FF00';$c['limegreen'] = '32CD32';$c['linen'] = 'FAF0E6';$c['magenta'] = 'FF00FF';$c['maroon'] = '800000';$c['mediumaquamarine'] = '66CDAA';$c['mediumblue'] = '0000CD';$c['mediumorchid'] = 'BA55D3';$c['mediumpurple'] = '9370D8';$c['mediumseagreen'] = '3CB371';$c['mediumslateblue'] = '7B68EE';$c['mediumspringgreen'] = '00FA9A';$c['mediumturquoise'] = '48D1CC';$c['mediumvioletred'] = 'C71585';$c['midnightblue'] = '191970';$c['mintcream'] = 'F5FFFA';$c['mistyrose'] = 'FFE4E1';$c['moccasin'] = 'FFE4B5';$c['navajowhite'] = 'FFDEAD';$c['navy'] = '000080';$c['oldlace'] = 'FDF5E6';$c['olive'] = '808000';$c['olivedrab'] = '6B8E23';$c['orange'] = 'FFA500';$c['orangered'] = 'FF4500';$c['orchid'] = 'DA70D6';$c['palegoldenrod'] = 'EEE8AA';$c['palegreen'] = '98FB98';$c['paleturquoise'] = 'AFEEEE';$c['palevioletred'] = 'D87093';$c['papayawhip'] = 'FFEFD5';$c['peachpuff'] = 'FFDAB9';$c['peru'] = 'CD853F';$c['pink'] = 'FFC0CB';$c['plum'] = 'DDA0DD';$c['powderblue'] = 'B0E0E6';$c['purple'] = '800080';$c['red'] = 'FF0000';$c['rosybrown'] = 'BC8F8F';$c['royalblue'] = '4169E1';$c['saddlebrown'] = '8B4513';$c['salmon'] = 'FA8072';$c['sandybrown'] = 'F4A460';$c['seagreen'] = '2E8B57';$c['seashell'] = 'FFF5EE';$c['sienna'] = 'A0522D';$c['silver'] = 'C0C0C0';$c['skyblue'] = '87CEEB';$c['slateblue'] = '6A5ACD';$c['slategray'] = '708090';$c['snow'] = 'FFFAFA';$c['springgreen'] = '00FF7F';$c['steelblue'] = '4682B4';$c['tan'] = 'D2B48C';$c['teal'] = '008080';$c['thistle'] = 'D8BFD8';$c['tomato'] = 'FF6347';$c['turquoise'] = '40E0D0';$c['violet'] = 'EE82EE';$c['violetred'] = 'D02090';$c['wheat'] = 'F5DEB3';$c['white'] = 'FFFFFF';$c['whitesmoke'] = 'F5F5F5';$c['yellow'] = 'FFFF00';$c['yellowgreen'] = '9ACD32';$this->_namedColors = &$c;}
}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_ColorCodes.class.php') {$col =& new Bs_ColorCodes();$t = $col->createDifferentColors(3);echo '3<table border="1">';$i=0;while (list(,$color) = each($t)) {echo "<td width='20' height='20' bgcolor='{$color}' title='{$color}'>{$i}</td>";$i++;}
echo '</table><br><br>';$t = $col->createDifferentColors(8);echo '8<table border="1">';$i=0;while (list(,$color) = each($t)) {echo "<td width='20' height='20' bgcolor='{$color}' title='{$color}'>{$i}</td>";$i++;}
echo '</table><br><br>';$t = $col->createDifferentColors(10);echo '10<table border="1">';$i=0;while (list(,$color) = each($t)) {echo "<td width='20' height='20' bgcolor='{$color}' title='{$color}'>{$i}</td>";$i++;}
echo '</table><br><br>';$t = $col->createDifferentColors(30);echo '30<table border="1">';$i=0;while (list(,$color) = each($t)) {echo "<td width='20' height='20' bgcolor='{$color}' title='{$color}'>{$i}</td>";$i++;}
echo '</table><br><br>';$t = $col->createDifferentColors(60);echo '60<table border="1">';$i=0;while (list(,$color) = each($t)) {echo "<td width='20' height='20' bgcolor='{$color}' title='{$color}'>{$i}</td>";$i++;}
echo '</table><br><br>';$t = $col->createDifferentColors(90);echo '90<table border="1">';$i=0;while (list(,$color) = each($t)) {echo "<td width='20' height='20' bgcolor='{$color}' title='{$color}'>{$i}</td>";$i++;}
echo '</table><br><br>';$t = $col->createDifferentColors(150);echo '150<table border="1">';$i=0;while (list(,$color) = each($t)) {echo "<td width='20' height='20' bgcolor='{$color}' title='{$color}'>{$i}</td>";$i++;}
echo '</table><br><br>';$t = $col->createDifferentColors(300);echo '300<table border="1">';$i=0;while (list(,$color) = each($t)) {echo "<td width='20' height='20' bgcolor='{$color}' title='{$color}'>{$i}</td>";$i++;}
echo '</table><br><br>';}
?>