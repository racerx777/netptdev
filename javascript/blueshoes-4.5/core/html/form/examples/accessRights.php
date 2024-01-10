<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . '../global.conf.php');
require_once($APP['path']['core']         . 'html/form/Bs_Form.class.php');

$accessRights = array(
  'guest'  => array(
    'add'   => 'normal', 
    'edit'  => 'readonly', 
  ), 
  'admin' => 'normal', 
);

$form =& new Bs_Form();
$form->internalName         = 'simpleForm';
$form->name                 = 'simpleForm';
$form->mustFieldsVisualMode = 'starRight';
$form->useJsFile            = TRUE;
$form->language             = 'en';
$form->mode                 = 'edit';
$form->user                 = 'guest';

unset($container);
$container =& new Bs_FormContainer();
$container->name         = "container";
$container->caption      = 'Container';
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldText();
$element->name           = 'textField';
$element->caption        = 'text';
$element->editability    = 'always';
$element->must           = TRUE;
$element->valueDefault   = 'text';
$element->accessRights   = $accessRights;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldPassword();
$element->name           = 'passwordField';
$element->caption        = 'password';
$element->editability    = 'always';
$element->must           = TRUE;
$element->valueDefault   = 'password';
$element->accessRights   = $accessRights;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldCheckbox();
$element->name           = 'checkboxField';
$element->caption        = 'checkbox';
$element->editability    = 'always';
$element->text           = 'do you want to click here?';
$element->must           = TRUE;
$element->valueDefault   = TRUE;
$element->accessRights   = $accessRights;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldRadio();
$element->name           = 'radioField';
$element->caption        = 'radio';
$element->editability    = 'always';
$element->optionsHard    = array('foo'=>'foo', 'bar'=>'bar');
$element->must           = TRUE;
$element->valueDefault   = 'bar';
$element->accessRights   = $accessRights;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldSelect();
$element->name           = 'selectField';
$element->caption        = 'select';
$element->editability    = 'always';
$element->optionsHard    = array('foo'=>'foo', 'bar'=>'bar');
$element->must           = TRUE;
$element->valueDefault   = 'bar';
$element->accessRights   = $accessRights;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldTextarea();
$element->name           = 'textareaField';
$element->caption        = 'textarea';
$element->editability    = 'always';
$element->must           = TRUE;
$element->cols           = 40;
$element->rows           = 3;
$element->valueDefault   = 'this is a textarea field.';
$element->accessRights   = $accessRights;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldButton();
$element->name           = 'buttonField';
$element->caption        = 'submit';
$element->editability    = 'always';
$element->accessRights   = $accessRights;
$container->addElement($element);
unset($element);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Form example using access rights</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>
</head>

<body bgcolor="#E6E6E6">

<h1>Form example using access rights</h1>

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