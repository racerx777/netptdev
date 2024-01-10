<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
errorclear();
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$selectquery1="
SELECT * 
FROM cases 
WHERE crcasestatuscode<>'CAN' and crptosstatus IS NOT NULL and crptosstatus <>'' AND cricid1 IS NOT NULL AND crinsurance1name = '' AND crpnum IS NOT NULL AND crpnum<>''
";
if($selectresult1 = mysqli_query($dbhandle,$selectquery1)) {

	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/insurance.options.php');
	$insuranceCompanys = getInsuranceCompaniesList(); // all insurance companies indexed by id
	$insuranceLocations = getInsuranceCompaniesLocationsList();
	$insuranceAdjusters = getInsuranceCompaniesAdjustersList();
	while($selectrow = mysqli_fetch_assoc($selectresult1)) {
		$crid=$selectrow['crid'];
		$set=array();
		if(!empty($selectrow['cricid1'])) {
			$set[] = "cricid1='" . mysqli_real_escape_string($dbhandle,$selectrow['cricid1']) . "'";
			if(!empty($selectrow['criclid1'])) 
				$set[] = "criclid1='" . mysqli_real_escape_string($dbhandle,$selectrow['criclid1']) . "'";
			else 
				$set[] = "criclid1=NULL";
			if(!empty($selectrow['cricaid1'])) 
				$set[] = "cricaid1='" . mysqli_real_escape_string($dbhandle,$selectrow['cricaid1']) . "'";
			else  
				$set[] = "cricaid1=NULL";
		}
		else {
			$set[] = "cricid1=NULL";
			$set[] = "criclid1=NULL";
			$set[] = "cricaid1=NULL";
		}

		$thiscompany=$insuranceCompanys[$selectrow['cricid1']];
		$set[]="crinsurance1name='" . mysqli_real_escape_string($dbhandle,$thiscompany['icname']) . "'";

		$thislocation=$insuranceLocations[$selectrow['criclid1']];
		$set[]="crinsurance1code='" . mysqli_real_escape_string($dbhandle,$thislocation['iclid']) . "'";
		$set[]="crinsurance1add1='" . mysqli_real_escape_string($dbhandle,$thislocation['icladdress1']) . "'";
		$set[]="crinsurance1add2='" . mysqli_real_escape_string($dbhandle,$thislocation['icladdress2']) . "'";
		$set[]="crinsurance1city='" . mysqli_real_escape_string($dbhandle,$thislocation['iclcity']) . "'";
		$set[]="crinsurance1state='" . mysqli_real_escape_string($dbhandle,$thislocation['iclstate']) . "'";
		$set[]="crinsurance1zip='" . mysqli_real_escape_string($dbhandle,$thislocation['iclzip']) . "'";
		$set[]="crinsurance1phone='" . mysqli_real_escape_string($dbhandle,$thislocation['iclphone']) . "'";

		$thisadjuster=$insuranceAdjusters[$selectrow['cricaid1']];
		$set[]="crinsurance1adjlname='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icalname']) . "'";
		$set[]="crinsurance1adjfname='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icafname']) . "'";
		$set[]="crinsurance1adjphone='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icaphone']) . "'";
		$set[]="crinsurance1adjemail='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icaemail']) . "'";
		$set[]="crinsurance1adjfax='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icafax']) . "'";

		if(!empty($selectrow['cricclaimnumber1']))
			$set[] = "cricclaimnumber1='" . mysqli_real_escape_string($dbhandle,$selectrow['cricclaimnumber1']) . "'";
		else
			$set[] = "cricclaimnumber1=NULL";
		
		if(!empty($selectrow['crinsurance1note']))
			$set[] = "crinsurance1note='" . mysqli_real_escape_string($dbhandle,$selectrow['crinsurance1note']) . "'";
		else
			$set[] = "crinsurance1note=NULL";

		if(!empty($selectrow['cricid2'])) {
			$set[] = "cricid2='" . mysqli_real_escape_string($dbhandle,$selectrow['cricid2']) . "'";
			if(!empty($selectrow['criclid2'])) 
				$set[] = "criclid2='" . mysqli_real_escape_string($dbhandle,$selectrow['criclid2']) . "'";
			else 
				$set[] = "criclid2=NULL";
			if(!empty($selectrow['cricaid2'])) 
				$set[] = "cricaid2='" . mysqli_real_escape_string($dbhandle,$selectrow['cricaid2']) . "'";
			else 
				$set[] = "cricaid2=NULL";
		}
		else {
			$set[] = "cricid2=NULL";
			$set[] = "criclid2=NULL";
			$set[] = "cricaid2=NULL";
		}

		$thiscompany=$insuranceCompanys[$selectrow['cricid2']];
		$set[]="crinsurance2name='" . mysqli_real_escape_string($dbhandle,$thiscompany['icname']) . "'";

		$thislocation=$insuranceLocations[$selectrow['criclid2']];
		$set[]="crinsurance2code='" . mysqli_real_escape_string($dbhandle,$thislocation['criclid2']) . "'";
		$set[]="crinsurance2add1='" . mysqli_real_escape_string($dbhandle,$thislocation['icladdress1']) . "'";
		$set[]="crinsurance2add2='" . mysqli_real_escape_string($dbhandle,$thislocation['icladdress2']) . "'";
		$set[]="crinsurance2city='" . mysqli_real_escape_string($dbhandle,$thislocation['iclcity']) . "'";
		$set[]="crinsurance2state='" . mysqli_real_escape_string($dbhandle,$thislocation['iclstate']) . "'";
		$set[]="crinsurance2zip='" . mysqli_real_escape_string($dbhandle,$thislocation['iclzip']) . "'";
		$set[]="crinsurance2phone='" . mysqli_real_escape_string($dbhandle,$thislocation['iclphone']) . "'";

		$thisadjuster=$insuranceAdjusters[$selectrow['cricaid2']];
		$set[]="crinsurance2adjlname='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icalname']) . "'";
		$set[]="crinsurance2adjfname='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icafname']) . "'";
		$set[]="crinsurance2adjphone='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icaphone']) . "'";
		$set[]="crinsurance2adjemail='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icaemail']) . "'";
		$set[]="crinsurance2adjfax='" . mysqli_real_escape_string($dbhandle,$thisadjuster['icafax']) . "'";
		// get and set insurance 2 values added 11/6/2010

		if(!empty($selectrow['cricclaimnumber2']))
			$set[] = "cricclaimnumber2='" . mysqli_real_escape_string($dbhandle,$selectrow['cricclaimnumber2']) . "'";
		else
			$set[] = "cricclaimnumber2=NULL";

		if(!empty($selectrow['crinsurance2note']))
			$set[] = "crinsurance2note='" . mysqli_real_escape_string($dbhandle,$selectrow['crinsurance2note']) . "'";
		else
			$set[] = "crinsurance2note=NULL";

		if(count($set) > 0) {
			$set[]="updprog='updateAll.php'";
			$query = "UPDATE cases SET " . implode(', ', $set) . " WHERE crid='$crid'";
			$result = mysqli_query($dbhandle,$query);
			if($result) {
				notify("000","Insurance for case $crid successfully updated.");
				if(isset($selectrow['crptosstatus']) && !empty($selectrow['crptosstatus']) && $selectrow['crptosstatus']!='RQS') {
					if(isset($selectrow['crpnum']) && !empty($selectrow['crpnum']) ) {
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
							if($row = mysqli_fetch_assoc($selectresult)) {
								$bnum=$row['cmbnum'];
								$pnum=$row['crpnum'];
								if(caseExportXML($bnum, $pnum, $crid, $row)) 
									notify('000', "XML exported...");
								else 
									error('022', "XML Error creating XML file $xmlfilename. Case $crid will NOT be updated in PTOS.");
							} // if
							else
								error('005', "SELECT error. $selectquery<br>".mysqli_error($dbhandle));
						}
						else
							error('004', "SELECT error. $selectquery<br>".mysqli_error($dbhandle));

						notify("000","XML for case $crid created.");
					}
					else 
						error("001","XML for case $crid NOT updated. PNUM ERROR");
				}
				else 
					error("002","XML for case $crid NOT updated. PTOSSTATUS ERROR");
			}
		}
		else 
			error("003","case $crid NOT updated. nothing to update <br>$query<br>");
		echo("end while <br>");
	}
}
displaysitemessages();
mysqli_close($dbhandle);

function caseExportXML($bnum, $pnum, $crid, $row) {
	if(!empty($row['crpnum'])) {
	// Mapped fields are below
		$xml=array();
	
		$xml['Record_ID']=$row['crpnum'];
		$xml['Last_Name']=$row['crlname'];
		$xml['First_Name']=$row['crfname'];
	
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
		echo $xmldata;
	//	return(writeFile($bnum, $pnum, $crid, $xmldata)); 
		$insertquery = "
			INSERT INTO ptos_interface (xmlbumcode, xmlcrid, xmlpnum, xmldatatype, xmlstatus, xmlstring)
			VALUES('$bnum', '$crid', '$pnum', 'I', 'NEW', '$xmldata')
			";
		if($insertresult = mysqli_query($dbhandle,$insertquery)) 
			return(TRUE); 
		else 
			error("999","Interface INSERT error. $insertquery<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
	return(FALSE);
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
?>