<?php
function rxRequestedDocs($cpid) {
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
if(!empty($cpid)) {
	$requesttime = date("Y-m-d H:i:s", time());
	$requestuser = getuser();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$updatequery = "
	update case_prescriptions
	set cpdocstatuscode='RQS', cpdocstatususer='$requestuser', cpdocstatusupdated='$requesttime'
	where cpid='$cpid' and cpauthstatuscode='NEW'
	";
	if($updateresult = mysqli_query($dbhandle,$updatequery)) {
		notify("","Request for Documents received at $requesttime by $requestuser.");
		require_once('authprocessingHistory.php');
		foreach($_POST['doc'] as $docname=>$val)
			if($docname != 'OTHER')
				rxAddHistory($cpid, "Document request received. $docname");
		if(!empty($_POST['doc']['OTHER']))
			rxAddHistory($cpid, 'Document request received. ' . $_POST['other']);
	}
	else 
		error("001","UPDATE QUERY:$query<br>".mysqli_error($dbhandle));
}
else
	error("002","No CPID.");
}
function rxSentDocs($cpid) {
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
if(!empty($cpid)) {
	$senttime = date("Y-m-d H:i:s", time());
	$sentuser = getuser();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$updatequery = "
	update case_prescriptions
	set cpdocstatuscode='SNT', cpdocstatususer='$sentuser', cpdocstatusupdated='$senttime'
	where cpid='$cpid' and cpauthstatuscode='NEW'
	";
	if($updateresult = mysqli_query($dbhandle,$updatequery)) {
		notify("","Sent requested documents at $senttime by $sentuser.");
		require_once('authprocessingHistory.php');
		foreach($_POST['doc'] as $docname=>$val)
			if($docname != 'OTHER')
				rxAddHistory($cpid, "Document request sent. $docname");
		if(!empty($_POST['doc']['OTHER']))
			rxAddHistory($cpid, 'Document request sent. ' . $_POST['other']);
	}
	else 
		error("001","UPDATE QUERY:$query<br>".mysqli_error($dbhandle));
}
else
	error("002","No CPID.");
}
?>