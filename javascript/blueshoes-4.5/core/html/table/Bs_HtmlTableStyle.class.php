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
require_once($_SERVER["DOCUMENT_ROOT"]       . "../global.conf.php");class Bs_HtmlTableStyle extends Bs_Object {var $_data;var $_style;function Bs_HtmlTableStyle() {parent::Bs_Object();}
function setData($data) {$this->_data = &$data;}
function setStyle($style) {$this->_style = &$style;}
function _setDefaultStyle() {$this->_style = array(
'head'     => '<table>', 
'foot'     => '</table>', 
'empty'    => 'empty', 
'row'      => array(
'head'      => '<tr>', 
'foot'      => '</tr>', 
'cell'      => '<td>__DATA__</td>'
), 
);}
function toHtml() {if ((!isSet($this->_style)) || (!is_array($this->_style))) {$this->_setDefaultStyle();}
if ((!is_array($this->_data)) || (empty($this->_data))) {if (!empty($this->_style['empty'])) return $this->_style['empty'];return '';}
$ret  = '';$ret .= $this->_style['head'];$i       = 1;$numRows = sizeOf($this->_data);reset($this->_data);while (list($k) = each($this->_data)) {$rowSet = FALSE;if ($i == 1) {if (isSet($this->_style['firstrow'])) {if (is_array($this->_style['firstrow'])) {if (isSet($this->_style['firstrow'][0])) {$styleRow = $this->_style['firstrow'][0];} else {$styleRow = $this->_style['firstrow'];}
$rowSet   = TRUE;} elseif (is_string($this->_style['firstrow'])) {$styleRow = $this->_style['firstrow'];$rowSet   = TRUE;}
}
} elseif ($numRows <= ($i)) {if (isSet($this->_style['lastrow'])) {if (is_array($this->_style['lastrow'])) {if (isSet($this->_style['lastrow'][0])) {$styleRow = $this->_style['lastrow'][0];} else {$styleRow = $this->_style['lastrow'];}
$rowSet   = TRUE;} elseif (is_string($this->_style['lastrow'])) {$styleRow = $this->_style['lastrow'];$rowSet   = TRUE;}
}
}
if (!$rowSet) {if (is_array($this->_style['row'])) {if (isSet($this->_style['row'][0])) {$numRowDefs = sizeOf($this->_style['row']);$styleRow   = $this->_style['row'][$i % $numRowDefs];} else {$styleRow   = $this->_style['row'];}
$rowSet   = TRUE;} else {if (isSet($this->_style['row']['cell'])) {$styleRow   = $this->_style['row'];$rowSet   = TRUE;}
}
}
if (!$rowSet) {$styleRow = array(
'head'      => '<tr>', 
'foot'      => '</tr>', 
'cell'      => '<td>__DATA__</td>'
);}
$ret .= $styleRow['head'];$j        = 1;$numCells = sizeOf($this->_data[$k]);while (list($k2) = each($this->_data[$k])) {$cellSet = FALSE;if ($j == 1) {if (isSet($styleRow['firstcell'])) {if (is_array($styleRow['firstcell'])) {if (isSet($styleRow['firstcell'][0])) {$styleCell = $styleRow['firstcell'][0];} else {$styleCell = $styleRow['firstcell'];}
$cellSet   = TRUE;} elseif (is_string($styleRow['firstcell'])) {$styleCell = $styleRow['firstcell'];$cellSet   = TRUE;}
}
} elseif ($numRows <= ($j)) {if (isSet($styleRow['lastcell'])) {if (is_array($styleRow['lastcell'])) {if (isSet($styleRow['lastcell'][0])) {$styleCell = $styleRow['lastcell'][0];} else {$styleCell = $styleRow['lastcell'];}
$cellSet   = TRUE;} elseif (is_string($styleRow['lastcell'])) {$styleCell = $styleRow['lastcell'];$cellSet   = TRUE;}
}
}
if (!$cellSet) {if (is_array($styleRow['cell'])) {if (isSet($styleRow['cell'][0])) {$numCellDefs = sizeOf($styleRow['cell']);$styleCell   = $styleRow['cell'][$i % $numCellDefs];} else {$styleCell   = $styleRow['cell'];}
$cellSet     = TRUE;} else if (is_string($styleRow['cell'])) {$styleCell   = $styleRow['cell'];$cellSet     = TRUE;}
}
if (!$cellSet) {$styleCell = '<td>__DATA__</td>';}
$ret .= str_replace('__DATA__', $this->_data[$k][$k2], $styleCell);$j++;}
$ret .= $styleRow['foot'];$i++;}
$ret .= $this->_style['foot'];return $ret;}
}
?>