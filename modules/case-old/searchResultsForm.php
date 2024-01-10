<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
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
//	LEFT JOIN appointments a on crpaid = apid ";
//	"crcasestatuscode != 'SCH'"
	$where = array();

	foreach($searchcaseSaved as $formvar=>$formvarvalue) {
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
					$test = "= '" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "'";
			endswitch;
			if($formvar=='paphone1') {
				$where[] = "((paphone1 $test) OR (paphone2 $test) OR (pacellphone $test))";
			}
			else
				$where[] = "$formvar $test";
		}
	}

	if(count($where) > 0)
		$query .= " WHERE " . implode(" and ", $where);

	//
	// Sort Order - Contained in Session variable 'customerservice'=>'searchResults'=>array(field=>collation) as sort
	//

	$orderby=array();
	if(empty($sortcaseSaved)) {
	// default sort here
		$sortcasevartitles = "unsorted (click column titles to add/toggle sort)";
	}
	else {
		$sortcasevartitles = "sorted by ";
		foreach($sortcaseSaved as $sortvar=>$sortvarproperty) {
			$orderby[] = trim($sortvar . " " . $sortvarproperty["collation"]);
			$sortcasevartitles .= trim($sortvarproperty["title"] . " " . $sortvarproperty["collation"]) . ", ";
		}
	}
	if(count($orderby) > 0) {
		$sortcasevartitles = substr($sortcasevartitles,0,-2);
		$query .= " ORDER BY " . implode(",", $orderby);
	}
	else {
		$query .= " ORDER BY palname, pafname, padob, paphone1, passn";
	}
//dump("query",$query);
	$query.=" LIMIT 100 ";
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$numRows = mysqli_num_rows($result);
	?>
