<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
errorclear();

function addPrescriptionHistorySimple($cphcpid, $cphhistory, $system) {
	$cphdate=date("Y-m-d H:i:s",time()); // now
	$cphuser=getuser(); // this user
	addPrescriptionHistory($cphcpid, $cphdate, $cphuser, $cphhistory, $system);
}

function addPrescriptionHistory($cphcpid, $cphdate, $cphuser, $cphhistory, $system) {
	$cphcpid=stripslashes(strip_tags(trim($cphcpid)));
	$cphdate=stripslashes(strip_tags(trim($cphdate)));
	$cphuser=stripslashes(strip_tags(trim($cphuser)));
	$cphhistory=stripslashes(strip_tags(trim($cphhistory)));
	$auditfields = getauditfields();
	$crtdate=stripslashes(strip_tags(trim($auditfields['date'])));
	$crtuser=stripslashes(strip_tags(trim($auditfields['user'])));
	$crtprog=stripslashes(strip_tags(trim($system)));
// Validate form fields
//	require_once('insuranceValidation.php');
	
	if(errorcount() == 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$set=array();
		//declare the SQL statement that will query the database
		$set[] = "cphcpid ='" . mysqli_real_escape_string($dbhandle,$cphcpid) . "'";
		$set[] = "cphdate ='" . mysqli_real_escape_string($dbhandle,$cphdate) . "'";
		$set[] = "cphuser ='" . mysqli_real_escape_string($dbhandle,$cphuser) . "'";
		$set[] = "cphhistory ='" . mysqli_real_escape_string($dbhandle,$cphhistory) . "'";
		$set[] = "crtdate ='" . mysqli_real_escape_string($dbhandle,$cpid) . "'";
		$set[] = "crtuser ='" . mysqli_real_escape_string($dbhandle,$cpid) . "'";
		$set[] = "crtprog ='" . mysqli_real_escape_string($dbhandle,$system) . "'";
		if(count($set) > 0)
			$values = "SET " . implode(', ', $set);

		$insertquery = "
			INSERT INTO case_prescriptions_history $values 
		"; 
//dump("insertquery",$insertquery);
		//execute the SQL query 
		if($insertresult = mysqli_query($dbhandle,$insertquery)) 
			notify("000","History successfully added.");
		else
			error('001', "INSERT Error.$insertquery<br>" . mysqli_error($dbhandle)); 	
		//close the connection
		mysqli_close($dbhandle);
	}
}
?>