<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . '../global.conf.php');
require_once($APP['path']['core']         . 'html/form/Bs_Form.class.php');
require_once($APP['path']['core']         . 'html/form/specialfields/Bs_FormFieldWysiwyg.class.php');
require_once($APP['path']['core']         . 'util/Bs_Array.class.php');
require_once($APP['path']['core']         . 'date/Bs_Date.class.php');
require_once($APP['path']['core']         . 'lang/Bs_Misc.lib.php');

$form =& new Bs_Form();
$form->internalName         = 'myForm';
$form->name                 = 'myForm';
$form->mustFieldsVisualMode = 'starRight';
$form->useJsFile            = TRUE;
$form->buttons              = 'default';
$form->language             = 'en';
$form->mode                 = 'add';

unset($container);
$container =& new Bs_FormContainer();
$container->name         = 'main';
$container->caption      = 'main';
$container->mayToggle    = FALSE;
$form->elementContainer->addElement($container);

$element =& new Bs_FormFieldWysiwyg();
$element->name            = 'textOne';
$element->caption         = 'text 1';
$element->editability     = 'always';
$element->minLength       = 10;
$element->maxLength       = 1000;
$element->dataType        = 'text';
$element->bsDataType      = 'text';
$element->bsDataInfo      = BS_FORM_TEXT_ANYTHING;
$element->valueDefault    = "This is some plain text\nthat goes into a nice editor\ninstead of just using the ugly\ntextarea field.";
$element->imageBrowserUrl = '/_bsApplications/filebrowser/examples/example1.php';
$element->must            = TRUE;
$container->addElement($element);
unset($element);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>example form 3 using blueshoes wysiwyg editor</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>
</head>

<body bgcolor="#E6E6E6">

<h1>example form 3 using blueshoes wysiwyg editor</h1>

<ul>
	<li>Using the Wysiwyg Editor as Text Editor here.</li>
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