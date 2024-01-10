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
define('BS_HTMLTABLE_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');class Bs_HtmlTable extends Bs_Object {var $cell = array();var $_rowSize = 0;var $_colSize = 0;var $_tableAttr    = array(); var $_trAttr       = array(); var $_globalTrAttr = array(); var $_globalTdAttr = array(); var $_tableStyle    = array(); var $_trStyle       = array(); var $_globalTrStyle = array(); var $_globalTdStyle = array(); var $htmlTableWindrose = NULL;   function Bs_HtmlTable($matrix=NULL) {parent::Bs_Object();if (isSet($matrix)) $this->initByMatrix(&$matrix);$this->_Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil']; }
function clear() {$this->cell = array();$this->_rowSize = 0; $this->_colSize = 0; $this->_tableAttr    = array(); $this->_trAttr       = array(); $this->_globalTrAttr = array(); $this->_globalTdAttr = array(); $this->_tableStyle    = array(); $this->_trStyle       = array(); $this->_globalTrStyle = array(); $this->_globalTdStyle = array(); }
function getNumRows() {return $this->_rowSize;}
function getNumCols() {return $this->_colSize;}
function initByMatrix($value) {for ($row=0; $row<$this->_rowSize; $row++) {for ($col=0; $col<$this->_colSize; $col++) {unSet($this->cell[$row][$col]['data']);}
}
if ( is_array($value) ) {$value_1 = &$value;} else {$value_1 = array(&$value);}
if ( is_array(current($value_1)) ) {$matrix = &$value_1;} else {$matrix = array(&$value_1);}
$maxRow = sizeOf($matrix);$maxCol = 0;reset($matrix);while (list($rowKey) = each($matrix)) {$maxCol = max($maxCol,  sizeOf($matrix[$rowKey]));}
reset($matrix);$row = $col = 0;while (list($rowKey) = each($matrix)) {$innerArray = &$matrix[$rowKey];reset($innerArray);$col = 0;while (list($colKey) = each($innerArray)) {$this->cell[$row][$col]['data'] = &$matrix[$rowKey][$colKey];$col++;}
for($i=$col; $i<$maxCol; $i++) {$this->cell[$row][$i]['data'] = NULL;}
$row++;}
$this->_rowSize = $maxRow;$this->_colSize = $maxCol;return TRUE;}
function pushRow($value, $rowPos=-1) {if ( !is_array($value) ) {$newRow = array(&$value);} else {$newRow = &$value;}
$rowPos = (($rowPos < 0) OR ($rowPos > $this->_rowSize)) ? $this->_rowSize : $rowPos;if (sizeOf($newRow) > $this->_colSize) {$newRow = array_slice($newRow,0,$this->_colSize);}
$tmpArr = array();$colCounter = 0;reset($newRow);while (list($key) = each($newRow)) {if ($colCounter >= $this->_colSize) break;$tmpArr[$colCounter]['data'] = &$newRow[$key];$colCounter++;}
for ($i=$colCounter; $i<$this->_colSize; $i++) {$tmpArr[$i]['data'] = NULL;}
if ($rowPos == 0) {array_unshift($this->cell, $tmpArr); } else if ($rowPos >= $this->_rowSize) {array_push($this->cell, $tmpArr);    } else {$endArr = array_splice($this->cell, $rowPos);array_push($this->cell, $tmpArr);$this->cell = array_merge($this->cell, $endArr);}
$this->_rowSize++;return $rowPos;}
function pushCol($value, $colPos=-1) {$this->flipData();$status = $this->pushRow(&$value, $colPos);$this->flipData();return $status;}
function setRow($rowPos, $value, $offset=0, $amount='') {$cellStream = &$this->getAreaStream($rowPos, $offset, 1, $amount);$streamSize = min(sizeOf($cellStream), sizeOf($value));if (is_array($value)) reset($value);for ($i=0; $i<$streamSize; $i++) {if (is_array($value)) {list($dev0, $cellStream[$i]['data']) = each($value);} else {$cellStream[$i]['data'] = $value;}
}
}
function setCol($colPos, $value, $offset=0, $amount='') {$cellStream = &$this->getAreaStream($offset, $colPos, $amount, 1);$streamSize = min(sizeOf($cellStream), sizeOf($value));if (is_array($value)) reset($value);for ($i=0; $i<$streamSize; $i++) {if (is_array($value)) {list($dev0, $cellStream[$i]['data']) = each($value);} else {$cellStream[$i]['data'] = $value;}
}
}  
function setArea($rowPos, $colPos, $value, $x='', $y='') {if (($rowPos<0) OR ($colPos<0)) return;$maxRow = $this->_rowSize; $maxCol = $this->_colSize;if (is_array($value)) {reset($value);$maxRow = min(($rowPos + sizeOf($value)), $maxRow);}
for ($row=$rowPos; $row<$maxRow; $row++) { if (is_array($value)) {list($dev0, $rowValue) = each($value);if (is_array($rowValue)) {reset($rowValue);$maxRow = min(($rowPos + sizeOf($rowValue)) , $maxRow);}
} else {$rowValue = $value;}
for ($col=$colPos; $col<$maxCol; $col++) { if (is_array($rowValue)) {list($dev0, $this->cell[$row][$col]['data']) = each($rowValue);} else {$this->cell[$row][$col]['data'] = $rowValue;}
} } }
function &getRow($rowPos, $what=NULL) {return getAreaStream($rowPos, $colPos=0, $x=1, $y='', $what);}
function &getCol($colPos, $what=NULL) {return getAreaStream($rowPos=0, $colPos, $x='', $y=1, $what);}  
function &getArea($rowPos=0, $colPos=0, $x='', $y='', $what=NULL) {$cellArea = array(array());list($x, $y) = $this->_trimArea($rowPos, $colPos, $x, $y);  for ($ix=0; $ix<$x; $ix++) {for ($iy=0; $iy<$y; $iy++) {$cell = &$this->cell[$rowPos+$ix][$colPos+$iy];if (!is_null($what)) {if (isSet($cell[$what])) {$cellArea[$ix][$iy] = &$cell[$what];} else {$cellArea[$ix][$iy] = NULL;}
} else {$cellArea[$ix][$iy] = &$cell;}
}
}
return $cellArea;}
function &getAreaStream($rowPos=0, $colPos=0, $x='', $y='', $what=NULL) {$cellStream = array();list($x, $y) = $this->_trimArea($rowPos, $colPos, $x, $y);  for ($ix=0; $ix<$x; $ix++) {for ($iy=0; $iy<$y; $iy++) {$cell = &$this->cell[$rowPos+$ix][$colPos+$iy];if (!is_null($what)) {if (isSet($cell[$what])) {$cellStream[] = &$cell[$what];} else {$cellStream[] = NULL;}
} else {$cellStream[] = &$cell;}
}
}
return $cellStream;}
function hideRow($rowPos, $offset=0, $amount='') {$this->hideArea($rowPos, $offset, 1, $amount);}
function hideCol($colPos, $offset=0, $amount='') {$this->hideArea($offset, $colPos, $amount, 1);}
function hideArea($rowPos, $colPos, $x='', $y='') {$cellStream = &$this->getAreaStream($rowPos, $colPos, $x, $y);$streamSize = sizeOf($cellStream);for ($i=0; $i<$streamSize; $i++) {$cellStream[$i]['hidden'] = TRUE;}
}
function spanRow($rowPos, $offset=0, $amount='') {return $this->spanArea($rowPos, $offset, $amount, 1);}
function spanCol($colPos, $offset=0, $amount='') {return $this->spanArea($offset, $colPos, 1, $amount);}
function spanArea($rowPos, $colPos, $x='', $y='') {if (is_numeric($x) AND ($x<=1) AND is_numeric($y) AND ($y<=1)) return TRUE; $cellStream = &$this->getAreaStream($rowPos, $colPos, &$x, &$y);$streamSize = sizeOf($cellStream);if ($streamSize<=1) return TRUE;  for ($i=0; $i<$streamSize; $i++) {$tmp1 = isSet($cellStream[$i]['spaned']) ? $cellStream[$i]['spaned'] : FALSE;$tmp2 = isSet($cellStream[$i]['span_start']) ? $cellStream[$i]['span_start'] : FALSE;if ($tmp1 OR $tmp2) {$absCol = $colPos + ($i % $this->_colSize);$absRow = $rowPos + (integer) ($i / $this->_colSize);return FALSE;} 
}
$cellStream[0]['span_start'] = TRUE;if (!isSet($cellStream[0]['attr'])) $cellStream[0]['attr'] = array();if ($x>1) $cellStream[0]['attr']['rowspan'] = $x;if ($y>1) $cellStream[0]['attr']['colspan'] = $y;for ($i=1; $i<$streamSize; $i++) {$cellStream[$i]['spaned'] = TRUE;}
return TRUE;}
function setTableAttr($newAttr) {if (is_string($newAttr)) {$newAttr = &$this->_Bs_HtmlUtil->parseAttrStr($newAttr);} else {$newAttr = &$this->_hashKeysToLower($newAttr);}
if (sizeOf($newAttr)<=0) return;$attr = &$this->_tableAttr;$attr = array_merge($attr, $newAttr);}
function setTrAttr($rowPos, $newAttr) {if ($rowPos<0) return;if (is_string($newAttr)) {$newAttr = &$this->_Bs_HtmlUtil->parseAttrStr($newAttr);} else {$newAttr = &$this->_hashKeysToLower($newAttr);}
if (sizeOf($newAttr)<=0) return;$attr = &$this->_trAttr[$rowPos];$attr = array_merge($attr, $newAttr);}
function setTdAttr($rowPos, $colPos, $newAttr) {$this->setAreaAttr($rowPos, $colPos, &$newAttr, 1, 1);}
function setGlobalTrAttr($newAttr) {if (is_string($newAttr)) {$newAttr = &$this->_Bs_HtmlUtil->parseAttrStr($newAttr);} else {$newAttr = &$this->_hashKeysToLower($newAttr);}
if (sizeOf($newAttr)<=0) return;$attr = &$this->_globalTrAttr;$attr = array_merge($attr, $newAttr);}
function setGlobalTdAttr($newAttr) {if (is_string($newAttr)) {$newAttr = &$this->_Bs_HtmlUtil->parseAttrStr($newAttr);} else {$newAttr = &$this->_hashKeysToLower($newAttr);}
if (sizeOf($newAttr)<=0) return;$attr = &$this->_globalTdAttr;$attr = array_merge($attr, $newAttr);}
function setRowAttr($rowPos, $newAttr, $offset=0, $amount='') {return $this->setAreaAttr($rowPos, $offset, &$newAttr, 1, $amount);}
function setColAttr($colPos, $newAttr, $offset=0, $amount='') {return $this->setAreaAttr($offset, $colPos, &$newAttr, $amount, 1);}
function setAreaAttr($rowPos, $colPos, $newAttr, $x='', $y='') {if (is_string($newAttr)) {$newAttr = &$this->_Bs_HtmlUtil->parseAttrStr($newAttr);} else {$newAttr = &$this->_hashKeysToLower($newAttr);}
if (sizeOf($newAttr)<=0) return;$cellArea = &$this->getAreaStream($rowPos, $colPos, $x, $y);$areaSize = sizeOf($cellArea);for ($i=0; $i<$areaSize; $i++) {if (!isSet($cellArea[$i]['attr'])) $cellArea[$i]['attr'] = array();$cellArea[$i]['attr'] = array_merge($cellArea[$i]['attr'], $newAttr);}
}
function setTableStyle($newStyle) {if (is_string($newStyle)) {$newStyle = &$this->_Bs_HtmlUtil->parseStyleStr($newStyle);} else {$newStyle = &$this->_hashKeysToLower($newStyle);}
if (sizeOf($newStyle)<=0) return;$attr = &$this->_tableStyle;$attr = array_merge($attr, $newStyle);}
function setTrStyle($rowPos, $newStyle) {if ($rowPos<0) return;if (is_string($newStyle)) {$newStyle = &$this->_Bs_HtmlUtil->parseStyleStr($newStyle);} else {$newStyle = &$this->_hashKeysToLower($newStyle);}
if (sizeOf($newStyle)<=0) return;$attr = &$this->_trStyle[$rowPos];$attr = array_merge($attr, $newStyle);}
function setTdStyle($rowPos, $colPos, $newStyle) {$this->setAreaStyle($rowPos, $colPos, &$newStyle, 1, 1);}
function setGlobalTrStyle($newStyle) {if (is_string($newStyle)) {$newStyle = &$this->_Bs_HtmlUtil->parseStyleStr($newStyle);} else {$newStyle = &$this->_hashKeysToLower($newStyle);}
if (sizeOf($newStyle)<=0) return;$attr = &$this->_globalTrStyle;$attr = array_merge($attr, $newStyle);}
function setGlobalTdStyle($newStyle) {if (is_string($newStyle)) {$newStyle = &$this->_Bs_HtmlUtil->parseStyleStr($newStyle);} else {$newStyle = &$this->_hashKeysToLower($newStyle);}
if (sizeOf($newStyle)<=0) return;$attr = &$this->_globalTdStyle;$attr = array_merge($attr, $newStyle);}
function setRowStyle($rowPos, $newStyle, $offset=0, $amount='') {return $this->setAreaStyle($rowPos, $offset, &$newStyle, 1, $amount);}
function setColStyle($colPos, $newStyle, $offset=0, $amount='') {return $this->setAreaStyle($offset, $colPos, &$newStyle, $amount, 1);}
function setAreaStyle($rowPos, $colPos, $newStyle, $x='', $y='') {if (is_string($newStyle)) {$newStyle = &$this->_Bs_HtmlUtil->parseStyleStr($newStyle);} else {$newStyle = &$this->_hashKeysToLower($newStyle);}
if (sizeOf($newStyle)<=0) return;$cellArea = &$this->getAreaStream($rowPos, $colPos, $x, $y);$areaSize = sizeOf($cellArea);for ($i=0; $i<$areaSize; $i++) {if (!isSet($cellArea[$i]['style'])) $cellArea[$i]['style'] = array();$cellArea[$i]['style'] = array_merge($cellArea[$i]['style'], $newStyle);}
}
function setWindroseStyle($htmlWindroseObj) {if (!is_object($htmlWindroseObj)) return;$expectedClass = 'bs_htmltablewindrose';if ((get_class($htmlWindroseObj) === $expectedClass) OR (is_subclass_of($htmlWindroseObj, $expectedClass))) {$this->htmlTableWindrose = &$htmlWindroseObj;}
}
function &renderTable($useClassID=FALSE, $indent=2, $tableTag=NULL) {$windroseStyleMatrix = NULL;if (isSet($this->htmlTableWindrose)) {$windroseStyleMatrix = &$this->htmlTableWindrose->getMatrix($this->_rowSize, $this->_colSize);}
$tbIndentStr = str_pad('', $indent);$trIndentStr = str_pad('', $indent+2);$tdIndentStr = str_pad('', $indent+4);if (is_null($tableTag)) {$out = $tbIndentStr . $this->_renderTag('TABLE', $this->_tableAttr, $this->_tableStyle) . "\n";       } else {$out = $tableTag;}
$trIndent = $indent + 2;for ($row=0; $row < $this->_rowSize; $row++) {$trAttr = $this->_globalTrAttr;if (isSet($this->_trAttr[$row])) {$trAttr = array_merge($trAttr, $this->_trAttr[$row]);}
$trStyle = $this->_globalTrStyle;if (isSet($this->_trStyle[$row])) {$trStyle = array_merge($trStyle, $this->_trStyle[$row]);}      
$out .= $trIndentStr . $this->_renderTag('TR', $trAttr, $trStyle) . "\n";  for ($col=0; $col < $this->_colSize; $col++) {if (isSet($this->cell[$row][$col]['spaned'])) continue; $tdAttr = $this->_globalTdAttr;$tmp = &$this->_tdAttr[$row];if (isSet($this->_tdAttr[$row])) {$tdAttr = array_merge($tdAttr, $tmp);}
$tmp = &$this->cell[$row][$col]['attr'];if (isSet($tmp)) {$tdAttr = array_merge($tdAttr, $tmp);}
$tmp = &$windroseStyleMatrix[$row][$col]['attr'];if (isSet($windroseStyleMatrix) AND isSet($tmp)) {$tdAttr = array_merge($tmp, $tdAttr);}
$tdStyle = $this->_globalTdStyle;$tmp = &$windroseStyleMatrix[$row][$col]['style'];if (isSet($windroseStyleMatrix) AND isSet($tmp)) {if ($useClassID) {$tdAttr['class'] = $windroseStyleMatrix[$row][$col]['styleID'];} else {$tdStyle = array_merge($tdStyle, $tmp);}
}
$tmp = &$this->cell[$row][$col]['style'];if (isSet($tmp)) {$tdStyle = array_merge($tdStyle, $tmp);}
$out .= $tdIndentStr . $this->_renderTag('TD', $tdAttr, $tdStyle);    $content =  &$this->cell[$row][$col];$contValue = &$content['data'];$contHidden = (isSet($content['hidden'])) ? $content['hidden'] : FALSE;if ($contHidden OR (!isSet($contValue))) { $out .= '&nbsp;';} else if (is_object($contValue)) {                $myClass = get_class($this);if ((get_class($contValue) === $myClass) OR (is_subclass_of($contValue, $myClass))) {$out .= "\n" .$contValue->renderTable($useClassID, $indent+6);}
} else {                                           if ($contValue == '') {$out .= '&nbsp;';} else {$out .= $contValue;}
}
$out .= ($out[strLen($out)-1] === "\n") ?  $tdIndentStr : '';$out .= "</TD>\n";}
$out .= $trIndentStr . "</TR>\n";}
$out .= $tbIndentStr . "</TABLE>\n";return $out;}
function &toHtml() {return $this->renderTable();}
function flipData() {$tempCell = array();$rowSize = sizeOf($this->cell);for ($row=0; $row < $rowSize; $row++) {$colSize = sizeOf($this->cell[$row]);for ($col=0; $col < $colSize; $col++) {$tempCell[$col][$row] = &$this->cell[$row][$col];}
}
$this->cell = &$tempCell;$tmp = $this->_colSize;$this->_colSize = $this->_rowSize;$this->_rowSize = $tmp;}
function _trimArea($rowPos=0, $colPos=0, $x='', $y='') {$deltaX = $this->_rowSize - $rowPos;  $deltaY = $this->_colSize - $colPos;  $x = (is_numeric($x)) ? min($x, $deltaX) : $deltaX;$y = (is_numeric($y)) ? min($y, $deltaY) : $deltaY;if (($x < 0) OR ($y < 0)) {$x=0;$y=0;}
return array($x, $y);}
function &_hashKeysToLower(&$hashArray) {$newHash = array();if (!is_array($hashArray)) return $newHash;reset($hashArray);while (list($key) = each($hashArray)) {$newHash[strToLower($key)] = &$hashArray[$key];}
return $newHash;}
function &_renderTag($tag, &$attrHash, &$styleHash) {$out = $styleStr = '';if (isSet($styleHash)) {reset($styleHash);while (list($key) = each($styleHash)) {$val = &$styleHash[$key];$styleStr .=  (is_null($val)) ? "{$key}; " : "{$key}:{$val}; ";}     
}
if (isSet($attrHash)) {reset ($attrHash);while (list($key) = each($attrHash)) {$val = &$attrHash[$key];$out .=  (is_null($val)) ? "{$key} " : "{$key}=\"{$val}\" ";}
}
if (strLen($styleStr)>0) {$ret = "<{$tag} {$out} style=\"{$styleStr}\">";} else {$ret = "<{$tag} {$out}>";}
return($ret);}
function _xRay() {$html = "<table>\n";$rowSize = sizeOf($this->cell);for ($row=0; $row < $rowSize; $row++) {$html .= "  <TR>\n";$colSize = sizeOf($this->cell[$row]);for ($col=0; $col < $colSize; $col++) {$html .= '    <TD';$tmp1 = isSet($this->cell[$row][$col]['spaned']) ? $this->cell[$row][$col]['spaned'] : FALSE;$tmp2 = isSet($this->cell[$row][$col]['span_start']) ? $this->cell[$row][$col]['span_start'] : FALSE;if ($tmp1) $html .= ' bgcolor="#10C0CC"';if ($tmp2) $html .= ' bgcolor="#C0C0C0"';$html .= '>';$tmp = $this->cell[$row][$col]['data'];$html .= (isSet($tmp) AND ($tmp!='')) ? $tmp : '&nbsp;';$html .= '</TD>'."\n";}
$html .= '  </TR>'."\n";}
$html .= '</TABLE>'."\n";return $html;} 
} $htmlTable_test = FALSE;if ($htmlTable_test) {$htmlTable_0 =& new Bs_HtmlTable();$htmlTable_1 =& new Bs_HtmlTable();$dataMatrix_0 = array(&$htmlTable_1);$dataMatrix_1 = array(
array('apple', ''      , ''),
array('grape', 'orange', 'pear'),
array('plum' , ''      , ''));$htmlTable_0->initByMatrix($dataMatrix_0);$htmlTable_1->initByMatrix($dataMatrix_1);$htmlTable_1->setTableAttr(array('BORDER'=>'0', 'CELLPADDING'=>'2', 'CELLSPACING'=>'1'));$htmlTable_1->setTrAttr(0, array('ALIGN'=>'right'));$htmlTable_1->setTdAttr(0, 0, array('CLASS'=>'header', 'COLSPAN'=>'3'));$htmlTable_1->setTdStyle(0, 0, array('color'=>'red', 'font'=>'italic 18pt sans-serif'));$htmlTable_1->setTdAttr(1, 0, array('WIDTH'=>'100', 'BGCOLOR'=>'#cccccc'));$htmlTable_1->setTdStyle(1, 0, array('font'=>'bold 9pt monospace'));$htmlTable_1->setTdAttr(1, 1, array('WIDTH'=>'100', 'BGCOLOR'=>'#cccccc'));$htmlTable_1->setTdStyle(1, 1, array('font'=>'18pt monospace'));$htmlTable_1->setTdAttr(1, 2, array('WIDTH'=>'100', 'BGCOLOR'=>'#cccccc', 'ROWSPAN'=>'2'));$htmlTable_1->setTdStyle(1, 2, array('background'=>'red'));$htmlTable_1->setTdAttr(2, 0, array('ALIGN'=>'right', 'BGCOLOR'=>'blue', 'COLSPAN'=>'2'));$htmlTable_1->setTdStyle(2, 0, array('color'=>'white', 'font'=>'bold 18pt serif'));$htmlTable_0->setTableAttr(array('CELLSPACING'=>'0', 'CELLPADDING'=>'0', 'BORDER'=>'8', 'BORDERCOLOR'=>'red'));$htmlTable_0->setTdAttr(0, 0, array('BGCOLOR'=>'green'));echo $htmlTable_0->toHtml();echo $htmlTable_1->_xRay();$htmlTable_1->pushRow(array(1,2,3), 2);$htmlTable_1->pushCol(array(1,2,3,4), 2);$htmlTable_1->setRowStyle(2,array('color'=>'lightblue', 'font'=>'bold 18pt serif'));echo $htmlTable_0->toHtml();}
?>