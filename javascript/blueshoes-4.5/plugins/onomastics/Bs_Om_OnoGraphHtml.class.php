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
require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");require_once($APP['path']['plugins']      . 'onomastics/Bs_Om_OnoGraph.class.php');Class Bs_Om_OnoGraphHtml extends Bs_Om_OnoGraph {var $_output;var $startPosX = 20;var $startPosY = 120;function Bs_Om_OnoGraphHtml() {parent::Bs_Om_OnoGraph(__FILE__); }
function createGraph($name, $limit, $cols=4, $additionalNames=NULL) {$status = $this->_makeGraph($name, $limit, $cols, $additionalNames);if ($status) {$this->_endDraw($name, $limit, $cols);return $this->_output;} else {return "no such name found: {$name}";}
}
function spitGraph($name, $limit, $cols=4) {echo $this->createGraph($name, $limit, $cols);}
function _startDraw($pixWidth) {$this->_output = '
<html xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<style>
v\:* {behavior: url(#default#VML);}
td {font-family : Arial, Helvetica, sans-serif;font-size : 11px;}
span {font-family : Arial, Helvetica, sans-serif;font-size : 11px;}
</style>
<bs:head/>
<script type="text/javascript" src="/_bsJavascript/plugins/jsrs/JsrsCore.lib.js"></script>
<script type="text/javascript" src="/_bsJavascript/plugins/onomastics/OnomasticsClient.class.js"></script>
<script type="text/javascript">
';$this->_output .= 'var relationTypes = new Array();';reset($this->_relationTypes);while (list($k) = each($this->_relationTypes)) {$this->_output .= 'relationTypes[' . $k . '] = Array();';$this->_output .= 'relationTypes[' . $k . '][0] = "' . $this->_relationTypes[$k][0] . '";';$this->_output .= 'relationTypes[' . $k . '][1] = "' . $this->_relationTypes[$k][1] . '";';}
$this->_output .= '
function init() {onoClient = new OnomasticsClient();onoClient.init("onoClient");}
var currentMouseOver;var dragStart;var dragStartPosX;var dragStartPosY;var dragRelationTypeID;function lineDragStart(divObj, relationTypeID) {dragStart          = currentMouseOver;dragStartPosX      = event.clientX;dragStartPosY      = event.clientY;dragRelationTypeID = relationTypeID;}
function lineOnDrop(divObj) {obj = event.srcElement;for (var i=0; i<10; i++) {if (obj.id.substr(0, 4) == "div_") {break;}
obj = obj.parentNode;}
addLine(dragStartPosX, dragStartPosY, event.clientX, event.clientY, dragRelationTypeID);//alert("fromID: " + idFrom + " toID: " + idTo + " relationType: " + dragRelationTypeID);var toID   = obj.id.substr(4);var fromID = dragStart.id.substr(4);onoClient.addRelation(dragRelationTypeID, fromID, toID);}
function nameMouseEnter(divObj) {currentMouseOver = divObj;divObj.style.zIndex = 50;}
function nameMouseLeave(divObj) {currentMouseOver = null;divObj.style.zIndex = 10;}
function addLine(xOne, yOne, xTwo, yTwo, relationTypeID) {var caption = relationTypes[relationTypeID][0];var color   = relationTypes[relationTypeID][1];var line    = \'<v:line from="\' + xOne + \' \' + yOne + \'"      to="\' + xTwo + \' \' + yTwo + \'"      id=null      href=null      target=null      class=null      title="\' + caption + \'"      alt=null      style="position:absolute; visibility:visible; z-index:30;"      opacity="1.0"      chromakey="null"      stroke="true"      strokecolor="\' + color + \'"      strokeweight="1"      fill="true"      fillcolor="blue"      print="true"      coordsize="1000,1000"      coordorigin="0 0"/>\';document.body.insertAdjacentHTML("afterBegin", line);}
var selectedLine;function toggleSelectLine(lineObj) {if (selectedLine) {deselectLine(selectedLine);if (selectedLine == lineObj) {selectedLine = null;return;}
}
selectedLine = lineObj;selectLine(lineObj);}
function selectLine(lineObj) {lineObj.strokeweight = 2;}
function deselectLine(lineObj) {lineObj.strokeweight = 1;}
function keyDownEvent() {//alert(window.event.keyCode);if (window.event.keyCode == 46) {//selectedLine.delete();//alert(selectedLine.alt);//selectedLine.print = "false";selectedLine.style.display = "none";onoClient.deleteRelation(selectedLine.relationTypeID, selectedLine.wordOneID, selectedLine.wordTwoID);//alert(selectedLine.relationTypeID + " " + selectedLine.wordOneID + " " + selectedLine.wordTwoID);}
}
</script>
</head>
<body onLoad="init();" onKeyDown="keyDownEvent();">
<bs:bodyStart/>
';}
function _endDraw() {$this->_output .= '<bs:bodyEnd/>';$this->_output .= '</body></html>';}
function _drawSearchForm() {$ret  = '';$ret .= '<form name="searchForm" onsubmit="return false;">';$ret .= '<input type="text" name="searchFormFirstname" id="searchFormFirstname" value="">';$ret .= '<input type="button" name="searchFormButton" value="search" onclick="onoClient.findFirstname(document.getElementById(\'searchFormFirstname\').value);">';$ret .= '</form>';return $ret;}
function _drawName($posX, $posY, &$nameData) {$this->_output .= '<div id="div_' . $nameData['ID'] . '" style="position:absolute; top:' . $posY . '; left:' . $posX . '; z-index:10;';if ($nameData['sex'] == 1) {$this->_output .= ' background: Aqua; border:thin solid Blue;"';} elseif ($nameData['sex'] == 2) {$this->_output .= ' background-color: #FFC0FF; border:thin solid Blue;"';} else {$this->_output .= ' background-color: yellow; border:thin solid Blue;"';}
$this->_output .= ' onMouseEnter="nameMouseEnter(this);" onMouseLeave="nameMouseLeave(this);" onDragEnter="window.event.returnValue=false;" onDragOver="window.event.returnValue=false;" onDrop="lineOnDrop(this);">';$this->_output .= '<table border="0" cellpadding="2" cellspacing="0">';$this->_output .= '<tr><td>';reset($this->_relationTypes);while (list($k) = each($this->_relationTypes)) {$this->_output .= '  <img src="" alt="' . $this->_relationTypes[$k][0] . '" width="10" height="10" ondragstart="lineDragStart(this, ' . $k . ');" style="cursor:hand; border:thin solid ' . $this->_relationTypes[$k][1] . ';" border="0">';}
$this->_output .= '</td></tr>';$this->_output .= '<tr><td><a href="' . $_SERVER['PHP_SELF'] . '?mainFirstName=' . $nameData['ID'] . '">' . $nameData['caption'] . '</a> (' . $nameData['ID'] . ')' . '</td></tr>';$this->_output .= '<tr><td>' . 'origin: ' . $nameData['strOrigin'] . '</td></tr>';$this->_output .= '<tr><td>' . 'sex: ' . $nameData['strSex'] . '</td></tr>';$this->_output .= '</table>';$this->_output .= '</div>';$nameData['posX'] = $posX;$nameData['posY'] = $posY;}
function _addRelations($relationData) {reset($relationData);while (list($k) = each($relationData)) {$xOne = $this->_allWords[$relationData[$k]['wordOneID']]['posX'];$yOne = $this->_allWords[$relationData[$k]['wordOneID']]['posY'];$xTwo = $this->_allWords[$relationData[$k]['wordTwoID']]['posX'];$yTwo = $this->_allWords[$relationData[$k]['wordTwoID']]['posY'];switch ($relationData[$k]['relationTypeID']) {case 1: break;case 2: $xOne += 5; $yOne += 5; $xTwo += 5; $yTwo += 5;break;case 3: $xOne += 10; $yOne += 10; $xTwo += 10; $yTwo += 10;break;case 4: $xOne += 15; $yOne += 15; $xTwo += 15; $yTwo += 15;break;case 5: $xOne += 20; $yOne += 20; $xTwo += 20; $yTwo += 20;break;default:
$xOne += 25; $yOne += 25; $xTwo += 25; $yTwo += 25;}
$this->_drawRelation($xOne, $yOne, $xTwo, $yTwo, $relationData[$k]['relationTypeID'], $relationData[$k]);}
}
function _drawRelation($xOne, $yOne, $xTwo, $yTwo, $relationType, &$relationData) {$caption = $this->_relationTypes[$relationType][0];$color   = $this->_relationTypes[$relationType][1];$caption .= ' ' . $this->_allWords[$relationData['wordOneID']]['caption'];$caption .= ' => ' . $this->_allWords[$relationData['wordTwoID']]['caption'];$this->_output .= '
<v:line
from="' . $xOne . ' ' . $yOne . '"
to="'   . $xTwo . ' ' . $yTwo . '"
relationTypeID="' . $relationType . '"
wordOneID="'      . $relationData['wordOneID'] . '"
wordTwoID="'      . $relationData['wordTwoID'] . '"
id=null
target=null
class=null
title="' . $caption . '"
alt="'   . $caption . '"
style="position:absolute; visibility:visible; z-index:30;"
opacity="1.0"
chromakey="null"
stroke="true"
strokecolor="' . $color . '"
strokeweight="1"
fill="true"
fillcolor="blue"
print="true"
coordsize="1000,1000"
coordorigin="0 0"
onclick="toggleSelectLine(this);"
/>
';}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_Om_OnoGraphHtml.class.php') {if (isSet($_GET['mainFirstName'])) {set_time_limit(30);$onoGraph =& new Bs_Om_OnoGraphHtml();$limit = 2;if (isSet($_GET['limit'])) $limit = (int)$_GET['limit'];$onoGraph->spitGraph($_GET['mainFirstName'], $limit, 4);}
}
?>