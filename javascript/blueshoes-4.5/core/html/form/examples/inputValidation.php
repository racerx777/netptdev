<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . '../global.conf.php');
require_once($APP['path']['core']         . 'html/form/Bs_Form.class.php');

$form =& new Bs_Form();
$form->internalName         = 'inputValidationForm';
$form->name                 = 'inputValidationForm';
$form->useJsFile            = TRUE;
$form->language             = 'en';
$form->mode                 = 'add';
$form->advancedStyles       = array(
  'captionMustWrong' => 'formError', 
  'captionMayWrong'  => 'formError', 
);

unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerMust";
$container->caption      = 'Must';
$form->elementContainer->addElement($container);

$element =& new Bs_FormHtml();
$element->name           = 'mustInfoText';
$element->html           = '
	The 1st field may be filled in or not. 
	If it is left empty, that\' ok. But if one filles it in, 
	then the min/maxlength etc has to be respected.<br>
	The 2nd field has to be filled in, it\'s a "must" field.
	<hr>
';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'fieldMay';
$element->caption        = 'May';
$element->editability    = 'always';
$element->must           = FALSE;
$element->minLength      = 5;
$element->maxLength      = 10;
$element->elementStringFormat = '%s 5 to 10 characters.';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'fieldMust';
$element->caption        = 'Must';
$element->editability    = 'always';
$element->must           = TRUE;
$element->minLength      = 5;
$element->maxLength      = 10;
$element->elementStringFormat = '%s 5 to 10 characters.';
$container->addElement($element);
unset($element);



unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerMustIf";
$container->caption      = 'Must If';
$form->elementContainer->addElement($container);

$element =& new Bs_FormHtml();
$element->name           = 'mustIfInfoText';
$element->html           = '
	If you want the newsletter then you have to specify your email address. 
	So the field "Email" becomes a must field when the "Newsletter" checkbox is checked. 
	That\'s the "mustIf" feature.
	<hr>
';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldCheckbox();
$element->name           = 'checkboxNewsletter';
$element->caption        = 'Newsletter';
$element->text           = 'I want the newsletter';
$element->editability    = 'always';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'emailNewsletter';
$element->caption        = 'Email';
$element->editability    = 'always';
$element->mustIf         = array(array('operator'=>'|', 'field'=>'checkboxNewsletter'));
$element->bsDataType     = 'email';
$element->bsDataInfo     = 1;
$container->addElement($element);
unset($element);



unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerMustOneOf";
$container->caption      = 'Must One Of';
$form->elementContainer->addElement($container);

$element =& new Bs_FormHtml();
$element->name           = 'mustOneOfInfoText';
$element->html           = '
	The "mustOneOf" feature says that at least one of the fields specified has to be filled in.<br>
	Note that the mustOneOf definition has to be used on only one field. It may be used on all, but 
	that makes it no better.
	<hr>
';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'mustOneOfEmail';
$element->caption        = 'Email';
$element->editability    = 'always';
$element->mustOneOf      = array('mustOneOfPhone');
$element->bsDataType     = 'email';
$element->bsDataInfo     = 1;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'mustOneOfPhone';
$element->caption        = 'Phone';
$element->editability    = 'always';
$element->bsDataType     = 'text';
$element->bsDataInfo     = 1;
$container->addElement($element);
unset($element);



$element =& new Bs_FormHtml();
$element->name           = 'mustEtc';
$element->html           = '
	<hr>
	It goes on with "mustOneOfIf", "onlyOneOf", "onlyIf" and "onlyOneOfIf". Since their use should 
	be clear now i\'ll stop here.
	<hr>
';
$form->elementContainer->addElement($element);
unset($element);



unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerInString";
$container->caption      = 'In String';
$form->elementContainer->addElement($container);

$element =& new Bs_FormHtml();
$element->name           = 'inStringInfoText';
$element->html           = '
	Some special string validating things, their names describe what they do.<br>
	None of them is case sensitive!
	<hr>
';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'mustStartWith';
$element->caption        = 'mustStartWith';
$element->editability    = 'always';
$element->mustStartWith  = 'foo';
$element->bsDataType     = 'text';
$element->bsDataInfo     = 1;
$element->elementStringFormat = '%s Must start with the string "foo".';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'notStartWith';
$element->caption        = 'notStartWith';
$element->editability    = 'always';
$element->notStartWith   = 'foo';
$element->bsDataType     = 'text';
$element->bsDataInfo     = 1;
$element->elementStringFormat = '%s May not start with the string "foo".';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'mustEndWith';
$element->caption        = 'mustEndWith';
$element->editability    = 'always';
$element->mustEndWith    = 'foo';
$element->bsDataType     = 'text';
$element->bsDataInfo     = 1;
$element->elementStringFormat = '%s Must end with the string "foo".';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'notEndWith';
$element->caption        = 'notEndWith';
$element->editability    = 'always';
$element->notEndWith     = 'foo';
$element->bsDataType     = 'text';
$element->bsDataInfo     = 1;
$element->elementStringFormat = '%s May not end with the string "foo".';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'mustContain';
$element->caption        = 'mustContain';
$element->editability    = 'always';
$element->mustContain    = 'foo';
$element->bsDataType     = 'text';
$element->bsDataInfo     = 1;
$element->elementStringFormat = '%s Must contain the string "foo".';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'notContain';
$element->caption        = 'notContain';
$element->editability    = 'always';
$element->notContain     = 'foo';
$element->bsDataType     = 'text';
$element->bsDataInfo     = 1;
$element->elementStringFormat = '%s May not contain the string "foo".';
$container->addElement($element);
unset($element);




unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerEqualTo";
$container->caption      = 'Equal To';
$form->elementContainer->addElement($container);

$element =& new Bs_FormHtml();
$element->name           = 'equalToInfoText';
$element->html           = '
	The "equalTo" password can be used to have a user confirm an input, for example a password or an email address.<br>
	Note that it does not make it any better to use the setting on both fields, one is enough.
	<hr>
';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'password';
$element->caption        = 'Password';
$element->editability    = 'always';
$element->bsDataType     = 'text';
$element->bsDataInfo     = 1;
$element->must           = TRUE;
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name           = 'passwordConfirm';
$element->caption        = 'Confirm Password';
$element->editability    = 'always';
$element->equalTo        = 'password';
$element->bsDataType     = 'text';
$element->bsDataInfo     = 1;
$element->must           = TRUE;
$container->addElement($element);
unset($element);

unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerAdditionalCheck";
$container->caption      = 'Additional Check';
$form->elementContainer->addElement($container);

$element =& new Bs_FormHtml();
$element->name           = 'additionalCheckInfoText';
$element->html           = '
	Custom php code that will be evaluated. the code should return bool TRUE, or a string error 
	message in case the input is not accepted.
	<hr>
';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldText();
$element->name                = 'additionalCheck';
$element->caption             = 'Additional Check';
$element->editability         = 'always';
$element->maxLength           = 5;
$element->size                = 5;
$element->bsDataType          = 'number';
$element->bsDataInfo          = '0|100'; 
$element->must                = TRUE;
$element->additionalCheck     = "
	if (((int)\$v) != ((double)\$v)) {
		return 'Only integers are allowed.';
	} else {
		return TRUE;
	}
";
$element->elementStringFormat = '%s A value from 0 to 100. A value like 50.5 does not pass the additionalCheck.';
$container->addElement($element);
unset($element);


$element =& new Bs_FormHtml();
$element->name           = 'notEqualTo';
$element->html           = '
	<hr>
	Now that you\'ve learned about "equalTo" the use of "notEqualTo" is obvious.<br>
	Example usage: Make sure a user does not pick the same username and password.
	<hr>
	
	regularExpression: any regular expression or an array of regexps can be used that the input needs to pass.
	<hr><br>
';
$form->elementContainer->addElement($element);
unset($element);



unset($container);
$container =& new Bs_FormContainer();
$container->name         = "containerEnforce";
$container->caption      = 'Enforce';
$form->elementContainer->addElement($container);

$element =& new Bs_FormHtml();
$element->name           = 'enforceInfoText';
$element->html           = '
	The "enforce" feature (name is subject to change) allows to ingore "weak requirements". <br>
	This textarea field has a minlength of 1000 characters :-) but once you\'ve submitted the form 
	with less chars you can click the checkbox and you\'ll be left alone.
	<hr>
';
$container->addElement($element);
unset($element);

$element =& new Bs_FormFieldTextarea();
$element->name           = 'enforce';
$element->caption        = 'enforce';
$element->editability    = 'always';
$element->bsDataType     = 'text';
$element->bsDataInfo     = 1;
$element->must           = TRUE;
$element->minLength      = 1000;
$element->maxLength      = 5000;
$element->enforce        = array('must'=>TRUE, 'minLength'=>TRUE);
$container->addElement($element);
unset($element);






$element =& new Bs_FormFieldSubmit();
$element->name           = 'submitButton';
$element->caption        = 'submit';
$element->editability    = 'always';
$form->elementContainer->addElement($element);
unset($element);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Form example using input validation</title>
  <?php
  $formRet = $form->doItYourself();
	if (is_array($formRet)) {
		echo $form->includeOnceToHtml($formRet['include']);
		echo $form->onLoadCodeToHtml($formRet['onLoad']);
	}
  ?>
	
	<style>
	.formError {
		color:red;
	}
	</style>
</head>

<body bgcolor="#E6E6E6">

<h1>Form example using input validation</h1>

<ul>
	<li>This demonstrates most of the input validation options.</li>
	<li>For further information check the apidoc. All vars are described in great detail.</li>
</ul>
<br>

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