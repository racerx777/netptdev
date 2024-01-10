<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 259200);
header('Content-Type: text/html; charset=utf-8');
$dbhandle = dbconnect();
$html = '';
$sql = array();




$query = "SELECT treatment_header.* FROM treatment_header";
$where = array();

if (!empty($_SESSION['useraccess']['clinics'])) {
	$where[] = "thcnum IN " . getUserClinicsList() . " ";
}

if (!empty($_GET['searchcnum']))
	$where[] = "thcnum IN ( '" . $_GET['searchcnum'] . "' )";
// $where[] = "thcnum IN ( '" . implode("','", $_GET['searchcnum']) . "' )";



if (!empty($_GET['searchcliniccode']) || !empty($_GET['searchcliniccode'][0]))
	$where[] = "thcnum IN ( '" . implode("','", $_GET['searchcliniccode']) . "' )";
if (isset($_GET['searchfromtreatmentdate']) && !empty($_GET['searchfromtreatmentdate']))
	$where[] = "DATE_FORMAT( thdate, '%Y%m%d' ) >= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle, $_GET['searchfromtreatmentdate']))) . "', '%Y%m%d') ";

if (isset($_GET['searchtotreatmentdate']) && !empty($_GET['searchtotreatmentdate']))
	$where[] = "DATE_FORMAT( thdate, '%Y%m%d' ) <= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle, $_GET['searchtotreatmentdate']))) . "', '%Y%m%d') ";

if (isset($_GET['searchpnum']) && !empty($_GET['searchpnum']))
	$where[] = "thpnum= '" . mysqli_real_escape_string($dbhandle, $_GET['searchpnum']) . "'";

if (isset($_GET['searchbnum']) && !empty($_GET['searchbnum'])) {
	$query .= " LEFT JOIN PTOS_Patients ON pnum = thpnum ";
	$where[] = "bnum = '" . mysqli_real_escape_string($dbhandle, $_GET['searchbnum']) . "'";
}

if (isset($_GET['searchlname']) && !empty($_GET['searchlname']))
	$where[] = "thlname LIKE '" . mysqli_real_escape_string($dbhandle, $_GET['searchlname']) . "%'";

if (isset($_GET['searchfname']) && !empty($_GET['searchfname']))
	$where[] = "thfname LIKE '" . mysqli_real_escape_string($dbhandle, $_GET['searchfname']) . "%'";

if (isset($_GET['searchctmcode']) && !empty($_GET['searchctmcode']))
	$where[] = "thctmcode= '" . mysqli_real_escape_string($dbhandle, $_GET['searchctmcode']) . "'";

if (isset($_GET['searchvtmcode']) && !empty($_GET['searchvtmcode']))
	$where[] = "thvtmcode= '" . mysqli_real_escape_string($dbhandle, $_GET['searchvtmcode']) . "'";

if (isset($_GET['searchttmcode']) && !empty($_GET['searchttmcode']))
	$where[] = "thttmcode= '" . mysqli_real_escape_string($dbhandle, $_GET['searchttmcode']) . "'";

if (isset($_GET['searchfromsubmitdate']) && !empty($_GET['searchfromsubmitdate']))
	$where[] = "DATE_FORMAT( thsbmdate, '%Y%m%d' ) >= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle, $_GET['searchfromsubmitdate']))) . "', '%Y%m%d') ";

if (isset($_GET['searchtosubmitdate']) && !empty($_GET['searchtosubmitdate']))
	$where[] = "DATE_FORMAT( thsbmdate, '%Y%m%d' ) <= DATE_FORMAT( '" . date('Y-m-d', strtotime(mysqli_real_escape_string($dbhandle, $_GET['searchtosubmitdate']))) . "', '%Y%m%d') ";

if (isset($_GET['searchsbmstatus']) && !empty($_GET['searchsbmstatus']))
	$where[] = "thsbmstatus " . mysqli_real_escape_string($dbhandle, $_GET['searchsbmstatus']) . " ";




