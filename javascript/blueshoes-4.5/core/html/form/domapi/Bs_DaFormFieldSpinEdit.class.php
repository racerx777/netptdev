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
define('BS_DAFORMFIELDSPINEDIT_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldTxt.class.php');class Bs_DaFormFieldSpinEdit extends Bs_FormFieldTxt {var $domApiDoRollover = TRUE;var $domApiEnabled    = TRUE;var $domApiDoWarning = FALSE;var $domApiStep = 1;function Bs_DaFormFieldSpinEdit() {$this->Bs_FormFieldTxt(); $this->fieldType = 'datepicker';$this->caption       = array(
'en'=>'Number', 
'de'=>'Zahl', 
'fr'=>'Numéro', 
);$this->size          = 4;$this->bsDataType    = 'number';$this->trim          = 'both';$this->persisterVarSettings['domApiDoRollover'] = array('mode'=>'stream');$this->persisterVarSettings['domApiEnabled']    = array('mode'=>'stream');$this->persisterVarSettings['domApiDoWarning']  = array('mode'=>'stream');$this->persisterVarSettings['domApiStep']       = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {switch ($this->getVisibility()) {case 'omit':
case 'invisible':
case 'read':
case 'readonly':
case 'show':
return parent::getField($explodeKey, $addEnforceCheckbox);}
$fieldName = $this->_getFieldNameForHtml($this->name);$hiddenFieldName = $fieldName;$spanId          = $fieldName . 'SpanId';$elementName     = $fieldName . 'Elm';$formName        = $this->_form->name;$onLoadCode = "
{$elementName}=Spinedit(core.getElm('{$spanId}'),null);{$elementName}.setEnabled("    . boolToString($this->domApiEnabled)    . ");{$elementName}.setDoRollover(" . boolToString($this->domApiDoRollover) . ");{$elementName}.doWarning = "   . boolToString($this->domApiDoWarning) . ";{$elementName}.step = "        . $this->domApiStep . ";";$minMax = $this->_getBsDataInfoNumber();if (is_numeric($minMax[0])) {$onLoadCode .= "{$elementName}.min = {$minMax[0]};";}
if (is_numeric($minMax[1])) {$onLoadCode .= "{$elementName}.max = {$minMax[1]};";}
$value = $this->_getTagStringValue($explodeKey);if (is_numeric($value)) {$onLoadCode .= "{$elementName}.setValue({$value});";}
$onLoadCode .= "
f=document.forms['{$formName}'];{$elementName}.attachToForm(f,'{$hiddenFieldName}');";$this->_form->addOnLoadCode($onLoadCode);$this->_form->addIncludeOnce('/_libDomapi/core_c.js');$this->_form->addIncludeOnce('/_libDomapi/gui/spinedit_c.js');$ret  = $this->getFieldAsHidden($explodeKey);$ret .= '<span onclick="alert(document.' . $formName . '.' . $hiddenFieldName . '.value);">x</span><span id="' . $spanId . '"></span>';return $ret;}
}
?>