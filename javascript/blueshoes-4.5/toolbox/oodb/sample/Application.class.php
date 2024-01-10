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
require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");require_once($APP['path']['core'] . 'storage/Bs_ooDbForMySql.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlTable.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlTableWindrose.class.php');require_once('./MenuSheet.class.php');require_once('./MenuItem.class.php');require_once('./FlipFlop.class.php');class Appication extends Bs_Object {var $menuAvailable = array();var $_ooDbProperty = array ( 
);function Appication() {parent::Bs_Object();$this->_init();}
function _init() {$this->self      = $_SERVER['PHP_SELF'];$this->bsDb      =& $GLOBALS['bsDb'];$this->bsDb->selectDb('ooDb');$this->ooDb      =& new Bs_ooDbForMySql($this->bsDb);$this->htmlUtil  = &$GLOBALS['Bs_HtmlUtil'];$this->windrose  =& new Bs_HtmlTableWindrose();$this->windrose->read('./overview.style');$this->htmlTbl   =& new Bs_HtmlTable();$this->htmlTbl->setWindroseStyle(&$this->windrose);}
function handleEvent(&$qIn) {$htmlBody = '';do { $tryBlock=1;if (is_null($qIn)) { $htmlBody = <<< EOD
          <!-- frames -->
          <frameset  cols="20%,*">
              <frame name="nav" src="{$this->self}?qIn[action]=start&qIn[do]=nav" marginwidth="10" marginheight="10" scrolling="auto" frameborder="1">
              <frame name="main" src="{$this->self}?qIn[action]=start&qIn[do]=main" marginwidth="10" marginheight="10" scrolling="auto" frameborder="1">
          </frameset>
EOD;
echo  $htmlBody;exit;}
$tryBlock++;switch ($qIn['action']) {case 'start':
if ($qIn['do']=='nav') {$htmlBody = $this->_action_showNavigation($qIn);} else {$htmlBody .= 'Nothing selected';}
break;case 'editMenuSheet':
$htmlBody = $this->_action_editMenuSheet($qIn);break;case 'editMenu':
$htmlBody = $this->_action_editMenu($qIn);break;case 'editMenuItem':
$htmlBody = $this->_action_editMenuItem($qIn);break;default:
$htmlBody = "INVALID ACTION: '{$qIn['action']}'";}
$tryBlock--;} while(FALSE); $this->_echoHtmlPage($htmlBody);}
function &_action_showNavigation(&$qIn) {$htmlOut = <<<EOD
    <A HREF="{$this->self}?qIn[action]=editMenuSheet" TARGET="main">Edit MenuSheet<A><br>
    <A HREF="{$this->self}?qIn[action]=editMenu" TARGET="main">Edit Menu<A><br>
    <A HREF="{$this->self}?qIn[action]=editMenuItem" TARGET="main">Edit Menu Item<A><br>
EOD;
return $htmlOut;}
function &_action_editMenuSheet(&$qIn) {do {$tryBlock = 1;$toDo = empty($qIn['do']) ? 'overview' : $qIn['do'];if ($toDo=='overview') {$allMenuSheets = $this->ooDb->oQuery('MenuSheet', "Select ID FROM MenuSheet");if ($allMenuSheets===FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;}
$tblRow[] = array('The menu sheets');for ($i=0; $i<sizeOf($allMenuSheets); $i++) {$menuSheet = &$allMenuSheets[$i];$id = $this->ooDb->getID($menuSheet);$tblRow[] = array("<A HREF={$this->self}?qIn[action]=editMenuSheet&qIn[do]=edit&qIn[data][id]={$id}>".$menuSheet->caption.'</A>', $menuSheet->menuType, "<A HREF={$this->self}?qIn[action]=editMenuSheet&qIn[do]=delete&qIn[data][id]={$id}>delete</A>");}
$tblRow[] = array("<A HREF={$this->self}?qIn[action]=editMenuSheet&qIn[do]=edit&qIn[data][id]=0>".'Add NEW'.'</A>', '');$this->htmlTbl->initByMatrix($tblRow);$this->htmlTbl->spanCol(0);$htmlOut = $this->htmlTbl->renderTable();break $tryBlock;}
if ($toDo=='edit') {$menuSheet =& new MenuSheet();$id = $qIn['data']['id'];if ($id>0) {if ($this->ooDb->unpersist(&$menuSheet, $id) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;}
}
$htmlOut = $menuSheet->htmlEdit($this->self, $qIn, array('qIn[action]'=>'editMenuSheet', 'qIn[do]'=>'edit'), $this->ooDb);break $tryBlock;}
if ($toDo=='delete') {$id = $qIn['data']['id'];if ($id>0) {if ($this->ooDb->softDelete('MenuSheet', $id) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;} else {$htmlOut = 'SUCCESS';}
} else {$htmlOut = "Invalid ID:'{$id}'";}
break $tryBlock;}
} while(FALSE); return $htmlOut;}
function &_action_editMenuItem(&$qIn) {do {$tryBlock = 1;$toDo = empty($qIn['do']) ? 'overview' : $qIn['do'];if ($toDo=='overview') {$allMenuItem = $this->ooDb->oQuery('MenuItem', "Select ID FROM MenuItem");if ($allMenuItem===FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;}
$tblRow[] = array('The menu items');for ($i=0; $i<sizeOf($allMenuItem); $i++) {$menuItem = &$allMenuItem[$i];$id = $this->ooDb->getID($menuItem);$tblRow[] = array("<A HREF={$this->self}?qIn[action]=editMenuItem&qIn[do]=edit&qIn[data][id]={$id}>".$menuItem->caption.'</A>', "<A HREF={$this->self}?qIn[action]=editMenuItem&qIn[do]=delete&qIn[data][id]={$id}>delete</A>");}
$tblRow[] = array("<A HREF={$this->self}?qIn[action]=editMenuItem&qIn[do]=edit&qIn[data][id]=0>".'Add NEW'.'</A>', '');$this->htmlTbl->initByMatrix($tblRow);$this->htmlTbl->spanCol(0);$htmlOut = $this->htmlTbl->renderTable();break $tryBlock;}
if ($toDo=='edit') {$menuItem =& new MenuItem();$id = $qIn['data']['id'];if ($id>0) {if ($this->ooDb->unpersist(&$menuItem, $id) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;}
}
$htmlForm = $menuItem->editMenuItemForm($qIn);$htmlOut = <<<EOD
          <form action='{$this->self}' method='post'>
            <input type='hidden' name='qIn[action]' value='editMenuItem'>
            <input type='hidden' name='qIn[data][id]' value={$id}>
            {$htmlForm}
            <br>
            <input type='submit' name='qIn[do]' value='save'>
          </form>
EOD;
break $tryBlock;}
if ($toDo=='save') {$menuItem =& new MenuItem();$id = $qIn['data']['id'];if ($id>0) {if ($this->ooDb->unpersist(&$menuItem, $id) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;}
}
$menuItem->editMenuItemForm($qIn);if ($this->ooDb->persist($menuItem) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;} else {$htmlOut = 'SUCCESS';}
break $tryBlock;}
if ($toDo=='delete') {$id = $qIn['data']['id'];if ($id>0) {if ($this->ooDb->softDelete('MenuItem', $id) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;} else {$htmlOut = 'SUCCESS';}
} else {$htmlOut = "Invalid ID:'{$id}'";}
break $tryBlock;}
} while(FALSE); return $htmlOut;}
function &_action_editMenu(&$qIn) {do {$tryBlock = 1;$toDo = empty($qIn['do']) ? 'overview' : $qIn['do'];if ($toDo=='overview') {$allMenuSheets = $this->ooDb->oQuery('MenuSheet', "Select ID FROM MenuSheet");if ($allMenuSheets===FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;}
$tblRow[] = array('The menu sheets');for ($i=0; $i<sizeOf($allMenuSheets); $i++) {$menuSheet = &$allMenuSheets[$i];$id = $this->ooDb->getID($menuSheet);$tblRow[] = array("<A HREF={$this->self}?qIn[action]=editMenu&qIn[do]=edit&qIn[data][id]={$id}>".$menuSheet->caption.'</A>', $menuSheet->menuType);}
$this->htmlTbl->initByMatrix($tblRow);$this->htmlTbl->spanCol(0);$htmlOut = $this->htmlTbl->renderTable();break $tryBlock;}
if ($toDo=='edit') {$menuSheet =& new MenuSheet();$id = $qIn['data']['id'];if ($id>0) {if ($this->ooDb->unpersist(&$menuSheet, $id) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;}
}
$htmlOut = $menuSheet->htmlEdit($this->self, $qIn);break $tryBlock;}
if ($toDo=='save') {$menuSheet =& new MenuSheet();$id = $qIn['data']['id'];if ($id>0) {if ($this->ooDb->unpersist(&$menuSheet, $id) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;}
}
$menuSheet->editMenuForm($qIn);if ($this->ooDb->persist($menuSheet) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;} else {$htmlOut = 'SUCCESS';}
break $tryBlock;}
if ($toDo=='delete') {$id = $qIn['data']['id'];if ($id>0) {if ($this->ooDb->softDelete('MenuSheet', $id) === FALSE) {$htmlOut = $this->ooDb->errorDump();break $tryBlock;} else {$htmlOut = 'SUCCESS';}
} else {$htmlOut = "Invalid ID:'{$id}'";}
break $tryBlock;}
} while(FALSE); return $htmlOut;}
function _echoHtmlPage(&$htmlBody) {$head = $body = '';if (is_array($htmlBody)) {if (!empty($htmlBody['head']))  $head = &$htmlBody['head'];if (!empty($htmlBody['body']))  $body = &$htmlBody['body'];} else {$body = &$htmlBody;}
$htmlOut = <<< EOD
      <html>
      <head>
          <title>Untitled</title>
        {$head}
      </head>
      <body style="font-family: Verdana, Arial; font-size: 12px;" background="" link="#0000FF" vlink="#000080">
      {$body}
      </body>
      </html>
EOD;
echo $htmlOut;}
}
if (basename($_SERVER['PHP_SELF']) == 'Application.class.php') {$appl =& new Appication();if (!isSet($qIn)) $qIn = NULL;$appl->handleEvent($qIn);}
?>