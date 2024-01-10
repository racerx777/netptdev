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
define('BS_FORMFIELDTEXTAREA_VERSION',      '4.5.$Revision: 1.5 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldTextarea extends Bs_FormField {var $cols = 60;var $rows = 5;var $wrap = 'soft';var $showCharsLeft = FALSE;var $bgColorWarning;var $infolineText;var $infolineCssClass;var $infolineCssStyle;var $numberCssClass;var $numberCssStyle;var $useProgressBar;function Bs_FormFieldTextarea() {$this->Bs_FormField(); $this->fieldType = 'textarea';$this->persisterVarSettings['cols']  = array('mode'=>'stream');$this->persisterVarSettings['rows']  = array('mode'=>'stream');$this->persisterVarSettings['wrap']  = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
case 'readonly':
$this->_markAsUsed();$fieldName .= '_readonly';$ret .= $this->getFieldAsHidden();$ret .= "<textarea readonly disabled";break;case 'show':
$ret .= $this->_getTagStringValue($explodeKey);return $ret;break;default: $this->_markAsUsed();$ret .= "<textarea";}
if (!is_null($explodeKey)) {$ret .= " name=\"{$fieldName}[{$explodeKey}]\"";} else {$ret .= " name=\"{$fieldName}\"";}
$ret .= " cols=\"{$this->cols}\"";$ret .= " rows=\"{$this->rows}\"";$ret .= " wrap=\"{$this->wrap}\"";if (isSet($this->direction)) $ret .= " dir=\"{$this->direction}\"";$ret .= $this->_getTagStringStyles();$ret .= $this->_getTagStringEvents();$ret .= $this->_getTagStringAdditionalTags();$ret .= '>';$ret .= $this->_getTagStringValue($explodeKey);$ret .= '</textarea>';if ($this->showCharsLeft) {$this->_form->addIncludeOnce('/_bsJavascript/core/lang/Bs_Misc.lib.js');$this->_form->addIncludeOnce('/_bsJavascript/components/limitedtextarea/Bs_LimitedTextarea.class.js');$this->_form->addIncludeOnce('/_bsJavascript/core/gfx/Bs_ColorUtil.lib.js');$jsVarName  = 'bsTa' . $fieldName;$aolc  = '';$aolc .= "{$jsVarName} = new Bs_LimitedTextarea('{$jsVarName}', '{$fieldName}', " . $this->maxLength . ");\n";if (isSet($this->useProgressBar))  $aolc .= "{$jsVarName}.useProgressBar     = " . boolToString($this->useProgressBar) . ";\n";if (isSet($this->infolineText))    $aolc .= "{$jsVarName}.infolineText       = '{$this->infolineText}';\n";if (!empty($this->bgColorWarning)) {$aolc .= "{$jsVarName}.setBgColorWarning({$this->bgColorWarning['kickInValue']}, '{$this->bgColorWarning['kickInType']}', '{$this->bgColorWarning['endColor']}');\n";}
$aolc .= "{$jsVarName}.draw();\n";$aolc .= "\n";$this->_form->addOnLoadCode($aolc);}
if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
}
?>