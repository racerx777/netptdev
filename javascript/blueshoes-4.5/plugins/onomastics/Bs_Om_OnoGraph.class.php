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
require_once($_SERVER["DOCUMENT_ROOT"]   . "../global.conf.php");require_once($APP['path']['plugins']     . 'onomastics/Bs_Om_OnomasticsServer.class.php');Class Bs_Om_OnoGraph extends Bs_Object {var $_bsDb;var $Bs_Om_OnomasticsServer;var $_allWords;var $_img;var $_imgColors;var $_relationTypes;var $startPosX = 20;var $startPosY = 20;function Bs_Om_OnoGraph() {parent::Bs_Object(); $this->_bsDb =& $GLOBALS['bsDb'];$this->Bs_Om_OnomasticsServer = &$GLOBALS['Bs_Om_OnomasticsServer'];$sql = "SELECT ID, caption from BsOnomastics.RelationType";$this->_relationTypes = $this->_bsDb->getAssoc($sql, TRUE);$colorArray = array('red', 'blue', 'green', 'black', 'lime', 'maroon', 'olive');while (list($k) = each($this->_relationTypes)) {$this->_relationTypes[$k][1] = $colorArray[$k -1];}
reset($this->_relationTypes);}
function createGraph($fileFullpath, $name, $limit, $cols=4) {$this->_makeGraph($name, $limit, $cols);Imagepng($this->_img, $fileFullpath);}
function spitGraph($name, $limit, $cols=4) {$this->_makeGraph($name, $limit, $cols);header('Content-type: image/png');Imagepng($this->_img);Imagedestroy($this->_img);}
function _makeGraph($name, $limit, $cols=4, $additionalNames=NULL) {$pixWidth = (($cols -1) * 200) +60;$this->_startDraw($pixWidth +150);if (is_numeric($name)) {$sql = "select * from BsOnomastics.FirstName where ID={$name}";} else {$sql = "select * from BsOnomastics.FirstName where lcase(caption) = '{$name}'";}
$recData = $this->_bsDb->getAll($sql);if (empty($recData)) {return FALSE;}
while (list(,$record) = each($recData)) {if (!is_numeric($name) && (strToLower($record['caption']) == strToLower($name)) || TRUE) {break; }
}
$relationWordIDs    = array();$relationIDs        = array();$this->_allWords    = array($record['ID']);$this->Bs_Om_OnomasticsServer->findRelationsLimit($record['ID'], $relationWordIDs, $relationIDs, $this->_allWords, $limit);while (list($wordID) = each($this->_allWords)) {$sql = "SELECT * from BsOnomastics.FirstName where ID = {$wordID}";$this->_allWords[$wordID] = $this->_bsDb->getRow($sql);}
if (!empty($additionalNames) && is_array($additionalNames)) {$addRelationIDsContainer = array();foreach ($additionalNames as $addNameID) {$sql = "SELECT * from BsOnomastics.FirstName where ID = {$addNameID}";$this->_allWords[$addNameID] = $this->_bsDb->getRow($sql);$addRelationWordIDs    = array();$addRelationIDs        = array();$addAllWords           = array($addNameID);$this->Bs_Om_OnomasticsServer->findRelationsLimit($addNameID, $addRelationWordIDs, $addRelationIDs, $addAllWords, 1);$addRelationIDsContainer[] = $addRelationIDs;}
foreach ($addRelationIDsContainer as $addRelationIDs) {foreach ($addRelationWordIDs as $addRelationWordArr) {if (isSet($this->_allWords[$addRelationWordArr['wordOneID']]) && isSet($this->_allWords[$addRelationWordArr['wordTwoID']])) {$relationWordIDs[] = $addRelationWordArr;}
}
}
}
function myusort($a, $b) {if ($a["strOrigin"] == $b["strOrigin"]) return 0;return ($a["strOrigin"] > $b["strOrigin"]) ? -1 : 1;}
uasort($this->_allWords, 'myusort');reset($this->_allWords);$posX = $this->startPosX; $posY = $this->startPosY; $t    = TRUE;$t2   = TRUE;while (list($wordID) = each($this->_allWords)) {$this->_drawName($posX, $posY, $this->_allWords[$wordID]);$posX += 200;if ($posX >= $pixWidth) {if ($t2) {$posX = 50;} else {$posX = 20;}
$posY += 100;$t2 = !$t2;}
if ($t) {$posY += 70;} else {$posY -= 70;}
$t = !$t;}
$this->_addRelations($relationWordIDs);return TRUE;}
function _startDraw($pixWidth) {$this->_img = ImageCreate($pixWidth, 2200);$this->_imgColors['white']  = ImageColorAllocate($this->_img, 255, 255, 255);$this->_imgColors['black']  = ImageColorAllocate($this->_img, 0, 0, 0);$this->_imgColors['red']    = ImageColorAllocate($this->_img, 255, 0, 0);$this->_imgColors['green']  = ImageColorAllocate($this->_img, 0, 255, 0);$this->_imgColors['blue']   = ImageColorAllocate($this->_img, 0, 0, 255);}
function _endDraw() {header("Content-type: image/png");Imagepng($this->_img);Imagedestroy($this->_img);}
function _drawName($posX, $posY, &$nameData) {Imagestring($this->_img, 3, $posX, $posY, $nameData['caption'] . ' (' . $nameData['ID'] . ')', $this->_imgColors['black']);Imagestring($this->_img, 3, $posX, $posY +12, 'origin: ' . $nameData['strOrigin'], $this->_imgColors['black']);Imagestring($this->_img, 3, $posX, $posY +24, 'sex: ' . $nameData['strSex'], $this->_imgColors['black']);$nameData['posX'] = $posX;$nameData['posY'] = $posY;}
function _addRelations($relationData) {reset($relationData);while (list($k) = each($relationData)) {$xOne = $this->_allWords[$relationData[$k]['wordOneID']]['posX'];$yOne = $this->_allWords[$relationData[$k]['wordOneID']]['posY'];$xTwo = $this->_allWords[$relationData[$k]['wordTwoID']]['posX'];$yTwo = $this->_allWords[$relationData[$k]['wordTwoID']]['posY'];switch ($relationData[$k]['relationTypeID']) {case 1: $color = 'blue';break;case 2: $color = 'green';$xOne += 5; $yOne += 5; $xTwo += 5; $yTwo += 5;break;case 3: $color = 'red';$xOne += 10; $yOne += 10; $xTwo += 10; $yTwo += 10;break;case 4: $color = 'black';$xOne += 15; $yOne += 15; $xTwo += 15; $yTwo += 15;break;case 5: $color = 'black';$xOne += 20; $yOne += 20; $xTwo += 20; $yTwo += 20;break;default:
$xOne += 25; $yOne += 25; $xTwo += 25; $yTwo += 25;$color = 'black';}
$this->_drawRelation($xOne, $yOne, $xTwo, $yTwo, $color);}
}
function _drawRelation($xOne, $yOne, $xTwo, $yTwo, $color) {ImageLine($this->_img, $xOne, $yOne, $xTwo, $yTwo, $this->_imgColors[$color]);}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_Om_OnoGraph.class.php') {if (isSet($_GET['name'])) {$onoGraph =& new Bs_Om_OnoGraph();$limit = 2;if (isSet($_GET['limit'])) $limit = (int)$_GET['limit'];$onoGraph->spitGraph($_GET['name'], $limit, 4);}
}
?>