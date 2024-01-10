<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
$searchSaved = getformvars('scheduling', 'search');
if( !empty($searchSaved) || !empty($_POST['formSubmit']) || !empty($_POST['sort']) ) {
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars('scheduling', 'searchResults');
	// if Sort button pressed set sort values
	if(count($_POST['sort']) > 0) {
	// If Reset Sort Pressed then clear saved values
		if(!empty($_POST['sort']['RESETSORT'])) {
			clearformvars('scheduling', 'searchResults');
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
		setformvars('scheduling', 'searchResults', $sortSaved);
		}
	}

// Select Call Record $callid
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

if(isset($_POST['releasecallbutton'])) {
	$callid = array_keys($_POST['releasecallbutton']);
	unlockrow($dbhandle, 'case_scheduling_queue', 'csqid', $callid[0]);
}

if(isset($_POST['placecallbutton'])) {
	$callid = array_keys($_POST['placecallbutton']);
	window.open();
}
// Check for user locked records
	$user = getuser();
	unset($callid);

	$lockquery  = "SELECT csqid FROM case_scheduling_queue where lockuser = '$user' limit 1";
	if($lockresult = mysqli_query($dbhandle,$lockquery)) {
		if($lockrow = mysqli_fetch_assoc($lockresult)) {
// Update LockUser and LockTime
			$callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $lockrow['csqid']);
		}
	}
	else
		error("001", "lockquery:" . $lockquery . "<br>Error:" . mysqli_error($dbhandle));

	if(!isset($callid)) {
// Check for calls
		$priorityselect = "SELECT csqid FROM case_scheduling_queue ";
		$prioritywhere = "where csqresult IS NULL ";
		$priorityorderby = "order by csqpriority, csqschcalldate, csqid LIMIT 1";

// Check for primary calls
		$priorityquery  = "$priorityselect $prioritywhere and csqpriority between 10 and 19 and csqschcalldate <= NOW() - INTERVAL 5 MINUTE $priorityorderby";
		if($priorityresult = mysqli_query($dbhandle,$priorityquery)) {
			if($priorityrow = mysqli_fetch_assoc($priorityresult)) {
				$callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $priorityrow['csqid']);
			}
		}
		else
			error("001", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
	}

	if(!isset($callid)) {
// Check for secondary calls
		$priorityquery  = "$priorityselect $prioritywhere and csqpriority between 20 and 29 and csqschcalldate <= NOW() - INTERVAL 5 MINUTE $priorityorderby";
		if($priorityresult = mysqli_query($dbhandle,$priorityquery)) {
			if($priorityrow = mysqli_fetch_assoc($priorityresult)) {
				$callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $priorityrow['csqid']);
			}
		}
		else
			error("001", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
	}

	if(!isset($callid)) {
// Check for remaining calls
		$priorityquery  = "$priorityselect $prioritywhere and csqpriority > 29 $priorityorderby";
		if($priorityresult = mysqli_query($dbhandle,$priorityquery)) {
			if($priorityrow = mysqli_fetch_assoc($priorityresult)) {
				$callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $priorityrow['csqid']);
			}
		}
		else
			error("001", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
	}

	// where
	$where = array();
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

	if(count($where) > 0) {
		$query .= " WHERE " . implode(" and ", $where);
	
	//
	// Sort Order - Contained in Session variable 'customerservice'=>'searchResults'=>array(field=>collation) as sort
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
	if(count($orderby) > 0) {
		$sortvartitles = substr($sortvartitles,0,-2);
		$query .= " ORDER BY " . implode(",", $orderby);
	}
	else {
//		$query .= " ORDER BY palname, pafname, padob, paphone1, passn";
	}
	$query.=" Limit 10";
	$query = "SELECT * from case_scheduling_queue LEFT JOIN cases ON csqcrid=crid LEFT JOIN patients ON crpaid=paid WHERE csqid=$callid";
		$result = mysqli_query($dbhandle,$query);
		if($result) {
			$numRows = mysqli_num_rows($result);
	?>
	<div class="containedBox">
	<fieldset>
	<legend class="boldLarger">My Next Call:</legend>
	<?php
			if($numRows>0) {
//				echo $numRows . " patients(s) found.";
	?>
	<form method="post" name="searchResults">
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr>
				<th><input name="sort[csqpriority]" type="submit" value="Call Priority" /></th>
<!--				<th><input name="sort[csqschcalldate]" type="submit" value="Scheduled Call Date/Time" /></th>
-->				<th><input name="sort[crlname]" type="submit" value="Last Name" /></th>
				<th><input name="sort[crfname]" type="submit" value="First Name" /></th>
				<th><input name="sort[padob]" type="submit" value="DOB" /></th>
<!--				<th><input name="sort[paphone1]" type="submit" value="Phone" /></th>
-->				<th><input name="sort[passn]" type="submit" value="SSN" /></th>
				<th><input name="sort[RESETSORT]" type="submit" value="Reset Sort"></th>
			</tr>
			<?php
				while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				if($row['painactive'] == '1') {
					$rowstyle=' style="background-color:#FFFFCC;"'; 
					$togglebutton = 'Make Active';
				}
				else {
					$rowstyle='';
					$togglebutton = 'Make Inactive';
				}
	?>
			<tr<?php echo $rowstyle; ?>>
				<td><?php echo $row["csqpriority"]; ?>&nbsp;</td>
<!--				<td><?php echo $row["csqschcalldate"]; ?>&nbsp;</td>
-->				<td><?php echo $row["palname"]; ?>&nbsp;</td>
				<td><?php echo $row["pafname"]; ?>&nbsp;</td>
				<td><?php echo date("m/d/Y", strtotime($row["padob"])); ?>&nbsp;</td>
<!--				<td><?php echo $row["paphone1"]; ?>&nbsp;</td>
-->				<td><?php echo $row["passn"]; ?>&nbsp;</td>
				<td><input name="releasecallbutton[<?php echo $row["csqid"]?>]" type="submit" value="Release Call" />
				<input name="button[<?php echo $row["csqid"]?>]" type="submit" value="Place Call" />
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
				echo('No patients found.');
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