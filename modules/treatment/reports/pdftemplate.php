<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10);
$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Treatment Report</title>
</head>
<body>
';
$html .= '<div style="float:left"><img src="../wsptn_logo_bw_outline.jpg" width="300px"></div>
		<div style="float:right;margin-right:100px;">
			<h1>Treatment Report </h1>
		</div><div style="clear:both;">';
// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

//declare the SQL statement that will query the database
 $query  = "SELECT * FROM treatment_header WHERE thid in (".$_GET['id'].") ";
//execute the SQL query and return records
$result = mysqli_query($dbhandle,$query);
if(!$result)
	echo mysqli_error($dbhandle);	
$totalRows = mysqli_num_rows($result);
$sbmDate = date('Y-m-d H:i:s');

$html .= '<div class="containedBox">
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th>Treatment Date</th>
			<th>Number</th>
			<th>Last Name</th>
			<th>First Name</th>
			<th>Case Type</th>
			<th>Visit Type</th>
			<th>Treatment Type</th>
			<th>Procedures/Modalities</th>
			<th>Submit Status</th>
		</tr>';
		
$numRows=0;
$cliniclistarray=array();
$clinics = $_SESSION['useraccess']['clinics'];
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
	$lnamefname=strtoupper(trim($row['thlname']) . trim($row['thfname']));
	$cliniclistarray[$row['thcnum']] = $row['thcnum'] . '-' . $clinics[$row['thcnum']]['cmname'];
	$updatequery = "UPDATE treatment_header set thsbmStatus='100', thsbmDate='" . $sbmDate . "', thsbmUser='" . $_SESSION['user']['umuser'] . "' WHERE thid = '" . $row["thid"] . "'";
	$update = mysqli_query($dbhandle,$updatequery);
	if(!$update)
		$sbm=mysqli_error($dbhandle);
	else
		$sbm="SUBMITTED";

		$casetypetext = $_SESSION['casetypes'][$row['thctmcode']];
		$visittypetext = $_SESSION['visittypes'][$row['thvtmcode']];
		$treatmenttypetext = $_SESSION['treatmenttypes'][$row['thttmcode']];

		$procmodarray = array();
		$queryproc  = "SELECT * FROM treatment_procedure_groups WHERE thid='" . $row['thid'] . "' and gmcode not in ('A','P') ORDER BY thid, gmcode";
		$resultproc = mysqli_query($dbhandle,$queryproc);
		if(!$resultproc)
			error("001", mysqli_error($dbhandle));
		else {
			$numRowsproc = mysqli_num_rows($resultproc);
			if($numRowsproc != NULL) {
				while($rowproc = mysqli_fetch_array($resultproc,MYSQLI_ASSOC)) {
					$procmodarray[] = $_SESSION['procedures'][$row['thttmcode']][$rowproc['gmcode']];

					$selectBox = "<select onchange='return addProcedureModalityQty(\"treatment_procedures\",$rowproc[thid],this.value,\"$rowproc[pmcode]\",\"pmcode\")'>";
							for ($i=0; $i < 6 ; $i++) { 
								if($rowproc['qty'] == $i)
									$selectBox .= "<option value='".$i."' selected>".$i."</option>";
								else
									$selectBox .= "<option value='".$i."'>".$i."</option>";
							}
							$selectBox .= "</select>";
							$procmodarray[] = $str."  ".$selectBox;
				}
			}
		}

//declare the SQL statement that will query the database
		$querymodality  = "SELECT * FROM treatment_modalities WHERE thid='" .  $row['thid'] . "' and mmcode not in ('15P') ORDER BY thid, mmcode";
		$resultmodality = mysqli_query($dbhandle,$querymodality);
		if(!$resultmodality)
			error("001", mysqli_error($dbhandle));
		else {
			$numRowsmodality = mysqli_num_rows($resultmodality);
			if($numRowsmodality != NULL) {
				while($rowmodality = mysqli_fetch_array($resultmodality,MYSQLI_ASSOC)) {
					$procmodarray[] = $_SESSION['modalities'][$row['thttmcode']][$rowmodality['mmcode']];

					$selectBox = "<select name='".$row['thid'].'_'.$str."' onchange='return addProcedureModalityQty(\"treatment_modalities\",$rowmodality[thid],this.value,\"$rowmodality[mmcode]\",\"mmcode\")'>";
						for ($i=0; $i < 6 ; $i++) { 
							if($rowmodality['qty'] == $i)
								$selectBox .= "<option value='".$i."' selected>".$i."</option>";
							else
								$selectBox .= "<option value='".$i."'>".$i."</option>";
						}
						$selectBox .= "</select>";
						$procmodarray[] = $str."  ".$selectBox;
				}
			}
		}
		$proceduretext = implode(', ', $procmodarray);

		$html .= '<tr '.$searchRowStyl.' >
			<td>'. date('m/d/Y', strtotime($row["thdate"])).'</td>
			<td>'. $row["thpnum"].'</td>
			<td>'. $row["thlname"].'</td>
			<td>'. $row["thfname"].'</td>
			<td>'. $casetypetext.'</td>
			<td>'. $visittypetext.'</td>
			<td>'. $treatmenttypetext.'</td>
			<td>'. $proceduretext.'</td>
			<td>'. $sbm.'</td>
		</tr>';
		
	$numRows++;
	addheaderhistory($row["thid"], $sbmDate, $_SESSION['user']['umuser'], 0, $_SESSION['application'], 'SUBMITTED', 'Treatment Submitted to Weststar.', $updatequery);
}
		$html .= '<tr>
			<td colspan="8" class="boldLarger">'. $numRows . " of " . $totalRows . " treatment(s) submitted on " . $sbmDate . ".".' </td>
		</tr>
	</table>';
	
//close the connection
mysqli_close($dbhandle);
$userclinic = implode(", ", $cliniclistarray);
	
$html .= '</div>';
$html .= '
<div>
	<p>Print Date : '.date("Y-m-d").'</p>
</div>';
?>
