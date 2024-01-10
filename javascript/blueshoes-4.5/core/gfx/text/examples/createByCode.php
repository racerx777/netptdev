<?php
/**
* @package    core_gfx
* @subpackage text_examples
*/

require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['core']      . 'gfx/text/Bs_TextType.class.php');

$tt =& new Bs_TextType();
$tt->set('fontSize', 30);
$tt->set('imgWidth', 500);
$tt->set('xAlign', 'right');
$tt->setText("Hello World");
$tt->create();
//$tt->save();
$tt->send();
$tt->destruct();

?>