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
?>