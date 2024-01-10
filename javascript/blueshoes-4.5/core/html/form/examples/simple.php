<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . '../global.conf.php');
require_once($APP['path']['core']         . 'html/form/Bs_Form.class.php');

$form =& new Bs_Form();
$form->internalName         = 'simpleForm';
$form->name                 = 'simpleForm';
$form->mustFieldsVisualMode = 'starRight';
$form->useAccessKeys        = TRUE;
$form->useJsFile            = TRUE;
$form->jumpToFirstError     = TRUE;
$form->buttons              = FALSE;
$form->language             = 'en';
$form->mode                 = 'add';
$form->onEnter              = 'tab';
$form->advancedStyles       = array(
  'captionMust'      => '', 
  'captionMustOkay'  => '', 
  'captionMustWrong' => 'formError', 
  'captionMay'       => '', 
  'captionMayOkay'   => '', 
  'captionMayWrong'  => 'formError', 
  'fieldMust'        => '', 
  'fieldMustOkay'    => '', 
  'fieldMustWrong'   => '', 
  'fieldMay'         => '', 
  'fieldMayOkay'     => '', 
  'fieldMayWrong'    => '', 
);


unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerNonFields";
$container->caption      = 'Non-Field Form Elements';
$container->mayToggle    = TRUE;
$form->elementContainer->addElement($container);

$element =& new Bs_FormText();
$element->name           = 'textBlock';
$element->text           = 'This is a text block. Any text goes here.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormHtml();
$element->name           = 'htmlBlock';
$element->html           = 'This is a <b>html</b> block. Any <i>html</i> goes here.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormLine();
$element->name           = 'lineBlock';
$element->align          = 'center';
$element->width          = 300;
$element->size           = 1;
$element->color          = 'green';
$element->noshade        = TRUE;
$container->addElement($element);
unset($element);

$container2 =& new Bs_FormContainer();
$container2->name         = "innerContainerOne";
$container2->caption      = 'Inner container';
$container2->mayToggle    = TRUE;
$container->addElement($container2);

$container3 =& new Bs_FormContainer();
$container3->name         = "innerContainerTwo";
$container3->caption      = 'Another inner container';
$container3->mayToggle    = TRUE;
$container2->addElement($container3);

$element =& new Bs_FormCode();
$element->name           = 'codeBlock';
$element->code           = 'for ($i=0; $i<5; $i++) echo "echo line {$i}<br>\n";';
$container3->addElement($element);
unset($element);


unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerFields";
$container->caption      = 'Field Form Elements';
$container->mayToggle    = TRUE;
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldText();
$element->name           = 'textField';
$element->caption        = 'text';
$element->editability    = 'always';
$element->must           = TRUE;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldPassword();
$element->name           = 'passwordField';
$element->caption        = 'password';
$element->editability    = 'always';
$element->must           = TRUE;
$container->addElement($element);
unset($element);

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


$element =& new Bs_FormFieldSubmit();
$element->name           = 'submitButton';
$element->caption        = 'submit';
$element->editability    = 'always';
$container->addElement($element);
unset($element);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Simple example form</title>
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

<h1>Simple example form</h1>

<ul>
	<li></li>
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