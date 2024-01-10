<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");
require_once($APP['path']['core']      . 'html/form/Bs_Form.class.php');
require_once($APP['path']['core']      . 'html/form/specialfields/Bs_FormFieldDatePicker.class.php');       // <= special field


$form =& new Bs_Form();

$form->internalName         = 'myForm';
$form->name                 = 'myForm';
$form->buttons              = 'default';
$form->language             = 'en';
$form->mode                 = 'add';

$element =& new Bs_FormFieldDatePicker();
$element->name          = 'datePickerOne';
$element->caption       = array('en'=>'Date Picker 1');
$element->editability   = 'always';
$element->valueDefault  = '';
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormFieldDatePicker();
$element->name          = 'datePickerTwo';
$element->caption       = array('en'=>'Date Picker 2');
//$element->editability   = 'never';
$element->valueDefault  = '2003-01-01';
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormFieldDatePicker();
$element->name          = 'datePickerThree';
$element->caption       = array('en'=>'Date Picker 3');
$element->editability   = 'always';
//$element->visibility    = 'show';
$element->valueDefault  = '2003-12-31';
$form->elementContainer->addElement($element);
unset($element);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>example form using blueshoes datepicker</title>
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

<h1>Example form using blueshoes datepicker</h1>
<br><br>

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
