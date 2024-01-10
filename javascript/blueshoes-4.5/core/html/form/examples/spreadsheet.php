<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"] . "../global.conf.php");
require_once($APP['path']['core']      . 'html/form/Bs_Form.class.php');
require_once($APP['path']['core']      . 'html/form/specialfields/Bs_FormFieldSpreadsheet.class.php');       // <= special field


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

$element =& new Bs_FormFieldSpreadsheet();
$element->name          = 'spreadSheetOne';
$element->caption       = array(
  'en'=>'spreadsheet 1', 
);
$element->editability   = 'always';
$element->bsDataType    = 'text';
$element->bsDataInfo    = 0;
$element->valueDefault  = array(
//  'en' => array(
    array('adsf', 'lala', 'lolo'), 
    array('asdf', 'lala', 'lolo'), 
//  ), 
);
$element->valueDefaultType  = 'array';
//$element->must          = TRUE;
//$element->trim          = 'both';
$container->addElement($element);
unset($element);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>example form using blueshoes spreadsheet editor</title>
  <?php
  $formCode = $form->getForm();
  echo $form->getIncludeOnce('string');
  echo $form->getOnLoadCode();
  ?>

  <script>
  core.loadUnit("toolbar");
  core.loadUnit("speedbutton");
  </script>

  <style>
    td {
    	font-family : Arial, Helvetica, sans-serif;
    	font-size : 13px;
    }
  </style>

</head>

<body bgcolor="#E6E6E6">

<?php
echo $formCode;
?>

</body>
</html>
