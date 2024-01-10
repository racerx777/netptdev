<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33); 

function collectionsSettleStatusUpdate($caid, $status, $date, $amount) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	$date=dbDate($date);
	$auditfields = getauditfields();
	$audituser = $auditfields['user'];
	$auditdate = $auditfields['date'];
	$auditprog = basename($auditfields['prog']);
//	$updatequery = "
//		UPDATE collection_accounts
//		SET casettlestatus='$status', casettleamount='$amount', casettledate='$date', casettleuser='$audituser', //upddate='$auditdate', upduser='$audituser', updprog='$auditprog'
//		WHERE caid='$caid'
//	";
	$updatequery = "
		UPDATE collection_accounts
		SET casettlestatus='$status', casettleamount='$amount', casettledate='$date', casettleuser='$audituser'
		WHERE caid='$caid'
	";
//dump("updatequery",$updatequery);
//exit();
	if($updateresult = mysqli_query($dbhandle,$updatequery))
		notify("000", "collectionsSettleStatusFunctions: $caid updated using status='$status', amount='$amount',  date='$date', user='$audituser', ");
	else 
		error("999", "collectionsSettleStatusFunctions: Error.<br>QUERY:$updatequery<br>".mysqli_error($dbhandle));		
displaysitemessages();
}
?>