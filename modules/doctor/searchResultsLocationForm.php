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
$searchlocationSaved = getformvars('doctor', 'searchlocation');
if(!empty($searchlocationSaved['dmid'])) {
	$dmid=$searchlocationSaved['dmid'];
	$dmlname=$searchlocationSaved['dmlname'];
	$dmfname=$searchlocationSaved['dmfname'];
}

if( !empty($searchlocationSaved) || !empty($_POST['buttonSearchLocation']) || !empty($_POST['sortlocation']) ) {
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortlocationSaved = getformvars('doctor', 'searchlocationResults');
	// if Sort button pressed set sort values
	if(isset($_POST['sortlocation']) && count($_POST['sortlocation']) > 0) {
	// If Reset Sort Pressed then clear saved values
		if(!empty($_POST['sortlocation']['RESETSORT'])) {
			clearformvars('doctor', 'searchlocationResults');
			unset($sortlocationSaved);
		}
		else {
	// determine sort field name from key
			$sortfield=key($_POST['sortlocation']);
	// if that key exists in the sort then toggle collation
			if(array_key_exists($sortfield, $sortlocationSaved)) {
				$collation = $sortlocationSaved["$sortfield"]['collation'];
				if($collation == 'desc')
					$sortlocationSaved["$sortfield"]["collation"] = '';
				else
					$sortlocationSaved["$sortfield"]["collation"] = 'desc';
			}
			else
				$sortlocationSaved["$sortfield"] = $searchlocationvars["$sortfield"];
			setformvars('doctor', 'searchlocationResults', $sortlocationSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$where = array();
	$patientcount=array();
	if(!empty($dmid)) {
//		$query  = "
//			SELECT dmid, dminactive, dlid, dlinactive, dlsname, dlname, dlphone, dlfax, dladdress, dlcity, dlzip
//			FROM  doctor_relationships
//			JOIN doctor_locations
//			ON drdlid = dlid
//			JOIN doctors
//			ON drdmid = dmid
//			WHERE dmid='$dmid'
//			";
		$query  = "
			SELECT dmid, dminactive, dmfname, dmlname, dlid, dlinactive, dlsname, dlname, dlphone, dlfax, dladdress, dlcity, dlzip, dlterritory
			FROM  doctors
			JOIN doctor_relationships
			ON dmid=drdmid
			JOIN doctor_locations
			ON drdlid = dlid
			";
//			$where[]="dmid='$dmid'";
		$patientcountquery="
			SELECT crrefdlid, count(*) as patientcount
			FROM cases
			WHERE crrefdmid='$dmid'
			GROUP BY crrefdlid
		";
	}
	else {
		$query  = "
			SELECT dlid, dlinactive, dlsname, dlname, dlphone, dlfax, dladdress, dlcity, dlzip, dlterritory
			FROM doctor_locations
		";
		$patientcountquery="
			SELECT crrefdlid, count(*) as patientcount
			FROM cases
			GROUP BY crrefdlid
		";
	}

	if($patientcountresult = mysqli_query($dbhandle,$patientcountquery)) {
		while($patientcountrow = mysqli_fetch_assoc($patientcountresult)) {
			$crrefdlid=$patientcountrow['crrefdlid'];
			$patientcount["$crrefdlid"]=$patientcountrow['patientcount'];
		}
	}

	foreach($searchlocationSaved as $formvar=>$formvarvalue) {
		if( isset($formvarvalue) && !empty($formvarvalue) )  {
                        //dump('svars', $searchlocationvars);
			$title = $searchlocationvars["$formvar"]['title'];
			$type = $searchlocationvars["$formvar"]['type'];
			$dbformat = $searchlocationvars["$formvar"]['dbformat'];
			$dblength = $searchlocationvars["$formvar"]['dblength'];
			$displayformat = $searchlocationvars["$formvar"]['displayformat'];
			$displaylength = $searchlocationvars["$formvar"]['displaylength'];
			$length = (isset($searchlocationvars["$formvar"]['length'])) ? $searchlocationvars["$formvar"]['length'] : false;
			$test = $searchlocationvars["$formvar"]['test'];
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
					$test = "LIKE '%" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "%'";
					break;
				default:
					$test = "= '" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "'";
			endswitch;
			$where[] = "$formvar $test";
		}
	}
	if(count($where) > 0)
		$query .= " WHERE " . implode(" and ", $where);
	else
		exit();
	// Sort Order - Contained in Session variable 'customerservice'=>'searchResults'=>array(field=>collation) as sort
	$orderby=array();
	if(empty($sortlocationSaved))
		$sortlocationvartitles = "unsorted (click column titles to add/toggle sort)";
	else {
		$sortlocationvartitles = "sorted by ";
		foreach($sortlocationSaved as $sortvar=>$sortvarproperty) {
			$orderby[] = trim($sortvar . " " . $sortvarproperty["collation"]);
			$sortlocationvartitles .= trim($sortvarproperty["title"] . " " . $sortvarproperty["collation"]) . ", ";
		}
	}
	if(count($orderby) > 0) {
		$sortlocationvartitles = substr($sortlocationvartitles,0,-2);
		$query .= " ORDER BY " . implode(",", $orderby);
	}
	else
		$query .= " ORDER BY dlinactive, dlsname, dlname, dlphone";
	$query.=" LIMIT 100 ";

if(empty($default['dmid'])){
	unset($default['dmlname']);
	unset($default['dmfname']);
}
//dump("query",$query);
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
	//execute the SQL query and return records
	if(!empty($dmfname))
		$doctor="Doctor $dmfname $dmlname";
	else {
		if(!empty($dmlname))
			$doctor="Doctor $dmlname";
		else
			$doctor="ALL DOCTORS";
	}
?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large;">Search <?php echo "$doctor";?> Location Results <?php echo $sortlocationvartitles;?></legend>
	<?php
		if($numRows > 0) {
			if($numRows == 1)
				echo "$numRows doctor location found.";
			else {
				if($numRows < 100)
					echo "$numRows doctor locations found.";
				else
					echo "Over $numRows doctors locations found. Did not display all doctors.";
			}
?>
	<form method="post" name="searchlocationResuts">
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr>
<?php if(userlevel()=='66' || userlevel()=='75' || userlevel()=='99') {
?>
				<th nowrap="nowrap"><input name="selectall" type="checkbox" value="Sel" onclick="selectallcheckboxes();" /></th>
<?php
}
?>
				<th><input name="sortlocation[dlsname]" type="submit" value="Short Name" /></th>
				<th><input name="sortlocation[dlname]" type="submit" value="Name" /></th>
				<th><input name="sortlocation[dlphone]" type="submit" value="Phone" /></th>
				<th><input name="sortlocation[dlemail]" type="submit" value="Email" /></th>
				<th><input name="sortlocation[dlfax]" type="submit" value="Fax" /></th>
				<th><input name="sortlocation[dladdress]" type="submit" value="Address" /></th>
				<th><input name="sortlocation[dlcity]" type="submit" value="City" /></th>
				<th><input name="sortlocation[dlzip]" type="submit" value="Zip" /></th>
				<th><input name="sortlocation[dlterritory]" type="submit" value="Territory" /></th>
				<?php if(!empty($dmid)) echo "<th>$dmlname Referrals</th>"; ?>
				<th><input name="sortlocation[RESETSORT]" type="submit" value="Reset Sort"></th>
			</tr>
			<?php
			$checkboxes=0;
			while($row = mysqli_fetch_assoc($result)) {
				if(isset($_POST['checkbox'][$dlid]) && $_POST['checkbox'][$dlid]==1)
					$_POST['checkbox'][$dlid]='checked';
				else
					$_POST['checkbox'][$dlid]='';
				if($row['dlinactive'] == '1') {
					$rowstyle=' style="background-color:#FFFFCC;"';
					$togglebutton = 'Make Location Active';
				}
				else {
					$rowstyle='';
					$togglebutton = 'Make Location Inactive';
				}
				if(!empty($dmid)) {
//					if(empty($patientcount[$row["dlid"]])) {
						$patientcounthtml = $patientcount[$row["dlid"]].'<input name="button['.$row["dlid"].']" type="submit" value="Remove Relationship" />';
//					}
//					else
//						$patientcounthtml = $patientcount[$row["dlid"]];
				}
?>
			<tr <?php echo $rowstyle; ?>>
<?php if(userlevel()=='66' || userlevel()=='75' || userlevel()=='99') {
		$checkboxes++;
?>
				<th rowspan="2" valign="top"><input name="checkbox[<?php echo $row["dlid"]; ?>]" type="checkbox" value="<?php echo $row["dlid"]; ?>" <?php if($_POST['checkbox'][$row["dlid"]]==1) echo "checked"; ?>/></th>
<?php
}
?>
				<td rowspan="2" valign="top"><?php if(!empty($row['dlsname'])) echo $row["dlsname"]; else echo "(id:".$row['dlid'].")"; ?>&nbsp;</td>
				<td rowspan="2" valign="top"><?php echo $row["dlname"]; ?>&nbsp;</td>
				<td rowspan="2" valign="top" nowrap="nowrap"><?php echo displayPhone($row["dlphone"]); ?>&nbsp;</td>
				<td rowspan="2" valign="top"><?php echo strtolower($row["dlemail"]); ?>&nbsp;</td>
				<td rowspan="2" valign="top" nowrap="nowrap"><?php echo displayPhone($row["dlfax"]); ?>&nbsp;</td>
				<td rowspan="2" valign="top"><?php echo $row["dladdress"]; ?>&nbsp;</td>
				<td rowspan="2" valign="top"><?php echo $row["dlcity"]; ?>&nbsp;</td>
				<td rowspan="2" valign="top"><?php echo $row["dlzip"]; ?>&nbsp;</td>
				<td rowspan="2" valign="top">
                                    <select name="territory[<?php echo $row["dlid"]; ?>]" />
                                        <option value="" /></option>
                                        <?php foreach($_SESSION['dlterritory'] as $terrId => $territory) : ?>

                                        <option value="<?php echo $terrId ?>" <?php if(isset($row['dlterritory']) && $terrId == $row['dlterritory']) { echo "selected=true"; } ?>><?php echo $territory; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
				<?php if(!empty($dmid)) echo '<td rowspan="2" valign="top">'.$patientcounthtml.'&nbsp;</td>'; ?>
				<td><input type="hidden" name="dmid" value="<?php echo $dmid ?>" />
					<input name="button[<?php echo $row["dlid"]?>]" type="submit" value="Edit Location" />
					<input name="button[<?php echo $row["dlid"]?>]" type="submit" value="<?php echo $togglebutton ?>" />
					<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Search Doctors" />
					<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Search Groups" disabled="disabled" />
				</td>
				<tr><td>
<?php
if($row['dlinactive'] != '1') {
$rowdlid=$row['dlid'];
$doctorrowlist=array();
$selectdoctors="SELECT dmid, dmlname, dmfname FROM doctor_relationships JOIN doctors ON drdmid=dmid WHERE drdlid='$rowdlid'";
if($resultdoctors=mysqli_query($dbhandle,$selectdoctors)) {
	while($doctorrow=mysqli_fetch_assoc($resultdoctors)) {
		$doctorrowdmid=$doctorrow['dmid'];
		$doctorrowlist["$doctorrowdmid"]=$doctorrow['dmfname']." ".$doctorrow['dmlname'];
	}
	echo "Doctors at location: ".implode(", ", $doctorrowlist);
}
else
	echo "NO SELECTED DOCTORS:$selectdoctors";
}
?>
				</td></tr>
			</tr>
			<?php
			} // while
			if( (userlevel()==66 || userlevel()=='75' || userlevel()==99) && !empty($checkboxes) ){
			?>
				<tr><th colspan="12" align="left">
                                    <input name="button[]" type="submit" value="Merge Selected Doctor Locations">
                                    <input name="button[]" type="submit" value="Make Selected Doctor Locations Inactive" />
                                    <input name="button[]" type="submit" value="Make Selected Doctor Locations Active" />
                                    <input name="button[]" type="submit" value="Update Doctor Territories" />
				</th></tr>
			<?php
			}
			?>
		</table>
	</form>
	<?php
		}
		else
			echo('No doctor locations found.');
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