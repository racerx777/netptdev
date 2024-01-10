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
define('BS_DAFORMFIELDCOMBOBOX_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldSelect.class.php');class Bs_DaFormFieldComboBox extends Bs_FormFieldSelect {var $domApiDoRollover = TRUE;var $domApiEnabled    = TRUE;var $domApiShowLines = TRUE;var $domApiDoDepress = TRUE;var $domApiAllowEdit = TRUE;var $domApiAlign     = 'down';var $domApiAutoClose = TRUE;function Bs_DaFormFieldComboBox() {$this->Bs_FormFieldTxt(); $this->fieldType = 'combobox';$this->persisterVarSettings['domApiDoRollover'] = array('mode'=>'stream');$this->persisterVarSettings['domApiEnabled']    = array('mode'=>'stream');$this->persisterVarSettings['domApiShowLines']  = array('mode'=>'stream');$this->persisterVarSettings['domApiDoDepress']  = array('mode'=>'stream');$this->persisterVarSettings['domApiAllowEdit']  = array('mode'=>'stream');$this->persisterVarSettings['domApiAlign']      = array('mode'=>'stream');$this->persisterVarSettings['domApiAutoClose']  = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($addEnforceCheckbox=TRUE) {switch ($this->getVisibility()) {case 'omit':
case 'invisible':
case 'read':
case 'readonly':
case 'show':
return parent::getField($explodeKey, $addEnforceCheckbox);}
$fieldName = $this->_getFieldNameForHtml($this->name);$hiddenFieldName = $fieldName;$spanId          = $fieldName . 'SpanId';$elementName     = $fieldName . 'Elm';$formName        = $this->_form->name;$valuesList      = join(',', $this->_prepareOptionsData()); $onLoadCode = "
{$elementName}=Combobox(core.getElm('{$spanId}'),null);{$elementName}.setEnabled("    . boolToString($this->domApiEnabled)    . ");{$elementName}.setDoRollover(" . boolToString($this->domApiDoRollover) . ");{$elementName}.dropDown.doDepress = "  . boolToString($this->domApiDoDepress)  . ";{$elementName}.setAllowEdit("  . boolToString($this->domApiAllowEdit)  . ");{$elementName}.direction = '{$this->domApiAlign}';{$elementName}.autoClose = "  . boolToString($this->domApiAutoClose)  . ";{$elementName}.accessKey = 'g';";if (!$this->domApiShowLines) {$onLoadCode .= "
{$elementName}.dropDown.hLines = "  . boolToString($this->domApiShowLines)  . ";//{$elementName}.reDraw(); //don't think we need that here cause we've only just begun.
";}
$onLoadCode .= "{$elementName}.dropDown.delimitedText('{$valuesList}');";$valueDefault = $this->_getTagStringValue($explodeKey);if (!empty($valueDefault)) {$onLoadCode .= "
{$elementName}.value = \"{$valueDefault}\";{$elementName}.edit.value = \"{$valueDefault}\";";}
$onLoadCode .= "
f=document.forms['{$formName}'];{$elementName}.attachToForm(f,'{$hiddenFieldName}');";$this->_form->addOnLoadCode($onLoadCode);$this->_form->addIncludeOnce('/_libDomapi/core_c.js');$ret  = '<span id="' . $spanId . '"></span>';$ret .= '<script language="JavaScript"><!-- core.loadUnit("combobox"); //--></script>';return $ret;}
}
?>