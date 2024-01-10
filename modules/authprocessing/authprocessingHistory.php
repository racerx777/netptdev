<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16);
function rxAddHistory($cphcpid, $cphhistory, $cphalertdate=NULL, $cphalertuser=NULL) {
if(!empty($cphcpid) && !empty($cphhistory)) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$cphdate = date("Y-m-d H:i:s", time());
	$cphuser = getuser();
	$insertquery = "
	insert into case_prescriptions_history
	set cphcpid='$cphcpid', cphdate='$cphdate', cphuser='$cphuser', cphhistory='$cphhistory'
	";
	if($insertquery = mysqli_query($dbhandle,$insertquery)) 	
		notify("000","History added at $cphdate by $cphuser.");
	else 
		error("001","rxAddHistory:INSERT QUERY:$insertquery<br>".mysqli_error($dbhandle));
}
else
	error("002","rxAddHistory:No CPID.");
}
?>