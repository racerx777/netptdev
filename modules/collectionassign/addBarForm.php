<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);

require_once($_SERVER['DOCUMENT_ROOT'].'/common/user.options.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
global $dbhandle;
$dbhandle = dbconnect();


// List of Collector Users
$collectoroptions="";
if($list = getUserList()) {
	if(count($list) > 0) {
		$collectoroptions =  getSelectOptions(
			$arrayofarrayitems=$list, 
			$optionvaluefield='umuser', 
			$arrayofoptionfields=array(
				'umuser'=>' '
				), 
			$defaultoption=$_POST['cqauser'], 
			$addblankoption=TRUE, 
			$arraykey='umrole', 
			$arrayofmatchvalues=array('33'=>'33','34'=>'34','73'=>'73')); 
	}
	else
		error("999","User getSelectOptions() Error-No values in master table.");
}
else
	echo("Error-getUserList().");

function getQueueList() {
	global $dbhandle;
	// List of Active Queues
	$query = "SELECT cqmselseq, cqmgroup, cqmdescription FROM master_collections_queue_groups WHERE cqminactive=0 ORDER BY cqmselseq, cqmgroup";
	$queryresult = mysqli_query($dbhandle,$query);
	$numRows = mysqli_num_rows($queryresult);
	$queuelist=array();
	for($i=1; $i<=$numRows; $i++) {
		if($queryrow = mysqli_fetch_array($queryresult,MYSQLI_ASSOC)) 
			$queuelist[$queryrow['cqmgroup']] = $queryrow;
	}
	return($queuelist);
}

$queueoptions="";
if($list = getQueueList()) {
	if(count($list) > 0) {
		$queueoptions =  getSelectOptions(
			$arrayofarrayitems=$list, 
			$optionvaluefield='cqmgroup', 
			$arrayofoptionfields=array(
				'cqmgroup'=>': ', 
				'cqmdescription'=>'' 
				), 
			$defaultoption=$_POST['cqmgroup'], 
			$addblankoption=TRUE);
	}
	else
		error("999","User getSelectOptions() Error-No values in master table.");
}
else
	echo("Error-getQueueList().");


?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Add/Search Queue Assignment Information</legend>
	<form method="post" name="addForm">
		<table border="1" cellspacing="0" cellpadding="3" width="576px">
			<tr>
				<th width="30%">User Name</th>
				<th width="50%">Assigned Queue</th>
				<th width="20%">&nbsp;</th>
			</tr>
			<tr>
				<td nowrap="nowrap" style="text-decoration:none">
					<select id="cqauser" name="cqauser" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['cqauser'])) echo $_POST['cqauser'];?>" />
					<?php echo $collectoroptions; ?>
					</select>
				</td>
				<td nowrap="nowrap" style="text-decoration:none">
					<select id="cqagroup" name="cqagroup" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['cqagroup'])) echo $_POST['cqagroup'];?>" />
					<?php echo $queueoptions; ?>
					</select>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="6"><div>
						<div style="float:left;">
							<input name="button[]" type="submit" value="Search" />
						</div>
						<div style="float:right;">
							<input name="button[]" type="submit" value="Add" />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
