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
define('BS_FORMFIELDEDITOR_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldEditor extends Bs_FormField {var $width = 446;var $height = 400;var $dataType = NULL;function Bs_FormFieldEditor() {$this->Bs_FormField(); $this->fieldType  = 'textarea'; $this->bsDataType = 'html';$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {$ret = '';$value     = $this->getValue($explodeKey); switch ($this->getVisibility()) {case 'omit':
return $value;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
case 'readonly':
$this->_markAsUsed();$ret .= $this->getFieldAsHidden();case 'show':
$ret .= '<div style="width:' . $this->width . '; height:' . $this->height . '; border:1px solid black; overflow:scroll;">';$ret .= $value;$ret .= '</div>';return $ret;break;default: $this->_markAsUsed();}
$valueForHtml = $this->_Bs_HtmlUtil->filterForHtml($value);$fieldName = $this->_getFieldNameForHtml($this->name);$jsVarName = 'bsEdit' . $fieldName;if (!is_null($explodeKey)) {$fieldName .= '[' . $explodeKey . ']';$jsVarName .= '_' . $explodeKey;}
$this->_form->addIncludeOnce('/_bsJavascript/core/lang/Bs_Misc.lib.js');$this->_form->addIncludeOnce('/_bsJavascript/core/html/Bs_HtmlUtil.lib.js');$this->_form->addIncludeOnce('/_bsJavascript/components/tabset/Bs_TabSet.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/tabset/default.css');$this->_form->addIncludeOnce('/_bsJavascript/components/editor/Bs_Editor.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/editor/lang/en.js');$this->_form->addIncludeOnce('/_bsJavascript/core/form/Bs_FormFieldSelect.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/toolbar/Bs_ButtonBar.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/toolbar/Bs_Button.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/resizegrip/Bs_ResizeGrip.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/toolbar/win2k_ie.css');$aolc  = '';$aolc .= "{$jsVarName} = new Bs_Editor('{$jsVarName}');";if (isSet($this->dataType)) {$aolc .= "{$jsVarName}.dataType = '{$this->dataType}';";}
if (isSet($this->editareaOnPaste)) {$aolc .= "{$jsVarName}.editareaOnPaste = {$this->editareaOnPaste};\n";}
if (isSet($this->mayResize)) {$aolc .= "{$jsVarName}.mayResize = " . boolToString($this->editareaOnPaste) . ";\n";}
if (isSet($this->buttonsWysiwyg)) {$aolc .= "{$jsVarName}.loadButtonsWysiwyg();\n";foreach ($this->buttonsWysiwyg as $btnKey => $btnValue) {if ($btnValue === FALSE) {$aolc .= "{$jsVarName}.buttonsWysiwyg['{$btnKey}'] = null;\n";}
}
}
if (isSet($this->buttonsHtml)) {$aolc .= "{$jsVarName}.loadButtonsHtml();\n";foreach ($this->buttonsHtml as $btnKey => $btnValue) {if ($btnValue === FALSE) {$aolc .= "{$jsVarName}.buttonsHtml['{$btnKey}'] = null;\n";}
}
}
$aolc .= "{$jsVarName}.convertField('{$fieldName}');";$this->_form->addOnLoadCode($aolc);$ret .= "<textarea name='{$fieldName}' id='{$fieldName}' cols='40' rows='10'";$ret .= " style='width:" . $this->width . "; height:" . $this->height . ";'";$ret .= ">";$ret .= $valueForHtml;$ret .= "</textarea>";if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
function _useRegisterHelper($type, $var) {if (!is_null($var)) {if (is_bool($var)) {return "w.{$type} = " . boolToString($var) . ";\n";} else {return "w.{$type} = '" . $this->$type . "';\n";}
}
return '';}
}
?>