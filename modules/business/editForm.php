<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
if(isset($_SESSION['id'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * FROM master_business_units WHERE bumcode='" . $_SESSION['id'] . "'";
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
		<legend>Edit Business Unit Information</legend>
		<table style="text-align:left;">
			<tr>
				<td> Inactive </td>
				<td><input name="buminactive" type="checkbox" value="1" <?php if(isset($_POST['buminactive']) && $_POST['buminactive'] == '1') echo "checked"; ?> />
				</td>
			</tr>
			<tr>
				<td>Business Unit Id </td>
				<td>

<?php 
if(userlevel() >= '90') 
	echo '<input id="bumcode" name="bumcode" type="text" size="3" maxlength="3" value="'.$_POST['bumcode'].'" onchange="upperCase(this.id)" />'; 
else  
	echo $_POST['bumcode'].'<input name="bumcode" type="hidden" value="'.$_POST['bumcode'].'" />';
?>
				</td>
			</tr>
			<tr>
				<td>Business Name </td>
				<td><input name="bumname" type="text" size="50" maxlength="50" value="<?php if(isset($_POST['bumname'])) echo $_POST['bumname'];?>" />
				</td>
			</tr>
			<tr>
				<td>Tax ID</td>
				<td><input id="bumtaxid" name="bumtaxid" type="text" size="32" maxlength="64" value="<?php if(isset($_POST['bumtaxid'])) echo $_POST['bumtaxid'];?>" >
				</td>
			</tr>
			<tr>
				<td>e-Mail Address </td>
				<td><input name="bumemail" type="text" size="20" maxlength="64" value="<?php if(isset($_POST['bumemail'])) echo $_POST['bumemail'];?>" />
				</td>
			</tr>
			<tr>
				<td>Phone Number </td>
				<td><input name="bumphone" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['bumphone'])) echo displayPhonePTOS($_POST['bumphone']);?>" />
				</td>
			</tr>
			<tr>
				<td>Fax Number </td>
				<td><input name="bumfax" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['bumfax'])) echo displayPhonePTOS($_POST['bumfax']);?>" />
				</td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Cancel" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Delete" <?php if(userlevel()!='90') echo 'disabled="disabled"' ?> />
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
