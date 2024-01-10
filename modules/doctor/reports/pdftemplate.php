<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(40); 
/*error_reporting(E_ALL);
ini_set('display_errors',true);*/
$html = "";
$searchdoctorSaved = getformvars('doctor', 'searchdoctor');
if( !empty($searchdoctorSaved) || !empty($_POST['buttonSearchDoctor']) || !empty($_POST['sortdoctor']) ) {
	
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortdoctorSaved = getformvars('doctor', 'searchdoctorResults');
	// if Sort button pressed set sort values
	if(count($_POST['sortdoctor']) > 0) {
	// If Reset Sort Pressed then clear saved values
		if(!empty($_POST['sortdoctor']['RESETSORT'])) {
			clearformvars('doctor', 'searchdoctorResults');
			unset($sortdoctorSaved);
		}
		else {
	// determine sort field name from key
			$sortfield=key($_POST['sortdoctor']);
	// if that key exists in the sort then toggle collation
			if(array_key_exists($sortfield, $sortdoctorSaved)) {
				$collation = $sortdoctorSaved["$sortfield"]['collation'];
				if($collation == 'desc')
					$sortdoctorSaved["$sortfield"]["collation"] = '';
				else
					$sortdoctorSaved["$sortfield"]["collation"] = 'desc';
			}
			else
				$sortdoctorSaved["$sortfield"] = $searchdoctorvars["$sortfield"];
			setformvars('doctor', 'searchdoctorResults', $sortdoctorSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query  = "SELECT dmid, dminactive, dmsname, dmlname, dmfname, dmdscode, dmdclass, dmestrefer, dmdob FROM doctors";
	$where = array();
	$formvalarray = array();

	foreach($searchdoctorSaved as $formvar=>$formvarvalue) {
		if( isset($formvarvalue) && !empty($formvarvalue) )  {

			$formvalarray[$formvar] = $formvarvalue;
			$title = $searchdoctorvars["$formvar"]['title'];
			$type = $searchdoctorvars["$formvar"]['type'];
			$dbformat = $searchdoctorvars["$formvar"]['dbformat'];
			$dblength = $searchdoctorvars["$formvar"]['dblength'];
			$displayformat = $searchdoctorvars["$formvar"]['displayformat'];
			$displaylength = $searchdoctorvars["$formvar"]['displaylength'];
			$length = $searchdoctorvars["$formvar"]['length'];
			//$test = $searchdoctorvars["$formvar"]['test'];
			$test = "LIKE";
			switch($dbformat):
				case 'date':
					$formvarvalue = date("Y-m-d", strtotime($formvarvalue));
					break;
				case 'phone':
					$formvarvalue = dbPhone($formvarvalue);
					break;
				case 'ssn':
					$formvarvalue = dbSsn($formvarvalue);
					break;
			endswitch;

			$formvarvalue = $formvarvalue;
			if($formvar=="dmdob")
				$formvarvalue = date("Y-m-d", strtotime($formvarvalue));

			switch($test):
				case 'LIKE':
					$test = "LIKE '" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "%'";
					break;
				default:
					$test = "= '" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "'";
			endswitch;


			$where[] = "$formvar $test";
		}
	}
	if(count($where) > 0) 
		$query .= " WHERE " . implode(" and ", $where);

	// Sort Order - Contained in Session variable 'customerservice'=>'searchResults'=>array(field=>collation) as sort
	$orderby=array();
	if(empty($sortdoctorSaved)) 
		$sortdoctorvartitles = "unsorted (click column titles to add/toggle sort)";
	else {
		$sortdoctorvartitles = "sorted by ";
		foreach($sortdoctorSaved as $sortvar=>$sortvarproperty) {
			$orderby[] = trim($sortvar . " " . $sortvarproperty["collation"]);
			$sortdoctorvartitles .= trim($sortvarproperty["title"] . " " . $sortvarproperty["collation"]) . ", ";
		}
	}
	if(count($orderby) > 0) {
		$sortdoctorvartitles = substr($sortdoctorvartitles,0,-2);
		$queryorder = " ORDER BY " . implode(",", $orderby);
	}
	else 
		$queryorder = " ORDER BY dminactive, dmsname, dmlname, dmfname"; 

$query="$query $queryorder";
$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Doctor Report </title>
</head>
<body>
<div style="float:left"><img src="../wsptn_logo_bw_outline.jpg" width="300px"></div>
<div style="float:right;margin-right:100px;">
	<h1>Doctor Report </h1>';
$varname = $varval = "";

foreach ($formvalarray as $key => $value) {

	switch ($key) {
		case 'dmsname':
			$varname = "Short Name";
			$varval  = $value;
			break;
		case 'dmlname':
			$varname = "Last Name";
			$varval  = $value;
			break;
		case 'dmfname':
			$varname = "First Name";
			$varval  = $value;
			break;
		case 'dmdscode':
			$varname = "Specialty";
			$varval  = $_SESSION['dscodes'][$value];
			break;
		case 'dmdclass':
			$varname = "MD Class";
			$varval  = $value;
			break;
		case 'dmestrefer':
			$varname = "Est Referrals";
			$varval  = $value;
			break;
		case 'dmdob':
			$varname = "DOB";
			$varval  = $value;
			break;
		case 'drdlid':
			$varname = "Loc ID";
			$varval  = $value;
			break;
	}
	$html .= '<p>'.$varname.' : ' .$varval.'</p>';
	
}

	

$html .='</div>
<div style="clear:both;">';
//dump("query",$query);
if($result = mysqli_query($dbhandle,$query)) {
	$numRows = mysqli_num_rows($result);
	//execute the SQL query and return records

	
	if($numRows > 0) {
		
		$html .= '<table border="1" cellpadding="3" cellspacing="0" width="100%">';

		$html .= '<tr><th>Short Name</th>
					<th>Last Name</th>
					<th>First Name</th>
					<th>Specialty</th>
					<th>MD Class</th>
					<th>Est Referrals</th>
					<th>DOB</th>
				</tr>';
			
			
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			
				$dmsname = "";
				if(!empty($row['dmsname'])) $dmsname = $row["dmsname"]; else $dmsname = "(id:".$row['dmid'].")";

				$html .= '<tr>';
				 
				$html .= '<td>'.$dmsname.'&nbsp;</td>
					<td>'.$row["dmlname"].'&nbsp;</td>
					<td>'.$row["dmfname"].'&nbsp;</td>
					<td>'.$_SESSION['dscodes'][$row["dmdscode"]].'&nbsp;</td>
					<td>'.$_SESSION['dclasses'][$row["dmdclass"]].'&nbsp;</td>
					<td>'.$row["dmestrefer"].'&nbsp;</td>
					<td>'.displayDate($row["dmdob"]).'&nbsp;</td>
				</tr>';
			} // while
			
		$html .= '</table>';
	

		
	}
}
//close the connection
mysqli_close($dbhandle);
}
$html .= '
<div>
	<p>Print Date : '.date("Y-m-d").'</p>
</div>';
?>