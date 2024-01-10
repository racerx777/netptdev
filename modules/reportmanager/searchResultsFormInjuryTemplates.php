<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 

unset($_SESSION['id']);
unset($_SESSION['button']);
// If Search Variables Are Set, unset empty values
$searchSaved = getformvars('reportmanager', 'searchTemplate');

foreach($searchSaved as $key=>$val) {
	if(empty($val))
		unset($searchSaved["$key"]);
}

// If more than just clinic was specified continue
if( count($searchSaved)==0 )  {

// If Reset Sort
	if(!empty($_POST['btnResetSort'])) {
		clearformvars('reportmanager', 'searchTemplateResults');
		unset($sortSaved);
	}
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars('reportmanager', 'searchTemplateResults');
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
		setformvars('reportmanager', 'searchTemplateResults', $sortSaved);
	}

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
// prepare where clause
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
	if(count($where) > 0) 
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
		$orderbysql = " ORDER BY ritrtid, ritname, ritdescription, ritdispseq, crtdate, crtuser, crtprog, upddate, upduser, updprog";
	}

	$limitsql = " LIMIT 100 ";

	$query  = "
		SELECT *
		FROM report_injury_templates 
		$wheresql
		$orderbysql
		$limitsql
	";
// dump("query",$query);
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
	?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large;">Search Templates Results <?php echo $sortvartitles;?></legend>
	<form style="width:800px; border:1px; border-color:#000000; border-style:solid;" id="TemplateList" name="TemplateList" method="post">
<?php
		if($numRows > 0) {
			if($numRows == 1)
				echo "$numRows template found.";
			else {
				if($numRows < 100)
					echo "$numRows templates found.";
				else
					echo "Over $numRows templates found. Did not display all templates.";
			}
?>
		<input name="btnResetSort" type="submit" value="Reset Sort">
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr>
				<th>&nbsp;</th>
				<th><input name="sort[ritinactive]" type="submit" value="Inactive" /></th>
				<th><input name="sort[ritrtid]" type="submit" value="Type" /></th>
				<th><input name="sort[ritid]" type="submit" value="ID" /></th>
				<th><input name="sort[ritname]" type="submit" value="Name" /></th>
				<th><input name="sort[ritdescription]" type="submit" value="Description" /></th>
				<th><input name="sort[ritdispseq]" type="submit" value="Disp Seq" /></th>
				<th><input name="sort[crtdate]" type="submit" value="Crt Date" /></th>
				<th><input name="sort[crtuser]" type="submit" value="Crt User" /></th>
				<th><input name="sort[crtprog]" type="submit" value="Crt Prog" /></th>
				<th><input name="sort[upddate]" type="submit" value="Upd Date" /></th>
				<th><input name="sort[upduser]" type="submit" value="Upd User" /></th>
				<th><input name="sort[updprog]" type="submit" value="Upd Prog" /></th>
			</tr>
			<?php
			while($row = mysqli_fetch_assoc($result)) {
				$rowid=$row['ritid'];
				if($row['painactive'] == '1') {
					$rowstyle=' style="background-color:#FFFFCC;"'; 
					$togglebutton = 'Make Active';
				}
				else {
					$rowstyle='';
					$togglebutton = 'Make Inactive';
				}
?>
			<tr<?php echo "$rowstyle"; ?>>
				<td valign="middle" align="right" >
					<?php
					if(empty($row['ritinactive'])) {
//						echo('<input name="button['.$row["ritid"].']" type="submit" value="Edit Template" />');

		$application = 'Report Manager';
		$button = 'Edit Template'; // Any unfiled reports that are assigned
		$vars=urlencode("application=$application&button[$rowid]=$button");
		echo('<input type="submit" name="navigation['.$vars.']" value="'.$button.'" />'); 

					}
					else
						echo('<div style="font-size:xx-small; color:gray;">(not&nbsp;active)</div>');
					?>
				</td>
				<td><?php echo $row["ritinactive"]; ?>&nbsp;</td>
				<td><?php echo $row["ritrtid"]; ?>&nbsp;</td>
				<td><?php echo $row["ritid"]; ?>&nbsp;</td>
				<td><?php echo $row["ritname"]; ?>&nbsp;</td>
				<td><?php echo $row["ritdescription"]; ?>&nbsp;</td>
				<td><?php echo $row["ritdispseq"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["crtdate"]); ?>&nbsp;</td>
				<td><?php echo $row["crtuser"]; ?>&nbsp;</td>
				<td><?php echo $row["crtprog"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["upddate"]); ?>&nbsp;</td>
				<td><?php echo $row["upduser"]; ?>&nbsp;</td>
				<td><?php echo $row["updprog"]; ?>&nbsp;</td>
			</tr>
<?php
			}
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
?>