<?php
function clinicupdateinactivate($id) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(23);
	errorclear();
	if(isset($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "UPDATE master_clinics SET cminactive = NOT cminactive WHERE cmcnum='" . $id . "'";
		$result1 = mysqli_query($dbhandle,$query1);
		if($result1) {
			$_SESSION['notify'][] = $numRows . "Record(s) $id Updated.";
			unset($_SESSION['clinics']);
		}
		else 
			error("001", mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("002", "Error: Missing Record Id.");
}
?>