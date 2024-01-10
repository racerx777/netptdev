<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
function getUserList($includeinactive=0) {
	$list=array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$whereitem=array();
	if($includeinactive != '1')
		$whereitem[] = "uminactive='0'";
	if(count($whereitem)==0) 
		$wheresql="uminactive='0'";
	else
		$wheresql='WHERE ' . implode(" and ", $whereitem);
	$query = "
	SELECT * 
	FROM master_user 
	$wheresql
	ORDER BY umname";
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisrow=array();
			foreach($row as $field=>$value) {
				$thisrow["$field"]=$value;
				$unique = array_map("unserialize", array_unique(array_map("serialize", $thisrow)));
			}
			$key=$row['umid'];
			$list["$key"]=$thisrow;
		}
		return($list);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getUserInformation($id, $includeinactive=0) {
	if($includeinactive == '0')
		$inactivewhere = "and uminactive='0'";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT *
		FROM master_user 
		WHERE umid='$id' $inactivewhere 
		ORDER BY umname
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
		error("001",mysqli_error($dbhandle));
	}
	return(false);
}
?>