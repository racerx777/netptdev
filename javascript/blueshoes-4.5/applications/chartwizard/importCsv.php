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
require_once($_SERVER["DOCUMENT_ROOT"]      . "../global.conf.php");require_once($APP['path']['core'] . 'util/Bs_CsvUtil.class.php');require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core'] . 'file/Bs_FileSystem.class.php');$csvString   = '';$jsArray     = "var data = new Array();\n";$statusMsg   = '';$hasImported = FALSE;do {if ($_POST['step'] != '2') break;$allowedTypes = array('csv', 'xls', 'txt');if (!(isSet($_FILES) && is_array($_FILES) && (isSet($_FILES['csvFile']['tmp_name']) && ($_FILES['csvFile']['tmp_name'] != 'none')))) break;$Bs_FileSystem =& new Bs_FileSystem();$typ = $Bs_FileSystem->getFileExtension($_FILES['csvFile']['name']);$typ = strToLower($typ);if (!in_array($typ, $allowedTypes)) break;if ($typ == 'xls') {require_once($APP['path']['core'] . 'file/converter/Bs_FileConverterExcel.class.php');$Bs_FileConverterExcel =& new Bs_FileConverterExcel();$fileContentCsv = $Bs_FileConverterExcel->xlsToCsv($_FILES['csvFile']['tmp_name']);} else {$fileContentCsv = join("", file($_FILES['csvFile']['tmp_name']));}
$Bs_HtmlUtil =& new Bs_HtmlUtil();if ($_GET['where'] == 'ss') {$csv =& new Bs_CsvUtil();$csvArray = $csv->csvStringToArray($fileContentCsv, $separator=';', $trim='none', $removeHeader=FALSE);$jsArray  = $Bs_HtmlUtil->arrayToJsArray($csvArray, 'data');} else {$csvString = $Bs_HtmlUtil->filterForJavaScript($fileContentCsv);}
$statusMsg   = "Imported: {$_FILES['csvFile']['name']}<br><br>";$hasImported = TRUE;} while (FALSE);?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>CSV Import</title>
<script>
onload = function() {<?php
if ($hasImported) {if ($_GET['where'] == 'ss') {echo $jsArray;echo "parent.data = data;\n";echo "parent.bs_ss_init(data);\n";} else {echo "parent.document.getElementById('dataCsv').value = \"{$csvString}\";\n";}
}
?>
}
</script>
</head>
<body bgcolor="#D6D3CE">
<?php echo $statusMsg;?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>?showPage=importCsv&where=<?php echo $_GET['where'];?>" method="post" enctype="multipart/form-data" name="myForm" id="myForm">
<input type="hidden" name="step"  value="2">
<input type="file" name="csvFile" size="40">
<input type="submit" name="submit" value="Import">
</form>
</body>
</html>
