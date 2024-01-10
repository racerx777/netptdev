<?php
if(isset($_SESSION['id'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(66); 
	errorclear();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	//declare the SQL statement that will query the database
	$query = "DELETE FROM doctors WHERE dmid='" . $_SESSION['id'] . "'";
	//execute the SQL query and return records
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$_SESSION['notify'][] = "Record " . $_SESSION['id'] . " successfully removed.";
		unset($_POST);
	}
	else
		error("001", mysqli_error($dbhandle));
	mysqli_close($dbhandle);
}
?>