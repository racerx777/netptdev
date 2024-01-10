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
			$_SESSION['notify'][] = $numRows . "Record $id Updated.";
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
?>