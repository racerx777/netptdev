<?php
/**
* @package    plugins_onomastics
* @subpackage examples
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>BlueShoes Onomastics</title>
	<link rel=stylesheet href="/default.css" type="text/css">
	<META NAME="robots" CONTENT="noindex">

<script language="javascript">
	<!--
	function loadOnoExample(first1, last1, first2, last2) {
		document.getElementById('first1').value = first1;
		document.getElementById('last1').value  = last1;
		document.getElementById('first2').value = first2;
		document.getElementById('last2').value  = last2;
	}
	// -->
</script>
<style>
.clickableSpan {
	cursor:hand;
	cursor:pointer;
	text-decoration:underline;
}
</style>
</head>

<body bgcolor="#FFFFFF">

<?php
require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
require_once($APP['path']['plugins']      . 'onomastics/Bs_Om_OnomasticsServer.class.php');
$o = &$GLOBALS['Bs_Om_OnomasticsServer'];
if (!empty($_POST['first1']) && !empty($_POST['first2'])) echo "Given name similarity: " . $o->calcSimilarityFirstname($_POST['first1'], $_POST['first2']) . "%<br>";
if (!empty($_POST['last1'])  && !empty($_POST['last2']))  echo "Family name similarity: " . $o->calcSimilarityLastname($_POST['last1'], $_POST['last2']) . "%<br>";
?>

<form action="" method="post">
	<table border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td valign="top">
				<fieldset style="padding:5px;">
					<legend><b>Compare this</b></legend>
					<table border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td>Given name:</td>
							<td><input type="text" name="first1" id="first1" size="20" style="width:180;" value="<?php echo @$_POST['first1'];?>"></td>
						</tr>
						<tr>
							<td>Family name:</td>
							<td><input type="text" name="last1" id="last1" size="20" style="width:180;" value="<?php echo @$_POST['last1'];?>"></td>
						</tr>
					</table>
				</fieldset>
			</td>
			<td valign="top">
				<fieldset style="padding:5px;">
					<legend><b>With this</b></legend>
					<table border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td>Given name:</td>
							<td><input type="text" name="first2" id="first2" size="20" style="width:180;" value="<?php echo @$_POST['first2'];?>"></td>
						</tr>
						<tr>
							<td>Family name:</td>
							<td><input type="text" name="last2" id="last2" size="20" style="width:180;" value="<?php echo @$_POST['last2'];?>"></td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
	<input type="submit" name="send" value="Compare">
</form>

<b>Try these:</b><br>
Choose an example, edit it if you like, then push the "Compare" button.<br><br>
<ul>
	<li><span onclick="loadOnoExample('Bill', 'Clinton', 'William', 'Clinton');" class="clickableSpan">Bill Clinton</span> (nickname)</li>
	<li><span onclick="loadOnoExample('josé', 'rodriguez', 'jose maria', 'rodriguez-johnson');" class="clickableSpan">rodrigueZ</span> (double-names)</li>
	<li><span onclick="loadOnoExample('Thomas', 'Rodrigues', 'Tomas', 'Rodriguez');" class="clickableSpan">rodrigueS</span> (name forms)</li>
	<li><span onclick="loadOnoExample('Rick', 'Müller', 'Richard', 'Mueller');" class="clickableSpan">Richard Mueller</span> (name form, german umlaut</li>
	<li><span onclick="loadOnoExample('Andrew', 'Simth', 'André', 'Smith');" class="clickableSpan">André Smith</span> (name form, spelling)</li>
	<li><span onclick="loadOnoExample('john peter', 'johnson', 'johnpeter', 'jonsson');" class="clickableSpan">johnpeter jonsson</span> (writing form)</li>
</ul>

<?php
/*
require_once($APP['path']['core'] . 'html/form/Bs_Form.class.php');

$form =& new Bs_Form();

$form->internalName         = 'myForm';
$form->name                 = 'myForm';
//$form->mustFieldsVisualMode = 'starRight';
$form->useAccessKeys        = TRUE;
$form->useJsFile            = TRUE;
$form->jumpToFirstError     = TRUE;
$form->useTemplate          = FALSE;
$form->buttons              = 'default';
$form->language             = 'en';
$form->mode                 = 'add';
$form->onEnter              = 'tab';

unset($container);
$container =& new Bs_FormContainer();
$container->name         = "main";
$container->caption      = array(
  'en'=>'main', 
);
$container->mayToggle    = FALSE;
$form->elementContainer->addElement($container);



$element =& new Bs_FormFieldSex();
$element->name             = 'some_sex';
$element->must             = TRUE;
$element->firstnameField   = 'some_firstname';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldFirstname();
$element->name          = 'some_firstname';
$element->editability   = 'always';
$element->size          = 10;
$element->must          = TRUE;
$element->lastnameField = 'some_lastname';
$element->sexField      = 'some_sex';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldLastname();
$element->name           = 'some_lastname';
$element->editability    = 'always';
$element->size           = 10;
$element->must           = TRUE;
$element->firstnameField = 'some_firstname';
$container->addElement($element);
unset($element);

*/
?>

</body>
</html>
