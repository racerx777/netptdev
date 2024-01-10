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
$element->name            = 'textOne';
$element->caption         = array('en'=>'Text 1');
$element->editability     = 'always';
$element->bsDataType      = 'text';
$element->must            = TRUE;
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormFieldFile();
$element->name            = 'fileOne';
$element->caption         = array('en'=>'File 1');
$element->editability     = 'always';
//$element->bsDataType      = 'number';
$element->must            = TRUE;
$form->elementContainer->addElement($element);
unset($element);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Example file upload form</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>
</head>

<body bgcolor="#E6E6E6">

<h1>Example file upload form</h1>

<ul>
	<li>
		When you upload a file and leave the "Text 1" field empty, the server will show you the form again and 
		complain about the missing information. But your uploaded file won't be lost, it will be kept on the server.
	</li>
	<li>For more features etc check the apidoc.</li>
</ul>
<br>

<?php
echo 'dumping $_POST:<br>';
dump($_POST);
echo 'dumping $_FILES:<br>';
dump($_FILES);

if (is_array($formRet)) {
	if (isSet($formRet['errors'])) echo $formRet['errors'];
	echo $formRet['form'];
} else {
	echo 'Done!';
}
?>


</body>
</html>