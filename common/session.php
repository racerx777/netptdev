<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/redirecturi.php'); 
function sessionstart() {
	// if( empty(session_id()) && !headers_sent()){
	// 	session_start();
	// }
	session_start();
	$_SESSION['ready'] = time();
	$_SESSION['SERVER_NAME'] = strtolower($_SERVER['SERVER_NAME']);

	if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone") != false)  
		$_SESSION['iphone'] = '1';
	else
		unset($_SESSION['iphone']);

	if (strpos($_SERVER['HTTP_USER_AGENT'],"Mobile") != false)  
		$_SESSION['mobile'] = '1';
	else
		unset($_SESSION['mobile']);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/debug.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/user.php');
	securitycheck($_SERVER['SCRIPT_FILENAME']);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/sitedivs.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/appdata.php');	
	getappdata();
}

function sessionstop() {
	session_destroy();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/redirect.php'); 
}

if(1==1 || $_REQUEST['upgrade']==1) {
//	session_set_cookie_params(120*60);
	sessionstart();
}
else {
	echo('
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>NetPT by WestStar</title>
</head>
<body>
<img style="float:left;" src="../img/logo.gif">
');
exit;
}
?>