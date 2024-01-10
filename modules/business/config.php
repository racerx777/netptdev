<?php
unset($_SESSION['business']);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query = "SELECT * FROM master_business_units ";
if(isuserlevel(20))
	$query .= "WHERE buminactive = 0 ";
else
	$query .= "WHERE buminactive = 0 and bumcode='" . getuserbusiness() . "' ";
$query .= " ORDER BY bumcode";
$result_id = mysqli_query($dbhandle,$query);
$numRows = mysqli_num_rows($result_id);
$businessunitsarray=array();
for($i=1; $i<=$numRows; $i++) {
	$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
	if($result) 
		$businessunitsarray[$result['bumcode']] = $result['bumname'];
}
$_SESSION['businessunits']=$businessunitsarray;
$_SESSION['init']['businessunit']=1;
?>