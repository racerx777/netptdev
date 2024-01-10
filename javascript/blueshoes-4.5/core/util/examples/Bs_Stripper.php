<?php
/**
* @package     core_util
*/


if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';
require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . '/global.conf.php');
require_once($APP['path']['core'] . 'util/Bs_Stripper.class.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Bs_Stripper Example</title>
</head>

<body bgcolor="#FFFFFF">

<?php
if (!empty($_POST['origCode'])) {
	$stripper =& new Bs_Stripper();
	$strippedCode = $stripper->strip($_POST['origCode']);
}
?>

<h1>Bs_Stripper Example</h1>

<form name="myForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

<h4>Original Code: (paste your code)</h4>
<textarea name="origCode" cols="90" rows="15" wrap="off"><?php if (!empty($_POST['origCode'])) echo $_POST['origCode'];?></textarea>
<br>

<h4>Stripped Code:</h4>
<textarea name="strippedCode" cols="90" rows="8" wrap="off"><?php if (!empty($strippedCode)) echo $strippedCode;?></textarea>
<br><br>

<input type="submit" name="submit" value="Strip!">
</form>

</body>
</html>
