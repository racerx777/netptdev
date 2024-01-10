<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 

function getCourtOptions($defaultcode=NULL, $code=NULL, $includeinactive=0) {
	$thislist = getSelectOptions(
		$arrayofarrayitems=getCourtList($code, $includeinactive), 
		$optionvaluefield='value', 
		$arrayofoptionfields=array(
			'title'=>'' 
			), 
		$defaultoption=$defaultcode, 
		$addblankoption=TRUE, 
		$arraykey='', 
		$arrayofmatchvalues=array()); 

	return($thislist);
}

function getCourtList($code=NULL, $includeinactive=0) {
	$where=array();
	if(!empty($code))
		$where[] = "cid='$code'";
	if($includeinactive == '0')
		$where[] = "cinactive='0'";
	if(count($where)>0)
		$wheresql = "WHERE ".implode(" and ",$where);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT *
		FROM courts 
		$wheresql 
		ORDER BY cname
	";
//	dump("query", $query);
	$thislist=array();
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisarray=array();
			$thisarray['value']=$row['cid'];
			$thisarray['title']=$row['clocation'];
			$thislist[]=$thisarray;
		}
		return($thislist);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getCourtInformation($code, $includeinactive=0) {
	$where=array();
	if(!empty($code))
		$where[] = "cid='$code'";
	if($includeinactive == '0')
		$where[] = "cinactive='0'";
	if(count($where)>0)
		$wheresql = "WHERE ".implode(" and ",$where);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT *
		FROM courts 
		$wheresql 
		ORDER BY cname
		LIMIT 1
	";
//	dump("query", $query);
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_assoc($result)) {
			$thisarray=array();
			foreach($row as $field=>$value) 
				$thisarray["$field"]=$value;
			return($thisarray);
		}
			error("002",mysqli_error($dbhandle));
	}
	else {
		error("01x",mysqli_error($dbhandle));
	}
	return(false);
}
?>