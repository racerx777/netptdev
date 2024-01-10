<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(40); 
$searchdoctorSaved = getformvars('doctor', 'searchdoctor');
if( !empty($searchdoctorSaved) || !empty($_POST['buttonSetSearchDoctor']) || !empty($_POST['sortdoctor']) ) {
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
		$query .= " ORDER BY " . implode(",", $orderby);
	}
	else 
		$query .= " ORDER BY dminactive, dmsname, dmlname, dmfname"; 
	$query.=" LIMIT 100 ";
//dump("query",$query);
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
	//execute the SQL query and return records
?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large;">Search Doctor Group Results <?php echo $sortdoctorvartitles;?></legend>
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
	<form method="post" name="searchdoctorResuts">
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr>
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
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				if($row['dminactive'] == '1') {
					$rowstyle=' style="background-color:#FFFFCC;"'; 
					$togglebutton = 'Make Active';
				}
				else {
					$rowstyle='';
					$togglebutton = 'Make Inactive';
				}
?>
			<tr <?php echo $rowstyle; ?>>
				<td><?php echo $row["dmsname"]; ?>&nbsp;</td>
				<td><?php echo $row["dmlname"]; ?>&nbsp;</td>
				<td><?php echo $row["dmfname"]; ?>&nbsp;</td>
				<td><?php echo $_SESSION['dscodes'][$row["dmdscode"]]; ?>&nbsp;</td>
				<td><?php echo $_SESSION['dclasses'][$row["dmdclass"]]; ?>&nbsp;</td>
				<td><?php echo $row["dmestrefer"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["dmdob"]); ?>&nbsp;</td>
				<td><input name="button[<?php echo $row["dmid"]?>]" type="submit" value="Edit Doctor" />
					<input name="button[<?php echo $row["dmid"]?>]" type="submit" value="<?php echo $togglebutton ?>" />
					<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Search Locations" />
					<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Search Groups" />
				</td>
			</tr>
			<?php
			} // while
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