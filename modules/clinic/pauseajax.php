<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$id=$_POST['pause'];
if($_POST['value']=='Pause'){
	$pause="1";
}
elseif($_POST['value']=='UnPause'){
	$pause="0";
}
$query="UPDATE master_clinics SET pausestate = '$pause' WHERE cmid =$id";

if (mysqli_query($dbhandle, $query)) {
  echo "Record updated successfully";
} else {
  echo "Error While Updating Record";
}
?>