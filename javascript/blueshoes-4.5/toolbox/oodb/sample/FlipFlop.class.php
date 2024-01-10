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
class FlipFlop {function FlipFlop() {global $HTTP_SERVER_VARS;$this->self  = $_SERVER['PHP_SELF'];}
function setup($fullSet, $subSet) {$this->fullSet = &$fullSet;$this->subSet = &$subSet;}
function &render($formName='MyForm', $id='', $resultName='textField') {$jsHead ='masterData = new Array()' . "\n";reset($this->fullSet);while(list($key) = each($this->fullSet)) {$jsHead .= "masterData[{$key}] = {$this->fullSet[$key]['caption']}\n";}
$jsHead .= "myFlipFlop_{$id} = new FlipFlop();\n";$jsHead .= "myFlipFlop_{$id}.setMasterData(masterData);\n";$firstLoop = TRUE; $preMarkedMasterVals = '';reset($this->subSet);while (list($masterID) = each($this->subSet)) {if ($firstLoop) {$firstLoop = FALSE;} else {$preMarkedMasterVals .= ',';}
$preMarkedMasterVals .= "'$masterID'";}
if (!empty($preMarkedMasterVals)) {$jsHead .= "myFlipFlop_{$id}.setPreMark( 'master', new Array({$preMarkedMasterVals}) );\n";}
$scriptTag = 'script';$htmlOut = <<< EOD
      <html>
      <head>
          <title>Untitled</title>
        <script language="JavaScript" src="./FlipFlop.js.html"></{$scriptTag}>
        <script language="JavaScript">
          {$jsHead} 
        </{$scriptTag}>
      </head>
      <body style="font-family: Verdana, Arial; font-size: 12px;" background="" link="#0000FF" vlink="#000080">
        <form name='{$formName}' action='{$this->self}' method='post'>
          <input type="hidden" name="{$resultName}" value="">
          <table>
          <tr>
            <td>Available</td>
            <td> </td>
            <td>Selected</td>
          </tr><tr>
            <td>
              <select size="6" name="L_select_{$id}" multiple>
              </select>
            </td>
            <td align="center">
              <input type="button" value=" >> " onClick="myFlipFlop_{$id}.moveSelect2Select('masterFlip', 'masterFlop');">
              <br><br>
              <input type="button" value=" << " onclick="myFlipFlop_{$id}.moveSelect2Select('masterFlop', 'masterFlip');">
            </td>
            <td>
              <select size="6" name="R_select_{$id}" multiple>
              </select>
            </td>
          </tr>
          </table>
          <input type="submit">
        </form>
        <script language="JavaScript" >
          myFlipFlop_{$id}.setSelectObj("master", self.document.forms['{$formName}'].L_select_{$id}, self.document.forms['{$formName}'].R_select_{$id});
          myFlipFlop_{$id}.setResultTextObj("master", self.document.forms['{$formName}'].{$resultName}); 
          myFlipFlop_{$id}.setup();
        </{$scriptTag}>
      </body>
      </html>
EOD;
return $htmlOut;}
function prepare(&$ooDb, &$fullSetObj, &$subSetObj) {$subSet = $fullSet = array();if (!empty($fullSetObj)) {reset($fullSetObj);while(list($key) = each($fullSetObj)) {$objID = $ooDb->getId($fullSetObj[$key]);$fullSet[$objID] = $fullSetObj[$key]->caption;}
}
if (!empty($subSetObj)) {reset($subSetObj);while(list($key) = each($subSetObj)) {$objID = $ooDb->getId($subSetObj[$key]);$subSet[$objID] = $subSetObj->caption;}
}
$this->setup($fullSet, $subSet);}
function getFlipFlop($formName='MyForm', $id='', $resultName='textField') {$jsHead ="masterData_{$id} = new Array()\n";reset($this->fullSet);while(list($key) = each($this->fullSet)) {$jsHead .= "masterData_{$id}[{$key}] = '{$this->fullSet[$key]}';\n";}
$jsHead .= "myFlipFlop_{$id} = new FlipFlop();\n";$jsHead .= "myFlipFlop_{$id}.setMasterData(masterData_{$id});\n";$firstLoop = TRUE; $preMarkedMasterVals = '';reset($this->subSet); $i=0;while (list($masterID) = each($this->subSet)) {if ($firstLoop) {$jsHead  .= "preMarked = new Array();\n";$firstLoop = FALSE;}
$jsHead  .= "preMarked[{$i}] = {$masterID};\n";$i++;}
if (sizeOf($this->subSet)) {$jsHead .= "myFlipFlop_{$id}.setPreMark('master', preMarked);\n";}
$scriptTag = 'script';$htmlHead = <<< EOD
        <script language="JavaScript" src="./FlipFlop.js.html"></{$scriptTag}>
        <script language="JavaScript">
          {$jsHead} 
        </{$scriptTag}>
EOD;
$htmlForm = <<< EOD
          <input type="hidden" name="{$resultName}" value="">
          <table>
          <tr>
            <td>Available</td>
            <td> </td>
            <td>Selected</td>
          </tr><tr>
            <td>
              <select size="6" name="L_select_{$id}" multiple>
              </select>
            </td>
            <td align="center">
              <input type="button" value=" >> " onClick="myFlipFlop_{$id}.moveSelect2Select('masterFlip', 'masterFlop');">
              <br><br>
              <input type="button" value=" << " onclick="myFlipFlop_{$id}.moveSelect2Select('masterFlop', 'masterFlip');">
            </td>
            <td>
              <select size="6" name="R_select_{$id}" multiple>
              </select>
            </td>
          </tr>
          </table>
EOD;
$htmlPostForm = <<< EOD
        <script language="JavaScript" >
          myFlipFlop_{$id}.setSelectObj("master", self.document.forms['{$formName}'].L_select_{$id}, self.document.forms['{$formName}'].R_select_{$id});
          myFlipFlop_{$id}.setResultTextObj("master", self.document.forms['{$formName}'].elements['{$resultName}']); 
          myFlipFlop_{$id}.setup();
        </{$scriptTag}>
EOD;
return array(&$htmlHead, &$htmlForm, &$htmlPostForm);}
}
if (basename($_SERVER['PHP_SELF']) == 'FlipFlop.class.php') {$x = new FlipFlop();$x->setup(
array(1=>'1',2=>'2',3=>'3',4=>'4',5=>'5',6=>'6',7=>'7',8=>'8',),
array(3=>'3',4=>'4')
);echo $x->render();}
?>