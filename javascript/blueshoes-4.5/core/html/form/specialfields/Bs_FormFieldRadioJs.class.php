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
define('BS_FORMFIELDRADIOJS_VERSION',      '4.2.$Id: Bs_FormFieldRadioJs.class.php,v 1.6 2003/10/29 17:48:41 andrej Exp $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldRadioJs extends Bs_FormFieldRadio {var $imgDir;var $imgWidth;var $imgHeight;var $useMouseover;var $captionAsAltText;var $cssClass;var $cssStyle;var $allowNoSelection;function Bs_FormFieldRadioJs() {$this->Bs_FormFieldRadio(); }
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE, $elementList=null) {$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
$this->_markAsUsed();$ret .= $this->getFieldAsHidden();case 'readonly':
$fieldName .= '_readonly';$disabled = TRUE;break;case 'show':
$ret .= $this->getOptionStringForValue($this->getValue($explodeKey));return $ret;break;default: $this->_markAsUsed();}
$jsVarName  = 'bsRad'    . $fieldName;$divVarName = 'bsRadDiv' . $fieldName;if (!is_null($explodeKey)) {$jsVarName  .= $explodeKey;$divVarName .= $explodeKey;}
$this->_form->addIncludeOnce('/_bsJavascript/components/radio/Bs_Radio.class.js');$this->_form->addIncludeOnce('/_bsJavascript/core/lang/Bs_Misc.lib.js');$aolc  = ''; $aolc .= "{$jsVarName} = new Bs_Radio();\n";$aolc .= "{$jsVarName}.objectName     = '{$jsVarName}';\n";$aolc .= "{$jsVarName}.radioFieldName = '{$fieldName}';\n";$aolc .= "{$jsVarName}.value = \"" . $this->_getTagStringValue($explodeKey) . "\";\n";if (@$disabled) $aolc .= "{$jsVarName}.disabled = true;\n";if (isSet($this->imgDir))           $aolc .= "{$jsVarName}.imgDir       = '$this->imgDir';\n";if (isSet($this->imgWidth))         $aolc .= "{$jsVarName}.imgWidth     = '$this->imgWidth';\n";if (isSet($this->imgHeight))        $aolc .= "{$jsVarName}.imgHeight    = '$this->imgHeight';\n";if (@$this->captionAsAltText)       $aolc .= "{$jsVarName}.captionAsAltText = true;\n";if (isSet($this->cssClass))         $aolc .= "{$jsVarName}.cssClass     = '$this->cssClass';\n";if (isSet($this->cssStyle))         $aolc .= "{$jsVarName}.cssStyle     = '$this->cssStyle';\n";if (isSet($this->allowNoSelection)) $aolc .= "{$jsVarName}.allowNoSelection = " . boolToString($this->allowNoSelection) . ";\n";$options = $this->_prepareOptionsData();if (is_array($elementList)) {$newArray = array();while (list(,$option) = each($elementList)) {if (isSet($options[$option])) {$newArray[$option] = $options[$option];}
}
$options = $newArray;}
while (list($k) = each($options)) {$aolc .= "{$jsVarName}.addOption(\"" . $this->_Bs_HtmlUtil->filterForJavaScript($k) . "\", \"" . $this->_Bs_HtmlUtil->filterForJavaScript($options[$k]) . "\");\n";}
$align = ($this->align === 'h') ? 'horizontal' : 'vertical';$aolc .= "var tmp = {$jsVarName}.renderAsTable('{$align}', '<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">');\n";$aolc .= "document.getElementById('{$divVarName}').innerHTML = tmp;\n";$this->_form->addOnLoadCode($aolc);$ret .= '<span id="' . $divVarName . '"></span>';if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
}
?>