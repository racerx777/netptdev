<?php
function rxDaily() {
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$dailytime = date("Y-m-d H:i:s", time());
$dailyuser = getuser();
// UPDATE cpauthstatuscode to LOK 
$updatequery = "
	update case_prescriptions
	JOIN cases
	ON crid=cpcrid
	set cpauthstatuscode='LOK'
	where 
		criclid1 IS NOT NULL and
		crcasestatuscode in ('PEN', 'SCH', 'ACT') and 
		cpauthstatuscode = 'NEW' and
		cprfastatuscode = 'SNT' and
		cprfaprinteddate IS NOT NULL and
		(
			( cpdocstatuscode ='' and NOW() > DATE_ADD(cprfapossentdate, INTERVAL 7 DAY) ) or 
			( NOW() > DATE_ADD(cprfapossentdate, INTERVAL 14 DAY) )
		)
	";
if($updateresult = mysqli_query($dbhandle,$updatequery)) 	
	notify("000","Process No Responses (Daily) selection executed at $dailytime by $dailyuser.");
else 
	error("001","UPDATE QUERY:$query<br>".mysqli_error($dbhandle));

// Process each selected prescription
$dailycount=0;
$selectquery = "
	SELECT cpid, palname, pafname, DATEDIFF(NOW(), cprfapossentdate) as days
	FROM case_prescriptions
	LEFT JOIN cases 
	ON cpcrid=crid
	LEFT JOIN patients
	ON crpaid = paid
	WHERE cpauthstatuscode = 'LOK'
	";
if($selectresult = mysqli_query($dbhandle,$selectquery)) {
	while($row=mysqli_fetch_assoc($selectresult)) {
		$cpid=$row['cpid'];
		$lname=$row['palname'];
		$fname=$row['pafname'];
		$days=$row['days'];
		$updatequery = "
		update case_prescriptions
		set cpauthstatuscode='ASU', cpauthstatususer='$dailyuser', cpauthstatusupdated='$dailytime'
		where cpid='$cpid' and cpauthstatuscode='LOK'
		";
		if($updateresult = mysqli_query($dbhandle,$updatequery)) {
			$dailycount++;
			notify("000","$dailycount. Prescription for $lname, $fname was assumed authorized after waiting $days days.");
			require_once('authprocessingHistory.php');
			rxAddHistory($cpid, "Prescription assumed authorized at $dailytime by $dailyuser.");
		}
		else 
			error("001","rxDaily:UPDATE QUERY error:$updatequery<br>".mysqli_error($dbhandle));
	}
}
else 
	error("001","SELECT QUERY:$query<br>".mysqli_error($dbhandle));
notify("000","$dailycount prescriptions assumed authorized at $dailytime by $dailyuser.");
// if a report is needed then add it here pass dailyuser and dailytime to re-selectrecords 
}
?>