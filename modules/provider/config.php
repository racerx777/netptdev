<?php
unset($_SESSION['providers']);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query = "SELECT * FROM master_provider_groups ";
if(isuserlevel(20))
	$query .= "WHERE pgminactive = 0 ";
else
	$query .= "WHERE pgminactive = 0 and pgmcode='" . getuserprovider() . "' ";
$query .= " ORDER BY pgmcode";
$result_id = mysqli_query($dbhandle,$query);
$numRows = mysqli_num_rows($result_id);
$providersarray=array();
for($i=1; $i<=$numRows; $i++) {
	$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
	if($result) 
		$providersarray[$result['pgmcode']] = $result['pgmname'];
}
$_SESSION['providers']=$providersarray;
$_SESSION['init']['provider']=1;
?>