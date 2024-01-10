<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
if(isset($_SESSION['id'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * FROM therapists WHERE ttherap='" . $_SESSION['id'] . "'";
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
			error("002", "Non-unique field error (should never happen).");	
	}
	else
		error("001", mysqli_error($dbhandle));	
}
else
	error("003", "Non-unique field error (should never happen).");	
	
if(errorcount() == 0) {
//dump('session business units',$_SESSION['useraccess']['businessunits']);
?>

<div class="centerFieldset" style="margin-top:100px;">
	<form action="" method="post" name="editForm">
		<fieldset style="text-align:center;">
		<legend>Edit Therapist Information</legend>
		<table style="text-align:left;">
			<tr>
				<td>Therapist Code </td>
				<td>
<?php 
if(userlevel() >= '90') 
	echo '<input id="ttherap" name="ttherap" type="text" size="2" maxlength="2" value="'.$_POST['ttherap'].'" onchange="upperCase(this.id)" />'; 
else  
	echo $_POST['ttherap'].'<input name="ttherap" type="hidden" value="'.$_POST['ttherap'].'" />';
?>
				</td>
			</tr>
			<tr>
				<td>Therapist Name </td>
				<td><input id="tname" name="tname" type="text" size="21" maxlength="21" value="<?php if(isset($_POST['tname'])) echo $_POST['tname'];?>" />
				</td>
			</tr>
			<tr>
				<td>License</td>
				<td><input id="tlic" name="tlic" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['tlic'])) echo $_POST['tlic'];?>" />
				</td>
			</tr>
			<tr>
				<td>NPI</td>
				<td><input id="tnpi" name="tnpi" type="text" size="17" maxlength="17" value="<?php if(isset($_POST['tnpi'])) echo $_POST['tnpi'];?>" />
				</td>
			</tr>
			<tr>
				<td>Reference Number</td>
				<td><input id="trefnum" name="trefnum" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['trefnum'])) echo $_POST['trefnum'];?>" />
				</td>
			</tr>
			<tr>
				<td>Note</td>
				<td><input id="tnote" name="tnote" type="text" size="60" maxlength="60" value="<?php if(isset($_POST['tnote'])) echo $_POST['tnote'];?>" />
				</td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Cancel" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Delete" <?php if(userlevel()!='90') echo 'disabled="disabled"'; ?> />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Update" />
						</div>
					</div></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php
}
?>
