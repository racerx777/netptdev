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
define('BS_FORMFIELDSLIDER_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldSlider extends Bs_FormField {var $direction;var $width;var $height;var $minVal;var $maxVal;var $arrowAmount;var $colorbar;var $moveX;var $moveY;var $baseZindex;var $imgBasePath;var $styleContainerClass;var $styleValueFieldClass;var $styleValueTextClass;var $bgColor;var $valueInterval;var $useInputField;var $inputTextFieldEvent;var $display;var $sliderImgSrc;var $sliderImgWidth;var $sliderImgHeight;var $arrowIconLeftSrc;var $arrowIconLeftWidth;var $arrowIconLeftHeight;var $arrowIconRightSrc;var $arrowIconRightWidth;var $arrowIconRightHeight;var $bgImgSrc;var $bgImgRepeat;var $skin;var $_attachedEvents;function Bs_FormFieldSlider() {$this->Bs_FormField(); $this->fieldType = 'slider';$this->persister->setVarSettings(&$this->persisterVarSettings);}
function setSliderIcon($src, $width, $height) {$this->sliderImgSrc    = $src;$this->sliderImgWidth  = $width;$this->sliderImgHeight = $height;}
function setArrowIconLeft($src, $width, $height) {$this->arrowIconLeftSrc    = $src;$this->arrowIconLeftWidth  = $width;$this->arrowIconLeftHeight = $height;}
function setArrowIconRight($src, $width, $height) {$this->arrowIconRightSrc    = $src;$this->arrowIconRightWidth  = $width;$this->arrowIconRightHeight = $height;}
function setBackgroundImage($src, $repeat='no-repeat') {$this->bgImgSrc        = $src;$this->bgImgRepeat     = $repeat;}
function setDisplay($display) {$this->display = $display;}
function attachEvent($trigger, $yourEvent) {if (!isSet($this->_attachedEvents)) {$this->_attachedEvents = array();}
if (!isSet($this->_attachedEvents[$trigger])) {$this->_attachedEvents[$trigger] = array($yourEvent);} else {$this->_attachedEvents[$trigger][] = $yourEvent;}
}
function loadSkin($skin) {if (is_array($skin)) {$name   = $skin['name'];$greedy = @$skin['greedy'];} else {$name   = $skin;$greedy = FALSE;}
switch ($name) {case 'default':
if ($greedy || !isSet($this->width))                $this->width         = 121;if ($greedy || !isSet($this->height))               $this->height        = 26;if ($greedy || !isSet($this->minVal))               $this->minVal        = 0;if ($greedy || !isSet($this->maxVal))               $this->maxVal        = 10;if ($greedy || !isSet($this->valueInterval))        $this->valueInterval = 1;if ($greedy || !isSet($this->arrowAmount))          $this->arrowAmount   = 1;if ($greedy || !isSet($this->imgBasePath))          $this->imgBasePath   = '/_bsJavascript/components/slider/img/';if ($greedy || !isSet($this->bgImgSrc))             $this->setBackgroundImage('bob/background.gif', 'no-repeat');if ($greedy || !isSet($this->sliderImgSrc))         $this->setSliderIcon('bob/slider.gif', 13, 18);if ($greedy || !isSet($this->arrowIconLeftSrc))     $this->setArrowIconLeft('img/arrowLeft.gif', 16, 16);if ($greedy || !isSet($this->arrowIconRightSrc))    $this->setArrowIconRight('img/arrowRight.gif', 16, 16);if ($greedy || !isSet($this->useInputField))        $this->useInputField = 2;if ($greedy || !isSet($this->moveY))                $this->moveY         = -8;if ($greedy || !isSet($this->colorbar)) {$this->colorbar = array();$this->colorbar['color']           = 'blue';$this->colorbar['height']          = 5;$this->colorbar['widthDifference'] = -12;$this->colorbar['offsetLeft']      = 5;$this->colorbar['offsetTop']       = 9;}
return TRUE;break;default: 
return FALSE;}
}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);$value = $this->getValue($explodeKey);switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
case 'readonly':
$this->_markAsUsed();$fieldName .= '_readonly';$ret .= $this->getFieldAsHidden();$ret .= $value;return $ret;break;case 'show':
$ret .= $value;return $ret;break;default: $this->_markAsUsed();}
$this->_form->addIncludeOnce('/_bsJavascript/lib/LibCrossBrowser.js');$this->_form->addIncludeOnce('/_bsJavascript/lib/EventHandler.js');$this->_form->addIncludeOnce('/_bsJavascript/components/slider/Bs_Slider.class.js');$this->_form->addIncludeOnce('/_bsJavascript/core/form/Bs_FormUtil.lib.js');$jsVarName  = 'bsSli' . $fieldName;$divVarName = 'bsDiv' . $fieldName;if (!is_null($explodeKey)) {$jsVarName  .= $explodeKey;$divVarName .= $explodeKey;}
$aolc  = ''; $aolc .= "initCrossBrowserLib();\n";$aolc .= "{$jsVarName} = new Bs_Slider();\n";$aolc .= "{$jsVarName}.objectName = '{$jsVarName}';\n";$aolc .= "{$jsVarName}.fieldName = '{$fieldName}';\n";if (isSet($this->direction))             $aolc .= "{$jsVarName}.direction                = {$this->direction}\n";if (isSet($this->width))                 $aolc .= "{$jsVarName}.width                    = {$this->width}\n";if (isSet($this->height))                $aolc .= "{$jsVarName}.height                   = {$this->height};\n";if (isSet($this->minVal))                $aolc .= "{$jsVarName}.minVal                   = {$this->minVal};\n";if (isSet($this->maxVal))                $aolc .= "{$jsVarName}.maxVal                   = {$this->maxVal};\n";if (isSet($this->valueDefault))          $aolc .= "{$jsVarName}.valueDefault             = {$this->valueDefault};\n";if (isSet($this->arrowAmount))           $aolc .= "{$jsVarName}.arrowAmount              = {$this->arrowAmount};\n";if (isSet($this->baseZindex))            $aolc .= "{$jsVarName}.baseZindex               = {$this->baseZindex};\n";if (isSet($this->imgBasePath))           $aolc .= "{$jsVarName}.imgBasePath              = '{$this->imgBasePath}';\n";if (isSet($this->styleContainerClass))   $aolc .= "{$jsVarName}.styleContainerClass      = '{$this->styleContainerClass}';\n";if (isSet($this->styleValueFieldClass))  $aolc .= "{$jsVarName}.styleValueFieldClass     = '{$this->styleValueFieldClass}';\n";if (isSet($this->styleValueTextClass))   $aolc .= "{$jsVarName}.styleValueTextClass      = '{$this->styleValueTextClass}';\n";if (isSet($this->bgColor))               $aolc .= "{$jsVarName}.bgColor                  = '{$this->bgColor}';\n";if (isSet($this->valueInterval))         $aolc .= "{$jsVarName}.valueInterval            = {$this->valueInterval};\n";if (isSet($this->useInputField))         $aolc .= "{$jsVarName}.useInputField            = {$this->useInputField};\n";if (isSet($this->inputTextFieldEvent))   $aolc .= "{$jsVarName}.inputTextFieldEvent      = '{$this->inputTextFieldEvent}';\n";if (isSet($this->moveX))                 $aolc .= "{$jsVarName}.moveX                    = '{$this->moveX}';\n";if (isSet($this->moveY))                 $aolc .= "{$jsVarName}.moveY                    = '{$this->moveY}';\n";if (isSet($this->display)) {$aolc .= "{$jsVarName}.setDisplay({$this->display});\n";}
if (isSet($this->sliderImgSrc)) {$aolc .= "{$jsVarName}.setSliderIcon('{$this->sliderImgSrc}', {$this->sliderImgWidth}, {$this->sliderImgHeight});\n";}
if (isSet($this->arrowIconLeftSrc)) {$aolc .= "{$jsVarName}.setArrowIconLeft('{$this->arrowIconLeftSrc}', {$this->arrowIconLeftWidth}, {$this->arrowIconLeftHeight});\n";}
if (isSet($this->arrowIconRightSrc)) {$aolc .= "{$jsVarName}.setArrowIconRight('{$this->arrowIconRightSrc}', {$this->arrowIconRightWidth}, {$this->arrowIconRightHeight});\n";}
if (isSet($this->bgImgSrc)) {$aolc .= "{$jsVarName}.setBackgroundImage('{$this->bgImgSrc}', '{$this->bgImgRepeat}');\n";}
if (isSet($this->colorbar)) {if (is_string($this->colorbar)) {$aolc .= "{$jsVarName}.colorbar = '{$this->colorbar}';\n";} else {$aolc .= "{$jsVarName}.colorbar = new Object();\n";if (isSet($this->colorbar['color']))           $aolc .= "{$jsVarName}.colorbar['color']           = '{$this->colorbar['color']}';\n";if (isSet($this->colorbar['height']))          $aolc .= "{$jsVarName}.colorbar['height']          = {$this->colorbar['height']};\n";if (isSet($this->colorbar['widthDifference'])) $aolc .= "{$jsVarName}.colorbar['widthDifference'] = {$this->colorbar['widthDifference']};\n";if (isSet($this->colorbar['offsetLeft']))      $aolc .= "{$jsVarName}.colorbar['offsetLeft']      = {$this->colorbar['offsetLeft']};\n";if (isSet($this->colorbar['offsetTop']))       $aolc .= "{$jsVarName}.colorbar['offsetTop']       = {$this->colorbar['offsetTop']};\n";}
}
if (isSet($this->_attachedEvents)) {foreach($this->_attachedEvents as $trigger => $events) {foreach($events as $event) {$event = $this->_Bs_HtmlUtil->filterForJavaScript($event);$aolc .= "{$jsVarName}.attachEvent('{$trigger}', '{$event}');\n";}
}
}
$aolc .= "{$jsVarName}.draw('{$divVarName}');\n";$this->_form->addOnLoadCode($aolc);$ret .= '<div id="' . $divVarName . '"></div>';if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
function unpersistTrigger() {parent::unpersistTrigger();if (!empty($this->skin)) {$this->loadSkin($this->skin);}
}
}
?>