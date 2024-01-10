<?php
unset($_SESSION['clinics']);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query = "SELECT * FROM master_clinics_treatmenttypes ORDER BY cttmcnum, cttmcode";
$result_id = mysqli_query($dbhandle,$query);
$numRows = mysqli_num_rows($result_id);
$array=array();
for($i=1; $i<=$numRows; $i++) {
	$result = mysqli_fetch_array($result_id, MYSQLI_ASSOC);
	if($result) 
		$array[$result['cttmcnum']] = $result['cttmcode'];
}
$_SESSION['clinictherapy']=$array;
$_SESSION['init']['clinictherapy']=1;
?>