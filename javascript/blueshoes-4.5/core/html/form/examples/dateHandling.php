<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
require_once($APP['path']['core'] . 'html/form/Bs_Form.class.php');
require_once($APP['path']['core'] . 'html/form/specialfields/Bs_FormFieldSlider.class.php');       // <= special field

$form =& new Bs_Form();

$form->internalName         = 'myForm';
$form->name                 = 'myForm';
$form->useJsFile            = TRUE;
$form->buttons              = 'default';
$form->language             = 'en';
$form->mode                 = 'add';

$element =& new Bs_FormFieldText();
$element->name            = 'date1';
$element->caption         = 'Date';
$element->editability     = 'always';
$element->bsDataType      = 'date'; //datetime
$element->bsDataInfo      = 4;
$element->valueDefault    = '';
$element->must            = TRUE;
$form->elementContainer->addElement($element);
unset($element);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Example form with date handling</title>
  <?php
	$formRet = $form->doItYourself();
  //$formRet = $form->getAll('string', TRUE);
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml(@$formRet['include']);
		echo $form->onLoadCodeToHtml(@$formRet['onLoad']);
	}
  ?>
</head>

<body bgcolor="#E6E6E6">

<h1>Example form with date handling</h1>
<br><br>

<?php
if (is_array($formRet)) {
	if (isSet($formRet['errors'])) echo $formRet['errors'];
	echo $formRet['form'];
} else {
	echo '<h1>Done!</h1>';
	echo 'valueReceived<br>';
	dump($form->getValuesArray(TRUE, 'valueReceived', TRUE));
	echo 'valueInternal<br>';
	dump($form->getValuesArray(TRUE, 'valueInternal', TRUE));
	echo 'valueDisplay<br>';
	dump($form->getValuesArray(TRUE, 'valueDisplay', TRUE));
}
?>


</body>
</html>