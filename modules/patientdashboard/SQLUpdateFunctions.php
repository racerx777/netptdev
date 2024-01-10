<?php
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
			notify("000","Treatment $id Updated.");
			addheaderhistory($id, date('Y-m-d H:i:s', time()), $_SESSION['user']['umuser'], 0, $_SESSION['application'], 'UPDATE', 'Updated Treatment Status ['. $newstatus . ']', $query);
		}
		else 
			error("001", mysqli_error($dbhandle));
		//close the connection
		mysqli_close($dbhandle);
	}
	else
		error("002", "Error: Missing Record Id.");
}

function treatmentupdateinactivate($id) {
	treatmentupdatestatus($id, '900');
}

function updatepatientstatus($id, $newstatus) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	errorclear();
	if(isset($id)) {
		// Connect to database 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$usercliniclist = getUserClinicsList();
		$query = "
		SELECT thid, thcnum, thlname, thfname 
		FROM treatment_header 
		WHERE thsbmstatus between '300' and '399' and thcnum in " . $usercliniclist . " and thid= '" . mysqli_real_escape_string($dbhandle,$id) . "'";
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			if($row = mysqli_fetch_assoc($result)) {
				addheaderhistory($row['thid'], date('Y-m-d H:i:s', time()), $_SESSION['user']['umuser'], 0, $_SESSION['application'], 'UPDATE', 'Patient Entry Status Update by Patient id ['. $newstatus . ']', $query);
				$query1 = "
				SELECT thid 
				FROM treatment_header 
				WHERE thsbmstatus between '300' and '399' and thcnum='" . mysqli_real_escape_string($dbhandle,$row['thcnum']) . "' and thlname='" . mysqli_real_escape_string($dbhandle,$row['thlname']) . "' and thfname='" . mysqli_real_escape_string($dbhandle,$row['thfname']) . "'";
				$result1 = mysqli_query($dbhandle,$query1);
				if($result1) {
					while($row1 = mysqli_fetch_assoc($result1)) {
						$query2 = "UPDATE treatment_header ";
						$set=array();
						$set[] = "thsbmStatus='" . mysqli_real_escape_string($dbhandle,$newstatus) . "' ";
						if(count($set) > 0)
							$auditfields = getauditfields();
							$set[] = "upddate='" . $auditfields['date'] . "' ";
							$set[] = "upduser='" . $auditfields['user'] . "' ";
							$set[] = "updprog='" . $auditfields['prog'] . "' ";
							$query2 .= "SET " . implode(', ', $set);
						$query2 .= "WHERE thid='" . mysqli_real_escape_string($dbhandle,$row1['thid']) . "'";
						$result2 = mysqli_query($dbhandle,$query2);
						if($result2) {
							notify("000","Treatment " . $row1['thid'] . "Updated.");
							addheaderhistory($row1['thid'], date('Y-m-d H:i:s', time()), $_SESSION['user']['umuser'], 0, $_SESSION['application'], 'UPDATE', 'Updated Treatment Status ['. $newstatus . ']', $query2);
						}
						else 
							error("001", mysqli_error($dbhandle));
					}
				}
				else
					error("999", $query1 . "-" . mysqli_error($dbhandle));
			}
			else
				error("000", $query . "-" . mysqli_error($dbhandle));
		}
		else 
			error("001", $query . "-" . mysqli_error($dbhandle));
		//close the connection
		if(isset($dbhandle)) mysqli_close($dbhandle);
	}
	else
		error("002", "Error: Missing Record Id.");
}
?>