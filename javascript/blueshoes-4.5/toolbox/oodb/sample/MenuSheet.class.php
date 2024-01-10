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
require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlTable.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlTableWindrose.class.php');class MenuSheet extends Bs_Object {var $_ooDbProperty = array ( 
'caption' => array('mode'=>'lonely', 'metaType'=>'string', 'index'=>TRUE ),
'menuType' => array('mode'=>'lonely', 'metaType'=>'string', 'index'=>FALSE ),
'dateBegin' => array('mode'=>'lonely', 'metaType'=>'integer', 'index'=>TRUE ),
'dateEnd' => array('mode'=>'lonely', 'metaType'=>'string', 'index'=>TRUE ),
'soups' => array('mode'=>'object',  'useScope'=>'' , 'readOnly'=>TRUE  ),
'entrees' => array('mode'=>'object',  'useScope'=>''   ),
'desserts' => array('mode'=>'object',  'useScope'=>''   ),
'softDrinks' => array('mode'=>'object',  'useScope'=>''   ),
'hardDrinks' => array('mode'=>'object',  'useScope'=>''   ),
'mainMenu' => array('mode'=>'object',  'useScope'=>''   )
);var $caption = 'Midday Food and Drinks';var $menuType = '12oClockFood';var $dateBegin = '20010701';var $dateEnd = '20010901';var $soups  = array();var $entrees = array();var $desserts = array();var $softDrinks = array();var $hardDrinks = array();var $mainMenu = array();var $priceList;function MenuSheet() {parent::Bs_Object();$this->_init();}
function _init() {$this->self      = $_SERVER['PHP_SELF'];$this->htmlUtil  =& $GLOBALS['Bs_HtmlUtil'];$this->windrose  =& new Bs_HtmlTableWindrose();$this->windrose->read('./overview.style');$this->htmlTbl   =& new Bs_HtmlTable();$this->htmlTbl->setWindroseStyle(&$this->windrose);}
function htmlEdit($self, &$qIn, $qOut, &$ooDb) {$owner = get_class($this);$toDo = empty($qIn[$owner]['do']) ? 'edit' : $qIn[$owner]['do'];$id = $ooDb->getID($this);$htmlOut = '';$subTbl = $this->htmlTbl; $subTbl->clear();do { $tryBlock = 1;if ($toDo=='edit') {$inData = array('caption'=>$this->caption, 'menuType'=>$this->menuType, 'dateBegin'=>$this->dateBegin, 'dateEnd'=>$this->dateEnd);$tblRow[] = array('Create- / Edit-menu');$tblRow[] = array('Menu Name', "<input type='text' name='qIn[{$owner}][data][caption]' value='".$inData['caption']."'>");$menuTypeSelect = "<select name='qIn[{$owner}][data][menuType]'>";$menuTypeSelect .= "<option value=''></option>";$menuTypeSelect .= "<option value='breakfast'" .($inData['menuType']=='breakfast' ? 'SELECTED' : ''). ">breakfast</option>";$menuTypeSelect .= "<option value='lunch'"     .($inData['menuType']=='lunch'     ? 'SELECTED' : ''). ">lunch</option>";$menuTypeSelect .= "<option value='dinner'"    .($inData['menuType']=='dinner'    ? 'SELECTED' : ''). ">dinner</option>";$menuTypeSelect .= "</select>";$tblRow[] = array('Menu type', $menuTypeSelect);$tblRow[] = array('Begin Date', "<input type='text' name='qIn[{$owner}][data][dateBegin]' value='".$inData['dateBegin']."'>");$tblRow[] = array('End Date', "<input type='text' name='qIn[{$owner}][data][dateEnd]' value='".$inData['dateEnd']."'>");$subTbl->initByMatrix($tblRow);$subTbl->spanCol(0);$mainTbl[] = array('Menu Data', $subTbl);$flipflop = new FlipFlop();$theHtmlHead = $theHtmlPostForm = '';$allItems = &$ooDb->oQuery('MenuItem', "SELECT ID FROM MenuItem WHERE itemType='soup'");$flipflop->prepare($ooDb, $allItems, $this->soups);list($htmlHead, $htmlOut, $htmlPostForm) = $flipflop->getFlipFlop($formName='MyForm', $id='soup', $resultName="qIn[{$owner}][data][soups]");$theHtmlHead .= $htmlHead;$theHtmlPostForm .= $htmlPostForm;$mainTbl[] = array('Soups', $htmlOut);$allItems = &$ooDb->oQuery('MenuItem', "SELECT ID FROM MenuItem WHERE itemType='entree'");$flipflop->prepare($ooDb, $allItems, $this->entrees);list($htmlHead, $htmlOut, $htmlPostForm) = $flipflop->getFlipFlop($formName='MyForm', $id='entree', $resultName="qIn[{$owner}][data][entrees]");$theHtmlHead .= $htmlHead;$theHtmlPostForm .= $htmlPostForm;$mainTbl[] = array('Entrees', $htmlOut);$this->htmlTbl->initByMatrix($mainTbl);$htmlTbl = $this->htmlTbl->renderTable();$qOutHt = $this->qOut2Hidden($qOut);$htmlBody = <<<EOD
          <form name='MyForm'  action='{$self}' method='post'>
            {$qOutHt}
            <input type='hidden' name='qIn[{$owner}][data][id]' value={$id}>
            {$htmlTbl}
            <br>
            <input type='submit' name='qIn[{$owner}][do]' value='save'>
          </form>
          {$theHtmlPostForm}
EOD;
$htmlOut = array('head'=>&$theHtmlHead, 'body'=>&$htmlBody);break $tryBlock;}
if ($toDo=='save') {$inData = &$qIn[$owner]['data'];$this->caption = $inData['caption'];$this->menuType = $inData['menuType'];$this->dateBegin = $inData['dateBegin'];$this->dateEnd = $inData['dateEnd'];if (empty($inData['soups'])) {$this->soups = array();} else {$this->soups = $ooDb->oQuery('MenuItem', "SELECT ID FROM MenuItem WHERE ID IN ({$inData['soups']})");}
if (empty($inData['entrees'])) {$this->entrees = array();} else {$this->entrees = $ooDb->oQuery('MenuItem', "SELECT ID FROM MenuItem WHERE ID IN ({$inData['entrees']})");}
$status = $ooDb->persist($this);if ($status === FALSE) {$htmlOut = $ooDb->errorDump();} else {$htmlOut = "Persisted with ID:{$status}";}
}
if ($toDo=='show') {if (sizeOf($this->soups)) {$tblRow = array();reset($this->soups);while(list($soupName) = each($this->soups)) {$tblRow[] = array($soupName, $this->priceList[$soupName]);}
$subTbl->clear();$subTbl->initByMatrix($tblRow);$mainTbl[] = array('soup', $subTbl);}
}
} while(FALSE); return $htmlOut;}
function &qOut2Hidden(&$qOut) {$hiddenList = array();reset($qOut);while(list($name) = each($qOut)) {$hiddenList[] = "<input type='hidden' name='{$name}' value={$qOut[$name]}>\n";}
return join('', $hiddenList);}
}
?>