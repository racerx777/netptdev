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
require_once($APP['path']['core'] . 'util/Bs_UnitConverter.class.php');require_once($APP['path']['core'] . 'net/http/Bs_Browscap.class.php');$GLOBALS['Bs_Browscap']->compute();$isIe = (($GLOBALS['Bs_Browscap']->data['browser'] == 'ie') && ($GLOBALS['Bs_Browscap']->data['browserMajorVersion'] >= 6));$task = (@$_POST['task'] === 'search') ? 'search' : 'dir';global $fileListString;$fileListString = '';global $commentString;$commentString  = '';$dir->setFullPath($path);$depth = (($task == 'search') && ($_POST['subfolder'])) ? BS_DIR_UNLIM_DEPTH : 0;if ($task == 'search') {$depth = ($_POST['subfolder']) ? BS_DIR_UNLIM_DEPTH : 0;if (!empty($_POST['containingName'])) {$contName = trim($_POST['containingName']); $contName = str_replace('^', '\^', $contName);$contName = str_replace('[', '\[', $contName);$contName = str_replace('$', '\$', $contName);$contName = str_replace('(', '\(', $contName);$contName = str_replace(')', '\)', $contName);$contName = str_replace('|', '\|', $contName);$contName = str_replace('+', '\+', $contName);$contName = str_replace('{', '\{', $contName);$contName = str_replace('?', '.?', $contName);$contName = str_replace('*', '.*', $contName);if (($contName[0] == '"') && ($contName[strlen($contName) -1] == '"')) {$contName = '^' . substr($contName, 1, -1) . '$';}
$regEx = $contName;} else {$regEx = null;}
} else {$depth = 0;$regEx = null;}
$listParams = array(
'regEx'      => $regEx, 'depth'      => $depth, 
'returnType' => 'subdir/file2' );$list = &$dir->getFileList($listParams);if (isEx($list)) {} elseif (empty($list)) {} else {if (sizeOf($list) > $fileManagerSettings['maxFilesToShow']) {$commentString = sprintf($langHash['error']['tooManyFiles'], sizeOf($list)) . ' ' . sprintf($langHash['error']['limitOfFiles'], $fileManagerSettings['maxFilesToShow']); } else {$fileExtensions = array();do {if (!isSet($fileManagerSettings['fileExtensions']) || ($fileManagerSettings['fileExtensions'] == '__DB__')) {if (!@is_object($GLOBALS['bsDb'])) break;$bsDb = &$GLOBALS['bsDb'];$fileExtensions = &$bsDb->getAssoc("SELECT ending, icon, en FROM BsKb.FileExtensions");if (isEx($fileExtensions)) {$fileExtensions = array();}
} else {$fContent = @file($APP['path']['bsRoot'] . 'applications/filemanager/fileExtensions.csv');if (!is_array($fContent)) break;while (list(,$line) = each($fContent)) {$lineArr = explode(';', $line);$fileExtensions[$lineArr[0]] = array($lineArr[1], trim($lineArr[2]));}
}
} while (FALSE);if ($isIe) {$fileListString .= "TopElements.elements=[\n";} else { $fileListString .= "var fileList = new Array;\n";}
$i = 0;while (list($k) = each($list)) {$fullPath = $path . $list[$k]['dir'] . $list[$k]['file'];$fileExtension = $dir->getFileExtension($list[$k]['file']);$read   = is_readable($fullPath);$isDir  = !is_file($fullPath);$isLink = $dir->isLink($fullPath);if ($isDir) {$size = '';$icon = '_dir';$fileTypeString = 'Directory';} else {$size = Bs_UnitConverter::toUsefulBitAndByteString(filesize($fullPath));if (isSet($fileExtensions[$fileExtension])) {$icon = $fileExtensions[$fileExtension][0];$fileTypeString = $fileExtensions[$fileExtension][1];} else {$icon = '_misc';$fileTypeString = 'unknown';}
}
if ($task == 'search') {$inFolderString = (!empty($list[$k]['dir'])) ? $list[$k]['dir'] : ' '; } else {$inFolderString = '';}
$read   = boolToString($read);$isDir  = boolToString($isDir);$isLink = boolToString($isLink);if ($isIe) {if ($i > 0) $fileListString .= ",\n";$fileListString .= "  new Element({$isDir}, {$isLink}";$fileListString .= ", \"{$list[$k]['file']}\", \"{$inFolderString}\", '{$fileManagerSettings['fileExtensionsImagePath']}{$icon}', '{$fileTypeString}', '";$fileListString .= $size;$fileListString .= "', '" . date("Y-m-d H:i:s", filemtime($fullPath)) . "', {$read})";} else { $fileListString .= "fileList[{$i}] = new Array();\n";$fileListString .= "fileList[{$i}]['isDir']      = {$isDir};\n";$fileListString .= "fileList[{$i}]['isLink']     = {$isLink};\n";$fileListString .= "fileList[{$i}]['name']       = \"{$list[$k]['file']}\";\n";$fileListString .= "fileList[{$i}]['dir']        = \"{$list[$k]['inFolderString']}\";\n";$fileListString .= "fileList[{$i}]['icon']       = \"{$fileManagerSettings['fileExtensionsImagePath']}{$icon}\";\n";$fileListString .= "fileList[{$i}]['type']       = \"{$list[$k]['fileTypeString']}\";\n";$fileListString .= "fileList[{$i}]['size']       = \"{$size}\";\n";$fileListString .= "fileList[{$i}]['lastmod']    = \"" . date("Y-m-d H:i:s", filemtime($fullPath)) . "\";\n";$fileListString .= "fileList[{$i}]['read']       = \"{$read}\";\n";}
$i++;}
$fileListString .= "\n";if ($isIe) {$fileListString .= "];\n";}
}
}
$titleListString  = '';if ($isIe) {$titleListString .= "TopElements.categories=[\n";$titleListString .= "  new Category('name',     '{$langHash['misc']['name']}',     '', '{$langHash['misc']['fileName']}',              true, stringSort),\n";if ($task == 'search') {$titleListString .= "  new Category('folder',   '{$langHash['misc']['inFolder']}','', '',                                            true, stringSort),\n";}
$titleListString .= "  new Category('size',     '{$langHash['misc']['size']}',     '', '{$langHash['misc']['fileSizeBytes']}',         true, fileSizeSort), //numericSort\n";$titleListString .= "  new Category('type',     '{$langHash['misc']['type']}',     '', '{$langHash['misc']['fileType']}',              true, stringSort),\n";$titleListString .= "  new Category('lastmod',  '{$langHash['misc']['modified']}', '', '{$langHash['misc']['lastModDatetime']}',       true, stringSort),\n";$titleListString .= "  new Category('readable', '{$langHash['misc']['read']}',     '', '{$langHash['misc']['fileReadableByProcess']}', false)\n";$titleListString .= "];\n";}
function getDiskFreeSpace() {global $path;return Bs_UnitConverter::toUsefulBitAndByteString(diskfreespace($path));}
function getInfoLine() {global $list, $langHash;if (is_array($list)) {$numObj = sizeOf($list) . ' ' . $langHash['misc']['object-s'] . ', '; } else {$numObj = '';}
return $numObj . $langHash['misc']['diskFreeSpace'] . ': ' . getDiskFreeSpace(); }
?>
<html>
<head>
<link rel='stylesheet' href='<?php echo $fileManagerSettings['imgPath'];?>fileManager.css'>
<script language='javascript' src='<?php echo $fileManagerSettings['imgPath'];?>fileManagerGeneral.js'></script>
<?php
if ($isIe) {echo "<script language='javascript' src='{$fileManagerSettings['imgPath']}fileManager.ie6.js'></script>\n";} else {echo "<script language='javascript' src='{$fileManagerSettings['imgPath']}fileManager.js'></script>\n";}
?>
<script language='javascript' src='<?php echo $fileManagerSettings['jsBasePath'];?>core/lang/Bs_Misc.lib.js'></script>
<script language="javascript">
//used to calculate the webroot.
var basePath = "<?php echo $basePath;?>";var fullPath = "<?php echo $path;?>";var relPath  = fullPath.substr(basePath.length);parent.doForMe("setInfoLine('<?php echo getInfoLine();?>')", 'frameStatus');parent.doForMe("setCwd('<?php echo $path;?>')",              'frameTop');var php_self = '<?php echo $_SERVER['PHP_SELF'];?>';var fileContextMenuActive = false;<?php
echo $titleListString;echo $fileListString;if ($isIe) {?>
document.onmousedown   = sinkTitle;document.onmouseup     = unsinkTitle;document.onselectstart = function () { return false }
<?php
}
?>
</script>
</head>
<body onload="drawFileList(document.getElementById('fileList'));" onClick="bodyOnClick();" leftmargin=0 topmargin=0>
<div id='fileList' style='position:relative'></div>
<?php echo $commentString;?>
</body>
</html>
<div 
id="menu1" 
class="menu" 
onClick="rightMouseMenuMouseClick()" 
onMouseOver="rightMouseMenuMouseOver()" 
onMouseOut="rightMouseMenuMouseOut()"
>
<div class="rightMouseMenuItem" id=mnuView style="font-weight: bold;"> <img id="mnuViewImg" class="rightMouseMenuItem" src="<?php echo $fileManagerSettings['imgPath'];?>view.gif" width="13" height="13" border="0" align="middle" alt=""> <?php echo $langHash['actions']['view'];?></div>
<div class="rightMouseMenuItem" id=mnuEdit    > <img id="mnuEditImg"     class="rightMouseMenuItem" src="<?php echo $fileManagerSettings['imgPath'];?>empty.gif"    width="13" height="13" border="0" align="middle" alt=""> <?php echo $langHash['actions']['edit'];?></div>
<div class="rightMouseMenuItem" id=mnuDownload> <img id="mnuDownloadImg" class="rightMouseMenuItem" src="<?php echo $fileManagerSettings['imgPath'];?>empty.gif"    width="13" height="13" border="0" align="middle" alt=""> <?php echo $langHash['actions']['download'];?>...</div>
<div class="rightMouseMenuItem" id=mnuDelete  > <img id="mnuDeleteImg"   class="rightMouseMenuItem" src="<?php echo $fileManagerSettings['imgPath'];?>delete.gif"   width="13" height="13" border="0" align="middle" alt=""> <?php echo $langHash['actions']['delete'];?>...</div>
<div class="rightMouseMenuItem" id=mnuRename  > <img id="mnuRenameImg"   class="rightMouseMenuItem" src="<?php echo $fileManagerSettings['imgPath'];?>empty.gif"    width="13" height="13" border="0" align="middle" alt=""> <?php echo $langHash['actions']['rename'];?>...</div>
<div class="rightMouseMenuItem" id=mnuCut     > <img id="mnuCutImg"      class="rightMouseMenuItem" src="<?php echo $fileManagerSettings['imgPath'];?>bs_cut.gif"   width="16" height="16" border="0" align="middle" alt=""> <?php echo $langHash['actions']['cut'];?></div>
<div class="rightMouseMenuItem" id=mnuCopy    > <img id="mnuCopyImg"     class="rightMouseMenuItem" src="<?php echo $fileManagerSettings['imgPath'];?>bs_copy.gif"  width="16" height="16" border="0" align="middle" alt=""> <?php echo $langHash['actions']['copy'];?></div>
</div>
</body>
</html>