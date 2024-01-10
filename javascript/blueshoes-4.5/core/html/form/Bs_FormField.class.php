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
define('BS_FORMFIELD_VERSION',      '4.5.$Revision: 1.7 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormElement.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core'] . 'util/Bs_String.class.php');require_once($APP['path']['core'] . 'util/Bs_Array.class.php');require_once($APP['path']['core'] . 'text/Bs_LanguageHandler.class.php');require_once($APP['path']['core'] . 'net/email/Bs_EmailValidator.class.php');require_once($APP['path']['core'] . 'net/Bs_Url.class.php');require_once($APP['path']['core'] . 'date/Bs_Date.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldText.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldPassword.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldRadio.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldCheckbox.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldImage.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldFile.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldHidden.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldButton.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldSubmit.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldReset.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldSelect.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldTextarea.class.php');define('BS_FORM_TEXT_AZLOWER',               8);define('BS_FORM_TEXT_AZUPPER',              16);define('BS_FORM_TEXT_09',                   32);define('BS_FORM_TEXT_UNDERSCORE',           64);define('BS_FORM_TEXT_DASH',                128);define('BS_FORM_TEXT_ANYTHING',            256);class Bs_FormField extends Bs_FormElement {var $_Bs_String;var $_Bs_Array;var $_Bs_HtmlUtil;var $_Bs_Date;var $fieldType = NULL;var $defaultErrorMessage;var $errorMessage;var $errorType;var $level;var $editability;var $isUsed = FALSE;var $valueDefault;var $valueDefaultType;var $valueReceived;var $valueDisplay = NULL;var $valueInternal = NULL;var $saveToDb = NULL;var $dbFieldName = NULL;var $dbDataType = NULL;var $dbNotNull = TRUE;var $dbPrimaryKey = TRUE;var $dbKey = FALSE;var $dbIndexFulltext;var $dbAutoIncrement;var $dbUnique;var $dbForeignKey;var $dbAttributes;var $explodeEval;var $_explodeArray;var $direction;var $styles;var $advancedStyles;var $events;var $onEnter;var $additionalTags;var $bsDataType = NULL;var $bsDataInfo = NULL;var $bsDataManipulation;var $bsDataManipVar;var $enforce;var $_must;var $must = FALSE;var $mustIf = NULL;var $mustOneOf = NULL;var $mustOneOfIf = NULL;var $onlyOneOf = NULL;var $onlyIf;var $onlyOneOfIf;var $minLength = NULL;var $maxLength = NULL;var $mustStartWith = NULL;var $notStartWith = NULL;var $mustEndWith = NULL;var $notEndWith = NULL;var $mustContain = NULL;var $notContain = NULL;var $equalTo;var $notEqualTo;var $mustBeUnique;var $regularExpression = NULL;var $additionalCheck = NULL;var $trim;var $remove;var $removeI;var $replace;var $replaceI;var $case;var $codePostLoad;var $codePostReceive;var $codePostManipulate;function Bs_FormField() {parent::Bs_FormElement(); $this->_Bs_String   = &$GLOBALS['Bs_String'];$this->_Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil'];$this->_Bs_Array    = &$GLOBALS['Bs_Array'];$this->_Bs_Date     = &$GLOBALS['Bs_Date'];$this->elementType  = 'field';$tempArray = array('mode'=>'stream');$t = array( 'fieldType'         => array('mode'=>'lonely',        'datatype'=>'string',      'index'=>TRUE), 
'caption'           => &$tempArray, 
'editability'       => &$tempArray, 
'visibility'        => &$tempArray, 
'valueDefault'      => &$tempArray, 
'valueDefaultType'  => &$tempArray, 
'saveToDb'          => &$tempArray, 
'dbFieldName'       => &$tempArray, 
'dbDataType'        => &$tempArray, 
'dbNotNull'         => &$tempArray, 'dbPrimaryKey'      => &$tempArray, 'dbKey'             => &$tempArray, 'dbIndexFulltext'   => &$tempArray, 'dbAutoIncrement'   => &$tempArray, 'dbUnique'          => &$tempArray, 'dbForeignKey'      => &$tempArray, 'dbAttributes'      => &$tempArray, 'explodeEval'       => &$tempArray, 
'direction'         => &$tempArray, 
'styles'            => &$tempArray, 
'advancedStyles'    => &$tempArray, 
'events'            => &$tempArray, 
'onEnter'           => &$tempArray, 
'additionalTags'    => &$tempArray, 
'bsDataType'        => &$tempArray, 
'bsDataInfo'        => &$tempArray, 
'enforce'           => &$tempArray, 
'must'              => &$tempArray, 
'mustIf'            => &$tempArray, 
'mustOneOf'         => &$tempArray, 
'mustOneOfIf'       => &$tempArray, 
'onlyOneOf'         => &$tempArray, 
'onlyIf'            => &$tempArray, 'minLength'         => &$tempArray, 
'maxLength'         => &$tempArray, 
'mustStartWith'     => &$tempArray, 
'notStartWith'      => &$tempArray, 
'mustEndWith'       => &$tempArray, 
'notEndWith'        => &$tempArray, 
'mustContain'       => &$tempArray, 
'notContain'        => &$tempArray, 
'equalTo'           => &$tempArray, 
'notEqualTo'        => &$tempArray, 
'mustBeUnique'      => &$tempArray, 
'regularExpression' => &$tempArray, 
'additionalCheck'   => &$tempArray, 
'trim'              => &$tempArray, 
'remove'            => &$tempArray, 
'removeI'           => &$tempArray, 
'replace'           => &$tempArray, 
'replaceI'          => &$tempArray, 
'case'              => &$tempArray, 
'codePostReceive'   => &$tempArray, 
'codePostLoad'      => &$tempArray
);$this->persisterVarSettings = array_merge($this->persisterVarSettings, $t);}
function &getElement($optionList=null) {$hasFormObject = $this->hasFormObject();if ($this->isExplodable()) {$ret = '<table border=0 cellpadding=2 cellspacing=0>';reset($this->_explodeArray);while (list($k) = each($this->_explodeArray)) {if ($hasFormObject && ($this->_form->state != 'form')) {$ret .= '<tr><td>' . $this->_explodeArray[$k] . '</td><td>' . $this->getValue($k) . '</td></tr>';} else {$ret .= '<tr><td>' . $this->_explodeArray[$k] . '</td><td>' . $this->getField($k, TRUE, $optionList) . '</td></tr>';}
}
$ret .= '</table>';} else {if ($hasFormObject && ($this->_form->state != 'form')) {$ret = &$this->getValue();} else {$ret = &$this->getField(null, TRUE, $optionList);}
}
return $ret;}
function getField() {return '';}
function addEnforceCheckbox() {$ret = '';if ((isSet($this->errorType)) && (isSet($this->enforce[$this->errorType])) && ($this->enforce[$this->errorType])) {$ret = '<br><input type="checkbox" name="' . $this->name . '_enforce" value="1"';if (@$_POST[$this->name . '_enforce']) $ret .= ' checked'; $ret .= '> overlook this error/warning';}
return $ret;}
function isExplodable() {return (bool)((isSet($this->_explodeArray)) && (is_array($this->_explodeArray)) && (sizeOf($this->_explodeArray) > 0));}
function getAdvancedStyle($key) {if (isSet($this->advancedStyles) && isSet($this->advancedStyles[$key]) && !empty($this->advancedStyles[$key])) {return $this->advancedStyles[$key];} else {if ($this->hasFormObject()) {if (isSet($this->_form->advancedStyles) && isSet($this->_form->advancedStyles[$key]) && !empty($this->_form->advancedStyles[$key])) {return $this->_form->advancedStyles[$key];}
}
}
return FALSE;}
function getAdvancedStyleHelper($what='caption') {if ($this->_form->step == 2) {if ($this->isMust()) {if (isSet($this->errorMessage)) {return $this->getAdvancedStyle($what . 'MustWrong');} else {return $this->getAdvancedStyle($what . 'MustOkay');}
} else {if (isSet($this->errorMessage)) {return $this->getAdvancedStyle($what . 'MayWrong');} else {return $this->getAdvancedStyle($what . 'MayOkay');}
}
} else {if ($this->isMust()) {return $this->getAdvancedStyle($what . 'Must');} else {return $this->getAdvancedStyle($what . 'May');}
}
return FALSE; }
function setExplode($param=NULL) {if (is_array($param)) {$this->_explodeArray = &$param;return;} elseif (is_string($param)) {$this->explodeEval = &$param;} else {if (isSet($this->explodeEval) && is_string($this->explodeEval)) {$param = &$this->explodeEval;} else {return;}
}
$t = evalWrap($param);if (is_array($t)) {$this->_explodeArray = &$t;}
}
function getLabel($useAccessKey=TRUE) {if (is_null($t = $this->getLanguageDependentValue($this->styles['label']))) return '';$ret  = '<label';if (isSet($this->styles['id']))        $ret .= " for=\"{$this->styles['id']}\"";if (isSet($this->styles['accessKey'])) $ret .= " accesskey=\"{$this->styles['accessKey']}\"";$ret .= ">{$t}</label>";return $ret;}
function inputManipulate($paramValue=NULL) {if ((is_null($paramValue)) && ($this->isExplodable()) && (is_array($this->valueReceived))) {while (list($k) = each($this->valueReceived)) {$this->inputManipulate(array($k, $this->valueReceived[$k]));}
return;} elseif (is_array($paramValue)) {$v = &$paramValue[1];} else {$v = $this->valueReceived;}
if (isSet($this->trim)) {switch (strToLower($this->trim)) {case 'both':
$v = trim($v);break;case 'left':
$v = ltrim($v);break;case 'right':
$v = rtrim($v);break;}
}
if (isSet($this->case)) {switch (strToLower($this->case)) {case 'lower':
$v = strToLower($v);break;case 'upper':
$v = strToUpper($v);break;case 'ucwords':
$v = ucWords($v);break;case 'ucwordsonly':
$v = ucWords(strToLower($v));break;case 'ucfirst':
$v = ucFirst($v);break;case 'ucfirstonly':
$v = ucFirst(strToLower($v));break;case 'nospam1':
$v = ereg_replace('!+', '!', $v);  $v = ereg_replace('\?+', '?', $v); if ($this->_Bs_String->isUpper($v)) $v = ucFirst(strToLower($v)); break;case 'nospam2':
$string = ereg_replace('!+', '.', $v);  $string = ereg_replace('\?+', '?', $v); if ($this->_Bs_String->isUpper($v)) $v = ucFirst(strToLower($v)); break;}
}
if ((isSet($this->remove)) && (is_array($this->remove))) {reset($this->remove);if (is_array(current($this->remove))) {$t = $this->getLanguageDependentValue($this->remove);if (!is_null($t)) $v = str_replace($t, '', $v);} else {$v = str_replace($this->remove, '', $v);}
}
if ((isSet($this->removeI)) && (is_array($this->removeI))) {reset($this->removeI);if (is_array(current($this->removeI))) {$t = $this->getLanguageDependentValue($this->removeI);} else {$t = $this->removeI;}
if (!is_null($t)) {while (list($k) = each($t)) {$t[$k] = '/' . preg_quote($t[$k], '/') . '/i';}
$v = preg_replace($t, '', $v);}
}
if ((isSet($this->replace)) && (is_array($this->replace))) {reset($this->replace);if (is_array(current($this->replace))) {$t = $this->getLanguageDependentValue($this->replace);if (!is_null($t)) $v = str_replace(array_keys($t), array_values($t), $v);} else {$v = str_replace(array_keys($this->replace), array_values($this->replace), $v);}
}
if ((isSet($this->replaceI)) && (is_array($this->replaceI))) {reset($this->replaceI);if (is_array(current($this->replaceI))) {$t = $this->getLanguageDependentValue($this->replaceI);} else {$t = $this->replaceI;}
if (!is_null($t)) {$tFrom = array_keys($t);$tTo   = array_values($t);while (list($k) = each($tFrom)) {$tFrom[$k] = '/' . preg_quote($tFrom[$k], '/') . '/i';}
$v = preg_replace($tFrom, $tTo, $v);}
}
switch ($this->bsDataType) {case 'date':
switch ($this->bsDataInfo) {case '4': $dateArray = $this->_Bs_Date->euDateToArray($v);if (is_array($dateArray)) {$vDisplay  = $this->_Bs_Date->formatArray('eu-3', $dateArray);$vInternal = $this->_Bs_Date->formatArray('sql-3', $dateArray);}
break;case '3': break;case '2': break;default: }
}
if (!isSet($vDisplay))  $vDisplay  = $v;if (!isSet($vInternal)) $vInternal = $v;if (is_array($paramValue)) {$this->valueDisplay[$paramValue[0]]  = $vDisplay;$this->valueInternal[$paramValue[0]] = $vInternal;} else {$this->valueDisplay  = $vDisplay;$this->valueInternal = $vInternal;}
}
function unpersistTrigger() {$this->setExplode();if ((isSet($this->codePostLoad)) && (is_string($this->codePostLoad))) {$this->_evalWrap($this->codePostLoad, 'low');}
}
function postReceiveTrigger() {if ((isSet($this->codePostReceive)) && (is_string($this->codePostReceive))) {$this->_evalWrap($this->codePostReceive, 'low');}
}
function postManipulateTrigger() {if ((isSet($this->codePostManipulate)) && (is_string($this->codePostManipulate))) {$this->_evalWrap($this->codePostManipulate, 'low');}
}
function inputValidate($paramValue=NULL) {if ((is_null($paramValue)) && ($this->isExplodable()) && (is_array($this->valueInternal))) {while (list($k) = each($this->valueInternal)) {$status = $this->inputValidate($this->valueInternal[$k]);if ($status !== TRUE) return $status;}
return TRUE;} elseif (!is_null($paramValue)) {$v = &$paramValue;} else {$v = $this->valueInternal;}
$vLength = strlen($v);unset($this->errorMessage);if (is_string($status = $this->validateMust             ($v, $vLength))) return $status;if (is_string($status = $this->validateOnlyOneOf        ($v)))           return $status;if (is_string($status = $this->validateOnlyIf           ($v, $vLength))) return $status;if (is_string($status = $this->validateMinLength        ($v, $vLength))) return $status;if (is_string($status = $this->validateMaxLength        ($v, $vLength))) return $status;if (is_string($status = $this->validateMustStartWith    ($v)))           return $status;if (is_string($status = $this->validateNotStartWith     ($v)))           return $status;if (is_string($status = $this->validateMustEndWith      ($v)))           return $status;if (is_string($status = $this->validateNotEndWith       ($v)))           return $status;if (is_string($status = $this->validateMustContain      ($v)))           return $status;if (is_string($status = $this->validateNotContain       ($v)))           return $status;if (is_string($status = $this->validateEqualTo          ($v)))           return $status;if (is_string($status = $this->validateNotEqualTo       ($v)))           return $status;if (is_string($status = $this->validateDataType         ($v)))           return $status;if (is_string($status = $this->validateRegularExpression($v)))           return $status;if (is_string($status = $this->validateAdditionalCheck  ($v, $vLength))) return $status;if (is_string($status = $this->validateMustBeUnique     ($v)))           return $status;return TRUE;}
function validateMust(&$v, &$vLength) {if ($vLength > 0) {return TRUE;}
if ((isSet($this->enforce['must'])) && ($this->enforce['must']) && (@$_POST[$this->name . '_enforce'])) { return TRUE;}
if ($isMust = $this->isMust(TRUE, TRUE)) { $this->errorType = $isMust;return $this->errorMessage = $this->getErrorMessage($isMust);}
return TRUE;}
function validateOnlyOneOf(&$v) {if ((isSet($this->enforce['onlyOneOf'])) && ($this->enforce['onlyOneOf']) && (@$_POST[$this->name . '_enforce'])) return TRUE;if ((isSet($this->onlyOneOf)) && (is_array($this->onlyOneOf)) && ($this->isFilledIn())) {if ($this->hasFormObject()) {while(list($k) = each($this->onlyOneOf)) {if ($this->_form->isFieldFilledIn($this->onlyOneOf[$k])) {$fieldList   = $this->onlyOneOf;$fieldList[] = $this->name;$this->errorType = 'onlyOneOf';return $this->errorMessage = sPrintF($this->getErrorMessage('onlyOneOf'), join(', ', $fieldList));break;}
}
}
}
return TRUE;}
function validateOnlyIf(&$v, &$vLength) {if ((isSet($this->enforce['onlyIf'])) && ($this->enforce['onlyIf']) && (@$_POST[$this->name . '_enforce'])) return TRUE;if ($vLength > 0) {if ((isSet($this->onlyIf)) && (is_array($this->onlyIf))) {if (!$this->_anyIfCase($this->onlyIf)) { $this->errorType = 'onlyIf';return $this->errorMessage = $this->getErrorMessage('onlyIf');}
}
}
return TRUE;}
function validateMinLength(&$v, &$vLength) {if ((isSet($this->enforce['minLength'])) && ($this->enforce['minLength']) && (@$_POST[$this->name . '_enforce'])) return TRUE;if ((isSet($this->minLength)) && ($this->minLength > 0)) {if (($vLength < $this->minLength) && (($vLength > 0) || ($this->isMust()))) {$this->errorType = 'minLength';return $this->errorMessage = sPrintF($this->getErrorMessage('minLength'), $this->minLength, $vLength);}
}
return TRUE;}
function validateMaxLength(&$v, &$vLength) {if ((isSet($this->enforce['maxLength'])) && ($this->enforce['maxLength']) && (@$_POST[$this->name . '_enforce'])) return TRUE;if ((is_int($maxLength = $this->_getMaxLength())) && ($vLength > $maxLength)) {$this->errorType = 'maxLength';return $this->errorMessage = sPrintF($this->getErrorMessage('maxLength'), $maxLength, $vLength);}
return TRUE;}
function validateMustStartWith(&$v) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['mustStartWith'])) && ($this->enforce['mustStartWith']) && (@$_POST[$this->name . '_enforce'])) return TRUE;$array = $this->_getParamValue($this->mustStartWith);if (sizeOf($array) > 0) {$success = FALSE;while(list($k) = each($array)) {if ($this->_Bs_String->startsWithI($v, $array[$k], FALSE)) {$success = TRUE;break;}
}
if (!$success) {$this->errorType = 'mustStartWith';return $this->errorMessage = sPrintF($this->getErrorMessage('mustStartWith'), join(', ', $array));}
}
return TRUE;}
function validateNotStartWith(&$v) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['notStartWith'])) && ($this->enforce['notStartWith']) && (@$_POST[$this->name . '_enforce'])) return TRUE;$array = $this->_getParamValue($this->notStartWith);if (sizeOf($array) > 0) {while(list($k) = each($array)) {if ($this->_Bs_String->startsWithI($v, $array[$k], FALSE)) {$this->errorType = 'notStartWith';return $this->errorMessage = sPrintF($this->getErrorMessage('notStartWith'), $array[$k]);break;}
}
}
return TRUE;}
function validateMustEndWith(&$v) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['mustEndWith'])) && ($this->enforce['mustEndWith']) && (@$_POST[$this->name . '_enforce'])) return TRUE;$array = $this->_getParamValue($this->mustEndWith);if (sizeOf($array) > 0) {$success = FALSE;while(list($k) = each($array)) {if ($this->_Bs_String->endsWithI($v, $array[$k], FALSE)) {$success = TRUE;break;}
}
if (!$success) {$this->errorType = 'mustEndWith';return $this->errorMessage = sPrintF($this->getErrorMessage('mustEndWith'), join(', ', $array));}
}
return TRUE;}
function validateNotEndWith(&$v) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['notEndWith'])) && ($this->enforce['notEndWith']) && (@$_POST[$this->name . '_enforce'])) return TRUE;$array = $this->_getParamValue($this->notEndWith);if (sizeOf($array) > 0) {while(list($k) = each($array)) {if ($this->_Bs_String->endsWithI($v, $array[$k], FALSE)) {$this->errorType = 'notEndWith';return $this->errorMessage = sPrintF($this->getErrorMessage('notEndWith'), $array[$k]);break;}
}
}
return TRUE;}
function validateMustContain(&$v) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['mustContain'])) && ($this->enforce['mustContain']) && (@$_POST[$this->name . '_enforce'])) return TRUE;$array = $this->_getParamValue($this->mustContain);if (sizeOf($array) > 0) {$success = FALSE;while(list($k) = each($array)) {if ($this->_Bs_String->inStrI($v, $array[$k])) {$success = TRUE;break;}
}
if (!$success) {$this->errorType = 'mustContain';return $this->errorMessage = sPrintF($this->getErrorMessage('mustContain'), join(', ', $array));}
}
return TRUE;}
function validateNotContain(&$v) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['notContain'])) && ($this->enforce['notContain']) && (@$_POST[$this->name . '_enforce'])) return TRUE;$array = $this->_getParamValue($this->notContain);if (sizeOf($array) > 0) {$success = TRUE;while(list($k) = each($array)) {if ($this->_Bs_String->inStrI($v, $array[$k])) {$success = FALSE;$this->errorType = 'notContain';return $this->errorMessage = sPrintF($this->getErrorMessage('notContain'), $array[$k]);break;}
}
}
return TRUE;}
function validateEqualTo(&$v) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['equalTo'])) && ($this->enforce['equalTo']) && (@$_POST[$this->name . '_enforce'])) return TRUE;if ((isSet($this->equalTo)) && ((!empty($this->equalTo)) || (is_array($this->equalTo)))) {if (is_string($this->equalTo)) $this->equalTo = array($this->equalTo); if ((is_array($this->equalTo)) && ($this->hasFormObject())) {while(list($k) = each($this->equalTo)) {$t = $this->_form->getFieldValue($this->equalTo[$k]);if (is_array($t)) {if ($t[0] != $v) {$this->errorType = 'equalTo';return $this->errorMessage = sPrintF($this->getErrorMessage('equalTo'), $this->equalTo[$k]);break;}
} else {}
}
}
}
return TRUE;}
function validateNotEqualTo(&$v) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['notEqualTo'])) && ($this->enforce['notEqualTo']) && (@$_POST[$this->name . '_enforce'])) return TRUE;if ((isSet($this->notEqualTo)) && ((!empty($this->notEqualTo)) || (is_array($this->notEqualTo)))) {if (is_string($this->notEqualTo)) $this->notEqualTo = array($this->notEqualTo); if ((is_array($this->notEqualTo)) && ($this->hasFormObject())) {while(list($k) = each($this->notEqualTo)) {$t = $this->_form->getFieldValue($this->notEqualTo[$k]);if (is_array($t)) {if ($t[0] == $v) {$this->errorType = 'notEqualTo';return $this->errorMessage = sPrintF($this->getErrorMessage('notEqualTo'), $this->notEqualTo[$k]);break;}
} else {}
}
}
}
return TRUE;}
function validateDataType(&$v) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['dataType'])) && ($this->enforce['dataType']) && (@$_POST[$this->name . '_enforce'])) return TRUE;switch ($this->bsDataType) {case 'boolean':
return TRUE;case 'blob':
return TRUE;case 'username':
return TRUE;case 'zipcode':
return TRUE;case 'creditcard':
return TRUE;case 'ip':
$dataInfo = isSet($this->bsDataInfo) ? $this->bsDataInfo : 1;$ret = TRUE;switch ($dataInfo) {case 1: if (!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $v)) {$ret = $this->errorMessage = $this->getErrorMessage('dataType_ip_invalid');}
break;default:$parts = explode('.',$v);if (!is_array($parts)) {$ret = $this->errorMessage = $this->getErrorMessage('dataType_ip_invalid');break;}
foreach($parts as $part) {if (!preg_match('/^[0-9\-\*\?]{1,7}$/', $part)) {$ret = $this->errorMessage = $this->getErrorMessage('dataType_ip_invalid');break;}
}
}
return $ret;case 'url':
$ret = TRUE;if (!$GLOBALS['Bs_Url']->checkSyntax($v)) {$ret = $this->errorMessage = $this->getErrorMessage('dataType_url_invalid');}
return $ret;case 'domain':
return TRUE;case 'host':
return TRUE;case 'time':
return TRUE;case 'timestamp':
return TRUE;case 'time':
return TRUE;case 'number':
if (!is_numeric($v)) {return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_number_nan'));}
if (isSet($this->bsDataInfo)) {$minMax = $this->_getBsDataInfoNumber();if (is_numeric($minMax[0]) && ($v < $minMax[0])) return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_number_low'));if (is_numeric($minMax[1]) && ($v > $minMax[1])) return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_number_high'));}
return TRUE;case 'text':
if (isSet($this->bsDataInfo)) {if ($this->bsDataInfo < 8) {switch ($this->bsDataInfo) {case 1: return TRUE;case 2: if ($this->_Bs_String->hasSpecialChars($v, 7)) 
return $this->errorMessage = $this->getErrorMessage('dataType_text2');return TRUE;case 3: if ($this->_Bs_String->hasSpecialChars($v, 3)) 
return $this->errorMessage = $this->getErrorMessage('dataType_text2');return TRUE;}
} else {if ($this->bsDataInfo & BS_FORM_TEXT_ANYTHING) return TRUE;$regExpArr = array();if ($this->bsDataInfo & BS_FORM_TEXT_AZLOWER)    $regExpArr[] = 'a-z';if ($this->bsDataInfo & BS_FORM_TEXT_AZUPPER)    $regExpArr[] = 'A-Z';if ($this->bsDataInfo & BS_FORM_TEXT_09)         $regExpArr[] = '0-9';if ($this->bsDataInfo & BS_FORM_TEXT_UNDERSCORE) $regExpArr[] = '_';if ($this->bsDataInfo & BS_FORM_TEXT_DASH)       $regExpArr[] = '-';if (preg_match('/^[' . join('', $regExpArr) . ']*$/', $v)) return TRUE;return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_text4') . join(', ', $regExpArr));}
}
return TRUE;case 'html':
return TRUE;case 'email':
if (isSet($this->bsDataInfo)) {$emailValidator =& new Bs_EmailValidator();switch ($this->bsDataInfo) {case 1: if (!$emailValidator->validateSyntax($v)) {$this->errorType = 'dataType_email_syntax';return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_email_syntax'));}
return TRUE;case 2: $status = $emailValidator->validateHost($v);break;case 3: $status = $emailValidator->validateHost($v);if ($status === TRUE) $status = $emailValidator->validateMailbox($v);break;}
if ($status === TRUE) {return TRUE;} elseif (isEx($status)) {$this->errorType = 'dataType_email_unknown';return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_email_unknown'));} elseif (is_numeric($status)) {switch ($status) {case BS_EMAILVALIDATOR_ERROR_NOT_CAPABLE:
return TRUE;case BS_EMAILVALIDATOR_ERROR_SYNTAX:
$this->errorType = 'dataType_email_syntax';return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_email_syntax'));case BS_EMAILVALIDATOR_ERROR_HOST:
$this->errorType = 'dataType_email_host';return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_email_host'));case BS_EMAILVALIDATOR_ERROR_NO_SUCH_USER:
$this->errorType = 'dataType_email_account';return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_email_account'));case BS_EMAILVALIDATOR_ERROR_NEW_ADDRESS:
$this->errorType = 'dataType_email_newAddress';return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_email_newAddress', $v));default: $this->errorType = 'dataType_email_unknown';return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_email_unknown'));}
}
}
return TRUE;case 'username':
if ($this->bsDataInfo == 2) {}
return TRUE;case 'password':
return TRUE;case 'price':
return TRUE;case 'date':
switch ($this->bsDataInfo) {case '4': $dateArray = $this->_Bs_Date->sqlDateToArray($v);if (!is_array($dateArray)) {$this->errorType = 'dataType_date_invalid';return $this->errorMessage = sPrintF($this->getErrorMessage('dataType_date_invalid'));}
break;case '3': break;case '2': break;default: return TRUE;}
return TRUE;case 'month':
return TRUE;case 'year':
return TRUE;case 'datetime':
return TRUE;}
return TRUE;}
function validateRegularExpression(&$v) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['regularExpression'])) && ($this->enforce['regularExpression']) && (@$_POST[$this->name . '_enforce'])) return TRUE;$array = $this->_getParamValue($this->regularExpression);if (sizeOf($array) > 0) {while(list($k) = each($array)) {if (!empty($array[$k])) {if (!ereg($array[$k], $v)) {$this->errorType = 'regularExpression';return $this->errorMessage = $this->getErrorMessage('regularExpression'); break;}
}
}
}
return TRUE;}
function validateAdditionalCheck(&$v, &$vLength) {if (!$this->isFilledIn()) return TRUE;if ((isSet($this->enforce['additionalCheck'])) && ($this->enforce['additionalCheck']) && (@$_POST[$this->name . '_enforce'])) return TRUE;$t = $this->_evalWrap($this->additionalCheck, 'low', FALSE, array('v'=>&$v, 'vLength'=>&$vLength));if (is_string($t)) {$this->errorType = 'additionalCheck';return $this->errorMessage = $t;} elseif (is_array($t)) {$this->errorType = 'additionalCheck';$t = $this->getLanguageDependentValue($t);if (!is_null($t)) return $this->errorMessage = $t;return $this->errorMessage = 'unknown error.';}
return TRUE;}
function validateMustBeUnique(&$v) {if ((isSet($this->enforce['mustBeUnique'])) && ($this->enforce['mustBeUnique']) && (@$_POST[$this->name . '_enforce'])) return TRUE;if ((isSet($this->mustBeUnique)) && ($this->mustBeUnique)) {if ($this->hasFormObject() && @$this->_form->saveToDb) { $dbTableName = $this->_form->getDbTableName();if ($this->_form->doesDbTableExist($dbTableName, TRUE) !== TRUE) {  return TRUE;}
$sqlQ = "SELECT ID FROM {$dbTableName} WHERE LCASE(" . $this->getDbFieldName() . ") = '" . $this->_form->bsDb->escapeString(strToLower($v)) . "'";$status = $this->_form->bsDb->countRead($sqlQ);if (isEx($status)) {$status->stackTrace('was here in validateMustBeUnique()', __FILE__, __LINE__);return $status;} elseif ($status == 0) {return TRUE;} else {$this->errorType = 'mustBeUnique';return $this->errorMessage = $this->getErrorMessage('mustBeUnique', array(&$v));}
}
}
return TRUE;}
function getOnEnterBehavior() {if (isSet($this->onEnter)) {return $this->onEnter;}
if ($this->hasFormObject()) {if (isSet($this->_form->onEnter)) {return $this->_form->onEnter;}
}
return 'nothing';}
function getOnEnterCode() {$behavior = $this->getOnEnterBehavior();switch ($behavior) {case 'ignore':
return 'return bsFormNoEnter(event);';case 'tab':
return 'return bsFormEnterToTab(event);';case 'submit':
return 'return bsFormEnterSubmit(event,this.form);';default: if (substr($behavior, 0, 3) === 'js:') {return 'return bsFormHandleEnter(event, \'' . substr($behavior, 3) . '\');';}
return '';}
}
function applyOnEnterBehavior() {$onEnter = $this->getOnEnterCode();if (!empty($onEnter)) {if (empty($this->events['onKeyDown'])) {$this->events['onKeyDown'] = $onEnter;} else {$this->events['onKeyDown'] .= ' ' . $onEnter;}
}
}
function shouldPersist() {$t = $this->getVisibility();if (($t == 'invisible') || ($t == 'normal') || ($t == 'read')) {return TRUE;} else {return FALSE;}
}
function getDbDataType() {if ((isSet($this->dbDataType)) && (!empty($this->dbDataType))) {return $this->dbDataType;} else {if (is_array($this->valueInternal)) return 'blob';switch ($this->fieldType) {case 'text':
case 'password':
case 'radio':
case 'checkbox':
case 'file': case 'select':
if ((isSet($this->maxLength)) && (is_numeric($this->maxLength)) && ($this->maxLength > 255)) {return 'blob';} else {switch ($this->bsDataType) {case 'number':
if (is_numeric($this->valueInternal)) {return 'int';} else {return 'varchar';}
break;case 'boolean':
if (((is_string($this->valueInternal)) && (strlen($this->valueInternal) < 2)) || (is_null($this->valueInternal))) {return 'tinyint';} else {return 'varchar';}
break;default:
return 'varchar';}
}
break;case 'image':
case 'button':
case 'submit':
case 'reset':
return ''; break;case 'hidden': case 'textarea':
case 'wysiwyg':
default: return 'blob';}
}
}
function getDbFieldName() {if ((isSet($this->dbFieldName)) && (!empty($this->dbFieldName))) {return $this->dbFieldName;} else {return 'prefix' . $this->_getFieldNameForHtml($this->name);}
}
function _getMaxLength() {if ((isSet($this->maxLength)) && (!empty($this->maxLength))) {return (int)$this->maxLength;} else {switch ($this->fieldType) {case 'spreadsheet':
case 'wysiwyg':
return 65565;break;}
if (isSet($this->bsDataType)) {switch ($this->bsDataType) {case 'boolean':
return 1; break; case 'number':
return 20; break;case 'text':
return 255; break;case 'blob':
return 65535; break;case 'html':
return 65535; break;case 'email':
return 80; break;case 'url':
return 200; break;case 'username':
return 20; break;case 'password':
return 20; break;case 'zipcode':
return 10; break;case 'price':
return 20; break;case 'creditcard':
return 16; break;case 'ip':
return 15; break;case 'domain':
return 80; break;case 'host':
return 80; break;case 'date':
return 10; break;case 'time':
return 8; break;case 'month':
return 2; break;case 'year':
return 4; break;case 'datetime':
return 19; break;case 'timestamp':
return 8; break;}
}
}
return NULL;}
function _getTagStringEvents() {$ret = '';if (isSet($this->events)) {reset($this->events);while(list($k) = each($this->events)) {if (!empty($this->events[$k])) $ret .= " $k=\"{$this->events[$k]}\"";}
}
return $ret;}
function getValue($explodeKey=NULL) {$ret = ''; $hasFormObject = $this->hasFormObject();if (($hasFormObject) && ($this->_form->step === 1) && (!isSet($this->level) || !isSet($this->_form->level) || ($this->level >= $this->_form->level))) {$valueDefault = '';if ((!is_null($explodeKey)) && (isSet($this->valueDefault) && is_array($this->valueDefault) && isSet($this->valueDefault[$explodeKey]))) {$valueDefault = $this->getLanguageDependentValue($this->valueDefault[$explodeKey]);} else {$valueDefault = (isSet($this->valueDefault)) ? $this->getLanguageDependentValue($this->valueDefault) : '';}
if (isSet($this->valueDefaultType)) {if ($this->valueDefaultType == 'text') {$ret = $valueDefault; } elseif ($this->valueDefaultType == 'field') {$t = $this->_form->getFieldValue($valueDefault); if (isSet($t[0])) $ret .= $t[0];} elseif ($this->valueDefaultType == 'code') {$valueDefault = $this->_evalWrap($valueDefault, 'low');if (!isAlphaNumeric($valueDefault)) $valueDefault = '';$ret .= $valueDefault;} elseif ($this->valueDefaultType == 'array') {$ret = $valueDefault;} } else {$ret .= $valueDefault;}
} elseif (isSet($this->valueDisplay)) {if (!is_null($explodeKey)) {$ret = $this->valueDisplay[$explodeKey];} else {$ret = $this->valueDisplay;}
}
if (is_null($ret)) $ret = '';return $ret;}
function _getTagStringValue($explodeKey=NULL) {$value = $this->getValue($explodeKey);if (is_array($value)) {while (list($k) = each($value)) {$value[$k] = $this->_Bs_HtmlUtil->filterForHtml($value[$k]);}
reset($value);return $value;} else {return $this->_Bs_HtmlUtil->filterForHtml($value);}
}
function _getTagStringStyles() {$ret = '';if (!is_null($t = $this->_getAccessKey())) {$ret .= " accesskey=\"{$t}\"";}
$classArr = array();$aStyle = $this->getAdvancedStyleHelper('field');if ($aStyle) {$classArr[] = $aStyle;}
if (isSet($this->styles)) {if ((isSet($this->styles['id']))       && (!empty($this->styles['id'])))        $ret .= " id=\"{$this->styles['id']}\"";if ((isSet($this->styles['class']))    && (!empty($this->styles['class']))) {$classArr[] = $this->styles['class'];}
if ((isSet($this->styles['style']))    && (!empty($this->styles['style'])))     $ret .= " style=\"{$this->styles['style']}\"";if ((isSet($this->styles['tabIndex'])) && (!empty($this->styles['tabIndex'])))  $ret .= " tabindex=\"{$this->styles['tabIndex']}\"";if (isSet($this->styles['title']) && !is_null($t = $this->getLanguageDependentValue($this->styles['title']))) {$ret .= " title=\"{$t}\"";}
}
if (!empty($classArr)) {$ret .= " class=\"" . join(' ', $classArr) . "\"";}
return $ret;}
function _getTagStringAdditionalTags() {$ret = '';if (isSet($this->additionalTags)) {if (is_array($this->additionalTags)) {$ret .= ' ' . join(' ', $this->additionalTags);} else {$ret .= ' ' . $this->additionalTags;}
}
return $ret;}
function getCaption($useAccessKey=TRUE, $lang=null) {if (is_null($ret = $this->getLanguageDependentValue($this->caption, $lang))) return '';if ($useAccessKey) $ret = $this->_highlightAccessKey($ret); return $ret;}
function getCaptionForFormOutput($useAccessKey=TRUE, $lang=null) {$ret = $this->getCaption($useAccessKey, $lang);$starRight = '';switch ($this->_form->mustFieldsVisualMode) {case 'none':
break;case 'starRight':
if ($this->isMust()) $starRight = ' *';break;default: if ($this->isMust()) $ret .= '* ';}
$ret .= $starRight;$aStyle = $this->getAdvancedStyleHelper();if ($aStyle) {$ret = "<span class=\"{$aStyle}\">{$ret}</span>";}
return $ret;}
function getError() {if (isSet($this->errorMessage)) return $this->errorMessage;return '';}
function getHelp() {return '';}
function _highlightAccessKey($string) {$ret = $string;do { if ((is_null($accessKey = $this->_getAccessKey($string))) || ($accessKey == '')) break;$pos = 'default'; if (($pos = strpos(strToLower($string), strToLower($accessKey))) === FALSE) break; if (($this->hasFormObject()) && (isSet($this->_form->accessKeyTags)) && (is_array($this->_form->accessKeyTags)) && (sizeOf($this->_form->accessKeyTags) == 2) && (!empty($this->_form->accessKeyTags[0]))) {$t = $this->_form->accessKeyTags;} else {$t = array('<u>', '</u>');}
$ret  = substr($string, 0, $pos);$ret .= $t[0];$ret .= substr($string, $pos, 1);$ret .= $t[1];$ret .= substr($string, $pos +1);} while (FALSE);return $ret;}
function _getAccessKey($caption=NULL) {if ((isSet($this->styles['accessKey'])) && (!is_null($t = $this->getLanguageDependentValue($this->styles['accessKey'])))) 
return $t;if (($this->hasFormObject()) && (@$this->_form->useAccessKeys == TRUE)) {if (is_null($caption)) $caption = $this->getCaption(FALSE); if (strlen($caption) > 0) return $caption[0];}
return NULL;}
function getFieldAsHidden($explodeKey=NULL) {$ret  = "<input type=\"hidden\"";if (!is_null($explodeKey)) {$ret .= " name=\"" . $this->_getFieldNameForHtml($this->name) . "[{$explodeKey}]\"";} else {$ret .= " name=\"" . $this->_getFieldNameForHtml($this->name) . "\"";}
$ret .= " value=\"" . $this->_getTagStringValue($explodeKey) . "\"";$ret .= '>'; return $ret;}
function _getFieldNameForHtml($name) {return str_replace("'", '', $name);}
function hasJavascript() {if ($this->hasFormObject()) {return parent::hasJavascript();} else {return NULL;}
}
function isMust($useCached=TRUE, $returnString=FALSE) {$ret = '';do {if (($useCached) && (isSet($this->_must))) {$ret = $this->_must;break;}
if ((isSet($this->must)) && ($this->must)) {$ret = 'must';break;}
if ($this->_anyIfCase(@$this->mustIf)) {$ret = 'mustIf';break;}
$must = FALSE; if (@is_array($this->mustOneOf)) {$must = TRUE;$ret  = 'mustOneOf';if ($this->hasFormObject()) {foreach($this->mustOneOf as $otherMustOneOfField) {if ($this->_form->isFieldFilledIn($otherMustOneOfField)) {$ret = '';break;}
}
}
break;}
$must = FALSE; if (isSet($this->mustOneOfIf) && is_array($this->mustOneOfIf) && isSet($this->mustOneOfIf['condition'])) {$must = TRUE;if ($this->hasFormObject()) {while(list($k) = each($this->mustOneOfIf['fields'])) {if ($this->_form->isFieldFilledIn($this->mustOneOf['fields'][$k])) {$ret = '';break;}
}
}
if ($this->_anyIfCase($this->mustOneOfIf['condition'])) {$ret = 'mustOneOfIf';break;}
}
$ret = '';break;} while (FALSE);if ($returnString) {return $ret;} else {return (bool)$ret;}
}
function _anyIfCase($myIf) {if (is_array($myIf) && !empty($myIf)) {$i = 0;reset($myIf);while (list($k) = each($myIf)) {$stack[$i]['operator'] = (isSet($myIf[$k]['operator'])) ? $myIf[$k]['operator'] : '|';$stack[$i]['compare']  = (isSet($myIf[$k]['compare']))  ? $myIf[$k]['compare'] : '=';$stack[$i]['boolean']  = FALSE; do { if (isSet($myIf[$k]['value'])) {$t = $this->_form->getFieldValue($myIf[$k]['field']);if (is_array($t)) {switch ($stack[$i]['compare']) {case '=':
$stack[$i]['boolean'] = (bool)($t[0] == $myIf[$k]['value']);break;case '>':
$stack[$i]['boolean'] = (bool)($t[0] > $myIf[$k]['value']);break;case '<':
$stack[$i]['boolean'] = (bool)($t[0] < $myIf[$k]['value']);break;case '>=':
$stack[$i]['boolean'] = (bool)($t[0] >= $myIf[$k]['value']);break;case '<=':
$stack[$i]['boolean'] = (bool)($t[0] <= $myIf[$k]['value']);break;case '!=':
case '<>': $stack[$i]['boolean'] = (bool)($t[0] != $myIf[$k]['value']);break;case 's': $stack[$i]['boolean'] = (bool)(soundex($t[0]) == soundex($myIf[$k]['value']));break;case '!s': $stack[$i]['boolean'] = (bool)(soundex($t[0]) != soundex($myIf[$k]['value']));break;default:
}
break;}
} else {$stack[$i]['boolean'] = (bool)$this->_form->isFieldFilledIn($myIf[$k]['field']); break;}
} while (FALSE);$i++;}
if (is_array($stack) && !empty($stack)) {$eval = '';while (list($k) = each($stack)) {if ($k > 0) $eval .= ($stack[$k]['operator'] == '&') ? 'AND ' : 'OR ';$eval .= ($stack[$k]['boolean']) ? 'TRUE ' : 'FALSE ';}
$eval = 'return (bool)(' . $eval . ');';if (evalWrap($eval) === TRUE) {return TRUE;}
}
}
return FALSE;}
function _markAsUsed() {$this->isUsed = TRUE;}
function _getBsDataInfoNumber() {$ret = array(null, null);do { $t = explode('|', $this->bsDataInfo); if (!empty($t[0])) { $ret[0] = $t[0];}
if (!empty($t[1])) { $ret[1] = $t[1];}
} while (FALSE);return $ret;}
function isFilledIn() {if (isSet($this->valueInternal)) {if (is_array($this->valueInternal)) {return (sizeOf($this->valueInternal) > 0);}
if (is_string($this->valueInternal)) {return ($this->valueInternal != '');}
return TRUE;}
return FALSE;}
function _evalWrap($string, $security='high', $suppressErrors=FALSE, $params=array()) {$params['this'] = &$this;return evalWrap(&$string, &$security, &$suppressErrors, &$params);}
function getErrorMessage($errorType='', $params=array(), $lang=NULL) {if (is_null($lang)) {if (($this->hasFormObject()) && (isSet($this->_form->language))) {$lang = $this->_form->language;} else {$lang = 'en'; }
}
if (isSet($this->defaultErrorMessage[$lang][$errorType])) {$evalMsg = $this->defaultErrorMessage[$lang][$errorType];} else {if (!isSet($hash)) static $hash;if (!isSet($hash[$lang])) {$Bs_LanguageHandler =& new Bs_LanguageHandler();$t = &$Bs_LanguageHandler->determineLanguage($GLOBALS['APP']['path']['core'] . 'html/form/lang/validationErrors', $lang);if (is_null($t)) return 'unknown error'; list($lang, $path) = $t;if (!isSet($hash[$lang])) {$hash[$lang] = &$Bs_LanguageHandler->readLanguage($path);}
}
if (isSet($hash[$lang][$this->fieldType][$errorType])) {$evalMsg = $hash[$lang][$this->fieldType][$errorType];} elseif (isSet($hash[$lang]['default'][$errorType])) {$evalMsg = $hash[$lang]['default'][$errorType];} else {return 'unknown error'; }
}
if (sizeOf($params) > 0) {$beenHere = FALSE;while (list($k) = each($params)) {if ($beenHere) {$paramString .= ', ';} else {$beenHere = TRUE;}
$paramString .= "\$params[$k]";}
$evalStr = "return sprintf(\$evalMsg, {$paramString});";$t = @eval($evalStr);if ((is_string($t)) && (!empty($t))) return $t;return $evalMsg; } else {return $evalMsg; }
}
}
?>