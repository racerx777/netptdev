<?php
if(isset($_SESSION['id'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(90); 
	$userid = $_SESSION['id'];
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * ";
	$query .= "FROM master_user ";
	$query .= "WHERE umid='" . $userid . "'";
	$result_id = mysqli_query($dbhandle,$query);
	if($result_id) {
		$numRows = mysqli_num_rows($result_id);
		if($numRows==1) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			foreach($result as $key=>$val) {
				$_POST[$key] = $val;
			}
		}
		else
			error('001', "Non-unique field error (should never happen).");	
	}
	else
		error('002', mysqli_error($dbhandle));	
}
else
	error('003', "id field error (should never happen).");	

if(errorcount() == 0) {
	// Clear Selected Clinics
//	foreach($_SESSION['clinics'] as $key=>$val)
//		$selected_clinic[$key]='';
	// Save Posted Clinic as Selected Clinic
//	if(isset($_POST['umclinic']) && !empty($_POST['umclinic'])) 
//		$selected_clinic[$_POST['umclinic']] = ' selected ';
	
	// Clear Selected Homepages
//	foreach($_SESSION['homepages'] as $key=>$val)
//		$selected_homepage[$key]='';
	// Save Posted Startup Screen as Selected Startup Screen
//	if(isset($_POST['umhomepage']) && !empty($_POST['umhomepage'])) 
//		$selected_homepage[$_POST['umhomepage']] = ' selected ';
	
	// Clear Selected User Roles
//	foreach($_SESSION['roles'] as $key=>$val)
//		$selected_role[$key]='';
	// Save Posted User Class as Selected User Class
//	if(isset($_POST['umrole']) && !empty($_POST['umrole'])) 
//		$selected_role[$_POST['umrole']] = ' selected ';
?>

<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="editForm">
		<fieldset>
		<legend>Edit User Information</legend>
		<table>
			<tr>
				<td> Inactive </td>
				<td colspan="7"><input name="uminactive" type="checkbox" value="1" <?php if(isset($_POST['uminactive']) && $_POST['uminactive'] == '1') echo "checked"; ?> />
				</td>
			</tr>
			<tr>
				<td>User Id </td>
				<td><input name="umuser" type="text" size="16" maxlength="16" value="<?php if(isset($_POST['umuser'])) echo $_POST['umuser'];?>" />
				</td>
				<td>Password </td>
				<td><input name="umpassword" type="password" size="16" maxlength="32" value="" />
				</td>
				<td>Name </td>
				<td><input name="umname" type="text" size="16" maxlength="64" value="<?php if(isset($_POST['umname'])) echo $_POST['umname'];?>" />
				</td>
				<td>e-Mail Address </td>
				<td><input name="umemail" type="text" size="16" maxlength="64" value="<?php if(isset($_POST['umemail'])) echo $_POST['umemail'];?>" />
				</td>
			</tr>
		</table>
		<div class="containedBox">
			<div style="float:left; margin:10px;">
				<input name="button[]" type="submit" value="Cancel" />
			</div>
			<div style="float:left; margin:10px;">
				<input name="button[<?php echo $userid; ?>]" type="submit" value="Delete" />
			</div>
			<div style="float:left; margin:10px;">
				<input name="button[<?php echo $userid; ?>]" type="submit" value="Update" />
			</div>
		</div>
		</fieldset>
	</form>
</div>
<?php 

$bumoptions=getSelectOptions($_SESSION['useraccess']['businessunits'], "bumcode", array("bumcode"=>"-", "bumname"=>""), $_POST['ucabumcode'], FALSE);

if(empty($_POST['ucabumcode'] ) || $_POST['ucabumcode'] == "*") unset($pgmfilter); else $pgmfilter = array($_POST['ucabumcode']);
$pgmoptions=getSelectOptions($_SESSION['useraccess']['providergroups'], "pgmcode", array("pgmcode"=>"-", "pgmname"=>""), $_POST['ucapgmcode'], FALSE, 'pgmbumcode', $pgmfilter);

if(empty($_POST['ucapgmcode'] ) || $_POST['ucapgmcode'] == "*") unset($cmfilter); else $cmfilter = array($_POST['ucapgmcode']);
$cmoptions = getSelectOptions($_SESSION['useraccess']['clinics'], "cmcnum", array("cmcnum"=>"-", "cmname"=>""), $_POST['ucacmcnum'], FALSE, 'cmpgmcode', $cmfilter);

$rolesoptions = getSelectOptions($_SESSION['site']['roles'], "rmcode", array("rmname"=>""), $_POST['ucarmcode'], FALSE);
$homepagesoptions = getSelectOptions($_SESSION['site']['homepages'], "hpmcode", array("hpmname"=>""), $_POST['ucahpmcode'], FALSE);
?>
<div class="containedBox">
	<fieldset>
	<legend>Add User Access Information</legend>
	<form method="post" name="addBarForm">
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
				<td><select name="ucacmcnum" size="1" onchange="javascript:submit();">
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
$query  = "SELECT * FROM user_clinic_access WHERE ucaumid='$userid' ORDER BY ucaumid, ucabumcode, ucapgmcode, ucacmcnum"; 
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
		$rolesarray = $_SESSION['site']['roles'];
		$homepagesarray = $_SESSION['site']['homepages']; // hpmid, hpmcode, hpmname
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
