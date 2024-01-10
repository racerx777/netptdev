<?php
errorclear();
if(isset($_SESSION['user']['umuser'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(5); 

// trim and strip all input
	foreach($_POST as $key=>$val) {
		if($key != 'button') {
			if(is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags($val));
		}
	}

// Validate form fields
	require_once('validation.php');

	if(errorcount() == 0) {
// Connect to database 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$set=array();
//declare the SQL statement that will query the database
		$query = "UPDATE master_user ";
		if(isset($_POST['umpassword']) && !empty($_POST['umpassword']))  {
			$set[] .= "umpass='" . md5($_POST['umpassword']) . "'";
			$umlastpasswordchanged=date('Y-m-d H:i:m', time());
			$set[] .= "umlastpasswordchanged='$umlastpasswordchanged'";
			$statusmessage[]="Password changed.";
			}
		if(isset($_POST['umname'])) {
			if($_POST['umname'] != $_SESSION['user']['umname']) {
				$set[] .= "umname='" . $_POST['umname'] . "'";
				$statusmessage[]="Name changed.";
			}
		}
		if(isset($_POST['umemail'])) {
			if($_POST['umemail'] != $_SESSION['user']['umemail']) {
				$set[] .= "umemail='" . $_POST['umemail'] . "'";
				$statusmessage[]="E-mail changed.";
			}
		}
		if(isset($_POST['umclinic'])) {
			if($_POST['umclinic'] != $_SESSION['user']['umclinic']) {
				$set[] .= "umclinic='" . $_POST['umclinic'] . "'";
				$statusmessage[]="Clinic changed.";
			}
		}
		if(count($set) > 0) {
			$query .= "SET " . implode(', ', $set);
			$query .= " WHERE umuser='" . $_SESSION['user']['umuser'] . "'";
//execute the SQL query 
			if($result = mysqli_query($dbhandle,$query)) {
				$statusmessages=implode("<br>", $statusmessage);
				getusersettings($_SESSION['user']['umid']);
				unset($_SESSION['button']);
				unset($_SESSION['navigation']);
				unset($_SESSION['application']);
			}
			else
				error("001", "MYSQL" . mysqli_error($dbhandle));
		}
		else
			$statusmessages="No information was changed.";
		notify("000", $statusmessages);

//close the connection
		mysqli_close($dbhandle);
		unset($_POST['umuser']);
		unset($_POST['umpassword']);
		unset($_POST['passwordcurrent']);
		unset($_POST['passwordnew1']);
		unset($_POST['passwordnew2']);
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