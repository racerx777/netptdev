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
define('BS_FORMFIELDRADIO_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldRadio extends Bs_FormField {var $optionsType;var $_options;var $optionsHard;var $optionsEval;var $align = 'v';function Bs_FormFieldRadio() {$this->Bs_FormField(); $this->fieldType = 'radio';$this->persisterVarSettings['optionsType'] = array('mode'=>'stream');$this->persisterVarSettings['optionsHard'] = array('mode'=>'stream');$this->persisterVarSettings['optionsEval'] = array('mode'=>'stream');$this->persisterVarSettings['align']       = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE, $elementList=null) {$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
$this->_markAsUsed();$ret .= $this->getFieldAsHidden();case 'readonly':
$fieldName .= '_readonly';$ret .= $this->getRadios($fieldName, $elementList, FALSE);break;case 'show':
$ret .= $this->getOptionStringForValue($this->getValue($explodeKey));break;default: $this->_markAsUsed();$ret .= $this->getRadios($fieldName, $elementList);}
if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
function getRadios($fieldName, $elementList=null, $editable=TRUE) {$ret       = ''; $array     = $this->_prepareOptionsData();$origArray = $array; if (is_array($elementList)) {$newArray = array();while (list(,$option) = each($elementList)) {if (isSet($array[$option])) {$newArray[$option] = $array[$option];}
}
$array = $newArray;}
$default = $this->_getTagStringValue(); if (isSet($this->align) && ($this->align === 'h')) {$alignChar = '&nbsp;';} else {$alignChar = '<br>';}
$retArray = array();reset($array);$tag = '';while(list($k) = each($array)) {$checked = ((!empty($default) || (@$default == '0')) && ($k == $default)) ? ' checked' : '';if ($editable) {$objectId = $fieldName . $this->_Bs_Array->getPos($origArray, $k);$tag = "<input type='radio' name='{$fieldName}' id='{$objectId}' value=\"{$k}\"{$checked} ";$tag .= $this->_getTagStringStyles();$tag .= $this->_getTagStringEvents();$tag .= $this->_getTagStringAdditionalTags();$tag .= "><label for=\"{$objectId}\">{$array[$k]}</label>";} else {$tag = "<img style='vertical-align:text-top;' src='/_bsImages/form/radio_disabled_" . (($checked) ? 'on' : 'off') . ".gif' border='0'> {$array[$k]}";}
$retArray[] = $tag;}
$ret .= join($alignChar, $retArray);return $ret;}
function _prepareOptionsData() {$ret = array(); if (!isSet($this->_options)) $this->_prepareOptions();if ((isSet($this->_options)) && (is_array($this->_options)) && (sizeOf($this->_options) > 0)) {reset($this->_options);if (is_array(current($this->_options))) {$ret = $this->getLanguageDependentValue($this->_options);if (is_null($ret)) $ret = array();} else {$ret = $this->_options;}
}
return $ret;}
function getOptionStringForValue($value, $lang=null) {$array = $this->_prepareOptionsData();if (is_null($value) || is_array($value)) return ''; return isSet($array[$value]) ?  $array[$value] : '';}
function inputManipulate() {$v = (isSet($this->valueReceived)) ? $this->valueReceived : NULL;$this->valueDisplay  = $v;$this->valueInternal = $v;}
function inputValidate($paramValue=NULL) {if ((is_null($paramValue)) && ($this->isExplodable()) && (is_array($this->valueInternal))) {while (list($k) = each($this->valueInternal)) {$status = $this->inputValidate($this->valueInternal[$k]);if ($status !== TRUE) return $status;}
return TRUE;} elseif (!is_null($paramValue)) {$v = &$paramValue;} else {$v = $this->valueInternal;}
$vLength = strlen($v);unset($this->errorMessage);if (is_string($status = $this->validateMust             ($v, $vLength))) return $status;if (is_string($status = $this->validateOnlyOneOf        ($v)))           return $status;return TRUE;}
function _prepareOptions() {if (isSet($this->optionsHard) && (@$this->optionsType !== 'eval')) { $this->_options = &$this->optionsHard;return;} elseif (isSet($this->optionsEval)) { $t = $this->getLanguageDependentValue($this->optionsEval);if (is_null($t)) {$this->_options = array();return;}
if (is_array($t = @eval($t))) {$this->_options = &$t;} else {$this->_options = array();}
} else { $this->_options = array();}
}
}
?>