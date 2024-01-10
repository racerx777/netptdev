<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
?>

<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="authorizedForm">
		<input type="hidden" name="formLoaded" value="1" />
		<fieldset style="text-align:center;">
		<legend>Received Authorization - <?php echo "Prescription:".$_POST['cpid']." Case:".$_POST['cpcrid']; ?>
			<input type="hidden" name="cpid" value="<?php if(isset($_POST['cpid'])) echo $_POST['cpid']; ?>" />
			<input type="hidden" name="cpcrid" value="<?php if(isset($_POST['cpcrid'])) echo $_POST['cpcrid']; ?>" />
		</legend>
		<table style="text-align:left;">
			<tr>
				<th colspan="2">Prescription Authorized</th>
			</tr>
			<tr>
				<td>Authorized By</td>
				<td><input id="cpauthperson" name="cpauthperson" type="input" size="30" maxlength="64" value="<?php if(isset($_POST['cpauthperson'])) echo $_POST['cpauthperson']; ?>" onchange="upperCase(this.id)" /></td>
			</tr>
			<tr>
				<td>Authorized Date</td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="cpauthdate" name="cpauthdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['cpauthdate'])) echo $_POST['cpauthdate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="cpauthdate_cal" id="cpauthdate_cal" src="/img/calendar.gif" onclick="cal.select(document.authorizedForm.cpauthdate,'cpauthdate_cal','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<td>Authorized Frequency/Duration/Visits</td>
				<td>Frequency
					<select name="cpauthfrequency" id="cpauthfrequency">
						<option value=""<?php if(empty($_POST['cpauthfrequency'])) echo ' selected="selected"'; ?>></option>
						<option value="1"<?php if($_POST['cpauthfrequency']=='1') echo ' selected="selected"'; ?>>1</option>
						<option value="2"<?php if($_POST['cpauthfrequency']=='2') echo ' selected="selected"'; ?>>2</option>
						<option value="3"<?php if($_POST['cpauthfrequency']=='3') echo ' selected="selected"'; ?>>3</option>
						<option value="4"<?php if($_POST['cpauthfrequency']=='4') echo ' selected="selected"'; ?>>4</option>
						<option value="5"<?php if($_POST['cpauthfrequency']=='5') echo ' selected="selected"'; ?>>5</option>
						<option value="6"<?php if($_POST['cpauthfrequency']=='6') echo ' selected="selected"'; ?>>6</option>
						<option value="7"<?php if($_POST['cpauthfrequency']=='7') echo ' selected="selected"'; ?>>7</option>
					</select>
					X
					Duration
					<select name="cpauthduration" id="cpauthduration">
						<option  value=""<?php if(empty($_POST['cpauthduration'])) echo ' selected="selected"'; ?>></option>
						<option  value="1"<?php if($_POST['cpauthduration']== '1') echo ' selected="selected"';  ?>>1</option>
						<option  value="2"<?php if($_POST['cpauthduration']== '2') echo ' selected="selected"';  ?>>2</option>
						<option  value="3"<?php if($_POST['cpauthduration']== '3') echo ' selected="selected"';  ?>>3</option>
						<option  value="4"<?php if($_POST['cpauthduration']== '4') echo ' selected="selected"';  ?>>4</option>
						<option  value="5"<?php if($_POST['cpauthduration']== '5') echo ' selected="selected"';  ?>>5</option>
						<option  value="6"<?php if($_POST['cpauthduration']== '6') echo ' selected="selected"';  ?>>6</option>
						<option  value="7"<?php if($_POST['cpauthduration']== '7') echo ' selected="selected"';  ?>>7</option>
						<option  value="8"<?php if($_POST['cpauthduration']== '8') echo ' selected="selected"';  ?>>8</option>
						<option  value="9"<?php if($_POST['cpauthduration']== '9') echo ' selected="selected"';  ?>>9</option>
						<option value="10"<?php if($_POST['cpauthduration']=='10') echo ' selected="selected"'; ?>>10</option>
						<option value="11"<?php if($_POST['cpauthduration']=='11') echo ' selected="selected"'; ?>>11</option>
						<option value="12"<?php if($_POST['cpauthduration']=='12') echo ' selected="selected"'; ?>>12</option>
					</select>
					= 
					Visits
					<input name="cpauthtotalvisits" type="text" size="5" maxlength="5" value="<?php if(isset($_POST['cpauthtotalvisits'])) echo $_POST['cpauthtotalvisits'];?>" />
				</td>
			</tr>
			<tr>
				<td>Authorization Note</td>
				<td><input id="cpauthnote" name="cpauthnote" type="text" size="64" maxlength="64" value="<?php if(isset($_POST['cpauthnote'])) echo $_POST['cpauthnote'];?>" onchange="uppercase(this.id)" /></td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Back" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_POST['cpid']; ?>]" type="submit" value="Confirm Authorized" />
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
