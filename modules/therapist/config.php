<?php
unset($_SESSION['therapist']);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query = "SELECT * FROM therapists ORDER BY tname";
$result_id = mysqli_query($dbhandle,$query);
$numRows = mysqli_num_rows($result_id);
$therapistsarray=array();
for($i=1; $i<=$numRows; $i++) {
	$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
	if($result) 
		$therapistsarray[$result['ttherap']] = $result['tname'];
}
$_SESSION['therapist']=$therapistsarray;
$_SESSION['init']['therapist']=1;
?>