<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
errorclear();
if(empty($crid)) 
	error('000', "Error crid : $crid");
else {
	// Validate form fields
//	require_once('caseValidationSendToPtos.php');
	if(errorcount() == 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		

function getcasepatient($paid) {
// Get the latest Patient Information
	if(!empty($paid)) {
		$patientquery = "
			SELECT palname, pafname, passn, paaddress1, paaddress2, pacity, pastate, pazip, paphone1, paphone2, paemail, padob, pasex 
			FROM patients
			WHERE paid='$paid'
		";
		if($patientresult=mysqli_query($dbhandle,$patientquery)) {
			if($patientrow=mysqli_fetch_assoc($patientresult)) {
				return($patientrow);
			}
		}
	}
	return(array());
}

function getcaseinsurancecompany($icid) {
// Get the latest Patient Information
	if(!empty($icid)) {
		$insurancequery = "
			SELECT icname 
			FROM insurance_companies
			WHERE icid='$icid'
		";
		if($insuranceresult=mysqli_query($dbhandle,$insurancequery)) {
			if($insurancerow=mysqli_fetch_assoc($insuranceresult)) {
				return($insurancerow);
			}
		}
	}
	return(array());
}

function getcaseinsurancelocation($iclid) {
// Get the latest Patient Information
	if(!empty($iclid)) {
		$insurancelocationquery = "
			SELECT iclid, iclicode , icladdress1, icladdress2, iclcity, iclstate, iclzip, iclphone, iclfax, iclemail
			FROM insurance_companies_locations
			WHERE iclid='$iclid'
		";
		if($insurancelocationresult=mysqli_query($dbhandle,$insurancelocationquery)) {
			if($insurancelocationrow=mysqli_fetch_assoc($insurancelocationresult)) {
				return($insurancelocationrow);
			}
		}
	}
	return(array());
}

function getcaseinsuranceadjuster($icaid) {
// Get the latest Patient Information
	if(!empty($icaid)) {
		$insuranceadjusterquery = "
			SELECT icalname, icafname, icaaddress1, icaaddress2, icacity, icastate, icazip, icaphone, icafax, icaemail
			FROM insurance_companies_adjusters
			WHERE icaid='$icaid'
		";
		if($insuranceadjusterresult=mysqli_query($dbhandle,$insuranceadjusterquery)) {
			if($insuranceadjusterrow=mysqli_fetch_assoc($insuranceadjusterresult)) {
				return($insuranceadjusterrow);
			}
		}
	}
	return(array());
}

function getcaseprescription($cpcrid, $cpdate, $cpttmcode) {
// Get the latest matching Prescription Information
	if(!empty($cpcrid) && !empty($cpdate) && !empty($cpttmcode)) {
		$rxquery = "
			SELECT cpdx1, cpdx2, cpdx3, cpdx4, cpdmid, cpdlid, cptherap, cpfrequency, cpduration, cptotalvisits, cpauthdate, cpauthfrequency, cpauthduration, cpauthtotalvisits
			FROM case_prescriptions
			WHERE cpcrid='$cpcrid' and cpdate='$cpdate' and cpttmcode='$cpttmcode'
		";
		if($rxresult=mysqli_query($dbhandle,$rxquery)) {
			if($rxrow=mysqli_fetch_assoc($rxresult)) {
				return($rxrow);
			}
		}
	}
	return(array());
}

function getdoctor($dmid) {
// Get the latest matching Prescription Information
	if(!empty($dmid)) {
		$drquery = "
			SELECT dmlname, dmfname, dmupin, dmnpi
			FROM doctors
			WHERE dmid='$dmid'
		";
		if($drresult=mysqli_query($dbhandle,$drquery)) {
			if($drrow=mysqli_fetch_assoc($drresult)) {
				return($drrow);
			}
		}
	}
	return(array());
}

function gettherapist($ttherap) {
// Get the latest matching Prescription Information
	if(!empty($ttherap)) {
		$therapistquery = "
			SELECT ttherap, tname, tnpi
			FROM therapists
			WHERE ttherap='$ttherap'
		";
		if($therapistresult=mysqli_query($dbhandle,$therapistquery)) {
			if($therapistrow=mysqli_fetch_assoc($therapistresult)) {
				return($therapistrow);
			}
		}
	}
	return(array());
}

		//declare the SQL statement that will query the database
		$loopquery = "
		SELECT crid, crdate, crtherapytypecode, crpaid, cricid1, criclid1, cricaid1, cricid2, criclid2, cricaid2, crrefdmid, crrefdlid from cases 
		WHERE crid='$crid'
		";
		if($loopresult = mysqli_query($dbhandle,$loopquery)) {
			if($looprow = mysqli_fetch_assoc($loopresult)) {

	// get patient information
				$crid=$looprow['crid'];
				unset($patient);
				$patient=getcasepatient($looprow['crpaid']); // Return Array of Patient Information
				$row['crlname']=strtoupper($patient['palname']);
				$row['crfname']=strtoupper($patient['pafname']);
				$row['crssn']=dbSsn($patient['passn']);
				$row['craddress1']=strtoupper($patient['paaddress1']);
				$row['craddress2']=strtoupper($patient['paaddress2']);
				$row['crcity']=strtoupper($patient['pacity']);
				$row['crstate']=strtoupper($patient['pastate']);
				$row['crzip']=dbZip($patient['pazip']);
				$row['crphone1']=dbPhone($patient['paphone1']);
				$row['crphone2']=dbPhone($patient['paphone2']);
				$row['crphone3']=dbPhone($patient['pacellphone']);
				$row['cremail']=strtolower($patient['paemail']);
				$row['crdob']=dbDate($patient['padob']);
				$row['crsex']=strtoupper($patient['pasex']);
	// get doctor information
				unset($doctor);
				$doctor=getdoctor($looprow['crpaid']); // Return Array of Patient Information
				$row['crrefdoc'] = $doctor['dmlname'] . ", " . $doctor['dmfname'];
	// get insurance 1 information
				unset($company1);
				unset($location1);
				unset($adjuster1);
				$company1=getcaseinsurancecompany($looprow['cricid1']);
				$row['crinsurance1name']=strtoupper($company1['icname']);
				$location1=getcaseinsurancelocation($looprow['criclid1']);
				if(empty($location1['iclicode'])) 
					$row['crinsurance1code']=$location1['iclid'];
				else 
					$row['crinsurance1code']=$location1['iclicode'];				
				$row['crinsurance1add1']=$location1['icladdress1'];
				$row['crinsurance1add2']=$location1['icladdress2'];
				$row['crinsurance1city']=$location1['iclcity'];
				$row['crinsurance1state']=$location1['iclstate'];
				$row['crinsurance1zip']=$location1['iclzip'];
				$row['crinsurance1phone']=dbPhone($location1['iclphone']);
//				$adjuster1=getcaseinsuranceadjuster($looprow['cricaid1']);
//				$row['crinsurance1adjusterlname']=$adjuster1['icalname'];
//				$row['crinsurance1adjusterfname']=$adjuster1['icafname'];
//				$row['crinsurance1adjusteradd1']=$adjuster1['icaaddress1'];
//				$row['crinsurance1adjusteradd2']=$adjuster1['icaaddress2'];
//				$row['crinsurance1adjustercity']=$adjuster1['icacity'];
//				$row['crinsurance1adjusterstate']=$adjuster1['icastate'];
//				$row['crinsurance1adjusterzip']=$adjuster1['icazip'];
//				$row['crinsurance1adjusterphone']=dbPhone($adjuster1['icaphone']);
	// get insurance 2 information
				unset($company2);
				unset($location2);
				unset($adjuster2);
				$company2=getcaseinsurancecompany($looprow['cricid2']);
				$row['crinsurance2name']=$company2['icname'];
				$location2=getcaseinsurancelocation($looprow['criclid2']);
				if(empty($location2['iclicode'])) 
					$row['crinsurance2code']=$location2['iclid'];
				else 
					$row['crinsurance2code']=$location2['iclicode'];				
				$row['crinsurance2add1']=$location2['icladdress1'];
				$row['crinsurance2add2']=$location2['icladdress2'];
				$row['crinsurance2city']=$location2['iclcity'];
				$row['crinsurance2state']=$location2['iclstate'];
				$row['crinsurance2zip']=$location2['iclzip'];
				$row['crinsurance2phone']=dbPhone($location2['iclphone']);
//				$adjuster2=getcaseinsuranceadjuster($looprow['cricaid2']);
//				$row['crinsurance2adjusterlname']=$adjuster2['icalname'];
//				$row['crinsurance2adjusterfname']=$adjuster2['icafname'];
//				$row['crinsurance2adjusteradd1']=$adjuster2['icaaddress1'];
//				$row['crinsurance2adjusteradd2']=$adjuster2['icaaddress2'];
//				$row['crinsurance2adjustercity']=$adjuster2['icacity'];
//				$row['crinsurance2adjusterstate']=$adjuster2['icastate'];
//				$row['crinsurance2adjusterzip']=$adjuster2['icazip'];
//				$row['crinsurance2adjusterphone']=dbPhone($adjuster2['icaphone']);
	// Get prescription information
				unset($rx);
				$rx=getcaseprescription($looprow['crid'], $looprow['crdate'], $looprow['crtherapytypecode']);
				$row['crfrequency']=$rx['cpfrequency'];
				$row['crduration']=$rx['cpfrequency'];
				$row['crtotalvisits']=$rx['cptotalvisits'];
				$row['crvisitsauthorized']=$rx['cpauthtotalvisits'];
				$row['cricd9code1']=$rx['cpdx1'];
				$row['cricd9code2']=$rx['cpdx2'];
				$row['cricd9code3']=$rx['cpdx3'];
				$row['cricd9code4']=$rx['cpdx4'];
				$icd9codes=icd9CodeOptions();
				$row['cricd9desc1']=$icd9codes[$rx['cpdx1']]['description'];
				$row['cricd9desc2']=$icd9codes[$rx['cpdx2']]['description'];
				$row['cricd9desc3']=$icd9codes[$rx['cpdx3']]['description'];
				$row['cricd9desc4']=$icd9codes[$rx['cpdx4']]['description'];
				unset($dr);
				$dr=getdoctor($rx['cpdmid']);
				if(!empty($dr['dmfname'])) 
					$row['crrefdoc']=$dr['dmlname']. ", " . $dr['dmfname'];
				else 
					$row['crrefdoc']=$dr['dmlname'];
				$row['crrefdocupin']=$dr['dmupin'];
				$row['crrefdocnpi']=$dr['dmnpi'];
	
				$therapist=gettherapist($rx['cptherap']);
				$row['crtherapcode']=$therapist['ttherap'];
				$row['crtherapname']=$therapist['tname'];
				$row['crtherapnpi']=$therapist['tnpi'];
	
	// validate case fields one last time
	
				foreach($row as $field=>$value) {
					$cleanvalue=mysqli_real_escape_string($dbhandle,$value);
					$set[]="$field='$cleanvalue'";
				}
				if(count($set) > 0) {
					$setvalues = "SET " . implode(", ", $set);
	// Update Case Record
					$updatequery="
						UPDATE cases
						$setvalues, crptosstatus='RQS', crptosupdated=NOW()
						WHERE crid='$crid'
					";
//	dump("UPDATE CASE:", $updatequery);
					if($updateresult = mysqli_query($dbhandle,$updatequery)) 
						notify("000","Case $crid export status updated to RQS.");
					else
						error('001', "UPDATE Error Record $crid<br>$updatequery<br> " . mysqli_error($dbhandle));
				} // set values
				else
					error('001', "SET Error Record $crid.");
			} // fetch
			else
				error('001', "FETCH Error Record $crid:$loopquery<br>" . mysqli_error($dbhandle));
		} // error selecting
		else
			error('001', "SELECT Error Record $crid:$loopquery<br>" . mysqli_error($dbhandle));
		foreach($_POST as $key=>$val) 
			unset($_POST[$key]);
		//close the connection
		mysql_close($dbhandle);
	}
}
?>