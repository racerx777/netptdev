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
require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");require_once($APP['path']['plugins']      . 'onomastics/Bs_Om_OnoGraphHtml.class.php');set_time_limit(30);function names_sort($a, $b) {if ($a['caption'] > $b['caption']) {return 1;} elseif ($a['caption'] < $b['caption']) {return -1;} else {return 0;}
}
$searchForm  = '';$searchForm .= '<form name="searchForm">';$searchForm .= 'Start with the name:<br>';$searchForm .= '<input type="text" name="mainFirstName" value="">';$searchForm .= '<input type="submit" name="searchFormButton" value="Go">';$searchForm .= '</form>';$addNameForm  = '';$addNameForm .= '<form name="addNameForm">';$addNameForm .= 'Add the name:<br>';$addNameForm .= '<input type="hidden" name="mainFirstName" value="' . $_REQUEST['mainFirstName'] . '">';$addNameForm .= '<input type="text" name="addNameText" value="">';$addNameForm .= '<input type="submit" name="addNameFormButton" value="Go">';$addNameForm .= '<bs:addNameSelect/>';$addNameForm .= '</form>';$head  = '<table><tr>';$head .= '<td valign="top">' . $searchForm . '</td>';$head .= '<td valign="top">' . $addNameForm . '</td>';$head .= '</tr></table>';$html  = '';$html .= '<bs:bodyStart/>';$html .= '<bs:bodyEnd/>';if (!isSet($_REQUEST['mainFirstName'])) {} else {do {if (!is_numeric($_REQUEST['mainFirstName'])) {$firstNameArray = $GLOBALS['Bs_Om_OnomasticsServer']->findFirstname($_REQUEST['mainFirstName'], $gender=0, $strict=FALSE, $returnData=TRUE);if (sizeOf($firstNameArray) == 0) {$html .= "nothing found";break;} elseif (sizeOf($firstNameArray) > 1) {$html .= sizeOf($firstNameArray) . " names found:<br>";foreach ($firstNameArray as $nameID => $nameHash) {$html .= 'ID: ' . $nameID . ' <a href="' . $_SERVER['PHP_SELF'] . '?mainFirstName=' . $nameID . '">' . $nameHash['caption'] . '</a> (' . $nameHash['strSex'] . ') origin: ' . $nameHash['strOrigin'] . ' definition:' . strip_tags($nameHash['strDefinition']) . '<br>';}
break;} else {$firstNameID = key($firstNameArray);}
} else {$firstNameID = $_REQUEST['mainFirstName'];}
$firstNameArray = $GLOBALS['Bs_Om_OnomasticsServer']->findFirstname($_REQUEST['mainFirstName'], $gender=0, $strict=FALSE, $returnData=TRUE);if (!empty($_REQUEST['addNameText']) || !empty($_REQUEST['addNameSelect'])) {$addNameSelect  = '';$hiddenSelectCaption = '';$addNameSelect .= '<select name="addNameSelect[]" id="addNameSelect" multiple size="4">';if (!empty($_REQUEST['addNameText'])) {$addNameArray = $GLOBALS['Bs_Om_OnomasticsServer']->findFirstname($_REQUEST['addNameText'], $gender=0, $strict=FALSE, $returnData=TRUE);foreach ($addNameArray as $nameID => $nameHash) {$addNameSelect       .= '<option value="' . $nameID . '">' . $nameHash['caption'] . '</option>';$hiddenSelectCaption .= '<input type="hidden" name="' . $nameID . '_caption" value="' . $nameHash['caption'] . '">';}
}
if (!empty($_REQUEST['addNameSelect'])) {foreach ($_REQUEST['addNameSelect'] as $nameID) {$addNameSelect       .= '<option value="' . $nameID . '" selected>' . $_REQUEST[$nameID . '_caption'] . '</option>';$hiddenSelectCaption .= '<input type="hidden" name="' . $nameID . '_caption" value="' . $_REQUEST[$nameID . '_caption'] . '">';}
}
$addNameSelect .= '</select>';$addNameSelect .= $hiddenSelectCaption;$head = str_replace('<bs:addNameSelect/>', $addNameSelect, $head);}
$onoGraph =& new Bs_Om_OnoGraphHtml();$limit = 2;if (isSet($_REQUEST['limit'])) $limit = (int)$_REQUEST['limit'];$html = $onoGraph->createGraph($firstNameID, $limit, 4, @$_REQUEST['addNameSelect']);$names = $onoGraph->_allWords;usort($names, 'names_sort');$nameList = '<div style="position:absolute; left:850;">';foreach ($names as $nameArr) {$nameList .= '<span';$nameList .= ' onMouseOver="document.getElementById(\'div_' . $nameArr['ID'] . '\').style.border=\'5px solid Blue\';"';$nameList .= ' onMouseOut ="document.getElementById(\'div_' . $nameArr['ID'] . '\').style.border=\'thin solid Blue\';"';if ($nameArr['sex'] == 1) {$nameList .= ' style="color:#006E6D;"';} elseif ($nameArr['sex'] == 2) {$nameList .= ' style="color:#C400C6;"';} else {$nameList .= ' style="color:#A6A800;"';}
$nameList .= '>';if ($nameArr['ID'] == $firstNameID) {$nameList .= '<b>' . $nameArr['caption'] . '</b>';} else {$nameList .= $nameArr['caption'];}
$nameList .= '</span><br>';}
$nameList .= '</div>';} while (FALSE);}
$docHead  = '';$docHead .= '
<script type="text/javascript" src="/_bsJavascript/core/lang/Bs_Misc.lib.js"></script>
<script type="text/javascript" src="/_bsJavascript/core/form/Bs_FormFieldSelect.class.js"></script>
<script type="text/javascript" src="/_bsJavascript/components/flipflop/Bs_FlipFlop.class.js"></script>
<script type="text/javascript" src="/_bsJavascript/components/toolbar/Bs_Button.class.js"></script>
<script>
if (moz) {document.writeln("<link rel=\'stylesheet\' href=\'/_bsJavascript/components/toolbar/win2k_mz.css\'>");} else {document.writeln("<link rel=\'stylesheet\' href=\'/_bsJavascript/components/toolbar/win2k_ie.css\'>");}
</script>
<style>
.flipFlopField {font-family:arial,helvetica;font-size:11px;width:100px;}
</style>
';if (isSet($nameList)) {$head .= $nameList;}
$html = str_replace('<bs:bodyEnd/>', $head, $html);$html = str_replace('<bs:head/>', $docHead, $html);echo $html;?>