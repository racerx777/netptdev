<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 

unset($_SESSION['id']);
unset($_SESSION['button']);
// If Search Variables Are Set, unset empty values
$searchSaved = getformvars('reportmanager', 'search');

foreach($searchSaved as $key=>$val) {
	if(empty($val))
		unset($searchSaved["$key"]);
}

// If more than just clinic was specified continue
if( (count($searchSaved)>1 ))  {

// If Reset Sort
	if(!empty($_POST['btnResetSort'])) {
		clearformvars('reportmanager', 'searchResults');
		unset($sortSaved);
	}
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars('reportmanager', 'searchResults');
	// if a sort column button was pressed
	if(count($_POST['sort']) > 0) {
	// determine sort field name from key
		$sortfield=key($_POST['sort']);
	// if that key exists in the sort then toggle collation
		if(array_key_exists($sortfield, $sortSaved)) {
			$collation = $sortSaved["$sortfield"]['collation'];
			if($collation == 'desc')
				$sortSaved["$sortfield"]["collation"] = '';
			else
				$sortSaved["$sortfield"]["collation"] = 'DESC';
		}
		else
			$sortSaved["$sortfield"] = $searchvars["$sortfield"];
		setformvars('reportmanager', 'searchResults', $sortSaved);
	}

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
// prepare where clause
	$where = array();
	$where[] = "crcnum IN " . getUserClinicsList() . " ";
	foreach($searchSaved as $formvar=>$formvarvalue) {
		if( isset($formvarvalue) && !empty($formvarvalue) )  {
			$title = $searchvars["$formvar"]['title'];
			$type = $searchvars["$formvar"]['type'];
			$dbformat = $searchvars["$formvar"]['dbformat'];
			$dblength = $searchvars["$formvar"]['dblength'];
			$displayformat = $searchvars["$formvar"]['displayformat'];
			$displaylength = $searchvars["$formvar"]['displaylength'];
			$length = $searchvars["$formvar"]['length'];
			$test = $searchvars["$formvar"]['test'];

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
			$where[] = "$formvar $test";
		}
	}

	unset($wheresql);
	if(count($where) > 0) {
		$wheresql = " WHERE " . implode(" and ", $where);
	//
	// Sort Order - Contained in Session variable 'reportmanager'=>'searchResults'=>array(field=>collation) as sort
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
	unset($orderbysql);
	if(count($orderby) > 0) {
		$sortvartitles = substr($sortvartitles,0,-2);
		$orderbysql = " ORDER BY " . implode(",", $orderby);
	}
	else {
		$orderbysql = " ORDER BY palname, pafname, padob, paphone1, passn";
	}

	$limitsql = " LIMIT 100 ";

	$query  = "
		SELECT 
			crid, crpnum, crinjurydate, crcnum, crapptdate, crcasestatuscode, crtherapytypecode, crcasetypecode, 
			paid, palname, pafname, padob, paphone1, passn,
			cmbnum, cmpgmcode
		FROM cases 
		LEFT JOIN patients
		ON crpaid=paid
		LEFT JOIN master_clinics
		ON crcnum=cmcnum
		$wheresql
		$orderbysql
		$limitsql
	";
//dump("query",$query);
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
	?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large;">Search Patient Report Results <?php echo $sortvartitles;?></legend>
	<form method="post" name="searchResults">
		<?php
		if($numRows > 0) {
			if($numRows == 1)
				echo "$numRows case/patient found.";
			else {
				if($numRows < 100)
					echo "$numRows cases/patients found.";
				else
					echo "Over $numRows cases/patients found. Did not display all cases/patients.";
			}
	?>
			<?php
			$casecount=0;
			while($row = mysqli_fetch_assoc($result)) {
				if(empty($casecount)) {
?>
		<input name="btnResetSort" type="submit" value="Reset Sort">
		<input type="hidden" name="bumcode" id="bumcode" value="<?php echo $row['cmbnum']; ?>" />
		<input type="hidden" name="pgmcode" id="pgmcode" value="<?php echo $row['cmpgmcode']; ?>" />
		<input type="hidden" name="crcnum" id="crcnum" value="<?php echo $row['crcnum']; ?>" />
		<input type="hidden" name="crpnum" id="crpnum" value="<?php echo $row['crpnum']; ?>" />
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr>
				<th>&nbsp;</th>
				<th><input name="sort[crpnum]" type="submit" value="Patient" /></th>
				<th><input name="sort[palname]" type="submit" value="Last" /></th>
				<th><input name="sort[pafname]" type="submit" value="First" /></th>
				<th><input name="sort[padob]" type="submit" value="DOB" /></th>
				<th><input name="sort[paphone1]" type="submit" value="Phone" /></th>
				<th><input name="sort[passn]" type="submit" value="SSN" /></th>
				<th><input name="sort[crinjurydate]" type="submit" value="DOI" /></th>
				<th><input name="sort[crcnum]" type="submit" value="Clinic" /></th>
				<th><input name="sort[crapptdate]" type="submit" value="First Appt" /></th>
				<th><input name="sort[crcasestatuscode]" type="submit" value="Status" /></th>
				<th><input name="sort[crtherapytypecode]" type="submit" value="Therapy" /></th>
				<th><input name="sort[crcasetypecode]" type="submit" value="Case" /></th>
			</tr>
<?php
				}
				$casecount++;
				$rowcrid=$row['crid'];
				if($row['painactive'] == '1') {
					$rowstyle=' style="background-color:#FFFFCC;"'; 
					$togglebutton = 'Make Active';
				}
				else {
					$rowstyle='';
					$togglebutton = 'Make Inactive';
				}
				
// Create Report HTML for this case and count reports
				$reportcount=0;
				$reportquery="select * from report_header where rhcrid='$rowcrid' ORDER BY rhvisitdate";
				if($reportresult=mysqli_query($dbhandle,$reportquery)) 
					$reportcount=mysqli_num_rows($reportresult);

				if(empty($reportcount))
					unset($reportrowspan);
				else
					$reportrowspan = 'rowspan="' . ($reportcount+1) . '"';
?>
			<tr<?php echo "$rowstyle"; ?>>
				<td valign="middle" align="right" >
<?php
if($row['crcasestatuscode']=='ACT')
	echo('<input name="button['.$row["crid"].']" type="submit" value="Add" />');
else
	echo('<div style="font-size:xx-small; color:gray;">(not&nbsp;active)</div>');
?>

</td>
				<td><?php 
					if(!empty($row["crpnum"])) 
						echo $row['crpnum']; 
					else 
						echo 'NEW PATIENT'; 
					?>&nbsp;</td>
				<td><?php echo $row["palname"]; ?>&nbsp;</td>
				<td><?php echo $row["pafname"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["padob"]); ?>&nbsp;</td>
				<td><?php echo displayPhone($row["paphone1"]); ?>&nbsp;</td>
				<td><?php echo displaySsn($row["passn"]); ?>&nbsp;</td>
				<td><?php echo displayDate($row["crinjurydate"]); ?>&nbsp;</td>
				<td><?php echo $row["crcnum"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["crapptdate"]); ?>&nbsp;</td>
				<td><?php echo $row["crcasestatuscode"]; ?>&nbsp;</td>
				<td><?php echo $row["crtherapytypecode"]; ?>&nbsp;</td>
				<td><?php echo $row["crid"]; ?>&nbsp;</td>
			</tr>
			<?php
				while($reportrow=mysqli_fetch_assoc($reportresult)) {
					$rhid=$reportrow['rhid'];
switch($reportrow['rhstatus']) {
	case 'NEW':
		$disableedit='';
		$disablegenerate='';
		$disableview='disabled="disabled"';
		$disablefile='disabled="disabled"';
		$disabledelete='';
		if(!empty($reportrow['rhvisitdate']))
			$dateparm=$reportrow['rhvisitdate'];
		else
			$dateparm=$reportrow['rhdate'];
		$rowcolorstyle=getRowColorStyle($dateparm);		
		break;
	case 'GENERATED':
		$disableedit='';
		$disablegenerate='';
		$disableview='';
		$disablefile='';
		$disabledelete='';
		if(!empty($reportrow['rhvisitdate']))
			$dateparm=$reportrow['rhvisitdate'];
		else
			$dateparm=$reportrow['rhdate'];
		$rowcolorstyle=getRowColorStyle($dateparm);
		break;
	case 'FILED':
		$disableedit='disabled="disabled"';
		$disablegenerate='disabled="disabled"';
		$disableview='';
		$disablefile='disabled="disabled"';
		$disabledelete='disabled="disabled"';
		$rowcolorstyle='';
		break;
	default:
		$disableedit='disabled="disabled"';
		$disablegenerate='disabled="disabled"';
		$disableview='disabled="disabled"';
		$disablefile='disabled="disabled"';
		$disabledelete='disabled="disabled"';
		break;
}
// Cannot file a report without a patient number
if(
	empty($reportrow['rhpnum'] ) ||
	empty($reportrow['rhlname']) ||
	empty($reportrow['rhfname']) ||
	empty($reportrow['rhcnum'] ) ||
	empty($reportrow['rhrtid'] ) 
)
	$disablefile='disabled="disabled"';


//		if(!empty($reportrow['rhvisitdate']))
//			$dateparm=$reportrow['rhvisitdate'];
//		else
//			$dateparm=$reportrow['rhdate'];
//		$rowcolorstyle=getRowColorStyle($dateparm);

?>
			<tr>
				<td <?php echo $rowcolorstyle; ?>>&nbsp;</td>
				<td colspan="13">
					<input name="button[<?php echo $reportrow["rhid"]?>]" type="submit" value="Edit" <?php echo $disableedit; ?> />
					<input name="button[<?php echo $rhid; ?>]" type="submit" value="Generate" <?php echo $disablegenerate; ?> />
<?php
					$url = "'/modules/reportmanager/viewReport.php?rhid=$rhid'";
					$title="'View Report'";
					$width="'height=1024px,width=1280px,scrollbars=yes'";
					$onclick='onclick="window.open('.$url.','.$title.','.$width.');"';
					$input="<input name='button[$rhid]' type='submit' value='View' $onclick $disableview. />";
					echo $input;
?>
				<?php echo displayDate($reportrow['rhvisitdate']) . " " .getReportDescription($reportrow['rhrtid']) . " (" . $reportrow['rhstatus'] . "/" . $reportrow['rharchstatus'] . ")"; ?> 
					<input name="button[<?php echo $reportrow["rhid"]; ?>]" type="submit" value="File" <?php echo $disablefile; ?> />
					<input name="button[<?php echo $reportrow["rhid"]; ?>]" type="submit" value="Delete" <?php echo $disabledelete; ?> />
				</td>
			</tr>
<?php
				} 
?>
			<tr<?php echo $rowstyle; ?>> </tr>
			<?php
			}
			foreach($_POST as $key=>$val)
				unset($_POST[$key]);
		?>
		</table>
	</form>
	<?php
		}
		else 
			echo('No patients found.');
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
	else
		unset($_POST['ClickedSearch']);
}
// Display New Patients
//if(countformvars('reportmanager', 'search')==1) {
//	require_once('searchResultsFormNew.php');
//	require_once('searchResultsFormWalkIns.php');
//	require_once('searchResultsFormRecent.php');
//}
?>