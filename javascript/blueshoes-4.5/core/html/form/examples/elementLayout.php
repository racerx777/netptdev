<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . '../global.conf.php');
require_once($APP['path']['core']         . 'html/form/Bs_Form.class.php');

$form =& new Bs_Form();
$form->internalName         = 'myForm';
$form->name                 = 'myForm';
$form->mustFieldsVisualMode = 'starRight';
$form->useJsFile            = TRUE;
$form->language             = 'en';
$form->mode                 = 'add';
$form->elementLayouts       = array(
	'field' => array(
		'text'  => '<tr><td bgcolor="lime">__CAPTION_FOR_FORM_OUTPUT__</td><td bgcolor="menu">__ELEMENT__</td></tr>', 
	), 
);

$element =& new Bs_FormFieldText();
$element->name                = 'textFieldOne';
$element->caption             = 'Text Field One';
$element->editability         = 'always';
$element->elementStringFormat = "%s foo";
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'textFieldTwo';
$element->caption        = 'Text Field Two';
$element->editability    = 'always';
$element->elementLayout  = "
<tr>
	<td bgcolor='blue'><font color='white'><b>__CAPTION_FOR_FORM_OUTPUT__</b></font></td>
	<td bgcolor='menu'>__ELEMENT__</td>
</tr>";

$form->elementContainer->addElement($element);
unset($element);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Form using elementLayouts</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>
	
	<style>
		.formError {
			color: red;
		}
	</style>
	
</head>

<body bgcolor="#E6E6E6">

<h1>Form using elementLayouts</h1>

<ul>
	<li>The form uses an elementLayouts definition that defines a green background for the field name and a gray background for the field.</li>
	<li>The 2nd field overwrites the forms elementLayouts definition.</li>
	<li>The 1st field has an elementStringFormat setting.</li>
</ul>
<br>

<?php
if (is_array($formRet)) {
	if (isSet($formRet['errors'])) echo $formRet['errors'];
	echo $formRet['form'];
} else {
	echo 'Done!<br><br>';
	echo 'dumping $_POST:<br>';
	dump($_POST);
}
?>

</body>
</html>