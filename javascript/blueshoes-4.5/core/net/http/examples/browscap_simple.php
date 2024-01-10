<?php
//require dependencies
$GLOBAL_CONF_TINY = TRUE;
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['core'] . 'net/http/Bs_Browscap.class.php');

$GLOBALS['Bs_Browscap']->compute();
dump($GLOBALS['Bs_Browscap']->data);
?>