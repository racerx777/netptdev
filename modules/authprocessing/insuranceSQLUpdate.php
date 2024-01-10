<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
errorclear();

if(!empty($crid) && !empty($icseq)) {
	// trim and strip all input
	foreach($_POST as $key=>$val) {
		if($key != 'button') {
			if(is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags(trim($val)));
		}
	}

	// Validate form fields
//	require_once('insuranceValidation.php');

	if(errorcount() == 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$set=array();
		//declare the SQL statement that will query the database
		$query = "UPDATE cases ";
		if($icseq == '1') {
			if(!empty($_POST['icid'])) {
				// Get Insurance Company Information
				$set[] = "cricid1='" . mysqli_real_escape_string($dbhandle,$_POST['icid']) . "'";
				if(!empty($_POST['iclid'])) {
					// get insurance company location information
					$set[] = "criclid1='" . mysqli_real_escape_string($dbhandle,$_POST['iclid']) . "'";
				}
				else {
					// blank out insurance company location information
					$set[] = "criclid1=NULL";
				}
				if(!empty($_POST['icaid'])) { 
					// get insurance adjuster information
					$set[] = "cricaid1='" . mysqli_real_escape_string($dbhandle,$_POST['icaid']) . "'";
				}
				else {// adjuster empty 
					// blanks out insurance adjuster information
					$set[] = "cricaid1=NULL";
				}
			}
			else {
				// blanks out all information
				$set[] = "cricid1=NULL";
				$set[] = "criclid1=NULL";
				$set[] = "cricaid1=NULL";
			}

			// get and set insurance 1 values added 11/6/2010
			require_once($_SERVER['DOCUMENT_ROOT'] . '/common/insurance.options.php');
			$insuranceCompanys = getInsuranceCompaniesList(); // all insurance companies indexed by id
			$thiscompany=$insuranceCompanys[$_POST['icid']];
			$set[]="crinsurance1name='" . mysqli_real_escape_string($dbhandle,$thiscompany['icname']) . "'";

			$insuranceLocations = getInsuranceCompaniesLocationsList();
			$thislocation=$insuranceLocations[$_POST['iclid']];
			$set[]="crinsurance1code='" . mysqli_real_escape_string($dbhandle,$thislocation['iclid']) . "'";
			$set[]="crinsurance1add1='" . mysqli_real_escape_string($dbhandle,$thislocation['icladdress1']) . "'";
			$set[]="crinsurance1add2='" . mysqli_real_escape_string($dbhandle,$thislocation['icladdress2']) . "'";
			$set[]="crinsurance1city='" . mysqli_real_escape_string($dbhandle,$thislocation['iclcity']) . "'";
			$set[]="crinsurance1state='" . mysqli_real_escape_string($dbhandle,$thislocation['iclstate']) . "'";
			$set[]="crinsurance1zip='" . mysqli_real_escape_string($dbhandle,$thislocation['iclzip']) . "'";
			$set[]="crinsurance1phone='" . mysqli_real_escape_string($dbhandle,$thislocation['iclphone']) . "'";

			$insuranceAdjusters = getInsuranceCompaniesAdjustersList();
			$thisadjuster=$insuranceAdjusters[$_POST['icaid']];
			$set[]="crinsurance1adjlname='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icalname']) . "'";
			$set[]="crinsurance1adjfname='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icafname']) . "'";
			$set[]="crinsurance1adjphone='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icaphone']) . "'";
			$set[]="crinsurance1adjemail='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icaemail']) . "'";
			$set[]="crinsurance1adjfax='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icafax']) . "'";
			// get and set insurance 1 values added 11/6/2010

			if(!empty($_POST['icclaimnumber']))
				$set[] = "cricclaimnumber1='" . mysqli_real_escape_string($dbhandle,$_POST['icclaimnumber']) . "'";
			else
				$set[] = "cricclaimnumber1=NULL";
			
			if(!empty($_POST['icnote']))
				$set[] = "crinsurance1note='" . mysqli_real_escape_string($dbhandle,$_POST['icnote']) . "'";
			else
				$set[] = "crinsurance1note=NULL";

		}
		if($icseq==2) {
			if(!empty($_POST['icid'])) {
				$set[] = "cricid2='" . mysqli_real_escape_string($dbhandle,$_POST['icid']) . "'";
				if(!empty($_POST['iclid'])) 
					$set[] = "criclid2='" . mysqli_real_escape_string($dbhandle,$_POST['iclid']) . "'";
				else 
					$set[] = "criclid2=NULL";
				if(!empty($_POST['icaid'])) 
					$set[] = "cricaid2='" . mysqli_real_escape_string($dbhandle,$_POST['icaid']) . "'";
				else // adjuster empty 
					$set[] = "cricaid2=NULL";
			}
			else {
				$set[] = "cricid2=NULL";
				$set[] = "criclid2=NULL";
				$set[] = "cricaid2=NULL";
			}

			// get and set insurance 2 values added 11/6/2010
			require_once($_SERVER['DOCUMENT_ROOT'] . '/common/insurance.options.php');
			$insuranceCompanys = getInsuranceCompaniesList(); // all insurance companies indexed by id
			$thiscompany=$insuranceCompanys[$_POST['icid']];
			$set[]="crinsurance2name='" . mysqli_real_escape_string($dbhandle,$thiscompany['icname']) . "'";

			$insuranceLocations = getInsuranceCompaniesLocationsList();
			$thislocation=$insuranceLocations[$_POST['iclid']];
			$set[]="crinsurance2code='" . mysqli_real_escape_string($dbhandle,$thislocation['iclid']) . "'";
			$set[]="crinsurance2add1='" . mysqli_real_escape_string($dbhandle,$thislocation['icladdress1']) . "'";
			$set[]="crinsurance2add2='" . mysqli_real_escape_string($dbhandle,$thislocation['icladdress2']) . "'";
			$set[]="crinsurance2city='" . mysqli_real_escape_string($dbhandle,$thislocation['iclcity']) . "'";
			$set[]="crinsurance2state='" . mysqli_real_escape_string($dbhandle,$thislocation['iclstate']) . "'";
			$set[]="crinsurance2zip='" . mysqli_real_escape_string($dbhandle,$thislocation['iclzip']) . "'";
			$set[]="crinsurance2phone='" . mysqli_real_escape_string($dbhandle,$thislocation['iclphone']) . "'";

			$insuranceAdjusters = getInsuranceCompaniesAdjustersList();
			$thisadjuster=$insuranceAdjusters[$_POST['icaid']];
			$set[]="crinsurance2adjlname='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icalname']) . "'";
			$set[]="crinsurance2adjfname='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icafname']) . "'";
			$set[]="crinsurance2adjphone='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icaphone']) . "'";
			$set[]="crinsurance2adjemail='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icaemail']) . "'";
			$set[]="crinsurance2adjfax='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icafax']) . "'";
			// get and set insurance 2 values added 11/6/2010

			if(!empty($_POST['icclaimnumber']))
				$set[] = "cricclaimnumber2='" . mysqli_real_escape_string($dbhandle,$_POST['icclaimnumber']) . "'";
			else
				$set[] = "cricclaimnumber2=NULL";

			if(!empty($_POST['icnote']))
				$set[] = "crinsurance2note='" . mysqli_real_escape_string($dbhandle,$_POST['icnote']) . "'";
			else
				$set[] = "crinsurance2note=NULL";
// get and set insurance 2 values
		}
		if(count($set) > 0)
			$query .= "SET " . implode(', ', $set);
		$query .= " WHERE crid='$crid'";
//dump("query",$query);
		//execute the SQL query 
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			notify("000","Insurance successfully updated.");
//
// MODIFICATION EXPORT UPDATED INSURANCE INFORMATION
// If Insurance was updated and case was already exported to PTOS then update PTOS Insurance Information
//
			$selected=0;
			$processed=0;
			$errors=0;
			
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
				WHERE crid ='$crid'
			";
			if($selectresult = mysqli_query($dbhandle,$selectquery)) {
				$selected = mysqli_num_rows($selectresult);
				while($row = mysqli_fetch_assoc($selectresult)) {
					$bnum=$row['cmbnum'];
					$pnum=$row['crpnum'];
					if(!empty($bnum) && !empty($pnum) && !empty($crid) && caseExportXML($bnum, $pnum, $crid, $row)) {
						notify('000', "XML exported...");
					}
					else 
						error('022', "XML Error creating XML file $xmlfilename. Case $crid will NOT be updated in PTOS.");
				} // while
			}
			else
				error('004', "SELECT error. $selectquery<br>".mysqli_error($dbhandle));

			foreach($_POST as $key=>$val) 
				unset($_POST[$key]);
		}
		else
			error('001', "Error Updating Record : $query<br>" . mysqli_error($dbhandle)); 	
		//close the connection
		mysqli_close($dbhandle);
	}
}
else 
	error('000', "Error crid/icseq : $crid/$icseq");


function caseExportCleanString($string) {
	$cleanvalue=mysqli_real_escape_string($dbhandle,$string);
// Remove the ampersand
	$cleanvalue=str_replace(" & "," and ", $cleanvalue);
	$cleanvalue=str_replace("& ","and ", $cleanvalue);
	$cleanvalue=str_replace(" &"," and", $cleanvalue);
	$cleanvalue=str_replace("&","and", $cleanvalue);
	return($cleanvalue);
}

function caseExportXML($bnum, $pnum, $crid, $row) {
	if(!empty($row['crpnum'])) {
	// Mapped fields are below
		$xml=array();
	
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
			$cleanxml["$field"]=caseExportCleanString($value);
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
			INSERT INTO ptos_interface (xmlbumcode, xmlcrid, xmlpnum, xmldatatype, xmlstatus, xmlstring)
			VALUES('$bnum', '$crid', '$pnum', 'I', 'NEW', '$xmldata')
			";
		if($insertresult = mysqli_query($dbhandle,$insertquery)) 
			return(TRUE); 
		else 
			error("999","Interface INSERT error. $insertquery<br>".mysqli_error($dbhandle));
	}
	return(FALSE);
}
?>