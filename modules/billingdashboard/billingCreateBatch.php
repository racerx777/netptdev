<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
errorclear();
// Connect to database
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function lockTreatmentHeaderRecords($cnum=NULL, $pnum=NULL) {
	// Lock all billable "in billing treatments" as 530
	$msg="Locking records ";
	if(!empty($cnum)) {
		$cnumjoin="and thcnum=tbdthcnum";
		$cnumwhere="and thcnum='$cnum'";
		$msg .= " cnum: $cnum";
	}
	else
		$cnumwhere="and thcnum IS NOT NULL and thcnum <>''";

	if(!empty($pnum)) {
		$pnumjoin="and thpnum=tbdthpnum";
		$pnumwhere="and thpnum='$pnum'";
		$msg .= " pnum: $pnum";
	}
	else
		$pnumwhere="and thpnum IS NOT NULL and thpnum <>''";

	notify("000",$msg);
	$upddate=date("Y-m-d H:i:s");
//	$query  = "
//		UPDATE treatment_header
//		LEFT JOIN treatment_billing_detail
//		ON thid=tbdthid and thsbmstatus between '500' and '529' and tbdstatuscode NOT IN ('CAN', 'ERR') $cnumjoin $pnumjoin
//		SET thsbmstatus='530', treatment_header.upduser='BATCH', treatment_header.upddate='$upddate'
//		WHERE  1=1 $cnumwhere $pnumwhere and tbdid IS NULL
//	";
	$query  = "
		UPDATE treatment_header
		SET thsbmstatus='530', treatment_header.upduser='BATCH', treatment_header.upddate='$upddate'
		WHERE thsbmstatus between '500' and '529' $cnumwhere $pnumwhere
	";
	if($result=mysqli_query($dbhandle,$query)) {
//		$query  = "
//			SELECT thid, tbdthid
//			FROM treatment_header
//			LEFT JOIN treatment_billing_detail
//			ON thid=tbdthid
//			WHERE thsbmstatus = '530' $cnumwhere $pnumwhere and tbdthid IS NULL
//		";
		$query  = "
			SELECT thid
			FROM treatment_header
			WHERE thsbmstatus = '530' $cnumwhere $pnumwhere
		";
		if($result=mysqli_query($dbhandle,$query)) {
			$numRows=mysqli_num_rows($result);
			if($numRows > 0)
				return($numRows);
			else
				return(FALSE);
		}
	}
	else {
		error("001","lockTreatmentHeaderRecords UPDATE Error.<br>$query<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
}

function lockTreatmentHeaderRecordsNew($cnum=NULL, $pnum=NULL) {
	if($result510=lockTreatmentHeaderRecords510($cnum, $pnum)) {
		notify("000","Eligible transactions with valid PNUM: $result510");
		if($result520=lockTreatmentHeaderRecords520($cnum, $pnum)) {
			notify("000","Eligible transactions with valid PNUM IN CNUM: $result520");
			if($result530=lockTreatmentHeaderRecords530($cnum, $pnum)) {
				notify("000","Eligible transactions with valid PNUM IN CNUM with valid acctype: $result530");
				return($result530);
			}
		}
	}
	return(FALSE);
}

function lockTreatmentHeaderRecords510($cnum=NULL, $pnum=NULL) {
	// Lock all 500 "in billing treatments" as 510 "Has Valid Pnum"
	notify("000","Processing cnum:$cnum and pnum:$pnum");
	if(!empty($cnum))
		$cnumwhere="and thcnum='$cnum'";
	if(!empty($pnum))
		$pnumwhere="and thpnum='$pnum'";
	$query  = "
		UPDATE treatment_header
		JOIN ptos_pnums
		ON thpnum=pnum
		SET thsbmstatus='510'
		WHERE thsbmstatus between '500' and '699' and thpnum IS NOT NULL and thpnum <>'' $cnumwhere $pnumwhere
	";
	if($result=mysqli_query($dbhandle,$query)) {
		if(mysql_info())
			return(TRUE);
	}
	else {
		error("001","lockTreatmentHeaderRecords UPDATE Error.<br>$query<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
}

function lockTreatmentHeaderRecords520($cnum=NULL, $pnum=NULL) {
	// Lock all 510 "in billing treatments" as 520 "Has Valid Pnum and Matching Cnum"
	if(!empty($cnum))
		$cnumwhere="and thcnum='$cnum'";
	if(!empty($pnum))
		$pnumwhere="and thpnum='$pnum'";
	$query  = "
		UPDATE treatment_header
		JOIN ptos_pnums
		ON thpnum=pnum and thcnum=cnum
		SET thsbmstatus='520'
		WHERE thsbmstatus='510' $cnumwhere $pnumwhere
	";
	if($result=mysqli_query($dbhandle,$query))
		return(mysql_affected_rows());
	else {
		error("001","lockTreatmentHeaderRecords UPDATE Error.<br>$query<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
}

function lockTreatmentHeaderRecords530($cnum=NULL, $pnum=NULL) {
	// Lock all 520 "in billing treatments" as 530 "Has Valid Pnum, matching Cnum and an Account Type"
	if(!empty($cnum))
		$cnumwhere="and thcnum='$cnum'";
	if(!empty($pnum))
		$pnumwhere="and thpnum='$pnum'";
	$query  = "
		UPDATE treatment_header
		JOIN ptos_pnums
		ON thpnum=pnum and thcnum=cnum
		SET thsbmstatus='530'
		WHERE thsbmstatus='520' and acctype IN ('15','16','17','18','19','3' ,'31','4','5','51','52','53','54','55','57','6','61','62','64','7' ,'75','76','77','78','8','9','92','98','FC') $cnumwhere $pnumwhere
	";
	if($result=mysqli_query($dbhandle,$query))
		return(mysql_affected_rows());
	else {
		error("001","lockTreatmentHeaderRecords UPDATE Error.<br>$query<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
}

function selectTreatmentHeaderRecords() {
	// Select all 550 "Select for automated billing"
	$query  = "
		SELECT thid, thcnum, thpnum, thlname, thfname, thctmcode, thvtmcode, thttmcode, thdate
		FROM treatment_header
		WHERE thsbmstatus='530'
	";
	if($result=mysqli_query($dbhandle,$query))
		return($result);
	else {
		error("001","selectTreatmentHeaderRecords SELECT Error.<br>$query<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
}

function writeBatchHeaderRecord($batchdate) {
// Write New Batch Header Record
	$auditfields = getauditfields();
	$audituser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
	$auditdate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
	$auditprog = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
	$query  = "
		INSERT into treatment_billing_header
		SET tbhdate='$batchdate', tbhstatuscode='NEW', tbhtotalcount='$numSelectRows', tbhcancelcount='0', tbherrorcount='0', crtdate=$auditdate, crtuser=$audituser, crtprog=$auditprog
		";
	if($result = mysqli_query($dbhandle,$query))
		return(mysql_insert_id());
	else {
		error("001","writeBatchHeaderRecord INSERT Error.<br>$query<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
}

function selectTreatmentProcedureRecords($thid) {
	$query  = "
		SELECT pmcode
		FROM treatment_procedures
		WHERE thid='$thid'
		";
	if($result=mysqli_query($dbhandle,$query))
		return($result);
	else {
		error("001","selectTreatmentProcedureRecords SELECT Error.<br>$query<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
}

function selectTreatmentModalityRecords($thid) {
	$query  = "
		SELECT mmcode
		FROM treatment_modalities
		WHERE thid='$thid'
		";
	if($result=mysqli_query($dbhandle,$query))
		return($result);
	else {
		error("001","selectTreatmentModalityRecords SELECT Error.<br>$query<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
}

function getPcsm($casetype) {
	$query="
		SELECT ctmpcsmcode
		FROM master_casetypes
		WHERE ctmcode='$casetype'
	";
	if($result=mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_assoc($result))
			return($row['ctmpcsmcode']);
		else
			return(FALSE);
	}
}

function getPcscm($pcsm, $code, $therapytype) {
	$pcscmcodes=array();
	$query="
		SELECT pcscmcode, pcscmbillingcode, pcscmbillingdescription
		FROM master_proceduralcodingsystems_codes
		WHERE pcscmname='$pcsm' and pcscmcode='$code' and pcscmvtmcode in ('', '$therapytype')
		ORDER BY pcscmseq
	";
	if($result=mysqli_query($dbhandle,$query)) {
		return($result);
	}
	else {
		error("001","$thid getPcscm Row Failed.");
		return(FALSE);
	}
}

function getTherapistFromClinicAndTherapytype($trancnum, $therapytype, $tranid) {
	$query="
		SELECT cttherap
		FROM master_clinics_therapists
		WHERE ctcnum='$trancnum' and ctttmcode='$therapytype'
		ORDER BY ctcnum, ctttmcode
	";
	if($result=mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_assoc($result)) {
			$therapist1 = $row['cttherap'];
			if(mysqli_num_rows($result) == 1)
				return($therapist1);
			else {
				$query="
					SELECT cttherap
					FROM master_clinics_therapists
					WHERE ctcnum='$trancnum' and cttherap='$trancnum'
					ORDER BY ctcnum, cttherap
				";
				if($result=mysqli_query($dbhandle,$query)) {
					if($row=mysqli_fetch_assoc($result)) {
						$therapist2 = $row['cttherap'];
						if(mysqli_num_rows($result) == 1)
							return($therapist2);
						else
							return($therapist1);
					} else if ($trancnum == '06') {
                        return 'SW';
                    } else if ($trancnum == '07') {
                        return 'AM';
                    } else {
						error("998", "(FETCH) Could not fetch therapist for clinic:$trancnum therapytype:$therapytype transid: $tranid<br>$query<br>".mysqli_error($dbhandle));
                    }
				}
				else
					error("997", "(SELECT) Could not select therapist for clinic:$trancnum therapytype:$therapytype transid: $tranid<br>$query<br>".mysqli_error($dbhandle));
			}
		}
		else
			error("996", "(FETCH) Could not fetch therapist for clinic:$trancnum therapytype:$therapytype transid: $tranid<br>$query<br>".mysqli_error($dbhandle));
	}
	else
		error("995", "(SELECT) Could not select therapist for clinic:$trancnum therapytype:$therapytype transid: $tranid<br>$query<br>".mysqli_error($dbhandle));
	return(FALSE);
}

function writeTreatmentBillingDetailRecord($batchid, $batchdate, $tranid, $transtatuscode, $trandate, $trancnum, $tranpnum, $tranlname, $tranfname, $trancrid, $trancode, $trandesc, $tranunits, $tranduration, $trantherapist) {
// Get Business Unit
	$bumquery="
		SELECT pgmbumcode
		FROM master_clinics
		JOIN master_provider_groups
		ON cmpgmcode = pgmcode
		WHERE cmcnum='$trancnum' and pgmbumcode IN ('WS', 'NET')
	";
	if($bumresult=mysqli_query($dbhandle,$bumquery)) {
		if($bumrow=mysqli_fetch_assoc($bumresult)) {
			$tranbumcode=$bumrow['pgmbumcode'];
			$batchid=mysqli_real_escape_string($dbhandle,$batchid);
			$batchdate=mysqli_real_escape_string($dbhandle,$batchdate);
			$tranid=mysqli_real_escape_string($dbhandle,$tranid);
			$transtatuscode=mysqli_real_escape_string($dbhandle,$transtatuscode);
			$trandate=mysqli_real_escape_string($dbhandle,$trandate);
			$tranbumcode=mysqli_real_escape_string($dbhandle,$tranbumcode);
			$trancnum=mysqli_real_escape_string($dbhandle,$trancnum);
			$tranpnum=mysqli_real_escape_string($dbhandle,$tranpnum);
			$tranlname=mysqli_real_escape_string($dbhandle,$tranlname);
			$tranfname=mysqli_real_escape_string($dbhandle,$tranfname);
			$trancrid=mysqli_real_escape_string($dbhandle,$trancrid);
			$trancode=mysqli_real_escape_string($dbhandle,$trancode);
			$trandesc=mysqli_real_escape_string($dbhandle,$trandesc);
			$tranunits=mysqli_real_escape_string($dbhandle,$tranunits);
			$tranduration=mysqli_real_escape_string($dbhandle,$tranduration);
			$trantherapist=mysqli_real_escape_string($dbhandle,$trantherapist);
			$query="
				INSERT INTO treatment_billing_detail
				SET tbdtbhid='$batchid', tbddate='$batchdate', tbdthid='$tranid', tbdstatuscode='$transtatuscode', tbdthdate='$trandate', tbdbumcode='$tranbumcode', tbdthcnum='$trancnum', tbdthpnum='$tranpnum', tbdthlname='$tranlname', tbdthfname='$tranfname', tbdthcrid='$trancrid', tbdcode='$trancode', tbddesc='$trandesc', tbdunits='$tranunits', tbdduration='$tranduration', tbdtherap='$trantherapist'
			";
			if($result=mysqli_query($dbhandle,$query))
				return(mysql_insert_id());
			else
				error("003","$tranid writeTreatmentBillingDetailRecord Row Failed.<br>$query<br>".mysqli_error($dbhandle));
		}
		else
			error("002","$tranid writeTreatmentBillingDetailRecord FETCH business unit Failed.<br>$bumquery<br>".mysqli_error($dbhandle));
	}
	else
		error("001","$tranid writeTreatmentBillingDetailRecord SELECT business unit Failed.<br>$bumquery<br>".mysqli_error($dbhandle));
	return(FALSE);
}

function processTreatmentProcedureRow($casetype, $batchid, $batchdate, $tranid, $therapytype, $trandate, $trancnum, $tranpnum, $tranlname, $tranfname, $trancrid, $trantherap, $treatmentprocedurerow) {
	$success=0;
	$fail=0;
	if($pcsm=getPcsm($casetype)) {
		if($pcscm=getPcscm($pcsm, $treatmentprocedurerow['pmcode'], $therapytype)) {
			while($row=mysqli_fetch_assoc($pcscm)) {
				$transtatuscode='NEW';
				$trancode=$row['pcscmbillingcode'];
				$trandesc=$row['pcscmbillingdescription'];
				$tranunits=1;
				$tranduration="";
				if(writeTreatmentBillingDetailRecord($batchid, $batchdate, $tranid, $transtatuscode, $trandate, $trancnum, $tranpnum, $tranlname, $tranfname, $trancrid, $trancode, $trandesc, $tranunits, $tranduration, $trantherap))
					$success++;
				else
					$fail++;
			}
			if(empty($fail))
				return(TRUE);
		}
		else
			error("002","$tranid Procedure Row Failed. $pcsm Procedure Code Missing");
	}
	else
		error("001","$tranid Procedure Row Failed. Procedural Coding System missing.");
	return(FALSE);
}

function processTreatmentModalityRow($casetype, $batchid, $batchdate, $tranid, $therapytype, $trandate, $trancnum, $tranpnum, $tranlname, $tranfname, $trantherap, $treatmentmodalityrow) {
	$success=0;
	$fail=0;
	if($pcsm=getPcsm($casetype)) {
		if($pcscm=getPcscm($pcsm, $treatmentmodalityrow['mmcode'], $therapytype)) {
			while($row=mysqli_fetch_assoc($pcscm)) {
				$transtatuscode='NEW';
				$trancode=$row['pcscmbillingcode'];
				$trandesc=$row['pcscmbillingdescription'];
				$tranunits=1;
				$tranduration="";
				if(writeTreatmentBillingDetailRecord($batchid, $batchdate, $tranid, $transtatuscode, $trandate, $trancnum, $tranpnum, $tranlname, $tranfname, $trancrid, $trancode, $trandesc, $tranunits, $tranduration, $trantherap))
					$success++;
				else
					$fail++;
			}
			if(empty($fail))
				return(TRUE);
		}
		else
			error("002","$thid Modality Row Failed.");
	}
	else
		error("001","$thid Modaliy Row Failed.");
	return(FALSE);
}

function processTreatmentHeaderRow($batchid, $batchdate, $row) {
// Determine the Case Type, Visit Type and Treatment Type
	$errors=0;
	$casetype=$row['thctmcode'];
	$tranid=$row['thid'];
	$trandate=$row['thdate'];
	$tranpnum=$row['thpnum'];
	$tranlname=$row['thlname'];
	$tranfname=$row['thfname'];
	$trancrid=$row['thcrid'];
	$trancnum=$row['thcnum'];
	$therapytype=$row['thttmcode'];
// until therapist is supported for entry select the first therapist for the clinic and pt type
//	$trantherap=$row['thcnum'];
//	save error count
	$processTreatmentHeaderRowError=errorcount();
	if($trantherap=getTherapistFromClinicAndTherapytype($trancnum, $therapytype, $tranid)) {

	// Process Procedures
		if($treatmentprocedureresult = selectTreatmentProcedureRecords($tranid)) {
			$numrowstreatmentprocedure = mysqli_num_rows($treatmentprocedureresult);
			while($treatmentprocedurerow=mysqli_fetch_assoc($treatmentprocedureresult)) { // process each treatment procedure record
				if($treatmentprocedurerowresult=processTreatmentProcedureRow($casetype, $batchid, $batchdate, $tranid, $therapytype, $trandate, $trancnum, $tranpnum, $tranlname, $tranfname, $trancrid, $trantherap, $treatmentprocedurerow)) {
					$success++;
				}
				else {
					// remove from batch? reset status?
					$errors++;
					error("002","$tranid Procedure Failed.");
				}
			}
	// output number processed in while
		}
		else
			error("001","$tranid Procedure Failed.");
	// Process Modalities
		if($treatmentmodalityresult = selectTreatmentModalityRecords($tranid)) {
			$numrowstreatmentmodality = mysqli_num_rows($treatmentmodalityresult);
			while($treatmentmodalityrow=mysqli_fetch_assoc($treatmentmodalityresult)) { // process each treatment modality record
				if($treatmentmodalityrowresult=processTreatmentModalityRow($casetype, $batchid, $batchdate, $tranid, $therapytype, $trandate, $trancnum, $tranpnum, $tranlname, $tranfname, $trantherap, $treatmentmodalityrow)) {
					$success++;
				}
				else {
					// remove from batch?
					$errors++;
					error("002","$tranid Modality Failed.");
				}
			}
	// output number processed in while
		}
		else
			error("001","$tranid Modality Failed.");
	}
	else {
		error("003","$tranid Clinic/Therapist Failed.");
	}
// Test Fail always
	if(errorcount()==$processTreatmentHeaderRowError)
		return(TRUE);
	else {
		displaysitemessages();
		return(FALSE);
	}
}

function updateTreatmentHeaderSuccess($batchheaderid, $thid) {
// Update Status of all transactions that were written to the Billing Detail File for this thid
	$query  = "
		UPDATE treatment_billing_detail
		SET tbdstatuscode='OPN'
		WHERE tbdtbhid='$batchheaderid' and tbdthid='$thid'
	";
	if($result = mysqli_query($dbhandle,$query)) {
// Update Status of header that was written to the Billing Header File for this thid
		$query  = "
			UPDATE treatment_billing_header
			SET tbhstatuscode='OPN', tbhgoodcount=tbhgoodcount+1
			WHERE tbhid='$batchheaderid'
		";
		if($result = mysqli_query($dbhandle,$query)) {
// Update the Treatment Header Status to 560 Created Automated Billing Record
			$query  = "
				UPDATE treatment_header
				SET thsbmstatus='710'
				WHERE thsbmstatus='530' and thid='$thid'
			";
			if($result = mysqli_query($dbhandle,$query))
				return(TRUE);
			else {
				error("003","updateTreatmentHeaderSuccess UPDATE Error.<br>$query<br>".mysqli_error($dbhandle));
				return(FALSE);
			}
		}
		else {
			error("002","updateTreatmentHeaderSuccess UPDATE Error.<br>$query<br>".mysqli_error($dbhandle));
			return(FALSE);
		}
	}
	else {
		error("001","updateTreatmentHeaderSuccess UPDATE Error.<br>$query<br>".mysqli_error($dbhandle));
		return(FALSE);
	}
}

function updateTreatmentHeaderFail($batchheaderid, $thid) {
// Cancel all transactions that were written to the Billing Detail File for this thid
	$query  = "
		UPDATE treatment_billing_detail
		SET tbdstatuscode='ERR'
		WHERE tbdtbhid='$batchheaderid' and tbdthid='$thid'
	";
	if($result = mysqli_query($dbhandle,$query)) {
// Increment the number of errors in the batch header
//			SET tbhstatuscode='ERR', tbherrorcount=tbherrorcount+1
		$query  = "
			UPDATE treatment_billing_header
			SET tbherrorcount=tbherrorcount+1
			WHERE tbhid='$batchheaderid'
			";
		if($result = mysqli_query($dbhandle,$query)) {
// Reset the Treatment Header Status to 540 Error Creating Automated Billing
			$query  = "
				UPDATE treatment_header
				SET thsbmstatus='510'
				WHERE thid='$thid'
			";
			if($result = mysqli_query($dbhandle,$query))
				return(TRUE);
			else {
				error("003","updateTreatmentHeaderFail UPDATE Error.<br>$query<br>".mysqli_error($dbhandle));
				return(FALSE);
			}
		}
		else {
			error("002","updateTreatmentHeaderFail UPDATE Error.<br>$query<br>".mysqli_error($dbhandle));
			return(FALSE);
		}
	}
	else {
		error("001","updateTreatmentHeaderFail UPDATE Error.<br>$query<br>".mysqli_error($dbhandle));
		return(FALSE);
	}

}

//
// MAIN LOOP
//
if(!empty($_REQUEST['pnum']))
	$pnum=$_REQUEST['pnum'];
else
	$pnum=NULL;
if(!empty($_REQUEST['cnum']))
	$cnum=$_REQUEST['cnum'];
else
	$cnum=NULL;
$success=0;
$fail=0;
unset($numrowstreatmentheaderlocked);
unset($treatmentheaderresult);
unset($numrowstreatmentheader);
unset($batchid);
//$cnum='08';
if($numrowstreatmentheaderlocked=lockTreatmentHeaderRecords($cnum, $pnum)) {
	notify("000","$numrowstreatmentheaderlocked treatment header records locked for automated billing.");
	if($treatmentheaderresult = selectTreatmentHeaderRecords()) {
		$numrowstreatmentheader = mysqli_num_rows($treatmentheaderresult);
		notify("000","$numrowstreatmentheader selected for automated billing.");
		$batchdate=date("Y-m-d H:i:s",time());
		if($batchid=writeBatchHeaderRecord($batchdate)) {
			notify("000","Billing batch $batchid created $batchdate.");
			while($treatmentheaderrow=mysqli_fetch_assoc($treatmentheaderresult)) { // process each treatment header record
				$tranid=$treatmentheaderrow['thid'];
				notify("000","Processing $tranid...");
				if($treatmentheaderrowresult=processTreatmentHeaderRow($batchid, $batchdate, $treatmentheaderrow)) {
					notify("000","Success $tranid. :)");
					$success++;
					updateTreatmentHeaderSuccess($batchid, $tranid); // update billing header, and update treatment header record
				}
				else {
					// remove from batch? reset status?
					notify("000","Failed $tranid. :(");
					$fail++;
					updateTreatmentHeaderFail($batchid, $tranid); // cancel billing detail, update billing header, unlock treatment header record
					error("004","main $treatmentheaderrowresult treatment header row result.<br>".mysqli_error($dbhandle));
//					displaysitemessages();
				}
			}
// output while processing results here
		}
		else {
			error("003","main $batchid batch header result.<br>".mysqli_error($dbhandle));
		}
	}
	else {
		error("002","main $treatmentheaderresult treatment header result.<br>".mysqli_error($dbhandle));
	}
}
else {
	error("001","main: Failed to lock any treatment header records. $numrowstreatmentheaderlocked<br>".mysqli_error($dbhandle));
}

notify("000","$success Headers successfully processed.");
if(!empty($fail))
	error("005","main $fail Headers failed to process.");

displaysitemessages();
?>