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
require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");require_once('Bs_TableStyler.class.php');$ts =& new Bs_TableStyler;?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Unbenannt</title>
<style>
.bsTabRegisterOn {height: 25;width: 100;background-color:menu;cursor:pointer; cursor:hand;font-family: arial;font-size: 12px;text-align: center;font-weight: bold;border-top: 1px solid white;border-right: 1px solid #404040;border-left: 1px solid white;}
.bsTabRegisterOff {height: 20;width: 80;background-color:menu;cursor:pointer; cursor:hand;font-family: arial;font-size: 12px;text-align: center;border-top: 1px solid white;border-right: 1px solid #404040;border-left: 1px solid white;}
.registerContent {background-color:white;font-family: arial;font-size: 11px;}
td {font-family: arial;font-size: 12px;}
</style>
<script language="javascript">
<?php
if ((@$_REQUEST['input']['submitSave']) || (@$_REQUEST['input']['submitSet'])) {echo "parent.frames['stylelist'].location.reload();\n";}
?>
var styleBsTabRegisterOn  = "  height: 25;  background-color:menu;  cursor:pointer; cursor:hand;  border-top: 1px solid white;  border-right: 1px solid #404040;  border-left: 1px solid white;";var styleBsTabRegisterOff = "  height: 20;  background-color:menu;  cursor:pointer; cursor:hand;";function bsTabContToggleReg(registerId) {var eCont = document.getElementById(registerId + 'content');if (eCont.style.display == 'none') {bsTabContHideAll();eCont.style.display = 'block';var eReg  = document.getElementById(registerId);//document.all[registerId]['class'] = 'bsTabRegisterOn';//eReg.style['class'] = 'bsTabRegisterOn';//eReg.style = styleBsTabRegisterOn;eReg.className = 'bsTabRegisterOn';}
}
function bsTabContHideAll() {var a = new Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');for (var i=0; i<a.length; i++) {var eCont = document.getElementById('reg' + a[i] + 'content');if (!eCont) return;var eReg  = document.getElementById('reg' + a[i]);if (!eReg) return;eCont.style.display = 'none';//eReg['class'] = 'bsTabRegisterOff';//eReg.style = styleBsTabRegisterOff;//eReg.style.height = '20';eReg.className = 'bsTabRegisterOff';}
}
function initTab() {<?php
if (isSet($_REQUEST['showTab'])) {echo "var reg = '{$_REQUEST['showTab']}';\n";} else {echo "var reg = '" . ((isSet($_REQUEST['input'])) ? 'B' : 'A') . "';\n";}
?>
//var reg = '<?php echo (isSet($_REQUEST['showTab']) || isSet($_REQUEST['input'])) ? 'B' : 'A';?>';bsTabContToggleReg('reg' + reg);}
//isSet($_REQUEST['input'])
</script>
</head>
<body bgcolor="antiquewhite" onLoad="initTab();">
<div>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="bottom"><div id="regA" class="bsTabRegisterOff" onClick="bsTabContToggleReg(this.id);">&nbsp;Info&nbsp;</div></td>
<td valign="bottom"><div id="regB" class="bsTabRegisterOff" onClick="bsTabContToggleReg(this.id);">&nbsp;Editor&nbsp;</div></td>
<td valign="bottom"><div id="regC" class="bsTabRegisterOff" onClick="bsTabContToggleReg(this.id);">&nbsp;Style File&nbsp;</div></td>
<td valign="bottom"><div id="regD" class="bsTabRegisterOff" onClick="bsTabContToggleReg(this.id);">&nbsp;PHP-Code&nbsp;</div></td>
</tr>
</table>
<div style="border:1px solid black; background-color:white;">
<div id="regAcontent" style="display:none;" class="registerContent">
<?php
echo $ts->getInfoTable();?>
</div>
<div id="regBcontent" style="display:none;" class="registerContent">
<?php
$input = (isSet($_REQUEST['input'])) ? $_REQUEST['input'] : NULL;$editHtml = $ts->getEditHtml($input);echo $editHtml[0]; ?>
</div>
<div id="regCcontent" style="display:none;" class="registerContent">
To update this view go to the "Editor" tab and save the style file.<br><br>
<div contentEditable style="background:#F0F0F0; border:1px solid black;">
<?php
if (@$_REQUEST['input']['stylefile']) {?>
<code><pre><?php echo $ts->getStyleFile($_REQUEST['input']['stylefile']); ?></pre></code>
<?php
} else {echo 'no style file';}
?>
</div>
</div>
<div id="regDcontent" style="display:none;" class="registerContent">
To update this view go to the "Editor" tab and push the "set styles" button.<br><br>
<?php
echo $editHtml[1]; ?>
</div>
</div>
</div>
</body>
</html>
