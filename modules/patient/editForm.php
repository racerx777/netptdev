<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');

if(isset($id)) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * FROM master_clinics WHERE cmcnum='" . $id . "'";
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
	error("002", "id field error (should never happen).");

if(errorcount() == 0) {
?>
<div class="centerFieldset" style="margin-top:100px;">
<form action="" method="post" name="editForm">
	<fieldset style="text-align:center;">
	<legend>Edit Clinic Information</legend>
	<table style="text-align:left;">
		<tr>
			<td>
				Inactive
			</td>
			<td>
				<input name="cminactive" type="checkbox" value="1" <?php if(isset($_POST['cminactive']) && $_POST['cminactive'] == '1') echo "checked"; ?> />
			</td>
		</tr>
		<tr>
			<td>Clinic Id
			</td>
			<td><input name="cmcnum" type="text" size="2" maxlength="2" value="<?php if(isset($_POST['cmcnum'])) echo $_POST['cmcnum'];?>" />
			</td>
		</tr>
		<tr>
			<td>Clinic Name
			</td>
			<td><input name="cmname" type="text" size="20" maxlength="30" value="<?php if(isset($_POST['cmname'])) echo $_POST['cmname'];?>" />
			</td>
		</tr>
		<tr>
			<td>e-Mail Address
			</td>
			<td><input name="cmemail" type="text" size="20" maxlength="64" value="<?php if(isset($_POST['cmemail'])) echo $_POST['cmemail'];?>" />
			</td>
		</tr>
		<tr>
			<td>Phone Number
			</td>
			<td><input name="cmphone" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['cmphone'])) echo $_POST['cmphone'];?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div>
					<div style="float:left; margin:20px;"><input name="button[]" type="submit" value="Cancel" /></div>
					<div style="float:left; margin:20px;"><input name="button[<?php echo $id; ?>]" type="submit" value="Delete" /></div>
					<div style="float:left; margin:20px;"><input name="button[<?php echo $id; ?>]" type="submit" value="Update" /></div>
				</div>
			</td>
		</tr>
	</table>
	</fieldset>
</form>
</div>
<?php
}
?>