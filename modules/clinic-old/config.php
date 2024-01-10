<?php
unset($_SESSION['clinics']);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query = "SELECT cmcnum, cmname FROM master_clinics ";
if(isuserlevel(20))
	$query .= "WHERE cminactive = 0 ";
else
	$query .= "WHERE cminactive = 0 and cmcnum='" . getuserclinic() . "' ";
$query .= " ORDER BY cmcnum";
$result_id = mysqli_query($dbhandle,$query);
$numRows = mysqli_num_rows($result_id);
$clinicsarray=array();
for($i=1; $i<=$numRows; $i++) {
	$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
	if($result) 
		$clinicsarray[$result['cmcnum']] = $result['cmname'];
}
$_SESSION['clinics']=$clinicsarray;
$_SESSION['init']['clinic']=1;
?>