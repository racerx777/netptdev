<?php
/**
* @package    plugins_onomastics
* @subpackage examples
*/

require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
require_once($APP['path']['plugins']      . 'onomastics/Bs_Om_OnomasticsServer.class.php');
$o = &$GLOBALS['Bs_Om_OnomasticsServer'];
$firstname = 'christina';
$lastname  = 'agileera';
echo "firstname similarity: " . $o->calcSimilarityFirstname($firstname, 'christina') . "<br>";
echo "lastname similarity: " . $o->calcSimilarityLastname($lastname, 'aguilera') . "<br>";
?>