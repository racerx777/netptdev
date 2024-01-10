<?php
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

function caseseen($id) {
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 

		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		

		$auditfields = getauditfields();
		$values['upduser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
		$values['upddate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
		$values['updprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
		$values['crcasestatuscode'] = "'ACT'";
		$values['crseenconfirmeddate']=$values['upddate'];
		$values['crseenconfirmedscheduler']=$values['upduser'];

		$set=array();
		foreach($values as $fieldname=>$fieldvalue) 
			$set["$fieldname"] = $fieldname . "=" . $fieldvalue;
		$update = "UPDATE cases SET " . implode(', ', $set) . " WHERE crid='$id'";
		if($result = mysqli_query($dbhandle,$update)) {
			$select = "select crlname, crfname, crcasestatuscode, crseenconfirmeddate, crseenconfirmedscheduler from cases where crid='$id'";
			if($selectresult = mysqli_query($dbhandle,$select)) { 
				if($selectrow = mysqli_fetch_assoc($selectresult)) {
					$fname = $selectrow['crfname']; 
					$lname = $selectrow['crlname']; 
					$casestatuscode = $selectrow['crcasestatuscode'];
					$confirmedon = $selectrow['crseenconfirmeddate'];
					$confirmedby = $selectrow['crseenconfirmedscheduler'];
					if($casestatuscode == 'ACT')
						notify("000", "Case $id $lname, $fname was confirmed. Status $casestatuscode by $confirmedby at $confirmedon.");
					else
						error("000", "Case $id $lname, $fname STATUS WAS NOT UPDATED to ACT. Please review.");
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

function caserequeuecall($id, $dbhandle) {
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
//		$dbhandle = dbconnect();
//		

		$auditfields = getauditfields();
		$values=array();
		$values['csqresult'] = "NULL";

		$select = "select csqid, csqcrid from case_scheduling_queue where csqcrid='$id'";
		if($selectresult = mysqli_query($dbhandle,$select)) { 
			if($selectrow = mysqli_fetch_assoc($selectresult)) {
				$values['upduser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
				$values['upddate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
				$values['updprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
				$set=array();
				foreach($values as $fieldname=>$fieldvalue) 
					$set["$fieldname"] = $fieldname . "=" . $fieldvalue;

				$update = "UPDATE case_scheduling_queue SET " . implode(', ', $set) . " WHERE csqcrid='$id'";
				if($result = mysqli_query($dbhandle,$update)) 
					notify("000", "Call $callid requeued.");
				else 
					error("001", "QUERY: $update<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else {
				$values['crtuser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
				$values['crtdate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
				$values['crtprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
				$values['csqcrid'] = "'$id'";
				$values['csqpriority'] = 30;
				$values['csqschcalldate'] = $values['crtdate'];
//				foreach($values as $fieldname=>$fieldvalue) 
//					$set["$fieldname"] = $fieldname . "=" . $fieldvalue;
				$insert = "INSERT INTO case_scheduling_queue (csqresult, crtuser, crtdate, crtprog, csqcrid, csqpriority, csqschcalldate) VALUES(" . implode(', ', $values) . ")";
				if($result = mysqli_query($dbhandle,$insert)) 
					notify("000", "Call queued.");
				else 
					error("002", "QUERY: $insert<br>ERROR:" . mysqli_error($dbhandle));
			}
		}
		else 
			error("021", "QUERY: $select<br>ERROR:" . mysqli_error($dbhandle));

//		mysqli_close($dbhandle);
	}
	else
		error("c04", "Error: Missing Record Id.");
}

function casenoshow($id) {
	if(!empty($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		

		$auditfields = getauditfields();
		$values['upduser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
		$values['upddate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
		$values['updprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
		$values['crcasestatuscode'] = "'PEN'";
		$values['crapptscheduler']="''";

		$set=array();
		foreach($values as $fieldname=>$fieldvalue) 
			$set["$fieldname"] = $fieldname . "=" . $fieldvalue;
		$update = "UPDATE cases SET " . implode(', ', $set) . " WHERE crid='$id'";

		if($result = mysqli_query($dbhandle,$update)) {
			$select = "select crid, crlname, crfname, crcasestatuscode, upduser from cases where crid='$id'";
			if($selectresult = mysqli_query($dbhandle,$select)) { 
				if($selectrow = mysqli_fetch_assoc($selectresult)) {
					$fname = $selectrow['crfname']; 
					$lname = $selectrow['crlname']; 
					$casestatuscode = $selectrow['crcasestatuscode']; 
					$byuser = $selectrow['upduser'];
					if($casestatuscode == 'PEN') {
						notify("000", "Case $id $lname, $fname status updated to $casestatuscode by $byuser.");
						caserequeuecall($id, $dbhandle);
					}
					else
						error("000", "Case $id $lname, $fname STATUS WAS NOT UPDATED to $casestatuscode. Please review.");
				}
				else
					error("001", "QUERY: $select<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else
				error("002", "QUERY: $select<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("003", "QUERY: $update<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("c04", "Error: Missing Record Id.");
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
function caseclinicrescheduled($id, $cnum, $appointment) {
	if(!empty($id) && !empty($appointment)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "UPDATE cases SET crcasestatuscode = 'SCH', crcnum='$cnum', crapptdate='$appointment' WHERE crid='$id'";
		if($result1 = mysqli_query($dbhandle,$query1)) {
			$query2 = "select crlname, crfname, crcasestatuscode, crapptdate from cases where crid='$id'";
			if($result2 = mysqli_query($dbhandle,$query2)) { 
				if($row2 = mysqli_fetch_assoc($result2)) {
					$fname = $row2['crfname']; 
					$lname = $row2['crlname']; 
					$apptdate = $row2['crapptdate'];
					notify("000", "Case $id $lname, $fname was rescheduled by clinic. Status updated to SCH at " . displayDate($apptdate) . " at " . displayTime($apptdate) . ".");
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
		errorclear();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
// Only allow send to scheduling if NEW or PRV case
		$query="select crid, crcnum from cases where crid='$id' and crcasestatuscode IN ('NEW','PEA')";
		if($result = mysqli_query($dbhandle,$query)) {
			$casesrow = mysqli_query($dbhandle,$result);
			if(mysqli_num_rows($result) != 1) {
				error("001", "Cannot send this case to scheduling. Only NEW and PEA cases can be sent to scheduling.");
			}
		}
		else {
			error("008", "QUERY: $query<br>ERROR:" . mysqli_error($dbhandle));
		}

// Only allow send to scheduling if no calls in queue already
		$query="select csqid from case_scheduling_queue where csqcrid='$id'";
		if($result = mysqli_query($dbhandle,$query)) {
			if(mysqli_num_rows($result) > 0) {
				error("001", "Cannot send this case to scheduling. Case was already sent to scheduling.");
				caseupdatestatuscode($id, 'PEN');
			}
		}
		else {
			error("009", "QUERY: $query<br>ERROR:" . mysqli_error($dbhandle));
		}

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
				caseupdatestatuscode($_SESSION['id'], 'PEN');
			}
			else 
				error("001", "QUERY: $query1<br>ERROR:" . mysqli_error($dbhandle));
		}
// else errors are queued for display			
	}
	else
		error("002", "Error: Missing Record Id.");

	mysqli_close($dbhandle);
}

function attendancecasecancel($id, $reason) {
	if(!empty($id) && !empty($reason)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(15); 
		errorclear();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		

		$auditfields = getauditfields();
		$values['upduser'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
		$values['upddate'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
		$values['updprog'] = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
		$values['crcasestatuscode'] = "'CAN'";
		$values['crcanceluser']=$values['upduser'];
		$values['crcanceldate']=$values['upddate'];
		$values['crcancelreasoncode']="'" . mysqli_real_escape_string($dbhandle,$reason) . "'";

		$set=array();
		foreach($values as $fieldname=>$fieldvalue) 
			$set["$fieldname"] = $fieldname . "=" . $fieldvalue;
		$update = "UPDATE cases SET " . implode(', ', $set) . " WHERE crid='$id'";
		if($result = mysqli_query($dbhandle,$update)) {
			$select = "select crlname, crfname, crcasestatuscode, crcancelreasoncode, crcanceldate, crcanceluser from cases where crid='$id'";
			if($selectresult = mysqli_query($dbhandle,$select)) { 
				if($selectrow = mysqli_fetch_assoc($selectresult)) {
					$fname = $selectrow['crfname']; 
					$lname = $selectrow['crlname']; 
					$casestatuscode = $selectrow['crcasestatuscode'];
					$cancelreasoncode = $selectrow['crcancelreasoncode'];
					$canceledon = $selectrow['crcanceldate'];
					$canceledby = $selectrow['crcanceluser'];
					if($casestatuscode == 'CAN' && $cancelreasoncode == $reason)
						notify("000", "Case $id $lname, $fname was canceled. Status $casestatuscode by $canceledby at $canceledon.");
					else
						error("000", "Case $id $lname, $fname STATUS WAS NOT UPDATED to CAN. Please review.");
				}
				else
					error("001", "QUERY: $select<br>ERROR:" . mysqli_error($dbhandle));				
			}
			else
				error("002", "QUERY: $select<br>ERROR:" . mysqli_error($dbhandle));
		}
		else 
			error("003", "QUERY: $update<br>ERROR:" . mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("d04", "Error: Missing Record Id.");
}
?>