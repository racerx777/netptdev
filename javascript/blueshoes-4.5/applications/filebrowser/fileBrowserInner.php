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
require_once($APP['path']['core']      . 'file/Bs_Dir.class.php');require_once($APP['path']['core']      . 'html/Bs_HtmlUtil.class.php');$viewStyle = (isSet($_REQUEST['viewStyle'])) ? $_REQUEST['viewStyle'] : 'detail';$dir =& new Bs_Dir();$subPath  = (isSet($_GET['path'])) ? $dir->realPath($_GET['path']) : '/'; $path     = $settings['basePath'] . $subPath;$path     = $dir->getRealPath($path); $fileExtensionsImagePath = "/_bsImages/fileExtensions/";if (!isSet($settings['fileExtensionsFilter'])) $settings['fileExtensionsFilter'] = array(); ?>
<html>
<head>
<style TYPE="text/css">
td { font-family: arial, helvetica; font-size: 10pt; color: #000000; }
td.small { font-family: arial, helvetica; font-size: 8pt; color: #000000; }
td.bar { font-family: arial, helvetica; font-size: 2pt; color: #000000; }
body { font-family: arial, helvetica; font-size: 10pt; color: #000000; }
</style>
<script>
/**
* updates the file preview div.
* if the src param is not given then the current preview will be cleared. back to empty.
* @param string src (image path, absolute to webroot)
* @return void
*/
function divFilePreview(src) {var div = document.getElementById('divFilePreview');if (typeof(src) == 'undefined') {div.innerHTML = '';} else {div.innerHTML = '<img src="' + src + '" border="0">';}
}
var fileSelected = new Array;/**
* a file (not a dir!) has been clicked.
* @param  string src
* @return void
*/
function fileClick(spanTag, src) {parent.inputFile.value = src;unSelectAllItems();fileSelected = new Array(spanTag);spanTag.style.filter = "progid:DXImageTransform.Microsoft.BasicImage(Invert=1)";<?php
if ($viewStyle == 'thumbnails') {echo "divFilePreview(src);\n";}
?>
}
function unSelectAllItems() {for (var i=0; i < fileSelected.length; ++i) {fileSelected[i].style.filter = "";}
fileSelected = new Array;}
</script>
</head>
<body onLoad="this.focus();">
<?php
if (isSet($_REQUEST['basePath']) || !isSet($settings['basePath'])) {echo "don't cheat! set the base path correctly.";} elseif (substr($path, 0, strlen($settings['basePath'])) != $settings['basePath']) {echo "Path not allowed or does not exist.";} else {$dir->setFullPath($path);echo "<script language='javascript'>\n";echo "try {\n"; echo "parent.myPath.setValue('" . $subPath . "');\n";echo "} catch (e) { }\n";echo "</script>\n";$dir =& new Bs_Dir($path);$listParams = array(
'depth'      => 0, 
'returnType' => 'subdir/file' );$list = &$dir->getFileList($listParams);if (isEx($list)) {} elseif (empty($list)) {} else {while (list($k) = each($list)) {if (empty($list[$k]['dir'])) {$fileList[] = $list[$k]['file'];} else {$dirWithoutSlash = substr($list[$k]['dir'], 0, -1);if (!empty($settings['ignoreDirs']) && isSet($settings['ignoreDirs'][$dirWithoutSlash])) continue;$dirList[]  = $dirWithoutSlash;}
}
if ($viewStyle == 'thumbnails') {echo "<table width='100%' cellspacing='0' cellpadding='0'>";echo "<tr><td valign='top'>";}
echo "<table width='100%' cellspacing='0' cellpadding='0'>";if (isSet($dirList)) {natsort($dirList);while (list($k) = each($dirList)) {$link = "";echo "<tr>";if ($viewStyle == 'detail') {echo '<td>';} else {echo '<td colspan="4">';}
echo "<span style='cursor: default;' onDblClick=\"parent.gotoDir('" . $subPath . $dirList[$k] . "/');\">";echo "<img src='{$fileExtensionsImagePath}_dir.gif' border='0'> ";echo $dirList[$k];echo "</span>";echo "</td>";echo "</tr>";}
}
if (isSet($fileList)) {natsort($fileList);if (is_object($GLOBALS['bsDb'])) {$bsDb = &$GLOBALS['bsDb'];$fileExtensions = &$bsDb->getAssoc("SELECT ending, icon FROM bs_kb.FileExtensions");if (isEx($fileExtensions)) {$fileExtensions = array();}
} else {$fileExtensions = array();}
while (list($k) = each($fileList)) {$fileExtension = $dir->getFileExtension($fileList[$k]);if (!empty($settings['fileExtensionsFilter']) && !isSet($settings['fileExtensionsFilter'][$fileExtension])) continue;echo "<tr>";if ($viewStyle == 'detail') {echo '<td>';} else {echo '<td colspan="4">';}
if (is_readable($path . $fileList[$k])) {$spanStyle = "cursor:default; zoom:100%; background-color:white;";$events    = "onClick=\"fileClick(this, '" . $GLOBALS['Bs_HtmlUtil']->filterForJavaScript($subPath . $fileList[$k]) . "');\"";$events   .= " onDblClick=\"fileClick(this, '" . $GLOBALS['Bs_HtmlUtil']->filterForJavaScript($subPath . $fileList[$k]) . "'); parent.setValueBack();\"";$readableGif = "<img src='{$fileExtensionsImagePath}_read.gif' border='0' alt='readable'>";} else {$spanStyle = "cursor:not-allowed;";$events    = '';$readableGif = "<img src='{$fileExtensionsImagePath}_read.gif' border='0' alt='unreadable'>";}
echo "<span style=\"{$spanStyle}\" {$events}>";if (isSet($fileExtensions[$fileExtension])) {echo "<img src='{$fileExtensionsImagePath}{$fileExtensions[$fileExtension]}.gif' border='0'> ";} else {echo "<img src='{$fileExtensionsImagePath}_misc.gif' border='0'> ";}
echo $fileList[$k];echo "</span>";if ($viewStyle == 'detail') {echo "<td>" . filesize($path . $fileList[$k]) . "</td>";echo "<td>" . date("Y-m-d H:i:s", filemtime($path . $fileList[$k])) . "</td>"; echo "<td>{$readableGif}</td>";}
echo "</tr>";}
}
echo "</table>";if ($viewStyle == 'thumbnails') {echo "</td><td valign='top' align='right' width='200'>";echo '<div id="divFilePreview" style="overflow:auto; width:200; height:220; border:1px solid black;"></div>';echo "</td></tr>";echo "</table>";}
}
}
?>
</body>
</html>
