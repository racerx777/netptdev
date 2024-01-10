<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function getClinicInformation($code, $includeinactive=0) {
	$where=array();
	if(!empty($code))
		$where[] = "cmcnum='$code'";

	if($includeinactive == '1')
		$where[] = "cminactive='1'";

	if($includeinactive == '0')
		$where[] = "cminactive='0'";

	$wheresql=implode(" AND ",$where);
	if(!empty($wheresql))
		$wheresql = "WHERE $wheresql";
	$query = "
		SELECT *
		FROM master_clinics 
		$wheresql 
		ORDER BY cmname
		LIMIT 1
	";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_assoc($result)) {
			$thisarray=array();
			foreach($row as $field=>$value) 
				$thisarray["$field"]=$value;
			return($thisarray);
		}
	}
	else {
		error("001","getClinicInformation<br>$query<br>".mysqli_error($dbhandle));
	}
	return(false);
}

function getClinicTypeOptions($ttmcode=NULL, $inactive=0) {
	$thislist=array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	if(!empty($ttmcode))
		$where[] = "cttmttmcode='$ttmcode'";
	if(empty($inactive))
		$where[] = "cminactive='0'";
	if(count($where)>0) 
		$wheresql = "WHERE ".implode(" and ", $where);
	$query = "
	SELECT cmcnum, cmname, cmcity 
	FROM master_clinics 
	LEFT JOIN master_clinics_treatmenttypes
		on cmcnum=cttmcnum 
	$wheresql 
	ORDER BY cmname
	";
//dump("query",$query);
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisarray=array();
			$thisarray['value']=$row['cmcnum'];
			$thisarray['title']=$row['cmname'] . ", " . $row['cmcity'];
			$thislist[]=$thisarray;
		}
		return($thislist);
	}
	else 
		error("001",mysqli_error($dbhandle));
	return(false);
}

function getMaster_Clinics($bnum=NULL, $cnum=NULL, $inactive=NULL) {
	$array = array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	if( !empty( $bnum ) )
		$wherearray[] = "cmbnum='$bnum'";
	if( !empty( $cnum ) )
		$wherearray[] = "cmcnum='$cnum'";
	if( empty( $inactive ) )
		$wherearray[]="cminactive='0'";
	if( count($wherearray) > 0)
		$where = 'WHERE '. implode(" and ", $wherearray);
	$query  = "
		SELECT * 
		FROM master_clinics 
		$where 
		";
	if($result = mysqli_query($dbhandle,$query)) {
		while($row = mysqli_fetch_assoc($result)) {
			$cmbnum=$row['cmbnum'];
			$cmcnum=$row['cmcnum'];
			$array[$cmbnum][$cmcnum] = $row;
		}
	}
	if(count($array)==1)
		return($array[$cmbnum][$cmcnum]);
	return($array);
}
?>