<?php
error_reporting(E_ALL);
ini_set("display_errors", 1); 
ini_set('max_execution_time', 0);
ini_set('memory_limit',"100M");
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');

if(isset($_GET['clear'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle1 = dbconnect();
	$dbselect1 = dbselect($dbhandle1);
	$clearquery = "UPDATE Authorizations1 SET importedcase=0";
	$result=mysqli_query($dbhandle,$clearquery);
	$clearquery = "truncate table cases";
	$result=mysqli_query($dbhandle,$clearquery);
	$clearquery = "truncate table case_prescriptions";
	$result=mysqli_query($dbhandle,$clearquery);
	$clearquery = "truncate table case_history";
	$result=mysqli_query($dbhandle,$clearquery);
	echo("tables cleared.<br>");
	mysqli_close($dbhandle1);
	exit();
}

function getxrefCode50($field, $code50, $newcode) {
	global $codes;
	$searchvalue = strtoupper(str_replace( ' ', '', $code50));
	foreach($codes as $key=>$value) {
		if($value['cfield']==$field && $value['ckey']==$searchvalue) {
			if(!empty($value["$newcode"]))
				return($value["$newcode"]);
			else
				return(FALSE);
		}
	}
	return(FALSE);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$codes=array();
$xrefCode50query = "select * from xrefCode50";
if($xrefCode50result = mysqli_query($dbhandle,$xrefCode50query)) {
	while($xrefCode50row = mysqli_fetch_assoc($xrefCode50result)) {
		$codes[]=$xrefCode50row;
	}
}

if(count($codes)==0) { 
	echo("You must convert xrefcode50.");
	exit();
}

if( ($xcount=count($codes)) > 300) { 
	echo("You converted too many xrefcode50s $xcount.");
	exit();
}

$reads=0;
$inserts=0;
$updates=0;
errorclear();

$authorizations1query  = "SELECT id, Active, importedpatient, FirstName, LastName, SocSecuirty, MF, DOB, Pt, Email, EMPLOYER, ApptDate, ApptTime, NumberAuthorized, RefPhysician, ScheduledBy, Marketer, ClinicReferredTo, TreatingPT, InsuranceName, InsurancePhone, DateReferred, importeddoctor, importeddoctorlocation, DOI, DX, TypeofTherapy, Status, SurgDate, DateCanned, ReasonforNotScheduling, Authorizer, AuthDate, DateofAuthChange, AuthStatus, ReAdmitReLocated, PSP, Notes FROM Authorizations1 WHERE importedcase=0";

if($authorizations1result = mysqli_query($dbhandle,$authorizations1query)) {
	$auditfields = getauditfields();
	$authorizations1NumRows = mysqli_num_rows($authorizations1result);
	while(($authorizations1Row = mysqli_fetch_assoc($authorizations1result)) && errorcount()==0) {
		$reads++;
		$map['case'] = array();
		if(($reads % 1000) == 0) {
			echo(" Records processed ... $reads of $authorizations1NumRows<br>" );
		}
		// Validate all input fields

		// Assign/Format output fields
//
// PATIENT INFORMATION
//
		if($authorizations1Row['Active'] == 'F') 
			$map['case']['crinactive'] = '1'; 
		else 
			$map['case']['crinactive'] = '0'; 
		// crpaid : Customer Service Patient Id
		$map['case']['crpaid'] = $authorizations1Row['importedpatient'];
		// crpnum : PTOS Account Number
		$map['case']['crpnum'] = '';
		$map['case']['crfname'] = strtoupper(trim($authorizations1Row['FirstName'])); 
		$map['case']['crmname'] = ''; 
		$map['case']['crlname'] = strtoupper(trim($authorizations1Row['LastName'])); 
		$map['case']['crssn'] = substr(dbSsn($authorizations1Row['SocSecuirty']),0,9);
		$map['case']['craddress1'] = '';
		$map['case']['craddress2'] = '';
		$map['case']['crcity'] = '';
		$map['case']['crstate'] = 'CA';
		$map['case']['crzip'] = '';
		$map['case']['crsex']= getxrefCode50('MF', $authorizations1Row['MF'], 'code3');
		if(!empty($authorizations1Row['DOB'])) {
			$crdob=date("Y-m-d H:i:s", strtotime($authorizations1Row['DOB']));
			if($crdob==(-1)) 
				unset($map['case']['crdob']);
			else
				$map['case']['crdob']=$crdob;
		}
		else
			unset($map['case']['crdob']);

		$map['case']['crphone1'] = dbPhone($authorizations1Row['Pt']);
		$map['case']['crphone2'] = '';
		$map['case']['crphone3'] = '';
		$map['case']['cremail'] = strtolower($authorizations1Row['Email']);
		$map['case']['crnote'] = '';
//
// EMPLOYMENT INFORMATION
//
		// croccup : Occupation 
		$map['case']['croccup'] = '';
		// crempname : Employer Name (copied from Employer Master)
		$map['case']['crempname'] = strtoupper($authorizations1Row['EMPLOYER']);

//
// RESPONSIBLE PARTY INFORMATION
//
		// crpayor : Payor
		$map['case']['crpayor'] = '';
		// crcopay : Copay 
		$map['case']['crcopay'] = 0;

//
// CLINIC TREATMENT INFORMATION
//
		$map['case']['crcnum'] = getxrefCode50('ClinicReferredTo', $authorizations1Row['ClinicReferredTo'], 'code3');

		$apptdate = trim($authorizations1Row['ApptDate']);
		$appttime = trim($authorizations1Row['ApptTime']);
//		echo("DATE: $apptdate TIME: $appttime ");
		$appt=NULL;
		if(!empty($apptdate) && !empty($appttime)) {
			if( strtotime($appttime) < strtotime('08:00:00') ) {
//				echo("Changed to PM ");
				$appt = strtotime($apptdate . " " . $appttime . " PM");
			}
			else {
//				echo(" ok ");
				$appt = strtotime($apptdate . " " . $appttime);
			}
		}
		else {
			if(!empty($apptdate)) {
//				echo(" DATE GIVEN, BUT NO TIME GIVEN ");
				$appt = strtotime($apptdate);
			}
			else {
//				echo(" NO DATE OR TIME GIVEN ");
				$appt = NULL;
			}
//			echo("NEWDATE: " . date('Y-m-d H:i:s', $appt));
		}
//		echo("<br>");
		$map['case']['crapptdate'] = date('Y-m-d H:i:s', $appt);

		$map['case']['crapptscheduler'] = getxrefCode50('ScheduledBy', $authorizations1Row['ScheduledBy'], 'calpha');

		// crstartdate : Start Date (First Visit Date)
		$map['case']['crfvisitdate'] = '';
		// crenddate : End Date (Last Visit Date)
		$map['case']['crlvisitdate'] = '';

		// crvisitauthorized : Authorized Visits 
		$map['case']['crvisitsauthorized'] = $authorizations1Row['NumberAuthorized'];

		// crvisitused : Used Visits 
		$map['case']['crvisitsused'] = '';

		// crvisitenddate : Visit End Date (Last Auth Visit Date)
		$map['case']['crvisitenddate'] = '';

//
// INJURY INFORMATION
//
		// cricd9code1 : ICD9 1 Code
		$map['case']['cricd9code1'] = '';
		// cricd9desc1 : ICD9 1 Description
		$map['case']['cricd9desc1'] = '';
		// cricd9code2 : ICD9 2 Code
		$map['case']['cricd9code2'] = '';
		// cricd9desc2 : ICD9 2 Description
		$map['case']['cricd9desc2'] = '';
		// cricd9code3 : ICD9 3 Code
		$map['case']['cricd9code3'] = '';
		// cricd9desc3 : ICD9 3 Description
		$map['case']['cricd9desc3'] = '';
		// cricd9code4 : ICD9 4 Code
		$map['case']['cricd9code4'] = '';
		// cricd9desc4 : ICD9 4 Description
		$map['case']['cricd9desc4'] = '';

//
// REFERRING DOCTOR INFORMATION
//
		// crrefdoc : Referring Doctor (Copied from Doctor Master Record)
		$map['case']['crrefdoc'] = strtoupper($authorizations1Row['RefPhysician']);
		// crrefdocupin : Referring Doctor upin (Copied from Doctor Master Record)
		$map['case']['crrefdocupin'] = '';
		// crrefdocnpi : Referring Doctor npi (Copied from Doctor Master Record)
		$map['case']['crrefdocnpi'] = '';

//
// PRIMARY CARE PHYSICIAN INFORMATION
//
		// crpridoc : Primary Doctor (Copied from Doctor Master Record)
		$map['case']['crpridoc'] = '';
		// crpridocupin : Primary Doctor upin (Copied from Doctor Master Record)
		$map['case']['crpridocupin'] = '';
		// crpridocnpi : Primary Doctor npi (Copied from Doctor Master Record)
		$map['case']['crpridocnpi'] = '';

//
// THERAPIST INFORMATION
//
		// crtherapcode : Therapist Code
		$map['case']['crtherapcode'] = '';
		// crtherapname : Therapist Name
		$map['case']['crtherapname'] = getxrefCode50('TreatingPT', $authorizations1Row['TreatingPT'],'calpha');
		// crtherapnpi : Therapist NPI
		$map['case']['crtherapnpi'] = '';

//
// PRIMARY INSURANCE INFORMATION
//
		// crinsurance1code : Primary Insurance Co Code (copied from Master)
		$map['case']['crinsurance1code'] = '';
		// crinsurance1name : Primary Insurance Co Name (copied from Master)
		$map['case']['crinsurance1name'] = strtoupper($authorizations1Row['InsuranceName']);
		// crinsurance1add1 : Primary Insurance Co Address Line 1 (copied from Master)
		$map['case']['crinsurance1add1'] = '';
		// crinsurance1add2 : Primary Insurance Co Address Line 2 (copied from Master)
		$map['case']['crinsurance1add2'] = '';
		// crinsurance1city : Primary Insurance Co City (copied from Master)
		$map['case']['crinsurance1city'] = '';
		// crinsurance1state : Primary Insurance Co State (copied from Master)
		$map['case']['crinsurance1state'] = '';
		// crinsurance1zip : Primary Insurance Co Zip (copied from Master)
		$map['case']['crinsurance1zip'] = '';
		// crinsurance1phone : Primary Insurance Co Phone (copied from Master)
		$map['case']['crinsurance1phone'] = dbPhone($authorizations1Row['InsurancePhone']);
		// crinsurance1group : Primary Insurance Co Group (copied from Master)
		$map['case']['crinsurance1group'] = '';
		// crinsurance1note : Primary Insurance Co Note (copied from Master)
		$map['case']['crinsurance1note'] = '';

//
// SECONDARY INSURANCE INFORMATION
//
		// crinsurance2code : Primary Insurance Co Code (copied from Master)
		$map['case']['crinsurance2code'] = '';
		// crinsurance2name : Primary Insurance Co Name (copied from Master)
		$map['case']['crinsurance2name'] = '';
		// crinsurance2add1 : Primary Insurance Co Address Line 1 (copied from Master)
		$map['case']['crinsurance2add1'] = '';
		// crinsurance2add2 : Primary Insurance Co Address Line 2 (copied from Master)
		$map['case']['crinsurance2add2'] = '';
		// crinsurance2city : Primary Insurance Co City (copied from Master)
		$map['case']['crinsurance2city'] = '';
		// crinsurance2state : Primary Insurance Co State (copied from Master)
		$map['case']['crinsurance2state'] = '';
		// crinsurance2zip : Primary Insurance Co Zip (copied from Master)
		$map['case']['crinsurance2zip'] = '';
		// crinsurance2phone : Primary Insurance Co Phone (copied from Master)
		$map['case']['crinsurance2phone'] = '';
		// crinsurance2group : Primary Insurance Co Group (copied from Master)
		$map['case']['crinsurance2group'] = '';
		// crinsurance2note : Primary Insurance Co Note (copied from Master)
		$map['case']['crinsurance2note'] = '';

// HOW DOES FACILITY WORK?

		// crdate : Referral date
		$map['case']['crdate'] = dbDate($authorizations1Row['DateReferred']);
		// crreadmit : Readmit Flag
		if($authorizations1Row['ReAdmitReLocated']=='T') {
			$map['case']['crreadmit'] = TRUE;
		}
		else {
			$map['case']['crreadmit'] = FALSE;
		}
		// crpostsurgical : PostSurgical Flag
		if($authorizations1Row['PSP']=='Y') {
			$map['case']['crpostsurgical'] = TRUE;
		}
		else {
			$map['case']['crpostsurgical'] = FALSE;
		}
		// cremprelate : Employ/Work Related Injury Flag
		$map['case']['cremprelated'] = '';
		// crempadd1 : Employer Address Line 1 (copied from Employer Master)
		$map['case']['crempadd1'] = '';
		// crempadd2 : Employer Address Line 2 (copied from Employer Master)
		$map['case']['crempadd2'] = '';
		// crdmid : Original Referring Doctor Id
		$map['case']['crrefdmid'] = $authorizations1Row['importeddoctor'];
		// crdmid : Original Referring Doctor Location Id
		$map['case']['crrefdlid'] = $authorizations1Row['importeddoctorlocation'];

		$map['case']['crsalesid'] = getxrefCode50('Marketer', $authorizations1Row['Marketer'],'code3');
		// crretdr : Return Doctor Date 
		$map['case']['crretdr'] = '';
		// crinjurydate : Injury Date
		$map['case']['crinjurydate'] = dbDate($authorizations1Row['DOI']);
		// crinjurytypecode : Injury Type Code  - Database Pro allows free format text entry
		$map['case']['crinjurytypecode'] = "cases";
		$map['case']['crdxcode'] = $authorizations1Row['DX'];
		// crdischargedate : Discharge Date
		$map['case']['crdischargedate'] = '';
		// crpayoradd1 : Payor Address Line 1
		$map['case']['crpayoradd1'] = '';
		// crpayoradd2 : Payor Address Line 2
		$map['case']['crpayoradd2'] = '';
		// crpayorcity : Payor Address City
		$map['case']['crpayorcity'] = '';
		// crpayorstate : Payor Address State
		$map['case']['crpayorstate'] = 'CA';
		// cracctype : Account Type - WC, PI, MC, PR, ???

		$map['case']['crcasetypecode'] = getxrefCode50('TypeofTherapy', $authorizations1Row['TypeofTherapy'],'csname');

		// crlastproc : Last Procedure Code
		$map['case']['crlastproc'] = '';
		// crrefnum : Reference Number 
		$map['case']['crrefnum'] = '';
		$map['case']['crcasestatuscode'] = getxrefCode50('Status', $authorizations1Row['Status'],'code3');
		$map['case']['crsurgerydate'] = dbDate($authorizations1Row['SurgDate']);
		$map['case']['crtherapytypecode'] = getxrefCode50('TypeofTherapy', $authorizations1Row['TypeofTherapy'],'code3');
		$map['case']['crcanceldate'] = dbDate($authorizations1Row['DateCanned']);
		$map['case']['crcancelreasoncode'] = getxrefCode50('ReasonforNotScheduling', $authorizations1Row['ReasonforNotScheduling'],'code3');
		$map['case']['importid'] = $authorizations1Row['id'];

		// importid = Import Authorization Database Record Id
		$map['case']['crtuser'] = $auditfields['user'];
		$map['case']['crtdate'] = $auditfields['date'];
		$map['case']['crtprog'] = $auditfields['prog'];
		if(count($map['case'])) {
			foreach($map['case'] as $key=>$value) {
				if(empty($value)) 
					unset($map['case']["$key"]);
				else
					$map['case']["$key"] = "'" . mysqli_real_escape_string($dbhandle,$value) ."'";
			}
		}
		// Insert Information
		$query = "INSERT INTO cases ";
		$query .= '(' . implode(', ', array_keys($map['case'])) . ') ';
		$query .= 'VALUES(' . implode(', ', array_values($map['case'])) . ') ';
		if($result = mysqli_query($dbhandle,$query)) {
			$inserts++;
			if($crid = mysql_insert_id()) {
				$updatequery="UPDATE Authorizations1 SET importedcase='$crid' WHERE id='" . $authorizations1Row['id'] . "'";
				if($updateresult= mysqli_query($dbhandle,$updatequery)) 
					$updates++;
				else {
					error("010", "UPDATEQUERY:$updatequery<br>".mysqli_error($dbhandle)."<br>");
					break;
				}
			}
			else {
				error("020", "crid:$crid<br>".mysqli_error($dbhandle)."<br>");
				break;
			}
		}
		else {
			error("030", "QUERY:$query<br>".mysqli_error($dbhandle)."<br>");
			break;
		}
	} // While
} // If
else 
	error("999", "FIRST ERROR<br>");
mysqli_close($dbhandle);

notify("000", "$reads Records processed ... of $authorizations1NumRows<br>" );
notify("000", "$inserts Records Inserted ... of $authorizations1NumRows<br>" );
notify("000", "$updates Records Updated ... of $authorizations1NumRows<br>" );
displaysitemessages();
?>