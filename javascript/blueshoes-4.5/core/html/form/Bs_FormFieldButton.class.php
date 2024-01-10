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
define('BS_FORMFIELDBUTTON_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldBtn.class.php');class Bs_FormFieldButton extends Bs_FormFieldBtn {var $htmlContent;var $type = 'submit';function Bs_FormFieldButton() {$this->Bs_FormFieldBtn(); $this->fieldType = 'button';$this->persisterVarSettings['htmlContent'] = array('mode'=>'stream');$this->persisterVarSettings['type']        = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField() {if (!isSet($this->htmlContent)) return parent::getField();$htmlContent = $this->getLanguageDependentValue($this->htmlContent);if (is_null($htmlContent)) return parent::getField();$ret = '';switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
$this->_markAsUsed();$ret .= "<button disabled";break;case 'readonly':
$ret .= "<button disabled";break;return $ret;break;default: $this->_markAsUsed();$ret .= "<button";}
$ret .= " name=\"" . $this->_getFieldNameForHtml($this->name) . "\"";if (!is_null($t = $this->getLanguageDependentValue($this->caption))) 
$ret .= " value=\"{$t}\"";if (isSet($this->direction)) $ret .= " dir=\"{$this->direction}\"";$ret .= $this->_getTagStringStyles();$ret .= $this->_getTagStringEvents();$ret .= $this->_getTagStringAdditionalTags();if (isSet($this->type)) $ret .= " type=\"{$this->type}\"";$ret .= '>';$ret .= $htmlContent . '</button>';return $this->_doElementStringFormat($ret);}
}
?>