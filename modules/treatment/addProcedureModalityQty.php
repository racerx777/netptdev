<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
if(isset($_POST['addProcedureModalityQty'])){
	$thid = $_POST['thid'];
	$qty = $_POST['qty'];
	$table = $_POST['table'];
	$pmcode = $_POST['pmcode'];
	$codekey = $_POST['codekey'];

	$query = "UPDATE $table set qty=".$qty." where thid=".$thid." and $codekey='".$pmcode."'";
	if(mysqli_query($dbhandle,$query)){
		echo json_encode(array(['status'=>1]));
	}else{
		echo json_encode(array(['status'=>0,'error'=>mysqli_error($dbhandle)]));
	}
}


?>