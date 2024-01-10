<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');

function userupdateinactivate($id) {
	$dbhandle = dbconnect();
	if(isset($id)) {
		$query1 = "UPDATE master_user SET uminactive = NOT uminactive WHERE umid='" . $id . "'";
		$result1 = mysqli_query($dbhandle,$query1);
		if($result1) 
			$_SESSION['notify'][] = $numRows . "Record(s) $id Updated. User active status changed.";
		else 
			error("001", mysqli_error($dbhandle));
	}
	else
		error("002", "Error: Missing Record Id.");
} 

function userupdatereset($id) {
	$dbhandle = dbconnect();
	if(isset($id)) {
		$query1 = "UPDATE master_user SET umpass = 'bc8b4ec2f4d8acd76f342ea0d4b1b0ad', umlastpasswordchanged='1999-01-01 01:01:01' WHERE umid='" . $id . "'";
//dump("query1",$query1);
		if($result1 = mysqli_query($dbhandle,$query1)) 
			$_SESSION['notify'][] = $numRows . "Record(s) $id Updated. Password Reset.";
		else 
			error("001", mysqli_error($dbhandle));
	}
	else
		error("002", "Error: Missing Record Id.");
} 
?>