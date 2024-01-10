<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90);
errorclear();
if(isset($_SESSION['id'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	//declare the SQL statement that will query the database
	$query = "DELETE FROM master_business_units WHERE bumcode='" . $_SESSION['id'] . "'";
	//execute the SQL query and return records
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$_SESSION['notify'][] = "Record successfully removed from current list.";
		unset($_POST['buminactive']);
		unset($_POST['bumcode']);
		unset($_POST['bumname']);
		unset($_POST['bumtaxid']);
		unset($_POST['bumemail']);
		unset($_POST['bumphone']);
		unset($_POST['bumfax']);
	}
	else
		error("001", mysqli_error($dbhandle));	
	mysqli_close($dbhandle);
}
?>