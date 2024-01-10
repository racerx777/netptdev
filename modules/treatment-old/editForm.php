<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
errorclear();
/*error_reporting(E_ALL);
ini_set('display_errors',true);
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query1 = "SELECT * FROM treatment_header WHERE thid='" . $_SESSION['id'] . "'";
//execute the SQL query and return records
$result1 = mysqli_query($dbhandle,$query1);
$numRows1 = mysqli_num_rows($result1);
if($numRows1==1) {
	$row1 = mysqli_fetch_array($result1,MYSQLI_ASSOC);
	foreach($row1 as $key=>$val) {
		$_POST[$key] = $val;
	}

	if(isuserlevel(23) || $_SESSION['user']['umrole'] == 10) {
		$query2 = "SELECT pmcode,qty FROM treatment_procedures WHERE thid='" . $_SESSION['id'] . "'";
		$result2 = mysqli_query($dbhandle,$query2);
		$numRows2 = mysqli_num_rows($result2);
		$procedure=array();
		for($i=1; $i<=$numRows2; $i++) {
			$row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
			$procedure[$_POST["thttmcode"]][$row2['pmcode']] = $row2['pmcode'];
			$procedure[$_POST["thttmcode"]][$row2['pmcode']] = $row2['qty'];
		}
		$_POST['individualprocedures'] = $procedure;
	}
	else {
		$query2 = "SELECT gmcode FROM treatment_procedure_groups WHERE thid='" . $_SESSION['id'] . "'";
		$result2 = mysqli_query($dbhandle,$query2);
		$numRows2 = mysqli_num_rows($result2);
		$procedure=array();
		for($i=1; $i<=$numRows2; $i++) {
			$row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
			$procedure[$_POST["thttmcode"]][$row2['gmcode']] = $row2['gmcode'];
		}
		$_POST['procedure'] = $procedure;
	}

	$query3 = "SELECT mmcode FROM treatment_modalities WHERE thid='" . $_SESSION['id'] . "'";
	$result3 = mysqli_query($dbhandle,$query3);
	$numRows3 = mysqli_num_rows($result3);
	$modalities=array();
	for($i=1; $i<=$numRows3; $i++) {
		$row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC);
		$modalities[$_POST["thttmcode"]][$row3['mmcode']] = $row3['mmcode'];
	}
	$_POST['modalities'] = $modalities;

	$query3a = "SELECT mmcode FROM treatment_modalities WHERE thid='" . $_SESSION['id'] . "'";
	$result3a = mysqli_query($dbhandle,$query3a);
	$numRows3a = mysqli_num_rows($result3a);
	$supplymodalities=array();
	for($i=1; $i<=$numRows3a; $i++) {
		$row3a = mysqli_fetch_assoc($result3a);
		$supplymodalities[$_POST["thttmcode"]][$row3a['mmcode']] = $row3a['mmcode'];
	}
	$_POST['supplymodalities'] = $supplymodalities;
}
else {
	echo('Error retrieving record. Uniqueness.');
}
?>