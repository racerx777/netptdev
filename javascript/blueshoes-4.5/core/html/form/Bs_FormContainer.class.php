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
define('BS_FORMCONTAINER_VERSION',      '4.5.$Revision: 1.6 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormElement.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormTemplateParser.class.php');require_once($APP['path']['core'] . 'util/Bs_Array.class.php');class Bs_FormContainer extends Bs_FormElement {var $_Bs_Array;var $formElements;var $caption = NULL;var $useCheckboxAsCaption = FALSE;var $pseudoContainer = FALSE;var $pseudoIfNada = TRUE;var $level;var $mayToggle = FALSE;var $defaultOff = FALSE;var $useTemplate = FALSE;var $templateString = NULL;var $templatePath = NULL;function Bs_FormContainer() {parent::Bs_FormElement(); $this->_Bs_Array = &$GLOBALS['Bs_Array'];$this->elementType = 'container';$tempArray = array('mode'=>'stream');$this->persisterVarSettings['caption']              = &$tempArray;$this->persisterVarSettings['useCheckboxAsCaption'] = &$tempArray;$this->persisterVarSettings['pseudoContainer']      = &$tempArray;$this->persisterVarSettings['level']                = array('mode'=>'lonely',        'metaType'=>'integer',     'index'=>TRUE);$this->persisterVarSettings['mayToggle']            = &$tempArray;$this->persisterVarSettings['defaultOff']           = &$tempArray;$this->persisterVarSettings['useTemplate']          = &$tempArray;$this->persisterVarSettings['templatePath']         = &$tempArray;$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getElement($level=null) {$isPreviousLevel = FALSE;if (!is_null($level) && isSet($this->level)) {if ($this->level > $level) {return ''; } elseif ($this->level < $level) {return '';}
$parentContainerLevel = $this->level;}
do { if (@$this->useTemplate !== TRUE) break;$tParser =& new Bs_FormTemplateParser();if (!empty($this->templateString)) {$status = $tParser->loadTemplateFromString($this->templateString);} else {if (isSet($this->templatePath)) $tParser->templatePath = $this->templatePath;$options = array();$options['name']  = $this->name;$options['mode']  = $this->_form->getMode();$options['state'] = $this->_form->state;if ((isSet($this->_form->user))     && (!empty($this->_form->user)))     $options['user']     = $this->_form->user;if ((isSet($this->_form->language)) && (!empty($this->_form->language))) $options['language'] = $this->_form->language;$status = $tParser->loadTemplate($this->_form->internalName, 'formcontainer', $options);if (!($status === TRUE)) break;}
$status = $tParser->parse();$status = $tParser->apply(NULL, &$this->_form);return $tParser->templateString;} while (FALSE);if ($this->pseudoIfNada && (empty($this->formElements) || !is_array($this->formElements))) {return '';}
$this->orderElements();$ret      = '';$retStart = '';if (!$this->pseudoContainer) $retStart .= $this->getStartTag(); if ((isSet($this->formElements)) && (is_array($this->formElements))) {$ret .= "<table border='0' cellpadding='2' cellspacing='0' width='100%'>\n";$numVisibleWidgets = 0;reset($this->formElements);while(list($k) = each($this->formElements)) {switch ($this->formElements[$k]->elementType) {case 'container':
if (isSet($parentContainerLevel)) {$this->formElements[$k]->level = $parentContainerLevel;}
$ret .= "<tr><td colspan='100%'>";$t = &$this->formElements[$k]->getElement($level);$ret .= $t . "</td></tr>\n";$numVisibleWidgets++;break;default:
if ($this->formElements[$k]->elementType === 'field') {if ((!$this->pseudoContainer) && (isSet($this->useCheckboxAsCaption)) && ($this->useCheckboxAsCaption == $k)) {$numVisibleWidgets++;break;}
}
if ($this->formElements[$k]->getVisibility() === 'omit') break;if (($this->formElements[$k]->elementType !== 'field') || ($this->formElements[$k]->fieldType !== 'hidden')) {$numVisibleWidgets++;}
$lay = $this->formElements[$k]->getElementLayout();if ($lay === FALSE) {if (@$this->formElements[$k]->hideCaption === 1) {$ret .= "<tr><td nowrap valign='top' align='left' width='20%'>";$ret .= "&nbsp;</td><td>";$ret .= $this->formElements[$k]->getElement();$ret .= "</td></tr>\n";} elseif (@$this->formElements[$k]->hideCaption === 2) {$ret .= "<tr><td colspan='2' valign='top' align='left'>";$ret .= $this->formElements[$k]->getElement();$ret .= "</td></tr>\n";} else {$ret .= "<tr><td nowrap valign='top' align='left' width='25%'>";$ret .= $this->formElements[$k]->getCaptionForFormOutput() . "</td><td>"; $ret .= $this->formElements[$k]->getElement();$ret .= "</td></tr>\n";}
} else {$t = str_replace('__CAPTION_FOR_FORM_OUTPUT__', $this->formElements[$k]->getCaptionForFormOutput(), $lay);$t = str_replace('__CAPTION__',                 $this->formElements[$k]->getCaption(),              $t);$t = str_replace('__ELEMENT__',                 $this->formElements[$k]->getElement(),              $t);$ret .= $t;}
}
}
$ret .= "</table>\n";}
if ($this->pseudoIfNada && ($numVisibleWidgets === 0)) {return $ret;} else {$ret = $retStart . $ret;if (!$this->pseudoContainer) $ret .= $this->getEndTag();return $this->_doElementStringFormat($ret);}
}
function getStartTag() {if ($this->mayToggle) {$ret  = '<fieldset><legend style="cursor:hand;" onClick="bsFormToggleContainer(\'container' . $this->name . '\');">';} else {$ret  = '<fieldset><legend style="cursor:default;">';}
if ((isSet($this->useCheckboxAsCaption)) && (isSet($this->formElements[$this->useCheckboxAsCaption]))) {$ret .= $this->formElements[$this->useCheckboxAsCaption]->getField(NULL, FALSE);$ret .= ' ';$ret .= $this->formElements[$this->useCheckboxAsCaption]->getCaption(TRUE, NULL, TRUE);$ret .= '</legend>';$ret .= '<span ID="container' . $this->name . '"';if (@$this->defaultOff) $ret .= ' style="display:none;"';$ret .= '>';$ret .= "<table border='0' cellpadding='10' cellspacing='0' width='100%'><tr><td>\n";$fieldText = $this->formElements[$this->useCheckboxAsCaption]->getFieldText(FALSE);if (!empty($fieldText)) {$ret .= $fieldText . '<br><br>';}
} else {$ret .= $this->getCaption() . '</legend>';$ret .= '<span ID="container' . $this->name . '"';if (@$this->defaultOff) $ret .= ' style="display:none;"';$ret .= '>';$ret .= "<table border='0' cellpadding='10' cellspacing='0' width='100%'><tr><td>\n";}
return $ret;}
function getEndTag() {$ret  = "</td></tr></table>\n";$ret .= '</span></fieldset><br>';return $ret;}
function addElement(&$element) {if (is_object($element)) {$element->setFormObject($this->_form);           $this->formElements[$element->name] = &$element; $element->container = &$this;                    return TRUE;}
return FALSE;}
function seedClearingHouse($ret=array(), $root=null, $firstCall=TRUE){if ($firstCall) {$root = $this->formElements;}
if ((is_array($root)) && (sizeOf($root) > 0)) {$currentKey = key($root);reset($root);while (list($k) = each($root)) {switch ($root[$k]->elementType) {case 'container':
$ret[$k] = &$root[$k];$ret = $root[$k]->seedClearingHouse(&$ret,  $root[$k]->formElements, FALSE); break;default:
$ret[$k] = &$root[$k];}
}
$x = $this->_Bs_Array->setPos($root, $currentKey);}
return $ret;}
function orderElements() {if ((isSet($this->formElements)) && (is_array($this->formElements))) {$defaultOrderId = 1000;$t = array();reset($this->formElements);while(list($k) = each($this->formElements)) {if (!isSet($this->formElements[$k]->orderId)) $this->formElements[$k]->orderId = $defaultOrderId - 1;$t[$k] = $this->formElements[$k]->orderId;$defaultOrderId = $this->formElements[$k]->orderId;}
arsort($t, SORT_NUMERIC);reset($t);$t2 = array();while (list($k) = each($t)) {$t2[$k] = &$this->formElements[$k];}
$this->formElements = &$t2;}
}
function hasFileFieldElement() {if ((isSet($this->formElements)) && (is_array($this->formElements))) {reset($this->formElements);while(list($k) = each($this->formElements)) {switch ($this->formElements[$k]->elementType) {case 'container':
$t = $this->formElements[$k]->hasFileFieldElement();if ($t === TRUE) return TRUE;break;case 'field':
if ($this->formElements[$k]->fieldType == 'file') 
return TRUE;break;}
}
}
return FALSE;}
function getLevel() {if (isSet($this->container) && is_object($this->container)) {$t = $this->container->getLevel();if (!is_null($t)) return $t;if (is_null($t) && isSet($this->level)) return $this->level;return null;} elseif (isSet($this->level)) {return $this->level;} else {return null; }
}
function getSaveToDbArray($hash=array()) {if ((isSet($this->formElements)) && (is_array($this->formElements))) {reset($this->formElements);while(list($k) = each($this->formElements)) {switch ($this->formElements[$k]->elementType) {case 'container':
$hash = $this->formElements[$k]->getSaveToDbArray(&$hash);break;case 'field':
if ($this->formElements[$k]->saveToDb) {$hash[$this->formElements[$k]->getDbFieldName()] = array('valueInternal'     =>$this->formElements[$k]->valueInternal, 
'neededDataType'    =>$this->formElements[$k]->getDbDataType(), 
'neededIndex'       =>(bool)$this->formElements[$k]->mustBeUnique, 
'shouldPersist'     =>$this->formElements[$k]->shouldPersist()
);}
break;}
}
}
return $hash;}
function persist() {if ((isSet($this->formElements)) && (is_array($this->formElements))) {reset($this->formElements);while(list($k) = each($this->formElements)) {if ($this->formElements[$k]->elementType == 'container') 
$this->formElements[$k]->persist();$status = $this->formElements[$k]->persister->persist();if (isEx($status)) {$status->stackTrace('was here in persist()', __FILE__, __LINE__);return $status;}
}
}
}
}
?>