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
$element->maxLength       = 10000;
$element->height          = 300;
$element->bsDataType      = 'html';
$element->bsDataInfo      = 0;
$element->valueDefault    = "this <i>is</i> some <b>formatted</b> text with <u>formatting</u>\nas i mentioned it.\n";
$element->must            = TRUE;
$element->specialCharsSelectorUrl = FALSE;
$element->useRegisterEasy         = FALSE;
$element->useRegisterPlus         = FALSE;
$element->useRegisterCustom1      = TRUE;
$element->registerCustom1Caption  = 'Wysiwyg';
$element->registerCustom1Content  = array(
	array(
		'action'   => 'btnCut', 
		'imgName'  => 'bs_cut', 
		'title'    => 'Cut', 
		'helpText' => 'Cut out the selection and put it into the Clipboard.', 
	), 
	array(
		'action'   => 'btnCopy', 
		'imgName'  => 'bs_copy', 
		'title'    => 'Copy', 
		'helpText' => 'Copy the selection and put it into the Clipboard.', 
	), 
	array(
		'action'   => 'btnPaste', 
		'imgName'  => 'bs_paste', 
		'title'    => 'Paste', 
		'helpText' => 'Paste data in clipboard into the current place.', 
	), 
	
	'__SEPARATOR__', 
	
	array(
		'action'   => 'btnBold', 
		'imgName'  => 'bs_formatBold_en', 
		'title'    => 'Bold', 
		'helpText' => 'Makes selected text bold.', 
	), 
	array(
		'action'   => 'btnItalic', 
		'imgName'  => 'bs_formatItalic_en', 
		'title'    => 'Italic', 
		'helpText' => 'Makes selected text italic.', 
	), 
	
	'__SEPARATOR__', 
	
	array(
		'action'   => 'btnOl', 
		'imgName'  => 'bs_ol', 
		'title'    => 'Ordered List', 
		'helpText' => 'Adds numbers to or removes numbers from selected paragraphs.', 
	), 
	array(
		'action'   => 'btnUl', 
		'imgName'  => 'bs_ul', 
		'title'    => 'Unordered List', 
		'helpText' => 'Adds bullets to or removes bullets from selected paragraphs.', 
	), 
	
	'__SEPARATOR__', 
	
	array(
		'action'   => 'btnOutdent', 
		'imgName'  => 'bs_outdent', 
		'title'    => 'Outdent', 
		'helpText' => 'Outdent text.', 
	), 
	array(
		'action'   => 'btnIndent', 
		'imgName'  => 'bs_indent', 
		'title'    => 'Indent', 
		'helpText' => 'Indent text.', 
	), 
);

$container->addElement($element);
unset($element);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>example form 2 using blueshoes wysiwyg editor</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>
</head>

<body bgcolor="#E6E6E6">

<h1>example form 2 using blueshoes wysiwyg editor</h1>

<ul>
	<li>The tabs useRegisterEasy and useRegisterPlus (Wysiwyg and Wysiwyg++) are disabled.</li>
	<li>Instead we're using the "Custom 1" tab, named it "Wysiwyg", and defined the buttons we want.</li>
	<li>The control height is shrinked to 300 (pixel).</li>
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