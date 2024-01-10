<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
if(dbDate($_POST['crdate']) < '2000-01-01 00:00:00')  {
	error("999", "Invalid referral date. " . dbDate($_POST['crdate']));
}
if(empty($_POST['crpaid'])) {
	error("999", "Case validation failed. No Patient Id.");
}
else {
	if(!isset($_POST['crpostsurgical']))
		unset($_POST['crsurgerydate']);
	else {
		$strtotime=strtotime($_POST['crsurgerydate']);
		if($strtotime==-1) {
			error("001","Case validation failed 1. Invalid surgery date.");
		}
		else {
			$date1=displayDate($_POST['crsurgerydate']);
			$date2=displayDate(date('Y-m-d H:i:s', $strtotime));
			if($date1!=$date2) {
//dump("_POST['crsurgerydate']",$_POST['crsurgerydate']);
//dump("strtotime",$strtotime);
				error("002","Case validation failed 2. Invalid surgery date.1:$date1 2:$date2");
			}
		}	
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

// Check duplicate PTOS Number
	if(!empty($_POST['crpnum']) && empty($_POST['crreadmit']) && empty($_POST['crrelocate'])) {
		$pnum=$_POST['crpnum'];
		$crid=$_SESSION['id'];
//		if(userlevel()=='99')
//			dumppost();
		$pnumquery="
			SELECT crid, crpnum, crlname, crfname from cases where crpnum='$pnum' and crid<>'$crid'
			";
		if($pnumresult = mysqli_query($dbhandle,$pnumquery)) {
			if($pnumrow = mysqli_fetch_assoc($pnumresult)) {
				$duplname=$pnumrow['crlname'];
				$dupfname=$pnumrow['crfname'];
				$dupcrid=$pnumrow['crid'];
				error("999","Patient Number $pnum is already in NetPT $duplname, $dupfname $dupcrid"); 
			}
		}
	}

// Update to latest Patient Information
	$query = "
		SELECT palname as crlname, pamname as crmname, pafname as crfname, paaddress1 as cradd1, pacity as crcity, pastate as crstate, pazip as crzip, paphone1 as crphone1, paphone2 as crphone2, pacellphone as crphone3, padob as crdob, pasex as crsex, passn as crssn 
		FROM patients 
		WHERE paid='" . $_POST['crpaid'] . "'";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_assoc($result)) {
			foreach($row as $field=>$value) {
				$_POST["$field"]=$value;
			}
		}		
		else
			error("998", mysqli_error($dbhandle));
	}
	else
		error("999", mysqli_error($dbhandle));
}
?>