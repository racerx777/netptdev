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
define('BS_FORMFIELDWYSIWYG_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldWysiwyg extends Bs_FormField {var $width = 446;var $height = 400;var $imageBrowserUrl = NULL;var $specialCharsSelectorUrl = NULL;var $dataType = NULL;var $useRegisterCustom1 = NULL;var $useRegisterText = NULL;var $useRegisterEasy = NULL;var $useRegisterPlus = NULL;var $useRegisterEditlive = NULL;var $useRegisterHtml = NULL;var $useRegisterScreentype = NULL;var $registerCustom1Caption;var $registerCustom1Content;function Bs_FormFieldWysiwyg() {$this->Bs_FormField(); $this->fieldType  = 'textarea'; $this->bsDataType = 'html';$this->persisterVarSettings['imageBrowserUrl']  = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {$ret = '';$value     = $this->getValue($explodeKey); switch ($this->getVisibility()) {case 'omit':
return $value;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
case 'readonly':
$this->_markAsUsed();$ret .= $this->getFieldAsHidden();case 'show':
$ret .= '<div style="width:' . $this->width . '; height:' . $this->height . '; border:1px solid black; overflow:scroll;">';$ret .= $value;$ret .= '</div>';return $ret;break;default: $this->_markAsUsed();}
$valueForJs = $this->_Bs_HtmlUtil->filterForJavaScript($value);$fieldName = $this->_getFieldNameForHtml($this->name);if (!is_null($explodeKey)) $fieldName .= '[' . $explodeKey . ']';$this->_form->addIncludeOnce('/_bsJavascript/components/wysiwyg/Bs_Wysiwyg.class.js');$this->_form->addIncludeOnce('/_bsJavascript/core/form/Bs_FormFieldSelect.class.js');$this->_form->addIncludeOnce('/_bsJavascript/core/html/Bs_HtmlUtil.lib.js');$aolc  = '';$aolc .= "w = new Bs_Wysiwyg('w');\n";$aolc .= "w.formFieldName = \"{$fieldName}\";\n";$aolc .= "w.setValue(\"{$valueForJs}\");\n";if (!is_null($this->imageBrowserUrl)) {$aolc .= "w.setImageBrowser(\"{$this->imageBrowserUrl}\");\n";}
if (!is_null($this->dataType)) {$aolc .= "w.dataType = '{$this->dataType}';\n";}
if (isSet($this->specialCharsSelectorUrl)) {if (is_string($this->specialCharsSelectorUrl)) {$aolc .= "w.specialCharsSelectorUrl = \"{$this->specialCharsSelectorUrl}\";\n";} elseif ($this->specialCharsSelectorUrl === FALSE) {$aolc .= "w.specialCharsSelectorUrl = false;\n";}
}
$aolc .= $this->_useRegisterHelper('useRegisterCustom1',    @$this->useRegisterCustom1);$aolc .= $this->_useRegisterHelper('useRegisterText',       @$this->useRegisterText);$aolc .= $this->_useRegisterHelper('useRegisterEasy',       @$this->useRegisterEasy);$aolc .= $this->_useRegisterHelper('useRegisterPlus',       @$this->useRegisterPlus);$aolc .= $this->_useRegisterHelper('useRegisterEditlive',   @$this->useRegisterEditlive);$aolc .= $this->_useRegisterHelper('useRegisterHtml',       @$this->useRegisterHtml);$aolc .= $this->_useRegisterHelper('useRegisterScreentype', @$this->useRegisterScreentype);if (isSet($this->registerCustom1Caption)) {$aolc .= "w.registerCustom1Caption = \"{$this->registerCustom1Caption}\";\n";}
if (is_array(@$this->registerCustom1Content)) {foreach($this->registerCustom1Content as $btnArr) {if (is_string($btnArr)) {$aolc .= "w.addToRegisterCustom1();\n";} else {$aolc .= "w.addToRegisterCustom1(\"{$btnArr['action']}\", \"{$btnArr['imgName']}\", \"{$btnArr['title']}\", \"{$btnArr['helpText']}\");\n";}
}
}
$aolc .= "var status = w.drawInto('bsFormWysiwygDiv');\n";$this->_form->addOnLoadCode($aolc);$ret .= '<div id="bsFormWysiwygDiv" style="width:' . $this->width . '; height:' . $this->height . ';"';$ret .= '></div>';if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
function _useRegisterHelper($type, $var) {if (!is_null($var)) {if (is_bool($var)) {return "w.{$type} = " . boolToString($var) . ";\n";} else {return "w.{$type} = '" . $this->$type . "';\n";}
}
return '';}
}
?>