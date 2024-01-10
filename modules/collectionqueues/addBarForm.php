<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query = "SELECT cqminactive, cqmselseq, cqmgroup, cqmdescription, cqmsql FROM master_collections_queue_groups ORDER BY cqmselseq";
$queryresult = mysqli_query($dbhandle,$query);
$numRows = mysqli_num_rows($queryresult);
$master_collections_queue_groups=array();
for($i=1; $i<=$numRows; $i++) {
	if($queryrow = mysqli_fetch_array($queryresult, MYSQLI_ASSOC)) 
		$master_collections_queue_groups[$queryrow['cqmgroup']] = $queryrow;
}
?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Add/Search Queue Information</legend>
	<form method="post" name="addForm">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th width="5%">Inactive Flag</th>
				<th width="5%">Sequence</th>
				<th width="10%">Queue</th>
				<th width="20%">Description</th>
				<th width="50%">SQL</th>
				<th width="10%">&nbsp;</th>
			</tr>
			<tr>
<?php
if(isset($_POST['cqminactive'])) {
	if($_POST['cqminactive']=='1') 
		$inactive='selected="selected"';
	else {
		if($_POST['cqminactive']=='0') 
			$active='selected="selected"';
		else
			$blank='selected="selected"';
	}
}
?>
				<td valign="top"><select name="cqminactive" id="cqminactive">
						<option value="" <?php echo $blank; ?>></option>
						<option value="0" <?php echo $active; ?>>Active</option>
						<option value="1"<?php echo $inactive; ?>>Inactive</option>
					</select>
				</td>
				<td valign="top"><input id="cqmselseq" name="cqmselseq" type="text" size="3" maxlength="11" value="<?php if(isset($_POST['cqmselseq'])) echo $_POST['cqmselseq'];  ?>"></td>
				<td valign="top"><input id="cqmgroup" name="cqmgroup" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['cqmgroup'])) echo $_POST['cqmgroup'];  ?>" onchange="upperCase(this.id)"></td>
				<td valign="top"><input id="cqmdescription" name="cqmdescription" type="text" size="32" maxlength="32" value="<?php if(isset($_POST['cqmdescription'])) echo $_POST['cqmdescription'];  ?>" ></td>
				<td valign="top"><textarea id="cqmsql" name="cqmsql" rows="5" cols="80"><?php if(isset($_POST['cqmsql'])) echo $_POST['cqmsql'];  ?></textarea></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="6"><div>
						<div style="float:left;">
							<input name="button[]" type="submit" value="Search" />
						</div>
						<div style="float:right;">
							<input name="button[]" type="submit" value="Add" />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
