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
$dir->setFullPath($path);$listParams = array(
'depth'      => BS_DIR_UNLIM_DEPTH, 
'fileDirLink' => array('file'=>FALSE, 'dir'=>TRUE, 'filelink'=>FALSE, 'dirlink'=>TRUE), 
'followLinks' => TRUE, 
'returnType' => 'nested' );$list = &$dir->getFileList($listParams);if (isEx($list)) {} elseif (empty($list)) {} else {if (!$fileManagerSettings['showRelative']) {$basePathJunks = array_reverse(explode('/', $basePath));while (list(,$dirJunk) = each($basePathJunks)) {if (empty($dirJunk)) continue;$tempList = $list;unset($list);$list[$dirJunk] = $tempList;}
}
$outJsTreeArray = writeMenuLines($list, 'dirTreeArr');}
function writeMenuLines($array, $varName, $pathPrefix='') {global $fileManagerSettings;global $basePath;$out  = '';$out .= "{$varName} = new Array();\n";$i = 0;while (list($k) = each($array)) {$currentFolder = $pathPrefix . $k . '/';$newVarName = "{$varName}[{$i}]";$out .= "{$newVarName} = new Array();\n";$out .= "{$newVarName}.caption = \"{$k}\";\n";$pathOrFullPath = ($fileManagerSettings['showRelative']) ? 'path' : 'fullPath';$out .= "{$newVarName}.onClickCaption = \"parent.frameFile.location.href = '{$_SERVER['PHP_SELF']}?showPage=file&{$pathOrFullPath}={$currentFolder}';\";\n";if (!$fileManagerSettings['showRelative']) {if (strlen($currentFolder) <= strlen($basePath)) {$out .= "{$newVarName}.isOpen = true;\n";}
}
if (!empty($array[$k])) {$out .= writeMenuLines($array[$k], $newVarName . '.children', $currentFolder);}
$i++;}
return $out;}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>BlueShoes File Manager - Directory -</title>
<!-- Tree Stuff -->    
<script language="JavaScript" src="<?php echo $fileManagerSettings['jsBasePath'];?>components/tree/Bs_Tree.class.js"></script>
<script language="JavaScript" src="<?php echo $fileManagerSettings['jsBasePath'];?>components/tree/Bs_TreeElement.class.js"></script>
<script language="JavaScript">
<?php
echo @$outJsTreeArray; if ($fileManagerSettings['showRelative']) {?>
var t = dirTreeArr;var dirTreeArr = new Array();dirTreeArr[0] = new Array();dirTreeArr[0].caption  = 'Virtual Root';dirTreeArr[0].onClickCaption = "parent.frameFile.location.href = '<?php echo $_SERVER['PHP_SELF'];?>?showPage=file&path=';";dirTreeArr[0].isOpen   = true;dirTreeArr[0].children = t;<?php
} else {}
?>
</script>
</head>
<body>
<div id="dirTree"></div>
<script language="JavaScript">
t = new Bs_Tree();t.autoCollapse              = false;t.useAutoSequence           = true;t.imageDir                  = '<?php echo $fileManagerSettings['jsBasePath'];?>components/tree/img/win98/';t.useFolderIcon             = true;t.useLeaf                   = false;var status = t.initByArray(dirTreeArr);if (!status) {var treeHtml = t.getLastError();document.getElementById('dirTree').innerHTML = treeHtml;} else {//attachEventClickCaption(g_mtTree[0]['children'], "g_mtTree[0]['children']");t.draw();}
</script>
</body>
</html>
