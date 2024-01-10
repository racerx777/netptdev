<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


// get today's date
$today=date("Y-m-d", time());

// remove archive from today's date
$deletequery="DELETE FROM collection_queue_summary_history WHERE cqshdate = '$today'";
echo("Today: $today<br>");
echo("Delete: $deletequery<br>");
if($deleteresult = mysqli_query($dbhandle,$deletequery)) {

// add today to archive
	$insertquery = "
	INSERT INTO collection_queue_summary_history (cqshdate, cqshgroup, cqshacctype, cqshacctypecount, cqshtcurr, cqsht30, cqsht60, cqsht90, cqsht120, cqshtbal, upddate)
	SELECT CURDATE() cqshdate, cqgroup cqshgroup, acctype cqshacctype, count(*) cqshacctypecount, sum(tcurr) cqshtcurr, sum(t30) cqsht30, sum(t60) cqsht60, sum(t90) cqsht90, sum(t120) cqsht120, sum(tbal) cqshtbal, NOW() upddate
	FROM collection_queue cq
		LEFT JOIN collection_accounts ca
		ON cqcaid=caid
		LEFT JOIN PTOS_Patients p
		ON cabnum=bnum and capnum=pnum
	GROUP BY cqgroup, acctype
	";

	if($insertresult=mysqli_query($dbhandle,$insertquery))
		echo "Collection_Queue_Summary_History updated.";
	else
		echo "Collection_Queue_Summary_History NOT updated.";
}
mysqli_close($dbhandle);
?>