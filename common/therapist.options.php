<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
function getTherapistTypeOptions($cnum=NULL, $ttmcode=NULL, $therap=NULL, $inactive=0) {
	$thislist=array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	if(!empty($cnum))
		$where[] = "ctcnum='$cnum'";
	if(!empty($ttmcode))
		$where[] = "ctttmcode='$ttmcode'";
	if(!empty($therap))
		$where[] = "cttherap='$therap'";
//	if(empty($inactive))
//		$where[] = "ctinactive='0'";
	if(count($where)>0) 
		$wheresql = "WHERE ".implode(" and ", $where);
	$query = "
	SELECT cmcnum, ctttmcode, cttherap, tname 
	FROM master_clinics 
	LEFT JOIN master_clinics_therapists
		on cmcnum=ctcnum 
	LEFT JOIN therapists
		on cttherap=ttherap
	$wheresql 
	ORDER BY cmcnum, ctttmcode, tname";
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisarray=array();
			$thisarray['value']=$row['cttherap'];
			$thisarray['title']=$row['tname'];
			$thislist[]=$thisarray;
		}
		return($thislist);
	}
	else 
		error("001",mysqli_error($dbhandle));
	return(false);
}
?>