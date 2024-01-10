<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Case Report</title>
</head>
<body>';

$searchcaseSaved = getformvars('case', 'searchcase');
if( !empty($searchcaseSaved) || !empty($_POST['formSubmit']) || !empty($_POST['sortcase']) ) {
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortcaseSaved = getformvars('case', 'searchcaseResults');
	// if Sort button pressed set sort values
	if(count($_POST['sortcase']) > 0) {
	// If Reset Sort Pressed then clear saved values
		if(!empty($_POST['sortcase']['RESETSORT'])) {
			clearformvars('case', 'searchcaseResults');
			unset($sortcaseSaved);
		}
		else {
			// determine sort field name from key
			$sortfield=key($_POST['sortcase']);
			// if that key exists in the sort then toggle collation
			if(array_key_exists($sortfield, $sortcaseSaved)) {
				$collation = $sortcaseSaved["$sortfield"]['collation'];
				if($collation == 'desc')
					$sortcaseSaved["$sortfield"]["collation"] = '';
				else
					$sortcaseSaved["$sortfield"]["collation"] = 'desc';
			}
			else
				$sortcaseSaved["$sortfield"] = $searchcasevars["$sortfield"];
			setformvars('case', 'searchcaseResults', $sortcaseSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query  = "SELECT c.crid, p.palname, p.pafname, p.passn, DATE_FORMAT(p.padob,'%m/%d/%Y') as padob, DATE_FORMAT(c.crinjurydate,'%m/%d/%Y') as crinjurydate, crrefdmid, DATE_FORMAT(c.crdate,'%Y/%m/%d') as crdate, p.paphone1, p.paphone2, p.pacellphone, crcnum, crcasestatuscode, crcasetypecode, crtherapytypecode, crapptdate, crcanceldate
	FROM cases c
	LEFT JOIN patients p ON crpaid=paid ";

	$where = array();

	$i=0;$str='<table >';
	$searchHeaders = ['pafname'=>'First Name','palname'=>'Last Name','padob'=>'DOB','paphone1'=>'Phone','passn'=>'SSN','crinjurydate'=>'DOI','crcnum'=>'Clinic','crcasestatuscode'=>'Status','crrefdmid'=>'Doctor','crdate'=>'Refer Date'];
	foreach($searchcaseSaved as $formvar=>$formvarvalue) {
				
		$str .= "<tr><td style='width:16.66%'>".$searchHeaders[$formvar].": ".$formvarvalue."</td></tr>";

		if( isset($formvarvalue) && !empty($formvarvalue) )  {
			$title = $searchcasevars["$formvar"]['title'];
			$type = $searchcasevars["$formvar"]['type'];
			$dbformat = $searchcasevars["$formvar"]['dbformat'];
			$dblength = $searchcasevars["$formvar"]['dblength'];
			$displayformat = $searchcasevars["$formvar"]['displayformat'];
			$displaylength = $searchcasevars["$formvar"]['displaylength'];
			$length = $searchcasevars["$formvar"]['length'];
			$test = $searchcasevars["$formvar"]['test'];

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

			switch($test):
				case 'LIKE':
					$test = "LIKE '" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "%'";
					break;
				case 'LIKELIKE':
					$test = "LIKE '%" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "%'";
					break;
				default:
					$test = "LIKE '%" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "%'";
			endswitch;
			if($formvar=='paphone1') {
				$where[] = "((paphone1 $test) OR (paphone2 $test) OR (pacellphone $test))";
			}
			else
				$where[] = "$formvar $test";

		}
		
	}

	$str .= "</table>";

	if(count($where) > 0)
		$query .= " WHERE " . implode(" and ", $where);


	$orderby=array();
	if(!empty($sortcaseSaved)) { 
		$sortcasevartitles = "sorted by ";
		foreach($sortcaseSaved as $sortvar=>$sortvarproperty) {
			$orderby[] = trim($sortvar . " " . $sortvarproperty["collation"]);
		}
	}
	if(count($orderby) > 0) {
		$query .= " ORDER BY " . implode(",", $orderby);
	}
	else {
		$query .= " ORDER BY palname, pafname, padob, paphone1, passn";
	}

	$query.=" LIMIT 100 ";
	$result = mysqli_query($dbhandle,$query);
$html .= '<div style="float:left"><img src="./wsptn_logo_bw_outline.jpg" width="300px"></div>
		<div style="float:right;margin-right:100px;">
			<h1>Case Report </h1>
			'.$str.'
		</div><div style="clear:both;">';
	$html .= "<div style='margin-top:20px;'></div>";
	if($result) {
	
	$html .= '<table style="text-align:center; border-collapse: collapse; border: solid;table-layout:fixed;" width="100%">
		<tr>
				<th style="font-size:10px;">Case Id</th>
				<th style="font-size:10px;">Last Name</th>
				<th style="font-size:10px;">First Name</th>
				<th style="font-size:10px;">DOB</th>
				<th style="font-size:10px;">Phone</th>
				<th style="font-size:10px;" >SSN</th>
				<th style="font-size:10px;" >DOI</th>
				<th style="font-size:10px;" >Ref MD ID</th>
                
                <th style="font-size:10px;" >Ref Date</th>
                <th style="font-size:10px;" >Clinic</th>
				<th style="font-size:10px;" >Status</th>

                <th style="font-size:10px;" >Appt Date</th>
				<th style="font-size:10px;" >Can Date</th>
				<th style="font-size:10px;" >Therapy Type</th>
				<th style="font-size:10px;" >Case Type</th>
		</tr>';
		
		while($row = mysqli_fetch_assoc($result)) {
			if($row['crinactive'] == '1') {
				$rowstyle=' style="background-color:#FFFFCC;"';
				$togglebutton = 'Make Active';
			}
			else {
				$rowstyle='';
				$togglebutton = 'Make Inactive';
			}
		
		$html .= '<tr '.$rowstyle.' >
			<td style="font-size:10px;" >'.$row["crid"].'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["palname"].'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["pafname"].'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["padob"].'&nbsp;</td>
			<td style="font-size:10px;" >h:'.displayPhone($row["paphone1"]).' w:'.displayPhone($row["paphone2"]).' c:'.displayPhone($row["pacellphone"]).'&nbsp;</td>
			<td style="font-size:10px;" >'.displaySsn($row["passn"]).'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["crinjurydate"].'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["crrefdmid"].'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["crdate"].'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["crcnum"].'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["crcasestatuscode"].'&nbsp;</td>';
			if(!empty($row["crapptdate"]) && $row["crcasestatuscode"]!='CAN'){
				$html .= '<td style="font-size:10px;" >'.displayDate($row["crapptdate"]) . ' ' . displayTime($row["crapptdate"]).'</td>';
			}else{
				$html .= '<td style="font-size:10px;" ></td>';
			}
			if(!empty($row["crcanceldate"]) && $row["crcasestatuscode"]=='CAN'){
				$html .= '<td style="font-size:10px;" >'.displayDate($row["crcanceldate"]).'</td>';
			}else{
				$html .= '<td style="font-size:10px;" ></td>';
			}
				
			$html .= '<td style="font-size:10px;" >'.$row["crtherapytypecode"].'&nbsp;</td>
			<td style="font-size:10px;" >'.$row["crcasetypecode"].'&nbsp;</td>
			}
		</tr>';
		
		}

	$html .= '</table>';
} mysqli_close($dbhandle); }
$html .= '
<div>
	<p>Print Date : '.date("Y-m-d").'</p>
</div>';
?>
