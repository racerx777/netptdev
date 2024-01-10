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
define('BS_HTMLTABLEPARSER_VERSION',      '4.5.$Revision: 1.2 $');require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");require_once($APP['path']['core'] . 'util/Bs_StopWatch.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');class Bs_HtmlTableParser extends Bs_Object {var $_tblNr = -1;var $_theTable = array(); function Bs_HtmlTableParser () {parent::Bs_Object();$this->_Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil']; }
function parse(&$data) {$this->_xmlizeReset();$this->_parser = null;$this->_parser = xml_parser_create();xml_set_object($this->_parser, &$this);xml_set_element_handler($this->_parser, '_tag_open', '_tag_close');xml_set_character_data_handler($this->_parser, '_cdata');xml_set_default_handler ($this->_parser, '_xmlDefaultHandler');$success = xml_parse($this->_parser, $data);if (!$success) {if( (xml_get_current_line_number($this->_parser)) == 1) {$errstr = "XML error at line 1, check URL";} else {$errstr = sprintf("XML error: %s at line %d",
xml_error_string(xml_get_error_code($this->_parser)),
xml_get_current_line_number($this->_parser));}
error_log($errstr);}
xml_parser_free($this->_parser);return $this->_theTable;}
function _xmlizeReset() {unSet($this->_theTable);$this->_tblNr = 0;$this->_theTable = array();$this->_firstTblTagFound = TRUE;$currentTbl = &$this->_theTable[$this->_tblNr];$currentTbl['row_counter'] = 0;$currentTbl['col_counter'] = 0;$currentTbl['data_expected'] = FALSE;}
function _xmlDefaultHandler($parser, $data) {$data = trim($data);}
function _tag_open($parser, $tag, $attributes) {$currentTbl = &$this->_theTable[$this->_tblNr];$row = &$currentTbl['row_counter'];$col = &$currentTbl['col_counter'];switch ($tag) {case 'TABLE':
if ($this->_firstTblTagFound) { $this->_firstTblTagFound = FALSE;} else {$this->_tblNr++; $currentTbl[$row][$col]['sub_table'] = $this->_tblNr;}
$currentTbl = &$this->_theTable[$this->_tblNr];$currentTbl['row_counter'] = 0;$currentTbl['col_counter'] = 0;$currentTbl['data_expected'] = FALSE;if (sizeOf($attributes) > 0) $currentTbl['attr'] = $attributes;break;case 'TR':
if (sizeOf($attributes) > 0) $currentTbl[$row]['attr'] = $attributes;$currentTbl['col_counter'] = 0;break;case 'TD':
$currentTbl['data_expected'] = TRUE;while (isSet($currentTbl[$row][$col]['spaned']) AND $currentTbl[$row][$col]['spaned']) {$currentTbl[$row][$col]['data'] = '-spaned-';$col++;}
$rowSpan = (isSet($attributes['ROWSPAN']) AND ($attributes['ROWSPAN']>0)) ? $attributes['ROWSPAN'] : 1;$colSpan = (isSet($attributes['COLSPAN']) AND ($attributes['COLSPAN']>0)) ? $attributes['COLSPAN'] : 1;if (sizeOf($attributes) > 0) {$filteredAttr = array();reset($attributes);while (list($key, $val) = each($attributes)) {if (($key==='ROWSPAN') OR ($key==='COLSPAN')) continue;if ($key==='STYLE') {$currentTbl[$row][$col]['style'] = $this->_Bs_HtmlUtil->parseStyleStr($attributes['STYLE']);continue;}
$filteredAttr[$key] = $val;}
$currentTbl[$row][$col]['attr'] = $filteredAttr;}
if (($rowSpan>1) OR ($colSpan>1)) {$rowSpan--; $colSpan--;for ($r_sp=0; $r_sp<=$rowSpan; $r_sp++) {for ($c_sp=0; $c_sp<=$colSpan; $c_sp++) {if (($r_sp + $c_sp)==0) { $currentTbl[$row+$r_sp][$col+$c_sp]['span_start'] = array('rowspan'=>$rowSpan+1, 'colspan'=>$colSpan+1);continue;} else {$currentTbl[$row+$r_sp][$col+$c_sp]['spaned'] = TRUE;}
}
}
}
break;default: 
if ($currentTbl['data_expected']) {$data = &$currentTbl[$row][$col]['data'];if (!isSet($data)) $data = '';$data .= '<'.$tag. ' ' . $this->_hash2AttrString($attributes) .'>';}
}
} function _cdata($parser, $cdata) {$currentTbl = &$this->_theTable[$this->_tblNr];if (isSet($currentTbl['data_expected']) AND (!$currentTbl['data_expected'])) return;if (!isSet($cdata)) $cdata=='';$currentTbl = &$this->_theTable[$this->_tblNr];$row = $currentTbl['row_counter'];$col = $currentTbl['col_counter'];if (!isSet($currentTbl[$row][$col]['data'])) $currentTbl[$row][$col]['data'] = '';$currentTbl[$row][$col]['data'] .= trim($cdata);}
function _tag_close($parser,$tag) {$currentTbl = &$this->_theTable[$this->_tblNr];$row = $currentTbl['row_counter'];$col = $currentTbl['col_counter'];switch ($tag) {case 'TABLE':
$this->_tblNr--;break;case 'TR':
$currentTbl['row_counter']++;break;case 'TD':
$currentTbl['data_expected'] = FALSE;$currentTbl['col_counter']++;;break;default:
if ($currentTbl['data_expected']) {$currentTbl[$row][$col]['data'] .= '</'.$tag.'>';}
}
}
function _getTblSize(&$aHash) {$rowSize = $colSize = $tmpColSize = 0;reset($aHash);while (list($rowNum, $columne) = each($aHash)) {if (is_int($rowNum)) $rowSize++;if (!is_array($columne)) continue;$tmpColSize = 0;reset($columne);while (list($colNum) = each($columne)) {if (is_int($colNum)) $tmpColSize++;}
$colSize = max($colSize, $tmpColSize);}
return array($rowSize, $colSize);}
function _hash2AttrString($aHash) {if ((sizeOf($aHash)<=0) OR (!is_array($aHash))) return '';$ret = '';$start = TRUE;reset($aHash);while (list($name, $val) = each($aHash)) {$ret .= ($start) ? '' : ' ';$start = FALSE;$ret .= "{$name}=\"{$val}\"";}
return $ret;}
function _hash2PhpCode($aHash, $indent=0) {if ((sizeOf($aHash)<=0) OR (!is_array($aHash))) return '';$ret = 'array(';$indentStr = str_pad('', $indent + strLen($ret));$start = TRUE;reset($aHash);while (list($name, $val) = each($aHash)) {$ret .= ($start) ? '' : ", \n" . $indentStr;$start = FALSE;$ret .= "'$name'=>'$val'";}
$ret .= ')';return $ret;}
function generateCode() {$tblSize = sizeOf($this->_theTable);$phpCode = '';$phpCode .= '// Make a Table Objects'."\n";for ($i=0; $i<$tblSize; $i++) {$tblName  = '$htmlTable_' . $i;$phpCode .= $tblName . ' = new Bs_HtmlTable();'."\n";}
$phpCode .= "\n";$phpCode .= '// Make a content matrix'."\n";for ($i=$tblSize-1; $i>=0; $i--) {$dataName = '$dataMatrix_'. $i;$currentTbl = &$this->_theTable[$i];list($rowSize, $colSize) = $this->_getTblSize($currentTbl);$tmp = $dataName . ' = array(';$indentStr = str_pad('', strLen($tmp));$phpCode .= $tmp;for ($row=0; $row < $rowSize; $row++) {$phpCode .= ($row==0) ? '' : ",\n" . $indentStr;$phpCode .= ' array(';for ($col=0; $col < $colSize; $col++) {$tmp = &$currentTbl[$row][$col]['sub_table'];if (isSet($tmp) AND ($tmp>0)) {$content = '&$htmlTable_'. $tmp;} else {$tmp = &$currentTbl[$row][$col]['data'];$content = "'" . (isSet($tmp) ? $tmp : '') . "'";}
$phpCode .= ($col==0) ? '' : ", \n       " .$indentStr;$phpCode .= $content;} $phpCode .= ')';} $phpCode .= " );\n";} $phpCode .= "\n";$phpCode .= '// Init tables with data'."\n";for ($i=0; $i<$tblSize; $i++) {$tblName  = '$htmlTable_' . $i;$dataName = '$dataMatrix_'. $i;$phpCode .= "{$tblName}->initByMatrix({$dataName});\n";}
$phpCode .= "\n";$phpCode .= '// Init tables with attributes'."\n";for ($i=0; $i<$tblSize; $i++) {$currentTbl = &$this->_theTable[$i];$tblName  = '$htmlTable_' . $i;$phpCode .= "// - Table {$tblName} : \n";reset ($currentTbl);while (list($row, $rowAttr) = each($currentTbl)) {if ($row === 'attr') {$tmpStr = "{$tblName}->setTableAttr(";$attr = $this->_hash2PhpCode($rowAttr, strLen($tmpStr));if (!empty($attr)) $phpCode .= $tmpStr . $attr .");\n";continue;}
if (!is_int($row)) continue;    if (!is_array($rowAttr)) continue;  reset ($rowAttr);while (list($col, $colAttr) = each($rowAttr)) {if ($col==='attr') {$tmpStr = "{$tblName}->setTrAttr({$row}, ";$attr = $this->_hash2PhpCode($colAttr, strLen($tmpStr));if (!empty($attr)) $phpCode .= $tmpStr . $attr .");\n";continue;}
if (!is_int($col)) continue;    if (!is_array($colAttr)) continue;  reset ($colAttr);while (list($name, $val) = each($colAttr)) {if ($name==='span_start') {if (($val['rowspan'] >1) AND ($val['colspan'] >1)) {$phpCode .= "{$tblName}->spanArea({$row}, {$col}, {$val['rowspan']}, {$val['colspan']});\n";} else if ($val['rowspan'] >1) {$phpCode .= "{$tblName}->spanRow({$row}, {$col}, {$val['rowspan']});\n";} else if ($val['colspan'] >1) {$phpCode .= "{$tblName}->spanCol({$col}, {$row}, {$val['colspan']});\n";}
continue;}
if ($name==='attr') {$tmpStr = "{$tblName}->setTdAttr({$row}, {$col}, ";$attr = $this->_hash2PhpCode($val, strLen($tmpStr));if (!empty($attr)) $phpCode .= $tmpStr . $attr .");\n";continue;}
if ($name==='style') {$tmpStr = "{$tblName}->setTdStyle({$row}, {$col}, ";$attr = $this->_hash2PhpCode($val, strLen($tmpStr));if (!empty($attr)) $phpCode .= $tmpStr . $attr .");\n";continue;}
} } } $phpCode .= "\n";} $phpCode .= "echo \$htmlTable_0->renderTable();\n";return $phpCode;}
}
?>