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
class Bs_MagicClass extends Bs_Object {var $persistPrimary = array(
'uniqueKey' => array('name'=>'ID', 'type'=>'auto_increment'), 
);var $persistFields;var $persistFieldsUsed;var $formFields;var $formGroups;var $formProps = array(
'internalName' => 'form', 
'name'         => 'form', 
'mode'         => 'add', 
'language'     => 'en', 
'buttons'      => 'default', 
'user'         => '', 
);var $formFieldsUsed;var $uniqueKey      = 0; function Bs_MagicClass($loadPersistHints=TRUE, $loadFormHints=FALSE) {if ($loadPersistHints) $this->loadPersistHints();if ($loadFormHints)    $this->loadFormHints();}
function activateField($fieldName) {$this->fieldsUsed[$fieldName] = TRUE;}
function addField($fieldName, $storageFields, $formFields) {$this->storageFieldsUsed[$fieldName]  = TRUE;$this->formFieldsUsed[$fieldName]     = TRUE;$this->storageFields[$fieldName]      = $storageFields;$this->formFields[$fieldName]         = $formFields;}
function removeField($fieldName) {unset($this->fieldsUsed[$fieldName]);}
function loadPersistHints() {}
function loadFormHints() {}
function bs_sop_getHints(&$sop) {$hintHash = array();if (isSet($this->persistTable)) $hintHash['table']   = $this->persistTable;if (isSet($this->persistDebug)) $hintHash['debug']   = $this->persistDebug;$hintHash['primary'] = $this->persistPrimary;$hintHash['fields']  = array();foreach ($this->persistFieldsUsed as $varName => $dev0) {$hintHash['fields'][$varName] = $this->persistFields[$varName];}
return $hintHash;}
function bs_fia_getHints(&$fia) {if (!is_array($this->formFieldsUsed)) return FALSE;$fields = array();foreach ($this->formFieldsUsed as $varName => $dev0) {unset($prop); $fallbackProps = (isSet($this->persistFields[$varName])) ? $this->persistFields[$varName] : array();$prop          = (isSet($this->formFields[$varName]))    ? $this->formFields[$varName]    : array();if (!isSet($prop['name'])) {$prop['name'] = $varName;}
if (!isSet($prop['bsDataType'])) {switch ($fallbackProps['metaType']) {case 'string':
case 'text':
case 'blob':
case 'serialize':
case 'boolean': case 'bool':
$prop['bsDataType'] = 'text';break;case 'integer':
case 'double':
$prop['bsDataType'] = 'number';break;default: $prop['bsDataType'] = 'text';}
}
if (!isSet($prop['fieldType'])) {if (isSet($fallbackProps['metaType'])) {switch ($fallbackProps['metaType']) {case 'string':
$prop['fieldType'] = 'Bs_FormFieldText';break;case 'text':
case 'blob':
case 'serialize':
$prop['fieldType'] = 'Bs_FormFieldTextarea';break;case 'boolean': case 'bool':
$prop['fieldType'] = 'Bs_FormFieldRadio'; break;case 'integer':
case 'double':
$prop['fieldType'] = 'Bs_FormFieldText';break;default:
$prop['fieldType'] = 'Bs_FormFieldText';}
} else {switch ($prop['bsDataType']) {default:
$prop['fieldType'] = 'Bs_FormFieldText';}
}
}
if (!isSet($prop['maxLength']) && isSet($dbHintArr['size'])) {$prop['maxLength'] = $dbHintArr['size'];}
if (($dbHintArr['metaType'] == 'boolean') && ($prop['fieldType'] == 'Bs_FormFieldRadio') && !isSet($prop['optionsHard']) && !isSet($prop['optionsEval'])) {$prop['optionsHard'] = array('1'=>'yes', '0'=>'no');$prop['align']       = 'h';}
$fields[$varName] = &$prop;}
return array('props'=>$this->formProps, 'groups'=>$this->formGroups, 'fields'=>$fields);}
}
?>