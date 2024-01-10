<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
errorclear();

if(!empty($crid) && !empty($icseq)) {
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
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$set=array();
		//declare the SQL statement that will query the database
		$query = "UPDATE cases ";
		if($icseq == '1') {
			if(isset($_POST['icid'])) 
				$set[] = "cricid1 ='" . mysqli_real_escape_string($dbhandle,$_POST['icid']) . "'";
			if(isset($_POST['iclid'])) 
				$set[] = "criclid1 ='" . mysqli_real_escape_string($dbhandle,$_POST['iclid']) . "'";
			if(isset($_POST['icaid'])) 
				$set[] = "cricaid1 ='" . mysqli_real_escape_string($dbhandle,$_POST['icaid']) . "'";
			if(isset($_POST['icnote'])) 
				$set[] = "crinsurance1note ='" . mysqli_real_escape_string($dbhandle,$_POST['icnote']) . "'";
		}
		if($icseq==2) {
			if(isset($_POST['icid'])) 
				$set[] = "cricid2 ='" . mysqli_real_escape_string($dbhandle,$_POST['icid']) . "'";
			if(isset($_POST['iclid'])) 
				$set[] = "criclid2 ='" . mysqli_real_escape_string($dbhandle,$_POST['iclid']) . "'";
			if(isset($_POST['icaid'])) 
				$set[] = "cricaid2 ='" . mysqli_real_escape_string($dbhandle,$_POST['icaid']) . "'";
			if(isset($_POST['icnote'])) 
				$set[] = "crinsurance2note ='" . mysqli_real_escape_string($dbhandle,$_POST['icnote']) . "'";
		}
		if(count($set) > 0)
			$query .= "SET " . implode(', ', $set);
		$query .= " WHERE crid='$crid'";
//dump("query",$query);
		//execute the SQL query 
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$_SESSION['notify'][] = "Insurance successfully updated.";
			foreach($_POST as $key=>$val) 
				unset($_POST[$key]);
		}
		else
			error('001', "Error Updating Record : $query<br>" . mysqli_error($dbhandle)); 	
		//close the connection
		mysql_close($dbhandle);
	}
}
else 
	error('000', "Error crid/icseq : $crid/$icseq");
?>