<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10); 
?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Add/Search Patient Information</legend>
	<form method="post" name="addForm">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th colspan="6">Case/Referral</th>
			</tr>
			<tr>
				<th>Referral Date</th>
				<th>Injury Date</th>
				<th>Dr.</th>
				<th>Dx</th>
				<th>Type</th>
			</tr>
			<tr>
				<td><input id="crdate" name="crdate" type="text" size="8" maxlength="10" value="<?php if(isset($_POST['crdate'])) echo $_POST['crdate'];  ?>"></td>
				<td><input id="crinjurydate" name="crinjurydate" type="text" size="8" maxlength="10" value="<?php if(isset($_POST['crinjurydate'])) echo $_POST['crinjurydate'];  ?>"></td>
				<td><select name="crdmid" size="1">
						<option label=""></option>
						<?php
foreach($_SESSION['doctors'] as $key=>$val)
	echo "<option " . $selected_doctor[$key] . " value='" . $key . "'>" . $_SESSION['doctors'][$key] . "</option>"; 
?>
					</select></td>
				<td><select name="crdx" size="1">
						<option label=""></option>
						<?php
foreach($_SESSION['dxtypes'] as $key=>$val)
	echo "<option " . $selected_dxtype[$key] . " value='" . $key . "'>" . $_SESSION['dxtypes'][$key] . "</option>"; 
?>
					</select></td>
				<td><select name="crcasetype" size="1">
						<option label=""></option>
						<?php
foreach($_SESSION['casetypes'] as $key=>$val)
	echo "<option " . $selected_casetype[$key] . " value='" . $key . "'>" . $_SESSION['casetypes'][$key] . "</option>"; 
?>
					</select></td>
			</tr>
		</table>
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th colspan="5">Patient</th>
			</tr>
			<tr>
				<th>Last Name</th>
				<th>First Name</th>
				<th>SSN</th>
				<th>DOB</th>
				<th>Phone</th>
			</tr>
			<tr>
				<td><input id="palast" name="palast" type="text" size="10" maxlength="30" value="<?php if(isset($_POST['palast'])) echo $_POST['palast'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="pafirst" name="pafirst" type="text" size="10" maxlength="30" value="<?php if(isset($_POST['pafirst'])) echo $_POST['pafirst'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="passn" name="passn" type="text" size="8" maxlength="9" value="<?php if(isset($_POST['passn'])) echo $_POST['passn'];  ?>"></td>
				<td><input id="padob" name="padob" type="text" size="8" maxlength="10" value="<?php if(isset($_POST['padob'])) echo $_POST['padob'];  ?>"></td>
				<td><input id="paphone" name="paphone" type="text" size="10" maxlength="20" value="<?php if(isset($_POST['paphone'])) echo $_POST['paphone'];  ?>" onchange="phoneFormat(this.id)"></td>
			</tr>
			<tr>
				<td colspan="5"><div>
						<div style="float:left;">
							<input name="button[]" type="submit" value="Search" />
						</div>
						<div style="float:right;">
							<input name="button[]" id="button[]" type="submit" value="Add" onclick="return checkAddBarForm();" />
						</div>
					</div></td>
			</tr>
		</table>
		<input id="paid" name="paid" type="hidden" size="5" maxlength="5" value="<?php if(isset($_POST['paid'])) echo $_POST['paid'];  ?>">
	</form>
	</fieldset>
</div>
