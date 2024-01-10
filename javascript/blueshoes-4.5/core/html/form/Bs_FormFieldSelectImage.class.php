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
define('BS_FORMFIELDSELECTIMAGE_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldSelectImage extends Bs_FormFieldSelect {var $width  = 300;var $height = 200;var $bgColor;var $imagePath   = '';var $imageWidth  = 50;var $imageHeight = 50;var $imageBorder = 0;function Bs_FormFieldSelectImage() {$this->Bs_FormFieldSelect(); $this->fieldType = 'select';$this->persisterVarSettings['width']         = array('mode'=>'stream');$this->persisterVarSettings['height']        = array('mode'=>'stream');$this->persisterVarSettings['imagePath']     = array('mode'=>'stream');$this->persisterVarSettings['imageWidth']    = array('mode'=>'stream');$this->persisterVarSettings['imageHeight']   = array('mode'=>'stream');$this->persisterVarSettings['imageBorder']   = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($addEnforceCheckbox=TRUE) {$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
case 'readonly': $this->_markAsUsed();$fieldName .= '_readonly';$ret .= $this->getFieldAsHidden();break;case 'show':
$ret .= $this->_getDefaultValue(); return $ret;break;default: $this->_markAsUsed();$ret .= $this->getFieldAsHidden();}
$headCode = '
<script language="JavaScript" type="text/javascript">
function ' . $this->name . 'OnClick(obj, name) {//at first deselect all elements (if not multiple):
for (var i=0; i<obj.parentNode.childNodes.length; i++) {if (obj.parentNode.childNodes[i].style) {obj.parentNode.childNodes[i].style.filter = "";}
}
//now select the new one:
var t = document.getElementById("' . $fieldName . '");if (t) t.value = name;obj.style.filter = "progid:DXImageTransform.Microsoft.BasicImage( Rotation=0,Mirror=0,Invert=1,XRay=0,Grayscale=0,Opacity=1.00)";}
</script>
';$this->_form->addIntoHead($headCode);if (isSet($this->bgColor) && !empty($this->bgColor)) {$bgColorString = " background-color:{$this->bgColor};";} else {$bgColorString = '';}
$ret .= "<div id='{$fieldName}Container' style='width:{$this->width}; height:{$this->height}; {$bgColorString} overflow:auto; border-left:1px inset; border-top:1px inset; border-right:1px outset; border-bottom:1px outset;'>";$ret .= $this->_getOptionsString();$ret .= "</div>";if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
function _getOptionsString() {$ret = ''; $default = $this->_getDefaultValue();if (!is_array($default)) {if (!is_null($default)) {$default = array($default);} else {$default = array();}
}
$array = $this->_prepareOptionsData();reset($array);while(list($k) = each($array)) {$ret .= '<img src="' . $this->imagePath . $array[$k] . '" width="' . $this->imageWidth . '" height="' . $this->imageHeight . '" border="' . $this->imageBorder . '" onClick="' . $this->name . 'OnClick(this, \'' . $k . '\');"';if (in_array($k, $default)) {$ret .= ' style="filter:progid:DXImageTransform.Microsoft.BasicImage( Rotation=0,Mirror=0,Invert=1,XRay=0,Grayscale=0,Opacity=1.00);"';}
$ret .= '> ';}
return $ret;}
}
?>