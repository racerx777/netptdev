<?php
errorclear();
if(isset($_SESSION['id'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(90); 
// trim and strip all input
	foreach($_POST as $key=>$val) {
		if($key != 'button') {
			if(is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags(trim($val)));
		}
	}

	if(!isset($_POST['uminactive'])) 
		$_POST['uminactive']=0;

// Validate form fields
	require_once('validation.php');

	if(errorcount() == 0) {
// Connect to database 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$set=array();
//declare the SQL statement that will query the database
		$query = "UPDATE master_user ";
		if(isset($_POST['uminactive'])) 
			$set[] = "uminactive ='" . $_POST['uminactive'] . "'";
		if(isset($_POST['umuser'])) 
			$set[] .= "umuser ='" . $_POST['umuser'] . "'";
		if(isset($_POST['umpassword']) && !empty($_POST['umpassword'])) 
			$set[] .= "umpass='" . md5($_POST['umpassword']) . "'";
		if(isset($_POST['umname'])) 
			$set[] .= "umname='" . $_POST['umname'] . "'";
		if(isset($_POST['umemail'])) 
			$set[] .= "umemail='" . $_POST['umemail'] . "'";
		if(isset($_POST['umclinic'])) 
			$set[] .= "umclinic='" . $_POST['umclinic'] . "'";
		if(isset($_POST['umhomepage'])) 
			$set[].= "umhomepage='" . $_POST['umhomepage'] . "'";
		if(isset($_POST['umrole'])) 
			$set[] .= "umrole='" . $_POST['umrole'] . "'";
		if(count($set) > 0)
			$query .= "SET " . implode(', ', $set);
		$query .= "WHERE umid='" . $_SESSION['id'] . "'";
//execute the SQL query 
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$_SESSION['notify'][] = "Record successfully updated.";
		}
		else {
			error("001", "MYSQL" . mysqli_error($dbhandle));
		}
//close the connection
		mysqli_close($dbhandle);
		unset($_POST['umuser']);
		unset($_POST['umpassword']);
		unset($_POST['umname']);
		unset($_POST['umemail']);
		unset($_POST['umclinic']);
		unset($_POST['umhomepage']);
		unset($_POST['umrole']);
	}
}
else
	error("001", "id field error (should never happen).");

?>