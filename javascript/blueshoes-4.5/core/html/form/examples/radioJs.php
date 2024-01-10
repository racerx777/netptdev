<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");
require_once($APP['path']['core']      . 'html/form/Bs_Form.class.php');
require_once($APP['path']['core']      . 'html/form/specialfields/Bs_FormFieldRadioJs.class.php');       // <= special field


$form =& new Bs_Form();

$form->internalName         = 'myForm';
$form->name                 = 'myForm';
$form->buttons              = 'default';
$form->language             = 'en';
$form->mode                 = 'add';

$element =& new Bs_FormFieldRadioJs();
$element->name          = 'radio1';
$element->caption       = array('en'=>'vertical and enabled');
$element->editability   = 'always';
$element->optionsHard    = array('foo'=>'foo', 'bar'=>'bar');
$element->valueDefault  = 'bar';
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormFieldRadioJs();
$element->name          = 'radio2';
$element->caption       = array('en'=>'horizontal and disabled');
$element->editability   = 'never';
$element->optionsHard   = array('foo'=>'foo', 'bar'=>'bar');
$element->valueDefault  = 'bar';
$element->align         = 'h';
$form->elementContainer->addElement($element);
unset($element);



/*
$element =& new Bs_FormFieldCheckboxJs();
$element->name          = 'checkbox2';
$element->caption       = array('en'=>'Checkbox 2');
$element->editability   = 'never';
$element->valueDefault  = 2;
$element->text          = 'click me';
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormFieldCheckboxJs();
$element->name          = 'checkbox3';
$element->caption       = array('en'=>'Checkbox 3');
$element->editability   = 'never';
$element->valueDefault  = 0;
$element->text          = 'click me';
$form->elementContainer->addElement($element);
unset($element);


$element =& new Bs_FormFieldCheckboxJs();
$element->name          = 'checkbox10';
$element->caption       = array('en'=>'Checkbox 10');
$element->editability   = 'always';
$element->valueDefault  = 2;
$element->text          = 'click me';
$element->text          = 'click me';
$element->imgDir       = '/_bsJavascript/components/checkbox/img/bobby/';
$element->imgWidth     = '11';
$element->imgHeight    = '11';
$element->useMouseover = TRUE;
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormFieldCheckboxJs();
$element->name          = 'checkbox11';
$element->caption       = array('en'=>'Checkbox 11');
$element->editability   = 'never';
$element->valueDefault  = 2;
$element->text          = 'click me';
$element->imgDir       = '/_bsJavascript/components/checkbox/img/bobby/';
$element->imgWidth     = '11';
$element->imgHeight    = '11';
$element->useMouseover = TRUE;
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormFieldCheckboxJs();
$element->name          = 'checkbox12';
$element->caption       = array('en'=>'Checkbox 12');
$element->editability   = 'never';
$element->valueDefault  = 0;
$element->text          = 'click me';
$element->imgDir       = '/_bsJavascript/components/checkbox/img/bobby/';
$element->imgWidth     = '11';
$element->imgHeight    = '11';
$element->useMouseover = TRUE;
$form->elementContainer->addElement($element);
unset($element);
*/

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Example form using blueshoes javascript checkbox</title>
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

<h1>Example form using blueshoes javascript checkbox</h1>
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
