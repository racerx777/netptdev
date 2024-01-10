<?php
//require dependencies
$GLOBAL_CONF_TINY = TRUE;
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['core'] . 'net/http/Bs_Browscap.class.php');

if (@$_GET['bcRun']) {
	$GLOBALS['Bs_Browscap']->compute();
	dump($GLOBALS['Bs_Browscap']->data);
} else {
	$GLOBALS['Bs_Browscap']->runTest();
}
?>