<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
if($_SESSION['user']['umrole'] < 10) 
	header('Location:https://' . $_SESSION['SERVER_NAME']);

errorclear();
if(isset($id)) {
// trim and strip all input
foreach($_POST as $key=>$val) {
	if($key != 'button') {
		if(is_string($_POST[$key]))
			$_POST[$key] = stripslashes(strip_tags(trim($val)));
	}
}

// Validate form fields
require_once('validation.php');

if(errorcount() == 0) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
		
	//declare the SQL statement that will query the database
	$query = "INSERT INTO cases ";
	$query .= "(crinjurydate, crcasetype, crcasestatus, crupddate, crupduser) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,date('Y-m-d', strtotime($_POST['crinjurydate']))) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['crcasetype']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['crcasestatus']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,date('Y-m-d H:i:s')) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_SESSION['user']['umuser']) . "'  ";
	$query .= ")";
	
	$query = "INSERT INTO patients ";
	$query .= "(palast, pafirst, passn, padob, paphone, paupddate, paupduser) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['palast']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['pafirst']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['passn']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,date('Y-m-d', strtotime($_POST['padob']))) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['paphone']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,date('Y-m-d H:i:s')) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_SESSION['user']['umuser']) . "' ";
	$query .= ")";

	$query = "INSERT INTO appointments ";
	$query .= "(apdate, apcnum, apstatus, paupddate, paupduser) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,date('Y-m-d', strtotime($_POST['apdate']))) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['apnum']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['apstatus']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,date('Y-m-d', strtotime($_POST['paupddate']))) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_SESSION['user']['umuser']) . "' ";
	$query .= ")";

	//execute the SQL query 
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$_SESSION['notify'][] = "Record successfully inserted.";
		foreach($_POST as $key=>$val) 
			unset($_POST[$key]);


	}
	else 
		error('001', "Error Inserting Record : " . mysqli_error($dbhandle)); 	
	//close the connection
	mysqli_close($dbhandle);
	}
}
?>









