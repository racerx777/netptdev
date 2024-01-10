<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
$searchapptSaved = getformvars('case', 'searchappt');
if( !empty($searchapptSaved) || !empty($_POST['formSubmit']) || !empty($_POST['sortappt']) ) {
	// sortappt is an array of the sortappt fields and properties "field"=>array("title", "collation")
	$sortapptSaved = getformvars('case', 'searchapptResult');
	// if sortappt button pressed set sortappt values
	if(count($_POST['sortappt']) > 0) {
	// If Reset sortappt Pressed then clear saved values
		if(!empty($_POST['sortappt']['RESETSORT'])) {
			clearformvars('case', 'searchapptResult');
			unset($sortapptSaved);
		}
		else {
	// determine sort field name from key
			$sortapptfield=key($_POST['sortappt']);
	// if that key exists in the sort then toggle collation
			if(array_key_exists($sortapptfield, $sortapptSaved)) {
				$collation = $sortapptSaved["$sortapptfield"]['collation'];
				if($collation == 'desc')
					$sortapptSaved["$sortapptfield"]["collation"] = '';
				else
					$sortapptSaved["$sortapptfield"]["collation"] = 'desc';
			}
			else
				$sortapptSaved["$sortapptfield"] = $searchapptvars["$sortapptfield"];
		setformvars('case', 'searchapptResult', $sortapptSaved);
		}
	}

// Select Call Record $callid
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

$query = "
SELECT crdate, crcnum, cmname, crlname, crfname, crdob, crinjurydate, crssn, crcasetypecode, crtherapytypecode, crcasestatuscode, crapptdate, crreadmit, crrelocate
FROM cases c
	LEFT JOIN master_clinics 
	ON crcnum = cmcnum
";

	// where
	$where = array();
	foreach($searchapptSaved as $formvar=>$formvarvalue) {
		if( isset($formvarvalue) && !empty($formvarvalue) )  {
			$title = $searchapptvars["$formvar"]['title'];
			$type = $searchapptvars["$formvar"]['type'];
			$dbformat = $searchapptvars["$formvar"]['dbformat'];
			$dblength = $searchapptvars["$formvar"]['dblength'];
			$displayformat = $searchapptvars["$formvar"]['displayformat'];
			$displaylength = $searchapptvars["$formvar"]['displaylength'];
			$length = $searchapptvars["$formvar"]['length'];
			$test = $searchapptvars["$formvar"]['test'];
			switch($dbformat):
				case 'date':
					$formvarvalue = date("Y-m-d", strtotime($formvarvalue));
					break;
			endswitch;
			switch($test):
				case 'LIKE':
					$test = "LIKE '" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "%'";
					break;
				case 'RANGE':
					$test = "BETWEEN '" . mysqli_real_escape_string($dbhandle,$formvarvalue . " 00:00:00") . "' AND '" . mysqli_real_escape_string($dbhandle,$formvarvalue . " 23:59:59") . "'";
					break;
				default:
					$test = "= '" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "'";
			endswitch;
			$where[] = "$formvar $test";
		}
	}

	if(count($where) > 0) {
		$query .= " WHERE crcasestatuscode='SCH' and " . implode(" and ", $where);
	//crapptdate between '$fromdate' and '$todate' and
	// Sort Order - Contained in Session variable 'customerservice'=>'searchapptResults'=>array(field=>collation) as sort
	//

	$orderby=array();
	if(empty($sortapptSaved)) {
	// default sort here
		$sortapptvartitles = "unsorted (click column titles to add/toggle sort)";
	}
	else {
		$sortapptvartitles = "sorted by ";
		foreach($sortapptSaved as $sortapptvar=>$sortapptvarproperty) {
			$orderby[] = trim($sortapptvar . " " . $sortapptvarproperty["collation"]);
			$sortapptvartitles .= trim($sortapptvarproperty["title"] . " " . $sortapptvarproperty["collation"]) . ", ";
		}
	}
	if(count($orderby) > 0) {
		$sortapptvartitles = substr($sortapptvartitles,0,-2);
		$query .= " ORDER BY " . implode(",", $orderby);
	}
	else {
		$query .= " ORDER BY crcnum, crapptdate";
	}
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$numRows = mysqli_num_rows($result);
	?>
	<div class="containedBox">
	<fieldset>
	<legend class="boldLarger">Search Appointment Results:</legend>
	<?php
			if($numRows>0) {
				echo $numRows . " appointment(s) found.";
	?>
	<form method="post" name="searchapptResults">
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr>
				<th><input name="sortappt[cmname]" type="submit" value="Clinic Name" /></th>
				<th><input name="sortappt[crlname]" type="submit" value="Last Name" /></th>
				<th><input name="sortappt[crfname]" type="submit" value="First Name" /></th>
				<th><input name="sortappt[crdob]" type="submit" value="DOB" /></th>
				<th><input name="sortappt[crdoi]" type="submit" value="DOI" /></th>
				<th><input name="sortappt[crssn]" type="submit" value="SSN" /></th>
				<th><input name="sortappt[crapptdate]" type="submit" value="Appointment Date" /></th>
				<th><input name="sortappt[RESETSORT]" type="submit" value="Reset Sort"></th>
			</tr>
			<?php
				while($row = mysqli_fetch_assoc($result)) {
				if($row['crinactive'] == '1') {
					$rowstyle=' style="background-color:#FFFFCC;"'; 
					$togglebutton = 'Make Active';
				}
				else {
					$rowstyle='';
					$togglebutton = 'Make Inactive';
				}
	?>
			<tr<?php echo $rowstyle; ?>>
				<td><?php echo $row["cmname"]; ?>&nbsp;</td>
				<td><?php echo $row["crlname"]; ?>&nbsp;</td>
				<td><?php echo $row["crfname"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["crdob"]); ?>&nbsp;</td>
				<td><?php echo displayDate($row["crdoi"]); ?>&nbsp;</td>
				<td><?php echo displaySsn($row["crssn"]); ?>&nbsp;</td>
				<td><?php echo displayDate(displayDate($row["crapptdate"])) . " " . displayTime($row["crapptdate"]); ?>&nbsp;</td>
				<td>
				<input name="button[<?php echo $row["crid"]?>]" type="submit" value="Seen" />
				<input name="button[<?php echo $row["crid"]?>]" type="submit" value="No Show" />
				<input name="button[<?php echo $row["crid"]?>]" type="submit" value="Clinic Rescheduled" />
				</td>
			</tr>
			<?php
				}
	?>
		</table>
	</form>
	<?php
				foreach($_POST as $key=>$val)
					unset($_POST[$key]);
			}
			else 
				echo('No appointments found.');
		}
		else 
			error('001', mysqli_error($dbhandle));
	//close the connection
		mysqli_close($dbhandle);
	?>
	</fieldset>
	</div>
	<?php
	}
	else
		unset($_POST['ClickedSearch']);
}
?>