<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
errorclear();
// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

//declare the SQL statement that will query the database
$query1 = "UPDATE treatment_header SET thsbmStatus='0', thsbmDate='' WHERE thsbmStatus='100' and thid='" . $_SESSION['id'] . "'";
//execute the SQL query and return records
$result1 = mysqli_query($dbhandle,$query1);
$numRows1 = mysqli_num_rows($result1);
if($result1) 
	$_SESSION['notify'][] = $numRows . "Record(s) Un-submitted.";
else 
	error("001", mysqli_error($dbhandle));
//close the connection
mysqli_close($dbhandle);
?>