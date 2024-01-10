<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
function patientGetCaseRecord($crid) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
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

function patientGetPatientRecord($paid) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
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

function patientGetDoctorRecord($dmid) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
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

function patientGetDoctorLocationRecord($dlid) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
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
			error("032","NUMROWS error.<br>$query,<br>".mysqli_error($dbhandle));
	}
	else 
		error("031","SELECT error.<br>$query,<br>".mysqli_error($dbhandle));
}

function patientPrintIntakeForms($crid, $casetype='WC', $lang='English') {
// Retrieve Information from database.
	if($caserow=patientGetCaseRecord($crid)) {
		$paid=$caserow['crpaid'];
		if($patientrow=patientGetPatientRecord($paid)) {
			$dmid=$caserow['crrefdmid'];
			$dlid=$caserow['crrefdlid'];
			if($doctorrow=patientGetDoctorRecord($dmid)) {
				if($doctorlocationrow=patientGetDoctorLocationRecord($dlid)) {
	// Required Fields: 
		// 		Today's Date
					$auditfields=getauditfields();
					$document['date'] = displayDate($auditfields['date']);
		// 		Injury Date
					$case['injurydate'] = displayDate($caserow['crinjurydate']);
		// 		Post Surgical Flag
					$case['postsurgical'] = NULL;
					$case['surgerydate'] = NULL;				
					if(!empty($caserow['crpostsurgical'])) {
		// 		Surgery Date
						$case['postsurgical'] = TRUE;
						if(!empty($caserow['crsurgerydate']))
							$case['casesurgerydate'] = displayDate($caserow['crsurgerydate']);
					}
		// 		Case Type (Verbiage)
					$casetypeoptions = caseTypeOptions();
					if(array_key_exists($caserow['crcasetypecode'], $casetypeoptions)) {
//						dump('caserow[crcasetypecode]',$caserow['crcasetypecode']);
//						dump('CaseTypeOptions', $casetypeoptions);
						$case['casetype'] = $casetypeoptions[$caserow['crcasetypecode']]['title'];
					}
					else
						$case['casetype'] = "Code:" . $caserow['crcasetypecode'];
		//		Patient 
		//			First Middle and Last Name
					$patient['name'] = strtoupper($patientrow['pafname']) . " " . strtoupper($patientrow['pamname']) . " " . strtoupper($patientrow['palname']);
		//			Address
					$patient['address'] = strtoupper($patientrow['paaddress1']);
					if(empty($patientrow['paaddress2']))
						$patient['address'] .= " " . strtoupper($patientrow['paaddress2']);
		//			City, State, Zip
					$patient['city'] = strtoupper($patientrow['pacity']);
					$patient['state'] = strtoupper($patientrow['pastate']);
					$patient['zip'] = displayZip($patientrow['pazip']);
		//			Home Phone
					$patient['phonehome'] = displayPhone($patientrow['paphone1']);
		//			Work Phone
					$patient['phonework'] = displayPhone($patientrow['paphone2']);
		//			Cell Phone
					$patient['phonecell'] = displayPhone($patientrow['pacellphone']);
		//			Social Security Number
					$patient['ssn'] = displaySsnAll($patientrow['passn']);
		//			Sex
					$patient['sex'] = strtoupper($patientrow['pasex']);
		//			Birth Date
					$patient['birthdate'] = displayDate($patientrow['padob']);
		//			Age
	//				$pdf['patientage'] = displayDate($today-$birthdate);
					if(!isset($patientrow['padob'])) 
						$patient['age'] = "DOB not specified.";
					else {
						unset($year_diff);
//						echo($patientrow['padob']);
    					list($year,$month,$day) = explode("-",$patientrow['padob']);
						$year_diff  = date("Y") - $year;
						$month_diff = date("m") - $month;
						$day_diff   = date("d") - $day;
						if ($month_diff < 0) $year_diff--;
						elseif (($month_diff==0) && ($day_diff < 0)) $year_diff--;
						$patient['age'] = $year_diff;
//						echo($patient['age']);
					}
		//			E-mail Address
					$patient['email'] = strtolower($patientrow['paemail']);
		//			Occupation
					$patient['occupation'] = strtolower($caserow['croccup']);
		//		Referring Doctor 
		//			Name
		//			Address
		//			Phone
	
	// Referring Doctor
					$doctorarray=array();
					$doctor['name'] = "[CASE REFERRING DOCTOR GOES HERE]";
	
					$doctorlocationarray=array();
					$doctor['address'] = "[CASE REFERRING DOCTOR LOCATION GOES HERE]";
					$doctor['phone'] = "[CASE REFERRING DOCTOR PHONE GOES HERE]";
	
					if(!empty($dmid)) {
						require_once($_SERVER['DOCUMENT_ROOT'] . "/common/doctor.options.php");
						$doctorarray=getDoctorList($dmid,"1");
						if(empty($doctorarray[0]['dmfname']))
							$doctor['name'] = strtoupper($doctorarray[0]['dmlname']);
						else 
							$doctor['name'] = strtoupper($doctorarray[0]['dmlname'] . ", " . $doctorarray[0]['dmfname']);		
	// Referring Doctor Location
						if(!empty($dlid)) {
							$doctorlocationarray=getDoctorLocationList($dmid, $dlid, "1");
							$doctor['phone'] = displayPhone($doctorlocationarray[0]['dlphone']);
							if(!empty($doctorlocationarray[0]['dlcity'])) {
								if(!empty($doctorlocationarray[0]['dlstate'])) {
									if(!empty($doctorlocationarray[0]['dlzip'])) {
										$doctor['address'] = $doctorlocationarray[0]['dlcity'] . ", " . $doctorlocationarray[0]['dlstate'] . " " . $doctorlocationarray[0]['dlzip'];
									}
									else
										$doctor['address'] = $doctorlocationarray[0]['dlcity'] . ", " . $doctorlocationarray[0]['dlstate'];
								}
								else
									$doctor['address'] = $doctorlocationarray[0]['dlcity']; 
							}
						}
					}
	
		//		Diagnosis
					$rx['diagnosis']=$caserow['crdxnature'];
		//		Bodypart
					$rx['bodypart']=$caserow['crdxbodypart'];
		//		Bodypart Descriptor
					$rx['bodypartdescriptor']=$caserow['crdxbodydescriptor'];
	
					if(empty($caserow['crdx']))
						$rx['dx'] = "[PRESCRIPTION DIAGNOSIS GOES HERE]";
					else
						$rx['dx'] = $caserow['crdx'];
	
					$icd9array=icd9CodeOptions();
					$dxarray=array();
					if(!empty($caserow['crdx1']))
						$dxarray[]=$icd9array[$caserow['crdx1']]['description'] . "(".$caserow['crdx1'].")";
					if(!empty($caserow['crdx2']))
						$dxarray[]=$icd9array[$caserow['crdx2']]['description'] . "(".$caserow['crdx2'].")";
					if(!empty($caserow['crdx3']))
						$dxarray[]=$icd9array[$caserow['crdx3']]['description'] . "(".$caserow['crdx3'].")";
					if(!empty($caserow['crdx4']))
						$dxarray[]=$icd9array[$caserow['crdx4']]['description'] . "(".$caserow['crdx4'].")";
					$rx['icd91']=$dxarray[0];
					$rx['icd92']=$dxarray[1];
					$rx['icd93']=$dxarray[2];
					$rx['icd94']=$dxarray[3];
	
	// body parts
					if(empty($caserow['crdxbodypart']))
						$rx['bodypart']="[CASE BODY PART(S) GOES HERE]";
					else {
						require_once($_SERVER['DOCUMENT_ROOT'] . '/common/injury.options.php');
						$injurybodyparts=getInjuryBodypartTypeOptions($row['crdxbodypart']);
						$rx['bodypart']=$injurybodyparts[0]['title'];
					}

					if(!empty($caserow['crdxbodydescriptor'])) {
						$bodypartstypes=array();
						foreach(getInjuryDescriptorTypeOptions(1) as $value=>$itemarray)
							$bodypartstypes[$itemarray['value']]=$itemarray['title'];
						$rx['bodypartdescriptor'] = $bodypartstypes[$row['crdxbodydescriptor']];
					}

					$data=array(
						'patient'=>$patient,
						'doctor'=>$doctor,
						'case'=>$case,
						'rx'=>$rx,
						'insurance1'=>$insurance1,
						'insurance2'=>$insurance2,
						'employer'=>$employer,
						'attorney'=>$attorney,
						'additional'=>$additional
					);
// Format Output Fields

// Save document data in database documents (for future re-print)

// Generate PDF Documents for New Patient Chart
//				$page[1]=generatePDF1001($pdf); // Patient Information and Treatment Authorization Form
					return($data);
				} // Got Doctor Location Record
				else
					error("999","Doctor Location $dlid");
			} // Got Doctor and Location
			else
				error("998","Doctor $dmid");
		} // Got Patient
		else
			error("997","Patient $paid");
	} // Got Case
	else
		error("996","Case $paid");
displaysitemessages();
}
?>