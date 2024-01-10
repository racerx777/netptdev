<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
$searchSaved = getformvars('collections', 'search');
if( !empty($searchSaved) || !empty($_POST['formSubmit']) || !empty($_POST['sort']) ) {
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars('collections', 'searchResults');
	// if Sort button pressed set sort values
	if(count($_POST['sort']) > 0) {
	// If Reset Sort Pressed then clear saved values
		if(!empty($_POST['sort']['RESETSORT'])) {
			clearformvars('collections', 'searchResults');
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
			setformvars('collections', 'searchResults', $sortSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query  = "
SELECT
	bnum, pnum, ssn, lname, fname, birth, injury, cnum, tbal,
	caid, capnum, caaccttype, caacctsubtype, caacctgroup, caacctstatus, calienstatus, cadorstatus, casettlestatus, cqgroup
FROM PTOS_Patients p
LEFT JOIN collection_accounts a
ON p.bnum=a.cabnum and p.pnum = a.capnum
LEFT JOIN collection_queue cq ON a.caid = cq.cqcaid
";

	$where = array();
//$where[] = "tbal > 0";
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
					GROUP BY p.lname, p.fname, p.ssn, p.birth
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
//		$order = "ORDER BY pnum, lname, fname";
	}

	$query.=" $order
LIMIT 25 ";
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
		Collections Search Results <?php echo $sortvartitles;?>
	</form>
</legend>
	<?php
	if($numRows > 0) {
		if($numRows == 1)
			echo "$numRows match found.";
		else {
			if($numRows < 25)
				echo "$numRows matches found.";
			else
				echo "Over $numRows matches found. Did not display all rows.";
		}
	?>
	<form method="post" name="searchResults">
		<table cellpadding="3" cellspacing="0" width="100%" style="border: 2px solid rgb(0,0,0);">
			<tr>
				<th>&nbsp;</th>
				<th><input name="sort[bnum]" type="submit" value="Bus Unit" /></th>
				<th><input name="sort[pnum]" type="submit" value="PTOS Acct Num" /></th>
				<th><input name="sort[ssn]" type="submit" value="SSN" /></th>
				<th><input name="sort[lname]" type="submit" value="Last Name" /></th>
				<th><input name="sort[fname]" type="submit" value="First Name" /></th>
				<th><input name="sort[birth]" type="submit" value="DOB" /></th>
				<th><input name="sort[injury]" type="submit" value="DOI" /></th>
				<th><input name="sort[caaccttype]" type="submit" value="Acct Type" /></th>
				<th><input name="sort[caacctsubtype]" type="submit" value="Sub-Type" /></th>
				<th><input name="sort[cqgroup]" type="submit" value="Queue" /></th>
				<th><input name="sort[caacctgroup]" type="submit" value="Group" /></th>
				<th><input name="sort[caacctstatus]" type="submit" value="Acct Sts" /></th>
				<th><input name="sort[calienstatus]" type="submit" value="Lien Sts" /></th>
				<th><input name="sort[cadorstatus]" type="submit" value="DOR Sts" /></th>
				<th><input name="sort[casettlestatus]" type="submit" value="Settle Sts" /></th>
				<th><input name="sort[caid]" type="submit" value="Coll Acct Id" /></th>
			</tr>
			<?php
//		$accttypecodes = collectionsAccountTypeCodes();
//		$acctsubtypecodes = collectionsAccountSubTypeCodes();
//		$acctstatuscodes = collectionsAccountStatusCodes();
//		$lienstatuscodes = collectionsLienStatusCodes();
		while($row = mysqli_fetch_assoc($result)) {
            if($row['tbal'] <= 0) {
                $rowcolor = "Silver";
                $textcolor = "Black";
            } else {
                $rowcolor = "LightCyan ";
                $textcolor = "Black";
            }
//			if($savedid != $row['pnum']) {
//				$breakid=TRUE;
//				$savedid = $row['pnum'];
//
//				else {
//					if($rowcolor == "LightCyan " || $rowcolor == "Silver" ) {
//						$rowcolor = "LightBlue";
//						$textcolor = "Black";
//					}
//					else {
//						$rowcolor = "LightCyan ";
//						$textcolor = "Black";
//					}
//				}
//			}
				$functions='<input type="submit" name="navigation['.$row['pnum'].']" id="navigation['.$row['pnum'].']" value="Work Account" />';
//				$functions='<input type="submit" name="crpnum['.$row['crpnum'].']" id="crpnum['.$row['crpnum'].']" value="Work Account" />';
//dump("functions",$functions);
//dump("row",$row);
		$application = 'collections';
		$button = 'Work Account';
                $button2 = 'Work Account New';
		$bnum = $row['bnum'];
		$pnum = $row['pnum'];
		$appid = $row['caid'];
//?noid=$noid&app=$app&appid=$appid&bnum=$bnum&pnum=$pnum&button=$button
//		$vars=urlencode("application=$application&button[]=$button&caid=$caid&bnum=$bnumpnum=$pnum");
		$vars=urlencode("app=$app&appid=$appid&bnum=$bnum&pnum=$pnum&button[$appid]=$button");
		$vars2=urlencode("app=$app&appid=$appid&bnum=$bnum&pnum=$pnum&button[$appid]=$button2");
                $functions  = '<input type="submit" name="navigation['.$vars.']" value="'.$button.'" />';
                if ($_SESSION['user']['umuser'] == 'mtwheaterC') {
                    $functions .= '<input type="submit" name="navigation['.$vars2.']" value="'.$button2.'" />';
                }

                $ssn=displaySsn($row['ssn']);
		if(empty($ssn))
			$ssn='&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;';
?>
			<tr style=" color:<?php echo $textcolor; ?>; background-color:<?php echo $rowcolor; ?>">
				<td nowrap="nowrap"><?php echo $functions; ?>
				<td><?php echo $row["bnum"]; ?></td>
				<td><?php echo $row["pnum"]; ?></td>
				<td nowrap="nowrap"><?php echo $ssn; ?></td>
				<td><?php echo $row["lname"]; ?></td>
				<td><?php echo $row["fname"]; ?></td>
				<td><?php echo displayDate($row["birth"]); ?></td>
				<td><?php echo displayDate($row["injury"]); ?></td>
				<td><?php echo $row["caaccttype"]; ?></td>
				<td><?php echo $row["caacctsubtype"]; ?></td>
				<td><?php echo $row["cqgroup"]; ?></td>
				<td><?php echo $row["caacctgroup"]; ?></td>
				<td><?php echo $row["caacctstatus"]; ?></td>
				<td><?php echo $row["calienstatus"]; ?></td>
				<td><?php echo $row["cadorstatus"]; ?></td>
				<td><?php echo $row["casettlestatus"]; ?></td>
				<td><?php echo $appid; ?></td>
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