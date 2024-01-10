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
define('BS_FORMFIELDBTN_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldBtn extends Bs_FormField {function Bs_FormFieldBtn() {$this->Bs_FormField(); $this->hideCaption = 2; }
function &getField() {$ret = '';switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
$this->_markAsUsed();$ret .= "<input type=\"{$this->fieldType}\" disabled";break;case 'readonly':
$ret .= "<input type=\"{$this->fieldType}\" disabled";break;case 'show':
return $ret;break;default: $this->_markAsUsed();$ret .= "<input type=\"{$this->fieldType}\"";}
$ret .= " name=\"" . $this->_getFieldNameForHtml($this->name) . "\"";if (!is_null($t = $this->getLanguageDependentValue($this->caption))) 
$ret .= " value=\"{$t}\"";if (!empty($this->direction)) $ret .= " dir=\"{$this->direction}\"";$ret .= $this->_getTagStringStyles();$ret .= $this->_getTagStringEvents();$ret .= $this->_getTagStringAdditionalTags();$ret .= '>';return $this->_doElementStringFormat($ret);}
function inputManipulate() {}
function inputValidate() {return TRUE;}
}
?>