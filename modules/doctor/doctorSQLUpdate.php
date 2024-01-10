<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
errorclear();

if(!empty($crid)) {
	// trim and strip all input
	foreach($_POST as $key=>$val) {
		if($key != 'button') {
			if(is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags(trim($val)));
		}
	}

	// Validate form fields

//	require_once('insuranceValidation.php');

	if(errorcount() == 0) {
//		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
//		$dbhandle = dbconnect();
//		

// Check for existence of Doctor-Location Relationship...
// Update the contact id if different

		$dmid = $_POST['dmid'];
		$dlid = $_POST['dlid'];
		$dlsid = $_POST['dlsid'];
		$query="
			SELECT * 
			FROM doctor_relationships
			WHERE drdmid='$dmid' and drdlid='$dlid'
		";
//dump("query",$query);
		if($result = mysqli_query($dbhandle,$query)) {
			if($row=mysqli_fetch_assoc($result)){
				if($row['drdlsid']!='$dlsid') {
		// update contact
					$query="
						UPDATE doctor_relationships
						SET drdlsid='$dlsid'
						WHERE drdmid='$dmid' and drdlid='$dlid'
					";
//dump("query",$query);
					if($result=mysqli_query($dbhandle,$query)) 
						notify("000","Doctor relationship updated.");
					else
						error("999","Failed<br>$query<br>".mysqli_error($dbhandle));
				}
			}
			else {
		// insert relationship
				$query="
					INSERT INTO doctor_relationships 
					(drdmid, drdlid, drdgid, drdlsid) 
					VALUES('$dmid', '$dlid', '$dgid', '$dlsid')
				";
//dump("query",$query);
				if($result=mysqli_query($dbhandle,$query)) 
					notify("000","Doctor relationship inserted.");
				else
					error("998","Failed<br>$query<br>".mysqli_error($dbhandle));		
			}
		}

		//declare the SQL statement that will query the database
		$set=array();
		$query = "UPDATE cases ";
		if(!empty($dmid) ) {
			$set[] = "crrefdmid='" . mysqli_real_escape_string($dbhandle,$dmid) . "'";
			if(!empty($dlid) ) 
				$set[] = "crrefdlid='" . mysqli_real_escape_string($dbhandle,$dlid) . "'";
			else 
				$set[] = "crrefdlid=NULL";

//			if(!empty($dlsid) ) 
//				$set[] = "crrefdlsid='" . mysqli_real_escape_string($dbhandle,$dlsid) . "'";
//			else // adjuster empty 
//				$set[] = "crrefdlsid=NULL";
		}
		else {
			$set[] = "crrefdmid=NULL";
			$set[] = "crrefdlid=NULL";
//			$set[] = "crrefdlsid=NULL";
		}
		if(count($set) > 0)
			$query .= "SET " . implode(', ', $set);
		$query .= " WHERE crid='$crid'";
//dump("query",$query);
		//execute the SQL query 
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$_SESSION['notify'][] = "Doctor successfully updated.";
			foreach($_POST as $key=>$val) 
				unset($_POST[$key]);
		}
		else
			error('001', "Error Updating Record : $query<br>" . mysqli_error($dbhandle)); 	
		//close the connection
//		mysqli_close($dbhandle);
	}
}
else 
	error('000', "Error crid : $crid");
?>