<div class="containedBox">
<fieldset>
<legend style="font-size:large;">Search Case Results <?php echo $sortcasevartitles;?></legend>
	<?php
	if($numRows > 0) {
		if($numRows == 1)
			echo "$numRows case found.";
		else {
			if($numRows < 100)
				echo "$numRows cases found.";
			else
				echo "Over $numRows cases found. Did not display all cases.";
		}
	?>
	<a href="/modules/case/printXLS.php"><img src="/img/icon-xls.png" style="float:right;margin-left: 3px;cursor: pointer;margin-right: 15px;" ></a>
	<a href="/modules/case/printPdf.php"><img src="/img/icon-pdf.png" style="float: right;cursor: pointer;margin-bottom: 10px;margin-right: 10px;">&nbsp;&nbsp;</a>
<form method="post" name="searchResults">
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<tr>
				<th><input name="sortcase[crid]" type="submit" value="Case Id" /></th>
				<th><input name="sortcase[palname]" type="submit" value="Last Name" /></th>
				<th><input name="sortcase[pafname]" type="submit" value="First Name" /></th>
				<th><input name="sortcase[padob]" type="submit" value="DOB" /></th>
				<th><input name="sortcase[paphone1]" type="submit" value="Phone" /></th>
				<th><input name="sortcase[passn]" type="submit" value="SSN" /></th>
				<th><input name="sortcase[crinjurydate]" type="submit" value="DOI" /></th>
				<th><input name="sortcase[crrefdmid]" type="submit" value="Ref MD ID" /></th>
                
                <th><input name="sortcase[crdate]" type="submit" value="Ref Date" /></th>
                <th><input name="sortcase[crcnum]" type="submit" value="Clinic" /></th>
				<th><input name="sortcase[crcasestatuscode]" type="submit" value="Status" /></th>

                <th><input name="sortcase[crapptdate]" type="submit" value="Appt Date" /></th>
				<th><input name="sortcase[crcanceldate]" type="submit" value="Can Date" /></th>
				<th><input name="sortcase[crtherapytypecode]" type="submit" value="Therapy Type" /></th>
				<th><input name="sortcase[crcasetypecode]" type="submit" value="Case Type" /></th>
				<th><input name="sortcase[RESETSORT]" type="submit" value="Reset Sort"></th>
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
		<tr <?php echo $rowstyle; ?>>
			<td><?php echo $row["crid"]; ?>&nbsp;</td>
			<td><?php echo $row["palname"]; ?>&nbsp;</td>
			<td><?php echo $row["pafname"]; ?>&nbsp;</td>
			<td><?php echo $row["padob"]; ?>&nbsp;</td>
			<td><?php echo ' h:'.displayPhone($row["paphone1"]).' w:'.displayPhone($row["paphone2"]).' c:'.displayPhone($row["pacellphone"]); ?>&nbsp;</td>
			<td><?php echo displaySsn($row["passn"]); ?>&nbsp;</td>
			<td><?php echo $row["crinjurydate"]; ?>&nbsp;</td>
			<td><?php echo $row["crrefdmid"]; ?>&nbsp;</td>
			<td><?php echo $row["crdate"]; ?>&nbsp;</td>
			<td><?php echo $row["crcnum"]; ?>&nbsp;</td>
			<td><?php echo $row["crcasestatuscode"]; ?>&nbsp;</td>
			<td><?php
				if(!empty($row["crapptdate"]) && $row["crcasestatuscode"]!='CAN')
					echo displayDate($row["crapptdate"]) . ' ' . displayTime($row["crapptdate"]);
				?>&nbsp;
			</td>
			<td><?php
				if(!empty($row["crcanceldate"]) && $row["crcasestatuscode"]=='CAN')
					echo displayDate($row["crcanceldate"]);
				?>&nbsp;</td>
			<td><?php echo $row["crtherapytypecode"]; ?>&nbsp;</td>
			<td><?php echo $row["crcasetypecode"]; ?>&nbsp;</td>
			<td><input name="button[<?php echo $row["crid"]?>]" type="submit" value="Edit Case" />
				<input name="button[<?php echo $row["crid"]?>]" type="submit" value="Add Note" />
			<?php
				if(isuserlevel(90)) {
					if($row["crcasestatuscode"] == 'NEW') {
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Delete Referral" />');
					}
					if($row["crcasestatuscode"] == 'PEN') {
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Seen" />');
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Scheduled" />');
					}
					if($row["crcasestatuscode"] == 'PEA') {
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Seen" />');
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Reschedule" />');
					}
					if($row["crcasestatuscode"] == 'SCH') {
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Seen" />');
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Reschedule" />');
					}
					if($row["crcasestatuscode"] == 'CAN') {
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Seen" />');
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Reschedule" />');
					}
					if($row["crcasestatuscode"] == 'ACT') {
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Reschedule" />');
					}
				}
				if(isuserlevel(15)) {
					if($row["crcasestatuscode"] == 'NEW' || $row["crcasestatuscode"] == 'PEN')
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="Requires Authorization" />');
					if($row["crcasestatuscode"] == 'NEW')
						echo('<input name="button[' . $row["crid"] . ']" type="submit" value="To Scheduling" />');
					if($row["crcasestatuscode"] == 'PEN' || $row["crcasestatuscode"] == 'PEA')
						echo('<input name="navigation[' . urlencode('contactreferral=' . $row['crid']) . ']" type="submit" value="Contact Referral" />');
				}
			?>
				<input type="button" value="Print Sheet" onclick="window.open('/modules/scheduling/printPatientInformationSheet.php?crid=<?php echo $row['crid'] ?>');" />
				<input type="button" value="Print Letter" onclick="window.open('/modules/scheduling/printSchedulingUpdateLetter.php?crid=<?php echo $row['crid'] ?>');" />
<?php
					  			$notewidth=75;
								$notelimit=5;
								$notehidecount=1;
//					  			require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsNotesList.php');
?>
				<input type="button" value="AuthDtl" onclick="window.open('/modules/case/caseAuthDtl.php?crid=<?php echo $row['crid'] ?>');" />
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
			echo('No cases found.');
	}
	else
		error('001', "QUERY:" . $query . ":" . mysqli_error($dbhandle));
//close the connection
mysqli_close($dbhandle);
?>
</fieldset>
</div>
<?php
}
?>