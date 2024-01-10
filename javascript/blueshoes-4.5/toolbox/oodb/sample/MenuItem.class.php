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
class MenuItem {var $_ooDbProperty = array ( 
'caption' => array('mode'=>'lonely', 'metaType'=>'string', 'index'=>TRUE ),
'itemType' => array('mode'=>'lonely', 'metaType'=>'string', 'index'=>FALSE ),
'typeOptions' => array('mode'=>'stream', 'streamName'=>'' )
);var $caption = '';var $itemType = '';var $typeOptions = array();function MenuItem() {$this->_init();}
function _init() {$this->self      = $_SERVER['PHP_SELF'];$this->htmlUtil  =& $GLOBALS['Bs_HtmlUtil'];$this->windrose  =& new Bs_HtmlTableWindrose();$this->windrose->read('./overview.style');$this->htmlTbl   =& new Bs_HtmlTable();$this->htmlTbl->setWindroseStyle(&$this->windrose);}
function editMenuItemForm(&$oodbIn) {$toDo = &$oodbIn['do'];$formBody = '';do { $tryBlock = 1;if ($toDo=='edit') {$inData = array('caption'=>$this->caption);$tblRow[] = array('Create- / Edit-Menuitem');$tblRow[] = array('Menu Item Name', "<input type='text' name='oodbIn[menuItem][form][caption]' value='".$inData['caption']."'>");$this->htmlTbl->initByMatrix($tblRow);$this->htmlTbl->spanCol(0);$formBody = $this->htmlTbl->renderTable();break $tryBlock;}
if ($toDo=='save') {$inData = &$oodbIn['menuItem']['form'];$this->caption = $inData['caption'];}
} while(FALSE); return $formBody;}
}
?>