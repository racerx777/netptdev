<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . '../global.conf.php');
require_once($APP['path']['core']         . 'html/form/Bs_Form.class.php');

$form =& new Bs_Form();
$form->internalName         = 'myForm';
$form->name                 = 'myForm';
$form->useJsFile            = TRUE;
$form->mustFieldsVisualMode = 'starRight';
$form->language             = 'en';
$form->mode                 = 'add';
$form->buttons              = 'default';
$form->level                = 1; //the initial level *needs* to be set.

$containerLevelOne =& new Bs_FormContainer();
$containerLevelOne->name            = 'levelOne';
$containerLevelOne->pseudoContainer = TRUE;
$containerLevelOne->level           = 1;
$form->elementContainer->addElement($containerLevelOne);

unset($container);
$container =& new Bs_FormContainer();
$container->name            = 'containerFoo';
$container->caption         = 'Foo';
$containerLevelOne->addElement($container);

$element =& new Bs_FormFieldText();
$element->name              = 'myTextOne';
$element->caption           = 'Text';
$element->editability       = 'always';
$element->must              = TRUE;
$element->minLength         = 20;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldTextarea();
$element->name              = 'myTextareaOne';
$element->caption           = 'Textarea';
$element->editability       = 'always';
$container->addElement($element);
unset($element);


unset($container);
$container =& new Bs_FormContainer();
$container->name            = 'containerBar';
$container->caption         = 'Bar';
$containerLevelOne->addElement($container);

$element =& new Bs_FormFieldText();
$element->name              = 'myTextTwo';
$element->caption           = 'Text';
$element->editability       = 'always';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldTextarea();
$element->name              = 'myTextareaTwo';
$element->caption           = 'Textarea';
$element->editability       = 'always';
$container->addElement($element);
unset($element);



$containerLevelTwo =& new Bs_FormContainer();
$containerLevelTwo->name            = 'levelTwo';
$containerLevelTwo->pseudoContainer = TRUE;
$containerLevelTwo->level           = 2;
$form->elementContainer->addElement($containerLevelTwo);

unset($container);
$container =& new Bs_FormContainer();
$container->name            = 'containerHello';
$container->caption         = 'Hello';
$containerLevelTwo->addElement($container);

$element =& new Bs_FormFieldText();
$element->name              = 'myTextThree';
$element->caption           = 'Text';
$element->editability       = 'always';
$element->must              = TRUE;
$element->minLength         = 20;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldTextarea();
$element->name              = 'myTextareaThree';
$element->caption           = 'Textarea';
$element->editability       = 'always';
$container->addElement($element);
unset($element);


unset($container);
$container =& new Bs_FormContainer();
$container->name            = 'containerWorld';
$container->caption         = 'World';
$containerLevelTwo->addElement($container);

$element =& new Bs_FormFieldText();
$element->name              = 'myTextFour';
$element->caption           = 'Text';
$element->editability       = 'always';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldTextarea();
$element->name              = 'myTextareaFour';
$element->caption           = 'Textarea';
$element->editability       = 'always';
$container->addElement($element);
unset($element);

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>MultiLevel Form example</title>
  <?php
	$mainOut = '';
	
	if (@$_POST['bs_form']['step'] == '2') {
	  $status = $form->setReceivedValues($_POST);
	  if (isEx($status)) {
	    $status->stackDump('die');
	  }
	  $isOk = $form->validate();
	}
	if ((@$_POST['bs_form']['level'] == 2) && @$isOk) {
		$mainOut .= '<h1>Done!</h1><br><br>';
		$mainOut .= '<h1>dumping getValuesArray():</h1>';
		$mainOut .= dump($form->getValuesArray(), TRUE);
		$mainOut .= '<h1>dumping $_POST:</h1>';
		$mainOut .= dump($_POST, TRUE);
	} else {
		$formRet = $form->getAll();
		if (is_array($formRet)) {
			echo $form->includeOnceToHtml($formRet['include']);
			echo $form->onLoadCodeToHtml($formRet['onLoad']);
		}
	}
  ?>
</head>

<body bgcolor="#E6E6E6">

<h1>MultiLevel Form example</h1>

<ul>
	<li>There are 2 levels.</li>
	<li>Level 1 uses two containers, Foo and Bar.</li>
	<li>Level 2 uses two containers, Hello and World.</li>
	<li>The first text field of both levels is a must field with a minlength of 20 chars. The level will only be completed once this requirement has been fulfilled.</li>
	<li>Note: If the next button is missing, there is a back button. Use it as next button. This is on the todo list.</li>
</ul>
<br>

<?php
if (@is_array($formRet)) {
	if (isSet($formRet['errors'])) $mainOut .= $formRet['errors'];
	$mainOut .= $formRet['form'];
}
echo $mainOut;
?>

</body>
</html>