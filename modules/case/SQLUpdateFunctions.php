<?php
function casedelete($id) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(90); 
	errorclear();
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$select = "select crid, crlname, crfname from cases where crid='$id' and crcasestatuscode='NEW'";
		if($selectresult = mysqli_query($dbhandle,$select)) { 
			if($selectrow = mysqli_fetch_assoc($selectresult)) {
				$fname = $selectrow['crfname']; 
				$lname = $selectrow['crlname']; 
				$delete = "delete from cases where crid='$id'";
				if($deleteresult = mysqli_query($dbhandle,$delete)) 
					notify("000", "Case $id $lname, $fname successfully deleted.");
				else
					error("001", "QUERY: $delete<br>ERROR:" . mysqli_error($dbhandle));
			}
			else
				error("002", "QUERY: $select<br>ERROR:" . mysqli_error($dbhandle));
		}
		else
			error("003", "QUERY: $select<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("a04", "Error: Missing record identifier.");
}

function caseupdateinactivate($id) {
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(90); 
		errorclear();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "UPDATE cases SET crinactive = NOT crinactive WHERE crid='$id'";
		if($result1 = mysqli_query($dbhandle,$query1)) {
			$query2 = "select crlname, crfname, crinactive from cases where crid='$id'";
			if($result2 = mysqli_query($dbhandle,$query2)) { 
				if($row2 = mysqli_fetch_assoc($result2)) {
					$fname = $row2['crfname']; 
					$lname = $row2['crlname']; 
					if($row2['crinactive'] == '1')
						$inactive = "inactive"; 
					else
						$inactive = "active";
					notify("000", "Case $id $lname, $fname was made $inactive.");
				}
				else
					error("001", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else
				error("002", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("003", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("b04", "Error: Missing Record Id.");
}

function caseupdatestatuscode($id, $newstatus) {
	if(!empty($id) && !empty($newstatus)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "UPDATE cases SET crcasestatuscode = '" . $newstatus . "' WHERE crid='$id'";
		if($result1 = mysqli_query($dbhandle,$query1)) {
			$query2 = "select crlname, crfname, crcasestatuscode from cases where crid='$id'";
			if($result2 = mysqli_query($dbhandle,$query2)) { 
				if($row2 = mysqli_fetch_assoc($result2)) {
					$fname = $row2['crfname']; 
					$lname = $row2['crlname']; 
					if($row2['crcasestatuscode'] == $newstatus)
						notify("000", "Case $id $lname, $fname status updated to $newstatus.");
					else
						error("000", "Case $id $lname, $fname STATUS WAS NOT UPDATED to $newstatus. Please review.");
				}
				else
					error("001", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else
				error("002", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("003", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("c04", "Error: Missing Record Id.");
}

function caserequiresauthorization($crid) {
// This function will update the initial prescription only as it accepts the case id as input parameter
	if(!empty($crid)) {
		schedulingaddcall($crid);
		caseupdatestatuscode($crid, 'PEA');
	}
}

function casereschedule($id) {
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "UPDATE cases SET crcasestatuscode = 'PEN', crapptscheduler=NULL, crapptscheduleddate=NULL WHERE crid='$id'";
		if($result1 = mysqli_query($dbhandle,$query1)) {
			$query2 = "select crlname, crfname, crcasestatuscode from cases where crid='$id'";
			if($result2 = mysqli_query($dbhandle,$query2)) { 
				if($row2 = mysqli_fetch_assoc($result2)) {
					$fname = $row2['crfname']; 
					$lname = $row2['crlname']; 
					if($row2['crcasestatuscode'] == $newstatus)
						notify("000", "Case $id $lname, $fname status updated to $newstatus.");
					else
						error("000", "Case $id $lname, $fname STATUS WAS NOT UPDATED to $newstatus. Please review.");
				}
				else
					error("001", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else
				error("002", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("003", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("c04", "Error: Missing Record Id.");
}

// This reschedule function does not update the scheduled by person
function caserescheduleatclinic($id, $appointment) {
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "UPDATE cases SET crcasestatuscode = 'SCH', crapptdate='$apptdate' WHERE crid='$id'";
		if($result1 = mysqli_query($dbhandle,$query1)) {
			$query2 = "select crlname, crfname, crcasestatuscode from cases where crid='$id'";
			if($result2 = mysqli_query($dbhandle,$query2)) { 
				if($row2 = mysqli_fetch_assoc($result2)) {
					$fname = $row2['crfname']; 
					$lname = $row2['crlname']; 
					notify("000", "Case $id $lname, $fname was rescheduled by clinic. Status updated to SCH at $appointment.");
				}
				else
					error("001", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else
				error("002", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("003", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("c04", "Error: Missing Record Id.");
}

function schedulingaddcall($id) {
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
// Only allow send to scheduling if NEW, PEA or PRV case
		$query="select crid, crcnum from cases where crid='$id' and crcasestatuscode IN ('NEW','PEA','PEN')";
		if($result = mysqli_query($dbhandle,$query)) {
			if(mysqli_num_rows($result) == 1) {
				$casesrow = mysqli_fetch_assoc($result);
// Only allow send to scheduling if no calls in queue already
				$query="select csqid, csqcrid, csqresult from case_scheduling_queue where csqcrid='$id'";
				if($result = mysqli_query($dbhandle,$query)) {
					if(mysqli_num_rows($result) != 0) {

						if($csqrow=mysqli_fetch_assoc($result)) {
// If there is an entry in the scheduling queue already, update the date and time to followup for 7 days.
//							error("001", "Case in scheduling queue. crid:". $csqrow['csqcrid'] ." csqid:". $csqrow['csqid'] ." result:". $csqrow['csqresult']);
							if($_SESSION['button']=='Requires Authorization') {
								$updatequery="UPDATE case_scheduling_queue set csqschcalldate=DATE_ADD(NOW(), INTERVAL 7 DAY) where csqcrid='$id'";
								if($updateresult = mysqli_query($dbhandle,$updatequery)) 
									error("000","Call $csqid re-queued for followup in 7 days.");
								else
									error("001", "UPDATE error. Could not re-queue call $csqid for followup in 7 days. $updatequery<br>".mysqli_error($dbhandle));
							}
							else
								notify("001", "Case already in scheduling queue. crid:". $csqrow['csqcrid'] ." csqid:". $csqrow['csqid'] ." result:". $csqrow['csqresult']);
						}
						else
							error("002", "FETCH error. $query".mysqli_error($dbhandle));

					}
				}
				else 
					error("009", "QUERY: $query<br>ERROR:" . mysqli_error($dbhandle));
			}
			else 
				error("001", "Cannot send this case to scheduling. Only NEW, PEA and PEN cases can be sent to scheduling. crid:$id");
		}
		else 
			error("008", "QUERY: $query<br>ERROR:" . mysqli_error($dbhandle));

		if(errorcount()==0) {
			$callpriority = 30;
// if Weststar then lower the priority
			if( $casesrow['crcnum'] == '01' ||
				$casesrow['crcnum'] == '02' ||
				$casesrow['crcnum'] == '03' ||
				$casesrow['crcnum'] == '04' ||
				$casesrow['crcnum'] == '05' ||
				$casesrow['crcnum'] == '06' ||
				$casesrow['crcnum'] == '07' ||
				$casesrow['crcnum'] == '08')
				$callpriority = 20;
			
			$auditfields = getauditfields();
	// Add call to scheduling queue
			$values['csqcrid'] = "'" . mysqli_real_escape_string($dbhandle,$id) . "'";
			$values['csqpriority'] = "'" . mysqli_real_escape_string($dbhandle,$callpriority) . "'";
			$values['csqschcalldate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
			$values['crtdate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
			$values['crtuser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
			$values['crtprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
			$insertvalues = "VALUES( " . implode(", ", $values) . ")";
			$query1 = "INSERT INTO case_scheduling_queue (csqcrid, csqpriority, csqschcalldate, crtdate, crtuser, crtprog) $insertvalues ";
			if($result1 = mysqli_query($dbhandle,$query1)) {
				notify("000", "Scheduling Queue Call created.");
			}
			else 
				error("001", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		}
		mysqli_close($dbhandle1);
// else errors are queued for display			
	}
	else
		error("002", "Error: Missing Record Id.");

}

function casecancel($id, $newstatus, $reason) {
	if(!empty($id) && !empty($reason)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "
			UPDATE cases 
			SET crcasestatuscode = '" . $newstatus . "', crcancelreasoncode='" . $reason . "', crcanceluser='" . getuser() . "', crcanceldate = '" . date("Y-m-d H:i:s") . "' 
			WHERE crid='$id'
			";
		if($result1 = mysqli_query($dbhandle,$query1)) {
			$query2 = "select crlname, crfname, crcasestatuscode, crcancelreasoncode from cases where crid='$id'";
			if($result2 = mysqli_query($dbhandle,$query2)) { 
				if($row2 = mysqli_fetch_assoc($result2)) {
					$fname = $row2['crfname']; 
					$lname = $row2['crlname']; 
					if($row2['crcasestatuscode'] == $newstatus && $row2['crcancelreasoncode'] == $reason)
						notify("000", "Case $id $lname, $fname status updated to $newstatus/$reason.");
					else
						error("000", "Case $id $lname, $fname STATUS WAS NOT UPDATED to $newstatus/$reason. Please review.");
				}
				else
					error("001", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else
				error("002", "QUERY: $query2<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("003", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("d04", "Error: Missing Record Id.");
}

function prescriptionadd($id) {
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
//		$query="select crtherapytypecode, crrefdmid, crrefdlid, crdate, crcnum from cases where crid='$id'";
		$query = "select crid, crtherapytypecode, crrefdmid, crrefdlid, crdate, crcnum, crfrequency, crduration, crtotalvisits  from cases where crid='$id'";
		if($result = mysqli_query($dbhandle,$query)) {
			if($row = mysqli_fetch_assoc($result)) {
				$auditfields = getauditfields();
				$values['cpcrid'] = "'" . mysqli_real_escape_string($dbhandle,$id) . "'";
				$values['cpttmcode'] = "'" . mysqli_real_escape_string($dbhandle,$row['crtherapytypecode']) . "'";
				$values['cpdmid'] = "'" . mysqli_real_escape_string($dbhandle,$row['crrefdmid']) . "'";
				$values['cpdlid'] = "'" . mysqli_real_escape_string($dbhandle,$row['crrefdlid']) . "'";
				$values['cpdate'] = "'" . mysqli_real_escape_string($dbhandle,$row['crdate']) . "'";
				$values['cpcnum'] = "'" . mysqli_real_escape_string($dbhandle,$row['crcnum']) . "'";
				$values['cpfrequency'] = "'" . mysqli_real_escape_string($dbhandle,$row['crfrequency']) . "'";
				$values['cpduration'] = "'" . mysqli_real_escape_string($dbhandle,$row['crduration']) . "'";
				$values['cptotalvisits'] = "'" . mysqli_real_escape_string($dbhandle,$row['crtotalvisits']) . "'";
				$values['cpstatuscode'] = "'NEW'";
				$values['cpstatusupdated'] = "NOW()";
				$values['cpauthstatuscode'] = "NULL";
				$values['crtdate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
				$values['crtuser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
				$values['crtprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
				$insertvalues = "VALUES( " . implode(", ", $values) . ")";
				$query1 = "INSERT INTO case_prescriptions (cpcrid, cpttmcode, cpdmid, cpdlid, cpdate, cpcnum, cpfrequency, cpduration, cptotalvisits, cpstatuscode, cpstatusupdated, cpauthstatuscode, crtdate, crtuser, crtprog) $insertvalues ";
				if($result1 = mysqli_query($dbhandle,$query1)) {
					notify("000", "Prescription created.");
				}
				else 
					error("004", "INSERT QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
			}
			else
				error("003", "FETCH QUERY: $query<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("002", "SELECT QUERY: $query<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle1);
	}
	else
		error("001", "Error: Missing Case Id.");
}
?>