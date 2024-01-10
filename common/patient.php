<?php
function getpatient($id) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * FROM patients WHERE painactive = 0 and paid=$id";
	$result = mysqli_query($dbhandle,$query);
	$numRows = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($result) 
		return($row);
	else
		return(array());
}
?>