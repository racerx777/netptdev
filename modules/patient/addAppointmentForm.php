		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th colspan="3">Appointment</th>
			</tr>
			<tr>
				<th>Date</th>
				<th>Clinic</th>
				<th>Status</th>
			</tr>
			<tr>
				<td><input id="apdate" name="apdate" type="text" size="8" maxlength="10" value="<?php if(isset($_POST['apdate'])) echo $_POST['apdate'];  ?>" /></td>
				<td><select name="apcnum" size="1">
						<option label=""></option>
						<?php
foreach($_SESSION['clinics'] as $key=>$val)
	echo "<option " . $selected_clinic[$key] . " value='" . $key . "'>" . $_SESSION['clinics'][$key] . "</option>"; 
?>
					</select></td>
				<td><select name="apstatuscode" size="1">
						<option label=""></option>
						<?php
foreach($_SESSION['apstatuscodes'] as $key=>$val)
	echo "<option " . $selected_amapptstatuscod[$key] . " value='" . $key . "'>" . $_SESSION['apstatuscodes'][$key] . "</option>"; 
?>
					</select></td>
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