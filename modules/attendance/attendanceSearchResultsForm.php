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
SELECT crid, crdate, crcnum, cmname, palname, pafname, crdob, crinjurydate, crssn, crcasetypecode, crtherapytypecode, crcasestatuscode, crapptdate, crreadmit, crrelocate
FROM cases c
	LEFT JOIN master_clinics 
	ON crcnum = cmcnum
	LEFT JOIN patients
	ON crpaid = paid
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
			$rangefield = $searchapptvars["$formvar"]['rangefield'];
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
//					$test = "BETWEEN '" . mysqli_real_escape_string($dbhandle,"1999-01-01 00:00:00") . "' AND '" . mysqli_real_escape_string($dbhandle,$formvarvalue . " 23:59:59") . "'";
					$test = "BETWEEN '" . mysqli_real_escape_string($dbhandle,$formvarvalue . " 00:00:00") . "' AND '" . mysqli_real_escape_string($dbhandle,$formvarvalue . " 23:59:59") . "'";						
					break;
				case 'RANGE1':
					$formvar = $rangefield;
					$test = "BETWEEN '" . mysqli_real_escape_string($dbhandle,$formvarvalue . " 00:00:00") . "'";						
					break;
				case 'RANGE2':
					$formvar = "";
					$test = "'" . mysqli_real_escape_string($dbhandle,$formvarvalue . " 23:59:59") . "'";						
					break;
				default:
					$test = "= '" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "'";
			endswitch;
			$where[] = "$formvar $test";
		}
	}
	if(count($where) > 0) {
		$query .= " WHERE " . implode(" and ", $where);
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
		if (isset($_POST['pageno'])){
			$pageno = $_POST['pageno'];
		} else {
			$pageno = 1;
		}

		$no_of_records_per_page = 100;
			$offset = ($pageno-1) * $no_of_records_per_page;
			$tmp=mysqli_query($dbhandle,$query);
			// $total_rows = mysqli_fetch_array($tmp)[0];
			$total_rows = mysqli_num_rows($tmp);
			$total_pages = ceil($total_rows / $no_of_records_per_page);
			$num_res = mysqli_query($dbhandle,$query);
			$query = $query.' limit '. $offset .','.$no_of_records_per_page;
		$result = mysqli_query($dbhandle,$query);

	if($result) {
		// $numRows = mysqli_num_rows($tmp);
	?>

<style>
	.pagination li {

	  margin-left: 6px;
	}
	.pagination {
	  display: flex;
	  list-style: none;
	  float: right;
	}
</style>
<div id="append-table">
	<div class="containedBox">
	<fieldset>
	<legend class="boldLarger">Search Appointment Results:</legend>
	<?php
			if($total_rows>0) {
				echo $total_rows . " appointment(s) found.";
	?>
	<form method="post" name="searchapptResults">
		<table border="1" cellpadding="3" cellspacing="0" width="100%" id="append-table">
			<tr>
				<th><input name="sortappt[crapptdate]" type="submit" value="Appt Date" /></th>
				<th><input name="sortappt[palname]" type="submit" value="Last Name" /></th>
				<th><input name="sortappt[pafname]" type="submit" value="First Name" /></th>
	<!--				<th><input name="sortappt[crdob]" type="submit" value="DOB" /></th>
				<th><input name="sortappt[crinjurydate]" type="submit" value="DOI" /></th>
				<th><input name="sortappt[crssn]" type="submit" value="SSN" /></th>
	-->				<th><input name="sortappt[crcasetypecode]" type="submit" value="Case Type" /></th>
				<th><input name="sortappt[crtherapytypecode]" type="submit" value="Therapy Type" /></th>
				<th><input name="sortappt[crcasestatuscode]" type="submit" value="Case Status" /></th>
				<th><input name="sortappt[crdate]" type="submit" value="Referral Date" /></th>
				<th><input name="sortappt[cmname]" type="submit" value="Clinic" /></th>
				<th><input name="sortappt[crreadmit]" type="submit" value="Readmit" /></th>
				<th><input name="sortappt[crrelocated]" type="submit" value="Relocated" /></th>
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
				<td><?php echo displayDate(displayDate($row["crapptdate"])) . " " . displayTime($row["crapptdate"]); ?>&nbsp;</td>
				<td><?php echo $row["palname"]; ?>&nbsp;</td>
				<td><?php echo $row["pafname"]; ?>&nbsp;</td>
				<td><?php echo $row["crcasetypecode"]; ?>&nbsp;</td>
				<td><?php echo $row["crtherapytypecode"]; ?>&nbsp;</td>
				<td><?php echo $row["crcasestatuscode"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["crdate"]); ?>&nbsp;</td>
				<td><?php echo $row["cmname"]; ?>&nbsp;</td>
				<td><?php echo $row["crreadmit"]; ?>&nbsp;</td>
				<td><?php echo $row["crrelocated"]; ?>&nbsp;</td>
				<td>
				<input name="button[<?php echo $row["crid"]?>]" type="submit" value="Seen" />
				<input name="button[<?php echo $row["crid"]?>]" type="submit" value="No Show" />
				<input name="button[<?php echo $row["crid"]?>]" type="submit" value="Clinic Rescheduled" />
				<input name="button[<?php echo $row["crid"]?>]" type="submit" value="Cancel Case" />
				</td>
			</tr>
			<?php
				}
	?>
		</table>
		<ul class="pagination">
			<li class="page-item disabled"><a class="page-link" data-pageid="<?php if($pageno <= 1){ echo '#'; } else { echo ($pageno - 1); } ?>" href="#">Previous</a></li>
			<?php
			$outOfRange = false;

			for($i = 1; $i <= $total_pages; $i++) {

			    if ($i <= 2 || $i >= $total_pages - 2 || abs($i - $pageno) <= 2) {
			        // page number should be echoed so do as you did before

			        $outOfRange = false;

			        if($i == $pageno) {
			        	
			            echo '<li class="page-item"><a data-pageid="'.$i.'" href="#" class="page-link">'.$i.'</a></li>';
			        } else {

			            echo '<li class="page-item 132"><a data-pageid="'.$i.'" href="#" class="page-link">'.$i.'</a></li>';
			        }
			    } else {

			        $outOfRange = true;

			    }
			}
				?>
			<li class="page-item"><a class="page-link" data-pageid="<?php if($pageno >= $total_pages){ echo '#'; } else { echo ($pageno + 1); } ?>" href="#">Next</a></li>
		</ul>
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
		mysql_close($dbhandle);
	?>
	</fieldset>
</div>
</div>
	<?php
	}
	else
		unset($_POST['ClickedSearch']);
}
?>