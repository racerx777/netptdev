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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>BlueShoes File Manager - Editor -</title>
<link rel='stylesheet' href='<?php echo $fileManagerSettings['imgPath'];?>fileManager.css'>
</head>
<body>
<?php
if (empty($APP['forms']['md5Key'])) $APP['forms']['md5Key'] = "Von der Wiege bis zur Bahre: Formulare.";require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');if (@$_POST['step'] == 2) {$compareMd5 = md5($_POST['filePath'] . $APP['forms']['md5Key']);if ($compareMd5 !== $_POST['filePathKey']) {echo $langHash['error']['cheat']; } else {$file =& new Bs_File();$status = $file->onewayWrite($_POST['fileContent'], $_POST['filePath']);if ($status) {echo $langHash['edit']['savedSuccessfully']; } else {echo $langHash['edit']['errorSaving'];       }
}
} else {$filePath     = $path . $_REQUEST['file'];do {if (!is_readable($filePath)) {echo $langHash['edit']['fileNotReadable']; break;}
if (!is_writable($filePath)) {echo $langHash['edit']['fileNotWritable']; break;}
if (is_dir($filePath)) {echo $langHash['edit']['noDir'];           break;}
$filePathHtml = $Bs_HtmlUtil->filterForHtml($filePath);$filePathKey  = $Bs_HtmlUtil->filterForHtml(md5($filePath . $APP['forms']['md5Key']));$fileContent  = $Bs_HtmlUtil->filterForHtml(join('', file($filePath)));$out = <<< EOD
<b>{$langHash['edit']['editFile']}:</b> {$filePath}<br>
<form action="" method="post">
<input type="hidden" name="step" value="2">
<input type="hidden" name="filePath"    value="{$filePathHtml}">
<input type="hidden" name="filePathKey" value="{$filePathKey}">
<textarea cols="80" rows="20" name="fileContent" wrap="off" style="width:80%; height:80%;">{$fileContent}</textarea><br>
<input type="submit" name="submit" value="Save"> <input type="button" value="Cancel" onClick="history.back();">
</form>
EOD;
echo $out;} while (FALSE);}
?>
</body>
</html>
