<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 

// If Search Variables Are Set, unset empty values
$searchSaved = getformvars('documentmanager', 'search');

foreach($searchSaved as $key=>$val) {
	if(empty($val))
		unset($searchSaved["$key"]);
}

// If more than just clinic was specified continue
if( (count($searchSaved)>0 ))  {

// If Reset Sort
	if(!empty($_POST['btnResetSort'])) {
		clearformvars('documentmanager', 'searchResults');
		unset($sortSaved);
	}

	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars('documentmanager', 'searchResults');
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
		setformvars('documentmanager', 'searchResults', $sortSaved);
	}

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
// prepare where clause
	$where = array();
//	$where[] = "crcnum IN " . getUserClinicsList() . " ";
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
		$orderbysql = " ORDER BY diid";
	}

	$limitsql = " LIMIT 100 ";

	$query  = "
		SELECT * 
		FROM D2M_interface 
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
	<legend style="font-size:large;">Search Documents Results <?php echo $sortvartitles; ?>	</legend>
	<form method="post" name="searchResults">
		<input name="btnResetSort" type="submit" value="Reset Sort">
		<?php
		if($numRows > 0) {
			if($numRows == 1)
				echo "$numRows record found.";
			else {
				if($numRows < 100)
					echo "$numRows records found.";
				else
					echo "Over $numRows records found. Did not display all records.";
			}
	?>
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr>
				<th>&nbsp;</th>
				<th><input name="sort[diid]" type="submit" value="ID" /></th>
				<th><input name="sort[distatus]" type="submit" value="Status" /></th>
				<th><input name="sort[difile_status]" type="submit" value="File Status" /></th>
				<th><input name="sort[diarchive_status]" type="submit" value="Archive Status" /></th>
				<th><input name="sort[diappname]" type="submit" value="Application" /></th>
				<th><input name="sort[diappdocname]" type="submit" value="Doc Name" /></th>
				<th><input name="sort[diappdocid]" type="submit" value="Doc ID" /></th>
				<th><input name="sort[diappdocdate]" type="submit" value="Doc Date" /></th>
				<th><input name="sort[diDOCENTITY]" type="submit" value="Doc Type" /></th>
				<th><input name="sort[diDOCFOLDER]" type="submit" value="Last Name" /></th>
				<th><input name="sort[diDOCSOURCE]" type="submit" value="First Name" /></th>
				<th><input name="sort[diDOCTYPE]" type="submit" value="Patient Number" /></th>
				<th><input name="sort[diIMPORTANCE]" type="submit" value="Clinic" /></th>
				<th><input name="sort[crtdate]" type="submit" value="Create Date" /></th>
				<th><input name="sort[crtuser]" type="submit" value="Create User" /></th>
				<th><input name="sort[upddate]" type="submit" value="Update Date" /></th>
				<th><input name="sort[upduser]" type="submit" value="Update User" /></th>
				<th>&nbsp;</th>
			</tr>
			<?php
			while($row = mysqli_fetch_assoc($result)) {
			?>
			<tr<?php echo "$rowstyle"; ?> >
				<td><input type="submit" name="button[<?php echo $row['diid'] ?>]" id="Edit" value="Edit" /><?php
					$diid=$row['diid'];
					$diappfilepath=$row['diappfilepath'];
					$diappfilename=$row['diappfilename'];
					$diappfiletype=$row['diappfiletype'];
					$url = "'/modules/documentmanager/documentView.php?diappfilepath=$diappfilepath&diappfilename=$diappfilename&diappfiletype=$diappfiletype'";
					$title="'View_Document'";
					$width="'height=1024px,width=1280px,scrollbars=yes'";
					$onclick='onclick="window.open('.$url.','.$title.','.$width.');"';
					$input='
<input name="button['.$diid.']" type="button" value="View" '."$onclick $disableview />";
					echo $input;?></td>
				<td><?php echo $row['diid']; ?></td>
				<td><?php echo $row["distatus"]; ?></td>
				<td><?php echo $row["difile_status"]; ?></td>
				<td><?php echo $row["diarchive_status"]; ?></td>
				<td><?php echo $row["diappname"]; ?></td>
				<td><?php echo $row["diappdocname"]; ?></td>
				<td><?php echo $row["diappdocid"]; ?></td>
				<td><?php echo displayDate($row["diappdocdate"]); ?></td>
				<td><?php echo $row["diDOCENTITY"]; ?></td>
				<td><?php echo $row["diDOCFOLDER"]; ?></td>
				<td><?php echo $row["diDOCSOURCE"]; ?></td>
				<td><?php echo $row["diDOCTYPE"]; ?></td>
				<td><?php echo $row["diIMPORTANCE"]; ?></td>
				<td><?php echo displayDate($row["crtdate"]); ?></td>
				<td><?php echo $row["crtuser"]; ?></td>
				<td><?php echo displayDate($row["upddate"]); ?></td>
				<td><?php echo $row["upduser"]; ?></td>
				<td><input type="submit" name="button[<?php echo $row['diid'] ?>]" id="Delete" value="Delete" /></td>
			</tr>
			<?php
			}
			foreach($_POST as $key=>$val)
				unset($_POST[$key]);
		?>
			<tr>
			<td colspan="19"><input type="submit" name="button[]" id="Add" value="Add" />
			</td>
			</tr>
		</table>
	</form>
	<?php
		}
		else 
			echo('No records found.');
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
else {
	info("000","You must specify at least one search criteria to perform a search.");
	displaysitemessages();
}
?>