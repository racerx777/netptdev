<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 259200);
$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Patient Report</title>
</head>
<body>
';
$searchSaved = getformvars('customerservice', 'search');
if( !empty($searchSaved) || !empty($_POST['formSubmit']) || !empty($_POST['sort']) ) {
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars('customerservice', 'searchResults');
	// if Sort button pressed set sort values
	if(count($_POST['sort']) > 0) {
	// If Reset Sort Pressed then clear saved values
		if(!empty($_POST['sort']['RESETSORT'])) {
			clearformvars('customerservice', 'searchResults');
			unset($sortSaved);
		}
		else {
	// determine sort field name from key
			$sortfield=key($_POST['sort']);
	// if that key exists in the sort then toggle collation
			if(array_key_exists($sortfield, $sortSaved)) {
				$collation = $sortSaved["$sortfield"]['collation'];
				if($collation == 'desc')
					$sortSaved["$sortfield"]["collation"] = '';
				else
					$sortSaved["$sortfield"]["collation"] = 'desc';
			}
			else
				$sortSaved["$sortfield"] = $searchvars["$sortfield"];
		setformvars('customerservice', 'searchResults', $sortSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query  = "
		SELECT DISTINCT paid, palname, pafname, DATE_FORMAT(padob, '%m/%d/%Y') as padob, paphone1, passn
		FROM patients p";

    if(isset($searchSaved['pnum']) && $searchSaved['pnum']) {
        //We Don't have easy access to pnum from the patients table so we have to join to cases where we do
        $query .= "LEFT JOIN cases ON crpaid = paid
                   LEFT JOIN PTOS_Patients ON crpnum = pnum";
    }
	$where = array();
	$str='<table >';
	$searchHeaders = ['pnum'=>'Patient Number','pafname'=>'First Name','palname'=>'Last Name','padob'=>'DOB','paphone1'=>'Phone','passn'=>'SSN'];
	foreach($searchSaved as $formvar=>$formvarvalue) {
		if( isset($formvarvalue) && !empty($formvarvalue) )  {
		$str .= "<tr><td style='width:16.66%'>".$searchHeaders[$formvar].": ".$formvarvalue."</td></tr>";
			$title = $searchvars["$formvar"]['title'];
			$type = $searchvars["$formvar"]['type'];
			$dbformat = $searchvars["$formvar"]['dbformat'];
			$dblength = $searchvars["$formvar"]['dblength'];
			$displayformat = $searchvars["$formvar"]['displayformat'];
			$displaylength = $searchvars["$formvar"]['displaylength'];
			$length = $searchvars["$formvar"]['length'];
			$test = $searchvars["$formvar"]['test'];

			$test = "LIKE";


			switch($formvar):
				case 'padob':
					$formvarvalue = date("Y-m-d", strtotime($formvarvalue));
					$test="";
					break;
				case 'paphone1':
					$formvarvalue = dbPhone($formvarvalue);
					$test="";
					break;
				case 'passn':
					$formvarvalue = dbSsn($formvarvalue);
					$test="";
					break;
			endswitch;

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

	$str .= "</table>";


	if(count($where) > 0) {
		$query .= " WHERE " . implode(" and ", $where);

	//
	// Sort Order - Contained in Session variable 'customerservice'=>'searchResults'=>array(field=>collation) as sort
	//

	$orderby=array();
	if(empty($sortSaved)) {
	// default sort here
		$sortvartitles = "unsorted (click column titles to add/toggle sort)";
	}
	else {
		$sortvartitles = "sorted by ";
		foreach($sortSaved as $sortvar=>$sortvarproperty) {
			$orderby[] = trim($sortvar . " " . $sortvarproperty["collation"]);
			$sortvartitles .= trim($sortvarproperty["title"] . " " . $sortvarproperty["collation"]) . ", ";
		}
	}
	if(count($orderby) > 0) {
		$sortvartitles = substr($sortvartitles,0,-2);
		$query .= " ORDER BY " . implode(",", $orderby);
	}
	else {
		$query .= " ORDER BY palname, pafname, padob, paphone1, passn";
	}

	// $query.= " LIMIT 2000 ";
	$result = mysqli_query($dbhandle,$query);

	if($_GET['printpdf']){
		$html .= '<div style="float:left"><img src="../wsptn logo bw outline.jpg" width="300px"></div>
		<div style="float:right;margin-right:100px;">
			<h1>Patient Report </h1>
			'.$str.'
		</div><div style="clear:both;">';
	}else{
		$html .= '<div style="float:left"></div>
		<div style="float:right;margin-right:100px;">
			<h1>Patient Report </h1>
			'.$str.'
		</div><div style="clear:both;">';
	}

		$html .= "<div style='margin-top:20px;'></div>";
	if($result) {
	
	$html .= '<table style="text-align:center; border-collapse: collapse; border: solid;table-layout:fixed;" width="100%">
		<tr>
			<th style="font-size:10px;">Last Name</th>
			<th style="font-size:10px;">First Name</th>
			<th style="font-size:10px;">DOB</th>
			<th style="font-size:10px;">Phone</th>
			<th style="font-size:10px;" >SSN</th>
		</tr>';

		// $new_result = array();
		// while ($row = mysqli_fetch_assoc($result)) {
		// 	$new_result[] = $row;
		// }
		
		while($row = mysqli_fetch_assoc($result)) {
			
			// foreach ($new_result as $key => $row) {
		$html .= '<tr>
			<td style="font-size:10px;" >'.$row["palname"].'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["pafname"].'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["padob"].'&nbsp;</td>
			<td style="font-size:10px;" >h:'.displayPhone($row["paphone1"]).' w:'.displayPhone($row["paphone2"]).' c:'.displayPhone($row["pacellphone"]).'&nbsp;</td>
			<td style="font-size:10px;" >'.displaySsn($row["passn"]).'&nbsp;</td>
			
		</tr>';
		
		}

	$html .= '</table>';
} mysqli_close($dbhandle); }}
$html .= '
<div>
	<p>Print Date : '.date("Y-m-d").'</p>
</div>';
// echo json_encode($pdfs);
?>
