<?php
function rxConvert($cpid) {
// Update Auth Status to New, CP Status to ACT, and RFAStatus to SNT User CONVERSION
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(99); 
if(!empty($cpid)) {
	$updatetime = date("Y-m-d H:i:s", time());
	$updateuser = getuser();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$selectquery = "
	SELECT cpcrid, cpauthstatuscode, cpstatuscode, cprfastatuscode 
	FROM case_prescriptions
	where cpid='$cpid'
	";
	if($selectresult = mysqli_query($dbhandle,$selectquery)) {
		if($row=mysqli_fetch_assoc($selectresult)) {
			$cpauthstatuscode=$row['cpauthstatuscode'];
			$cpstatuscode=$row['cpstatuscode'];
			$cprfastatuscode=$row['cprfastatuscode'];
			$updatequery = "
				UPDATE case_prescriptions
				SET cpstatuscode='ACT', cpauthstatuscode='NEW', cprfastatuscode='SNT', cprfastatususer='CONVERSION', cprfastatusupdated='$updatetime', upduser='$updateuser', upddate='$updatetime'
				WHERE cpid='$cpid'
			";
			if($updateresult = mysqli_query($dbhandle,$updatequery)) {
				require_once('authprocessingHistory.php');
				rxAddHistory($cpid, 'Prescription was converted by $updateuser at $updatetime.');
				notify("000","Prescription was converted by $updateuser at $updatetime.");
			}
			else 
				error("004","Prescription WAS NOT converted successfully.<br>$updatequery<br>" . mysqli_error($dbhandle));
		}
		else 
			error("003","rxConvert:FETCH QUERY:$selectquery<br>".mysqli_error($dbhandle));
	}
	else 
		error("001","rxConvert:SELECT QUERY:$selectquery<br>".mysqli_error($dbhandle));
}
else
	error("002","rxConvert:No CPID ($cpid).");
}

function rxUpdateScheduling($cpid) {
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
if(!empty($cpid)) {
	$updatetime = date("Y-m-d H:i:s", time());
	$updateuser = getuser();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$selectquery = "
	SELECT cpcrid, cpauthstatuscode, crcasestatuscode
	FROM case_prescriptions
	JOIN cases
	ON cpcrid=crid
	where cpid='$cpid'
	";
	if($selectresult = mysqli_query($dbhandle,$selectquery)) {
		if($row=mysqli_fetch_assoc($selectresult)) {
			$cpauthstatuscode=$row['cpauthstatuscode'];
			$crcasestatus=$row['crcasestatuscode'];
			if($crcasestatus=='PEA' || $crcasestatus=='PEN' ) { // what happens when there are two prescriptions? 
				require_once($_SERVER['DOCUMENT_ROOT'] .'/modules/attendance/SQLUpdateFunctions.php');
				$crid=$row['cpcrid'];
				casenoshow($crid); // PEN and requeue like a noshow
				require_once($_SERVER['DOCUMENT_ROOT'] .'/modules/scheduling/SQLUpdateFunctions.php');
				$crid=$row['cpcrid'];
				caseschedulinghistoryadd($crid, "Authorization Status updated to $cpauthstatuscode for case $crid Rx $cpid.");
				require_once('authprocessingHistory.php');
				rxAddHistory($cpid, 'Prescription sent to scheduling.');
			}
		}
		else 
			error("003","rxUpdateScheduling:FETCH QUERY:$selectquery<br>".mysqli_error($dbhandle));
	}
	else 
		error("001","rxUpdateScheduling:SELECT QUERY:$selectquery<br>".mysqli_error($dbhandle));
}
else
	error("002","rxUpdateScheduling:No CPID.");
}

function rxAuthorized($cpid) {
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
if(!empty($cpid)) {
	$authtime = date("Y-m-d H:i:s", time());
	$authuser = getuser();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$cpauthdate = dbDate($_POST['cpauthdate']);
	$cpauthperson = strtoupper($_POST['cpauthperson']);
	$cpauthfrequency = $_POST['cpauthfrequency'];
	$cpauthduration = $_POST['cpauthduration'];
	if(empty($_POST['cpauthtotalvisits']))
		$cpauthtotalvisits = $cpauthfrequency*$cpauthduration;
	else
		$cpauthtotalvisits = $_POST['cpauthtotalvisits'];
	$cpauthnote = strtoupper($_POST['cpauthnote']);
	$updatequery = "
	update case_prescriptions
	set cpauthstatuscode='AUT', cpauthstatususer='$authuser', cpauthstatusupdated='$authtime', cpauthdate='$cpauthdate', cpauthperson='$cpauthperson', cpauthfrequency='$cpauthfrequency', cpauthduration='$cpauthduration', cpauthtotalvisits='$cpauthtotalvisits', cpauthnote='$cpauthnote'
	where cpid='$cpid'
	";
	if($updateresult = mysqli_query($dbhandle,$updatequery)) {
		if(rxExport($cpid)) {
			notify("000","Prescription $cpid authorized at $authtime by $authuser.");
			require_once('authprocessingHistory.php');
			rxAddHistory($cpid, "Prescription authorized. (".displayDate($cpauthdate).":$cpauthfrequency:$cpauthduration:$cpauthtotalvisits:$cpauthperson)");
			if(!empty($cpauthnote)) 
				rxAddHistory($cpid, "NOTE:($cpauthnote)");
			rxUpdateScheduling($cpid);
		}
		else
			error("003","rxAuthorized:XML Export Failed.");
	}
	else 
		error("001","rxAuthorized:UPDATE QUERY:$updatequery<br>".mysqli_error($dbhandle));
}
else
	error("002","rxAuthorized:No CPID.");
}

