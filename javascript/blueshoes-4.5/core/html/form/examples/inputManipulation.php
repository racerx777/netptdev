<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . '../global.conf.php');
require_once($APP['path']['core']         . 'html/form/Bs_Form.class.php');

$form =& new Bs_Form();
$form->internalName         = 'inputManipulationForm';
$form->name                 = 'inputManipulationForm';
$form->useJsFile            = TRUE;
$form->language             = 'en';
$form->mode                 = 'add';


unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerTrim";
$container->caption      = 'Trim';
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldText();
$element->name           = 'trimLeft';
$element->caption        = 'Trim Left';
$element->editability    = 'always';
$element->trim           = 'left';
$element->elementStringFormat = '%s Trim input on the left side.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'trimRight';
$element->caption        = 'Trim Right';
$element->editability    = 'always';
$element->trim           = 'right';
$element->elementStringFormat = '%s Trim input on the right side.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'trimBoth';
$element->caption        = 'Trim Both';
$element->editability    = 'always';
$element->trim           = 'both';
$element->elementStringFormat = '%s Trim input on both sides.';
$container->addElement($element);
unset($element);


unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerRemove";
$container->caption      = 'Remove';
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldText();
$element->name           = 'remove';
$element->caption        = 'Remove';
$element->editability    = 'always';
$element->remove         = array(' ', '-', '_', 'y');
$element->elementStringFormat = '%s Remove spaces, dashes, underscore and y.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'removeI';
$element->caption        = 'Remove Case Insensitive';
$element->editability    = 'always';
$element->removeI        = array('y');
$element->elementStringFormat = '%s Remove y (and Y).';
$container->addElement($element);
unset($element);


unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerReplace";
$container->caption      = 'Replace';
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldText();
$element->name           = 'replace';
$element->caption        = 'Replace';
$element->editability    = 'always';
$element->replace        = array('ä'=>'ae');
$element->elementStringFormat = '%s Replace german ä with ae.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'replaceI';
$element->caption        = 'Replace Case Insensitive';
$element->editability    = 'always';
$element->replaceI       = array('ö'=>'oe', 'ü'=>'ue');
$element->elementStringFormat = '%s Replace german ö with oe and ü with ue (case insensitive). Note: Ö will be replaced with oe and not OE or Oe. Same for ü.';
$container->addElement($element);
unset($element);


unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerCase";
$container->caption      = 'Case';
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldText();
$element->name           = 'caseLower';
$element->caption        = 'Lower Case';
$element->editability    = 'always';
$element->case           = 'lower';
$element->elementStringFormat = '%s Convert input to lowercase.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'caseUpper';
$element->caption        = 'Upper Case';
$element->editability    = 'always';
$element->case           = 'upper';
$element->elementStringFormat = '%s Convert input to uppercase.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'ucwords';
$element->caption        = 'Upper Case Words';
$element->editability    = 'always';
$element->case           = 'ucwords';
$element->elementStringFormat = '%s Make first character of each word uppercase.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'ucwordsonly';
$element->caption        = 'Upper Case Words Only';
$element->editability    = 'always';
$element->case           = 'ucwordsonly';
$element->elementStringFormat = '%s Same, but makes rest of input lowercase.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'ucfirst';
$element->caption        = 'Upper Case First';
$element->editability    = 'always';
$element->case           = 'ucfirst';
$element->elementStringFormat = '%s Make first character of input uppercase.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'ucfirstonly';
$element->caption        = 'Upper Case First Only';
$element->editability    = 'always';
$element->case           = 'ucfirstonly';
$element->elementStringFormat = '%s Same, but makes rest of input lowercase.';
$container->addElement($element);
unset($element);

/*
  *   - nospam1    change strings to ucfirst if they are all upper case. replace any number of ! and ? 
  *                signs with just one.
  *                eg 'HELLO! CLICK HERE FOR SEX PICTURES!!!' => 'Hello! click here for sex pictures!!!'
  *   - nospam2    nospam1 + replace any number of ! signs with just one dot '.'.
*/


$element =& new Bs_FormFieldCheckbox();
$element->name           = 'checkMe';
$element->caption        = 'Check me';
$element->text           = 'Check me if you don\' want to see the form again, and see a dump of your input instead.';
$element->editability    = 'always';
$element->must           = TRUE;
$element->hideCaption    = 2;
$form->elementContainer->addElement($element);
unset($element);


$element =& new Bs_FormFieldSubmit();
$element->name           = 'submitButton';
$element->caption        = 'submit';
$element->editability    = 'always';
$form->elementContainer->addElement($element);
unset($element);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Form example using input manipulation</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>
</head>

<body bgcolor="#E6E6E6">

<h1>Form example using input manipulation</h1>

<ul>
	<li>Demonstrating the use of input manipulation. It gets done before input validation.</li>
	<li>Using the elementStringFormat feature to display help texts on the right side of the fields.</li>
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