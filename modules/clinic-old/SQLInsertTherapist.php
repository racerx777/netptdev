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
	$query = "INSERT INTO master_clinics_treatmenttypes ";
	$query .= "(cttmcnum, cttmttmcode, crtdate, crtuser, crtprog) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cmcnum']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['ttmcode']) . "', ";
	$auditfields = getauditfields();
	$query .= "'" . $auditfields['date'] . "', ";
	$query .= "'" . $auditfields['user'] . "', ";
	$query .= "'" . $auditfields['prog'] . "' ";
	$query .= ")";
	//execute the SQL query and return records

	if($result = mysqli_query($dbhandle,$query)) {
		$_SESSION['notify'][] = "Record successfully added to Treatment Types.";
	}

	$query = "INSERT INTO master_clinics_therapists ";
	$query .= "(ctcnum, ctttmcode, cttherap, crtdate, crtuser, crtprog) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cmcnum']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['ttmcode']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['ttherap']) . "', ";
	$auditfields = getauditfields();
	$query .= "'" . $auditfields['date'] . "', ";
	$query .= "'" . $auditfields['user'] . "', ";
	$query .= "'" . $auditfields['prog'] . "' ";
	$query .= ")";
	//execute the SQL query and return records
	if($result = mysqli_query($dbhandle,$query)) {
		$_SESSION['notify'][] = "Record successfully added to Therapists.";
	}
	unset($_POST['cmcnum']);
	unset($_POST['ttmcode']);
	unset($auditfields);
	//close the connection
	mysqli_close($dbhandle);
}
?>