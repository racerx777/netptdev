<?php
function checkpwd($pwd) {
// between 8 and 16 characters
// Must contain a capital
// Must contain a number or symbol

// no spaces or quotes (illegal characters)
// Must not contain username
// Must not contain 'password'
	if( str_replace(" ", "", $pwd) != $pwd) 
		error('001', 'Password may not contain spaces.');
	
	if( (str_replace("'", "", $pwd) != $pwd) || ( str_replace('"', '', $pwd) != $pwd))
		error('002', 'Password may not contain quotes.');
	
	if( (strlen($pwd) < 8) || (strlen($pwd) > 16) )
		error('003', 'Password must be between 8 and 16 characters in length.');
	
	if( !preg_match("#[a-z]+#", $pwd) ) 
		error('004', 'Password must include at least one lowercase letter.');
	
	if( !preg_match("#[0-9]+#", $pwd)  && !preg_match("#\W+#", $pwd) )
		error('005', 'Password must include at least one number or symbol.');
	
	if( !preg_match("#[A-Z]+#", $pwd) ) 
		error('006', 'Password must include at least one CAPITAL letter.');
	
	if( preg_match("/password/", strtolower($pwd)) ) 
		error('007', 'Password cannot include the word password.');
	
	if( preg_match("/" . strtolower($_SESSION['user']['umuser']) ."/", strtolower($pwd)) ) 
		error('008', 'Password cannot include your user name.');

	if(errorcount()==0)
		return(true);
	else
		return(false);
}

function validatecurrentpassword($currentpassword) {
// Validate current password 
// $_POST['passwordcurrent']
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	// Clean user input
	$user = strtolower(mysqli_real_escape_string($dbhandle,$_SESSION['user']['umuser']));
	$query1 = "SELECT umpass FROM master_user WHERE uminactive=0 and umuser = '$user'";
	if($result1 = mysqli_query($dbhandle,$query1)) {
		$numRows1= mysqli_num_rows($result1);
		if($numRows1 == 1) {
			if($row1 = mysqli_fetch_assoc($result1)) {
				$password = md5(mysqli_real_escape_string($dbhandle,$currentpassword));
				$query2 = "SELECT umid FROM master_user WHERE uminactive=0 and LOWER(umuser)='$user' and umpass='$password'";
				if($result2 = mysqli_query($dbhandle,$query2)) {
					$numRows2=mysqli_num_rows($result2);
					if($numRows2 == 1) {
						if($row2 = mysqli_fetch_assoc($result2)) {
							return(true);
						}
					}
				}
			}
		}
	}
	return(false);
}

if(validatecurrentpassword($_POST['passwordcurrent'])) {
	// If changing password must supply two matching new passwords as well
	if(isset($_POST['passwordnew1']) || isset($_POST['passwordnew2'])) {
		if(isset($_POST['passwordnew1'])) {
			if(isset($_POST['passwordnew2'])) {
				if(!empty($_POST['passwordnew1'])) {
					if(!empty($_POST['passwordnew2'])) {
						$pwd1 = $_POST['passwordnew1'];
						if(checkpwd($pwd1)) {
							$pwd2 = $_POST['passwordnew2'];
							if(checkpwd($pwd2)) {
								if($pwd1==$pwd2) { 
									$_POST['umpassword']=$pwd1;
								}
								else {
									error("009","When changing password you must supply your new password twice (to be sure that you typed it correctly) and both passwords must match exactly.");
								}
							}
						}
					}
					else
						error('010','When changing password you must supply your new password twice (to be sure that you typed it correctly) and they cannot be blank.');
				}
			}
			else
				error('012','When changing password you must supply your new password twice (to be sure that you typed it correctly) and both passwords must match exactly.');
		}
		else
			error('013','When changing password you must supply your new password twice (to be sure that you typed it correctly) and both passwords must match exactly.');
	}
}
else
	error('014','Please supply your correct current password to make changes to your settings.');

if(errorcount()>0) {
	unset($_POST['umpassword']);
}
unset($_POST['passwordcurrent']);
unset($_POST['passwordnew1']);
unset($_POST['passwordnew2']);
?>