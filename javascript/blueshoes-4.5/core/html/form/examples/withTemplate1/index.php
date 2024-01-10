<?php
require_once($_SERVER["DOCUMENT_ROOT"]    . '../global.conf.php');
require_once($APP['path']['core']         . 'html/form/Bs_Form.class.php');

$form =& new Bs_Form();
$form->internalName         = 'firstTemplateForm';
$form->name                 = 'firstTemplateForm';
$form->mustFieldsVisualMode = 'starRight';
$form->useAccessKeys        = TRUE;
$form->useJsFile            = TRUE;
$form->jumpToFirstError     = TRUE;
$form->language             = 'en';
$form->mode                 = 'add';
$form->useTemplate          = TRUE;
$form->templatePath         = $APP['path']['core'] . 'html/form/examples/withTemplate1/';


$element =& new Bs_FormFieldText();
$element->name           = 'textFieldOne';
$element->caption        = 'Text Field One';
$element->editability    = 'always';
$element->width          = 10;
$element->minLength      = 2;
$element->must           = TRUE;
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'textFieldTwo';
$element->caption        = 'Text Field Two';
$element->editability    = 'always';
$element->width          = 10;
$element->minLength      = 10;
$element->must           = TRUE;
$form->elementContainer->addElement($element);
unset($element);


unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerOne";
$container->caption      = 'Container One';
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldText();
$element->name           = 'textFieldThree';
$element->caption        = 'Text Field Three';
$element->editability    = 'always';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'textFieldFour';
$element->caption        = 'Text Field Four';
$element->editability    = 'always';
$container->addElement($element);
unset($element);


unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerTwo";
$container->caption      = 'Container Two';
$container->useTemplate  = TRUE;
$container->templatePath = $APP['path']['core'] . 'html/form/examples/withTemplate1/';
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldText();
$element->name           = 'textFieldFive';
$element->caption        = 'Text Field Five';
$element->editability    = 'always';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'textFieldSix';
$element->caption        = 'Text Field Six';
$element->editability    = 'always';
$form->elementContainer->addElement($element);
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldSubmit();
$element->name           = 'submitButton';
$element->caption        = 'submit';
$element->editability    = 'always';
$form->elementContainer->addElement($element);
unset($element);
/*


$element =& new Bs_FormFieldCheckbox();
$element->name           = 'checkboxField';
$element->caption        = 'checkbox';
$element->editability    = 'always';
$element->text           = 'do you want to click here?';
$element->must           = TRUE;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldRadio();
$element->name           = 'radioField';
$element->caption        = 'radio';
$element->editability    = 'always';
$element->optionsHard    = array('foo'=>'foo', 'bar'=>'bar');
$element->must           = TRUE;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldSelect();
$element->name           = 'selectField';
$element->caption        = 'select';
$element->editability    = 'always';
$element->optionsHard    = array('foo'=>'foo', 'bar'=>'bar');
$element->must           = TRUE;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldTextarea();
$element->name           = 'textareaField';
$element->caption        = 'textarea';
$element->editability    = 'always';
$element->must           = TRUE;
$element->cols           = 40;
$element->rows           = 3;
$container->addElement($element);
unset($element);
*/


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Form using templates</title>
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

<h1>Form using templates</h1>

<ul>
	<li>There are 6 text fields. Two are attached to the form directly, and there are 2 containers with 2 text fields in each.</li>
	<li>The form itself uses a template. This template arranges the first 2 text fields, the 2 containers, and the submit button.</li>
	<li>The first container uses no template, thus its 2 fiels are spitted out in a standard way.</li>
	<li>The second container uses a template, and its 2 fields are arranged there.</li>
</ul>
<br>

<?php
if (is_array($formRet)) {
	echo $formRet['form'];
} else {
	echo 'Done!<br><br>';
	echo 'dumping $_POST:<br>';
	dump($_POST);
}
?>

</body>
</html>