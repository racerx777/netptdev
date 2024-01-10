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
	$query = "INSERT INTO therapists ";
	$query .= "(ttherap, tname, tlic, tnpi, trefnum, tnote, crtdate, crtuser, crtprog) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['ttherap']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['tname']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['tlic']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['tnpi']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['trefnum']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['tnote']) . "', ";
	$auditfields = getauditfields();
	$query .= "'" . $auditfields['date'] . "', ";
	$query .= "'" . $auditfields['user'] . "', ";
	$query .= "'" . $auditfields['prog'] . "' ";
	$query .= ")";
	//execute the SQL query and return records
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$_SESSION['notify'][] = "Record successfully added to list.";
			unset($_POST['ttherap']);
			unset($_POST['tname']);
			unset($_POST['tlic']);
			unset($_POST['tnpi']);
			unset($_POST['trefnum']);
			unset($_POST['tnote']);
			unset($auditfields);
		}
		else
			error("001", mysqli_error($dbhandle));

	//close the connection
	mysqli_close($dbhandle);
}
?>