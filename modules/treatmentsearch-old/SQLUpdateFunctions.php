<?php
function treatmentbilltreatment($id) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(23);
	if(!empty($id)) {
		$thquery = "
			SELECT *
			FROM treatment_header
			WHERE thid='$id'
		";
// Get Treatment Record
		if($thresult=mysqli_query($dbhandle,$thquery)) {
			if($throw=mysqli_fetch_assoc($thresult)) {
// Assign and format if necessary
				$thid=$throw['thid'];
				$thcnum=$throw['thcnum'];
				$thdate=$throw['thdate'];
				$thpnum=$throw['thpnum'];
				$thlname=$throw['thlname'];
				$thfname=$throw['thfname'];
				$thctmcode=$throw['thctmcode'];
				$thvtmcode=$throw['thvtmcode'];
				$thttmcode=$throw['thttmcode'];
				$thsbmstatus=$throw['thsbmstatus'];
// Check for blank values
				if(empty($thid))
					error("001","Id empty. NetPT:$thid");

				if(empty($thcnum))
					error("001","Clinic empty. NetPT:$thcnum");

				if(empty($thdate))
					error("001","Treatment Date empty. NetPT:$thdate");

				if(empty($thpnum))
					error("001","Patient Number empty. NetPT:$thpnum");

				if(empty($thlname))
					error("001","Last Name empty. NetPT:$thlname");

				if(empty($thfname))
					error("001","First Name empty. NetPT:$thfname");

				if(empty($thctmcode))
					error("001","Case Type Code empty. NetPT:$thctmcode");

				if(empty($thvtmcode))
					error("001","Visit Type Code empty. NetPT:$thvtmcode");

				if(empty($thttmcode))
					error("001","Treatment Type Code empty. NetPT:$thttmcode");

				if(empty($thsbmstatus))
					error("001","Submit Status Code empty. NetPT:$thsbmstatus");

				if(errorcount()==0) {
					$ptosquery = "
						SELECT *
						FROM ptos_pnums
						WHERE pnum='$thpnum' and cnum='$thcnum'
					";
// Get PTOS_PNUM Record
//					dump("ptosquery", $ptosquery);
					if($ptosresult=mysqli_query($dbhandle,$ptosquery)) {
						if($ptosrow=mysqli_fetch_assoc($ptosresult)) {
// Assign to variables and format if necessary
							$bnum=$ptosrow['bnum'];
							$cnum=$ptosrow['cnum'];
							$pnum=$ptosrow['pnum'];
							$acctype=$ptosrow['acctype'];
							$fvisit=$ptosrow['fvisit'];
							$lvisit=$ptosrow['lvisit'];
							$lname=$ptosrow['lname'];
							$fname=$ptosrow['fname'];
							$sex=$ptosrow['sex'];
							$ssn=$ptosrow['ssn'];
							$birth=$ptosrow['birth'];
							$injury=$ptosrow['injury'];
// Check values/validate
							if($bnum <> 'WS' and $bnum <> 'NET')
								error("001","Invalid Business Unit. PTOS:$bnum");

							else if($cnum <> $thcnum)
								error("001","Clinic Number mismatch. PTOS:$cnum NetPT:$thcnum");

							else if($pnum <> $thpnum)
								error("001","Patient Number mismatch. PTOS:$pnum NetPT:$thpnum");

//							else if($acctype <> $thctmcode)
//								error("001","Account Type mismatch. PTOS:$acctype NetPT:$thacctype");

							else if($fvisit > $thdate)
								error("001","Treatment cannot occur before first visit. PTOS First Visit:$fvisit, NetPT Treatment:$thdate");

//							if($lvisit)
//								error("001","PTOS Last Visit : $lvisit");

							else if(strtolower($lname) <> strtolower($thlname))
								error("001","Last Name mismatch. PTOS:$lname, NetPT:$thlname");

							else if($fname <> $thfname)
								error("001","First Name mismatch. PTOS:$fname, NetPT:$thfname");

//							if($sex)
//								error("001","Sex PTOS: $sex");

//							if($ssn)
//								error("001","SSN PTOS: $ssn");

//							if($birth)
//								error("001","Birth PTOS: $birth");

							else if($injury > $thdate)
								error("001","Treatment cannot occur before injury. PTOS Injury:$injury, NetPT Treatment:$thdate");

							if(errorcount()==0) {
								treatmentupdatestatus($id, '500');
								notify("000","Treatment $id processed.");
							}
							else
								error("001","Treatment $id NOT processed.");
						}
						else
//							error("001", "FETCH PTOS_PNUMS error.<br>$ptosquery<br>".mysqli_error($dbhandle));
							error("001", "Patient Number $thpnum not found in clinic $thcnum in PTOS.");
					}
					else
						error("001", "SELECT PTOS_PNUMS error.<br>$ptosquery<br>".mysqli_error($dbhandle));
				}
				else
					error("001", "Error validating Treatment.<br>$thquery<br>".mysqli_error($dbhandle));
			}
			else
				error("001", "FETCH Treatment error.<br>$thquery<br>".mysqli_error($dbhandle));
		}
		else
			error("001", "SELECT Treatment error.<br>$thquery<br>".mysqli_error($dbhandle));
	}
	else
		error("001", "Missing/Zero Treatment Id.");
}

