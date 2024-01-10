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
class Bs_FormItAble extends Bs_Object {var $_defaultFormHints = array (
'debug' => array(
'checkSopHints'   => TRUE,  'checkHintSyntax' => TRUE,  'checkClassVars'  => TRUE,  ),
'props'  => array(),
'groups' => array(),
'fields' => array(),
);var $_formHints;var $_dataPrefix;function &Bs_FormItAble() {if (isSet($GLOBALS['Bs_FormItAble'])) return $GLOBALS['Bs_FormItAble'];parent::Bs_Object(); $GLOBALS['Bs_FormItAble'] = &$this;}
function &doItYourself(&$theObject, $returnFormObj=FALSE, $overwriteFormProps=NULL) {$_func_ = 'doItYourself';$err = FALSE;do {if (!$form = &$this->buildForm($theObject, $overwriteFormProps)) {$err = "-- See previous error.";break; }
if (@$_POST['bs_form']['step'] == '2') {$ret = $form->doItYourself();if (is_array($ret)) break; } else {$ret = $form->doItYourself();break; }
if (($form->mode !== 'delete') && ($form->mode !== 'view')) {$formData = $form->getValuesArray(TRUE, 'valueInternal');foreach ($formData as $var => $value) {$varName = (isSet($this->_formHints['fields'][$var]['varName'])) ? $this->_formHints['fields'][$var]['varName'] : $var;if (!empty($this->_dataPrefix[$var]) && is_array($this->_dataPrefix[$var])) {switch (sizeOf($this->_dataPrefix[$var])) {case 1:
$key1 = $this->_dataPrefix[$var][0];$theObject->{$key1}[$varName] = $value;break;case 2:
$key1 = $this->_dataPrefix[$var][0];$key2 = $this->_dataPrefix[$var][1];$theObject->{$key1}[$key2][$varName] = $value;break;case 3:
$key1 = $this->_dataPrefix[$var][0];$key2 = $this->_dataPrefix[$var][1];$key3 = $this->_dataPrefix[$var][2];$theObject->{$key1}[$key2][$key3][$varName] = $value;break;}
} else {$theObject->$varName = $value;}
}
}
} while(FALSE);if ($err) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
if (!is_array($ret) && $returnFormObj) return $form;return $ret;}
function &buildForm(&$theObject, $overwriteFormProps=NULL) {$_func_='buildForm';$status = $err = FALSE;$tryBlock = 1;do {bs_lazyLoadPackage('html/form');$classname = get_class($theObject);if (method_exists($theObject, 'bs_fia_getHints')){$formHints = $theObject->bs_fia_getHints($this);$this->_formHints =& $formHints;if (empty($formHints)) {$err = "No form hints returned by the callback function {$classname}::bs_fia_getHints() from your object[{$classname}].";break $tryBlock; }
} else {$err = "No form hints found for [{$classname}]. You must define the callback methode bs_fia_getHints() in your class, that returns the hint-hash.";break $tryBlock; }
foreach ($this->_defaultFormHints as $key => $defaults) {$formHints[$key] = empty($formHints[$key]) ? $defaults : array_merge($defaults, $formHints[$key]);}
$sopHints = ($formHints['debug']['checkSopHints'] AND method_exists($theObject, 'bs_sop_getHints')) ? $theObject->bs_sop_getHints($x=NULL) : array();$form =& new Bs_Form();if (@is_array($formHints['props'])) {foreach ($formHints['props'] as $var => $val) {$form->$var = $val;}
}
if (is_array($overwriteFormProps)) {foreach ($overwriteFormProps as $var => $val) {$form->$var = $val;}
}
$formGroups = array();if (@is_array($formHints['fields'])) {$tryBlock++;foreach($formHints['fields'] as $var => $fieldProps) {if (isSet($fieldProps['group'])) {$groupName        = $fieldProps['group'];if (is_array($fieldProps['group'])) {$groupNameLastElm = $fieldProps['group'][sizeOf($fieldProps['group'])-1];$firstLoop        = TRUE;foreach ($groupName as $partOfGroupName) {if ($firstLoop) {$groupProps = $formHints['groups'][$partOfGroupName];$firstLoop  = FALSE;} else {$groupProps = $groupProps['children'][$partOfGroupName];}
}
} else {$groupNameLastElm = $fieldProps['group'];$groupProps       = $formHints['groups'][$groupNameLastElm];}
} else {$groupName        = NULL;$groupNameLastElm = NULL;$groupProps       = NULL;}
unset($fieldProps['group']); $dataPrefixKeys = array();if (isSet($formHints['dataPrefix'])) {$dataPrefixKeys[] = $formHints['dataPrefix'];}
if (isSet($groupProps['dataPrefix'])) {if (is_array($groupProps['dataPrefix'])) {$dataPrefixKeys = array_merge($dataPrefixKeys, $groupProps['dataPrefix']);} else {$dataPrefixKeys[] = $groupProps['dataPrefix'];}
}
$this->_dataPrefix[$var] = $dataPrefixKeys;$varName = (isSet($fieldProps['varName'])) ? $fieldProps['varName'] : $fieldProps['name'];if (empty($dataPrefixKeys)) {$valDefault = isSet($theObject->$varName) ? $theObject->$varName : NULL;} else {$firstLoop = TRUE;foreach ($dataPrefixKeys as $dataPrefixKey) {if ($firstLoop) {$valDefault = isSet($theObject->$dataPrefixKey) ? $theObject->$dataPrefixKey : NULL;$firstLoop = FALSE;} else {if (isSet($valDefault[$dataPrefixKey])) {$valDefault = $valDefault[$dataPrefixKey];} else {$valDefault = NULL;break;}
}
}
if (isSet($valDefault[$varName])) {$valDefault = $valDefault[$varName];} else {$valDefault = NULL;}
}
if (($form->mode === 'edit') || ($form->mode === 'delete') || ($form->mode === 'view')) {$fieldProps['valueDefault'] = $valDefault;} elseif ($form->mode === 'add') {if (!is_null($valDefault) && !empty($valDefault)) {$fieldProps['valueDefault'] = $valDefault;}
}
$field = &bs_fabricateFormField($fieldProps);if (!is_null($groupNameLastElm)) {if (!isSet($formGroups[$groupNameLastElm])) {$propArr = $groupProps;$formGroups[$groupNameLastElm] =& new Bs_FormContainer;$formGroups[$groupNameLastElm]->name = $groupNameLastElm;if (!isSet($propArr['caption'])) $propArr['caption'] = $groupNameLastElm;foreach ($propArr as $grpVar => $grpProp) {$formGroups[$groupNameLastElm]->$grpVar = $grpProp;}
$form->elementContainer->addElement($formGroups[$groupNameLastElm]);if (isSet($groupProps['children'])) {$this->_buildGroups($formGroups, $groupNameLastElm, $formHints);}
}
$formGroups[$groupNameLastElm]->addElement($field);} else {$form->elementContainer->addElement($field);}
}
$tryBlock--;}
$status = TRUE;} while(FALSE);if (!$status OR $err) {Bs_Error::setError($err, 'ERROR', __LINE__, $_func_, __FILE__);return FALSE;}
return $form;}
function _buildGroups(&$formGroups, $group, $formHints) {foreach ($formHints['groups'][$group]['children'] as $grpName => $propArr) {$formGroups[$grpName] =& new Bs_FormContainer;$formGroups[$grpName]->name = $grpName;if (!isSet($propArr['caption'])) $propArr['caption'] = $group;foreach($propArr as $grpVar => $grpProp) {$formGroups[$grpName]->$grpVar = $grpProp;}
$formGroups[$group]->addElement($formGroups[$grpName]);}
}
}
?>