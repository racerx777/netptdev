<?php
if(isset($_SESSION['id'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(90); 

	$userid = $_SESSION['id'];
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
// If user data not loaded yet, load it.
	if(!isset($_POST['master_user'])) {
		$master_user_query = "SELECT * FROM master_user WHERE umid='" . $userid . "'";
		$master_user_result_id = mysqli_query($dbhandle,$master_user_query);
		if($master_user_result_id) {
			$master_user_numRows = mysqli_num_rows($master_user_result_id);
			if($master_user_numRows == 1) {
				$master_user = mysqli_fetch_assoc($master_user_result_id);
// Create user description line.
				$userdescription = "User: " . $master_user['umname'] . "(" . $master_user['umuser'] . ")";
// Default the HomePage and Roles
				$_POST['ucarmcode'] = '10';
				$_POST['ucahpmcode'] = 'treatment';
			}
			else
				error('001', "Non-unique field error (should never happen).");	
		}
		else
			error('002', mysqli_error($dbhandle));
	}
}
else
	error('003', "id field error (should never happen).");	

if(errorcount() == 0) {
// Business Unit Select Options
				$bumoptions = getSelectOptions(
									$_SESSION['useraccess']['businessunits'], 
									"bumcode", 
									array("bumcode"=>"-", "bumname"=>""), 
									$_POST['ucabumcode'], 
									FALSE);
// Determine filter for provider groups using Business Unit as bound key value.
				if(empty($_POST['ucabumcode'] ) || $_POST['ucabumcode'] == "*")  unset($pgmfilter); 
				else $pgmfilter = array($_POST['ucabumcode']);
// Load Provider Groups Select Options 
				$pgmoptions = getSelectOptions(
									$_SESSION['useraccess']['providergroups'], 
									"pgmcode", 
									array("pgmcode"=>"-", "pgmname"=>""), 
									$_POST['ucapgmcode'], 
									FALSE, 
									'pgmbumcode', 
									$pgmfilter);
// Determine filter for clinics using provider group as bound key value
				if(empty($_POST['ucapgmcode'] ) || $_POST['ucapgmcode'] == "*") unset($cmfilter); 
				else $cmfilter = array($_POST['ucapgmcode']);
// Load Provider Groups Select Options
				$cmoptions = getSelectOptions(
									$_SESSION['useraccess']['clinics'], 
									"cmcnum", 
									array("cmcnum"=>"-", "cmname"=>""), 
									$_POST['ucacmcnum'], 
									FALSE, 
									'cmpgmcode', 
									$cmfilter);
// Load Roles Select Options
				$allroles=getRoles(1);
				$rolesoptions = getSelectOptions(
									$allroles, 
									"rmcode", 
									array("rmname"=>""), 
									$_POST['ucarmcode'], 
									FALSE);
// Load Home Pages Select Options
				$allhomepages=getHomePages(1);
				$homepagesoptions = getSelectOptions(
									$allhomepages, 
									"hpmcode", 
									array("hpmname"=>""), 
									$_POST['ucahpmcode'], 
									FALSE);
?>
<div class="centerFieldset" style="margin-top:100px;">
	<fieldset>
	<legend>User Information</legend>
	<div>
		<?php echo $userdescription; ?>
	</div>
	</fieldset>
</div>
<div class="containedBox">
	<fieldset>
	<legend>Add/Remove User Access Information</legend>
	<form method="post" name="addClinicAccessForm">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Business Unit(s)</th>
				<th>Provider Group(s)</th>
				<th>Clinic(s)</th>
				<th>Role</th>
				<th>Homepage</th>
			</tr>
			<tr>
				<td><select name="ucabumcode" size="1" onchange="javascript:submit();">
						<option value="*">*ALL</option>
						<?php echo $bumoptions; ?>
					</select>
				</td>
				<td><select name="ucapgmcode" size="1" onchange="javascript:submit();">
						<option value="*">*ALL</option>
						<?php echo $pgmoptions; ?>
					</select>
				</td>
				<td><select name="ucacmcnum" size="1">
						<option value="*">*ALL</option>
						<?php echo $cmoptions; ?>
					</select></td>
				<td><select name="ucarmcode" size="1">
						<?php echo $rolesoptions; ?>
					</select></td>
				<td><select name="ucahpmcode" size="1">
						<?php echo $homepagesoptions; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="5"><div>
						<div style="float:right;">
							<input name="button[<?php echo $userid; ?>]" id="button[]" type="submit" value="Add Access" />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>

<?php
// Clinic Access Records
$query = "SELECT * FROM user_clinic_access WHERE ucaumid='$userid' ORDER BY ucaumid, ucabumcode, ucapgmcode, ucacmcnum";
$result = mysqli_query($dbhandle,$query);
if($result) {
	$numRows = mysqli_num_rows($result);
?>
<div class="containedBox">
<fieldset>
<legend>User Clinic Access</legend>
<?php
	if($numRows>0) {
		echo $numRows . " user access records found.";
//		$rolesarray = $_SESSION['site']['roles'];
//		$rolesarray=getRoles(1);
//dump("rolesarray",$rolesarray);
//		$homepagesarray = $_SESSION['site']['homepages']; // hpmid, hpmcode, hpmname
//		$homepagesarray=getHomePages(1);
?>
<form method="post" name="useraccesslist">
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th>Business Unit(s)</th>
			<th>Provider Group(s)</th>
			<th>Clinic(s)</th>
			<th>Role</th>
			<th>Homepage</th>
			<th>Functions</th>
		</tr>
		<?php
		while($row = mysqli_fetch_assoc($result)) {
			if($row["ucabumcode"] == '*') 
				$ucabumcode = '*ALL';
			else
				$ucabumcode = $row["ucabumcode"];
			
			if($row["ucapgmcode"] == '*') 
				$ucapgmcode = '*ALL';
			else
				$ucapgmcode = $row["ucapgmcode"];
			
			if($row["ucacmcnum"] == '*') 
				$ucacmcnum = '*ALL';
			else
				$ucacmcnum = $row["ucacmcnum"];		
?>
		<tr>
			<td><?php echo $ucabumcode; ?>&nbsp;</td>
			<td><?php echo $ucapgmcode; ?>&nbsp;</td>
			<td><?php echo $ucacmcnum; ?>&nbsp;</td>
			<td><?php echo $rolesarray[$row['ucarmcode']]['rmname']; ?>&nbsp;</td>
			<td><?php echo $homepagesarray[$row['ucahpmcode']]['hpmname']; ?>&nbsp;</td>
			<td><input name="button[<?php echo $row["ucaid"]?>]" type="submit" value="Delete Access" /></td>
		</tr>
		<?php
		}
?>
	</table>
</form>
<?php
	}
	else {
		echo('No users found.');
	}
}
else 
	error("001", mysqli_error($dbhandle));
//close the connection
mysqli_close($dbhandle);
?>
</fieldset>
<?php
}
?>