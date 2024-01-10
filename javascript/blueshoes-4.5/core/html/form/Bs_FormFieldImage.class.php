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
define('BS_FORMFIELDIMAGE_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldImage extends Bs_FormField {var $src = NULL;var $alt = NULL;var $align = NULL;var $usemap = NULL;var $width = NULL;var $height = NULL;var $border = NULL;var $hspace = NULL;var $vspace = NULL;function Bs_FormFieldImage() {parent::Bs_FormField(); $this->fieldType = 'image';$t = array('src'      => array('mode'=>'stream'), 
'alt'      => array('mode'=>'stream'), 
'align'    => array('mode'=>'stream'), 
'usemap'   => array('mode'=>'stream'), 
'width'    => array('mode'=>'stream'), 
'height'   => array('mode'=>'stream'), 
'border'   => array('mode'=>'stream'), 
'hspace'   => array('mode'=>'stream'), 
'vspace'   => array('mode'=>'stream'), 
);$this->persisterVarSettings = array_merge($this->persisterVarSettings, $t);$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField() {$ret = '';switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
$this->_markAsUsed();$ret .= "<input type=\"{$this->fieldType}\" disabled";break;case 'readonly':
$ret .= "<input type=\"{$this->fieldType}\" disabled";break;case 'show':
return $ret;break;default: $this->_markAsUsed();$ret .= "<input type=\"{$this->fieldType}\"";}
$ret .= " name=\"" . $this->_getFieldNameForHtml($this->name) . "\"";if (!is_null($t = $this->getLanguageDependentValue($this->src))) 
$ret .= " src=\"{$t}\"";if (!is_null($t = $this->getLanguageDependentValue($this->alt))) 
$ret .= " alt=\"{$t}\"";if (!is_null($t = $this->getLanguageDependentValue($this->width))) 
$ret .= " width=\"{$t}\"";if (!is_null($t = $this->getLanguageDependentValue($this->height))) 
$ret .= " height=\"{$t}\"";if (isSet($this->align))  $ret .= " align=\"{$this->align}\"";if (isSet($this->hspace)) $ret .= " hspace=\"{$this->hspace}\"";if (isSet($this->vspace)) $ret .= " vspace=\"{$this->vspace}\"";if (isSet($this->border)) $ret .= " border=\"{$this->border}\"";if (isSet($this->usemap)) $ret .= " usemap=\"{$this->usemap}\"";$ret .= " value=\"" . $this->_getTagStringValue() . "\"";if (isSet($this->direction)) $ret .= " dir=\"{$this->direction}\"";$ret .= $this->_getTagStringStyles();$ret .= $this->_getTagStringEvents();$ret .= $this->_getTagStringAdditionalTags();$ret .= '>';return $this->_doElementStringFormat($ret);}
}
?>