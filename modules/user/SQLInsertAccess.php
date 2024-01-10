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
//require_once('validation.php');

if(errorcount() == 0) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	//declare the SQL statement that will query the database
	$query = "INSERT INTO user_clinic_access ";
	$query .= "(ucaumid, ucabumcode, ucapgmcode, ucacmcnum, ucarmcode, ucahpmcode, crtdate, crtuser, crtprog) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_SESSION['id']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['ucabumcode']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['ucapgmcode']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['ucacmcnum']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['ucarmcode']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['ucahpmcode']) . "', ";
	$auditfields = getauditfields();
	$query .= "'" . $auditfields['date'] . "', ";
	$query .= "'" . $auditfields['user'] . "', ";
	$query .= "'" . $auditfields['prog'] . "' ";
	$query .= ")";
	//execute the SQL query and return records
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$_SESSION['notify'][] = "Record successfully added to User Clinic Access List.";

		$umid=mysqli_real_escape_string($dbhandle,$_SESSION['id']);
		$homepage=mysqli_real_escape_string($dbhandle,$_POST['ucahpmcode']);
		$role=mysqli_real_escape_string($dbhandle,$_POST['ucarmcode']);
		$auditfields = getauditfields();
		$upddate=mysqli_real_escape_string($dbhandle,$auditfields['date']);
		$upduser=mysqli_real_escape_string($dbhandle,$auditfields['user']);
		$updprog=mysqli_real_escape_string($dbhandle,$auditfields['prog']);

		$query = "UPDATE master_user SET umhomepage='$homepage', umrole='$role', upddate='$upddate', upduser='$upduser', updprog='$upddate' WHERE umid='$umid'";
		//execute the SQL query and return records
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$_SESSION['notify'][] = "Master User Record successfully updated.";
		}
		else
			error('002', mysqli_error($dbhandle));	
	}
	else
		error('001', mysqli_error($dbhandle));	
	//close the connection
	unset($_SESSION['button']);
	unset($_POST['ucabumcode']);
	unset($_POST['ucapgmcode']);
	unset($_POST['ucacmcnum']);
	unset($_POST['ucarmcode']);
	unset($_POST['ucahpmcode']);
	mysqli_close($dbhandle);
}
?>