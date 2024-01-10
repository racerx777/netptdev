<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23); 
errorclear();
$selected=0;
$processed=0;
$errors=0;
notify("000","BEGIN PROCESSING.");
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function billingExportCleanString($string) {
	$cleanvalue=mysqli_real_escape_string($dbhandle,$string);
// Remove the ampersands
	$cleanvalue=str_replace(" & "," and ", $cleanvalue);
	$cleanvalue=str_replace("& ","and ", $cleanvalue);
	$cleanvalue=str_replace(" &"," and", $cleanvalue);
	$cleanvalue=str_replace("&","and", $cleanvalue);
	return($cleanvalue);
}

function billingExportUpdateRow($crid, $updatemsg, &$row) {
	foreach($row as $field=>$value) {
		$cleanvalue = billingExportCleanString($value);
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

function billingExportXMLDetail($row) {
	if(!empty($row['tbdthpnum'])) {

	// Write out an xml file using the ptos number as file name
		$bumcode=trim($row['tbdbumcode']);
		$crid=trim($row['tbdcrid']); //
		$thid=trim($row['tbdthid']);
		$tbhid=trim($row['tbdtbhid']);
		$tbdid=trim($row['tbdid']);
		$pnum=trim($row['tbdthpnum']);
		$lname=mysqli_real_escape_string($dbhandle,trim($row['tbdthlname']));
		$fname=mysqli_real_escape_string($dbhandle,trim($row['tbdthfname']));
		$xmlid=trim($row['tbdxmlid']);	//
		$xml=array();
		$xml['Patient']=$pnum;
		$xml['Date']=displayDate($row['tbdthdate']);
		$xml['Therapist']=$row['tbdtherap'];
		$xml['RevCode']="";
		$xml['RevDesc']="";
		$xml['CPTCode']=substr($row['tbdcode'],0,5);
		$xml['CPTDesc']=$row['tbdcode'] . '     ' . $row['tbddesc'];
		$xml['Modifier']=substr($row['tbdcode'],5,4);
		$xml['Units']=$row['tbdunits'];
		$xml['Duration']=$row['tbdduration'];
		foreach($xml as $field=>$value) {
			$cleanxml["$field"]=billingExportCleanString($value);
		}
		$xmldata="";
		$xmldata = "<Charge>\n";
		foreach($cleanxml as $tag=>$val) 
			$xmldata.= "<$tag>$val</$tag>\n";
		$xmldata .= "</Charge>\n";
		$xmldata = mysqli_real_escape_string($dbhandle,$xmldata);
		$auditfields=getauditfields();
		$upddate=$auditfields['date'];
		$insertquery = "
			INSERT INTO ptos_interface_transaction_detail (txmlbumcode, txmlcrid, txmlthid, txmltbhid, txmltbdid, txmlpnum, txmllname, txmlfname, txmldatatype, txmlstatus, txmlxmlid, txmlstring, upddate)
			VALUES('$bumcode', '$crid', '$thid', '$tbhid', '$tbdid', '$pnum', '$lname', '$fname', 'T', 'NEW', '$xmlid', '$xmldata', '$upddate')
			";
		if($insertresult = mysqli_query($dbhandle,$insertquery)) 
			return(TRUE); 
		else 
			error("999","Interface INSERT error.<br>$insertquery<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
	return(FALSE);
}

function billingExportXML2() {
	$auditfields = getAuditFields();
	$mydate = $auditfields['date'];
	$myuser = $auditfields['user'];
	$myprog = $auditfields['prog'];
// Read through ptos_interface_transaction_detail grouping XML 
	$select = "
		SELECT * 
		FROM ptos_interface_transaction_detail 
		WHERE txmlstatus='NEW' 
		ORDER BY txmlbumcode, txmlpnum, txmlthid
	";
	if($result=mysqli_query($dbhandle,$select)) {
		$saved=array();
		$xmlstring=array();
		$auditfields=getauditfields();
		$upddate=$auditfields['date'];

// Add patient billing counter Changed 05/31/2012
// Needed to provide a patient counter for Nancy
$uniquebnums=array();
$uniquepatientok=array();
$uniquepatienterr=array();
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^ Changed 05/31/2012

		while($row=mysqli_fetch_assoc($result)) {

// Add patient billing counter Changed 05/31/2012
unset($rowbnum);
unset($rowpnum);
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^ Changed 05/31/2012
			$txmlid=$row['txmlid'];
// Summarize by BUM, CNUM, PNUM
			if($row['txmlbumcode'] != $saved['txmlbumcode'] || $row['txmlcnum'] != $saved['txmlcnum'] || $row['txmltbhid'] != $saved['txmltbhid'] || $row['txmlpnum'] != $saved['txmlpnum']) {
// New/Change Patient
				if(count($saved)!=0) { 
					unset($xmlid);
					$bumcode=$saved['txmlbumcode'];
					$cnum=$saved['txmlcnum'];
					$pnum=$saved['txmlpnum'];
					$tbhid=$saved['txmltbhid'];
					$lname=$saved['txmllname'];
					$fname=$saved['txmlfname'];
					$crid=NULL;
					$thid=NULL;
					$insertquery = "
						INSERT INTO ptos_interface (xmlbumcode, xmlcrid, xmlthid, xmltbhid, xmlpnum, xmldatatype, xmlstatus, xmlstring, upddate)
						VALUES('$bumcode', NULL, NULL, '$tbhid', '$pnum', 'T', NULL, NULL, '$upddate')
						";
					if($insertresult=mysqli_query($dbhandle,$insertquery)) {
						$xmlid=mysql_insert_id();
						$xmldata = '<?xml version="1.0"?>
<PTOSImport>
<Patient>
	<Record_ID>'.$pnum.'</Record_ID>
	<Last_Name>' . mysqli_real_escape_string($dbhandle,$lname) . '</Last_Name>
	<First_Name>' . mysqli_real_escape_string($dbhandle,$fname) . '</First_Name>
</Patient>
';
						$xmldata .= implode("\n",$xmlstring);
						$xmldata .= "\n</PTOSImport>";
						$updatequery = "
							UPDATE ptos_interface SET xmlstatus='NEW', xmlstring='$xmldata' WHERE xmlid='$xmlid'
							";
						if($updateresult=mysqli_query($dbhandle,$updatequery)) {
							$pupdated++;
							notify("000","$pnum written to interface table.");
						}
						else {
							$errors++;
							error("999","Error Updating ptos_interface record. txmlid=$txmlid<br>$updatequery<br>".mysqli_error($dbhandle));
						}
					}
					else {
						$errors++;
						error("999","Error Inserting ptos_interface record. xmlid='$xmlid' txmlid='$txmlid'<br>$insertquery<br>".mysqli_error($dbhandle));
					}
				}
// Save New Values / First Pass
				$saved['txmlbumcode'] = $row['txmlbumcode'];
				$saved['txmlcnum'] = $row['txmlcnum'];
				$saved['txmltbhid']=$row['txmltbhid'];
				$saved['txmlpnum'] = $row['txmlpnum'];
				$saved['txmllname'] = $row['txmllname'];
				$saved['txmlfname'] = $row['txmlfname'];
// Clear XML data
				$xmlstring=array();
			}

// Add patient billing counter Changed 05/31/2012
$rowbnum=$row['txmlbumcode'];
$rowpnum=$row['txmlpnum'];
$uniquebnums["$rowbnum"]=1;
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^ Changed 05/31/2012

			if(errorcount()==0) {

// Add patient billing counter Changed 05/31/2012
$uniquepatientok["$rowbnum"]["$rowpnum"]=1;
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^ Changed 05/31/2012

				$xmlstring[]=$row['txmlstring'];
				$updatequery = "
					UPDATE ptos_interface_transaction_detail SET txmlstatus='XML' WHERE txmlid='$txmlid'
					";
				if(!$updateresult=mysqli_query($dbhandle,$updatequery))
					error("999","Error Updating ptos_interface_transaction_detail record. txmlid=$txmlid<br>$updatequery<br>".mysqli_error($dbhandle));
			}
			else {

// Add patient billing counter Changed 05/31/2012
$uniquepatienterr["$rowbnum"]["$rowpnum"]=1;
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^ Changed 05/31/2012

			}
		}
// write out last record
// do change patient

// Handle Only/Last New Patient
			if(errorcount()==0) {
				if(count($saved)!=0) { 
					unset($xmlid);
					$bumcode=$saved['txmlbumcode'];
					$tbhid=$saved['txmltbhid'];
					$cnum=$saved['txmlcnum'];
					$pnum=$saved['txmlpnum'];
					$lname=$saved['txmllname'];
					$fname=$saved['txmlfname'];
					$insertquery = "
						INSERT INTO ptos_interface (xmlbumcode, xmlcrid, xmlthid, xmltbhid, xmlpnum, xmldatatype, xmlstatus, xmlstring, upddate)
						VALUES('$bumcode', '$crid', '$thid', '$tbhid', '$pnum', 'T', NULL, NULL, '$upddate')
						";
					if($insertresult=mysqli_query($dbhandle,$insertquery)) {
						$xmlid=mysql_insert_id();
						$xmldata = '<?xml version="1.0"?>
<PTOSImport>
<Patient>
	<Record_ID>'.$pnum.'</Record_ID>
	<Last_Name>' . mysqli_real_escape_string($dbhandle,$lname) . '</Last_Name>
	<First_Name>' . mysqli_real_escape_string($dbhandle,$fname) . '</First_Name>
</Patient>
';
						$xmldata .= implode("\n",$xmlstring);
						$xmldata .= "\n</PTOSImport>";
						$updatequery = "
							UPDATE ptos_interface SET xmlstatus='NEW', xmlstring='$xmldata' WHERE xmlid='$xmlid'
							";
						if($updateresult=mysqli_query($dbhandle,$updatequery)) {
							$pupdated++;
							notify("000","$pnum written to interface table.");
						}
						else {
							$errors++;
							error("999","Error Updating ptos_interface record. txmlid=$txmlid<br>$updatequery<br>".mysqli_error($dbhandle));
						}
					}
					else {
						$errors++;
						error("999","Error Inserting ptos_interface record. xmlid='$xmlid' txmlid='$txmlid'<br>$insertquery<br>".mysqli_error($dbhandle));
					}
				}
			}
	}

// Add patient billing counter Changed 05/31/2012
foreach($uniquebnums as $keybnum=>$valbnum) {
	info("000","$keybnum Patients Billed:".count($uniquepatientok["$keybnum"]));
	info("001","$keybnum Patients Failed:".count($uniquepatienterr["$keybnum"]));
}
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^ Changed 05/31/2012

	if(errorcount()==0)
		return(TRUE);
	else
		return(FALSE);
}

// MAIN
$claimcountok=array();
$claimcounterr=array();
$lockquery = "
	UPDATE treatment_billing_header 
	SET tbhstatuscode='PRC'
	WHERE tbhstatuscode = 'OPN' and tbhgoodcount>0
	";
if($lockresult = mysqli_query($dbhandle,$lockquery)) {
	$headerquery = "
		SELECT tbhid, tbhdate, tbhstatuscode, tbhgoodcount, tbherrorcount, tbhcancelcount, tbhtotalcount 
		FROM treatment_billing_header
		WHERE tbhstatuscode='PRC'
	";
	if($headerresult = mysqli_query($dbhandle,$headerquery)) {
		$selected = mysqli_num_rows($headerresult);
		notify("000","$selected billing header rows selected for processing.");
		while($headerrow=mysqli_fetch_assoc($headerresult)) {
			$tbhid=$headerrow['tbhid'];
			$detailquery = "
				SELECT tbdid, tbdtbhid, tbdthid, tbddate, tbdstatuscode, tbdthdate, tbdbumcode, tbdthcnum, tbdthpnum, tbdthlname, tbdthfname, tbdcode, tbddesc, tbdunits, tbdduration, tbdtherap 
				FROM treatment_billing_detail
				WHERE tbdtbhid='$tbhid' and tbdstatuscode='OPN'
			";
			if($detailresult = mysqli_query($dbhandle,$detailquery)) {
				$detailselected = mysqli_num_rows($detailresult);
				notify("000","Begin processing $detailselected rows in billing detail batch $tbhid.");
				while($detailrow=mysqli_fetch_assoc($detailresult)) {
					$tbdid=$detailrow['tbdid'];
					$updatequery = "
						UPDATE treatment_billing_detail 
						SET tbdstatuscode='PRC', upddate=NOW()
						WHERE tbdid='$tbdid' and tbdstatuscode = 'OPN'
					";
					if($updateresult = mysqli_query($dbhandle,$updatequery)) {
	// validation here
						if(errorcount() == 0) {

							$claimbnum=$detailrow['tbdbumcode'];
							$claimpnum=$detailrow['tbdthpnum'];
							$claimdate=$detailrow['tbdthdate'];
							
							if(billingExportXMLDetail($detailrow)==TRUE) {
								$expupdatequery = "
									UPDATE treatment_billing_detail 
									SET tbdstatuscode='XML', upddate=NOW()
									WHERE tbdid='$tbdid' and tbdstatuscode = 'PRC'
								";
								if($expupdateresult = mysqli_query($dbhandle,$expupdatequery)) {
									$processed++;
									$claimcountok["$claimbnum"]["$claimpnum"]["$claimdate"]=1;
								}
								else {
									$errors++;
									$claimcounterr["$claimbnum"]["$claimpnum"]["$claimdate"]=1;
									error('001', "XML Error updating billing detail record id=$tbdid:<br>$expupdatequery<br>" . mysqli_error($dbhandle));
								}
							}
							else {
								$errors++;
								$errupdatequery = "
									UPDATE treatment_billing_detail 
									SET tbdstatuscode='ERR', upddate=NOW()
									WHERE tbdid='$tbdid' and tbdstatuscode = 'PRC'
								";
								if(!$errupdateresult = mysqli_query($dbhandle,$errupdatequery)) 
									error('001', "ERR Error updating billing detail record id=$tbdid:<br>$errupdatequery<br>" . mysqli_error($dbhandle));
							}
						}
						else {
							$errors++;
							error('003', "ROW validation error. case=$crid.");
						}
					}
					else {
						error("999","UPDATE Billing detail error.<br>$updatequery<br>".mysqli_error($dbhandle));
					}
				}
// post while process
// reorganize data into patient sets
				if(errorcount()==0) {
					if(billingExportXML2()==TRUE) 
						notify("000","XML File reorganized successfully.");
					else
						error("999","XML File NOT reorganized.");
				}
// Update Header
				if(errorcount()==0) {
					$headerupdatequery = "
						UPDATE treatment_billing_header 
						SET tbhstatuscode='XML', upddate=NOW()
						WHERE tbhid='$tbhid' and tbhstatuscode = 'PRC'
						";
					if($headerupdateresult = mysqli_query($dbhandle,$headerupdatequery)) 
						notify("000","Batch $tbhid processed.");
					else
						error("999","Error updating batch $tbhid to XML status.<br>$headerupdatequery<br>".mysqli_error($dbhandle));
				}
				else {
					$headerupdatequery = "
						UPDATE treatment_billing_header 
						SET tbhstatuscode='ERR', upddate=NOW()
						WHERE tbhid='$tbhid' and tbhstatuscode = 'PRC'
						";
					if($headerupdateresult = mysqli_query($dbhandle,$headerupdatequery)) 
						error("999","Batch $tbhid had errors.");
					else
						error("999","Error updating batch $tbhid to ERR status.<br>$headerupdatequery<br>".mysqli_error($dbhandle));
				}
				notify("000","End processing billing detail rows for batch $tbhid.");
			}
			else
				error('004', "SELECT Billing detail error.<br>$detailquery<br>".mysqli_error($dbhandle));
		}
// output while header result?
foreach($claimcountok as $keybnum=>$valbnum) 
	info("000","$keybnum Claims Billed:".count($valbnum));
foreach($claimcounterr as $keybnum=>$valbnum) 
	info("000","$keybnum Claims NOT Billed:".count($valbnum));

	}
	else
		error('005', "SELECT Billing header error.<br>$headerquery<br>".mysqli_error($dbhandle));
}
else
	error('006', "LOCK UPDATE Billing header error.<br>$lockquery<br>".mysqli_error($dbhandle));

foreach($_POST as $key=>$val) 
	unset($_POST[$key]);
//close the connection
mysqli_close($dbhandle);
notify("000", "Export Summary: selected: $selected, processed:$processed, errors:$errors.");
displaysitemessages();
?>