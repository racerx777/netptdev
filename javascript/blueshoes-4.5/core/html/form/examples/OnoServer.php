<?php
/**
* @package    core_html
* @subpackage form_examples
*/


require_once($_SERVER["DOCUMENT_ROOT"]       . "../global.conf.php");
require_once($APP['path']['core']            . "JavaScript/JSRS/lib_server/JsrsServer.class.php");
require_once($APP['path']['core'] . 'plugins/Onomastics/Bs_Om_OnomasticsServer.class.php');

$theOnoServer =& new Bs_Om_OnomasticsServer();

//dump($theOnoServer->getGender('andrea')); exit;
//dump($theOnoServer->isOrderOk('thalala', 'richârd')); exit;

$JsrsServer->propagateMethod($theOnoServer, 'getGender');
$JsrsServer->propagateMethod($theOnoServer, 'isOrderOk');
$JsrsServer->start();
?>