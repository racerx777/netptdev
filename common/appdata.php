<?php
//function gethomepages() {
//	$homepages=array('patientdashboard'=>'Patient Entry Dashboard', 'billingdashboard'=>'Billing Dashboard', 'treatmentdashboard'=>'Treatment Dashboard', 'treatment'=>//'Treatment Entry', 'user'=>'User Management', 'clinic'=>'Clinic Management', 'patient'=>'Patient/Case Management', 'customerservice'=>'Customer Service');
//	return($homepages);
//}

//function getroles() {
//	$roles=array(
//			'99'=>'System Administrator', 
//			'70'=>'Corporate Management', 
//			'25'=>'Corporate Customer Service', 
//			'24'=>'Corporate Scheduling', 
//			'23'=>'Corporate UR', 
//			'22'=>'Corporate Billing Entry', 
//			'21'=>'Corporate Patient Entry', 
//			'10'=>'Clinic User');
//	return($roles);
//}

function getHomePages($all=NULL) {
	$returnarray = array();
	if(isset($all) || !isset($_SESSION['site']['homepages']) || (isset($_SESSION['site']['homepages']) && (count($_SESSION['site']['homepages'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		$query = "SELECT hpmid, hpmcode, hpmname FROM master_homepages ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		while($result = mysqli_fetch_assoc($result_id)) {
			$returnarray[$result['hpmcode']] = $result;
		}
		return($returnarray);
	}
	else
		return($_SESSION['site']['homepages']);
}

function getRoles($all=NULL) {
	$returnarray = array();
	if(isset($all) || !isset($_SESSION['site']['roles']) || (isset($_SESSION['site']['roles']) && (count($_SESSION['site']['roles'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		$query = "SELECT rmid, rmcode, rmname FROM master_roles ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		while($result = mysqli_fetch_assoc($result_id)) {
			$returnarray[$result['rmcode']] = $result;
		}
		return($returnarray);
	}
	else
		return($_SESSION['site']['roles']);
}

function getappdata() {
	$userhomepage=$_SESSION['user']['umhomepage'];
	$userrole=$_SESSION['user']['umrole'];
	$homepages = getHomePages();
	$roles = getRoles();
	$_SESSION['site']['homepages']["$userhomepage"] = $homepages["$userhomepage"];
	$_SESSION['site']['roles']["$userrole"] = $roles["$userrole"];
}
?>