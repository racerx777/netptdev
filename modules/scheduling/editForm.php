<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 

$buttonvalue = 'Add';
if(isset($_SESSION['id'])) {
	$buttonvalue = 'Update';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * FROM patients WHERE paid='" . $_SESSION['id'] . "'";
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
if(errorcount() == 0) {
?>
<div class="centerFieldset" style="margin-top:100px;">
<form action="" method="post" name="editForm">
	<fieldset style="text-align:center;">
	<legend>Add/Edit Patient Information</legend>
	<table style="text-align:left;">
		<tr>
			<td>
				Inactive
			</td>
			<td>
				<input name="painactive" type="checkbox" value="1" <?php if(isset($_POST['painactive']) && $_POST['painactive'] == '1') echo "checked"; ?> />
			</td>
		</tr>
		<tr>
			<td>First Name
			</td>
			<td><input name="pafname" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['pafname'])) echo $_POST['pafname'];?>" />
			</td>
		</tr>
		<tr>
			<td>Middle Name
			</td>
			<td><input name="pamname" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['pamname'])) echo $_POST['pamname'];?>" />
			</td>
		</tr>
		<tr>
			<td>Last Name
			</td>
			<td><input name="palname" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['palname'])) echo $_POST['palname'];?>" />
			</td>
		</tr>
		<tr>
			<td>Gender
			</td>
			<td><input name="pasex" type="text" size="1" maxlength="1" value="<?php if(isset($_POST['pasex'])) echo $_POST['pasex'];?>" />
			</td>
		</tr>
		<tr>
			<td>Social Security Number
			</td>
			<td><input name="passn" type="text" size="9" maxlength="9" value="<?php if(isset($_POST['passn'])) echo $_POST['passn'];?>" />
			</td>
		</tr>
		<tr>
			<td>Date of Birth
			</td>
			<td><input name="padob" type="text" size="20" maxlength="30" value="<?php if(isset($_POST['padob'])) echo $_POST['padob'];?>" />
			</td>
		</tr>
		<tr>
			<td>Phone Number
			</td>
			<td><input name="paphone1" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['paphone1'])) echo $_POST['paphone1'];?>" />
			</td>
		</tr>
		<tr>
			<td>Address
			</td>
			<td><input name="paaddress" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['paaddress'])) echo $_POST['paaddress'];?>" />
			</td>
		</tr>
		<tr>
			<td>City
			</td>
			<td><input name="pacity" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['pacity'])) echo $_POST['pacity'];?>" />
			</td>
		</tr>
		<tr>
			<td>State
			</td>
			<td><input name="pastate" type="text" size="2" maxlength="2" value="<?php if(isset($_POST['pastate'])) echo $_POST['pastate'];?>" />
			</td>
		</tr>
		<tr>
			<td>Zip
			</td>
			<td><input name="pazip" type="text" size="9" maxlength="9" value="<?php if(isset($_POST['pazip'])) echo $_POST['pazip'];?>" />
			</td>
		</tr>
		<tr>
			<td>Note
			</td>
			<td><input name="panote" type="text" size="60" maxlength="60" value="<?php if(isset($_POST['panote'])) echo $_POST['panote'];?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div>
					<div style="float:left; margin:20px;"><input name="button[]" type="submit" value="Cancel" /></div>
					<div style="float:left; margin:20px;"><input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="<?php echo $buttonvalue; ?>" /></div>
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