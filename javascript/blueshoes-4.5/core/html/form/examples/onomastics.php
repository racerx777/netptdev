<?php
/**
* example using onomastics
* note: you need to place OnoServer.php in the same dir as this file.
* remote scripting (jsrs) is used to determine if you mixed first/lastname 
* of if you mixed the gender. example: thomas is not female. 
* 
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
require_once($APP['path']['core'] . 'html/form/Bs_Form.class.php');
require_once($APP['path']['core'] . 'html/form/specialfields/Bs_FormFieldSex.class.php');        // <= special field
require_once($APP['path']['core'] . 'html/form/specialfields/Bs_FormFieldFirstname.class.php');  // <= special field
require_once($APP['path']['core'] . 'html/form/specialfields/Bs_FormFieldLastname.class.php');   // <= special field


$form =& new Bs_Form();

$form->serializeType        = 'php';
$form->internalName         = 'myForm';
$form->name                 = 'myForm';
$form->mustFieldsVisualMode = 'starRight';
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



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>example form using onomastics</title>
  <?php
  $formCode = $form->getForm();
  echo $form->getIncludeOnce('string');
  echo $form->getOnLoadCode();
  ?>
</head>

<body bgcolor="#E6E6E6">

<?php
echo $formCode;
?>

</body>
</html>
