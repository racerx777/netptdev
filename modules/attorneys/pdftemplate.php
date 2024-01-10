<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
$html = '';
$sql = array();
ini_set('memory_limit', '3000M');
ini_set('max_execution_time', 0);
header('Content-Type: text/html; charset=utf-8');
if (!empty($_GET['fname'])) {
	$sql[] = "attorney.name_first LIKE '%" . $_GET['fname'] . "%'";
}
if (!empty($_GET['lname'])) {
	$sql[] = "attorney.name_last LIKE '%" . $_GET['lname'] . "%'";
}
if (!empty($_GET['city'])) {
	$sql[] = "attorney.city LIKE '%" . $_GET['city'] . "%'";
}
if (!empty($_GET['zip'])) {
	$sql[] = "attorney.zip LIKE '%" . $_GET['zip'] . "%'";
}
if (!empty($_GET['firmname'])) {
	header('Content-Type: text/html; charset=utf-8');
	$sql[] = "attorney_firm.firm_name LIKE '%" . $_GET['firmname'] . "%'";
}

if (!empty($_GET['total_rows'])) {
	$total_rows = $_GET['total_rows'];
} else {
	$total_rows = "";
}

if (!empty($_GET['appliedSearch'])) {
	$appliedSearch = $_GET['appliedSearch'];
} else {
	$appliedSearch = "";
}

if (!empty($sql)) {
	$count = 0;
	foreach ($sql as $keyword) {
		if ($count == 0) {
			$query .= $keyword;
		} else {
			$query .= ' AND ' . $keyword;
		}
		$count++;
	}
	$prepare = "SELECT * FROM attorney INNER JOIN attorney_firm ON attorney.firm = attorney_firm.firm_id WHERE $query";
} else {
	$prepare = "SELECT * from attorney INNER JOIN attorney_firm ON attorney.firm = attorney_firm.firm_id ORDER BY name_first, created_date";
}
$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Attorneys Report</title>
</head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4682B4;
  color: white;
}
#searchfilter {
	font-size: 14px;
}
</style>
<body>


<table style="width: 1025px; margin: 0 auto; padding: 0px; border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif;" align="center">
	<tr>
		<td align="left" style="text-align: left; width: 50%;">
			<img src="wsptn logo bw outline.jpg" width="300px">
			<p>' . $total_rows . ' Attorneys(s) found.</p>
			<p id="searchfilter">Search Filter(s) applied: ' . $appliedSearch . '</p>
		</td>
		<td  align="right" style="text-align: right; width: 50%;">
			<h1>Attorneys Report</h1>
			<p>Report Date: ' . date("m/d/Y") . '</p>
		</td>
	</tr>
</table>



<div style="clear:both;">';
// $prepare .= 'LIMIT 1500';
if ($result = mysqli_query($dbhandle, $prepare)) {
	$html .= '<table id="customers" style="text-align:center; margin: 0 auto; padding: 0px; border-collapse: collapse; border: solid;  width="1025px" align="center">
				<tr>
					<th style="font-size:10px;text-align: center">Firm Name</th>
					<th style="font-size:10px;text-align: center">First Name</th>
					<th style="font-size:10px;text-align: center">Middle Name</th>
					<th style="font-size:10px;text-align: center">Last Name</th>
					<th style="font-size:10px;text-align: center">Address</th>
					<th style="font-size:10px;text-align: center">Suite No</th>
					<th style="font-size:10px;text-align: center">City</th>
					<th style="font-size:10px;text-align: center">State</th>
					<th style="font-size:10px;text-align: center">Zip</th>
					<th style="font-size:10px;text-align: center">Phone</th>
					<th style="font-size:10px;text-align: center">Email</th>
				</tr>';

	$new_result = array();
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$new_result[] = $row;
	}

	// $flag = 0;
	//    $chunks = array_chunk($new_result, 100);
	foreach ($new_result as $key => $row) {
		// $flag++;  
		$html .= '<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
				<td align="center" style="font-size:10px;width:7%" >' . $row['firm_name'] . '</td>
				<td align="center" style="font-size:10px;width:7%" >' . $row['name_first'] . '</td>
				<td align="center" style="font-size:10px;width: 7%" >' . $row['name_middle'] . '</td>
				<td align="center" style="font-size:10px;width:7%" >' . $row['name_last'] . '</td>
				<td align="center" style="font-size:10px;width:10%" >' . $row['address'] . '</td>
				<td align="center" style="font-size:10px;width:10%" >' . $row['address2'] . '</td>
				<td align="center" style="font-size:10px;width:8%" >' . $row['city'] . '</td>
				<td align="center" style="font-size:10px;width:8%" >' . $row['state'] . '</td>
				<td align="center" style="font-size:10px;width:6%" >' . $row['zip'] . '</td>
				<td align="center" style="font-size:10px;width:10%" >' . $row['phone'] . '</td>
				<td align="center" style="font-size:10px;width:15%;word-break: break-all;">' . $row['email'] . '</td>				
		</tr>';
	}

	$html .= '</table>';
	$html .= '<br />';
}
