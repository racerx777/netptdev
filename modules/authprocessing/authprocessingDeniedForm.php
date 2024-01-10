<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
?>

<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="deniedForm">
		<input type="hidden" name="formLoaded" value="1" />
		<fieldset style="text-align:center;">
		<legend>Received Denial - prescription <?php echo $_POST['cpid']; ?><input type="hidden" name="cpid" value="<?php if(isset($_POST['cpid'])) echo $_POST['cpid']; ?>" /></legend>
		<table style="text-align:left;">
			<tr>
				<th colspan="2">Prescription Denied</th>
			</tr>
			<tr>
				<td>Denied By</td>
				<td><input id="cpdeniperson" name="cpdeniperson" type="input" size="30" maxlength="64" value="<?php if(isset($_POST['cpdeniperson'])) echo $_POST['cpdeniperson']; ?>" onchange="upperCase(this.id)" /></td>
			</tr>
			<tr>
				<td>Denied Date</td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="cpdenidate" name="cpdenidate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['cpdenidate'])) echo $_POST['cpdenidate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="cpdenidate_cal" id="cpdenidate_cal" src="/img/calendar.gif" onclick="cal.select(document.deniedForm.cpdenidate,'cpdenidate_cal','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<td>Denied Reason</td>
				<td><input id="cpdenireason" name="cpdenireason" type="text" size="6" maxlength="6" disabled="disabled" value="<?php if(isset($_POST['cpdenireason'])) echo $_POST['cpdenireason'];?>" onchange="upperCase(this.id)" /></td>
			</tr>
			<tr>
				<td>Denied Note</td>
				<td><input id="cpdeninote" name="cpdeninote" type="text" size="64" maxlength="64" value="<?php if(isset($_POST['cpdeninote'])) echo $_POST['cpdeninote'];?>" onchange="upperCase(this.id)" /></td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Back" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_POST['cpid']; ?>]" type="submit" value="Confirm Denied" />
						</div>
					</div></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php
exit;
?>
