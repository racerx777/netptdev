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
define('BS_HTMLTABLEWINDROSE_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core'] . 'util/Bs_IniHandler.class.php');class Bs_HtmlTableWindrose extends Bs_Object {var $_styleVariation  = array(); var $_attrVariation   = array(); var $_windroseMatrix = NULL;     var $_rowSize = 0;               var $_colSize = 0;               var $_styleData  = array();  var $_styleOrder = array();  var $_styleOrderNr = 0;      var $_attrData  = array();   var $_attrOrder = array();   var $_attrOrderNr = 0;       var $metaInfo = array();var $windroseMnomics = array(
'ALL'=>'All Cells',
'C'  =>'Center Cells', 
'DD' =>'Diagonal Down Row',
'DU' =>'Diagonal Up Row',
'N'  =>'North Row',
'W'  =>'West Row',
'S'  =>'South Row',
'E'  =>'East Row',
'NW' =>'North West Cell',
'SW' =>'South West Cell',
'SE' =>'South East Cell',
'NE' =>'North East Cell',
'ZR_0' =>'Zebra Rows, (the even numbers)',
'ZR_1' =>'Zebra Rows (the odd numbers)',
'ZC_0' =>'Zebra Cols (the even numbers)',
'ZC_1' =>'Zebra Rows (the odd numbers)',
);function Bs_HtmlTableWindrose () {parent::Bs_Object();$this->_Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil']; }
function setStyle($windroseID, $newStyle, $transparent=TRUE) {if ($windroseID == '') return;if (is_string($newStyle)) {$newStyle = $this->_Bs_HtmlUtil->parseStyleStr($newStyle);} else {$newStyle = $this->_hashKeysToLower($newStyle);}
if (sizeOf($newStyle)<=0) return;$this->_resetMatrix();$this->_styleData[$windroseID]['style'] = $newStyle;$this->_styleData[$windroseID]['transparent'] = $transparent;$this->_styleOrder[$windroseID] = $this->_styleOrderNr;$this->_styleOrderNr++;}
function setAttr($windroseID, $newAttr, $transparent=TRUE) {if ($windroseID == '') return;if (is_string($newAttr)) {$newAttr = $this->_Bs_HtmlUtil->parseAttrStr($newAttr);} else {$newAttr = $this->_hashKeysToLower($newAttr);}
if (sizeOf($newAttr)<=0) return;$this->_resetMatrix();$this->_attrData[$windroseID]['attr'] = $newAttr;$this->_attrData[$windroseID]['transparent'] = $transparent;$this->_attrOrder[$windroseID] = $this->_attrOrderNr;$this->_attrOrderNr++;}
function &getMatrix($rowSize, $colSize) {$matrix = &$this->_windroseMatrix;if (isSet($matrix) AND ($rowSize==$this->_rowSize) AND ($colSize==$this->_colSize)) return $matrix; $this->_rowSize = $rowSize;$this->_colSize = $colSize;$matrix = array();for ($i=0; $i<$rowSize; $i++) {for ($j=0; $j<$colSize; $j++) {$matrix[$i][$j] = NULL;}
}
if (sizeOf($this->_styleData)>0) {$this->_initMatrix($this->_styleData, $this->_styleOrder, $this->_styleVariation, 'style');}
if (sizeOf($this->_attrData)>0) {$this->_initMatrix($this->_attrData, $this->_attrOrder, $this->_attrVariation, 'attr');}
return $matrix;}
function &getCssBlock() {$out = '';reset ($this->_styleVariation);while (list($windroseID) = each($this->_styleVariation)) {$out .= str_pad('.'.$windroseID, 10);$styleAttrTxt = '';while (list($name, $val) = each($this->_styleVariation[$windroseID])) {$styleAttrTxt .= (is_null($val)) ? " {$name};" : " {$name}:{$val};";}
if (strLen($styleAttrTxt) > 0) {$out .= '{'.$styleAttrTxt."}\n";      }
} 
return $out;}
function isStyleTransparent($windroseID) {$tmp = $this->_styleData[$windroseID]['transparent'];return (isSet($tmp) AND $tmp);}
function isAttrTransparent($windroseID) {$tmp = $this->_attrData[$windroseID]['transparent'];return (isSet($tmp) AND $tmp);}
function getStyleStrings() {$styleStrings = array();asort($this->_styleOrder, SORT_NUMERIC);foreach($this->_styleOrder as $windroseID => $dummy) {$styleTxt = '';$dataArray = $this->_styleData[$windroseID]['style'];foreach($dataArray as $name => $val) {$styleTxt .= (is_null($val)) ? "{$name}; " : "{$name}:{$val}; ";}
$styleStrings[$windroseID] = $styleTxt;}
return $styleStrings;}
function getAttrStrings() {$attrStrings = array();asort($this->_attrOrder, SORT_NUMERIC);foreach($this->_attrOrder as $windroseID => $dummy) {$attrTxt = '';$dataArray = $this->_attrData[$windroseID]['attr'];foreach($dataArray as $name => $val) {$attrTxt .= (is_null($val)) ? "{$name} " : "{$name}=\"{$val}\" ";}
$attrStrings[$windroseID] = $attrTxt;}
return $attrStrings;}
function write($path='') {$out = <<< EOD
//////////////////////////////////////////////////////////////////////////////
// ini-file for Bs_HtmlTableWindrose.class.php (blueshoes.org)
// ===========================================================
// o) The Relative Position mnomics ID's (or keys)
//    - ALL
//      All cells
//    - Borders And Corners 
//      Defined by the wind rose mnomics. E.g. NW is top/left cell and N is top row
//                 N                +----+---+----+
//            NW   ¦   NE           ¦ NW ¦ N ¦ NE ¦
//               \ ¦ /              +----+---+----+
//          W ---- C ---- E         ¦ W  ¦ C ¦  E ¦
//               / ¦ \              +----+---+----+
//            SW   ¦   SE           ¦ SW ¦ S ¦ SE ¦
//                 S                +----+---+----+
//    - C (Center)
//       C (the center cells)
//    - DD (Diogonal Down) and DU (Diogonal Up)
//    - ZR_0/ZR_1 and ZC_0/ZC_1 (Zebra Row and Zebra Col)
//
// o) See sample below for setup. 
//    - NOTE: %TRANSPARENT% is default.
// E.g.
//  [style]
//      N = color:black; background-color:white; font-size:20px;
//      NE= %OPAQUE% color:#0066CC; font-size:12px; font-family:Verdana,Arial; font-weight:bold;
//  [attr]
//      W = %TRANSPARENT% aline="center"
//      E = aline="left"
//////////////////////////////////////////////////////////////////////////////

EOD;
if ($path === '') {$path = tempnam('/tmp', 'bss');}
$out .= "\n[info]\n";$out .= "    isFirstRowTitle = " . boolToString(@$this->metaInfo['isFirstRowTitle']) . "\n";$out .= "    isLastRowTitle  = " . boolToString(@$this->metaInfo['isLastRowTitle'])  . "\n";$out .= "    isFirstColTitle = " . boolToString(@$this->metaInfo['isFirstColTitle']) . "\n";$out .= "    isLastColTitle  = " . boolToString(@$this->metaInfo['isLastColTitle'])  . "\n";$out .= "    tableTag        = " . @$this->metaInfo['tableTag']                      . "\n";$out .= "\n[style]\n";$styleStr = $this->getStyleStrings();foreach($styleStr as $windroseID => $dummy) {$tmp = ($this->_styleData[$windroseID]['transparent']) ? '' : '%OPAQUE% ';$out .= "    {$windroseID} = {$tmp}{$styleStr[$windroseID]} \n";}
$out .= "\n[attr]\n";$attrStr = $this->getAttrStrings();foreach($attrStr as $windroseID => $dummy) {$tmp = ($this->_attrData[$windroseID]['transparent']) ? '' : '%OPAQUE%';$out .= "    {$windroseID} = {$tmp}{$attrStr[$windroseID]} \n";}
$fp = fopen($path, 'w');if ($fp) fwrite($fp, $out);@fclose($fp);}
function readByString($string) {$ini =& new Bs_IniHandler;$ini->loadString($string);$t = $ini->get('info');$this->metaInfo['isFirstRowTitle'] = isTrue(@$t['isFirstRowTitle']);$this->metaInfo['isLastRowTitle']  = isTrue(@$t['isLastRowTitle']);$this->metaInfo['isFirstColTitle'] = isTrue(@$t['isFirstColTitle']);$this->metaInfo['isLastColTitle']  = isTrue(@$t['isLastColTitle']);$this->metaInfo['tableTag']        = @$t['tableTag'];if (strpos($string, "\r\n") !== FALSE) {$lines = explode("\r\n", $string);} elseif (strpos($string, "\n\r") !== FALSE) {$lines = explode("\n\r", $string);} elseif (strpos($string, "\r") !== FALSE) {$lines = explode("\r", $string);} else {$lines = explode("\n", $string);}
$dataBlock='';$this->_reset();$lineSize = sizeOf($lines);for ($iL=0; $iL<$lineSize; $iL++) {$line = $lines[$iL];$noComment = preg_replace('/\/\/.*/', '', $line); if (strLen($noComment) < 3) continue; if (preg_match('/\[(\w+)\]/', $noComment, $regs)) {$dataBlock=strToLower($regs[1]);continue;}
if (!preg_match('/([\w|_]+)\s*=\s*(\%?)(\w*)\%?(.*)/i', $noComment, $regs))  continue;$windroseID = strToUpper($regs[1]);$dataStr = '';if ($regs[2] === '%') { $transparent = (strToUpper($regs[3]) === 'OPAQUE');$dataStr = trim($regs[4]);} else {$transparent = TRUE;$dataStr = trim($regs[3] . $regs[4]);}
switch ($dataBlock) {case 'style':
$this->setStyle($windroseID, $dataStr, $transparent);break;case 'attr':
$this->setAttr($windroseID, $dataStr, $transparent);break;default: ;}
} }
function read($path='') {if ($path === '') return;if (!is_readable($path)) return;$string = join('', file($path));$this->readByString($string);}
function _hashKeysToLower($hashArray) {$newHash = array();if (!is_array($hashArray)) return $newHash;$keyList = array_keys($hashArray);$keySize = sizeOf($keyList);for ($k=0; $k<$keySize; $k++) {$newHash[strToLower($keyList[$k])] = $hashArray[$keyList[$k]];}
return $newHash;}
function &_getMatrixStream($rowPos, $colPos, $x='', $y='') {$matrix = &$this->_windroseMatrix;$matrixStream = array();$deltaX = $this->_rowSize - $rowPos;  $deltaY = $this->_colSize - $colPos;  $x = (is_int($x)) ? min($x, $deltaX) : $deltaX;$y = (is_int($y)) ? min($y, $deltaY) : $deltaY;if (($x < 0) OR ($y < 0)) {$x=0; $y=0;}
for ($i=0; $i<$x; $i++) {for ($j=0; $j<$y; $j++) {$matrixStream[] = &$matrix[$rowPos+$i][$colPos+$j];}
}
return $matrixStream;}
function _reset() {$this->_styleData = array();$this->_styleOrder = array();$this->_styleOrderNr = 0;$this->_attrData = array();$this->_attrOrder = array();$this->_attrOrderNr = 0;$this->_resetMatrix();}
function _resetMatrix() {if (!isSet($this->_windroseMatrix)) return;unSet($this->_windroseMatrix);$this->_rowSize = 0;$this->_colSize = 0;}
function _initMatrix(&$windroseData, &$windroseOrder, &$variationList, $key) {$rowSize = $this->_rowSize;$colSize = $this->_colSize;$keyList = array_keys($windroseData);$keySize = sizeOf($keyList);for ($k=0; $k<$keySize; $k++) {$windroseID = $keyList[$k];$variationList[$windroseID] = &$windroseData[$windroseID][$key];}
$matrixStream = array();asort($windroseOrder, SORT_NUMERIC);$keyList = array_keys($windroseOrder);$keySize = sizeOf($keyList);for ($k=0; $k<$keySize; $k++) {$windroseID = $keyList[$k];switch ($windroseID) {case 'ALL':
$matrixStream = &$this->_getMatrixStream(0, 0, $rowSize, $colSize);break;case 'C':
$matrixStream = &$this->_getMatrixStream(1, 1, $rowSize-2, $colSize-2);break;case 'DD':
if (($rowSize>1) AND ($colSize>1)) {$stream = array();$matrix = &$this->_windroseMatrix;$colFrag = $rowSize / $colSize;for ($row=0; $row<$rowSize; $row++) {$col = round($colFrag*$row);$stream[] = &$matrix[$row][$col];}
}
$matrixStream = &$stream;break;case 'DU':
if (($rowSize>1) AND ($colSize>1)) {$stream = array();$matrix = &$this->_windroseMatrix;$colFrag = $rowSize / $colSize;for ($row=0; $row<$rowSize; $row++) {$col = round($colFrag*$row);$stream[] = &$matrix[($rowSize-$row-1)][$col];}
}
$matrixStream = &$stream;break;case 'N':
$matrixStream = &$this->_getMatrixStream(0, 0, 1, $colSize);break;case 'E':
$matrixStream = &$this->_getMatrixStream(0, $colSize-1, $rowSize, 1);break;case 'S':
$matrixStream = &$this->_getMatrixStream($rowSize-1, 0, 1, $colSize);break;case 'W':
$matrixStream = &$this->_getMatrixStream(0, 0, $rowSize, 1);break;case 'NE':
$matrixStream = &$this->_getMatrixStream(0, $colSize-1, 1, 1);break;case 'SE':
$matrixStream = &$this->_getMatrixStream($rowSize-1, $colSize-1, 1, 1);break;case 'SW':
$matrixStream = &$this->_getMatrixStream($rowSize-1, 0, 1, 1);break;case 'NW':
$matrixStream = &$this->_getMatrixStream(0, 0, 1, 1);break;case 'ZR_0':
$stream = array();for ($row=0; $row<$rowSize; $row+=2) {$stream = array_merge(&$stream, $this->_getMatrixStream($row, 0, 1, $colSize));}
$matrixStream = &$stream;break;case 'ZR_1':
$stream = array();for ($row=1; $row<$rowSize; $row+=2) {$stream = array_merge(&$stream, $this->_getMatrixStream($row, 0, 1, $colSize));}
$matrixStream = &$stream;break;case 'ZC_0':
$stream = array();for ($col=0; $col<$colSize; $col+=2) {$stream = array_merge(&$stream, $this->_getMatrixStream(0, $col, $rowSize, 1));}
$matrixStream = &$stream;break;case 'ZC_1':
$stream = array();for ($col=1; $col<$colSize; $col+=2) {$stream = array_merge(&$stream, $this->_getMatrixStream(0, $col, $rowSize, 1));}
$matrixStream = &$stream;break;default:
$tmp = array();$matrixStream = &$tmp;break;}
$areaSize = sizeOf($matrixStream);for ($i=0; $i<$areaSize; $i++) {$matrixCell = &$matrixStream[$i];$variationKey = $windroseID;if (isSet($matrixCell[$key]) AND $windroseData[$windroseID]['transparent']) {$variationKey = $matrixCell[$key.'ID'] . '_' . $windroseID;if (!isSet($variationList[$variationKey])) {$variationList[$variationKey] =  array_merge($matrixCell[$key], $windroseData[$windroseID][$key]);}
}
$matrixCell[$key.'ID'] = $variationKey;$matrixCell[$key] = &$variationList[$variationKey];} } }
}
?>