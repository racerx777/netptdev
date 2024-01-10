<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 

errorclear();
if(isset($id)) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	//declare the SQL statement that will query the database
	$query = "DELETE FROM master_accounts WHERE amId='" . $id . "'";
	//execute the SQL query and return records
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$_SESSION['notify'][] = "Record successfully deleted.";
	}
	else
		error("001", mysqli_error($dbhandle));
	mysqli_close($dbhandle);
	foreach($_POST as $key=>$val) 
		unset($_POST[$key]);
}
?>