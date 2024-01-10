<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
function getInjuryNatureTypeOptions($includeinactive=0) {
	if($includeinactive == '1')
		$inactivewhere = "1=1";
	else
		$inactivewhere = "imninactive='0'";
	$wheresql = "WHERE $inactivewhere";
	$thislist=array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
	SELECT imncode, imnsdescription 
	FROM master_injury_nature 
	$wheresql 
	ORDER BY imncode";
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisarray=array(0);
			$thisarray['value']=$row['imncode'];
			$thisarray['title']=$row['imnsdescription'];
			$thislist[]=$thisarray;
		}
		return($thislist);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}
function getInjuryBodypartTypeOptions($imbcode=NULL, $includeinactive=0) {
	if(!empty($imbcode))
		$imbcodewhere = "imbcode='$imbcode'";
	else
		$imbcodewhere = "imbcode not like '%*%'";
	if($includeinactive == '0')
		$inactivewhere = "and imbinactive='0'";
	$wheresql = "WHERE $imbcodewhere $inactivewhere";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
	SELECT imbcode, imbsdescription 
	FROM master_injury_bodyparts
	$wheresql 
	ORDER BY imbcode";
	$thislist=array();
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
//			$thislist[] = array('value'=>$row['imbcode'], 'title'=>$row['imbsdescription']);
			$thisarray=array();
			$thisarray['value']=$row['imbcode'];
			$thisarray['title']=$row['imbsdescription'];
			$thislist[$row['imbcode']]=$thisarray;
		}
		return($thislist);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getInjuryDescriptorTypeOptions($imdcode=NULL, $includeinactive=0) {
	if(!empty($imdcode))
		$imdcodewhere = "imdcode='$imdcode'";
	else
		$imdcodewhere = "imdcode not like '%*%'";
	if($includeinactive == '0')
		$inactivewhere = "and imdinactive='0'";
	$wheresql = "WHERE $imdcodewhere $inactivewhere";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
	SELECT imdcode, imdsdescription 
	FROM master_injury_descriptors 
	$wheresql 
	ORDER BY imdcode";
	$thislist=array();
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisarray=array();
			$thisarray['value']=$row['imdcode'];
			$thisarray['title']=$row['imdsdescription'];
			$thislist[]=$thisarray;
		}
		return($thislist);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}
?>