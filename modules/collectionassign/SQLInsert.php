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


// Validate form fields

if(errorcount() == 0) {
	//declare the SQL statement that will query the database
	$query = "INSERT INTO master_collections_queue_assign ";
	$query .= "(cqauser, cqagroup, crtdate, crtuser, crtprog) ";
	$query .= "VALUES(";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cqauser']) . "', ";
	$query .= "'" . mysqli_real_escape_string($dbhandle,$_POST['cqagroup']) . "', ";
	$auditfields = getauditfields();
	$query .= "'" . $auditfields['date'] . "', ";
	$query .= "'" . $auditfields['user'] . "', ";
	$query .= "'" . $auditfields['prog'] . "' ";
	$query .= ")";
	//execute the SQL query and return records
		$result = mysqli_query($dbhandle,$query);
		if($result) 
			$_SESSION['notify'][] = "Record successfully added to list.";
		else
			error("001", mysqli_error($dbhandle));
}
//close the connection
mysqli_close($dbhandle);
?>