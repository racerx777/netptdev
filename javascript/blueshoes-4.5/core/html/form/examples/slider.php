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

$element =& new Bs_FormFieldSlider();
$element->name            = 'sliderOne';
$element->caption         = array('en'=>'Slider 1');
$element->editability     = 'always';
$element->bsDataType      = 'number';
$element->must            = TRUE;
$element->valueDefault    = 3;

$element->width         = 121;
$element->height        = 26;
$element->minVal        = 1;
$element->maxVal        = 20;
$element->valueInterval = 1;
$element->arrowAmount   = 1;
$element->imgBasePath   = '/_bsJavascript/components/slider/img/';
$element->setBackgroundImage('bob/background.gif', 'no-repeat');
$element->setSliderIcon('bob/slider.gif', 13, 18);
$element->setArrowIconLeft('img/arrowLeft.gif', 16, 16);
$element->setArrowIconRight('img/arrowRight.gif', 16, 16);
$element->useInputField = 2;
$element->styleValueFieldClass = 'sliderInput';
$element->colorbar = array();
$element->colorbar['color']           = 'blue';
$element->colorbar['height']          = 5;
$element->colorbar['widthDifference'] = -12;
$element->colorbar['offsetLeft']      = 5;
$element->colorbar['offsetTop']       = 9;

$form->elementContainer->addElement($element);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Example form using built-in blueshoes slider control</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>
</head>

<body bgcolor="#E6E6E6">

<h1>Example form using built-in blueshoes slider control</h1>
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