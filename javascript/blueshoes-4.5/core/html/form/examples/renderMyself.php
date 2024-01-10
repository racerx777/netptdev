<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
require_once($APP['path']['core'] . 'html/form/Bs_Form.class.php');
require_once($APP['path']['core'] . 'html/Bs_HtmlNavigation.class.php');

$navData = array(
  array(
    'caption'  => '<input type="radio" name="treeSelfmade" value="home" onClick="updateHiddenTree(this.value);"> Home',
  ), 
  array(
    'caption'  => '<input type="radio" name="treeSelfmade" value="html" onClick="updateHiddenTree(this.value);"> HTML',
    'children' => array(
      array(
        'caption' => '<input type="radio" name="treeSelfmade" value="form" onClick="updateHiddenTree(this.value);"> Form',
		    'children' => array(
		      array(
		        'caption' => '<input type="radio" name="treeSelfmade" value="foo" onClick="updateHiddenTree(this.value);"> Foo',
		      ),
		      array(
		        'caption' => '<input type="radio" name="treeSelfmade" value="bar" onClick="updateHiddenTree(this.value);"> Bar',
		      ),
				),
      ),
    ),
  ),
);

$n =& new Bs_HtmlNavigation();
//$n->setStyle($navStyle);
$n->setData($navData);
//$n->setCurrentPage($bsEnv['requestPath']); //that is '/en/framework/html/navigation/'


$form =& new Bs_Form();

$form->internalName         = 'myForm';
$form->name                 = 'myForm';
$form->useJsFile            = TRUE;
$form->buttons              = 'default';
$form->language             = 'en';
$form->mode                 = 'add';

$element =& new Bs_FormFieldHidden();
$element->name            = 'tree';
$element->caption         = array('en'=>'Tree');
$element->editability     = 'always';
$form->elementContainer->addElement($element);
unset($element);

$element =& new Bs_FormHtml();
$element->name            = 'treeHtml';
$element->caption         = array('en'=>'Tree');
$element->html            = $n->toHtml();
$form->elementContainer->addElement($element);
unset($element);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Example form where we use an input field that we render ourself</title>
  <?php
  $formRet = $form->getAll('string', TRUE);
	if (is_array($formRet)) {
		echo $formRet['include'];
		echo $formRet['onLoad'];
	}
  ?>
<script language="javascript">
<!--
function updateHiddenTree(newVal) {
	alert('new value: ' + newVal);
	document.forms.myForm.elements.tree.value = newVal;
}
//-->
</script>
</head>

<body bgcolor="#E6E6E6">

<h1>Example form where we use an input field that we render ourself.</h1>

We need to use a special form field: a tree with a radio button on each node. Just pretend that 
Bs_Form does not have such a control. :-)<br><br>

So we use a hidden Bs_Form field named 'tree'. And a visible selfmade radio button named 'treeSelfmade'. 
The 'treeSelfmade' has an onClick event to track changes. It will alert the new value, and set it to the 
hidden 'tree' field. After submitting the $_POST will be dumped.<br><br>


<?php
if (is_array($formRet)) {
	if (isSet($formRet['errors'])) echo $formRet['errors'];
	echo $formRet['form'];
} else {
	echo 'Done!';
}

if (isSet($_POST)) {
	dump($_POST);
}
?>


</body>
</html>