$total_rows = "";
//dump('query', $query);
$result = mysqli_query($dbhandle, $query);
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

if (count($where) > 0)
	$query .= " WHERE " . implode(" and ", $where) . " ";

if (isset($_GET['XLS']) && !empty($_GET['XLS'])) {



} else {


	$query .= " LIMIT  1000";
}


$abc = "";
if (isset($_GET['XLS']) && !empty($_GET['XLS'])) {

	$abc = '<p>' . $total_rows . ' Search Treatment(s) found</p>';

} else {

	$abc = '<p>1000 Search Treatment(s) found out of ' . $total_rows . ' records.</p>';

}
// print_r($query);


$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Treatment Search Report</title>
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
</style>
<body>
<table style="width: 1025px; margin: 0 auto; padding: 0px; border-collapse: collapse;  font-family: Arial, Helvetica, sans-serif;" align="center">
	<tr>
		<td align="left" style="text-align: left; width: 50%;">
			<img src="wsptn logo bw outline.jpg" width="300px">
			
	 ' . $abc . ' 
			<p id="searchfilter">Search Filter(s) applied: ' . $appliedSearch . '</p>
		</td>
		<td  align="right" style="text-align: right; width: 50%;">
			<h1>Treatment search Report</h1>
			<p>Report Date: ' . date("m/d/Y") . '</p>
		</td>
	</tr>
</table>

