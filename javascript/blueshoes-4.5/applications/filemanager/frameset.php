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
?>
<html>
<head>
<title>BlueShoes File Manager</title>
<script language="javascript">
<!--
var fileSelected = new Array();var dirSelected  = new Array();/**
* it looks like delete() is a reserved name.
//deprecated
function deleteFiles() {if (fileSelected.length == 0) {alert("Cannot delete - no file(s) selected.");return;} else if (fileSelected.length == 1) {//q = "Are you sure you want to delte '" + x + "'?";q = "Are you sure you want to delte this file?";} else {q = "Are you sure you want to delete these " + fileSelected.length + "items?";}
doIt = confirm(q);if (doIt == false) return;alert("ok, let's implement that function...");}
*/
var bs_tryAgainArray = new Array();function doForMe(func, frameName) {var evalStr = "document.frames['" + frameName + "']." + func;try {eval(evalStr);} catch (e) {//alert(e); //4debug
/*
* the cause can be:
* 1) a frameset is not loaded yet
* 2) a coder changed something that's not working
* 3) a browser does not support something
* i coded this because of the first one.
* now we're gonna set a timer to execute it again in x seconds.
*/
bs_tryAgain(evalStr, 2, 5);}
}
/**
* 
*/
function bs_tryAgain(evalStr, interval, tries) {var newEntry = bs_tryAgainArray.length;bs_tryAgainArray[newEntry] = new Array(evalStr, interval, tries);setTimeout('timeoutCallback(' + newEntry + ');', interval *1000);}
/**
* gets called from javascript itself after a setTimeout().
* @param int entryId
* @see   bs_tryAgain()
*/
function timeoutCallback(entryId) {var doNow = bs_tryAgainArray[entryId];if (doNow[2] > 0) {doNow[2]--;try {eval(doNow[0]);} catch (e) {//still not working, register it again.
//alert(e); //4debug
setTimeout('timeoutCallback(' + entryId + ');', doNow[1] *1000);}
}
}
//-->
</script>
<style TYPE="text/css">
td { font-family: arial, helvetica; font-size: 10pt; color: #000000; }
td.small { font-family: arial, helvetica; font-size: 8pt; color: #000000; }
td.bar { font-family: arial, helvetica; font-size: 2pt; color: #000000; }
</style>
</head>
<frameset rows="75,*,26,15,0" ID="framesetOuter">
<frame src="<?php echo $_SERVER['PHP_SELF'];?>?showPage=top"      name="frameTop"     id="frameTop"     frameborder="0" scrolling="No" noresize marginwidth="10" marginheight="10">
<frameset cols="300,*" ID="framesetLeft">
<!frame src="fileBrowserDir.php"    name="frameDir"     id="frameDir"     frameborder="1" scrolling="Auto" marginwidth="10" marginheight="10">
<frame src="<?php echo $_SERVER['PHP_SELF'];?>?showPage=dir"  name="frameLeft"     id="frameLeft"     frameborder="1" scrolling="Auto" marginwidth="10" marginheight="10">
<frame src="<?php echo $_SERVER['PHP_SELF'];?>?showPage=file"   name="frameFile"    id="frameFile"    frameborder="1" scrolling="Auto" marginwidth="10" marginheight="10">
</frameset>
<frame src="<?php echo $_SERVER['PHP_SELF'];?>?showPage=upload"   name="frameUpload"  id="frameUpload"  frameborder="0" scrolling="No" noresize marginwidth="0" marginheight="0">
<frame src="<?php echo $_SERVER['PHP_SELF'];?>?showPage=status"   name="frameStatus"  id="frameStatus"  frameborder="0" scrolling="No" noresize marginwidth="0" marginheight="0">
<frame src="<?php echo $_SERVER['PHP_SELF'];?>?showPage=command"  name="frameCommand" id="frameCommand" frameborder="0" scrolling="No" noresize marginwidth="0" marginheight="0">
</frameset>
</html>
