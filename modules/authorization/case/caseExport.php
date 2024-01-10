<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(21);
errorclear();
$selected=0;
$processed=0;
$errors=0;

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

function getdoctorlocation($dlid) {
// Get the latest matching Prescription Information
	if(!empty($dlid)) {
		$dlquery = "
			SELECT *
			FROM doctor_locations
			WHERE dlid='$dlid'
		";
		if($dlresult=mysqli_query($dbhandle,$dlquery)) {
			if($dlrow=mysqli_fetch_assoc($dlresult)) {
				return($dlrow);
			}
		}
	}
	return(array());
}

function gettherapist($ttherap) {
// Get the latest matching Prescription Information
	$therapistarray=array();
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

function caseExportCleanString($string) {
	$cleanvalue=mysqli_real_escape_string($dbhandle,$string);
// Remove the ampersand
	$cleanvalue=str_replace(" & "," and ", $cleanvalue);
	$cleanvalue=str_replace("& ","and ", $cleanvalue);
	$cleanvalue=str_replace(" &"," and", $cleanvalue);
	$cleanvalue=str_replace("&","and", $cleanvalue);
	return($cleanvalue);
}

function caseExportUpdateRow($crid, $updatemsg, &$row) {
	foreach($row as $field=>$value) {
		$cleanvalue = caseExportCleanString($value);
		$set[]="$field='$cleanvalue'";
		unset($row["$field"]);
	}
	if(count($set) > 0) {
		$setvalues = "SET " . implode(", ", $set);
// Update Case Record
		$updatequery="
			UPDATE cases
			$setvalues
			WHERE crid='$crid'
		";
//dump("UPDATE CASE:", $updatequery);
		if($updateresult = mysqli_query($dbhandle,$updatequery)) {
//			notify("000","Case $crid $updatemsg updated.");
		}
	}
}

//declare the SQL statement that will query the database
$lockquery = "
	UPDATE cases
	SET crptosstatus='XML'
	WHERE crptosstatus = 'RQS'
	";
if($lockresult = mysqli_query($dbhandle,$lockquery)) {

// get fields to lock in values for each case
	$loopquery = "
		SELECT crid, crpaid, crdate, crtherapytypecode, crrefdmid, crrefdlid, cricid1, criclid1, cricaid1, cricid2, criclid2, cricaid2
		FROM cases
		WHERE crptosstatus='XML'
	";
	if($loopresult = mysqli_query($dbhandle,$loopquery)) {
		while($looprow=mysqli_fetch_assoc($loopresult)) {
// get patient information
			$row=array();
			$crid=$looprow['crid'];
			unset($patient);
			$patient=getcasepatient($looprow['crpaid']); // Return Array of Patient Information
			if(count($patient)>0) {
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
				caseExportUpdateRow($crid, "Patient Information", $row);
			}
			else
				notify("000","Patient Information Not Updated. ".$looprow['crpaid']);

// get insurance 1 information
			unset($company1);
			unset($location1);
			unset($adjuster1);
			$company1=getcaseinsurancecompany($looprow['cricid1']);
			if(count($company1)>0) {
				$row['crinsurance1name']=strtoupper($company1['icname']);
				caseExportUpdateRow($crid, "Insurance 1 Name", $row);
			}
			else
				notify("000","Insurance 1 Name Not Updated. ".$looprow['cricid1']);

			$location1=getcaseinsurancelocation($looprow['criclid1']);
			if(count($location1)>0) {
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
				caseExportUpdateRow($crid, "Insurance 1 Location", $row);
			}
			else
				notify("000","Insurance 1 Location Not Updated. ".$looprow['criclid1']);

			$adjuster1=getcaseinsuranceadjuster($looprow['cricaid1']);
			if(count($adjuster1)>0) {
				$row['crinsurance1adjusterlname']=$adjuster1['icalname'];
				$row['crinsurance1adjusterfname']=$adjuster1['icafname'];
				$row['crinsurance1adjusteradd1']=$adjuster1['icaaddress1'];
				$row['crinsurance1adjusteradd2']=$adjuster1['icaaddress2'];
				$row['crinsurance1adjustercity']=$adjuster1['icacity'];
				$row['crinsurance1adjusterstate']=$adjuster1['icastate'];
				$row['crinsurance1adjusterzip']=$adjuster1['icazip'];
				$row['crinsurance1adjusterphone']=dbPhone($adjuster1['icaphone']);
				caseExportUpdateRow($crid, "Insurance 1 Adjuster", $row);
			}
			else
				notify("000","Insurance 1 Adjuster Not Updated. ".$looprow['cricaid1']);

// get insurance 2 information
			unset($company2);
			unset($location2);
			unset($adjuster2);
			$company2=getcaseinsurancecompany($looprow['cricid2']);
			if(count($company2)>0) {
				$row['crinsurance2name']=$company2['icname'];
				caseExportUpdateRow($crid, "Insurance 2 Name", $row);
			}
//			else
//				notify("000","Insurance 2 Name Not Updated. ".$looprow['cricid2']);

			$location2=getcaseinsurancelocation($looprow['criclid2']);
			if(count($location2)>0) {
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
				caseExportUpdateRow($crid, "Insurance 2 Location", $row);
			}
//			else
//				notify("000","Insurance 2 Location Not Updated. ".$looprow['criclid2']);

			$adjuster2=getcaseinsuranceadjuster($looprow['cricaid2']);
			if(count($adjuster2)>0) {
				$row['crinsurance2adjusterlname']=$adjuster2['icalname'];
				$row['crinsurance2adjusterfname']=$adjuster2['icafname'];
				$row['crinsurance2adjusteradd1']=$adjuster2['icaaddress1'];
				$row['crinsurance2adjusteradd2']=$adjuster2['icaaddress2'];
				$row['crinsurance2adjustercity']=$adjuster2['icacity'];
				$row['crinsurance2adjusterstate']=$adjuster2['icastate'];
				$row['crinsurance2adjusterzip']=$adjuster2['icazip'];
				$row['crinsurance2adjusterphone']=dbPhone($adjuster2['icaphone']);
				caseExportUpdateRow($crid, "Insurance 2 Adjuster", $row);
			}
//			else
//				notify("000","Insurance 2 Adjuster Not Updated. ".$looprow['cricaid2']);
// Get prescription information
			unset($rx);
			$rx=getcaseprescription($looprow['crid'], $looprow['crdate'], $looprow['crtherapytypecode']);
			if(count($rx)>0) {
				$row['crfrequency']=$rx['cpfrequency'];
				$row['crduration']=$rx['cpduration'];
				$row['crtotalvisits']=$rx['cptotalvisits'];
				$row['crvisitsauthorized']=$rx['cpauthtotalvisits'];
				$icd9codes=icd9CodeOptions();
				$row['cricd9code1']=$rx['cpdx1'];
				$row['cricd9code2']=$rx['cpdx2'];
				$row['cricd9code3']=$rx['cpdx3'];
				$row['cricd9code4']=$rx['cpdx4'];
				$row['cricd9desc1']=$icd9codes[$rx['cpdx1']]['description'];
				$row['cricd9desc2']=$icd9codes[$rx['cpdx2']]['description'];
				$row['cricd9desc3']=$icd9codes[$rx['cpdx3']]['description'];
				$row['cricd9desc4']=$icd9codes[$rx['cpdx4']]['description'];
				caseExportUpdateRow($crid, "Prescription Information", $row);
			}
			else
				notify("000","Prescription Information Not Updated. ".$looprow['crid'].", ".$looprow['crdate'].", ".$looprow['crtherapytypecode']);

// Update Doctor
			unset($dr);
			if(!empty($rx['cpdmid']))
				$dr=getdoctor($rx['cpdmid']);
			else
				if(!empty($loopresult['crrefdmid']))
					$dr=getdoctor($loopresult['crrefdmid']);
			if(count($dr)>0) {
				if(!empty($dr['dmfname']))
					$row['crrefdoc']=$dr['dmlname']. ", " . $dr['dmfname'];
				else
					$row['crrefdoc']=$dr['dmlname'];
				$row['crrefdocupin']=$dr['dmupin'];
				$row['crrefdocnpi']=$dr['dmnpi'];
				caseExportUpdateRow($crid, "Doctor Information", $row);
			}
			else
				notify("000","Doctor Information Not Updated. ".$rx['cpdmid'].", ".$looprow['crrefdmid']);

// Update Doctor Location
			if(!empty($rx['cpdlid']))
				$location=getdoctorlocation($rx['cpdlid']);
			else
				if(!empty($loopresult['crrefdlid']))
					$location=getdoctorlocation($loopresult['crrefdlid']);
			if(count($location)>0) {
				$row['crrefdoccity']=$location['dlcity'];
				caseExportUpdateRow($crid, "Doctor Location Information", $row);
			}
			else
				notify("000","Doctor Location Information Not Updated. ".$rx['cplid'].", ".$looprow['crrefdlid']);
// Update Therapist
			if(!empty($rx['cptherap']))
				$therapist=gettherapist($rx['cptherap']);
			else
				if(!empty($loopresult['crtherapcode']))
					$therapist=gettherapist($loopresult['crtherapcode']);
			if(count($therapist)>0) {
				$row['crtherapcode']=$therapist['ttherap'];
				$row['crtherapname']=$therapist['tname'];
				$row['crtherapnpi']=$therapist['tnpi'];
				caseExportUpdateRow($crid, "Therapist Information", $row);
			}
			else
				notify("000","Therapist Information Not Updated. ".$rx['cptherap'].", ".$looprow['crtherapcode']);
		}
	}
	$selectquery = "
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
		WHERE crptosstatus ='XML'
	";
	if($selectresult = mysqli_query($dbhandle,$selectquery)) {
		$selected = mysqli_num_rows($selectresult);
		notify("000","$selected rows selected for processing.");
		while($row = mysqli_fetch_assoc($selectresult)) {
			$crid=$row['crid'];
	// ROW VALIDATION
		//	require_once('caseValidationSendToPtos.php');
			if(errorcount() == 0) {
//				if(caseUpdatePTOSNumber($row)==TRUE) {
					if(caseExportXML($row)==TRUE) {
						$expupdatequery = "
							UPDATE cases
							SET crptosstatus='EXP', crptosupdated=NOW()
							WHERE crid='$crid' and crptosstatus = 'XML'
						";
						if($expupdateresult = mysqli_query($dbhandle,$expupdatequery))
							$processed++;
						else {
							$errors++;
							error('001', "EXP Error updating Case record case=$crid:$expupdatequery<br>" . mysqli_error($dbhandle));
						}
					}
					else {
						$errors++;
						$errupdatequery = "
							UPDATE cases
							SET crptosstatus='RQS'
							WHERE crid='$crid' and crptosstatus = 'XML'
						";
						if($errupdateresult = mysqli_query($dbhandle,$errupdatequery))
							error('012', "XML Error creating export record. Case $crid was reset.<br>" . mysqli_error($dbhandle));
						else
							error('022', "XML Error creating export record. Case $crid could not be reset.<br>" . mysqli_error($dbhandle));
					}
//				}
//				else
//					error('032', "XML Error creating PTOS Number. Case $crid could not derrive a PTOS number.<br>" . mysqli_error($dbhandle));
			}
			else {
				$errors++;
				error('003', "ROW validation error. case=$crid.");
			}
		} // while
	}
	else
		error('004', "SELECT error. $selectquery<br>".mysqli_error($dbhandle));
}
else
	error('005', "LOCK UPDATE error. $lockquery<br>".mysqli_error($dbhandle));

foreach($_POST as $key=>$val)
	unset($_POST[$key]);
//close the connection
mysqli_close($dbhandle);
notify("000", "Export Summary: selected: $selected, processed:$processed, errors:$errors.");

function next09AZ($thischar) {
	$ordval=ord($thischar);
	$nextval=$ordval+1;
	$nextchr=chr($nextval);
	if(($nextchr>='0' and $nextchr<='9') or ($nextchr>='A' and $nextchr<='Z'))
		return($nextchr);
	else
		return(FALSE);
}

function ptosValidDigits() {
//	$validdigitsstring = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$validdigitsstring = "0123456789ABCDEFGHJKLMNPQRSTUVWXY";
	return(preg_split('//', $validdigitsstring, -1));
}

function ptosNumberVerifyDigits($pnum) {
	$validdigitsarray=ptosValidDigits();
	$pnumarray = preg_split('//', $pnum, -1);

	foreach($pnumarray as $position=>$pnumdigit) {
		if(!in_array($pnumdigit, $validdigitsarray))
			return(FALSE);
	}
	return(TRUE);
}

function ptosIncrementPatientNumber($pnum) {
// expects full ptos number and takes the last three, returns full 6 or FALSE
	if(strlen($pnum) == 6) {
		if(ptosNumberVerifyDigits($pnum)) {
			$validdigitsarray=ptosValidDigits();
			$pnumdigit3=substr($pnum,-1,1);
			$pnumdigit2=substr($pnum,-2,1);
			$pnumdigit1=substr($pnum,-3,1);
			$position3=array_search($pnumdigit3, $validdigitsarray);
			$position2=array_search($pnumdigit2, $validdigitsarray);
			$position1=array_search($pnumdigit1, $validdigitsarray);
			$newdigit3=$validdigitsarray[$position3+1];
			$newdigit2=$validdigitsarray[$position2];
			$newdigit1=$validdigitsarray[$position1];

			if($position3+1 >= count($validdigitsarray)) {
				$newdigit3=$validdigitsarray[0]; // reset to first valid number
				$newdigit2=$validdigitsarray[$position2+1]; // increment 2
				if($position2+1 >= count($validdigitsarray)) {
					$newdigit2=$validdigitsarray[0];
					$newdigit1=$validdigitsarray[$position1+1];
					if($position1+1 >= count($validdigitsarray)) {
						return(FALSE);
					}
				}
			}
			$newpnum = substr($pnum,0,3) . $newdigit1 . $newdigit2 . $newdigit3;
			return($newpnu);
		}
	}
	return(FALSE);
}

function caseUpdatePTOSNumber($row) {
	$cnum=$row['crcnum'];
	$fvisit=$row['crapptdate'];
	$casetype=$row['crcasetypecode'];
	$therapytype=$row['crtherapytypecode'];
	$year4 = date('Y', $fvisit);
	$year2 = date('y', $fvisit);
	if($year4 < '2010')  // before this numbering system was implemented
		error("111","Cannot support autonumbering prior to 2010");
	else {
		if($bumcode=='NET') {
			unset($yeardigit);
			if($year4 == '2010')
				$yeardigit='A';
			if($year4 == '2011')
				$yeardigit='B';
			if($year4 == '2012')
				$yeardigit='C';
			if($year4 == '2013')
				$yeardigit='D';
			if($year4 == '2014')
				$yeardigit='E';
			if($year4 == '2015')
				$yeardigit='F';
			if($year4 == '2016')
				$yeardigit='G';
			if($year4 == '2017')
				$yeardigit='H';
			if($year4 == '2018')
				$yeardigit='I';
			if($year4 == '2019')
				$yeardigit='J';
			if($year4 == '2020')
				$yeardigit='K';

		}
		else {
			if($bumcode=='WS') {
				$yeardigit=$year2;
				$ocfdigit=substr(intval($cnum),-1,1);
			}
			else {
				error("333","Invalid Business Unit");
			}
		}
		if(!isset($yeardigit))
			error("222","Cannot assign year digit correctly. Beyond life of subroutine.");
		else {
//  Retrieve the last number like this one
			$selectlastusedquery = "
				SELECT MAX(pnum) as lastnumberused FROM patients_ptos WHERE cnum='$cnum' AND pnum LIKE'$yeardigit%'
			";
			if($selectlastusedresult=mysqli_query($dbhandle,$selectlastusedquery)) {
				if($selectlastusedrow=mysqli_fetch_assoc($selectlastusedresult)) {
					$officecode=substr($selectlastusedrow['lastnumberused'],1,2);
					$patientnumber=substr($selectlastusedrow['lastnumberused'],3,3);
				}
			}
		}
	}
	return(FALSE);
}

function caseExportXML($row) {
// Write out an xml file using the ptos number as file name
	$bumcode=trim($row['bumcode']);
	$crid=trim($row['crid']);
	$pnum=trim($row['crpnum']);
	$xml=array();
	$xml['Record_ID']=$row['crpnum'];
	if(strlen($row['crssn'])==4)
		$xml['SSN']="***-**-".$row['crssn'];
	else
		$xml['SSN']=displaySsnAll($row['crssn']);
	$xml['Last_Name']=$row['crlname'];
	$xml['First_Name']=$row['crfname'];
	$xml['Address1']=$row['craddress1'];
	$xml['Address2']=$row['craddress2'];
	$xml['City']=$row['crcity'];
	$xml['State']=$row['crstate'];
	$xml['Zip']=displayZip($row['crzip']);
	$xml['Phone1']=displayPhonePTOS($row['crphone1']);
	$xml['Phone2']=displayPhonePTOS($row['crphone2']);
	$xml['Phone3']=displayPhonePTOS($row['crphone3']);
	$xml['Email']=$row['cremail'];
	$xml['Birthdate']=displayDate($row['crdob']);
	$xml['Sex']=$row['crsex'];
	$xml['Occupation']=$row['croccup'];
	$xml['Employer']=$row['crempname'];
	$xml['Responsible_Party']=$row['criclid1'];
//	$xml['Notes']=$row['crnote'];
// Create the notes field values
	$notes=array();
	if(!empty($row['crpostsurgical'])) {
		if(!empty($row['crsurgerydate'])) {
			$notes[]='SX DATE:' . displayDate($row['crsurgerydate']);
			unset($bodypart);
			unset($bodyparttype);
			if(!empty($row['crdxbodypart'])) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/common/injury.options.php');
				$bodyparts=array();
				foreach(getInjuryBodypartTypeOptions($row['crdxbodypart'], 1) as $value=>$itemarray)
					$bodyparts[$itemarray['value']]=$itemarray['title'];
				$bodypart = $bodyparts[$row['crdxbodypart']];

				if(!empty($row['crdxbodydescriptor'])) {
					$bodypartstypes=array();
					foreach(getInjuryDescriptorTypeOptions(NULL, 1) as $value=>$itemarray)
						$bodypartstypes[$itemarray['value']]=$itemarray['title'];
					$bodyparttype = $bodypartstypes[$row['crdxbodydescriptor']];
				}
				$notes[]="$bodyparttype $bodypart";
			}
		}
	}
	if(count($notes)>0)
		$xml['Notes']=implode(" ", $notes);

	$xml['Copay']='0';
	$xml['Injury_Date']=displayDate($row['crinjurydate']);
// If Readmit do not update
	if($row['crreadmit']==0) {
		$xml['AccountType']=$row['crcasetypecode'];
		$xml['Start_Date']=displayDate($row['crapptdate']);
	}
	$xml['End_Date']='';

	$xml['ICD9_Code1']=$row['cricd9code1'];
	$xml['Description1']=$row['cricd9desc1'];
	$xml['ICD9_Code2']=$row['cricd9code2'];
	$xml['Description2']=$row['cricd9desc2'];
	$xml['ICD9_Code3']=$row['cricd9code3'];
	$xml['Description3']=$row['cricd9desc3'];
//	$xml['ICD9_Code4']=$row['cricd9code4'];
//	$xml['Description4']=$row['cricd9desc4'];
	if(!empty($row['cricclaimnumber1'])) {
		if(!empty($row['cricclaimnumber2']))
			$xml['Description4']="CLAIM # " . $row['cricclaimnumber1'] . " and ". $row['cricclaimnumber2'];
		else
			$xml['Description4']="CLAIM # " . $row['cricclaimnumber1'];
	}

	$xml['RefDoc_UPIN']=$row['crrefdocupin'];
	$xml['RefDoc_Name']=$row['crrefdoc'];
	$xml['RefDoc_NPI']=$row['crrefdocnpi'];

	$xml['PCP_UPIN']='';
	$xml['PCP_Name']='';
	$xml['PCP_NPI']='';

	$xml['therapist_code']=$row['crtherapcode'];
	$xml['therapist_name']=$row['crtherapname'];
	$xml['therapist_npi']=$row['crtherapnpi'];

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
//	$xml['Insurance1_Group']=$row['crinsurance2group'];
	if(!empty($row['cricclaimnumber2']))
		$xml['Insurance1_Group']="CL# ".$row['cricclaimnumber2'];
	if(!empty($row['crssn']))
		$xml['Insurance_Id']=displaySsnAll($row['crssn']);
//	$xml['Insurance1_Notes']=$row['crinsurance2note'];
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
//	$xml['Insurance1_Group']=$row['crinsurance1group'];
	if(!empty($row['cricclaimnumber1']))
		$xml['Insurance1_Group']="CL# ".$row['cricclaimnumber1'];
	if(!empty($row['crssn']))
		$xml['Insurance_Id']=displaySsnAll($row['crssn']);
//	$xml['Insurance1_Notes']=$row['crinsurance1note'];
//	$xml['Insurance1_Notes']="TEST1";
//	$xml['Insurance_Code2']=$row['crinsurance2code'];
//	$xml['Insurance_Name2']=$row['crinsurance2name'];
//	$xml['Insurance2_Address1']=$row['crinsurance2add1'];
//	$xml['Insurance2_Address2']=$row['crinsurance2add2'];
//	$xml['Insurance2_City']=$row['crinsurance2city'];
//	$xml['Insurance2_State']=$row['crinsurance2state'];
//	$xml['Insurance2_ZIP']=$row['crinsurance2zip'];
//	$xml['Insurance2_Phone']=displayPhonePTOS($row['crinsurance2phone']);
//	$xml['Insurance2_Group']=$row['crinsurance2group'];
//	if(!empty($row['cricclaimnumber2']))
//		$xml['Insurance2_Group']="CL# ".$row['cricclaimnumber2'];
//	$xml['Insurance2_Notes']=$row['crinsurance2note'];
//	$xml['Insurance1_Notes']="TEST1";
}
	foreach($xml as $field=>$value) {
		$cleanxml["$field"]=caseExportCleanString($value);
	}
	$xmldata="";
	$xmldata =  "<?xml version=\"1.0\"?>\n";
	$xmldata .= "<PTOSImport>\n";
	$xmldata .= "<Patient>\n";
	foreach($cleanxml as $tag=>$val)
		$xmldata.= "<$tag>$val</$tag>\n";
	$xmldata .= "</Patient>\n";
	$xmldata .= "</PTOSImport>\n";
	$xmldata = mysqli_real_escape_string($dbhandle,$xmldata);
	$insertquery = "
		INSERT INTO ptos_interface (xmlbumcode, xmlcrid, xmlpnum, xmldatatype, xmlstatus, xmlstring)
		VALUES('$bumcode', '$crid', '$pnum', 'P', 'NEW', '$xmldata')
		";
// dump("INSERT XML:", $insertquery);
	if($insertresult = mysqli_query($dbhandle,$insertquery)) {
//		$filename = $bumcode . '_patient_' . $pnum . '_' . date("YmdHis") . '.xml';
//		$path = $_SERVER['DOCUMENT_ROOT'] . "/xml/ptos_$bumcode";
//		$fullpath = "$path/$filename";
//		if(is_writable($path)) {
//			if(!$handle = fopen($fullpath, 'w'))
//				error("006","Cannot open ($filename) for <br>$xmldata");
//			else {
//				if(fwrite($handle, $xmldata) === FALSE)
//					error("005","Cannot write to ($filename) for <br>$xmldata");
//				else {
//					notify("000","Success, wrote ($xmldata) to ($filename)");
//					fclose($handle);
//					notify('000',$cleanxml["Last_Name"].' '.$cleanxml["First_Name"].' ('.$cleanxml["Record_ID"].') inserted into the ptos_interface table.');
					return(TRUE);
//				}
//			}
//		}
//		else
//			error("007","The path $path or file $filename is not writable");
	}
	else {
		error("999","Interface INSERT error. $insertquery<br>".mysqli_error($dbhandle));
	}
	return(FALSE);
}
?>