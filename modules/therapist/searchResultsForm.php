<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


//declare the SQL statement that will query the database
$query  = "SELECT * FROM therapists ";
$where = array();
if(isset($_POST['ttherap']) && !empty($_POST['ttherap'])) 
	$where[] = "ttherap= '" . $_POST['ttherap'] . "'";

if(isset($_POST['tname']) && !empty($_POST['tname'])) 
	$where[] = "tname like '%" . $_POST['tname'] . "%'";

if(isset($_POST['tlic']) && !empty($_POST['tlic'])) 
	$where[] = "tlic like '%" . $_POST['tlic'] . "%'";

if(isset($_POST['tnpi']) && !empty($_POST['tnpi'])) 
	$where[] = "tnpi like '%" . $_POST['tnpi'] . "%'";

if(isset($_POST['trefnum']) && !empty($_POST['trefnum'])) 
	$where[] = "trefnum like '%" . $_POST['trefnum'] . "%'";

if(isset($_POST['tnote']) && !empty($_POST['tnote'])) 
	$where[] = "tnote like '%" . $_POST['tnote'] . "%'";

if(count($where) > 0) 
	$query .= " WHERE " . implode(" and ", $where);
$query .= " ORDER BY tname, ttherap"; 
//execute the SQL query and return records
$result = mysqli_query($dbhandle,$query);
if($result) {
	$numRows = mysqli_num_rows($result);
?>
<fieldset>
<legend style="font-size:large;">Search Results</legend>
<?php
	if($numRows>0) {
		echo $numRows . " therapist(s) found.";
?>
<form method="post" name="searchResults">
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th>Code</th>
			<th>Name</th>
			<th>License</th>
			<th>NPI</th>
			<th>Reference Number</th>
			<th>Note</th>
			<th>Functions</th>
		</tr>
		<?php
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
?>
		<tr<?php echo $rowstyle; ?>>
			<td><?php echo $row["ttherap"]; ?>&nbsp;</td>
			<td><?php echo $row["tname"]; ?>&nbsp;</td>
			<td><?php echo $row["tlic"]; ?>&nbsp;</td>
			<td><?php echo $row["tnpi"]; ?>&nbsp;</td>
			<td><?php echo $row["trefnum"]; ?>&nbsp;</td>
			<td><?php echo $row["tnote"]; ?>&nbsp;</td>
			<td><input name="button[<?php echo $row["ttherap"]?>]" type="submit" value="Edit" /></td>
		</tr>
		<?php
		}
?>
	</table>
</form>
<?php
	}
	else {
		echo('No therapists found.');
	}
}
else 
	error("001", mysqli_error($dbhandle));	
//close the connection
mysqli_close($dbhandle);
?>
</fieldset>
