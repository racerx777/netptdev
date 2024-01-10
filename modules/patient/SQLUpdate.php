<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
if($_SESSION['user']['umrole'] < 10) 
	header('Location:https://' . $_SESSION['SERVER_NAME']);

errorclear();
if(!isset($id)) 
	error('002', "Error Record Id invalid."); 
else {
	// trim and strip all input
	foreach($_POST as $key=>$val) {
		if($key != 'button') {
			if(is_string($_POST[$key]))
				$_POST[$key] = stripslashes(strip_tags(trim($val)));
		}
	}
		
	if(!isset($_POST['cminactive'])) 
		$_POST['cminactive']=0;
	
	// Validate form fields
	require_once('validation.php');
	
	if(errorcount() == 0) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$set=array();
		//declare the SQL statement that will query the database
		$query = "UPDATE patients ";
		if(isset($_POST['painactive'])) 
			$set[] = "painactive ='" . mysqli_real_escape_string($dbhandle,$_POST['painactive']) . "'";
		if(isset($_POST['amcnum'])) 
			$set[] .= "amcnum ='" . mysqli_real_escape_string($dbhandle,$_POST['amcnum']) . "'";
		if(isset($_POST['amname'])) 
			$set[] .= "amname='" . mysqli_real_escape_string($dbhandle,$_POST['amname']) . "'";
		if(isset($_POST['amemail'])) 
			$set[] .= "amemail='" . mysqli_real_escape_string($dbhandle,$_POST['amemail']) . "'";
		if(isset($_POST['amphone'])) 
			$set[] .= "amphone='" . mysqli_real_escape_string($dbhandle,$_POST['amphone']) . "'";
		if(count($set) > 0)
			$query .= "SET " . implode(', ', $set);
		$query .= "WHERE amcnum='" . $id . "'";
			
		//execute the SQL query 
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$_SESSION['notify'][] = "Record successfully updated.";
			foreach($_POST as $key=>$val) 
				unset($_POST[$key]);
		}
		else
			error('001', "Error Updating Record : " . mysqli_error($dbhandle)); 	
		//close the connection
		mysqli_close($dbhandle);
	}
}
?>




