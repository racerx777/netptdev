<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 
errorclear();

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
	$query = "INSERT INTO master_user ";
	$query .= "(umuser, umpass, umname, umemail, umclinic, umcreated, umhomepage, umrole) ";
	$query .= "VALUES(";
	$query .= "'" . $_POST['umuser'] . "', ";
	$query .= "'" . md5($_POST['umuser']) . "', ";
	$query .= "'" . $_POST['umname'] . "', ";
	$query .= "'" . $_POST['umemail'] . "', ";
	$query .= "'" . $_POST['umclinic'] . "', ";
	$query .= "'" . date('Y-m-d H:i:s') . "', ";
	$query .= "'" . $_POST['umhomepage'] . "', ";
	$query .= "'" . $_POST['umrole'] . "' ";
	$query .= ")";
	//execute the SQL query and return records
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$_SESSION['notify'][] = "Record successfully added to current treatment list.";
	}
	else
		error('001', mysqli_error($dbhandle));	
	//close the connection
	unset($_POST['umuser']);
	unset($_POST['umpassword']);
	unset($_POST['umname']);
	unset($_POST['umemail']);
	unset($_POST['umclinic']);
	unset($_POST['umhomepage']);
	unset($_POST['umrole']);
	mysqli_close($dbhandle);
}
?>