<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");
require_once($APP['path']['core']      . 'html/form/Bs_Form.class.php');
require_once($APP['path']['core']      . 'html/form/domapi/Bs_DaFormFieldColorPicker2.class.php');       // <= special field


$form =& new Bs_Form();

$form->internalName         = 'myForm';
$form->name                 = 'myForm';
$form->buttons              = 'default';
$form->language             = 'en';
$form->mode                 = 'add';

$element =& new Bs_DaFormFieldColorPicker2();
$element->name          = 'colorPickerOne';
$element->caption       = array('en'=>'Color Picker');
$element->editability   = 'always';
//$element->valueDefault  = '';
$form->elementContainer->addElement($element);
unset($element);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>example form using domapi datepicker</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>

  <style>
    td {
    	font-family : Arial, Helvetica, sans-serif;
    	font-size : 13px;
    }
  </style>

</head>

<body bgcolor="#E6E6E6">

<h1>Example form using domapi datepicker</h1>
<br><br>

<?php
if (is_array($formRet)) {
	if (isSet($formRet['errors'])) echo $formRet['errors'];
	echo $formRet['form'];
} else {
	echo 'Done!<br><br>';
	echo 'dumping $_POST:<br>';
	dump($_POST);
	$formValues = $form->getValuesArray(TRUE, 'valueInternal');
	dump($formValues);
}
?>

</body>
</html>
