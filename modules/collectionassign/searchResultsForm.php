<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);

$query = "SELECT cqmselseq, cqmgroup, cqmdescription FROM master_collections_queue_groups WHERE cqminactive=0 ORDER BY cqmselseq, cqmgroup";
$queryresult = mysqli_query($dbhandle,$query);
$numRows = mysqli_num_rows($queryresult);
$queuelist=array();
for($i=1; $i<=$numRows; $i++) {
	if($queryrow = mysqli_fetch_array($queryresult, MYSQLI_ASSOC)) 
		$queuelist[$queryrow['cqmgroup']] = $queryrow;
}

//declare the SQL statement that will query the database
$query  = "SELECT cqauser, cqagroup FROM master_collections_queue_assign ";
$where = array();

if(isset($_POST['cqauser']) && !empty($_POST['cqauser'])) 
	$where[] = "cqauser like '%" . $_POST['cqauser'] . "%'";

if(isset($_POST['cqagroup']) && !empty($_POST['cqagroup'])) 
	$where[] = "cqagroup like '%" . $_POST['cqagroup'] . "%'";

if(count($where) > 0) 
	$query .= " WHERE " . implode(" and ", $where);
$query .= " ORDER BY cqauser, cqagroup"; 

if($queryresult = mysqli_query($dbhandle,$query)) {
	$numRows = mysqli_num_rows($queryresult);
//dump("query",$query);
?>

<fieldset>
<legend style="font-size:large;">Search Results</legend>
<?php
	if($numRows>0) {
		echo $numRows . " entries(s) found.";
?>
<form method="post" name="searchResults">
	<table border="1" cellpadding="3" cellspacing="0" width="576px" >
		<tr>
			<th width="30%">User</th>
			<th width="50%">Assigned Queue</th>
			<th width="20%">Functions</th>
		</tr>
		<?php
		while($row = mysqli_fetch_array($queryresult,MYSQLI_ASSOC)) {
?>
		<tr<?php echo $rowstyle; ?>>
			<td><?php echo $row["cqauser"]; ?>&nbsp;</td>
			<td><b><?php echo $row["cqagroup"]; ?></b>:<?php echo $queuelist[$row["cqagroup"]]['cqmdescription']; ?>&nbsp;</td>
			<td align="right" nowrap="nowrap"><input name="button[<?php echo $row["cqauser"]?>]" type="submit" value="Delete" /></td>
		</tr>
		<?php
		}
?>
	</table>
</form>
<?php
	}
	else {
		echo('No entries found.');
	}
}
else 
	error("001", mysqli_error($dbhandle));	
//close the connection
?>
</fieldset>
