<?php
function patientdelete($id) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(11); 
	errorclear();
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		

		$select = "select palname, pafname from patients where paid='$id'";
		if($selectresult = mysqli_query($dbhandle,$select)) { 
			if($selectrow = mysqli_fetch_assoc($selectresult)) {
				$fname = $selectrow['pafname']; 
				$lname = $selectrow['palname']; 
				$delete = "delete from patients where paid='$id'";
				if($deleteresult = mysqli_query($dbhandle,$delete)) 
					notify("000", "Patient $lname, $fname successfully deleted.");
				else
					error("001", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
			}
			else
				error("002", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		}
		else
			error("003", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("004", "Error: Missing record identifier.");
}

function patientupdateinactivate($id) {
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(11); 
		errorclear();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "UPDATE patients SET painactive = NOT painactive WHERE paid='$id'";
		if($result1 = mysqli_query($dbhandle,$query1)) {
			$query2 = "select palname, pafname, painactive from patients where paid='$id'";
			if($result2 = mysqli_query($dbhandle,$query2)) { 
				if($row2 = mysqli_fetch_assoc($result2)) {
					$fname = $row2['pafname']; 
					$lname = $row2['palname']; 
					if($row2['painactive'] == '1')
						$inactive = "inactive"; 
					else
						$inactive = "active";
					notify("000", "Patient $lname, $fname was made $inactive.");
				}
				else
					error("001", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else
				error("002", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("003", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("004", "Error: Missing Record Id.");
}

function casebuttonaction($csqid, $button) {
	unset($newstatus);
	unset($data);
	unset($cshid);
	$cshid = casecallhistory('INSERT', $csqid, NULL, $_POST['cshdata']);
//dump("cshid",$cshid);
	notify("000","Button pressed: $button");
	switch($button):
		case 'Busy':
		case 'No Answer':
		case 'Ans Mach':
			$newstatus = 'PEN';
			$data = "$button-$newstatus";
			casecallplaced($csqid, $newstatus, $button);
			break;
		case 'Confirm Callback Referral':
			$newstatus = 'PEN';
			$callbackdate = date("Y-m-d H:i:s", strtotime($_POST['csqschcalldate']['date'] . ' ' . $_POST['csqschcalldate']['time']));
			$callbacknumber = $_POST['csqphone'];
			if(empty($_POST['cshdata']))
				$data = "$button-$newstatus $callbacknumber@@$callbackdate";
			else
				$data = $_POST['cshdata'];
			casecallback($csqid, $newstatus, $callbacknumber, $callbackdate);
			break;
		case 'Confirm Schedule Referral':
			$newstatus = "SCH";
			$appointmentdate = date("Y-m-d H:i:s", strtotime($_POST['crfvisitdate'] . ' ' . $_POST['crfvisitdatetime']));
			$clinic = $_POST['crcnum'];
			$data = "$button-$newstatus $clinic@$appointmentdate";
			caseschedule($csqid, $newstatus, $clinic, $appointmentdate);
			break;
		case 'Confirm Cancel Referral':
			$newstatus = "CAN";
			$cancelreasoncode = $_POST['crcancelreasoncode'];
			$data = "$button-$cancelreasoncode";
			casecancel($csqid, $newstatus, $cancelreasoncode);
			break;
	endswitch;
	casecallhistory('UPDATE', $csqid, $newstatus, $data, $cshid);
}

function casecallhistory($mode, $csqid, $newstatus=NULL, $data=NULL, $cshid=NULL) {
// Mode = INSERT or UPDATE
// if MODE == INSERT then csqid is the queue id and $cshid is returned.
// if MODE == UPDATE then $cshid is used to update the call scheduling history entry.
	if(!empty($mode) && !empty($csqid) ) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15);
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$selectquery = "
			SELECT * 
			FROM case_scheduling_queue 
			JOIN cases ON csqcrid=crid 
			WHERE csqid = '$csqid'
			";
// cshid = autonumber - ok
// cshcrid = case referral id - retrieved on first call ok
// csholdcasestatuscode = old case status - retrieved on first call ok
// csholdpriority = old priority code - retrieved on first call ok
// csholdschcalldate = old call date - retrieved on first call ok
// csholdphone = old phone number - retrieved on first call ok
// audit fields
		if($selectresult = mysqli_query($dbhandle,$selectquery)) {
			if($selectrow = mysqli_fetch_assoc($selectresult)) {
				$auditfields = getauditfields();
				$values=array();
				if($mode == 'INSERT') {
					$values['cshcrid'] = $selectrow['crid'];
					$values['csholdcasestatuscode'] = $selectrow['crcasestatuscode'];
					$values['csholdpriority'] = $selectrow['csqpriority'];
					$values['csholdschcalldate'] = $selectrow['csqschcalldate'];
					$values['csholdphone'] = $selectrow['csqphone'];
//					$values['cshnewcasestatuscode'] = "";
//					$values['cshnewpriority'] = "";
//					$values['cshnewschcalldate'] = "";
//					$values['cshnewphone'] = "";
//					$values['cshdata'] = "";
					$values['crtdate'] = $auditfields['date'];
					$values['crtuser'] = $auditfields['user'];
					$values['crtprog'] = $auditfields['prog'];
					foreach($values as $field=>$value) 
						$insertvalues["$field"] = "$field = '" . mysqli_real_escape_string($dbhandle,$value) . "'";
					$insertquery = "INSERT INTO case_scheduling_history SET " . implode(", ", $insertvalues);
					if($insertresult = mysqli_query($dbhandle,$insertquery)) {
// return newly created history record id
						$selectquery2 = "
							SELECT LAST_INSERT_ID() as cshid
							FROM case_scheduling_history
							";
						if($selectresult2 = mysqli_query($dbhandle,$selectquery2)) {
							if($selectrow2 = mysqli_fetch_assoc($selectresult2)) {
								$cshid = $selectrow2['cshid'];
//								notify("021", "Call History $cshid created.");
								return($cshid);
							}
							else
								error("023", "Call History - Error on fetch. $selectquery2");
						}
						else
							error("024", "Call History - Error on select result. $selectquery2");
					}
					else
						error("022", "Call History - Error on insert result. $insertquery");
				}
				else {
// cshnewcasestatuscode = new case status - passed in
// cshnewpriority = new priority code - retrieved on second call
// cshnewschcalldate = new call date - retrieved on second call
// cshnewphone = new phone number - retrieved on second call
// cshdata = passed in related data
					if($mode == 'UPDATE') {
						if(!empty($cshid) && !empty($newstatus)  && !empty($data)) {
							$values['cshnewcasestatuscode'] = $newstatus;
							$values['cshdata'] = $data;
							$values['cshnewpriority'] = $selectrow['csqpriority'];
							$values['cshnewschcalldate'] = $selectrow['csqschcalldate'];
							$values['cshnewphone'] = $selectrow['csqphone'];
							$values['upddate'] = $auditfields['date'];
							$values['upduser'] = $auditfields['user'];
							$values['updprog'] = $auditfields['prog'];
							foreach($values as $field=>$value) 
								$updatevalues["$field"] = "$field = '" . mysqli_real_escape_string($dbhandle,$value) . "'";
							$updatequery = "UPDATE case_scheduling_history SET " . implode(", ", $updatevalues) . " WHERE cshid='$cshid'";
							if($updateresult = mysqli_query($dbhandle,$updatequery)) {
//								notify("011", "Call History $cshid updated.");
								return($cshid);
							}
							else
								error("012", "Call History - Error on update result. $updatequery");
						}
						else
							error("001","Call History ID, status or data not provided for update. $cshid/$newstatus/$data");
					}
					else 
						error("002", "Call History mode invalid $mode.");
				}
			}
			else
				error("003", "Call History - Error select fetch. $selectquery");
		}
		else 
			error("004", "Call History - Error on select result. $selectquery");
	}
	else 
		notify("001", "Missing id, status or data $cshid/$newstatus/$data");
}

function caseschedulinghistoryadd($crid, $data=NULL) {
	if( !empty($crid) && !empty($data) ) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15);
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$selectquery = "select crcasestatuscode from cases where crid='$crid'";
		if($selectresult = mysqli_query($dbhandle,$selectquery)) {
			if($selectrow = mysqli_fetch_assoc($selectresult)) {
				$auditfields = getauditfields();
				$values['cshcrid'] = $crid;
				$values['csholdcasestatuscode'] = $selectrow['crcasestatuscode'];
				$values['csholdpriority'] = NULL;
				$values['csholdschcalldate'] = NULL;
				$values['csholdphone'] = NULL;
				$values['cshnewcasestatuscode'] = NULL;
				$values['cshnewpriority'] = NULL;
				$values['cshnewschcalldate'] = NULL;
				$values['cshnewphone'] = NULL;
				$values['cshdata'] = $data;
				$values['crtdate'] = $auditfields['date'];
				$values['crtuser'] = $auditfields['user'];
				$values['crtprog'] = $auditfields['prog'];
	
				foreach($values as $field=>$value) 
					$insertvalues["$field"] = "$field = '" . mysqli_real_escape_string($dbhandle,$value) . "'";
				$insertquery = "INSERT INTO case_scheduling_history SET " . implode(", ", $insertvalues);
				if($insertresult = mysqli_query($dbhandle,$insertquery)) 
					notify("000", "Call History Added - $data");
				else
					error("022", "Call History Add - Error on insert result. $insertquery<br>".mysqli_error($dbhandle));
			}
			else
				error("003", "Call History Add - Error select fetch. $selectquery<br>".mysqli_error($dbhandle));
		}
		else 
			error("004", "Call History Add - Error on select result. $selectquery<br>".mysqli_error($dbhandle));
	}
	else 
		notify("001", "Call History Add - Missing case id or data $crid/$data");
}

function casecallplaced($csqid, $newstatus, $callbutton) {
	if(!empty($csqid) && !empty($newstatus) && !empty($callbutton)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
//		errorclear();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$auditfields = getauditfields();
		$upduser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
		$upddate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
		$updprog = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
		$newpriority = 55;
		switch($callbutton) {
			case 'Busy' :
				$newpriority = 25;
				$newtime = "DATE_ADD(NOW(), INTERVAL 15 MINUTE)";
				break;
			case 'No Answer':
				$newpriority = 35;
				$newtime = "DATE_ADD(NOW(), INTERVAL 3 HOUR)";
				break;
			case 'Ans Mach';
				$newpriority = 45;
				$newtime = "DATE_ADD(NOW(), INTERVAL 25 HOUR)";
				break;
			default:
				$newtime="NOW()";
		}
		$csqquery = "
			update case_scheduling_queue 
			set csqpriority=$newpriority,
				csqschcalldate=$newtime,
				lockuser=NULL, 
				lockdate=NULL, 
				upduser=$upduser,
				upddate=$upddate,
				updprog=$updprog
			WHERE csqid = '$csqid'
			";
		if($csqresult = mysqli_query($dbhandle,$csqquery)) {
			notify("000","Last call result set to $callbutton, call re-queued.");
		}
		else
			error("006", "QUERY: $csqquery<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("999", "casecallplaced Error: Missing Record Id $csqid, newstatus $newstatus or button code $callbutton.");
}

function casecallback($csqid, $newstatus, $phone, $callbackdate) {
	if( !empty($csqid) && !empty($newstatus) && !empty($phone) && !empty($callbackdate) ) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$auditfields = getauditfields();
		$upduser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
		$upddate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
		$updprog = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
		$newpriority="'" . mysqli_real_escape_string($dbhandle,'10') . "'";
		$newtime = "'" . mysqli_real_escape_string($dbhandle,$callbackdate) . "'";
		$csqquery = "
			update case_scheduling_queue 
			set csqpriority=$newpriority,
				csqschcalldate=$newtime,
				lockuser=NULL, 
				lockdate=NULL, 
				upduser=$upduser,
				upddate=$upddate,
				updprog=$updprog
			WHERE csqid = '$csqid'
			";
		if($csqresult = mysqli_query($dbhandle,$csqquery)) 
			notify("000","Last call result set to Callback, call re-queued.");
		else
			error("006", "QUERY: $csqquery<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("999", "casecallback Error: Missing Record Id $csqid, newstatus $newstatus, phone $phone, or callback date & time $callbackdate.");
}

function caseschedule($csqid, $newstatus, $clinic, $appointment) {
	if(!empty($csqid) && !empty($newstatus) && !empty($clinic) && !empty($appointment) ) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$auditfields = getauditfields();
		$upduser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
		$upddate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
		$updprog = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";

//		$newstatus = 'SCH';
		
		$csqquery = "
			update case_scheduling_queue 
			set 
				csqresult='$newstatus', 
				lockuser=NULL, 
				lockdate=NULL, 
				upduser=$upduser,
				upddate=$upddate,
				updprog=$updprog
			WHERE csqid = '$csqid'
			";
//dump("csqquery",$csqquery);
		if($csqresult = mysqli_query($dbhandle,$csqquery)) {
			$csqquery2 = "
				select csqcrid, crlname, crfname
				from case_scheduling_queue 
				left join cases 
				on csqcrid=crid
				where 
					csqid='$csqid' 
				";
//dump("csqquery2",$csqquery2);
			if($csqresult2 = mysqli_query($dbhandle,$csqquery2)) { 
				if($csqrow2 = mysqli_fetch_assoc($csqresult2)) {
					$crid = $csqrow2['csqcrid']; 
					$lname = $csqrow2['crlname'];
					$fname = $csqrow2['crfname'];
					$apptscheduleddate=date("Y-m-d H:i:s", time());
					$query1 = "
						UPDATE cases 
						SET 
							crcasestatuscode='$newstatus', 
							crcnum='$clinic',
							crapptdate='$appointment',
							crapptscheduler=$upduser,
							crapptscheduleddate='$apptscheduleddate',
							upduser=$upduser,
							upddate=$upddate,
							updprog=$updprog
						WHERE crid='$crid'
						";
					$_POST['printPatientInformationSheet']=$crid;
//					header($_SERVER['DOCUMENT_ROOT'] . "/modules/scheduling/printPatientInformationSheet.php?crid=$crid");
//					require_once("printPatientInformationSheet.php");
//dump("query1",$query1);
					if($result1 = mysqli_query($dbhandle,$query1)) {
						if($newstatus=='SCH') {
							$pageaddress = "'/modules/scheduling/printPatientInformationSheet.php?crid=$crid'";
							$buttonhtml1='<input type="button" value="Print Sheet" onclick="window.open(' . $pageaddress .');" />';
							$pageaddress = "'/modules/scheduling/printSchedulingUpdateLetter.php?crid=$crid'";
							$buttonhtml2='<input type="button" value="Print Letter" onclick="window.open(' . $pageaddress .');" />';
						}
						else
							unset($buttonhtml);
						notify("000", "Previous Case $crid : Status updated to $newstatus. $buttonhtml1 $buttonhtml2");
//<a target=_blank href='/modules/scheduling/printPatientInformationSheet.php?crid=$crid'>Print Patient Information Sheet</a>
					}
					else 
						error("003", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
				}
				else
					error("004", "QUERY: $csqquery2<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else
				error("005", "QUERY: $csqquery2<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("006", "QUERY: $csqquery<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("999", "caseschedule Error: Missing Record Id $csqid, clinic code $clinic or appointment date/time $appointment.");
}

function casecancel($csqid, $newstatus, $reason) {
	if(!empty($csqid) && !empty($newstatus) && !empty($reason)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$newstatus = "'" . mysqli_real_escape_string($dbhandle,$newstatus) . "'";
		$reason = "'" . mysqli_real_escape_string($dbhandle,$reason) . "'";
		$auditfields = getauditfields();
		$upduser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
		$upddate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
		$updprog = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
		
		$csqquery = "
			update case_scheduling_queue 
			set 
				csqresult=$newstatus, 
				csqendcalldate=$upddate,
				lockuser=NULL, 
				lockdate=NULL, 
				upduser=$upduser,
				upddate=$upddate,
				updprog=$updprog
			WHERE csqid = '$csqid'
			";
//dump("csqquery",$csqquery);
		if($csqresult = mysqli_query($dbhandle,$csqquery)) {
			$csqquery2 = "
				select csqcrid 
				from case_scheduling_queue 
				where 
					csqid='$csqid' and 
					csqresult=$newstatus and 
					csqendcalldate=$upddate 
				";
//dump("csqquery2",$csqquery2);
			if($csqresult2 = mysqli_query($dbhandle,$csqquery2)) { 
				if($csqrow2 = mysqli_fetch_assoc($csqresult2)) {
					$crid = $csqrow2['csqcrid']; 
					$query1 = "
						UPDATE cases 
						SET 
							crcasestatuscode=$newstatus, 
							crcancelreasoncode=$reason, 
							crcanceluser=$upduser, 
							crcanceldate=$upddate, 
							upduser=$upduser,
							upddate=$upddate,
							updprog=$updprog
						WHERE crid='$crid'
						";
//dump("query1",$query1);
					if($result1 = mysqli_query($dbhandle,$query1)) {
						$query2 = "
							select crlname, crfname, crcasestatuscode, crcancelreasoncode 
							from cases 
							where crid='$crid'";
//dump("query2",$query2);
						if($result2 = mysqli_query($dbhandle,$query2)) { 
							if($row2 = mysqli_fetch_assoc($result2)) {
								$fname = $row2['crfname']; 
								$lname = $row2['crlname']; 
								notify("000", "Case $crid $lname, $fname status updated to $newstatus/$reason.");
						if($newstatus=='CAN') {
							$pageaddress = "'/modules/scheduling/printPatientInformationSheet.php?crid=$crid'";
							$buttonhtml1='<input type="button" value="Print Sheet" onclick="window.open(' . $pageaddress .');" />';
							$pageaddress = "'/modules/scheduling/printSchedulingUpdateLetter.php?crid=$crid'";
							$buttonhtml2='<input type="button" value="Print Letter" onclick="window.open(' . $pageaddress .');" />';
						}
						else
							unset($buttonhtml);
						notify("000", "Previous Case $crid : Status updated to $newstatus/$reason. $buttonhtml1 $buttonhtml2");
							}
							else
								error("001", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));
						}
						else
							error("002", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));
					}
					else 
						error("003", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
				}
				else
					error("004", "QUERY: $csqquery2<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else
				error("005", "QUERY: $csqquery2<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("006", "QUERY: $csqquery<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("999", "casecancel Error: Missing Record Id $csqid, newstatus $newstatus or reason code $reason.");
}
?>