<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(66); 

$script = 'editForm';
$table = 'doctors';
$keyfield = 'dmid';
$fields[$table]=array(
				'dminactive'=>'boolean',
				'dmsname'=>'name',
				'dmfname'=>'name',
				'dmlname'=>'name',
				'dmdescphys'=>'memo',
				'dmdob'=>'date',
				'dmdscode'=>'dscode',
				'dmdclass'=>'dclass',
				'dmdescwork'=>'memo',
				'dmwcmix'=>'percentage',
				'dmpimix'=>'percentage',
				'dmothermix'=>'percentage',
				'dmestrefer'=>'integer'
			);

$buttonvalue = 'Add';
$id=$_SESSION['id'];
if(!empty($id)) {
	$buttonvalue = 'Update';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * FROM $table WHERE $keyfield='$id'";
	$result_id = mysqli_query($dbhandle,$query);
	if($result_id) {
		$numRows = mysqli_num_rows($result_id);
		if($numRows==1) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			foreach($result as $fieldname=>$fieldvalue) {
				$_POST[$fieldname] = $fieldvalue;
			}
		}
		else
			error('001', "Non-unique field error (should never happen).");	
	}
	else
		error('002', mysqli_error($dbhandle));
}

if(errorcount() == 0) {
	// Clear Selected Specialty Codes
	foreach($_SESSION['dscodes'] as $key=>$val)
		$selected_dscode[$key]='';
	// Save Posted Specialty Code as Selected Specialty Code
	if(isset($_POST['dmdscode']) && !empty($_POST['dmdscode'])) 
		$selected_dscode[$_POST['dmdscode']] = ' selected ';
	
	// Clear Selected Doctor Classes
	foreach($_SESSION['dclasses'] as $key=>$val)
		$selected_dclass[$key]='';
	// Save Posted Doctor Classes as Selected Doctor Class
	if(isset($_POST['dmdclass']) && !empty($_POST['dmdclass'])) 
		$selected_dclass[$_POST['dmdclass']] = ' selected ';
?>
<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="editForm">
		<fieldset>
		<legend>Edit Doctor Information</legend>
		<table>
			<tr>
				<td>
					Inactive
				</td>
				<td>
					<input name="dminactive" type="checkbox" value="1" <?php if(isset($_POST['dminactive']) && $_POST['dminactive'] == '1') echo "checked"; ?> />
				</td>
			</tr>
			<tr>
				<td>Short Name
				</td>
				<td><input name="dmsname" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dmsname'])) echo $_POST['dmsname'];?>" />
				</td>
			</tr>
			<tr>
				<td>Last Name
				</td>
				<td><input name="dmlname" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['dmlname'])) echo $_POST['dmlname'];?>" />
				</td>
			</tr>
			<tr>
				<td>First Name
				</td>
				<td><input name="dmfname" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['dmfname'])) echo $_POST['dmfname'];?>" />
				</td>
			</tr>
			<tr>
				<td>Specialty
				</td>
				<td><select name="dmdscode" size="1">
						<option label=""></option>
						<?php
							foreach($_SESSION['dscodes'] as $key=>$val)
								echo "<option " . $selected_dscode[$key] . " value='" . $key . "'>" . $_SESSION['dscodes'][$key] . "</option>"; 
						?>
					</select></td>
			</tr>
			<tr>
				<td>MD Class
				</td>
				<td><select name="dmdclass" size="1">
						<option label=""></option>
						<?php
							foreach($_SESSION['dclasses'] as $key=>$val)
								echo "<option " . $selected_dclass[$key] . " value='" . $key . "'>" . $_SESSION['dclasses'][$key] . "</option>"; 
						?>
					</select></td>
			</tr>
			<tr>
				<td>Estimated Referrals
				</td>
				<td><input name="dmestrefer" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dmestrefer'])) echo $_POST['dmestrefer'];?>" />
				</td>
			</tr>
			<tr>
				<td>Date of Birth
				</td>
				<td><input name="dmdob" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['dmdob'])) echo date('m/d/Y', strtotime($_POST["dmdob"])); ?>" />
				</td>
			</tr>
			<tr>
				<td>Physical Description
				</td>
				<td><input name="dmdescphys" type="text" size="50" maxlength="50" value="<?php if(isset($_POST['dmdescphys'])) echo $_POST["dmdescphys"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Description of Work/Practice
				</td>
				<td><input name="dmdescwork" type="text" size="50" maxlength="50" value="<?php if(isset($_POST['dmdescwork'])) echo $_POST["dmdescwork"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Work Comp Percentage (Format 999.99)
				</td>
				<td><input name="dmwcmix" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dmwcmix'])) echo $_POST["dmwcmix"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Personal Injury Percentage
				</td>
				<td><input name="dmpimix" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dmpimix'])) echo $_POST["dmpimix"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Other Type of Work Percentage
				</td>
				<td><input name="dmothermix" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dmothermix'])) echo $_POST["dmothermix"]; ?>" />
				</td>
			</tr>
		</table>
		<div class="containedBox">
			<div style="float:left; margin:10px;"><input name="button[]" type="submit" value="Cancel" /></div>
			<div style="float:left; margin:10px;"><input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Delete" /></div>
			<div style="float:left; margin:10px;"><input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="<?php echo $buttonvalue; ?>" /></div>
		</div>
		</fieldset>
	</form>
</div>
<?php
}
?>