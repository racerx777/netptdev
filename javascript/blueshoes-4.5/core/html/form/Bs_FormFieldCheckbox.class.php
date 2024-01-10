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
define('BS_FORMFIELDCHECKBOX_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldCheckbox extends Bs_FormField {var $text = NULL;var $textStyles = NULL;function Bs_FormFieldCheckbox() {$this->Bs_FormField(); $this->fieldType = 'checkbox';$this->persisterVarSettings['text'] = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $withText=TRUE, $addEnforceCheckbox=TRUE) {$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
case 'readonly':
$this->_markAsUsed();$fieldName .= '_readonly';$ret .= $this->getFieldAsHidden();$ret .= "<input type=\"{$this->fieldType}\" disabled";break;case 'show':
if ($this->bsDataType === 'boolean') {$ret .= boolToString($this->_getTagStringValue($explodeKey));} else {$ret .= $this->_getTagStringValue($explodeKey);}
return $ret;break;default: $this->_markAsUsed();$ret .= "<input type=\"{$this->fieldType}\"";}
if (!is_null($explodeKey)) {$ret .= " name=\"{$fieldName}[{$explodeKey}]\"";} else {$ret .= " name=\"{$fieldName}\" id=\"{$fieldName}\"";}
$ret .= ' value="1"';if (isTrue($this->_getTagStringValue($explodeKey))) $ret .= " checked";$ret .= $this->_getTagStringStyles();$ret .= $this->_getTagStringEvents();$ret .= $this->_getTagStringAdditionalTags();$ret .= '>';if ($withText) $ret .= ' ' . $this->getFieldText(TRUE);if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
function getFieldText($clickable=FALSE) {if (empty($this->text)) return '';switch ($this->getVisibility()) {case 'readonly':
return $this->getLanguageDependentValue($this->text);break;default: if ($clickable) {return "<label for=\"" . $this->_getFieldNameForHtml($this->name) . "\">" . $this->getLanguageDependentValue($this->text) . "</label>";} else {return "<span" . $this->_getTagStringStylesForText() . ">" . $this->getLanguageDependentValue($this->text) . "</span>";}
}
}
function getCaption($useAccessKey=TRUE, $lang=null, $clickable=FALSE) {switch ($this->getVisibility()) {case 'readonly':
return parent::getCaption(FALSE, $lang);break;default: if (($clickable) && ($this->hasFormObject())) {return "<span style=\"cursor: default;\" onClick=\"bsFormToggleCheckbox('{$this->_form->name}', '" . $this->_getFieldNameForHtml($this->name) . "');\">" . parent::getCaption($useAccessKey, $lang) . "</span>";} else {return parent::getCaption($useAccessKey);}
}
}
function getReadableValue($value=null, $lang=null) {if (is_null($value)) $value = $this->valueDisplay;if ($value) {return $this->_form->getInterfaceText('yes', $lang);} else {return $this->_form->getInterfaceText('no', $lang);}
}
function inputValidate($paramValue=NULL) {if ((is_null($paramValue)) && ($this->isExplodable()) && (is_array($this->valueInternal))) {while (list($k) = each($this->valueInternal)) {$status = $this->inputValidate($this->valueInternal[$k]);if ($status !== TRUE) return $status;}
return TRUE;} elseif (!is_null($paramValue)) {$v = &$paramValue;} else {$v = $this->valueInternal;}
$vLength = strlen($v);unset($this->errorMessage);if (is_string($status = $this->validateMust             ($v, $vLength))) return $status;return TRUE;}
function _getTagStringStylesForText($ignoreStyle=FALSE) {$ret = '';if (isSet($this->textStyles)) {if (isSet($this->textStyles['id'])       && (!empty($this->textStyles['id'])))        $ret .= " id=\"{$this->textStyles['id']}\"";if (isSet($this->textStyles['class'])    && (!empty($this->textStyles['class'])))     $ret .= " class=\"{$this->textStyles['class']}\"";if ((!$ignoreStyle) && (isSet($this->textStyles['style']))    && (!empty($this->textStyles['style'])))     $ret .= " style=\"{$this->textStyles['style']}\"";if (isSet($this->textStyles['title']) && !is_null($t = $this->getLanguageDependentValue($this->textStyles['title']))) {$ret .= " title=\"{$t}\"";}
}
return $ret;}
}
?>