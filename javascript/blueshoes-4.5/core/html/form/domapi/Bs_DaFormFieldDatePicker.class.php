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
define('BS_DAFORMFIELDDATEPICKER_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldTxt.class.php');class Bs_DaFormFieldDatePicker extends Bs_FormFieldTxt {var $domApiDoRollover = TRUE;var $domApiEnabled    = TRUE;var $domApiAlign     = 'down';var $domApiAutoClose  = TRUE;var $domApiDateFormat = 'dd.mm.yyyy';function Bs_DaFormFieldDatePicker() {$this->Bs_FormFieldTxt(); $this->fieldType = 'datepicker';$this->caption       = array(
'en'=>'Date', 
'de'=>'Datum', 
'fr'=>'Date', 
'it'=>'Data', 
);$this->size          = 10;$this->minLength     = 10;$this->maxLength     = 10;$this->bsDataType    = 'date';$this->bsDataInfo    = 4;$this->trim          = 'both';$this->persisterVarSettings['domApiDoRollover'] = array('mode'=>'stream');$this->persisterVarSettings['domApiEnabled']    = array('mode'=>'stream');$this->persisterVarSettings['domApiShowLines']  = array('mode'=>'stream');$this->persisterVarSettings['domApiDoDepress']  = array('mode'=>'stream');$this->persisterVarSettings['domApiAllowEdit']  = array('mode'=>'stream');$this->persisterVarSettings['domApiAlign']      = array('mode'=>'stream');$this->persisterVarSettings['domApiAutoClose']  = array('mode'=>'stream');$this->persisterVarSettings['domApiDateFormat'] = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {switch ($this->getVisibility()) {case 'omit':
case 'invisible':
case 'read':
case 'readonly':
case 'show':
return parent::getField($explodeKey, $addEnforceCheckbox);}
$fieldName = $this->_getFieldNameForHtml($this->name);$hiddenFieldName = $fieldName;$spanId          = $fieldName . 'SpanId';$elementName     = $fieldName . 'Elm';$formName        = $this->_form->name;$onLoadCode = "
//core.loadUnit('datepicker'); not workie
{$elementName}=Datepicker(core.getElm('{$spanId}'),null);{$elementName}.setEnabled("    . boolToString($this->domApiEnabled)    . ");{$elementName}.setDoRollover(" . boolToString($this->domApiDoRollover) . ");{$elementName}.direction = '{$this->domApiAlign}';{$elementName}.autoClose = "  . boolToString($this->domApiAutoClose)  . ";{$elementName}.setDateFormat('{$this->domApiDateFormat}');f=document.forms['{$formName}'];{$elementName}.attachToForm(f,'{$hiddenFieldName}');";$this->_form->addOnLoadCode($onLoadCode);$this->_form->addIncludeOnce('/_libDomapi/core_c.js');$this->_form->addIncludeOnce('/_libDomapi/gui/datepicker_c.js');$ret  = $this->getFieldAsHidden($explodeKey);$ret .= '<span id="' . $spanId . '"></span>';return $ret;}
}
?>