function treatmentcancelbillingrecords($id) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(23);
	if(isset($id)) {
		// Connect to database
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "UPDATE treatment_billing_detail ";
		$set=array();
		$set[] = "tbdstatuscode='CAN' ";
		$auditfields = getauditfields();
		$set[] = "upddate='" . $auditfields['date'] . "' ";
		$set[] = "upduser='" . $auditfields['user'] . "' ";
		$set[] = "updprog='" . $auditfields['prog'] . "' ";
		$query .= "SET " . implode(', ', $set);
		$query .= "WHERE tbdthid='" . mysqli_real_escape_string($dbhandle,$id) . "'";
		if($result = mysqli_query($dbhandle,$query)) {
			notify("000","Treatment Billing Detail Records cancelled for Treatment Id $id.");
			return(TRUE);
		}
		else
			error("001", "Could not cancel Treatment Billing Detail Records for Treatment Id $id.<br>$query<br>".mysqli_error($dbhandle));
		//close the connection
		mysqli_close($dbhandle);
	}
	else
		error("002", "Error: Missing Treatment Id.");
	return(FALSE);
}

function treatmentrollbackbilling($id) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(23);
	if(isset($id)) {
// Cancel Billing Records
		if(treatmentcancelbillingrecords($id))
// Update Status to UR
			if(treatmentupdatestatus($id, '105'))
				notify("000","Billing Rollback for Treatment $id successful.");
			else
				error("001","Billing Rollback for Treatment $id NOT successful.");
		else
			error("002","Billing Rollback for Treatment $id NOT successful.");
	}
	else
		error("003", "Error: Missing Treatment Id.");
}

function treatmentupdatestatus($id, $newstatus) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	errorclear();
	if(isset($id)) {
		// Connect to database
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "UPDATE treatment_header ";
		$set=array();
		$set[] = "thsbmStatus='" . mysqli_real_escape_string($dbhandle,$newstatus) . "' ";
		if(count($set) > 0)
			$auditfields = getauditfields();
			$set[] = "upddate='" . $auditfields['date'] . "' ";
			$set[] = "upduser='" . $auditfields['user'] . "' ";
			$set[] = "updprog='" . $auditfields['prog'] . "' ";
			$query .= "SET " . implode(', ', $set);
		$query .= "WHERE thid='" . mysqli_real_escape_string($dbhandle,$id) . "'";
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$_SESSION['notify'][] = $numRows . "Record $id Updated.";
			addheaderhistory($id, date('Y-m-d H:i:s', time()), $_SESSION['user']['umuser'], 0, $_SESSION['application'], 'UPDATE', 'Updated Treatment Status ['. $newstatus . ']', $query);
			return(TRUE);
		}
		else
			error("001", mysqli_error($dbhandle));
		//close the connection
		mysqli_close($dbhandle);
	}
	else
		error("002", "Error: Missing Record Id.");
	return(FALSE);
}

function treatmentupdateinactivate($id) {
	treatmentupdatestatus($id, '900');
}
?>