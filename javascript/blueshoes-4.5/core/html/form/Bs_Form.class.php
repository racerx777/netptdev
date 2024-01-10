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
define('BS_FORM_VERSION',      '4.5.$Revision: 1.8 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormContainer.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core'] . 'util/Bs_Array.class.php');require_once($APP['path']['core'] . 'text/Bs_LanguageHandler.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormTemplateParser.class.php');require_once($APP['path']['core'] . 'net/Bs_Url.class.php');require_once($APP['path']['core'] . 'text/Bs_TextUtil.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormElement.class.php');bs_lazyLoadPackage('html/form/specialfields');bs_lazyLoadPackage('html/form/domapi');function &bs_fabricateFormField($arr) {$fieldType = (isSet($arr['fieldType'])) ? $arr['fieldType'] : 'Bs_FormFieldText';unset($arr['fieldType']); if (!isSet($arr['name']))        $arr['name']        = 'noname';if (!isSet($arr['editability'])) $arr['editability'] = 'always';$field =& new $fieldType;if (!isSet($arr['caption'])) {if (!isSet($field->caption)) { $arr['caption']     = $GLOBALS['Bs_String']->studlyCapsToSeparated($arr['name']); }
}
foreach ($arr as $key => $value) {$field->$key = $value;}
return $field;}
class Bs_Form extends Bs_Object {var $persister = NULL;var $persisterID;var $persisterVarSettings = array('internalName'         => array('mode'=>'lonely',        'metaType'=>'string',      'index'=>TRUE), 
'name'                 => array('mode'=>'stream'), 
'action'               => array('mode'=>'stream'), 
'method'               => array('mode'=>'stream'), 
'encType'              => array('mode'=>'stream'), 
'target'               => array('mode'=>'stream'), 
'styles'               => array('mode'=>'stream'), 
'advancedStyles'       => array('mode'=>'stream'), 
'useAccessKeys'        => array('mode'=>'stream'), 
'accessKeyTags'        => array('mode'=>'stream'), 
'direction'            => array('mode'=>'stream'), 
'events'               => array('mode'=>'stream'), 
'onEnter'              => array('mode'=>'stream'), 
'disabledMode'         => array('mode'=>'stream'), 
'mustFieldsVisualMode' => array('mode'=>'stream'), 
'useTemplate'          => array('mode'=>'stream'), 
'templatePath'         => array('mode'=>'stream'), 
'buttons'              => array('mode'=>'stream'), 
'useJsFile'            => array('mode'=>'stream'), 
'jumpToFirstError'     => array('mode'=>'stream'), 
'saveToDb'             => array('mode'=>'stream'), 
'dbDsn'                => array('mode'=>'stream',      'crypt'=>TRUE), 
'dbName'               => array('mode'=>'stream'), 
'dbTableName'          => array('mode'=>'stream'), 
'additionalParams'     => array('mode'=>'stream'), 
'sendMailRaw'          => array('mode'=>'stream'), 
'mailRawTo'            => array('mode'=>'stream'), 
'mailRawCc'            => array('mode'=>'stream'), 
'mailRawBcc'           => array('mode'=>'stream'), 
'mailRawSubject'       => array('mode'=>'stream'), 
'sendMailNice1'        => array('mode'=>'stream'), 
'mailNice1To'          => array('mode'=>'stream'), 
'mailNice1Cc'          => array('mode'=>'stream'), 
'mailNice1Bcc'         => array('mode'=>'stream'), 
'mailNice1Subject'     => array('mode'=>'stream'), 
'mailNice1Template'    => array('mode'=>'stream')
);var $bsDb;var $_Bs_Array;var $_Bs_HtmlUtil;var $Bs_TextUtil;var $_APP;var $name;var $action;var $method;var $encType;var $target;var $styles;var $advancedStyles;var $useAccessKeys;var $accessKeyTags;var $direction;var $events;var $onEnter;var $disabledMode;var $mustFieldsVisualMode = 'starLeft';var $useTemplate;var $templatePath;var $elementLayouts;var $errorTableLayout = '';var $skinName;var $skinPath;var $buttons;var $internalName;var $saveToDb;var $dbDsn;var $dbName;var $dbTableName;var $additionalParams;var $sendMailRaw;var $mailRawTo;var $mailRawCc;var $mailRawBcc;var $mailRawSubject;var $sendMailNice1;var $mailNice1To;var $mailNice1Cc;var $mailNice1Bcc;var $mailNice1Subject;var $mailNice1Template;var $_guiLangHash;var $clearingHouse;var $fieldsUsed;var $serializeType = 'php'; var $md5Key;var $elementContainer;var $hasJavascript;var $errors;var $mode;var $level;var $_prevLevel;var $_nextLevel;var $step = 1;var $state = 'form';var $viewCount = 1;var $startTimestamp;var $usedTime;var $language;var $recordId;var $user;var $useJsFile = TRUE;var $_includeOnce = NULL;var $_onLoad = NULL;var $_inHead = NULL;var $jumpToFirstError = FALSE;function Bs_Form() {parent::Bs_Object(); $this->bsDb         = &$GLOBALS['bsDb'];$this->_Bs_Array    = &$GLOBALS['Bs_Array'];$this->_Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil'];$this->Bs_TextUtil  = &$GLOBALS['Bs_TextUtil'];$this->_APP         = &$GLOBALS['APP'];$this->md5Key       = (@!empty($this->_APP['forms']['md5Key'])) ? $this->_APP['forms']['md5Key'] : 'Von der Wiege bis zur Bahre: Formulare';static $_guiLangHash = array();$this->_guiLangHash  = &$_guiLangHash;$this->elementContainer =& new Bs_FormContainer();$this->elementContainer->setFormObject($this);$this->elementContainer->pseudoContainer = TRUE;}
function doItYourself($postData=NULL) {if (is_null($postData)) {if (!empty($_POST)) {$postData = &$_POST;}
}
$iHaveBeenPosted = (!empty($postData) && isSet($postData['bs_form']) && ($postData['bs_form']['name'] == $this->internalName) && ($postData['bs_form']['step'] == 2));if ($iHaveBeenPosted) {$this->setReceivedValues($postData);}
$this->postLoadTrigger();if ($iHaveBeenPosted) {$isOk = $this->validate();if ($isOk && $this->isLastLevel(TRUE)) {return TRUE;} else {return $this->getAll();}
} else {return $this->getAll();}
}
function getAll($includeOnceHow='array', $onLoadWithCodeTags=FALSE) {$ret = array();$ret['form']    = $this->getForm(FALSE);$ret['include'] = $this->getIncludeOnce($includeOnceHow);$ret['onLoad']  = $this->getOnLoadCode($onLoadWithCodeTags);$ret['head']    = $this->getInHeadCode();if (isSet($this->errors) && ($this->state == 'form') && ($this->step == 2)) {$ret['errors'] = $this->getErrorTable($this->getInterfaceText('errorsOccured'));}
return $ret;}
function &getForm($withErrorTable=FALSE) {do { if (!isSet($this->useTemplate) || (!$this->useTemplate)) break;$tParser =& new Bs_FormTemplateParser();if (isSet($this->templatePath)) $tParser->templatePath = $this->templatePath;$options = array();$options['mode']  = $this->getMode();$options['state'] = $this->state;if ((isSet($this->user))     && (!empty($this->user)))     $options['user']     = $this->user;if ((isSet($this->language)) && (!empty($this->language))) $options['language'] = $this->language;$status = $tParser->loadTemplate($this->internalName, 'form', $options);if (!($status === TRUE)) break;$status = $tParser->parse();$status = $tParser->apply(NULL, &$this);return $tParser->templateString;} while (FALSE);if (isSet($this->skinName)) {if (isSet($this->skinPath)) {$dev0 = $this->loadSkin($this->skinName, $this->skinPath);} else {$dev0 = $this->loadSkin($this->skinName);}
}
$ret  = '';switch ($this->state) { case 'done':
$ret .= 'thank you. your form has been submitted successfully.';return $ret;case 'preview':
return 'under construction. sorry.';default: if (($withErrorTable) && (isSet($this->errors)) && ($this->state == 'form') && ($this->step == 2)) {$ret .= $this->getErrorTable($this->getInterfaceText('errorsOccured'));}
$tempRet = $this->elementContainer->getElement(@$this->level);$ret .= $this->getFormHead();$ret .= $tempRet;$ret .= $this->_getButtonString();$ret .= $this->getFormFoot();return $ret;}
}
function getInfo() {$ret = array();$ret['viewCount']      = $this->viewCount -1;$ret['startTimestamp'] = $this->startTimestamp;$ret['usedTime']       = $this->usedTime;$ret['language']       = $this->language;return $ret;}
function getFormTag() {$ret  = '';$ret .= '<form';if ((isSet($this->name)) && ($this->name != '')) 
$ret .= " name=\"{$this->name}\"";if ((isSet($this->action)) && ($this->action != '')) {$ret .= " action=\"{$this->action}\"";} else {$ret .= " action=\"" . Bs_Url::getUrlJunk('8') . "\"";}
if ((isSet($this->action)) && (strToLower($this->action) == 'get')) {$ret .= " method=\"get\"";} else {$ret .= " method=\"post\"";}
if ($this->isMultipart()) {$ret .= " enctype=\"multipart/form-data\"";} elseif ((isSet($this->encType)) && ($this->encType != '')) {$ret .= " enctype=\"{$this->enctype}\"";}
if ((isSet($this->target)) && ($this->target != '')) 
$ret .= " target=\"{$this->target}\"";if (isSet($this->events) && is_array($this->events)) {reset($this->events);while (list($k) = each($this->events)) {$ret .= " {$k}=\"{$this->events[$k]}\"";}
}
$ret .= ">\n";return $ret;}
function getFormHead() {$ret  = '';if ($this->useJsFile) {$this->addIncludeOnce('/_bsJavascript/core/form/Bs_FormUtil.lib.js');}
$ret .= $this->getFormTag();$ret .= "<input type=\"hidden\" name=\"bs_form[name]\" value=\"" . $this->internalName . "\">\n";$ret .= "<input type=\"hidden\" name=\"bs_form[mode]\" value=\"" . $this->mode . "\">\n";$ret .= "<input type=\"hidden\" name=\"bs_form[state]\" value=\"" . $this->state . "\">\n";$ret .= "<input type=\"hidden\" name=\"bs_form[level]\" value=\"" . ((isSet($this->level)) ? $this->level : '') . "\">\n";$ret .= "<input type=\"hidden\" name=\"bs_form[step]\" value=\"2\">\n";$t = (isSet($this->recordId)) ? $this->recordId : '';$ret .= "<input type=\"hidden\" name=\"bs_form[recordId]\" value=\"" . $t . "\">\n";$ret .= "<input type=\"hidden\" name=\"bs_form[viewCount]\" value=\"" . $this->viewCount . "\">\n";if (!isSet($this->startTimestamp)) $this->startTimestamp = time();$ret .= "<input type=\"hidden\" name=\"bs_form[startTimestamp]\" value=\"" . $this->startTimestamp . "\">\n";$ret .= "<input type=\"hidden\" name=\"bs_form[language]\" value=\"" . $this->language . "\">\n";if (isSet($this->user)) $ret .= "<input type=\"hidden\" name=\"bs_form[user]\" value=\"" . $this->user . "\">\n";$ret .= '<bs_after_formopen_tag/>';return $ret;}
function getFormFoot() {$ret  = '';$ret .= $this->getUsedFields();if (isSet($this->level) && ($this->level > 1)) {$useTheseLevels = array();for ($i=1; $i<$this->level; $i++) {$useTheseLevels[] = $i;}
$valuesFromPreviousSteps = $this->getValuesArray(FALSE, 'valueInternal', FALSE, null, FALSE, $useTheseLevels);while (list($k) = each($valuesFromPreviousSteps)) {$ret .= '<input type="hidden" name="' . $k . '" value="' . $this->_Bs_HtmlUtil->filterForHtml($valuesFromPreviousSteps[$k]) . '">' . "\n";}
}
$ret .= '<bs_before_formclose_tag/>';$ret .= "</form>\n";if ($this->jumpToFirstError) {if (isSet($this->errors) && !empty($this->errors)) {reset($this->errors);$aolc = "bsFormJumpToFirstError('" . key($this->errors) . "', '{$this->name}', true);\n";$this->addOnLoadCode($aolc);}
}
return $ret;}
function getUsedFields() {$this->seedClearingHouse();$currentKey = key($this->clearingHouse);reset($this->clearingHouse);$fieldArray = array();while (list($k) = each($this->clearingHouse)) {if ($this->clearingHouse[$k]->elementType == 'field') {if ($this->clearingHouse[$k]->isUsed) {$fieldArray[] = $this->clearingHouse[$k]->name;}
}
}
$this->_Bs_Array->setPos($this->clearingHouse, $currentKey);if ($this->serializeType == 'php') {$serial = serialize($fieldArray);} else { $t = $fieldArray;$packet_id = wddx_packet_start('PHP');wddx_add_vars($packet_id, 'fieldArray');$serial = wddx_packet_end($packet_id);}
$md5key = $this->_Bs_HtmlUtil->filterForHtml(md5($serial . $this->md5Key));$serial = $this->_Bs_HtmlUtil->filterForHtml($serial);$ret  = "<input type=\"hidden\" name=\"bs_form[fields]\" value=\"" . $serial . "\">\n";$ret .= "<input type=\"hidden\" name=\"bs_form[fieldsMd5]\" value=\"" . $md5key . "\">\n";return $ret;}
function loadSkin($skinName, $basePath=NULL) {if (is_null($basePath)) $basePath = $this->_APP['path']['core'] . 'html/form/skin/';$fullPath = $basePath . $skinName . '/skin.php';if (file_exists($fullPath) && is_readable($fullPath)) {$fContent = join('', file($fullPath));$t = eval($fContent);if (is_array($t)) {$this->elementLayouts = $t;return TRUE;}
}
return FALSE;}
function _getButtonString() {if (@$this->buttons === 'default') {$buttons = array(
'view'      => array(
'edit'      => array('en'=>'edit',     'de'=>'bearbeiten'), 
'delete'    => array('en'=>'delete',   'de'=>'löschen'), 
'cancel'    => array('en'=>'overview', 'de'=>'übersicht') 
), 
'edit'      => array(
'save'      => array('en'=>'save',     'de'=>'speichern'), 
'cancel'    => array('en'=>'cancel',   'de'=>'abbrechen'), 
'next'      => array('en'=>'next',     'de'=>'weiter'), 
'back'      => array('en'=>'back',     'de'=>'zurück')
), 
'add'       => array(
'save'      => array('en'=>'add',      'de'=>'hinzufügen'), 
'cancel'    => array('en'=>'cancel',   'de'=>'abbrechen'), 
'next'      => array('en'=>'next',     'de'=>'weiter'), 
'back'      => array('en'=>'back',     'de'=>'zurück')
), 
'delete'    => array(
'save'      => array('en'=>'delete',   'de'=>'löschen'), 
'cancel'    => array('en'=>'cancel',   'de'=>'abbrechen') 
), 
'search'    => array(
'go'        => array('en'=>'search',   'de'=>'suchen')
)
);} elseif (@is_array($this->buttons)) {$buttons = $this->buttons;} else {return '';}
$ret = '';$mode = $this->getMode();if (!isSet($buttons[$mode])) return '';$t = $buttons[$mode];if (isSet($this->level) && ($this->level > 0)) { if (isSet($t['next'])) {$ret .= '<input type="submit" name="bs_form[btnNext]" value="' . $this->Bs_TextUtil->getLanguageDependentValue($t['next'],   $this->language) . '">';}
if (!$this->isFirstLevel()) {if (isSet($t['back'])) {$ret .= '<input type="submit" name="bs_form[btnBack]" value="' . $this->Bs_TextUtil->getLanguageDependentValue($t['back'],   $this->language) . '">';}
}
if (isSet($t['cancel'])) {$ret .= '<input type="submit" name="bs_form[btnCancel]" value="' . $this->Bs_TextUtil->getLanguageDependentValue($t['cancel'], $this->language) . '">';}
} else {while (list($k) = each($t)) {switch ($k) {case 'save':
$ret .= '<input type="submit" name="bs_form[btnSubmit]" value="' . $this->Bs_TextUtil->getLanguageDependentValue($t[$k], $this->language) . '">';break;case 'cancel':
$ret .= '<input type="submit" name="bs_form[btnCancel]" value="' . $this->Bs_TextUtil->getLanguageDependentValue($t[$k], $this->language) . '">';break;case 'edit':
$ret .= '<input type="submit" name="bs_form[btnEdit]" value="'   . $this->Bs_TextUtil->getLanguageDependentValue($t[$k], $this->language) . '">';break;case 'delete':
$ret .= '<input type="submit" name="bs_form[btnDelete]" value="' . $this->Bs_TextUtil->getLanguageDependentValue($t[$k], $this->language) . '">';break;default:
}
}
}
return $ret;}
function isMultiLevel() {return (isSet($this->level) && is_numeric($this->level));}
function getNumbersOfLevels() {if (!isSet($this->level)) return 1;$ret = 1;foreach ($this->elementContainer->formElements as $container) {$level = $container->getLevel();if ($level > $ret) $ret = $level;}
return $ret;}
function isFirstLevel() {return (!($this->level > 1));}
function isLastLevel($afterSubmit=TRUE) {$numberOfLevels = $this->getNumbersOfLevels();if ($numberOfLevels == 1) return TRUE;if ($afterSubmit) {return ($this->level > $numberOfLevels);} else {return ($this->level == $numberOfLevels);}
}
function isMultipart() {if ((isSet($this->encType)) && (strToLower($this->encType) == 'multipart/form-data')) 
return TRUE;return (bool)($this->elementContainer->hasFileFieldElement());}
function getMode() {if (isSet($this->mode)) return $this->mode;return '';}
function hasJavascript() {return NULL;}
function getElement($elementName, $what='element', $optionList=null) {$this->seedClearingHouse();reset($this->clearingHouse);if (isSet($this->clearingHouse[$elementName])) {switch ($what) {case 'caption':
if ($this->clearingHouse[$elementName]->elementType == 'field') {return $this->clearingHouse[$elementName]->getCaptionForFormOutput();} elseif ($this->clearingHouse[$elementName]->elementType == 'container') {return $this->clearingHouse[$elementName]->getCaption();}
break;case 'text':
if (($this->clearingHouse[$elementName]->elementType == 'field') && ($this->clearingHouse[$elementName]->fieldType == 'checkbox')) 
return $this->clearingHouse[$elementName]->getFieldText();break;case 'error':
if ($this->clearingHouse[$elementName]->elementType == 'field') return $this->clearingHouse[$elementName]->getError();break;case 'help':
if ($this->clearingHouse[$elementName]->elementType == 'field') return $this->clearingHouse[$elementName]->getHelp();break;default: return $this->clearingHouse[$elementName]->getElement($optionList);}
} else {return '';}
}
function validate() {unset($this->errors);$isOk = TRUE;$this->seedClearingHouse();reset($this->clearingHouse);while (list($k) = each($this->clearingHouse)) {if ($this->clearingHouse[$k]->elementType == 'field') {if (in_array($k, $this->fieldsUsed)) $this->clearingHouse[$k]->inputManipulate();}
}
reset($this->clearingHouse);while(list($k) = each($this->clearingHouse)) {if ($this->clearingHouse[$k]->elementType == 'field') {if (in_array($k, $this->fieldsUsed)) {$this->clearingHouse[$k]->postManipulateTrigger();}
}
}
if (($this->mode !== 'delete') && ($this->mode !== 'view')) { $err  = array();reset($this->clearingHouse);while (list($k) = each($this->clearingHouse)) {if ($this->clearingHouse[$k]->elementType == 'field') {if (in_array($k, $this->fieldsUsed)) {$status = $this->clearingHouse[$k]->inputValidate();if (is_string($status)) {$isOk    = FALSE;$err[$k] = array($this->clearingHouse[$k]->getCaption(FALSE), $status);}
}
}
}
}
if ($isOk) {if (!$this->isMultiLevel() || $this->isLastLevel(TRUE)) {$this->step = 2;$this->state    = 'done'; $this->usedTime = time() - $this->startTimestamp; } else {$this->step = 1;}
if ($this->isMultiLevel()) {$this->level++;}
return TRUE;} else {$this->step   = 2;$this->errors = &$err;return FALSE;}
}
function getErrorTable($title=NULL, $style='default') {if (!((isSet($this->errors)) && (is_array($this->errors)))) return '';$ret = $errTbl = "\n";if ($style == 'default') {$errTbl .= "<table border='0' cellpadding='10' cellspacing='0' width='100%'><tr><td>\n";$errTbl .= "<table border=0 cellpadding='2' cellspacing=0>\n";foreach($this->errors as $error) {if (is_array($error)) {$errTbl .= "<tr><td valign='top' align='left'>{$error[0]}:</td><td valign='top' align='left'>{$error[1]}</td></tr>\n";} else {$errTbl .= "<tr><td valign='top' align='left' colspan='2'>{$error}</td></tr>\n";}
}
$errTbl .= "</table>";$errTbl .= "</td></tr></table>\n";if (is_string($title)) {$ret .= "<fieldset><legend>" . $title . "</legend>";} else {$ret .= "<fieldset>";}
$ret .= empty($this->errorTableLayout) ? $errTbl : str_replace('__*__', $errTbl, $this->errorTableLayout);$ret .= "</fieldset>\n";}
return $ret;}
function addError($errorMsg, $fieldName=NULL, $fieldCaption=NULL) {$this->state = 'form';if (is_null($fieldName)) {$this->errors[] = $errorMsg;} else {$this->errors[$fieldName] = array($fieldCaption => $errorMsg);}
}
function isFieldFilledIn($fieldName) {$this->seedClearingHouse();if (!isSet($this->clearingHouse[$fieldName])) return NULL;if ($this->clearingHouse[$fieldName]->elementType != 'field') return NULL;return $this->clearingHouse[$fieldName]->isFilledIn();}
function getFieldValue($fieldName, $valueType='internal') {$this->seedClearingHouse();if (!isSet($this->clearingHouse[$fieldName])) return NULL;if ($this->clearingHouse[$fieldName]->elementType != 'field') return NULL;switch ($valueType) {case 'default':
return array($this->clearingHouse[$fieldName]->valueDefault);break;case 'received':
return array($this->clearingHouse[$fieldName]->valueReceived);break;case 'display':
return array($this->clearingHouse[$fieldName]->valueDisplay);break;default: return array($this->clearingHouse[$fieldName]->valueInternal);}
}
function setReceivedValues(&$postData) {if (get_magic_quotes_gpc()) {$array = $postData;bs_recursiveStripSlashes($array); } else {$array = $postData;}
$this->seedClearingHouse();if (isSet($array['bs_form'])) {$status = $this->setBsFormData($array['bs_form']);if (!$status) {return new Bs_Exception('the hidden field information was removed from the form.', __FILE__, __LINE__, '', 'fatal');}
}
reset($this->clearingHouse);while(list($k) = each($this->clearingHouse)) {if ($this->clearingHouse[$k]->elementType == 'field') {$fieldNameExploded = explode('[', $k);if (sizeOf($fieldNameExploded) == 1) {$valueReceived = isSet($array[$k]) ? $array[$k] : NULL; } else {$evalVar = 'return $array';foreach ($fieldNameExploded as $fieldNameJunk) {$fieldNameJunk = str_replace(']', '', $fieldNameJunk);$evalVar .= '["' . $fieldNameJunk . '"]';}
$valueReceived = eval($evalVar . ';');}
if (in_array($k, $this->fieldsUsed)) {if ($this->clearingHouse[$k]->fieldType == 'file') {if (isSet($_FILES[$k]['name']) && (!empty($_FILES[$k]['name']))) {$this->clearingHouse[$k]->valueReceived = $_FILES[$k]['name'];} elseif (isSet($array[$k . '_pData'])) {$this->clearingHouse[$k]->setValueFromPreviousSubmit($array[$k . '_pData'], $array[$k . '_pKey']);} else {$this->clearingHouse[$k]->valueReceived = '';}
} else {$this->clearingHouse[$k]->valueReceived = $valueReceived;}
} elseif (($this->level > 1) && $this->isMultiLevel() && ($this->clearingHouse[$k]->getLevel() < $this->level)) {$this->clearingHouse[$k]->valueReceived = $valueReceived;$this->clearingHouse[$k]->valueDisplay  = $valueReceived;$this->clearingHouse[$k]->valueInternal = $valueReceived;}
}
}
reset($this->clearingHouse);while(list($k) = each($this->clearingHouse)) {if ($this->clearingHouse[$k]->elementType == 'field') {if (in_array($k, $this->fieldsUsed)) {$this->clearingHouse[$k]->postReceiveTrigger();}
}
}
return TRUE;}
function setLoadedValues(&$array) {$this->step = 1;$this->seedClearingHouse();reset($this->clearingHouse);while(list($k) = each($this->clearingHouse)) {if ($this->clearingHouse[$k]->elementType == 'field') {$this->clearingHouse[$k]->valueDefault = $array[$k];}
}
}
function setValuesToDefault() {$this->seedClearingHouse();reset($this->clearingHouse);while(list($k) = each($this->clearingHouse)) {if ($this->clearingHouse[$k]->elementType == 'field') $this->clearingHouse[$k]->valueInternal = $this->clearingHouse[$k]->valueDefault;if ($this->clearingHouse[$k]->elementType == 'field') $this->clearingHouse[$k]->valueDisplay  = $this->clearingHouse[$k]->valueDefault;}
}
function setBsFormData(&$hash) {if (is_array($hash)) {if (isSet($hash['viewCount']))      $this->viewCount      = ((int)$hash['viewCount']) + 1; if (isSet($hash['startTimestamp'])) $this->startTimestamp = $hash['startTimestamp'];if (isSet($hash['mode']))           $this->mode           = $hash['mode'];if (isSet($hash['language']))       $this->language       = $hash['language'];if (isSet($hash['recordId']))       $this->recordId       = $hash['recordId'];if (isSet($hash['user']))           $this->user           = $hash['user'];if (isSet($hash['state']))          $this->state          = $hash['state'];if (isSet($hash['level']))          $this->level          = $hash['level'];$isOk = FALSE;do { if (!isSet($hash['fieldsMd5'])) break; if (!isSet($hash['fields']))    break; if ($this->serializeType == 'php') {$t = $hash['fields'];$this->fieldsUsed = unserialize($t);} else { $t                = wddx_deserialize($hash['fields']);$this->fieldsUsed = $t['fieldArray'];}
$compareMd5 = md5($hash['fields'] . $this->md5Key);if ($compareMd5 !== $hash['fieldsMd5']) break;$isOk = TRUE;} while (FALSE);if (!$isOk) {return FALSE;}
return TRUE;}
return FALSE; }
function postLoadTrigger() {$this->seedClearingHouse();reset($this->clearingHouse);while(list($k) = each($this->clearingHouse)) {if ($this->clearingHouse[$k]->elementType === 'field') $this->clearingHouse[$k]->unpersistTrigger();}
}
function seedClearingHouse($onlyIfNotSet=TRUE) {if ((!$onlyIfNotSet) || (!isSet($this->clearingHouse)) || ((is_array($this->clearingHouse)) && (sizeOf($this->clearingHouse) == 0))) {$this->clearingHouse = $this->elementContainer->seedClearingHouse();}
}
function addIncludeOnce($scriptUrl) {if (@is_array($this->_includeOnce) && in_array($scriptUrl, $this->_includeOnce)) {return FALSE;} else {$this->_includeOnce[] = $scriptUrl;return TRUE;}
}
function getIncludeOnce($how='array') {if (!is_array($this->_includeOnce)) $this->_includeOnce = array();reset($this->_includeOnce);if ($how == 'string') {return $this->includeOnceToHtml($this->_includeOnce);} else {return $this->_includeOnce;}
}
function includeOnceToHtml($includeOnce) {if (empty($includeOnce) || !is_array($includeOnce)) return '';$ret = '';foreach ($includeOnce as $k=>$v) {if ((getType($k) == 'string') && (getType($v) != 'string')) {$v = $k;}
$fileType = (substr($v, -4) == '.css') ? 'css' : 'js';switch ($fileType) {case 'js':
$ret .= '<script type="text/javascript" language="JavaScript" src="' . $v . '"></script>';break;case 'css':
$ret .= '<link rel="stylesheet" href="' . $v . '" />';break;}
}
return $ret;}
function addOnLoadCode($code) {$this->_onLoad[] = $code;return TRUE;}
function getOnLoadCode($withCodeTags=TRUE) {if (is_array($this->_onLoad) && !empty($this->_onLoad)) {if ($withCodeTags) {return $this->onLoadCodeToHtml(join("\n", $this->_onLoad));} else {return join("\n", $this->_onLoad);}
} else {return '';}
}
function onLoadCodeToHtml($onLoadCode) {if (empty($onLoadCode)) return ''; $ret = '';$ret .= "<script language='JavaScript' type='text/javascript'>\n";$ret .= "<!--\n";$ret .= "onload=function() {\n";$ret .= $onLoadCode;$ret .= "}\n";$ret .= "// -->\n";$ret .= "</script>\n";return $ret;}
function addIntoHead($code) {$this->_inHead[] = $code;}
function getInHeadCode() {if (empty($this->_inHead) || @!is_array($this->_inHead)) return '';return join("\n", $this->_inHead);}
function addHiddenData($data, $varName='') {if (!is_array($data)) return;foreach($data as $key => $val) {if (!empty($varName)) {$fieldName = $varName . '[' . $key . ']';} else {$fieldName = $key;}
if (is_array($val)) {$ret .= $this->addHiddenData($val, $fieldName);} else {$fieldVal  = $this->_Bs_HtmlUtil->filterForHtml($val);unset($field);$field =& new Bs_FormFieldHidden();$field->name         = $fieldName;$field->valueDefault = $fieldVal;$this->elementContainer->addElement($field);}
}
return $ret;}
function setPersisterID($persisterID) {$this->persisterID = (int)$persisterID;$this->seedClearingHouse();reset($this->clearingHouse);while(list($k) = each($this->clearingHouse)) {$this->clearingHouse[$k]->FormID = $FormID;}
}
function persist($withElements=TRUE) {$status = $this->persister->persist();if (isEx($status)) {$status->stackTrace('was here in persist()', __FILE__, __LINE__);return $status;}
if ($withElements) {$status = $this->elementContainer->persist();if (isEx($status)) {$status->stackTrace('was here in persist()', __FILE__, __LINE__);return $status;}
}
return TRUE;}
function unPersist($internalName=NULL, $withElements=TRUE) {if (is_null($internalName)) {$status = $this->persister->unpersist();} else {$status = $this->persister->unpersist("WHERE " . BS_OP_FIELD_PREFIX . "internalName = '{$internalName}'");}
if (isEx($status)) {$status->stackTrace('was here in unPersist()', __FILE__, __LINE__);$funcArgs = func_get_args();$status->setStackParam('functionArgs', $funcArgs);return $status;} elseif ($status === FALSE) {return FALSE;}
if ($withElements) {$query = "SELECT * FROM FormElement WHERE " . BS_OP_FIELD_PREFIX . "FormID = {$this->persisterID}";$rsArray = &$this->persister->bsDb->getAll($query);if (isEx($rsArray)) {$rsArray->stackTrace('was here in unPersist()', __FILE__, __LINE__);return $rsArray;}
$tempPseudoContainer = array();while (list($k) = each($rsArray)) {switch ($rsArray[$k][BS_OP_FIELD_PREFIX . 'elementType']) {case 'field':
$className = 'Bs_FormField' . ucFirst($rsArray[$k][BS_OP_FIELD_PREFIX . 'fieldType']);$element =& new $className;break;default:
$className = 'Bs_Form' . ucfirst($rsArray[$k][BS_OP_FIELD_PREFIX . 'elementType']);$element =& new $className;}
$element->setFormObject($this);$status = $element->persister->unPersist($rsArray[$k]);if (isEx($status)) {$status->stackTrace('was here in unPersist()', __FILE__, __LINE__);$funcArgs = func_get_args();$status->setStackParam('functionArgs', $funcArgs);return $status;}
$tempPseudoContainer[$element->name] = &$element;unset($element);}
while (list($k) = each($tempPseudoContainer)) {if ((isSet($tempPseudoContainer[$k]->container)) && (isSet($tempPseudoContainer[$tempPseudoContainer[$k]->container]))) {if ($tempPseudoContainer[$tempPseudoContainer[$k]->container]->elementType == 'container') {$tempPseudoContainer[$tempPseudoContainer[$k]->container]->addElement($tempPseudoContainer[$k]);continue;}
}
$this->elementContainer->addElement($tempPseudoContainer[$k]);}
}
return TRUE;}
function saveToDb() {if (!$this->saveToDb) {return FALSE;}
$saveToDbArray = $this->getSaveToDbArray();$dbTableName = $this->getDbTableName();if ($this->doesDbTableExist($dbTableName, TRUE) !== TRUE) {  $status = $this->createDbTable($dbTableName, $saveToDbArray);if (isEx($status)) {$status->stackTrace('was here in saveToDb()', __FILE__, __LINE__);return $status;}
}
$sqlI = "INSERT INTO {$dbTableName} SET ";$haveSomething = FALSE;reset($saveToDbArray);while (list($k) = each($saveToDbArray)) {if ($saveToDbArray[$k]['shouldPersist']) {if ($haveSomething) $sqlI .= ', ';$haveSomething = TRUE;if (is_array($saveToDbArray[$k]['valueInternal'])) {$sqlI .= $k . ' = \'' . $this->bsDb->escapeString('**STREAM**' . serialize($saveToDbArray[$k]['valueInternal'])) . '\'';} else {$sqlI .= $k . ' = \'' . $this->bsDb->escapeString($saveToDbArray[$k]['valueInternal']) . '\'';}
}
}
if (isSet($this->additionalParams) && is_array($this->additionalParams)) {while (list($k) = each($this->additionalParams)) {if (($this->additionalParams[$k][1] == 'both') || (($this->additionalParams[$k][1] != '') && ($this->additionalParams[$k][1] == $this->getMode()))) {if ($this->additionalParams[$k][0] == 'code') {$t = $this->_evalWrap($this->additionalParams[$k][2], 'low');if (isEx($t)) {break;}
} else {$t =  $this->additionalParams[$k][2];}
if ($haveSomething) $sqlI .= ', ';$haveSomething = TRUE;if (is_array($t)) {$sqlI .= $k . ' = \'' . $this->bsDb->escapeString('**STREAM**' . serialize($t)) . '\'';} else {$sqlI .= $k . ' = \'' . $this->bsDb->escapeString($t) . '\'';}
$haveSomething = TRUE;}
}
}
if (!$haveSomething) {return FALSE;}
$status = $this->bsDb->idWrite($sqlI);if (isEx($status)) {$status2 = $this->updateDbTableStructure($dbTableName, $saveToDbArray);if (isEx($status2)) {$status2->stackTrace('was here in saveToDb()', __FILE__, __LINE__);return $status2;}
if ($status2) {$status = $this->bsDb->idWrite($sqlI);if (isEx($status)) {$status->stackTrace('was here in saveToDb()', __FILE__, __LINE__);return $status;}
} else {$status->stackTrace('was here in saveToDb()', __FILE__, __LINE__);return $status;}
}
return $this->recordId = (int)$status;}
function getDbTableName() {if ((isSet($this->dbTableName)) && (!empty($this->dbTableName))) {return $this->dbTableName;} else {return 'form' . $this->internalName;}
}
function doesDbTableExist($dbTableName=NULL, $useCache=TRUE) {if (is_null($dbTableName)) $dbTableName = $this->getDbTableName();$status = $this->bsDb->tableExists($dbTableName, NULL, $useCache);if (isEx($status)) {$status->stackTrace('was here in doesDbTableExist()', __FILE__, __LINE__);return $status;}
return $status;}
function createDbTable($dbTableName=NULL, &$saveToDbArray) {if (is_null($dbTableName)) $dbTableName = $this->getDbTableName();$sqlC  = "CREATE TABLE IF NOT EXISTS {$dbTableName} (";$sqlC .= "ID INT UNSIGNED NOT NULL DEFAULT 0 AUTO_INCREMENT, ";$sqlK  = "PRIMARY KEY ID (ID)";reset($saveToDbArray);while (list($k) = each($saveToDbArray)) {if ($saveToDbArray[$k]['neededIndex']) $sqlK .= ", KEY {$k} ({$k})";$sqlC .= $this->_alterTableLineHelper($k, $saveToDbArray[$k]['neededDataType']) . ', ';}
$sqlC .= $sqlK;$sqlC .= ")";$status = $this->bsDb->write($sqlC);if (isEx($status)) {$status->stackTrace('was here in createDbTable()', __FILE__, __LINE__);return $status;}
return TRUE;}
function _alterTableLineHelper($fieldName, $dataType) {switch ($dataType) {case 'char':
case 'varchar':
$ret = "{$fieldName} VARCHAR(255) NOT NULL DEFAULT ''";break;case 'tinyint':
case 'smallint':
case 'mediumint':
case 'int':
case 'bigint':
case 'float':
case 'double':
case 'decimal':
$ret = "{$fieldName} {$dataType} NOT NULL DEFAULT 0";break;default:
$ret = "{$fieldName} {$dataType} NOT NULL";}
return $ret;}
function updateDbTableStructure($dbTableName=NULL, &$saveToDbArray, $checkOnly=FALSE) {if (is_null($dbTableName)) $dbTableName = $this->getDbTableName();$madeChanges = FALSE;$current = $this->bsDb->getTableProperties($dbTableName);reset($saveToDbArray);while (list($k) = each($saveToDbArray)) {if (isSet($current[$k])) {} else {$madeChanges = TRUE;if (!$checkOnly) {$sqlA = "ALTER TABLE {$dbTableName} ADD " . $this->_alterTableLineHelper($k, $saveToDbArray[$k]['neededDataType']);$status = $this->bsDb->write($sqlA);if (isEx($status)) {$status->stackTrace('was here in updateDbTableStructure()', __FILE__, __LINE__);return $status;}
}
}
}
return $madeChanges;}
function getSaveToDbArray() {return $this->elementContainer->getSaveToDbArray();}
function getValuesArray($shouldUseOnly=TRUE, $valueType='valueDisplay', $withCaption=FALSE, $captionLang=null, $printable=FALSE, $useLevels=null) {$this->seedClearingHouse();$currentKey = key($this->clearingHouse); reset($this->clearingHouse);$ret = array();while (list($k) = each($this->clearingHouse)) {if (is_array($useLevels)) {$level = $this->clearingHouse[$k]->getLevel();if (is_null($level)) continue; if (!in_array($level, $useLevels)) continue; }
if ($this->clearingHouse[$k]->elementType == 'field') {if ($shouldUseOnly) {if (! $this->clearingHouse[$k]->shouldPersist()) continue; }
$fieldValue = $this->clearingHouse[$k]->$valueType;do {if (!$printable) break;if (($this->clearingHouse[$k]->fieldType == 'select') || ($this->clearingHouse[$k]->fieldType == 'radio')) {$fieldValue = $this->clearingHouse[$k]->getOptionStringForValue($this->clearingHouse[$k]->valueDisplay);break;} elseif ($this->clearingHouse[$k]->fieldType == 'checkbox') {$fieldValue = $this->clearingHouse[$k]->getReadableValue(null, $captionLang);break;}
} while (FALSE);$k_x_pos = strpos($k, '[');if ($k_x_pos === FALSE) {if ($withCaption) {$ret[$k] = array($this->clearingHouse[$k]->getCaption(FALSE, $captionLang), $fieldValue);} else {$ret[$k] = $fieldValue;}
} else {$kOne = substr($k, 0, $k_x_pos);$kTwo = substr($k, $k_x_pos +2, -2);if ($withCaption) {$ret[$kOne][$kTwo] = array($this->clearingHouse[$k]->getCaption(FALSE, $captionLang), $fieldValue);} else {$ret[$kOne][$kTwo] = $fieldValue;}
}
}
}
$this->_Bs_Array->setPos($this->clearingHouse, $currentKey); return $ret;}
function getInterfaceText($key, $lang=null) {do {if (is_null($lang)) {if (!isSet($this->language)) break; $lang = $this->language;}
if (!isSet($this->_guiLangHash[$lang])) {$status = $this->_loadInterfaceLanguage($lang);if (!$status) {break; }
}
if (!isSet($this->_guiLangHash[$lang]['default'][$key])) break; return $this->_guiLangHash[$lang]['default'][$key];} while (FALSE);return ''; }
function _loadInterfaceLanguage($wantLang=null) {if (is_null($wantLang)) {if (!isSet($this->language)) return FALSE; $wantLang = $this->language;}
if (isSet($this->_guiLangHash[$wantLang])) return TRUE; $Bs_LanguageHandler =& new Bs_LanguageHandler();$t = &$Bs_LanguageHandler->determineLanguage($GLOBALS['APP']['path']['core'] . 'html/form/lang/text', $wantLang);if (is_null($t)) return FALSE; list($lang, $path) = $t;if (isSet($this->_guiLangHash[$lang])) {if (!isSet($this->_guiLangHash[$wantLang])) {$this->_guiLangHash[$wantLang] = &$this->_guiLangHash[$lang];}
} else {$this->_guiLangHash[$lang] = &$Bs_LanguageHandler->readLanguage($path);$this->_guiLangHash[$wantLang] = &$this->_guiLangHash[$lang];}
return TRUE;}
function _evalWrap($string, $security='high', $suppressErrors=FALSE, $params=array()) {$params['this'] = &$this;return evalWrap(&$string, &$security, &$suppressErrors, &$params);}
}
?>