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
	$query = "INSERT INTO master_business_units ";
	$query .= "(bumcode, bumname, bumtaxid, bumemail, bumphone, bumfax, crtdate, crtuser, crtprog) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['bumcode']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['bumname']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['bumtaxid']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['bumemail']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['bumphone']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['bumfax']) . "', ";
	$auditfields = getauditfields();
	$query .= "'" . $auditfields['date'] . "', ";
	$query .= "'" . $auditfields['user'] . "', ";
	$query .= "'" . $auditfields['prog'] . "' ";
	$query .= ")";
	//execute the SQL query and return records
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$_SESSION['notify'][] = "Record successfully added to list.";
			unset($_POST['bumcode']);
			unset($_POST['bumname']);
			unset($_POST['bumtaxid']);
			unset($_POST['bumemail']);
			unset($_POST['bumphone']);
			unset($_POST['bumfax']);
			unset($auditfields);
		}
		else
			error("001", mysqli_error($dbhandle));

	//close the connection
	mysqli_close($dbhandle);
}
?>