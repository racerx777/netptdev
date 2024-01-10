<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
errorclear();
if(empty($cpid)) 
	error('000', "Error cpid : $cpid");
else {
	// Validate form fields
//	require_once('prescriptionValidationAuthorization.php');

	if(errorcount() == 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$cpstatususer = getuser();
		//declare the SQL statement that will query the database
		$query = "UPDATE case_prescriptions set cpstatuscode='ACT', cpstatususer='$cpstatususer', cpstatusupdated=NOW(), cpauthstatuscode='NEW', cpauthstatusupdated=NOW(), cprfastatuscode='NEW' WHERE cpid='$cpid'";
		if($result = mysqli_query($dbhandle,$query)) {
			$_SESSION['notify'][] = "Prescription status updated to ACT, Authorization status set to NEW, Request for Authorization status set to NEW.";
			foreach($_POST as $key=>$val) 
				unset($_POST[$key]);
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/authprocessing/authprocessingHistory.php');
		rxAddHistory($cpid, "Sent to authorization processing");
		}
		else
			error('001', "Error Updating Record : " . mysqli_error($dbhandle)); 	
		//close the connection
		mysqli_close($dbhandle);
	}
}
?>