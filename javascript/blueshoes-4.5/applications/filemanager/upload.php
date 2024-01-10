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
if (isSet($_POST['step'])) {if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {if (substr($_REQUEST['cwd'], 0, strlen($basePath)) != $basePath) {die($langHash['error']['pathNotAllowed']); } else {$saveTo = $_REQUEST['cwd'] . basename($_FILES['userfile']['name']);if (file_exists($saveTo)) {$tempSaveTo = getTmp() . 'bsTmpUpload' . md5(basename($_FILES['userfile']['name']));$status = @move_uploaded_file($_FILES['userfile']['tmp_name'], $tempSaveTo);if (!$status) {die($langHash['error']['uploadFailedTmp'] . ': ' . $tempSaveTo); }
local_fileExistsWhatNow($tempSaveTo, $saveTo);} else {$status = move_uploaded_file($_FILES['userfile']['tmp_name'], $saveTo);if ($status) {echo "<script language='javascript'>\n";echo "parent.frameFile.location.reload();\n";echo "</script>\n";} else {die($langHash['error']['undefinedError']); }
}
}
}
}
function local_fileExistsWhatNow($tmpFullPath, $desiredFullPath) {echo $langHash['overwrite']['alreadyExists']; echo "<form>";echo "<input type='radio' name='' value='' selected>{$langHash['overwrite']['alreadyExists']} "; echo "<input type='radio' name='' value='' selected>{$langHash['overwrite']['overwrite']} "; echo "<input type='radio' name='' value='' selected>{$langHash['overwrite']['renameOld']} <input type='text'> "; echo "<input type='radio' name='' value='' selected>{$langHash['overwrite']['renameNew']} <input type='text'> "; echo "</form>";}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>BlueShoes File Manager</title>
</head>
<body leftmargin=0 topmargin=0 bgcolor="#D4D0C8">
<script language="javascript">
function upload() {document.getElementById('cwd').value = parent.frameTop.getCwd();//alert(parent.frameTop.getCwd()); return; //4debug
document.frmUpload.submit();}
</script>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?showPage=upload" enctype="multipart/form-data" name="frmUpload">
<input type="hidden" name="step" value="2">
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $fileManagerSettings['maxFileUploadSize'];?>">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
<input type="hidden" name="cwd" id="cwd">
<input name="userfile" type="file" size="40" style="width:500;"> 
<input type="button" value="Upload" onClick="upload();">
</td>
</tr>
</table>
</form>
</body>
</html>
