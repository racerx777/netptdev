<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
errorclear();
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$query0 = "DELETE FROM treatment_procedures WHERE thid='" . $_SESSION['id'] . "'";
$result0 = mysqli_query($dbhandle,$query0);
if(!$result0) 
	error('001', mysqli_error($dbhandle));
addheaderhistory($_SESSION['id'], date('Y-m-d H:i:s', time()), $_SESSION['user']['umuser'], 0, $_SESSION['application'], 'DELETE', 'Delete Treatment Procedures', $query0);

$query1 = "DELETE FROM treatment_modalities WHERE thid='" . $_SESSION['id'] . "'";
$result1 = mysqli_query($dbhandle,$query1);
if(!$result1) 
	error('001', mysqli_error($dbhandle));
addheaderhistory($_SESSION['id'], date('Y-m-d H:i:s', time()), $_SESSION['user']['umuser'], 0, $_SESSION['application'], 'DELETE', 'Delete Treatment Modalities', $query1);

$query2 = "DELETE FROM treatment_procedure_groups WHERE thid='" . $_SESSION['id'] . "'";
$result2 = mysqli_query($dbhandle,$query2);
if(!$result2) 
	error('001', mysqli_error($dbhandle));
addheaderhistory($_SESSION['id'], date('Y-m-d H:i:s', time()), $_SESSION['user']['umuser'], 0, $_SESSION['application'], 'DELETE', 'Delete Treatment Procedure Groups', $query2);

$query3 = "DELETE FROM treatment_header WHERE thid='" . $_SESSION['id'] . "'";
$result3 = mysqli_query($dbhandle,$query3);
if(!$result3) 
	error('001', mysqli_error($dbhandle));
addheaderhistory($_SESSION['id'], date('Y-m-d H:i:s', time()), $_SESSION['user']['umuser'], 0, $_SESSION['application'], 'DELETE', 'Delete Treatment Header', $query3);

if($result1 && $result2 && result3) {
	$_SESSION['notify'][] = "Record successfully removed from current treatment list.";
	unset($_POST);
}
mysqli_close($dbhandle);
?>