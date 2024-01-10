<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
errorclear();
// trim and strip all input
foreach($_POST as $key=>$val) {
	if($key != 'button') {
		if(is_string($_POST[$key]))
			$_POST[$key] = stripslashes(strip_tags(trim($val)));
	}
}

// Validate form fields

if(errorcount() == 0) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
		
	//declare the SQL statement that will query the database
	$query = "INSERT INTO master_provider_groups ";
	$query .= "(pgmbumcode, pgmcode, pgmname, pgmemail, pgmphone, pgmfax, crtdate, crtuser, crtprog) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['pgmbumcode']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['pgmcode']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['pgmname']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['pgmemail']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['pgmphone']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['pgmfax']) . "', ";
	$auditfields = getauditfields();
	$query .= "'" . $auditfields['date'] . "', ";
	$query .= "'" . $auditfields['user'] . "', ";
	$query .= "'" . $auditfields['prog'] . "' ";
	$query .= ")";
	//execute the SQL query and return records
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$_SESSION['notify'][] = "Record successfully added to list.";
			unset($_POST['pgmbumcode']);
			unset($_POST['pgmcode']);
			unset($_POST['pgmname']);
			unset($_POST['pgmemail']);
			unset($_POST['pgmphone']);
			unset($_POST['pgmfax']);
			unset($auditfields);
		}
		else
			error("001", mysqli_error($dbhandle));

	//close the connection
	mysqli_close($dbhandle);
}
?>