<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/common/session.php');
securitylevel(33); 
dumppost();
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


// This function will list users that can be assigned to a collection queue
function getQueueUserAssignment() {
	$list=array();
	$query = "
	SELECT umid, umuser, umname, cqmgroup
	FROM master_user 
	LEFT JOIN master_collections_queue_groups c
	ON umuser=cqmcollector
	WHERE uminactive=0 and umrole IN ('33','34') and (cqminactive = 0 or cqminactive IS NULL)
	ORDER BY umname
	";
	if($result = mysqli_query($dbhandle,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$list[$row['umuser']]=$row;
		}
		return($list);
	}
	else {
		error("001",mysqli_error($dbhandle));
		return(false);
	}
}

// Display list of all queues in build order
// Allow user to change the assigned collector
// Dropdown only lists active collections persons who are not assigned a queue and a 'None' option
$listoptions="";
if($list = getQueueUserAssignment()) {
	if(count($list) > 0) {
		$listoptions =  getSelectOptions(
			$arrayofarrayitems=$list, 
			$optionvaluefield='umuser', 
			$arrayofoptionfields=array(
				'umuser'=>': ', 
				'umname'=>'' 
				), 
			$defaultoption=NULL, 
			$addblankoption=TRUE); 
	}
	else
		error("999","User getSelectOptions() Error-No values in master table.");
}
else
	echo("Error-getUserList().");

$select="SELECT * FROM master_collections_queue_groups WHERE cqminactive=0 ORDER BY cqmselseq";
if($result = mysqli_query($dbhandle,$select)) {
	while($row = mysqli_fetch_assoc($result)) {
		$queuelist[$row['cqmgroup']]=$row;
	}
}
//dump("queuelist",$queuelist);
// Display Queue List with dropdowns for each and an update or cancel button
?>

<form method="post" name="QueueList">
	<table cellpadding="3" cellspacing="0" style="border: 2px solid rgb(0,0,0);">
		<tr style="background-color:#3300CC; color:#FFFFFF">
			<th>Queue Name</th>
			<th>Queue Description</th>
			<th>Collector Assigned</th>
			<th>Assign to</th>
		</tr>
		<?php
foreach($queuelist as $key=>$row) {
?>
		<tr>
			<td nowrap="nowrap"><?php echo $row["cqmgroup"]; ?></td>
			<td nowrap="nowrap"><?php echo $row["cqmdescription"]; ?></td>
			<td nowrap="nowrap"><?php if(empty($row['cqmcollector'])) echo "*** Not Assigned ***"; else echo $row["cqmcollector"]; ?></td>
			<td><select name="newcollector[<?php echo $row['cqmgroup'] ?>]" value="<?php echo $row['cqmcollector'];?>">
					<?php echo $listoptions; ?>
				</select></td>
		</tr>
		<?php 
}
?>
		<tr style="border: 2px solid rgb(0,0,0);">
			<td colspan="2"><input type="submit" value="Cancel"></td>
			<td colspan="2"><input type="submit" value="Update Queue Assignment"></td>
		</tr>
	</table>
</form>
