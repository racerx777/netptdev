<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$duplicatequery  = "
SELECT d.rowid, d.duplicatecount, a.* from treatment_header a JOIN 
(
	SELECT thcnum,thdate,thpnum,thlname,thfname, _rowid as rowid, count(*) as duplicatecount 
	FROM treatment_header b
	WHERE thsbmstatus > '0' and thsbmstatus < '900'
	GROUP BY thcnum,thdate,thpnum,thlname,thfname
	HAVING count(*) > 1
) as d
ON a.thcnum=d.thcnum and a.thdate=d.thdate and a.thpnum=d.thpnum and a.thlname=d.thlname and a.thfname=d.thfname
WHERE a.thsbmstatus<'900'
ORDER BY a.thcnum, a.thdate, a.thlname, a.thfname";
$duplicateresult = mysqli_query($dbhandle,$duplicatequery);
if(!$duplicateresult)
	error("001","MySql[duplicateresult]:" . mysqli_error($dbhandle));	
$numRows = mysqli_num_rows($duplicateresult);
?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Duplicates Dashboard</legend>
	<?php
if($numRows > 0) {
?>
	<form method="post" name="duplicateList">
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr style="color:#FFFFFF; background-color:#4682B4;">
				<th>did</th>
				<th>dcount</th>
				<th>id</th>
				<th>Clinic</th>
				<th>Date</th>
				<th>PTOS Number</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Case Type</th>
				<th>Visit Type</th>
				<th>Treatment Type</th>
				<th>Update Date</th>
				<th>Update User</th>
				<th>Submit Status</th>
				<th>Submit Date</th>
				<th>Submit User</th>
				<th>&nbsp;</th>
			</tr>
			<?php
	while($row = mysqli_fetch_array($duplicateresult)) {
		if($row["rowid"] != $lastrowid) {
			$toggle++;
			$lastrowid = $row['rowid'];
		}
		if(($toggle % 2) == 1)
			$redstyle = ' style="background-color:pink;"';
		else
			$redstyle = ' style="background-color:salmon;"';
?>
			<tr<?php echo $redstyle ?>>
				<td><?php echo($row["rowid"]); ?></td>
				<td><?php echo($row["duplicatecount"]); ?></td>
				<td><?php echo($row["thid"]); ?></td>
				<td><?php echo($row["thcnum"]); ?></td>
				<td><?php echo($row["thdate"]); ?></td>
				<td><?php echo($row["thpnum"]); ?></td>
				<td><?php echo($row["thlname"]); ?></td>
				<td><?php echo($row["thfname"]); ?></td>
				<td><?php echo($row["ctmcode"]); ?></td>
				<td><?php echo($row["vtmcode"]); ?></td>
				<td><?php echo($row["ttmcode"]); ?></td>
				<td><?php echo($row["updDate"]); ?></td>
				<td><?php echo($row["updUser"]); ?></td>
				<td><?php echo($row["thsbmstatus"]); ?></td>				
				<td><?php echo($row["thsbmdate"]); ?></td>
				<td><?php echo($row["thsbmuser"]); ?></td>
				<td><input name="button[<?php echo($row["thid"]); ?>]" type="submit" value="Make Inactive" />
				</td>
			</tr>
			<?php
	}
?>
		</table>
	</form>
	<?php
}
else {
	echo('No duplicates found.');
}
mysqli_close($dbhandle);
?>
	</fieldset>
</div>
