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
function getList() {}
$innerUrl = $_SERVER['PHP_SELF'] . '?page=inner';?>
<html>
<head>
<title>BlueShoes File Browser</title>
<script type="text/javascript" src="/_bsJavascript/components/dropdown/Bs_Dropdown.class.js"></script>
<script type="text/javascript" src='/_bsJavascript/core/lang/Bs_Misc.lib.js'></script>
<script type="text/javascript" src="/_bsJavascript/core/lang/Bs_Array.class.js"></script>
<script type="text/javascript" src="/_bsJavascript/core/form/Bs_FormUtil.lib.js"></script>
<script type="text/javascript" src="/_bsJavascript/core/form/Bs_FormFieldSelect.class.js"></script>
<script language="JavaScript" src="/_bsJavascript/components/toolbar/Bs_ButtonBar.class.js" type="text/javascript"></script>
<script language="JavaScript" src="/_bsJavascript/components/toolbar/Bs_Button.class.js" type="text/javascript"></script>
<script language="javascript">
<!--
function bs_isGecko() {//rather poor implementation.
if (navigator.appName == "Microsoft Internet Explorer") return false;return (navigator.userAgent.match(/gecko/i));}
if (bs_isGecko()) {document.writeln("<link rel='stylesheet' href='/_bsJavascript/components/toolbar/win2k_mz.css'>");} else {document.writeln("<link rel='stylesheet' href='/_bsJavascript/components/toolbar/win2k_ie.css'>");}
<?php
if (isSet($_REQUEST['callbackFunction'])) {echo 'var callbackFunction = "' . $_REQUEST['callbackFunction'] . '";';} else {echo 'var callbackFunction = "fileBrowserCallback";';}
?>
function setValueBack() {//let's see if something is selected.
var inputFld = document.getElementById('inputFile');if (inputFld.value == '') return; //could issue a warning here.
var evalStr = '';evalStr += (opener) ? 'opener.' : 'parent.';evalStr += callbackFunction + '("' + inputFld.value + '");';//try {eval(evalStr);if (opener) window.close();//} catch (e) {//  alert("error: failed executing the command: \n" + evalStr + "\nthe error was:\n" + e);//}
}
//-->
function goDirUp() {browser.location.href = '<?php echo $innerUrl;?>&path=' + getCurrentPath() + '../' + '&viewStyle=' + getViewStyle();}
function gotoDir(dir) {browser.location.href = '<?php echo $innerUrl;?>&path=' + dir + '&viewStyle=' + getViewStyle();}
function updateInner() {browser.location.href = '<?php echo $innerUrl;?>&path=' + getCurrentPath() + '&viewStyle=' + getViewStyle();}
function getCurrentPath() {//return pathString.innerText; //old code without dropdown.
return myPath.getValue();}
/**
* returns the view mode.
* one of 'detail' (default), 'thumbnails'.
* @return string
*/
function getViewStyle() {if (b3.getStatus() == 2) return 'thumbnails';return 'detail';}
function initToolbar() {bar = new Bs_ButtonBar();bar.useHelpBar = false;b1 = new Bs_Button();b1.objectName = 'b1';b1.title = 'Go up';b1.imgName = 'bs_goDirUp';b1.attachEvent(goDirUp);bar.addButton(b1);bar.newGroup();b2 = new Bs_Button();b2.objectName = 'b2';b2.group      = 'view';b2.title      = 'Detail view';b2.imgName    = 'bs_fileViewDetail';b2.attachEvent(updateInner);b2.setStatus(2);bar.addButton(b2);b3 = new Bs_Button();b3.objectName = 'b3';b3.group      = 'view';b3.title      = 'Thumbnail view';b3.imgName    = 'bs_fileViewThumbnails';b3.attachEvent(updateInner);bar.addButton(b3);bar.drawInto('buttonBar');}
function initPathDropdown() {myPath = new Bs_Dropdown();myPath.objectName = 'myPath';myPath.setValue('/');//myPath.addOption('http://www.yahoo.com/');myPath.classInput  = 'dropdownInput';myPath.classSelect = 'dropdownSelect';myPath.drawInto('myPathDiv');myPath.attachEvent('onChange', pathUpdated);}
function pathUpdated(comboObj) {gotoDir(comboObj.getValue());}
function init() {initPathDropdown();initToolbar();}
</script>
<style TYPE="text/css">
td { font-family: arial, helvetica; font-size: 10pt; color: #000000; }
td.small { font-family: arial, helvetica; font-size: 8pt; color: #000000; }
td.bar { font-family: arial, helvetica; font-size: 2pt; color: #000000; }
.dropdownInput {width:335;}
.dropdownSelect {width:355;}
</style>
</head>
<body leftmargin=1 topmargin=1 bgcolor="#C6C7C6" onLoad="init();">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="40">Path: </td>
<td><div id="myPathDiv"></div></td>
<td><div id="buttonBar"></div></td>
</tr>
<tr>
<td colspan="100%" width="100%">
<iframe src="<?php echo $innerUrl;?>" name="browser" id="browser" width="100%" height="245" marginwidth="0" marginheight="0" align="left" style="overflow:auto;">you need iframes</iframe>
</td>
</tr>
<tr>
<td colspan="100%">
<input type="text" name="inputFile" id="inputFile" size="58" maxlength="300" style="width:450px;"> 
<input type="button" name="ok" value="OK" onClick="setValueBack();" style="width:40px;">
</td>
</tr>
</table>
</body>
</html>
