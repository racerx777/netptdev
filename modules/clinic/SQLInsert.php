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

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
	
$select = "SELECT pgmbumcode FROM master_provider_groups WHERE pgmcode='".$_POST['cmpgmcode']."'";
if($result=mysqli_query($dbhandle,$select)) {
	$row=mysqli_fetch_assoc($result);
	$_POST['cmbnum']=$row['pgmbumcode'];
	notify("000","Business ".$_POST['cmbnum']." Unit Found.");
}
else
	error("999","Error retrieving associated business unit.".$select.mysqli_error($dbhandle));
// Validate form fields
if (!$_POST['cmcnum']) {
    error("998","Please enter a cnum");
}

if(errorcount() == 0) {
	//declare the SQL statement that will query the database
	$query = "INSERT INTO master_clinics ";
	$query .= "(cmpgmcode, cmcnum, cmbnum, cmname, cmemail, cmphone, cmfax, crtdate, crtuser, crtprog) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cmpgmcode']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cmcnum']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cmbnum']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cmname']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cmemail']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cmphone']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cmfax']) . "', ";
	$auditfields = getauditfields();
	$query .= "'" . $auditfields['date'] . "', ";
	$query .= "'" . $auditfields['user'] . "', ";
	$query .= "'" . $auditfields['prog'] . "' ";
	$query .= ")";
	//execute the SQL query and return records
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$_SESSION['notify'][] = "Record successfully added to current treatment list.";
			unset($_POST['cminactive']);
			unset($_POST['cmcnum']);
			unset($_POST['cmbnum']);
			unset($_POST['cmname']);
			unset($_POST['cmemail']);
			unset($_POST['cmphone']);
			unset($_POST['cmfax']);
			unset($auditfields);
		}
		else
			error("001", mysqli_error($dbhandle));
}
//close the connection
mysqli_close($dbhandle);
?>