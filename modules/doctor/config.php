<?php
function getdscodes() {
	if(!isset($_SESSION['dscodes']) || (isset($_SESSION['dscodes']) && (count($_SESSION['dscodes'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT dscode, dsdesc FROM doctor_specialties ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$dscodesarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result)
				$dscodesarray[$result['dscode']] = $result['dsdesc'];
		}
		return($dscodesarray);
	}
	else
		return($_SESSION['dscodes']);
}

function getdclasses() {
	if(!isset($_SESSION['dclasses']) || (isset($_SESSION['dclasses']) && (count($_SESSION['dclasses'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT dccode, dcdesc FROM doctor_classes ";
//		dump("query",$query);
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$dccodesarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result)
				$dccodesarray[$result['dccode']] = $result['dcdesc'];
		}
		return($dccodesarray);
	}
	else
		return($_SESSION['dclasses']);
}

function getdlterritory() {
	if(!isset($_SESSION['dlterritory']) || (isset($_SESSION['dlterritory']) && (count($_SESSION['dlterritory'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT mstnum, mstname FROM master_sales_territory ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$dlterritoryarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) {
				$dlterritoryarray[$result['mstnum']] = $result['mstname'];
            }
		}
		return($dlterritoryarray);
	}
	else
		return($_SESSION['dlterritory']);
}

$_SESSION['dscodes']=getdscodes();
$_SESSION['dclasses']=getdclasses();
$_SESSION['dlterritory']=getdlterritory();
$_SESSION['init']['doctor']=1;
?>