function getRxInfo($cpid) {
	$dbhandle = dbconnect();
	$select="SELECT cpid, cpcrid, cpcnum, cpauthtotalvisits FROM case_prescriptions WHERE cpid='$cpid'";
	if($result=mysqli_query($dbhandle,$select)) {
		if($row=mysqli_fetch_assoc($result)) {
			return($row);
		}
	}
	return(false);
}

function getBuInfo($cnum) {
	$dbhandle = dbconnect();
	$select="SELECT cmbnum FROM master_clinics WHERE cmcnum='$cnum'";
	if($result=mysqli_query($dbhandle,$select)) {
		if($row=mysqli_fetch_assoc($result)) {
			return($row);
		}
	}
	return(false);
}

function putCaseInfo($crid, $authvisits) {
	$dbhandle = dbconnect();
	$updatequery="UPDATE cases SET crvisitsauthorized='$authvisits' WHERE crid='$crid'";
	if($updateresult=mysqli_query($dbhandle,$updatequery)) {
//		$select="SELECT crpnum, crid, crlname, crfname, crvisitsauthorized FROM cases WHERE crid='$crid'";
		$select = "
			SELECT * 
			FROM cases
				LEFT JOIN master_clinics
				ON crcnum = cmcnum
				LEFT JOIN master_provider_groups
				ON cmpgmcode = pgmcode
				LEFT JOIN master_business_units
				ON pgmbumcode = bumcode	
				LEFT JOIN therapists
				ON crtherapcode = ttherap
			WHERE crid ='$crid'
			";
		if($result=mysqli_query($dbhandle,$select)) {
			if($row=mysqli_fetch_assoc($result)) {
				return($row);
			}
		}
		else
			error("998","Error selecting<br>".mysqli_error($dbhandle));
	}
	else
		error("999","Error updating<br>".mysqli_error($dbhandle));
	return(false);
}

function rxExport($cpid) {
	// Get Prescription - cpcnum
	if($rxinfo=getRxInfo($cpid)) {
		$crid=$rxinfo['cpcrid'];
//		dump("rxinfo",$rxinfo);
//		dump("crid",$crid);
	// Determine Business Unit 
		if($buinfo=getBuInfo($rxinfo['cpcnum'])) {
//			dump("buinfo",$buinfo);
			$bnum=$buinfo['cmbnum'];
//			dump("bnum",$bnum);
	// Update Case total auth visits - crvisitsauthorized
			if($row=putCaseInfo($crid, $rxinfo['cpauthtotalvisits'])) {
//				dump("row",$row);
				$pnum=$row['crpnum'];
//				dump("pnum",$pnum);
	// XML output
// Add test to assure that there is a Patient Number, If there is no patient number skip the XML Export Process, let the Patient Entry function pass the information into PTOS.
				if(empty($pnum)) {
					return(true);
				}
				else {
					return(rxExportXML($bnum, $pnum, $crid, $row));
				}
			}
		}
	}
	return(false);
}

function rxExportCleanString($string) {
	$dbhandle = dbconnect();
	$cleanvalue=mysqli_real_escape_string($dbhandle,$string);
// Remove the ampersand
	$cleanvalue=str_replace(" & "," and ", $cleanvalue);
	$cleanvalue=str_replace("& ","and ", $cleanvalue);
	$cleanvalue=str_replace(" &"," and", $cleanvalue);
	$cleanvalue=str_replace("&","and", $cleanvalue);
	return($cleanvalue);
}

