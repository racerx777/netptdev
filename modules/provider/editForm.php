<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
if(isset($_SESSION['id'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * FROM master_provider_groups WHERE pgmcode='" . $_SESSION['id'] . "'";
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
		<legend>Edit Provider Information</legend>
		<table style="text-align:left;">
			<tr>
				<td> Inactive </td>
				<td><input name="pgminactive" type="checkbox" value="1" <?php if(isset($_POST['pgminactive']) && $_POST['pgminactive'] == '1') echo "checked"; ?> />
				</td>
			</tr>
			<tr>
				<td>Business Unit</td>
				<td><select name="pgmbumcode" id="pgmbumcode">
					<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['businessunits'], $optionvaluefield='bumcode', $arrayofoptionfields=array('bumname'=>' (', 'bumcode'=>')'), $defaultoption=$_POST['pgmbumcode'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select></td>
			</tr>
			<tr>
				<td>Provider Office </td>
				<td><input id="pgmofc" name="pgmofc" type="text" size="3" maxlength="3" value="<?php if(isset($_POST['pgmofc'])) echo $_POST['pgmofc'];?>" >
				</td>
			</tr>
			<tr>
				<td>Provider Id </td>
				<td>
<?php 
if(userlevel() >= '90') 
	echo '<input id="pgmcode" name="pgmcode" type="text" size="3" maxlength="3" value="'.$_POST['pgmcode'].'" onchange="upperCase(this.id)" />'; 
else  
	echo $_POST['pgmcode'].'<input name="pgmcode" type="hidden" value="'.$_POST['pgmcode'].'" />';
?>
				</td>
			</tr>
			<tr>
				<td>Provider Name </td>
				<td><input name="pgmname" type="text" size="50" maxlength="50" value="<?php if(isset($_POST['pgmname'])) echo $_POST['pgmname'];?>" />
				</td>
			</tr>
			<tr>
				<td>e-Mail Address </td>
				<td><input name="pgmemail" type="text" size="20" maxlength="64" value="<?php if(isset($_POST['pgmemail'])) echo $_POST['pgmemail'];?>" />
				</td>
			</tr>
			<tr>
				<td>Phone Number </td>
				<td><input name="pgmphone" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['pgmphone'])) echo displayPhonePTOS($_POST['pgmphone']);?>" />
				</td>
			</tr>
			<tr>
				<td>Fax Number </td>
				<td><input name="pgmfax" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['pgmfax'])) echo displayPhonePTOS($_POST['pgmfax']);?>" />
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
