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
define('BS_FORMFIELDSPREADSHEET_VERSION',      '4.5.$Revision: 1.5 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core']      . 'html/Bs_HtmlUtil.class.php');class Bs_FormFieldSpreadsheet extends Bs_FormField {var $Bs_HtmlUtil;var $showLineNumbers;var $firstRowTitle;var $firstColTitle;var $useToolbar;var $mayUseFormat;var $mayUseAlign;var $mayUseWysiwyg;var $numCols;var $numRows;var $defaultCellWidth;var $defaultCellHeight;var $sheetWidth;var $sheedHeight;function Bs_FormFieldSpreadsheet() {$this->Bs_FormField(); $this->fieldType = 'spreadsheet';$this->persister->setVarSettings(&$this->persisterVarSettings);$this->Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil'];}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);$this->_markAsUsed();$this->_form->addIncludeOnce('/_bsJavascript/core/lang/Bs_Misc.lib.js');$this->_form->addIncludeOnce('/_bsJavascript/core/html/Bs_HtmlUtil.lib.js');$this->_form->addIncludeOnce('/_bsJavascript/core/lang/Bs_Array.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/spreadsheet/Bs_SpreadSheet.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/spreadsheet/Bs_SpreadSheet.inc.js');$this->_form->addIncludeOnce('/_bsJavascript/components/toolbar/Bs_ButtonBar.class.js');$this->_form->addIncludeOnce('/_bsJavascript/components/toolbar/Bs_Button.class.js');$this->_form->addIntoHead("
<script>
function bs_isGecko() {//rather poor implementation.
if (navigator.appName == 'Microsoft Internet Explorer') return false;return (navigator.userAgent.match(/gecko/i));}
if (bs_isGecko()) {document.writeln(\"<link rel='stylesheet' href='/_bsJavascript/components/toolbar/win2k_mz.css'>\");} else {document.writeln(\"<link rel='stylesheet' href='/_bsJavascript/components/toolbar/win2k_ie.css'>\");}
</script>
");if (($this->valueDefaultType === 'array') && (@is_array($this->valueDefault))) {$dataArrPhp = $this->valueDefault;} else {$dataArrPhp = array();}
$dataArrJs = $this->Bs_HtmlUtil->arrayToJsArray($dataArrPhp, 'data');$jsObjName = $fieldName . '_obj';$jsObjName = 'mySpreadSheet'; $aolc = "
{$dataArrJs}
{$jsObjName} = new Bs_SpreadSheet;{$jsObjName}.objectName = '{$jsObjName}';{$jsObjName}.returnType = 'returnType';";if (isSet($this->showLineNumbers))   $aolc .= "{$jsObjName}.showLineNumbers   = " . boolToString($this->showLineNumbers) . ";\n";if (isSet($this->firstRowTitle))     $aolc .= "{$jsObjName}.firstRowTitle     = " . boolToString($this->firstRowTitle)   . ";\n";if (isSet($this->firstColTitle))     $aolc .= "{$jsObjName}.firstColTitle     = " . boolToString($this->firstColTitle)   . ";\n";if (isSet($this->useToolbar))        $aolc .= "{$jsObjName}.useToolbar        = " . boolToString($this->useToolbar)      . ";\n";if (isSet($this->mayUseFormat))      $aolc .= "{$jsObjName}.mayUseFormat      = " . boolToString($this->mayUseFormat)    . ";\n";if (isSet($this->mayUseAlign))       $aolc .= "{$jsObjName}.mayUseAlign       = " . boolToString($this->mayUseAlign)     . ";\n";if (isSet($this->mayUseWysiwyg))     $aolc .= "{$jsObjName}.mayUseWysiwyg     = " . boolToString($this->mayUseWysiwyg)   . ";\n";if (isSet($this->numCols))           $aolc .= "{$jsObjName}.numCols           = {$this->numCols};\n";if (isSet($this->numRows))           $aolc .= "{$jsObjName}.numRows           = {$this->numRows};\n";if (isSet($this->defaultCellWidth))  $aolc .= "{$jsObjName}.defaultCellWidth  = {$this->defaultCellWidth};\n";if (isSet($this->defaultCellHeight)) $aolc .= "{$jsObjName}.defaultCellHeight = {$this->defaultCellHeight};\n";if (isSet($this->sheetWidth))        $aolc .= "{$jsObjName}.sheetWidth        = {$this->sheetWidth};\n";if (isSet($this->sheedHeight))       $aolc .= "{$jsObjName}.sheedHeight       = {$this->sheedHeight};\n";$aolc .= "{$jsObjName}.init(data, 'dataTableDiv', 'bs_ss_callbackOnSave');\n
menubar();";$this->_form->addOnLoadCode($aolc);$this->_form->addIntoHead("
<script>
function bs_ss_callbackOnSave(val) {var cvsData = {$jsObjName}.exportDataToCsv();document.getElementById('{$fieldName}').value = cvsData;}
</script>
");if (!is_array($this->_form->events))          $this->_form->events             = array();if (!isSet($this->_form->events['onSubmit'])) $this->_form->events['onSubmit'] = '';$this->_form->events['onSubmit'] .= $jsObjName . '.save();';$ret .= '<div id="dataTableDiv" style="width:500; height:350; overflow:auto;"></div>';$ret .= '<input type="hidden" name="' . $fieldName . '" id="' . $fieldName . '" value="">';return $ret;$dataArray = $this->getValue($explodeKey);if (!is_array($dataArray) || empty($dataArray)) {$dataArray = array(
array('', '', ''), 
array('', '', ''), 
array('', '', ''), 
);}
$jsArray = $this->Bs_HtmlUtil->arrayToJsArray($dataArray, 'data');$strScript = '</script>'; $ret = <<< EOD
<!--  name="iframeSpreadSheet" -->
<iframe 
  id="iframe_{$fieldName}" 
  name="iframe_{$fieldName}" 
  src="about:blank" 
  width="500" 
  height="330" 
  onblur="iframe_{$fieldName}.invokeSave();" 
></iframe>

<input type="hidden" name="{$fieldName}" id="{$fieldName}" value="">

  <script language="JavaScript"><!--
    function bs_ss_edit(data) {
      var iframeSs = document.getElementById("iframe_{$fieldName}");
      iframeSs.src = "/_admin/spreadSheet/spreadSheet.html";
    }
    function bs_ss_callback(data) {
      //alert("todo: saving data ...");
      //document.getElementById("tableContent").innerHTML = bs_ss_window.mySpreadSheet.toHtml();
      
      //alert(data);
      document.getElementById("{$fieldName}").value = data;
    }
    function bs_ss_init() {
      //var iframeSs = document.getElementById("iframeSpreadSheet");
      //bs_ss_window.mySpreadSheet.init(data, "bs_ss_callback");
      //iframeSs.mySpreadSheet.init(data, "bs_ss_callback");
      document.iframe_{$fieldName}.mySpreadSheet.init(data, "bs_ss_callback", "csv");
    }
    
    {$jsArray}
bs_ss_edit(data);
  //-->
{$strScript}
EOD;
return $ret;switch ($this->getVisibility()) {case 'omit':
return $ret;break;case 'invisible':
$this->_markAsUsed();return $this->getFieldAsHidden();break;case 'read':
case 'readonly':
$this->_markAsUsed();$fieldName .= '_readonly';$ret .= $this->getFieldAsHidden();$ret .= "<textarea readonly disabled";break;case 'show':
$ret .= $this->_getTagStringValue($explodeKey);return $ret;break;default: $this->_markAsUsed();$ret .= "<textarea";}
if (!is_null($explodeKey)) {$ret .= " name=\"{$fieldName}[{$explodeKey}]\"";} else {$ret .= " name=\"{$fieldName}\"";}
$ret .= " cols=\"{$this->cols}\"";$ret .= " rows=\"{$this->rows}\"";$ret .= " wrap=\"{$this->wrap}\"";if (isSet($this->direction)) $ret .= " dir=\"{$this->direction}\"";$ret .= $this->_getTagStringStyles();$ret .= $this->_getTagStringEvents();$ret .= $this->_getTagStringAdditionalTags();$ret .= '>';$ret .= $this->_getTagStringValue($explodeKey);$ret .= '</textarea>';if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
}
?>