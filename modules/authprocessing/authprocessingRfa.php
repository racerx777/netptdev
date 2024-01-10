<?php
function rxSentRfa($cpid) {
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
if(!empty($cpid)) {
	$senttime = date("Y-m-d H:i:s", time());
	$sentuser = getuser();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$updatequery = "
	update case_prescriptions
	set cprfastatuscode='SNT', cprfastatususer='$sentuser', cprfastatusupdated='$senttime', cprfapossentdate='$senttime', cprfapossentuser='$sentuser'
	where cpid='$cpid'
	";
	if($updateresult = mysqli_query($dbhandle,$updatequery)) {
		notify("","Request for Authorization sent at $senttime by $sentuser.");
		require_once('authprocessingHistory.php');
		rxAddHistory($cpid, 'Request for Authorization sent.');
	}
	else 
		error("001","UPDATE QUERY:$query<br>".mysqli_error($dbhandle));
}
else
	error("002","No CPID.");
}
?>