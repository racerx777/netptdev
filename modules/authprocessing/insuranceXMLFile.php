<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 

$selected=0;
$processed=0;
$errors=0;

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

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
dump("crid",$crid);
if($selectresult = mysqli_query($dbhandle,$selectquery)) {
	$selected = mysqli_num_rows($selectresult);
	while($row = mysqli_fetch_assoc($selectresult)) {
		$bnum=$row['cmbnum'];
		$pnum=$row['crpnum'];
		if(caseExportXML($bnum, $pnum, $crid, $row)) {
			notify('000', "XML exported...");
		}
		else 
			error('022', "XML Error creating XML file $xmlfilename. Case $crid will NOT be updated in PTOS.");
	} // while
}
else
	error('004', "SELECT error. $selectquery<br>".mysqli_error($dbhandle));

//mysqli_close($dbhandle);
//notify("000", "Export Summary: selected: $selected, processed:$processed, errors:$errors.");

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

dump("insertquery",$insertquery);
return(TRUE);

	if($insertresult = mysqli_query($dbhandle,$insertquery)) 
		return(TRUE); 
	else 
		error("999","Interface INSERT error. $insertquery<br>".mysqli_error($dbhandle));
	return(FALSE);
}



function writeFile($bnum, $pnum, $crid, $stringData){
	if($bnum=='WS' || $bnum=='NET') {
		if(!empty($pnum)) {
			if(!empty($stringData)) {
				$filebnum=strtolower($bnum);
				$myFileName=$pnum.'_I_'.$crid.'.xml.exp';
				$myFile=$_SERVER['DOCUMENT_ROOT'] . '/collections/'.$filebnum.'/'.$myFileName;
				if($fh = fopen($myFile, 'w')){
					if($fw = fwrite($fh, $stringData)) {
						if($fc = fclose($fh)) {
							return($myFileName);
						}
						else
							error("999","Interface writeXML fclose error. $bnum $pnum");
					}
					else
						error("999","Interface writeXML fwrite error. $bnum $pnum");
				}
				else
					error("999","Interface writeXML fopen error. $bnum $pnum");
			}
			else
				error("999","Interface writeXML stringData error. $bnum $pnum");
		}
		else
			error("999","Interface writeXML pnum error. $bnum $pnum");
	}
	else
		error("999","Interface writeXML bnum error. $bnum $pnum");
	return(FALSE);
}

function ftpXML($bnum, $pnum, $crid, $filename){
	if($bnum=='WS' || $bnum=='NET') {
		if(!empty($pnum)) {
			if(!empty($filename)) {
//				$host = 'lgb-static-208.57.67.221.mpowercom.net';
				$host = '208.57.67.221';
				$port = "31434";
				$usr = 'Administrator';
				$pwd = 'apmiWeststar';

//				$host = 'ftp.mozilla.org';
//				$port = 21;
//				$usr = 'anonymous';
//				$pwd = 'user@domain.com';

//				$local_file = $filename;
				$filebnum=strtolower($bnum);
				$local_file = $_SERVER['DOCUMENT_ROOT'] . '/collections/'.$filebnum.'/'.$filename;
//				$ftp_path = 'example.txt';
				$ftp_path = $filename;

				$conn_id = ftp_connect($host, $port);
				if($conn_id != FALSE) {
 					if(ftp_login($conn_id, $usr, $pwd)) {
// 						ftp_pasv ($conn_id, true);
						if($upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_ASCII)) {
							print (!$upload) ? 'Cannot upload' : 'Upload complete';
							print "\n";
							if(ftp_close($conn_id)) {
								return($filename);
							}
							else
								error("999","Interface ftpXML ftp_close error. $bnum $pnum $filename");
						}
						else
							error("999","Interface ftpXML ftp_put error. $bnum $pnum $filename $conn_id $ftp_path $local_file");

					}
					else
						error("999","Interface ftpXML ftp_login error. $bnum $pnum $filename");
				}
				else
					error("999","Interface ftpXML ftp_connect error. $bnum $pnum $filename");
			}
			else
				error("999","Interface ftpXML filename error. $bnum $pnum $filename");
		}
		else
			error("999","Interface ftpXML pnum error. $bnum $pnum $filename");
	}
	else
		error("999","Interface ftpXML bnum error. $bnum $pnum $filename");
	return(FALSE);
}
?>