<?php
// Read through Authorizations1.esd.csv
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
errorclear();
if(isset($_GET['clear'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$clearquery = "TRUNCATE TABLE Authorizations1";
	$result=mysqli_query($dbhandle,$clearquery);
	echo("tables cleared.<br>");
	exit();
}

$handle = fopen("new patients.esd.csv", "r");
$length = 8192;

$delimiter = '","';

$fieldnames = array();
$fieldnames["# Authorized"] 				= "NumberAuthorized";
$fieldnames["Active"] 						= "Active";
$fieldnames["Adj Gross"] 					= "AdjGross";
$fieldnames["Adjuster"] 					= "Adjuster";
$fieldnames["Adjuster ext."] 				= "AdjusterExt";
$fieldnames["Adjuster Fax #:"] 				= "AdjusterFax";
$fieldnames["Appt Date: "] 					= "ApptDate";
$fieldnames["Appt Time:"] 					= "ApptTime";
$fieldnames["Auth Date:"] 					= "AuthDate";
$fieldnames["Authorization Status"] 		= "AuthStatus";
$fieldnames["Authorizer"] 					= "Authorizer";
$fieldnames["Claim #"] 						= "Claim";
$fieldnames["Clinic Referred To"] 			= "ClinicReferredTo";
$fieldnames["Date Appointment Made:"] 		= "DateAppointmentMade";
$fieldnames["Date Canned"] 					= "DateCanned";
$fieldnames["Date of Auth Change"] 			= "DateofAuthChange";
$fieldnames["Date Referred: "] 				= "DateReferred";
$fieldnames["DOB:"] 						= "DOB";
$fieldnames["DOI:"] 						= "DOI";
$fieldnames["DR Fax #"] 					= "DRFax";
// DR Phone #
$fieldnames["DR Phone #"] 					= "DRPhone";
// Dr. City
$fieldnames["Dr. City"] 					= "DrCity";
$fieldnames["DX:"] 							= "DX";
$fieldnames["Email"] 						= "Email";
$fieldnames["EMPLOYER"] 					= "EMPLOYER";
$fieldnames["FirstName:"] 					= "FirstName";
$fieldnames["Freq and Duration"] 			= "FreqandDuration";
$fieldnames["Initial Eval"] 				= "InitialEval";
$fieldnames["Insurance Name:"] 				= "InsuranceName";
$fieldnames["Insurance Phone #:"] 			= "InsurancePhone";
$fieldnames["Intake"] 						= "Intake";
$fieldnames["LastName:"] 					= "LastName";
$fieldnames["Location Wanted"] 				= "LocationWanted";
$fieldnames["M/F"] 							= "MF";
$fieldnames["Marketer"] 					= "Marketer";
//$fieldnames["MD City"] 						= "MDCity";
$fieldnames["MD Class:"] 					= "MDClass";
$fieldnames["Next Action Date:"] 			= "NextActionDate";
//$fieldnames["Notes"] 						= "Notes";
$fieldnames["Possible Re-Admit"] 			= "PossibleReAdmit";
//$fieldnames["Post Surgical"] 				= "PostSurgical";
$fieldnames["Potential Re-Admit"] 			= "PotentialReAdmit";
$fieldnames["PR2"] 							= "PR2";
$fieldnames["Prescription"] 				= "Prescription";
$fieldnames["PSP"] 							= "PSP";
$fieldnames["PT Notes"] 					= "PTNotes";
$fieldnames["Pt. #:"] 						= "Pt";
$fieldnames["Re-Admit/Re-Located:"] 		= "ReAdmitReLocated";
$fieldnames["Reason for Not Scheduling"] 	= "ReasonforNotScheduling";
$fieldnames["Ref Physician"] 				= "RefPhysician";
//$fieldnames["Ref Physician 1"] 				= "RefPhysician1";
$fieldnames["Report Protocol"] 				= "ReportProtocol";
$fieldnames["Scheduled by:"] 				= "ScheduledBy";
$fieldnames["Soc Secuirty #"] 				= "SocSecuirty";
$fieldnames["Status:"] 						= "Status";
$fieldnames["Still Treating"] 				= "StillTreating";
$fieldnames["Surg Date"] 					= "SurgDate";
$fieldnames["Treating PT"] 					= "TreatingPT";
$fieldnames["Type of Therapy"] 				= "TypeofTherapy";
$fieldnamescount = count($fieldnames);

$row = 0;
while (( $input = fgets($handle, $length)) !== FALSE) {
	$data = array();
// Trim space
	$input = trim($input);
// Trim Begin and End Quote Characters
	$input = substr($input, 1, strlen($input) - 2);
// Split fields into array
	$data = explode($delimiter, $input);

	$num = count($data);
// First Row Contains Field Names
	if($row==0) {
// Check to be sure the number of fields expected match
		if($num == $fieldnamescount) {
			for($c=0; $c < $num; $c++) {
// $csvfield[0] = " FirstName:";
				$csvfield[$c] = $data[$c]; // the csv field name from first row
				$dbfield[$c]=$fieldnames[$data[$c]]; // dbfields fields mapped in array above
			}
// Connect to database
			require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
			$dbhandle = dbconnect();
			
		}
		else {
			echo("Number of fields in CSV header ($num) does not match number of fields expected ($fieldnamescount)");
			exit;
		}
	}
// All other rows contain data
	else {
		if($num == $fieldnamescount) {
			$setarray = array();
			for($c=0; $c < $num; $c++) {
				$setarray[] = "'" . mysqli_real_escape_string($dbhandle,$data[$c]) . "'";
			}
			$query = "INSERT INTO Authorizations1 (" . implode(",", $dbfield) . ") VALUES(" . implode(",", $setarray) . ")";	
// Insert into database
			$result = mysqli_query($dbhandle,$query);
			if($result) {
				$inserted++;
			}
			else {
				error("001", "QUERY: $query<br>ERROR:" . mysqli_error($dbhandle) . "<br>");
				$errors++;
				break;
			}
		}
	}
	$row++;
}
if($inserted > 0) 
	notify("000", "$inserted records inserted.");
if($errors > 0) 
	error("001", "$errors records in error.");
displaysitemessages(); 								// site notification messages
mysql_close($dbhandle);
fclose($handle);
?>