<?php
	global $myServer, $myUser, $myPass, $myDB;

function dbconnect() {
	$myServer = "208.57.67.221,31433";
	switch($_SESSION['SERVER_NAME']):	
		case 'netpt.wsptn.com':
			$myUser = "sunnispoon";
			$myPass = "apmiWeststar";
			break;
	endswitch;
	$dbhandle = mssql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer@" . $_SESSION['SERVER_NAME']);
	return($dbhandle);
}

function dbselect($dbhandle) {
	switch($_SESSION['SERVER_NAME']):	
		case 'netpt.wsptn.com':
			$myDB = "netpt_wsptn_com";
			break;
	endswitch;
	$dbselect = mssql_select_db($myDB, $dbhandle) or die("Couldn't open database $myDB");
	return($dbselect);
}
?>