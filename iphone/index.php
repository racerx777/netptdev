<?php
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone") == true)  
	$_SESSION['iphone'] = '1';
else
	$_SESSION['iphone'] = '0';
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/redirect.php'); 
?>