<div style="clear:both;">';
if ($result = mysqli_query($dbhandle, $query)) {
	$html .= '<table id="customers" style="text-align:center; border-collapse: collapse; border: solid;margin-right:20px" width="100%" >
				<tr>		
					<th style="font-size:10px;text-align: center">Clinicchange</th>
					<th style="font-size:10px;text-align: center">Treatment Date</th>
					<th style="font-size:10px;text-align: center">Number</th>
					<th style="font-size:10px;text-align: center">Last Name</th>
					<th style="font-size:10px;text-align: center">First Name</th>
					<th style="font-size:10px;text-align: center">Visit Type</th>
					<th style="font-size:10px;text-align: center">Treatment Type</th>
					<th style="font-size:10px;text-align: center">Procedures/Modalities</th>
					<th style="font-size:10px;text-align: center">Next Action Date</th>
				</tr>';
	// $billablerows = 0;
	$new_result = array();
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$new_result[] = $row;
	}

	foreach ($new_result as $key => $row) {

		$thid = $row['thid'];

		$pnum = $row['thpnum'];
		$casetypestyle = "";
		if (!empty($pnum)) {
			if (userlevel() >= 23) {
				$casetypequery = "
							SELECT count(*) as casetypecount FROM (
								SELECT DISTINCT thctmcode from treatment_header where thpnum='$pnum'
							) as a";
				if ($casetyperesult = mysqli_query($dbhandle, $casetypequery)) {
					if ($casetyperow = mysqli_fetch_assoc($casetyperesult)) {
						if ($casetyperow['casetypecount'] > 1)
							$casetypestyle = 'style="background-color:#FFFF00"';
					}
				}
			}
		} else
			unset($pnum);
		$casetypetext = $_SESSION['casetypes'][$row['thctmcode']];
		$visittypetext = $_SESSION['visittypes'][$row['thvtmcode']];
		$treatmenttypetext = $_SESSION['treatmenttypes'][$row['thttmcode']];
		$procmodarray = array();

		$queryproc = "SELECT * FROM treatment_procedures WHERE thid='" . $row['thid'] . "' AND pmcode not in ('A','P') ORDER BY thid, pmcode";
		$resultproc = mysqli_query($dbhandle, $queryproc);

		if (!$resultproc)
			error("001", mysqli_error($dbhandle));
		else {
			$numRowsproc = mysqli_num_rows($resultproc);
			if ($numRowsproc != NULL) {
				while ($rowproc = mysqli_fetch_array($resultproc)) {
					if (!empty($_SESSION['individualprocedures'][$row['thttmcode']][$rowproc['pmcode']])) {
						$str = $_SESSION['individualprocedures'][$row['thttmcode']][$rowproc['pmcode']];
						$selectBox = " (" . $rowproc['qty'] . ")";
						$procmodarray[] = $str . $selectBox;
					} else {
						$querymaster = "SELECT * FROM master_procedures WHERE pmcode='" . $rowproc['pmcode'] . "'";
						$resultmaster = mysqli_query($dbhandle, $querymaster);

						if (!$resultmaster) {
							error("001", mysqli_error($dbhandle));
						} else {
							$numRowsmaster = mysqli_num_rows($resultmaster);
							if ($numRowsmaster != NULL) {
								while ($rowmaster = mysqli_fetch_array($resultmaster)) {
									$str = $rowmaster['pmdescription'];
									$selectBox = " (" . $rowproc['qty'] . ")";
									$procmodarray[] = $str . $selectBox;
								}
							}
						}
					}
				}
			}
		}
		if (!empty($procmodarray))
			$proceduretext = "<p><span style='color:#4b7fb4'>P |</span> " . implode(', ', $procmodarray) . "</p>";
		$procmodarray = array();

		//declare the SQL statement that will query the database
		$querymodality = "SELECT * FROM treatment_modalities WHERE thid='" . $row['thid'] . "' and mmcode not in ('15P') ORDER BY thid, mmcode";
		$resultmodality = mysqli_query($dbhandle, $querymodality);
		if (!$resultmodality)
			error("001", mysqli_error($dbhandle));
		else {
			$numRowsmodality = mysqli_num_rows($resultmodality);
			if ($numRowsmodality != NULL) {
				while ($rowmodality = mysqli_fetch_array($resultmodality)) {
					if (!empty($_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']]))
						$procmodarray[] = $_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']];
					if (!empty($_SESSION['supplymodalities'][$row['thttmcode']][$rowmodality['mmcode']]))
						$procmodarray[] = $_SESSION['supplymodalities'][$row['thttmcode']][$rowmodality['mmcode']];
				}
			}
		}
		$modulitytext = "";
		$ab = "";
		if ($row['thnadate'] <= '2012-08-01 00:00:00.000') {
			$ab = "";
		} else {
			$ab = date('m/d/Y', strtotime($row["thnadate"]));

		}
		$link = "";
		if (empty($_GET['searchpnum'])) {
			$link = ('<input type="submit" name="button[' . $pnum . ']" value="' . $pnum . '" />');
		} else
			$link = $pum;

		// if (!empty($procmodarray))
		// 	$modulitytext = "<p><span style='color:#4b7fb4'>M | </span>" . implode(', ', $procmodarray) . "</p>";

		$html .= '<tr style="border-collapse:collapse; border:solid; border-bottom-color:#CCCCCC;">
	<td align="center" style="font-size:8;width:1%" >' . $row['thcnum'] . '&nbsp;</td>
		<td align="center" style="font-size:8;width:1%" >' . date('m/d/Y', strtotime($row['thdate'])) . '&nbsp;</td>
		<td align="center" style="font-size:8;width:1%" >
			' . $link . ' &nbsp;
		</td>
		<td align="center" style="font-size:8;width:1%" >
			' . $row['thlname'] . ' &nbsp;
		</td>
		<td align="center" style="font-size:8;width:1%" >
			' . $row['thfname'] . ' &nbsp;
		</td>
		<td align="center" style="font-size:8;width:1%" >
			' . $visittypetext . ' &nbsp;
		</td>
		<td align="center" style="font-size:8;width:1%" >
			' . $treatmenttypetext . '&nbsp;
		</td>
		<td align="center" style="font-size:8;width:1%" >
			' . $proceduretext . ' 
		</td>
		<td align="center" style="font-size:8;width:1%;word-break: break-all;">
		' . $ab . '
		</td>
	


	</tr>';

	}

	$html .= '</table>';
	$html .= '<br />';
}
// die();