<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(5); 

$searchSaved = getformvars($thisapplication, $thisform);

if(!empty($_POST['buttonSetSearch']) || !empty($searchSaved) || !empty($_POST['sort']) ) {
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars($thisapplication, $thisform.'results');
	// if Sort button pressed set sort values
	if(count($_POST['sort']) > 0) {
	// If Reset Sort Pressed then clear saved values
		if(!empty($_POST['sort']['RESETSORT'])) {
			clearformvars($thisapplication, $thisform.'results');
			unset($sortSaved);
		}
		else {
	// determine sort field name from key
			$sortfield=key($_POST['sort']);
	// if that key exists in the sort then toggle collation
			if(array_key_exists($sortfield, $sortSaved)) {
				$collation = $sortSaved["$sortfield"]['collation'];
				if($collation == 'desc')
					$sortSaved["$sortfield"]["collation"] = '';
				else
					$sortSaved["$sortfield"]["collation"] = 'desc';
			}
			else
				$sortSaved["$sortfield"] = $searchvars["$sortfield"];
		setformvars($thisapplication, $thisform.'results', $sortSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query  = "SELECT * FROM patients LEFT JOIN cases ON paid=crpaid ";
	$where = array();
	$where[] = "crcnum IN " . getUserClinicsList();	
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
	if(count($where) > 0) {
		$query .= " WHERE " . implode(" and ", $where);
		$orderby=array();
		if(empty($sortSaved)) {
			$sortvartitles = "unsorted (click column titles to add/toggle sort)";
		}
		else {
			$sortvartitles = "sorted by ";
			foreach($sortSaved as $sortvar=>$sortvarproperty) {
				$orderby[] = trim($sortvar . " " . $sortvarproperty["collation"]);
				$sortvartitles .= trim($sortvarproperty["title"] . " " . $sortvarproperty["collation"]) . ", ";
			}
		}
		if(count($orderby) > 0) {
			$sortvartitles = substr($sortvartitles,0,-2);
			$query .= " ORDER BY " . implode(",", $orderby);
		}
		else 
			$query .= " ORDER BY palname, pafname, padob, paphone1, passn";
		$query.= " LIMIT 100 ";
		if($result = mysqli_query($dbhandle,$query)) {
			$numRows = mysqli_num_rows($result);
?>
<div class="containedBox">
	<fieldset>
		<legend style="font-size:large;">
			Search Patient Results <?php echo $sortvartitles;?><form method="post" name="searchReset"><input name="sort[RESETSORT]" type="submit" value="Reset Sort"></form>
    	</legend>
    <?php
	if($numRows > 0) {
		if($numRows == 1)
			echo "$numRows patient found.";
		else {
			if($numRows < 100)
				echo "$numRows patients found.";
			else
				echo "Over $numRows patients found. Did not display all patients.";
		}
	?>
	<form method="post" name="searchResults">
		<table width="100%" border="1" cellpadding="3" cellspacing="0" >
			<tr>
				<th>Functions</th>
				<th><input name="sort[palname]" type="submit" value="Last Name" /></th>
				<th><input name="sort[pafname]" type="submit" value="First Name" /></th>
				<th><input name="sort[crpnum]" type="submit" value="Patient Number" /></th>
				<th><input name="sort[passn]" type="submit" value="SSN" /></th>
				<th><input name="sort[paphone1]" type="submit" value="Phone 1" /></th>
				<th><input name="sort[paphone2]" type="submit" value="Phone 2" /></th>
				<th><input name="sort[pacellphone]" type="submit" value="Cell Phone" /></th>
				<th><input name="sort[padob]" type="submit" value="DOB" /></th>
				<th><input name="sort[crcnum]" type="submit" value="Clinic" /></th>
				<th><input name="sort[crinjurydate]" type="submit" value="DOI" /></th>
				<th><input name="sort[crcasetypecode]" type="submit" value="Case Type" /></th>
				<th><input name="sort[crcasestatuscode]" type="submit" value="Status" /></th>
				<th><input name="sort[crtherapytypecode]" type="submit" value="Therapy Type" /></th>
			</tr>
<?php
		while($row = mysqli_fetch_assoc($result)) {
			$paid=$row['paid'];
			$crid=$row['crid'];
			$pnum=$row['crpnum'];

			$style=array();
			$styles=array();
			if($row['crcasestatuscode']=='CAN')
				$style['background-color']="lightgray";

			foreach($style as $css=>$cssval) 
				$styles[]="$css:$cssval";

			if(is_array($styles) && count($styles)>0)
				$rowstyle = ' style="'.implode(";\n",$styles).'"';
			else
				$rowstyle="";
?>
			<tr<?php echo $rowstyle; ?>>
				<td nowrap="nowrap">
					<input type="submit" id="button" name="button[<?php echo $paid; ?>]" value="View Patient" />
					<input type="submit" id="button" name="button[<?php echo $crid; ?>]" value="View Case" />
				</td>
				<td nowrap="nowrap"><?php echo $row["palname"]; ?></td>
				<td nowrap="nowrap"><?php echo $row["pafname"]; ?></td>
				<td nowrap="nowrap"><?php echo $row["crpnum"]; ?></td>
				<td nowrap="nowrap"><?php echo displaySsn($row["passn"]); ?></td>
				<td nowrap="nowrap"><?php echo displayPhone($row["paphone1"]); ?></td>
				<td nowrap="nowrap"><?php echo displayPhone($row["paphone2"]); ?></td>
				<td nowrap="nowrap"><?php echo displayPhone($row["pacellphone"]); ?></td>
				<td nowrap="nowrap"><?php echo displayDate($row["padob"]); ?></td>
				<td nowrap="nowrap"><?php echo $row["crcnum"]; ?></td>
				<td nowrap="nowrap"><?php echo displayDate($row["crinjurydate"]); ?></td>
				<td nowrap="nowrap"><?php echo $row["crcasetypecode"]; ?></td>
				<td nowrap="nowrap"><?php echo $row["crcasestatuscode"]; ?></td>
				<td nowrap="nowrap"><?php echo $row["crtherapytypecode"]; ?></td>
			</tr>
<?php
		}
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
mysqli_close($dbhandle);
	?>
  </fieldset>
</div>
<?php
	} // end of where count > 0
	else
		unset($_POST['ClickedSearch']);
}
?>