function rxExportXML($bnum, $pnum, $crid, $row) {
	$dbhandle = dbconnect();
	if(!empty($row['crpnum'])) {
// Mapped fields are below
		$xml=array();
		$updatetime = date("Y-m-d H:i:s", time());
	
		$xml['Record_ID']=$row['crpnum'];
		$xml['Last_Name']=$row['crlname'];
		$xml['First_Name']=$row['crfname'];
		$xml['Auth_Visits']=$row['crvisitsauthorized'];
	
		// If Authorization provides insurance 2, then it is really the billing insurance.
		if(!empty($row['cricid2'])) {
			$xml['Insurance_Code1']=$row['crinsurance2code'];
			$xml['Insurance_Name1']=$row['crinsurance2name'];
			$xml['Insurance1_Address1']=$row['crinsurance2add1'];
			$xml['Insurance1_Address2']=$row['crinsurance2add2'];
			$xml['Insurance1_City']=$row['crinsurance2city'];
			$xml['Insurance1_State']=$row['crinsurance2state'];
			$xml['Insurance1_ZIP']=$row['crinsurance2zip'];
			$xml['Insurance1_Phone']=displayPhonePTOS($row['crinsurance2phone']);
			if(!empty($row['cricclaimnumber2'])) 
				$xml['Insurance1_Group']="CL# ".$row['cricclaimnumber2'];
			if(!empty($row['crssn'])) {
				$xml['Insurance_Id']=displaySsnAll($row['crssn']);
				$xml['SSN']=displaySsnAll($row['crssn']);
			}
		}
		else {
			$xml['Insurance_Code1']=$row['crinsurance1code'];
			$xml['Insurance_Name1']=$row['crinsurance1name'];
			$xml['Insurance1_Address1']=$row['crinsurance1add1'];
			$xml['Insurance1_Address2']=$row['crinsurance1add2'];
			$xml['Insurance1_City']=$row['crinsurance1city'];
			$xml['Insurance1_State']=$row['crinsurance1state'];
			$xml['Insurance1_ZIP']=$row['crinsurance1zip'];
			$xml['Insurance1_Phone']=displayPhonePTOS($row['crinsurance1phone']);
			if(!empty($row['cricclaimnumber1'])) 
				$xml['Insurance1_Group']="CL# ".$row['cricclaimnumber1'];
			if(!empty($row['crssn'])) {
				$xml['Insurance_Id']=displaySsnAll($row['crssn']);
				$xml['SSN']=displaySsnAll($row['crssn']);
			}
		}
	
		foreach($xml as $field=>$value) 
			$cleanxml["$field"]=rxExportCleanString($value);
		$xmldata="";
		$xmldata =  '<?xml version="1.0"?>';
		$xmldata .= "<PTOSImport>";
		$xmldata .= "<Patient>";
		foreach($cleanxml as $tag=>$val) 
			$xmldata.= "<$tag>$val</$tag>";
		$xmldata .= "</Patient>";
		$xmldata .= "</PTOSImport>";
	//	echo $xmldata;
	//	return(writeFile($bnum, $pnum, $crid, $xmldata)); 
		$insertquery = "
			INSERT INTO ptos_interface (xmlbumcode, xmlcrid, xmlpnum, xmldatatype, xmlstatus, xmlstring, upddate)
			VALUES('$bnum', '$crid', '$pnum', 'I', 'NEW', '$xmldata','$updatetime')
			";
		if($insertresult = mysqli_query($dbhandle,$insertquery)) 
			return(TRUE); 
		else 
			error("999","Interface INSERT error. $insertquery<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
	return(FALSE);
}

function rxDenied($cpid) {
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
if(!empty($cpid)) {
	$deniedtime = date("Y-m-d H:i:s", time());
	$denieduser = getuser();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$cpdenidate = displayDate($_POST['cpdenidate']);
	$cpdeniperson = strtoupper($_POST['cpdeniperson']);
	$cpdenireasoncode = $_POST['cpdenireasoncode'];
	$cpdeninote = strtoupper($_POST['cpdeninote']);
	$updatequery = "
	update case_prescriptions
	set cpauthstatuscode='DEN', cpauthstatususer='$denieduser', cpauthstatusupdated='$deniedtime', cpdenidate='$cpdenidate', cpdeniperson='$cpdeniperson', cpdenireasoncode='$cpdenireasoncode',  cpdeninote='$cpdeninote'
	where cpid='$cpid' and cpauthstatuscode='NEW'
	";
	if($updateresult = mysqli_query($dbhandle,$updatequery)) {
		notify("","Prescription $cpid denied at $deniedtime by $denieduser.");
		require_once('authprocessingHistory.php');
		rxAddHistory($cpid, "Prescription denied. ($cpdenidate:$cpdenireasoncode:$cpdeniperson)");
		if(!empty($cpdeninote)) 
			rxAddHistory($cpid, "NOTE:($cpdeninote)");
		rxUpdateScheduling($cpid);
	}
	else 
		error("001","rxDenied:UPDATE QUERY:$updatequery<br>".mysqli_error($dbhandle));
}
else
	error("002","rxDenied:No CPID.");
}
?>