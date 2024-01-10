<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);

function getInsuranceCompaniesOptions($code=NULL, $includeinactive=0) {
	if(!empty($code))
		$codewhere = "icid='$code'";
	else
		$codewhere = "icid not like '%*%'";
	if($includeinactive == '0')
		$inactivewhere = "and icinactive='0'";
	$wheresql = "WHERE $codewhere $inactivewhere";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
	SELECT icid, icname, icprintname, iciscode, iciclass, icnotes1, icnotes2
	FROM insurance_companies
	$wheresql
	ORDER BY icname";
	$thislist=array();
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisarray=array();
			$thisarray['value']=$row['icid'];
			$thisarray['title']=$row['icname'];
			$thislist[]=$thisarray;
		}
		return($thislist);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getInsuranceCompanyInformation($code, $includeinactive=0) {
	if(!empty($code))
		$codewhere = "icid='$code'";
	else
		$codewhere = "icid not like '%*%'";
	if($includeinactive == '0')
		$inactivewhere = "and icinactive='0'";
	$wheresql = "WHERE $codewhere $inactivewhere";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT *
		FROM insurance_companies
		$wheresql
		ORDER BY icname
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

function getInsuranceCompaniesLocationsOptions($icid=NULL, $id=NULL, $includeinactive=0) {
	$list=array();

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	$whereitem=array();
	if(!empty($icid))
		$whereitem[] = "iclicid='$icid'";
	if(!empty($id))
		$whereitem[] = "iclid='$id'";
	if($includeinactive != '1')
		$whereitem[] = "iclinactive='0'";

	if(count($whereitem)==0)
		$wheresql="";
	else
		$wheresql='WHERE ' . implode(" and ", $whereitem);

	$query = "
	SELECT *
	FROM insurance_companies_locations
	$wheresql
	ORDER BY iclname, iclcity, iclphone";
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisrow=array();
			foreach($row as $field=>$value) {
				$thisrow["$field"]=$value;
			}
			$key=$row['iclid'];
			$list["$key"]=$thisrow;
		}
		return($list);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getInsuranceCompaniesAdjustersOptions($icid=NULL, $iclid=NULL, $id=NULL, $includeinactive=0) {
	$list=array();

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	$whereitem=array();
	if(!empty($icid))
		$whereitem[] = "icaicid='$icid'";
	if(!empty($iclid))
		$whereitem[] = "icaiclid = '$iclid'";
	if(!empty($id))
		$whereitem[] = "icaid = '$id'";
	if($includeinactive != '1')
		$whereitem[] = "icainactive='0'";

	if(count($whereitem)==0)
		$wheresql="";
	else
		$wheresql='WHERE ' . implode(" and ", $whereitem);

	$query = "
	SELECT *
	FROM insurance_companies_adjusters
	$wheresql
	ORDER BY icalname, icafname, icacity, icaphone";
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisrow=array();
			foreach($row as $field=>$value) {
				$thisrow["$field"]=$value;
			}
			$key=$row['icaid'];
			$list["$key"]=$thisrow;
		}
		return($list);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getInsuranceCompaniesList($includeinactive=0) {
	$list=array();

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	$whereitem=array();
	if($includeinactive != '1')
		$whereitem[] = "icinactive='0'";
	if(count($whereitem)==0)
		$wheresql="icinactive='0'";
	else
		$wheresql='WHERE ' . implode(" and ", $whereitem);

	$query = "
	SELECT *
	FROM insurance_companies
	$wheresql
	ORDER BY icname";
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisrow=array();
			foreach($row as $field=>$value) {
				$thisrow["$field"]=$value;
			}
			$key=$row['icid'];
			$list["$key"]=$thisrow;
		}
		return($list);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getInsuranceCompaniesLocationsList($icid=NULL, $includeinactive=0) {
	$list=array();

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	$whereitem=array();
	if(!empty($icid))
		$whereitem[] = "iclicid='$icid'";
	if($includeinactive != '1')
		$whereitem[] = "iclinactive='0'";

	if(count($whereitem)==0)
		$wheresql="";
	else
		$wheresql='WHERE ' . implode(" and ", $whereitem);

	$query = "
	SELECT *
	FROM insurance_companies_locations
	$wheresql
	ORDER BY iclname, iclcity, iclphone";
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisrow=array();
			foreach($row as $field=>$value) {
				$thisrow["$field"]=$value;
			}
			$key=$row['iclid'];
			$list["$key"]=$thisrow;
		}
		return($list);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getInsuranceCompaniesAdjustersList($icid=NULL, $iclid=NULL, $includeinactive=0) {
	$list=array();

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

	$whereitem=array();
	if(!empty($icid))
		$whereitem[] = "icaicid='$icid'";
	if(!empty($iclid))
		$whereitem[] = "icaiclid = '$iclid'";
	if($includeinactive != '1')
		$whereitem[] = "icainactive='0'";

	if(count($whereitem)==0)
		$wheresql="";
	else
		$wheresql='WHERE ' . implode(" and ", $whereitem);

	$query = "
	SELECT *
	FROM insurance_companies_adjusters
	$wheresql
	ORDER BY icalname, icafname, icacity, icaphone";
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisrow=array();
			foreach($row as $field=>$value) {
				$thisrow["$field"]=$value;
			}
			$key=$row['icaid'];
			$list["$key"]=$thisrow;
		}
		return($list);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

function getPTOSInsuranceCompaniesOptions($bnum, $icode=NULL) {
	$where=array();
	if(!empty($bnum))
		$where[] = "bnum='$bnum'";
	if(!empty($icode))
		$where[] = "icode='$icode'";

	$where[] = "iname <> ''";
	$wheresql = "WHERE ".implode(' and ',$where);;

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT *
		FROM PTOS_Insurance
		$wheresql
		ORDER BY iname
	";
	$thislist=array();
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$thisarray=array();
			$thisarray['value']=$row['icode'];
			$thisarray['title']=trim($row['iname'])." ".trim($row['iadd1'])." ".trim($row['iadd2'])." ".trim($row['iadd3'])." ".trim($row['iphone']);
			$thislist[]=$thisarray;
		}
		return($thislist);
	}
	else {
		error("901","Error selecting Insurance Company List.<br>QUERY:$query<br>".mysqli_error($dbhandle));
		return(false);
	}
}

function getPTOSInsuranceCompanyInformation($bnum, $icode) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT *
		FROM PTOS_Insurance
		WHERE bnum='$bnum' and icode='$icode'
		ORDER BY iname
	";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_assoc($result)) {
			$thisarray=array();
			foreach($row as $field=>$value)
				$thisarray["$field"]=$value;
			return($thisarray);
		}
	}
	else
		error("001",mysqli_error($dbhandle));
	return(false);
}

?>