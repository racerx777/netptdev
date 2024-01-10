<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function getProviderRecord($cnum) {
	$dbhandle = dbconnect();

	$query  = "
		SELECT * 
		FROM master_clinics 
		WHERE cmcnum='$cnum'
		";
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
		if($numRows==1) 
			return(mysqli_fetch_assoc($result));
		else
			error("002","NUMROWS error.<br>$query,<br>".mysqli_error($dbhandle));
	}
	else 
		error("001","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
}

function getCaseRecord($crid) {
	$dbhandle = dbconnect();

	$query  = "
		SELECT * 
		FROM cases 
		WHERE crid='$crid'
		";
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
		if($numRows==1) 
			return(mysqli_fetch_assoc($result));
		else
			error("002","NUMROWS error.<br>$query,<br>".mysqli_error($dbhandle));
	}
	else 
		error("001","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
}

function getPatientRecord($paid) {
	$dbhandle = dbconnect();

	$query  = "
		SELECT * 
		FROM patients 
		WHERE paid='$paid'
		";
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
		if($numRows==1) 
			return(mysqli_fetch_assoc($result));
		else
			error("012","NUMROWS error.<br>$query,<br>".mysqli_error($dbhandle));
	}
	else 
		error("011","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
}

function getDoctorRecord($dmid) {
	$dbhandle = dbconnect();

	$query  = "
		SELECT * 
		FROM doctors
		WHERE dmid='$dmid'
		";
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
		if($numRows==1) 
			return(mysqli_fetch_assoc($result));
		else
			error("022","NUMROWS error.<br>$query,<br>".mysqli_error($dbhandle));
	}
	else 
		error("021","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
}

function getDoctorLocationRecord($dlid) {
	$dbhandle = dbconnect();

	$query  = "
		SELECT * 
		FROM doctor_locations
		WHERE dlid='$dlid'
		";
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
		if($numRows==1) 
			return(mysqli_fetch_assoc($result));
		else
			return(false);
//			error("032","NUMROWS error.<br>$query,<br>".mysqli_error($dbhandle));
	}
	else 
		error("031","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
}

function getDoctorLocationStaffRecord($dlsid) {
	$dbhandle = dbconnect();

	$query  = "
		SELECT * 
		FROM doctor_locations_contacts 
		WHERE dlsid='$dlsid'
	";
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
		if($numRows==1) 
			return(mysqli_fetch_assoc($result));
		else
			return(false);
	}
	else 
		error("031","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
}

function getSchedulingHistory($crid) {
	$dbhandle = dbconnect();

	$history=false;
	$query  = "
		SELECT DATE_FORMAT(crtdate,'%m/%d/%y %h:%i %p') as date, crtuser as user, cshdata as text  
		FROM case_scheduling_history 
		WHERE cshcrid='$crid' ORDER BY crtdate
	";
	if($result = mysqli_query($dbhandle,$query)) {
		$history=array();
		while($row=mysqli_fetch_assoc($result)) 
			$history[]=$row;
	}
	else 
		error("031","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
	return($history);
}

function getAuthorizationHistory($crid) {
	$dbhandle = dbconnect();

	$history=false;
	$query  = "
		SELECT DATE_FORMAT(cphdate,'%m/%d/%y %h:%i %p') as date, cphuser as user, cphhistory as text 
		FROM case_prescriptions 
		LEFT JOIN case_prescriptions_history
		ON cpid=cphcpid
		WHERE cpcrid='$crid'
		ORDER BY cphdate
	";
	if($result = mysqli_query($dbhandle,$query)) {
		$history=array();
		while($row=mysqli_fetch_assoc($result)) 
			$history[]=$row;
	}
	else 
		error("031","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
	return($history);
}

function getTreatmentBillingHistory($bnum, $cnum, $pnum) {
	$dbhandle = dbconnect();

	$history=false;
	$query  = "
		SELECT DATE_FORMAT(tbdthdate,'%m/%d/%y') as date, tbdcode as user, tbddesc as text 
		FROM  treatment_billing_detail 
		WHERE tbdbumcode='$bnum' and tbdthcnum='$cnum' and tbdthpnum='$pnum'
		ORDER BY tbdthdate, tbdcode, tbddesc
	";
	if($result = mysqli_query($dbhandle,$query)) {
		$history=array();
		while($row=mysqli_fetch_assoc($result)) 
			$history[]=$row;
	}
	else 
		error("031","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
	return($history);
}

function getCollectionsQueue($bnum, $pnum) {
	$dbhandle = dbconnect();

	$queue=false;
	$query  = "
		SELECT DATE_FORMAT(cq.upddate,'%m/%d/%y %h:%i %p') as date, cqgroup as user, '' as text 
		FROM  collection_accounts ca
		LEFT JOIN collection_queue cq ON caid=cqcaid
		WHERE cabnum='$bnum' and capnum='$pnum'
	";
	if($result = mysqli_query($dbhandle,$query)) {
		$queue=array();
		while($row=mysqli_fetch_assoc($result)) 
			$queue[]=$row;
	}
	else 
		error("031","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
	return($queue);
}

function getCollectionsHistory($bnum, $pnum) {
	$dbhandle = dbconnect();

	$history=false;
	$query  = "
		SELECT DATE_FORMAT(n.crtdate,'%m/%d/%y %h:%i %p') as date, n.crtuser as user, nonote as text 
		FROM  collection_accounts ca
		LEFT JOIN notes n ON caid=noappid
		WHERE noapp='collections' and cabnum='$bnum' and capnum='$pnum'
		ORDER BY n.crtdate, n.crtuser, nonote
	";
	if($result = mysqli_query($dbhandle,$query)) {
		$history=array();
		while($row=mysqli_fetch_assoc($result)) 
			$history[]=$row;
	}
	else 
		error("031","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
	return($history);
}

function historyHtml($title, $rows, $titles=NULL){
	if(is_null($titles))
		$titles=array("Date","User","History");
	$data=array();
	$data[]='<table width="700px" cellpadding="5" cellspacing="0">';
	$data[]='<tr><th colspan="3">'.$title.'</tr>';

	if(is_array($rows) && count($rows)>0) {
		$data[]='<tr><td align="left"><u>'.$titles[0].'</u></td><td align="left"><u>'.$titles[1].'</u></td><td align="left"><u>'.$titles[2].'</u></td></tr>';
		foreach($rows as $index=>$row) {
			$data[]='<tr><td align="left" nowrap="nowrap">'.$row['date'].'</td><td align="left" nowrap="nowrap">'.$row['user'].'</td><td align="left">'.$row['text'].'</td></tr>';
		}
	}
	else
		$data[]='<td align="left" colspan="3">No History Found '.$rows.'</td>';

	$data[]='</table>';
	$html=implode("\n",$data);
	return($html);
}

function getSchedulingHistoryOld($crid) {
	$dbhandle = dbconnect();

$callhistory="";
$callhistoryquery = "
		SELECT * 
		FROM case_scheduling_history 
		WHERE cshcrid='$crid'
		";
if($callhistoryresult = mysqli_query($dbhandle,$callhistoryquery)) {
	while($callhistoryrow = mysqli_fetch_assoc($callhistoryresult)) {
		$callhistory .= displayDate($callhistoryrow['crtdate']) . " " . displayTime($callhistoryrow['crtdate']) . "-" .$callhistoryrow['crtuser'] . " " . $callhistoryrow['cshdata'] . "<br>";
	}
	$_POST['callhistory']=$callhistory;
}
else
	error("801", mysqli_error($dbhandle));
}
?>