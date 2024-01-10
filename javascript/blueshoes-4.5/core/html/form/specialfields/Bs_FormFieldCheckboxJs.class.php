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
define('BS_FORMFIELDCHECKBOXJS_VERSION',      '4.2.$Id: Bs_FormFieldCheckboxJs.class.php,v 1.4 2003/10/29 17:48:40 andrej Exp $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldCheckboxJs extends Bs_FormFieldCheckbox {var $imgDir;var $imgWidth;var $imgHeight;var $useMouseover;var $noPartly;function Bs_FormFieldCheckboxJs() {$this->Bs_FormFieldCheckbox(); $this->bsDataType = 'number';$this->bsDataInfo = '0|2';$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $withText=TRUE, $addEnforceCheckbox=TRUE) {$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
case 'readonly':
$this->_markAsUsed();$ret .= $this->getFieldAsHidden();$fieldName .= '_readonly';$disabled = TRUE;break;case 'show':
if ($this->bsDataType === 'boolean') {$ret .= boolToString($this->_getTagStringValue($explodeKey));} else {$ret .= $this->_getTagStringValue($explodeKey);}
return $ret;break;default: $this->_markAsUsed();}
$jsVarName  = 'bsChk'    . $fieldName;$divVarName = 'bsChkDiv' . $fieldName;if (!is_null($explodeKey)) {$jsVarName  .= $explodeKey;$divVarName .= $explodeKey;}
$this->_form->addIncludeOnce('/_bsJavascript/components/checkbox/Bs_Checkbox.class.js');$aolc  = ''; $aolc .= "{$jsVarName} = new Bs_Checkbox();\n";$aolc .= "{$jsVarName}.objectName   = '{$jsVarName}';\n";$aolc .= "{$jsVarName}.caption      = \"" . $this->_Bs_HtmlUtil->filterForJavaScript($this->getFieldText(FALSE)) . "\";\n";$aolc .= "{$jsVarName}.checkboxName = '{$fieldName}';\n";$value = $this->getValue($explodeKey);if ($value === TRUE) $value = 2;if (empty($value)) $value = 0;$aolc .= "{$jsVarName}.value = " . $value . ";\n";if (@$disabled) $aolc .= "{$jsVarName}.disabled = true;\n";if (isSet($this->imgDir))    $aolc .= "{$jsVarName}.imgDir       = '$this->imgDir';\n";if (isSet($this->imgWidth))  $aolc .= "{$jsVarName}.imgWidth     = '$this->imgWidth';\n";if (isSet($this->imgHeight)) $aolc .= "{$jsVarName}.imgHeight    = '$this->imgHeight';\n";if (@$this->useMouseover)    $aolc .= "{$jsVarName}.useMouseover = true;\n";if (@$this->noPartly)        $aolc .= "{$jsVarName}.noPartly     = true;\n";$aolc .= "{$jsVarName}.draw('{$divVarName}');\n";$this->_form->addOnLoadCode($aolc);$ret .= '<span id="' . $divVarName . '"></span>';if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
}
?>