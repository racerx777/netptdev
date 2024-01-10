<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
//declare the SQL statement that will query the database
$query  = "SELECT cqminactive, cqmselseq, cqmgroup, cqmdescription, cqmsql FROM master_collections_queue_groups ";
$where = array();
if(isset($_POST['cqminactive']) && !empty($_POST['cqminactive'])) 
	$where[] = "cqminactive= '" . $_POST['cqminactive'] . "'";

if(isset($_POST['cqmselseq']) && !empty($_POST['cqmselseq'])) 
	$where[] = "cqmselseq= '" . $_POST['cqmselseq'] . "'";

if(isset($_POST['cqmgroup']) && !empty($_POST['cqmgroup'])) 
	$where[] = "cqmgroup like '%" . $_POST['cqmgroup'] . "%'";

if(isset($_POST['cqmdescription']) && !empty($_POST['cqmdescription'])) 
	$where[] = "cqmdescription like '%" . $_POST['cqmdescription'] . "%'";

if(isset($_POST['cqmsql']) && !empty($_POST['cqmsql'])) 
	$where[] = "cqmsql like '%" . addslashes($_POST['cqmsql']) . "%'";

if(count($where) > 0) 
	$query .= " WHERE " . implode(" and ", $where);
$query .= " ORDER BY cqminactive, cqmselseq, cqmgroup"; 

if($queryresult = mysqli_query($dbhandle,$query)) {
	$numRows = mysqli_num_rows($queryresult);
//dump("query",$query);
?>

<fieldset>
<legend style="font-size:large;">Search Results</legend>
<?php
	if($numRows>0) {
		echo $numRows . " queues(s) found.";
?>
<form method="post" name="searchResults">
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th width="5%">Inactive Flag</th>
			<th width="5%">Sequence</th>
			<th width="10%">Queue</th>
			<th width="20%">Description</th>
			<th width="50%">SQL</th>
			<th width="10%">Functions</th>
		</tr>
		<?php
		while($row = mysqli_fetch_array($queryresult,MYSQLI_ASSOC)) {
			if($row['cqminactive'] == '1') {
				$rowstyle=' style="background-color:#FFFFCC;"'; 
				$togglebutton = 'Make Active';
			}
			else {
				$rowstyle='';
				$togglebutton = 'Make Inactive';
			}
?>
		<tr<?php echo $rowstyle; ?>>
			<td><?php echo $row["cqminactive"]; ?>&nbsp;</td>
			<td><?php echo $row["cqmselseq"]; ?>&nbsp;</td>
			<td><?php echo $row["cqmgroup"]; ?>&nbsp;</td>
			<td><?php echo $row["cqmdescription"]; ?>&nbsp;</td>
			<td><?php echo $row["cqmsql"]; ?>&nbsp;</td>
			<td nowrap="nowrap"><input name="button[<?php echo $row["cqmgroup"]?>]" type="submit" value="Edit" />
				<input name="button[<?php echo $row["cqmgroup"]?>]" type="submit" value="<?php echo $togglebutton ?>" /></td>
		</tr>
		<?php
		}
?>
	</table>
</form>
<?php
	}
	else {
		echo('No queues found.');
	}
}
else 
	error("001", "searchResults:Error<br />$query<br />".mysqli_error($dbhandle));	
//close the connection
?>
</fieldset>
