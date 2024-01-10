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
define('BS_FORMFIELDSELECT_VERSION',      '4.5.$Revision: 1.6 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldSelect extends Bs_FormField {var $size;var $multiple;var $optionsType;var $_options;var $optionsHard;var $optionsEval;var $useFlipFlop = FALSE;var $flipFlopSettings;var $flipFlopObjName;function Bs_FormFieldSelect() {$this->Bs_FormField(); $this->fieldType = 'select';$this->persisterVarSettings['size']        = array('mode'=>'stream');$this->persisterVarSettings['multiple']    = array('mode'=>'stream');$this->persisterVarSettings['optionsType'] = array('mode'=>'stream');$this->persisterVarSettings['optionsHard'] = array('mode'=>'stream');$this->persisterVarSettings['optionsEval'] = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($addEnforceCheckbox=TRUE) {$ret       = '';$fieldName = $this->_getFieldNameForHtml($this->name);switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
$this->_markAsUsed();$ret .= $this->getFieldAsHidden();case 'readonly':
$fieldName .= '_readonly';$ret .= "<select disabled";break;case 'show':
$ret .= $this->getOptionStringForValue($this->getValue());return $ret;break;default: $this->_markAsUsed();$ret .= "<select";}
if ((isSet($this->multiple)) && ($this->multiple)) {$ret .= " name=\"{$fieldName}[]\"";} else {$ret .= " name=\"{$fieldName}\"";}
if (isSet($this->direction)) $ret .= " dir=\"{$this->direction}\"";if (empty($this->styles['id']) && $this->useFlipFlop) {$this->styles['id'] = 's' . substr(md5(uniqid(rand(), true)), 0, 8);}
$ret .= $this->_getTagStringStyles();$ret .= $this->_getTagStringEvents();$ret .= $this->_getTagStringAdditionalTags();if ((isSet($this->size)) && (!empty($this->size))) $ret .= " size=\"{$this->size}\"";if ((isSet($this->multiple)) && ($this->multiple)) $ret .= " multiple";$ret .= '>';$ret .= $this->_getOptionsString();$ret .= '</select>';if ($this->useFlipFlop) {$this->_form->addIncludeOnce('/_bsJavascript/core/lang/Bs_Misc.lib.js');$this->_form->addIncludeOnce('/_bsJavascript/core/form/Bs_FormFieldSelect.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/flipflop/Bs_FlipFlop.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/toolbar/Bs_Button.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/toolbar/win2k_ie.css');$jsVarName  = (!empty($this->flipFlopObjName)) ? $this->flipFlopObjName : 'bsSe' . $fieldName;$aolc  = '';$aolc .= "{$jsVarName} = new Bs_FlipFlop('{$jsVarName}');\n";if (isSet($this->flipFlopSettings['fieldAvailableCssClass']))    $aolc .= "{$jsVarName}.fieldAvailableCssClass      = '{$this->flipFlopSettings['fieldAvailableCssClass']}';\n";if (isSet($this->flipFlopSettings['fieldSelectedCssClass']))     $aolc .= "{$jsVarName}.fieldSelectedCssClass       = '{$this->flipFlopSettings['fieldSelectedCssClass']}';\n";if (isSet($this->flipFlopSettings['textAvailable']))             $aolc .= "{$jsVarName}.textAvailable               = '{$this->flipFlopSettings['textAvailable']}';\n";if (isSet($this->flipFlopSettings['textAvailableCssClass']))     $aolc .= "{$jsVarName}.textAvailableCssClass       = '{$this->flipFlopSettings['textAvailableCssClass']}';\n";if (isSet($this->flipFlopSettings['textSelected']))              $aolc .= "{$jsVarName}.textSelected                = '{$this->flipFlopSettings['textSelected']}';\n";if (isSet($this->flipFlopSettings['textSelectedCssClass']))      $aolc .= "{$jsVarName}.textSelectedCssClass        = '{$this->flipFlopSettings['textSelectedCssClass']}';\n";if (isSet($this->flipFlopSettings['showCaptionLine']))           $aolc .= "{$jsVarName}.showCaptionLine             = " . boolToString($this->flipFlopSettings['textSelectedCssClass']) . ";\n";if (isSet($this->flipFlopSettings['captionLineClass']))          $aolc .= "{$jsVarName}.captionLineClass            = '{$this->flipFlopSettings['captionLineClass']}';\n";if (isSet($this->flipFlopSettings['moveOnDblClick']))            $aolc .= "{$jsVarName}.moveOnDblClick              = " . boolToString($this->flipFlopSettings['moveOnDblClick']) . ";\n";if (isSet($this->flipFlopSettings['moveOnClick']))               $aolc .= "{$jsVarName}.moveOnClick                 = " . boolToString($this->flipFlopSettings['moveOnClick']) . ";\n";if (isSet($this->flipFlopSettings['maxSelectedNumber']))         $aolc .= "{$jsVarName}.maxSelectedNumber           = '{$this->flipFlopSettings['maxSelectedNumber']}';\n";if (isSet($this->flipFlopSettings['maxSelectedWarning']))        $aolc .= "{$jsVarName}.maxSelectedWarning          = '{$this->flipFlopSettings['maxSelectedWarning']}';\n";if (isSet($this->flipFlopSettings['buttonSelectAll']))           $aolc .= "{$jsVarName}.buttonSelectAll             = null;\n";if (isSet($this->flipFlopSettings['buttonDeselectAll']))         $aolc .= "{$jsVarName}.buttonDeselectAll           = null;\n";$aolc .= "{$jsVarName}.convertField('{$this->styles['id']}');\n";$aolc .= "\n";$this->_form->addOnLoadCode($aolc);}
if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
function _getOptionsString() {$ret = ''; $array = $this->_prepareOptionsData();$default = $this->_getDefaultValue();if (!is_array($default)) {if (!is_null($default)) {$default = array($default);} else {$default = array();}
}
reset($array);while(list($k) = each($array)) {$selected = (in_array($k, $default)) ? ' selected' : '';$ret .= "<option value=\"{$k}\"{$selected}>{$array[$k]}</option>";}
return $ret;}
function _prepareOptionsData() {$ret = array(); if (!isSet($this->_options)) $this->_prepareOptions();if ((isSet($this->_options)) && (is_array($this->_options)) && (sizeOf($this->_options) > 0)) {reset($this->_options);if (is_array(current($this->_options))) {$ret = $this->getLanguageDependentValue($this->_options);if (@is_null($array)) $array = array();} else {$ret = $this->_options;}
}
return $ret;}
function getOptionStringForValue($value, $lang=null) {$array = $this->_prepareOptionsData();if (is_null($value) || is_array($value)) return ''; return isSet($array[$value]) ?  $array[$value] : '';}
function _getDefaultValue() {if ($this->_form->step == 1) {if (isSet($this->valueDefault) && is_array($this->valueDefault)) {$type = $this->_Bs_Array->guessType($this->valueDefault);if (substr($type, 0, 4) == 'hash') {$default = $this->_getTagStringValue();} else {$default = $this->valueDefault;}
} elseif (isSet($this->valueDefault)) {$default = $this->valueDefault;} else {$default = '';}
} else {$default = $this->_getTagStringValue();}
return $default;}
function inputManipulate() {$v = (isSet($this->valueReceived)) ? $this->valueReceived : NULL;$this->valueDisplay  = $v;$this->valueInternal = $v;}
function inputValidate($paramValue=NULL) {if ((is_null($paramValue)) && ($this->isExplodable()) && (is_array($this->valueInternal))) {while (list($k) = each($this->valueInternal)) {$status = $this->inputValidate($this->valueInternal[$k]);if ($status !== TRUE) return $status;}
return TRUE;} elseif (!is_null($paramValue)) {$v = &$paramValue;} else {$v = $this->valueInternal;}
if (is_array($v)) {} else {$vLength = strlen($v);unset($this->errorMessage);if (is_string($status = $this->validateMust             ($v, $vLength))) return $status;if (is_string($status = $this->validateOnlyOneOf        ($v)))           return $status;if (is_string($status = $this->validateOnlyIf           ($v, $vLength))) return $status;if (is_string($status = $this->validateMinLength        ($v, $vLength))) return $status;if (is_string($status = $this->validateMaxLength        ($v, $vLength))) return $status;}
return TRUE;}
function validateMinLength(&$v) {if ((isSet($this->minLength)) && ($this->minLength > 0) && ($this->multiple)) {$size = sizeOf($v);if ( (!is_array($v)) || (($size < $this->minLength) && (($size > 0) || ($this->isMust())))) {return $this->errorMessage = sPrintF($this->getErrorMessage('minLength'), $this->minLength, $size);}
}
return TRUE;}
function validateMaxLength(&$v, &$vLength) {$size = sizeOf($v);if ((@$this->multiple) && (is_numeric($this->maxLength)) && (is_array($v)) && ($size > $this->maxLength)) {return $this->errorMessage = sPrintF($this->getErrorMessage('maxLength'), $this->maxLength, $size);}
return TRUE;}
function _prepareOptions($suppressErrors=FALSE) {if ((!isSet($this->optionsType) || ($this->optionsType != 'eval')) && (isSet($this->optionsHard))) { $this->_options = &$this->optionsHard;return;} elseif (isSet($this->optionsEval)) { $t = $this->getLanguageDependentValue($this->optionsEval);if (is_null($t)) {$this->_options = array();return;}
if ($suppressErrors) {$t = @eval($t);} else {$t = eval($t);}
if (is_array($t)) {$this->_options = &$t;} else {$this->_options = array();}
} else { $this->_options = array();}
}
}
?>