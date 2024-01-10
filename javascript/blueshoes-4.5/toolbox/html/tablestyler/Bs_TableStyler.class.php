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
require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");require_once($APP['path']['core'] . 'util/Bs_StopWatch.class.php');require_once($APP['path']['core'] . 'file/Bs_Dir.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core'] . 'html/table/Bs_HtmlTable.class.php');require_once($APP['path']['core'] . 'html/table/Bs_HtmlTableWindrose.class.php');class Bs_TableStyler extends Bs_Object {var $_sampleMatrix = array(array('NW', 'N',  'N', 'NE'),
array( 'W', 'C',  'C', 'E' ),
array( 'W', 'C',  'C', 'E' ),
array('SW', 'S',  'S', 'SE'));function Bs_TableStyler() {parent::Bs_Object();$this->_Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil']; }
function &_getTopLeftTable() {$tblData = array(array('NW', 'N',  'N', 'NE'),
array( 'W', 'C',  'C', 'E' ),
array( 'W', 'C',  'C', 'E' ),
array('SW', 'S',  'S', 'SE'));$windrose =& new Bs_HtmlTableWindrose();$windrose->setStyle('ALL','color:blue; background-color:lightgrey; font-family:arial; font-weight:bold; text-align:center;');$windrose->setStyle('NE' ,'color:skyblue; background-color:gray');$windrose->setStyle('NW' ,'color:skyblue; background-color:gray');$windrose->setStyle('SE' ,'color:skyblue; background-color:gray');$windrose->setStyle('SW' ,'color:skyblue; background-color:gray');$windrose->setStyle('C'  ,'color:aqua; background-color:black');$tbl =& new Bs_HtmlTable($tblData);$tbl->setWindroseStyle(&$windrose);return $tbl;}
function &_getFormInputTable(&$htmlWindroseObj) {$colSize=5;$rowSize=1;$data = $this->_matrix($rowSize, $colSize);$windroseMnomics = &$htmlWindroseObj->windroseMnomics;$rowPos = 0;$data[$rowPos][0] = 'Order';$data[$rowPos][1] = 'Mnomic';$data[$rowPos][2] = 'What';$data[$rowPos][3] = 'Transp.';$data[$rowPos][4] = 'Attribute or sytle data';$rowPos++;if (isSet($htmlWindroseObj)) {$styleArray = &$htmlWindroseObj->getStyleStrings();$attrArray = &$htmlWindroseObj->getAttrStrings();while (list($windroseID) = each($styleArray)) {$styleValue = $attrValue = '';$styleTransp = $attrTransp  = 'checked';if (isSet($styleArray[$windroseID])) {$styleValue = $styleArray[$windroseID];$styleTransp = ($htmlWindroseObj->isStyleTransparent($windroseID)) ? 'checked' : '';}
if (isSet($attrArray[$windroseID])) {$attrValue = $attrArray[$windroseID];$attrTransp  = ($htmlWindroseObj->isAttrTransparent($windroseID)) ? 'checked' : '';}
$data[$rowPos][0] = '<input type="text" size="2" name="input[wr]['.$rowPos.'][order]" value="' . 2*$rowPos. '">';$data[$rowPos][1] = '<select name="input[wr]['.$rowPos.'][mnomic]" size="1">' . $this->_Bs_HtmlUtil->arrayToHtmlSelect(&$windroseMnomics, $windroseID) .'</select>';$data[$rowPos][2] = 'Style :<br>Attr :';$data[$rowPos][3] = '<input type="checkbox" name="input[wr]['.$rowPos.'][style][transparent]" value="1" ' .$styleTransp. '><br>'.
'<input type="checkbox" name="input[wr]['.$rowPos.'][attr][transparent]" value="1" ' .$attrTransp. '>';$data[$rowPos][4] = '<input type="text" size="80" name="input[wr]['.$rowPos.'][style][data]" value="' . $styleValue. '"><br>'.
'<input type="text" size="80" name="input[wr]['.$rowPos.'][attr][data]" value="' .  $attrValue. '">';$rowPos++;}
}
$selectMnomics =  $this->_Bs_HtmlUtil->arrayToHtmlSelect(&$windroseMnomics, 'ALL');for ($i=$rowPos; $i<$rowPos+$rowSize; $i++) {$data[$rowPos][0] = '<input type="text" size="2" name="input[wr]['.$i.'][order]" value="' . 2*$i. '">';$data[$i][1] = '<select name="input[wr]['.$i.'][mnomic]" size="1">' .$selectMnomics.'</select>';$data[$i][2] = 'Style :<br>Attr :';$data[$i][3] = '<input type="checkbox" name="input[wr]['.$i.'][style][transparent]" value="1" checked><br>'.
'<input type="checkbox" name="input[wr]['.$i.'][attr][transparent]" value="1" checked>';$data[$i][4] = '<input type="text" size="80" name="input[wr]['.$i.'][style][data]" value=""><br>'.
'<input type="text" size="80" name="input[wr]['.$i.'][attr][data]" value="">';}
$windrose =& new Bs_HtmlTableWindrose();$windrose->setStyle('ALL', 'background-color:#e0e0e0');$windrose->setStyle('N', 'font-size:12px; font-family:arial; font-weight:bold; text-align:center;');$tbl =& new Bs_HtmlTable($data);$tbl->setTableAttr('border="0"  bordercolor="#000000" bordercolorlight="#e0e0e0" bordercolordark="#bcbcbc" style="background-color:#636363;"');$tbl->setWindroseStyle($windrose);return $tbl;}
function &_getTinyTable($windroseMnomic) {$windrose =& new Bs_HtmlTableWindrose();$windrose->setStyle('ALL', 'background-color:#e0e0e0; font-size:1pt');$windrose->setStyle($windroseMnomic, 'background-color:#ff3300; font-size:1pt');$tbl =& new Bs_HtmlTable($this->_matrix(4,4));$tbl->setTableAttr('border="2" width="100%" bordercolor="#000000" bordercolorlight="#e0e0e0" bordercolordark="#bcbcbc" style="background-color:#636363;"');$tbl->setWindroseStyle($windrose);return $tbl;}
function &_getMnomoicPictogram() {$windrose =& new Bs_HtmlTableWindrose();$windroseMnomics = &$windrose->windroseMnomics;$rowsToBuild = round((sizeOf($windroseMnomics) / 4) +.4);$tblData = &$this->_matrix(sizeOf($rowsToBuild),4);for ($row=0; $row<$rowsToBuild; $row++) {for ($i=0; $i<4; $i++) {$styleID = $windroseMnomics[$row*4+$i];$tblData[$row][$i*2] = $styleID;$tblData[$row][$i*2+1] = &$this->_getTinyTable($styleID);}
}
$windrose->setStyle('ZC_0','color:yellow; background-color:#0066ff; font-size:16px; font-family:arial; font-weight:bold; text-align:center;');$windrose->setStyle('ZC_1','background-color:#FFFFFF; text-align:center;');$tbl =& new Bs_HtmlTable($tblData);$tbl->setWindroseStyle(&$windrose);$data = $this->_matrix(1, 2);$data[0][0] = '<img src="windrose.gif" border="1" width="150" height="150">';$data[0][1] = &$tbl;return (new Bs_HtmlTable($data));}
function  &_toPhpCode(&$htmlWindroseObj) {$styleArray = &$htmlWindroseObj->getStyleStrings();$attrArray = &$htmlWindroseObj->getAttrStrings();$phpCode = '    <?php'."\n";$phpCode .= <<< EOD
      require_once(\$_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
      require_once(\$APP['path']['core'] . 'html/Bs_HtmlTable.class.php');
      require_once(\$APP['path']['core'] . 'html/Bs_HtmlTableWindrose.class.php');

      function &makeTable(\$tblData=NULL) {
        if (!isSet(\$tblData)) { // Use some default data if no data was set
          \$tblData = array(array('NW', 'N',  'N', 'NE'),
                           array( 'W', 'C',  'C', 'E' ),
                           array( 'W', 'C',  'C', 'E' ),
                           array('SW', 'S',  'S', 'SE'));
        }
        // Create a windrose object
        \$htmlWindroseObj =& new Bs_HtmlTableWindrose();

EOD;
$phpCode .= "\n        // Set styles:\n";while (list($windroseID) = each($styleArray)) {$styleValue = '';$styleTransp = 'TRUE';if (isSet($styleArray[$windroseID])) {$styleValue = $styleArray[$windroseID];$styleTransp = ($htmlWindroseObj->isStyleTransparent($windroseID)) ? '' : ', FALSE';$phpCode .= "        \$htmlWindroseObj->setStyle('{$windroseID}', '{$styleValue}'{$styleTransp});\n";}
}
$phpCode .= "\n        // Set attributes:\n";while (list($windroseID) = each($attrArray)) {$attrValue = '';$attrTransp  = 'TRUE';if (isSet($attrArray[$windroseID])) {$attrValue = $attrArray[$windroseID];$attrTransp  = ($htmlWindroseObj->isAttrTransparent($windroseID)) ? '' : ', FALSE';$phpCode .= "        \$htmlWindroseObj->setAttr('{$windroseID}', '{$attrValue}'{$attrTransp});\n";}
}
$phpCode .= "\n";$phpCode .= <<< EOD
        // Make Table
        \$tbl =& new Bs_HtmlTable(\$tblData);
        // Set the windrose object by refference so later changes to windrose will effect the table.
        \$tbl->setWindroseStyle(&\$htmlWindroseObj);
        return \$tbl;
      }
      
      //------------------------------------------------------ 
      \$aHtmlTbl = makeTable();
      echo \$aHtmlTbl->renderTable();
EOD;
$phpCode .= "\n    ?>\n";ob_start();highlight_string($phpCode);$phpCode = ob_get_contents();ob_end_clean();return $phpCode;}
function  _scanInputStyle(&$input, &$htmlWindroseObj) {$windroseData = &$input['wr'];if (!isSet($windroseData)) return $htmlWindroseObj;$sortedWindroseData = array();while (list($i) = each($windroseData)) {$styleData = &$windroseData[$i];$sortedWindroseData[$styleData['order']] = &$windroseData[$i];}
asort($sortedWindroseData);reset($sortedWindroseData);while (list($i) = each($sortedWindroseData)) {$styleData = &$sortedWindroseData[$i];$styleTransp = isSet($styleData['style']['transparent']) ? TRUE : FALSE;$attrTransp  = isSet($styleData['attr']['transparent']) ? TRUE : FALSE;$style = &$styleData['style']['data'];$attr  = &$styleData['attr']['data'];if (strLen($style)>0) {$htmlWindroseObj->setStyle($styleData['mnomic'], $style, $styleTransp);}
if (strLen($attr)>0) {$htmlWindroseObj->setAttr($styleData['mnomic'], $attr, $attrTransp);}
}
}
function &getEditHtml($input=NULL) {global $APP;$info = <<< EOD
    <SPAN STYLE=font-family:arial;>
    <h1>Sample use of the Windrose object</h1><div align="center">
    This page is to demonstrate the use of the "windrose"-object: <br><strong>Bs_HtmlTableWindrose.class.php</strong></div><br>
    With the help of <strong>Bs_HtmlTableWindrose.class.php</strong> syles and attributes can be set to a <strong>*RELATIVE*</strong> 
    positions. The positions are given by position "windrose"-mnomics like 'N', 'NW', ....
    (With the Bs_HtmlTable.class.php you are 'only' able to set syles and attributes to *ABSOLUTE* positions).
    <br><br>
      <strong>NOTE:</strong>
      <ul compact>
      <li>Use the correct syntax for syles and attributes. E.g.:<br>
      <strong>For Style:</strong> color:blue; background-color:lightgrey; font-family:arial; <br>
      <strong>For Attr:</strong> align=right  background=compassBg.gif</li>
      </ul>
    </SPAN>
EOD;
$input['todo'] = isSet($input['todo']) ? $input['todo'] : '';$phpCodeSample = '';if (isSet($input)) {$htmlWindroseObj =& new Bs_HtmlTableWindrose();if (($input['todo']==='readStyle') AND isSet($input['stylefile'])) {$styleFile = $APP['path']['bsRoot'] . 'toolbox/Html/TableStyler/styles/' . $input['stylefile'];$htmlWindroseObj->read($styleFile.'.style');}
if ($input['todo']==='readForm') {$this->_scanInputStyle($input, $htmlWindroseObj);if (isSet($input['stylefile']) AND isSet($input['submit']) AND ($input['submit']==='save style')) {$htmlWindroseObj->write('styles/'.$input['stylefile'].'.style');}
}
$phpCodeSample = &$this->_toPhpCode($htmlWindroseObj);}
$sampleTbl =& new Bs_HtmlTable($this->_sampleMatrix);$sampleTbl->setWindroseStyle(&$htmlWindroseObj);$sampleTbl->setTableAttr(array('cellspacing'=>'0', 'cellpadding'=>'0'));$saveStyleName = isSet($input['stylefile']) ? $input['stylefile'] : 'default';$data = $this->_matrix(3, 2);$data[0][0] = $this->_getMnomoicPictogram();$data[1][0] = &$sampleTbl;$data[1][1] = 'Save windrose style and attr. to file:<br><input type="text" name="input[stylefile]" size="35" value="'.$saveStyleName.'"><BR><input type="submit" name="input[submit]" value="save style"><br><br>';$data[2][0] = $this->_getFormInputTable($htmlWindroseObj);$tbl =& new Bs_HtmlTable($data);$tbl->setRowAttr(1,array('align'=>'center'));$tbl->spanCol(0);$tbl->spanCol(2);$tblString = &$tbl->renderTable();$action = $_SERVER['PHP_SELF'];$out = <<< EOD
    {$info}
    <CENTER>
      <form action="{$action}" method="post">  
        <input type="hidden" name="input[todo]" value="readForm">
        {$tblString}
        <input type="submit" name="input[submit]" value="set style">
      </form>
      <TABLE border="1" bgcolor="#f0f0f0">
        <tr><td><SPAN STYLE="font-size:16px; font-family:arial">The Code for sample above could look like this:</SPAN>    
        </td></tr>
        <tr><td>
          <SPAN STYLE="font-size:11px;">
          {$phpCodeSample}
          </SPAN>    
        </td></tr>
      </TABLE>
    </CENTER>
EOD;
return $out;}
function &getStyles() {$action = $_SERVER['PHP_SELF'];$out  = "<CENTER><form action='{$action}' method='post' target='edit'> \n";$out .= '<input type="hidden" name="input[todo]" value="readStyle">' . "\n";$htmlWindroseObj =& new Bs_HtmlTableWindrose();global $APP;$aDir =& new Bs_Dir($APP['path']['bsRoot'] . 'toolbox/Html/TableStyler/styles/');$fileList = $aDir->getFileList(array('regEx'=>'\.style$'));$tbl =& new Bs_HtmlTable($this->_sampleMatrix);$tbl->setTableAttr(array('cellspacing'=>'0', 'cellpadding'=>'0'));for ($i=0; $i<sizeOf($fileList); $i++) {$htmlWindroseObj->read($fileList[$i]);$tbl->setWindroseStyle($htmlWindroseObj);$out .= $tbl->renderTable();$fileName = basename($fileList[$i]);$fileName = substr($fileName, 0, strpos($fileName, '.'));$out .= '<input type="submit" name="input[stylefile]" value="'.$fileName.'">';$out .= '<br><br>';}
$out .= '</form></CENTER>';return $out;}
function &_matrix($row, $col, $value='&nbsp;') {$matrix = array();for ($i=0; $i<$row; $i++) {for ($j=0; $j<$col; $j++) {$matrix[$i][$j] = $value;}
}
return $matrix;}
}  
if (isSet($_REQUEST['input'])) {$input = $_REQUEST['input'];} else { $html = <<< EOD
    <html>
      <frameset cols="80%,*">
        <frame src="{$_SERVER['PHP_SELF']}?input[target]=edit" name="edit">
        <frame src="{$_SERVER['PHP_SELF']}?input[target]=stylelist" name="stylelist">
      </frameset>
    </html>
EOD;
echo $html;exit;} 
$stopWatch = $GLOBALS['Bs_StopWatch'];$stopWatch->reset();$tbl =& new Bs_TableStyler;if (isSet($input['target']) AND ($input['target']==='stylelist')) {$out = &$tbl->getStyles();} else {$out = &$tbl->getEditHtml($input);}
$stopWatch->takeTime('Time');$time = &$stopWatch->toHtml();$html = <<< EOD
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
      <html>
      <head>
          <title>Sample of Bs_HtmlTableWindrose.class.php use</title>
      </head>
      <body background="compassBg.gif">
        {$out}
      </body>
      </html>
EOD;
echo $html;?>