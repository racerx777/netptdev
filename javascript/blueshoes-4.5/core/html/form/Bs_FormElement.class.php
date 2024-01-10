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
define('BS_FORMELEMENT_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'storage/objectpersister/Bs_ObjPersisterForMySql.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormContainer.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormField.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormImage.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormLine.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormText.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormHtml.class.php');require_once($APP['path']['core'] . 'html/form/Bs_FormCode.class.php');class Bs_FormElement extends Bs_Object {var $persister = NULL;var $persisterID;var $persisterVarSettings = array('name'                => array('mode'=>'lonely',        'metaType'=>'string',      'index'=>TRUE), 
'elementType'         => array('mode'=>'lonely',        'metaType'=>'string',      'index'=>TRUE), 
'FormID'              => array('mode'=>'lonely',        'metaType'=>'integer',     'index'=>TRUE), 
'container'           => array('mode'=>'stream'), 
'orderId'             => array('mode'=>'stream'), 
'elementStringFormat' => array('mode'=>'stream')
);var $_form;var $name;var $elementType;var $FormID;var $container;var $orderId;var $elementLayout;var $elementStringFormat;var $caption = NULL;var $hideCaption;var $accessRights;var $visibility;function Bs_FormElement() {parent::Bs_Object(); $this->persister =& new Bs_ObjPersisterForMySql($this); $this->persister->setDbTableName('FormElement');}
function hasFormObject() {return (bool)(@is_object($this->_form));}
function setFormObject(&$form) {if ((is_object($form)) && (get_class($form) == 'bs_form')) {$this->_form = &$form;return TRUE;}
return FALSE;}
function getCaption() {if (is_null($ret = $this->getLanguageDependentValue($this->caption))) return '';return $ret;}
function getCaptionForFormOutput() {if (is_null($ret = $this->getLanguageDependentValue($this->caption))) return '';return $ret;}
function getElement() {return '';}
function getLevel() {if (isSet($this->container) && is_object($this->container)) {return $this->container->getLevel();} else {return null; }
}
function getVisibility() {if (isSet($this->accessRights) && isSet($this->_form->user) && isSet($this->accessRights[$this->_form->user])) {if (is_array($this->accessRights[$this->_form->user])) {$t = $this->accessRights[$this->_form->user][$this->_form->mode];} else {$t = $this->accessRights[$this->_form->user];}
return $t;} elseif ((isSet($this->visibility)) && ($this->visibility != '')) {return $this->visibility;} else {if (($this->elementType === 'field') && isSet($this->editability)) {if ($this->hasFormObject() && is_object($this->_form)) {$mode = $this->_form->getMode();} else {$mode = ''; }
if (($mode == 'view') || ($mode == 'delete')) return 'show';switch ($this->editability) {case 'always':
return 'normal';break;case 'once':
return ($mode == 'add') ? 'normal' : 'readonly';case 'never':
return 'readonly';break;default:
return 'normal';}
} else {return 'normal';}
}
}
function getElementLayout() {if (isSet($this->elementLayout)) return $this->elementLayout;if (isSet($this->_form->elementLayouts)) {do {switch ($this->elementType) {case 'field':
if (!isSet($this->_form->elementLayouts['field'])) break 2;if (isSet($this->_form->elementLayouts['field'][$this->fieldType])) {$t = $this->_form->elementLayouts['field'][$this->fieldType];if (substr($t, 0, 1) == '_') {if (!isSet($this->_form->elementLayouts['field'][substr($t, 1)])) break 2;$lay = $this->_form->elementLayouts['field'][substr($t, 1)];} else {$lay = $t;}
} elseif (isSet($this->_form->elementLayouts['field']['_default'])) {$lay = $this->_form->elementLayouts['field']['_default'];}
break 2;default: if (isSet($this->_form->elementLayouts[$this->elementType])) {$lay = $this->_form->elementLayouts[$this->elementType];} elseif (isSet($this->_form->elementLayouts['_default'])) {$lay = $this->_form->elementLayouts['_default'];}
}
} while (FALSE);if (isSet($lay)) return $lay;}
return FALSE;}
function _doElementStringFormat($elementString) {if (isSet($this->elementStringFormat)) {$elementStringFormat = $this->getLanguageDependentValue($this->elementStringFormat);if (!empty($elementStringFormat)) {$elementString = sprintf($elementStringFormat, $elementString);}
}
return $elementString;}
function _getParamValue($var) {if (isSet($var)) {if (is_array($var)) {reset($var);if (is_array(current($var))) {return $this->getLanguageDependentValue($var); } else {return $var;}
} else {return array($var);}
} else {return array();}
}
function getLanguageDependentValue($var, $lang=null) {if (is_null($lang) && ($this->hasFormObject() && isSet($this->_form->language))) $lang = $this->_form->language;if (isSet($var)) {if (!@is_object($this->_Bs_Array)) $this->_Bs_Array = &$GLOBALS['Bs_Array']; if (is_array($var) && (substr($this->_Bs_Array->guessType($var), 0, 4) == 'hash')) {if (!is_null($lang)) {if (isSet($var[$lang])) {return $var[$lang]; }
if (strlen($lang) > 2) {$t = substr($lang, 0, 2);if (isSet($var[$t])) return $var[$t];}
}
if (isSet($var['']))   return $var[''];if (isSet($var['xx'])) return $var['xx'];if (sizeOf($var) == 0) return ''; reset($var);return current($var);} else {return $var;}
} else {return NULL;}
}
}
?>