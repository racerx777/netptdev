<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
require_once($APP['path']['core'] . 'html/form/Bs_Form.class.php');
require_once($APP['path']['core'] . 'html/form/specialfields/Bs_FormFieldTree.class.php');       // <= special field

$form =& new Bs_Form();

$form->internalName         = 'myForm';
$form->name                 = 'myForm';
$form->useJsFile            = TRUE;
$form->buttons              = 'default';
$form->language             = 'en';
$form->mode                 = 'add';

$a = array(
	array(
		'caption'          => "Yahoo", 
		'url'              => "http://www.yahoo.com/", 
		'isOpen'           => TRUE, 
		'isChecked'        => 1, 
		'children'         => array(
			array(
				'caption'          => "Yahoo Sports", 
				'url'              => "http://sports.yahoo.com/", 
				'isChecked'        => 1, 
				'children'         => array(
					array(
						'caption'          => "Baseball", 
						'isChecked'        => 2, 
					),
					array(
						'caption'          => "Soccer", 
						'isChecked'        => 0, 
					),
					array(
						'caption'          => "Hockey", 
						'isChecked'        => 0, 
					),
				),
			), 
			array(
				'caption'          => "Yahoo Finance", 
				'url'              => "http://finance.yahoo.com/", 
				'isChecked'        => 0, 
			),
		), 
	), 
	array(
		'caption'          => "BlueShoes", 
		'url'              => "http://www.blueshoes.org/", 
		'isChecked'        => 0, 
	),
);

$element =& new Bs_FormFieldTree();
$element->name            = 'treeOne';
$element->caption         = array('en'=>'Tree 1');
$element->editability     = 'always';
$element->treeElementData = $a;
$element->useCheckboxSystem      = TRUE;
$element->checkboxSystemWalkTree = 3;
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormFieldTree();
$element->name            = 'treeTwo';
$element->caption         = array('en'=>'Tree 2');
$element->editability     = 'always';
$element->treeElementData = $a;
$element->useRadioButton  = TRUE;
$form->elementContainer->addElement($element);
unset($element);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Example form using built-in blueshoes tree control</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>
</head>

<body bgcolor="#E6E6E6">

<h1>Example form using built-in blueshoes tree control</h1>
<br><br>

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