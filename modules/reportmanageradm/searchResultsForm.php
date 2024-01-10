<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(99); 
//unset($_SESSION['id']);
//unset($_SESSION['button']);

// If Search Variables Are Set, unset empty values
$searchSaved = getformvars('reportmanageradm', 'search');

foreach($searchSaved as $key=>$val) {
	if(empty($val) && $searchvars["$key"]['type']!='checkbox')
		unset($searchSaved["$key"]);
}

// If more than just clinic was specified continue
if( (count($searchSaved)>1 ))  {

// If Reset Sort
	if(!empty($_POST['btnResetSort'])) {
		clearformvars('reportmanageradm', 'searchResults');
		unset($sortSaved);
	}
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars('reportmanageradm', 'searchResults');
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
		setformvars('reportmanageradm', 'searchResults', $sortSaved);
	}

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
// prepare where clause
	$where = array();
// Add where in bum and pgm
	$where[] = "rthcnum IN " . getUserClinicsList() . " ";
	foreach($searchSaved as $formvar=>$formvarvalue) {
		if( 
			(isset($formvarvalue) && !empty($formvarvalue)) || 
			($searchvars["$formvar"]['type']=='checkbox' && ($formvarvalue=='1' || $formvarvalue=='0') ) 
			) {
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
		$orderbysql = " ORDER BY rthsequence";
	}

	$limitsql = " LIMIT 100 ";

	$query  = "
		SELECT 
			rthinactive, rthid, rthbumcode, rthpgmcode, rthcnum, rthname, rthdescription, crtdate, upddate
		FROM report_template_header 
		$wheresql
		$orderbysql
		$limitsql
	";
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
	?>

<div class="containedBox">
	<fieldset>
	<form method="post" name="searchResults">
		<legend style="font-size:large;">Search Template Results <?php echo $sortvartitles;?>
		<input name="btnResetSort" type="submit" value="Reset Sort">
		</legend>
		<?php
		if($numRows > 0) {
			if($numRows == 1)
				echo "$numRows Templates found.";
			else {
				if($numRows < 100)
					echo "$numRows Templates found.";
				else
					echo "Over $numRows Templates found. Did not display all Templates.";
			}
	?>
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>&nbsp;</th>
				<th><input name="sort[rthinactive]" type="submit" value="Inactive" /></th>
				<th><input name="sort[rthbumcode]" type="submit" value="Business" /></th>
				<th><input name="sort[rthpgmcode]" type="submit" value="Provider" /></th>
				<th><input name="sort[rthcnum]" type="submit" value="Clinic" /></th>
				<th><input name="sort[rthname]" type="submit" value="Template Name" /></th>
				<th><input name="sort[rthdescription]" type="submit" value="Description" /></th>
				<th><input name="sort[crtdate]" type="submit" value="Create" /></th>
				<th><input name="sort[upddate]" type="submit" value="Update" /></th>
			</tr>
			<?php
			while($row = mysqli_fetch_assoc($result)) {
				$rowrthid=$row['rthid'];
				if($row['rthinactive'] == '1') {
					$rowstyle=' style="background-color:#FFFFCC;"'; 
					$togglebutton = 'Make Active';
				}
				else {
					$rowstyle='';
					$togglebutton = 'Make Inactive';
				}
				
// Create Report HTML for this case and count reports
				$sectioncount=0;
				$sectionquery="select * from report_template_detail where rtdrthid='$rowrthid' ORDER BY rtdsequence";
				if($sectionresult=mysqli_query($dbhandle,$sectionquery)) {
					$sectioncount=mysqli_num_rows($sectionresult);

					if(empty($sectioncount))
						unset($sectionrowspan);
					else
						$sectionrowspan = 'rowspan="' . ($sectioncount+1) . '"';
?>
			<tr<?php echo "$rowstyle"; ?>>
				<td valign="top" align="right" >
				<input name="button[<?php echo $row["rthid"]?>]" type="submit" value="<?php echo $togglebutton; ?>" />
				<input name="button[<?php echo $row["rthid"]?>]" type="submit" value="Add Section" />
				<input name="button[<?php echo $row["rthid"]?>]" type="submit" value="Edit Template" />
				</td>
				<td><?php echo $row["rthinactive"]; ?>&nbsp;</td>
				<td><?php echo $row["rthbumcode"]; ?>&nbsp;</td>
				<td><?php echo $row["rthpgmcode"]; ?>&nbsp;</td>
				<td><?php echo $row["rthcnum"]; ?>&nbsp;</td>
				<td><?php echo $row["rthname"]; ?>&nbsp;</td>
				<td><?php echo $row["rthdescription"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["crtdate"]); ?>&nbsp;</td>
				<td><?php echo displayDate($row["upddate"]); ?>&nbsp;</td>
			</tr>
			<?php
					while($sectionrow=mysqli_fetch_assoc($sectionresult)) {
?>
			<tr <?php echo $rowstyle; ?>>
				<td>&nbsp;</td>
				<td colspan="5" nowrap="nowrap" align="right">
					<input name="button[<?php echo $sectionrow["rtdid"]; ?>]" type="submit" value="Delete Section" />
					<input name="button[<?php echo $sectionrow["rtdid"]; ?>]" type="submit" value="Edit Section" />
				</td>
				<td colspan="1"><?php echo '('.$sectionrow['rtdsequence'].') '.$sectionrow['rtdname'].': '.$sectionrow['rtddescription']; ?> 
				</td>
				<td><?php echo displayDate($sectionrow['crtdate']); ?> 
				</td>
				<td><?php echo displayDate($sectionrow['upddate']); ?> 
				</td>
			</tr>
<?php
					} 
?>
			<tr<?php echo $rowstyle; ?>> </tr>
			<?php
				}
				else
					error('002', "SECTION QUERY:" . $sectionquery . ":" . mysqli_error($dbhandle));
			}
			foreach($_POST as $key=>$val)
				unset($_POST[$key]);
		?>
		</table>
	</form>
	<?php
		}
		else 
			echo('No templates found.');
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
displaysitemessages();
}
?>
