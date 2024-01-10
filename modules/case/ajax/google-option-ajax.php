<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/injury.options.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/clinic.options.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/therapist.options.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/sitedivs.php');
if($_POST['check'] == 'option_load'){
	echo json_encode(getSelectOptions($arrayofarrayitems=getClinicTypeOptions($_POST['crtherapytypecode']), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')', ' ', 'distance'=>' '), $defaultoption=$_POST['crcnum'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array()));die;
}else{
	$dbhandle = dbconnect();
	$sql = "DELETE FROM case_google_option WHERE case_id='".$_POST['crid']."'";
	mysqli_query($dbhandle, $sql);
	mysqli_close($dbhandle);
}