<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(5); 

function getDoctorList($dmid=NULL,$includeinactive=0) {
	if(empty($dmid)) 
		$doctorwhere = "1=1";
	else
		$doctorwhere = "dm.dmid='$dmid'";
	if($includeinactive == '1')
		$inactivewhere = "1=1";
	else
		$inactivewhere = "dm.dminactive='0' ";
	$wheresql = "WHERE $doctorwhere and $inactivewhere";
	$doctorslist=array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
	SELECT distinct dm.dmid, dm.dmlname, dm.dmfname, dm.dmnpi
	FROM doctors dm 
		LEFT JOIN doctor_relationships dr 
		ON dm.dmid = dr.drdmid 
			LEFT JOIN doctor_locations dl 
			ON dr.drdlid = dl.dlid 
	$wheresql 
	ORDER BY dm.dmlname, dm.dmfname, dl.dlcity, dl.dlphone";
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisdoctor=array();
			foreach($row as $field=>$value) {
				$thisdoctor["$field"]=$value;
			}
			$doctorlist[$row["dmid"]]=$thisdoctor;
		}
		return($doctorlist);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getDoctorLocationsList($dmid=NULL, $dlid=NULL,$includeinactive=0) {
	return(getDoctorLocationList($dmid, $dlid, $includeinactive));
}

function getDoctorLocationList($dmid=NULL, $dlid=NULL,$includeinactive=0) {

	if(empty($dmid)) 
		$doctorwhere = "1=1";
	else
		$doctorwhere = "dr.drdmid='$dmid'";

	if(empty($dlid)) 
		$locationwhere = "2=2";
	else
		$locationwhere = "dl.dlid='$dlid'";

	if($includeinactive == '1')
		$inactivewhere = "3=3";
	else
		$inactivewhere = "dl.dlinactive='0'";

	$wheresql = "WHERE $doctorwhere and $locationwhere and $inactivewhere ";
	$doctorslist=array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
	SELECT dl.dlid, dl.dlname, dl.dladdress, dl.dlcity, dl.dlstate, dl.dlzip, dl.dlphone 
	FROM doctor_locations dl 
		LEFT JOIN doctor_relationships dr
		ON dl.dlid = dr.drdlid 
	$wheresql
	ORDER BY dl.dlname, dl.dlcity, dl.dlphone";
//	dump("query", $query);
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisdoctor=array();
			foreach($row as $field=>$value) {
				$thisdoctor["$field"]=$value;
			}
			$doctorlist[$row["dlid"]]=$thisdoctor;
		}
		return($doctorlist);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getDoctorLocationsContactsList($dmid=NULL, $dlid=NULL, $includeinactive=0) {
	if(empty($dmid)) 
		$doctorwhere = "1=1";
	else
		$doctorwhere = "dr.drdmid='$dmid'";
	if(empty($dlid)) 
		$locationwhere = "2=2";
	else
		$locationwhere = "dr.drdlid='$dlid'";
	if($includeinactive == '1')
		$inactivewhere = "3=3";
	else
		$inactivewhere = "dls.dlsinactive='0'";
	$wheresql = "WHERE $doctorwhere and $locationwhere and $inactivewhere ";
	$thislist=array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
	SELECT dls.dlsid, dls.dlstitle, dls.dlsname, dls.dlsphone, dls.dlsfax 
	FROM doctor_locations_contacts dls 
		LEFT JOIN doctor_relationships dr 
		ON dls.dlsid = dr.drdlsid 
	$wheresql
	ORDER BY dls.dlsname, dls.dlsphone";
//	dump("query", $query);
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisitem=array();
			foreach($row as $field=>$value) {
				$thisitem["$field"]=$value;
			}
			$thislist[$row['dlsid']]=$thisitem;
		}
		return($thislist);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

?>