<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 
$searchSaved = getformvars('notes', 'search');
if( !empty($searchSaved) || !empty($_POST['formSubmit']) || !empty($_POST['sort']) ) {
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars('notes', 'searchResults');
	// if Sort button pressed set sort values
	if(count($_POST['sort']) > 0) {
	// If Reset Sort Pressed then clear saved values
		if(!empty($_POST['sort']['RESETSORT'])) {
			clearformvars('notes', 'searchResults');
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
			setformvars('notes', 'searchResults', $sortSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query  = "
SELECT *
FROM notes n
";

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
					$formvarvalue = date("Ymd", strtotime($formvarvalue));
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

	if(count($where) > 0) 

// Default search for cases with status SCH and authorization status of NEW

		$query .= "WHERE " . implode(" and ", $where);
		$groupby =  "
					";
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
		$sortvartitles = substr($sortvartitles,0,-2) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="sort[RESETSORT]" type="submit" value="Reset Sort">';
		$order = "ORDER BY " . implode(",", $orderby);
	}
	else {
		$order = "ORDER BY nobnum, nopnum, crtdate";
	}

	$query.=" $order
LIMIT 100 ";
//dump("query",$query);
//dump("groupby",$groupby);
//dump("order",$order);
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$numRows = mysqli_num_rows($result);
	?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large;"><form method="post" name="sortReset">
		Notes Search Results <?php echo $sortvartitles;?>
	</form>
</legend>
	<?php
	if($numRows > 0) {
		if($numRows == 1)
			echo "$numRows match found.";
		else {
			if($numRows < 100)
				echo "$numRows matches found.";
			else
				echo "Over $numRows matches found. Did not display all rows.";
		}
	?>
	<form method="post" name="searchResults">
		<table cellpadding="3" cellspacing="0" width="100%" style="border: 2px solid rgb(0,0,0);">
			<tr>
				<th><input name="sort[noid]" type="submit" value="Note ID" /></th>
				<th><input name="sort[noapp]" type="submit" value="Application" /></th>
				<th><input name="sort[noappid]" type="submit" value="Application ID" /></th>
				<th><input name="sort[nobnum]" type="submit" value="Business Unit" /></th>
				<th><input name="sort[nopnum]" type="submit" value="Patient Num" /></th>
				<th><input name="sort[nobutton]" type="submit" value="Button" /></th>
				<th><input name="sort[crtdate]" type="submit" value="Create Date" /></th>
				<th><input name="sort[crtuser]" type="submit" value="Create User" /></th>
				<th><input name="sort[crtprog]" type="submit" value="Create Program" /></th>
				<th><input name="sort[upddate]" type="submit" value="Update Date" /></th>
				<th><input name="sort[upduser]" type="submit" value="Update User" /></th>
				<th><input name="sort[updprog]" type="submit" value="Update Program" /></th>
				<th>&nbsp;</th>
			</tr>
			<?php
		while($row = mysqli_fetch_assoc($result)) {
			if($savedid != $row['pnum']) {
				$breakid=TRUE;  
				$savedid = $row['pnum'];
				if($rowcolor == "#FFFFFF") 
					$rowcolor = "#CCCCCC";
				else 
					$rowcolor = "#FFFFFF";
			}
//			$function1='<input type="submit" name="navigation['.$row['pnum'].']" id="navigation['.$row['pnum'].']" value="Display" />';
		$application = 'notes';
		$noid = $row['noid'];
		$app = $row['noapp'];
		$appid = $row['noappid'];
		$bnum = $row['nobnum'];
		$pnum = $row['nopnum'];

		$button = 'Display';
		$vars=urlencode("application=$application&button[]=$button&noid=$noid&app=$app&appid=$appid&bnum=$bnum&pnum=$pnum");
		$functions='<input type="submit" name="navigation['.$vars.']" value="'.$button.'" />'; 

		$button = 'Edit';
		$vars=urlencode("application=$application&button[]=$button&noid=$noid&app=$app&appid=$appid&bnum=$bnum&pnum=$pnum");
		$functions.='<input type="submit" name="navigation['.$vars.']" value="'.$button.'" />'; 

		$button = 'Delete';
		$vars=urlencode("application=$application&button[]=$button&noid=$noid&app=$app&appid=$appid&bnum=$bnum&pnum=$pnum");
		$functions.='<input type="submit" name="navigation['.$vars.']" value="'.$button.'" />'; 
?>
			<tr style="background-color:<?php echo $rowcolor; ?>">
				<td valign="top" rowspan="3"><?php echo $row["noid"]; ?></td>
				<td><?php echo $row["noapp"]; ?></td>
				<td><?php echo $row["noappid"]; ?></td>
				<td><?php echo $row["nobnum"]; ?></td>
				<td><?php echo $row["nopnum"]; ?></td>
				<td><?php echo $row["nobutton"]; ?></td>
				<td><?php echo $row["crtdate"]; ?></td>
				<td><?php echo $row["crtuser"]; ?></td>
				<td><?php echo $row["crtprog"]; ?></td>
				<td><?php echo $row["upddate"]; ?></td>
				<td><?php echo $row["upduser"]; ?></td>
				<td><?php echo $row["updprog"]; ?></td>
				<td nowrap="nowrap"><?php echo $functions; ?></td>
			</tr>
			<tr><td colspan="11"><?php echo $row["nonote"]; ?></td></tr>
			<tr><td colspan="11"><?php echo $row["nodata"]; ?></td></tr>
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
			echo('No matches found.');
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