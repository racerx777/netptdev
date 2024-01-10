<?php
/**
* @package    core_gfx
* @subpackage text_examples
*/

require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['core']      . 'gfx/text/Bs_TextType.class.php');

$defaults = array(
  'fontDir' => 'c:/winnt/fonts/', 
);
$tt =& new Bs_TextType();
$tt->doItYourself($defaults);
?>