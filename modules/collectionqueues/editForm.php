<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


if(isset($_SESSION['id'])) {
	$query = "SELECT * FROM master_collections_queue_groups WHERE cqmgroup='".$_SESSION['id']."'";
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
		error("001", "Error<br />$query<br/>".mysqli_error($dbhandle));	
}
else
	error("003", "Non-unique field error (should never happen).");	


if(errorcount() == 0) {
?>

<div class="centerFieldset" style="margin-top:100px;">
	<form action="" method="post" name="editForm">
		<fieldset style="text-align:center;">
		<legend>Edit Queue Information</legend>
		<table style="text-align:left;">
			<tr>
				<td> Inactive Flag</td>
				<td><input name="cqminactive" type="checkbox" value="1" <?php if(isset($_POST['cqminactive']) && $_POST['cqminactive'] == '1') echo "checked"; ?> />
				</td>
			</tr>
			<tr>
				<td>Select Sequence</td>
				<td><input id="cqmselseq" name="cqmselseq" type="text" size="3" maxlength="11" value="<?php if(isset($_POST['cqmselseq'])) echo $_POST['cqmselseq'];  ?>"></td>
			</tr>
			<tr>
				<td>Queue Group</td>
				<td><?php 
if(userlevel() >= '90') 
	echo '<input id="cqmgroup" name="cqmgroup" type="text" size="2" maxlength="2" value="'.$_POST['cqmgroup'].'" onchange="upperCase(this.id)" />'; 
else  
	echo $_POST['cqmgroup'].'<input name="cqmgroup" type="hidden" value="'.$_POST['cqmgroup'].'" />';
?>
				</td>
			</tr>
			<tr>
				<td>Description</td>
				<td><input name="cqmdescription" type="text" size="50" maxlength="50" value="<?php if(isset($_POST['cqmdescription'])) echo $_POST['cqmdescription'];?>" />
				</td>
			</tr>
			<tr>
				<td>SQL</td>
				<td><textarea name="cqmsql" rows="20" cols="160" ><?php if(isset($_POST['cqmsql'])) echo $_POST['cqmsql'];?>
</textarea></td>
			</tr>
			<tr>
				<td colspan="2"></td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Cancel" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Delete" <?php if(userlevel()!='34') echo 'disabled="disabled"' ?> />
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
