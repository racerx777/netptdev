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
$form->useJsFile            = TRUE;
$form->language             = 'en';
$form->mode                 = 'add';


unset($container);
$container =& new Bs_FormContainer();
$container->name         = 'containerOne';
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldCheckbox();
$element->name           = 'checkMeOne';
$element->caption        = 'Check me';
$element->text           = 'This is the text value of the checkbox field.';
$element->editability    = 'always';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'myTextOne';
$element->caption        = 'Text';
$element->editability    = 'always';
$container->addElement($element);
unset($element);


unset($container);
$container =& new Bs_FormContainer();
$container->name         = 'containerTwo';
$container->useCheckboxAsCaption = 'checkMeTwo';
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldCheckbox();
$element->name           = 'checkMeTwo';
$element->caption        = 'Check me';
$element->editability    = 'always';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'myTextTwo';
$element->caption        = 'Text';
$element->editability    = 'always';
$container->addElement($element);
unset($element);


unset($container);
$container =& new Bs_FormContainer();
$container->name         = 'containerThree';
$container->useCheckboxAsCaption = 'checkMeThree';
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldCheckbox();
$element->name           = 'checkMeThree';
$element->caption        = 'Check me';
$element->text           = 'This is the text value of the checkbox field.';
$element->editability    = 'always';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'myTextThree';
$element->caption        = 'Text';
$element->editability    = 'always';
$container->addElement($element);
unset($element);


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Form example using the useCheckboxAsCaption feature</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>
</head>

<body bgcolor="#E6E6E6">

<h1>Form example using the useCheckboxAsCaption feature</h1>

<ul>
	<li>There are 3 containers. All have a checkbox and a text field.</li>
	<li>The 2nd and 3rd ones use the useCheckboxAsCaption feature.</li>
	<li>The 1st and 3rd ones use the 'text' var of the checkbox field. The 2nd does not.</li>
</ul>
<br>

<?php
if (is_array($formRet)) {
	if (isSet($formRet['errors'])) echo $formRet['errors'];
	echo $formRet['form'];
} else {
	echo '<h1>Done!</h1><br><br>';
	echo '<h1>dumping getValuesArray():</h1>';
	dump($form->getValuesArray());
	echo '<h1>dumping $_POST:</h1>';
	dump($_POST);
}
?>

</body>
</html>