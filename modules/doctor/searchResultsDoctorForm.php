<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(40); 
?>
<script type="text/javascript">
function selectallcheckboxes() {
// written by Daniel P 3/21/07
// toggle all checkboxes found on the page
	var inputlist = document.getElementsByTagName("input");
	for (i = 0; i < inputlist.length; i++) {
	if ( inputlist[i].getAttribute("type") == 'checkbox' ) { // look only at input elements that are checkboxes
		if (inputlist[i].checked) inputlist[i].checked = false
		else inputlist[i].checked = true;
		}
	}
}
</script>
<?php
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
	foreach($searchdoctorSaved as $formvar=>$formvarvalue) {
		if( isset($formvarvalue) && !empty($formvarvalue) )  {
			$title = $searchdoctorvars["$formvar"]['title'];
			$type = $searchdoctorvars["$formvar"]['type'];
			$dbformat = $searchdoctorvars["$formvar"]['dbformat'];
			$dblength = $searchdoctorvars["$formvar"]['dblength'];
			$displayformat = $searchdoctorvars["$formvar"]['displayformat'];
			$displaylength = $searchdoctorvars["$formvar"]['displaylength'];
			$length = $searchdoctorvars["$formvar"]['length'];
			$test = $searchdoctorvars["$formvar"]['test'];
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
//dump("query",$query);
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
	//execute the SQL query and return records
?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large;">Search Doctor Results <?php echo $sortdoctorvartitles;?></legend>
	<?php
		if($numRows > 0) {
			if($numRows == 1)
				echo "$numRows doctor found.";
			else {
				if($numRows < 100)
					echo "$numRows doctors found.";
				else
					echo "Over $numRows doctors found. Did not display all doctors.";
			}
?>
	<a href="/modules/doctor/reports/printXLS.php"><img src="/img/icon-xls.png" style="margin-left:5px;float:;cursor: pointer;margin-bottom: 10px;float: right;" ></a>
	<a href="/modules/doctor/reports/printPdf.php"><img src="/img/icon-pdf.png" style="cursor: pointer;position: relative;float: right;margin-right: 10px;">&nbsp;&nbsp;</a>
	<form method="post" name="searchdoctorResuts">
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr>
<?php if(userlevel()=='66' || userlevel()=='75' || userlevel()=='99') {
?>
				<th nowrap="nowrap"><input name="selectall" type="checkbox" value="Sel" onclick="selectallcheckboxes();" /></th>
<?php
}
?>
				<th><input name="sortdoctor[dmsname]" type="submit" value="Short Name" /></th>
				<th><input name="sortdoctor[dmlname]" type="submit" value="Last Name" /></th>
				<th><input name="sortdoctor[dmfname]" type="submit" value="First Name" /></th>
				<th><input name="sortdoctor[dmdscode]" type="submit" value="Specialty" /></th>
				<th><input name="sortdoctor[dmdclass]" type="submit" value="MD Class" /></th>
				<th><input name="sortdoctor[dmestrefer]" type="submit" value="Est Referrals" /></th>
				<th><input name="sortdoctor[dmdob]" type="submit" value="DOB" /></th>
				<th><input name="sortdoctor[RESETSORT]" type="submit" value="Reset Sort"></th>
			</tr>
			<?php
			$checkboxes=0;
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				if(isset($_POST['checkbox'][$dmid]) && $_POST['checkbox'][$dmid]==1)
					$_POST['checkbox'][$dmid]='checked';
				else
					$_POST['checkbox'][$dmid]='';
				if($row['dminactive'] == '1') {
					$rowstyle=' style="background-color:#FFFFCC;"'; 
					$togglebutton = 'Make Active';
				}
				else {
					$rowstyle='';
					$togglebutton = 'Make Inactive';
				}

				$searchlocations = "navigation[" . urlencode (
				'searchlocation[dmid]=' . $row["dmid"] . "&" .
				'searchlocation[dmlname]=' . $row["dmlname"] . "&" .
				'searchlocation[dmfname]=' . $row["dmfname"] . "&" .
				'buttonSearchLocation=1') . "]";

				$searchgroups = "navigation[" . urlencode (
				'searchgroup[dmid]=' . $row["dmid"] . "&" .
				'buttonSearchGroup=1') . "]";
?>
			<tr <?php echo $rowstyle; ?>>
<?php if(userlevel()=='66' || userlevel()=='75' || userlevel()=='99') {
		$checkboxes++;
?>
				<th><input name="checkbox[<?php echo $row["dmid"]; ?>]" type="checkbox" value="<?php echo $row["dmid"]; ?>" <?php if($_POST['checkbox'][$row["dmid"]]==1) echo "checked"; ?>/></th>
<?php
}
?>
				<td><?php if(!empty($row['dmsname'])) echo $row["dmsname"]; else echo "(id:".$row['dmid'].")"; ?>&nbsp;</td>
				<td><?php echo $row["dmlname"]; ?>&nbsp;</td>
				<td><?php echo $row["dmfname"]; ?>&nbsp;</td>
				<td><?php echo $_SESSION['dscodes'][$row["dmdscode"]]; ?>&nbsp;</td>
				<td><?php echo $_SESSION['dclasses'][$row["dmdclass"]]; ?>&nbsp;</td>
				<td><?php echo $row["dmestrefer"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["dmdob"]); ?>&nbsp;</td>
				<td><input name="button[<?php echo $row["dmid"];?>]" type="submit" value="Edit Doctor" />
					<input name="button[<?php echo $row["dmid"];?>]" type="submit" value="<?php echo $togglebutton ?>" />
					<input name="<?php echo $searchlocations; ?>" type="submit" value="Search Locations" />
					<input name="<?php echo $searchgroups; ?>" type="submit" value="Search Groups" disabled="disabled" />
<?php
				if(userlevel()=='66' || userlevel()=='75' || userlevel()=='99') {
?>
					<input name="button[<?php echo $row["dmid"]; ?>]" type="submit" value="UNDO Merge Selected Doctor Locations" />
<?php
				}
?>
				</td>
			</tr>
			<?php
			} // while
			if( (userlevel()==66 || userlevel()=='75' || userlevel()==99) && !empty($checkboxes) ){
			?>
				<tr><td colspan="9">
					<input name="button[]" type="submit" value="Merge Selected Doctors">
					<input name="button[]" type="submit" value="Make Selected Doctors Inactive" />
					<input name="button[]" type="submit" value="Make Selected Doctors Active" />
				</td></tr>
			<?php
			}
			?>
		</table>
	</form>
	<?php
		}
		else 
			echo('No doctors found.');
?>
	</fieldset>
</div>
<?php
	}
	else // $result
		error( "001", "QUERY:$query<br>ERROR:" . mysqli_error($dbhandle) );

	//close the connection
	mysqli_close($dbhandle);
}
?>