<?php
if(isset($_SESSION['user']['umuser'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(5); 
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * ";
	$query .= "FROM master_user ";
	$query .= "WHERE umuser='" . $_SESSION['user']['umuser'] . "'";
	$result_id = mysqli_query($dbhandle,$query);
	if($result_id) {
		$numRows = mysqli_num_rows($result_id);
		if($numRows==1) {
			$result = mysqli_fetch_assoc($result_id);
			foreach($result as $key=>$val) {
				$_POST[$key] = $val;
			}
		}
		else
			error('001', "Non-unique field error (should never happen).");	
	}
	else
		error('002', mysqli_error($dbhandle));	
	if(errorcount() == 0) {
?>
<div class="centerFieldset" style="margin-top:100px;">
<form method="post" name="editForm">
	<fieldset>
	<legend>Edit My User Information</legend>
	<table>
		<tr>
			<td>Current Password (required)
			</td>
			<td><input name="passwordcurrent" type="password" size="16" maxlength="32" value="" />
			</td>
		</tr>
		<tr>
			<td>New Password
			</td>
			<td><input name="passwordnew1" type="password" size="16" maxlength="32" value="" />
			</td>
		</tr>
		<tr>
			<td>New Password (again)
			</td>
			<td><input name="passwordnew2" type="password" size="16" maxlength="32" value="" />
			</td>
		</tr>
		<tr>
			<td>Name
			</td>
			<td><input name="umname" type="text" size="16" maxlength="64" value="<?php if(isset($_POST['umname'])) echo $_POST['umname'];?>" />
			</td>
		</tr>
		<tr>
			<td>e-Mail Address
			</td>
			<td><input name="umemail" type="text" size="16" maxlength="64" value="<?php if(isset($_POST['umemail'])) echo $_POST['umemail'];?>" />
			</td>
		</tr>
	</table>
	<div class="containedBox">
		<!--<div style="float:left; margin:10px;"><input name="button[]" type="submit" value="Cancel/Exit" /></div>-->
		<div style="float:left; margin:10px;"><input name="button[]" type="submit" value="Update" /></div>
	</div>
	</fieldset>
</form>
</div>
<?php
	}
}
else
	error('003', "id field error (should never happen).");	
?>