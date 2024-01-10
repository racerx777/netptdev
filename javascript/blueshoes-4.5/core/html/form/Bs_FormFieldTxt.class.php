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
define('BS_FORMFIELDTXT_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core']      . 'html/form/Bs_FormField.class.php');class Bs_FormFieldTxt extends Bs_FormField {var $size = 30;function Bs_FormFieldTxt() {parent::Bs_FormField(); $this->persisterVarSettings['size']  = array('mode'=>'stream');}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
case 'readonly':
$this->_markAsUsed();$fieldName .= '_readonly';$ret .= $this->getFieldAsHidden();$ret .= "<input type=\"{$this->fieldType}\" readonly disabled";break;case 'show':
$ret .= $this->_getTagStringValue($explodeKey);return $ret;break;default: $this->_markAsUsed();$ret .= "<input type=\"{$this->fieldType}\"";}
if (!is_null($explodeKey)) {$ret .= " name=\"{$fieldName}[{$explodeKey}]\"";} else {$ret .= " name=\"{$fieldName}\"";}
$ret .= " value=\"" . $this->_getTagStringValue($explodeKey) . "\"";if (!empty($this->size)) $ret .= " size=\"{$this->size}\"";if (!empty($this->direction)) $ret .= " dir=\"{$this->direction}\"";$ret .= $this->_getTagStringStyles();$this->applyOnEnterBehavior();$ret .= $this->_getTagStringEvents();if (!is_null($t = $this->_getMaxLength())) $ret .= " maxlength=\"{$t}\"";$ret .= $this->_getTagStringAdditionalTags();$ret .= '>';if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
}
?>