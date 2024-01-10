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
$doWindowRefresh = FALSE;$errorMsg        = NULL;switch (@$_REQUEST['do']) {case 'download':
header("Content-type: application/octet-stream");header("Content-Disposition: attachment; filename={$_REQUEST['file']}");header("Content-Description: PHP Generated Data");if (strpos($_REQUEST['file'], '..') === FALSE) {readfile($path . $_REQUEST['file']);} else {$errorMsg = $langHash['error']['notAllowed']; }
break;case 'delete':
if (strpos($_REQUEST['file'], '..') === FALSE) {$deleteFullPath = $path . $_REQUEST['file'];if (is_dir($deleteFullPath)) {$status = $dir->rm($deleteFullPath);} else {$status = @unlink($deleteFullPath);}
if ($status) {$doWindowRefresh = TRUE;} else {$errorMsg = $langHash['error']['couldNotDelete']; }
} else {$errorMsg = $langHash['error']['notAllowed']; }
break;case 'rename':
if ((strpos($_REQUEST['file'], '..') === FALSE) && (strpos($_REQUEST['newFile'], '..') === FALSE)) {$status = moveFile($_REQUEST['file'], $_REQUEST['newFile']);$doWindowRefresh = TRUE;} else {$errorMsg = $langHash['error']['notAllowed']; }
break;case 'createFolder':
if (strpos($_REQUEST['folderName'], '..') === FALSE) {$newPath = $path . $_REQUEST['folderName'];if (file_exists($newPath)) {$errorMsg = $langHash['error']['pathAlreadyExists']; } else {if (!@mkdir($newPath)) {$errorMsg = $langHash['error']['createDir']; } else {$doWindowRefresh = TRUE;}
}
} else {$errorMsg = $langHash['error']['notAllowed']; }
break;case 'createFile':
if (strpos($_REQUEST['fileName'], '..') === FALSE) {$newPath = $path . $_REQUEST['fileName'];if (file_exists($newPath)) {$errorMsg = $langHash['error']['fileAlreadyExists']; } else {require_once($APP['path']['core'] . 'file/Bs_File.class.php');$file =& new Bs_File;if (!$file->onewayWrite('', $newPath)) {$errorMsg = $langHash['error']['createFile']; } else {$doWindowRefresh = TRUE;}
}
} else {$errorMsg = $langHash['error']['notAllowed']; }
break;case 'move':
case 'copy':
if ((strpos($_REQUEST['pathOld'], '..') === FALSE) && (strpos($_REQUEST['pathNew'], '..') === FALSE) && (strpos($_REQUEST['element'], '..') === FALSE)) {$newLocation = makePathAbsolute($_REQUEST['pathNew'] . $_REQUEST['element']);if (!file_exists($newLocation) || isSet($_REQUEST['overwrite'])) {if ($_REQUEST['do'] == 'move') {$status = moveFile($_REQUEST['pathOld'] . $_REQUEST['element'], $_REQUEST['pathNew'] . $_REQUEST['element']);} else { $status = copyFile($_REQUEST['pathOld'] . $_REQUEST['element'], $_REQUEST['pathNew'] . $_REQUEST['element']);}
if ($status) {$doWindowRefresh = TRUE;} else {$errorMsg = 'Could not ' . $_REQUEST['do'] . ' files/folders!';}
} else {?>
<script type="text/javascript">
var status = confirm("<?php echo $langHash['error']['overwrite'];?>"); //The destination file/directory already exists. Do you want to overwrite it?
if (status) {document.location.href = encodeURI(parent.frameFile.php_self + "?showPage=command&do=<?php echo $_REQUEST['do'];?>&pathOld=<?php echo $_REQUEST['pathOld'];?>&pathNew=<?php echo $_REQUEST['pathNew'];?>&element=<?php echo $_REQUEST['element'];?>&overwrite=1");}
</script>
<?php
exit;}
} else {$errorMsg = $langHash['error']['notAllowed']; }
break;default:
}
if (!empty($errorMsg)) {?>
<script type="text/javascript">
alert("<?php echo $errorMsg;?>");</script>
<?php
}
if ($doWindowRefresh) {?>
<script type="text/javascript">
parent.frameFile.location.reload();</script>
<?php
}
?>