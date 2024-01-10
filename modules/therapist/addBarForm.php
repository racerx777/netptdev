<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
?>
<div class="containedBox">
<fieldset>
<legend style="font-size:large">Add/Search Therapist Information</legend>
<form method="post" name="addForm">
	<table width="100%" border="1" cellspacing="0" cellpadding="3">
		<tr>
			<th>Code</th>
			<th>Name</th>
			<th>License</th>
			<th>NPI</th>
			<th>Reference Number</th>
			<th>Note</th>
		</tr>
		<tr>
			<td><input id="ttherap" name="ttherap" type="text" size="2" maxlength="2" value="<?php if(isset($_POST['ttherap'])) echo $_POST['ttherap'];  ?>"  onchange="upperCase(this.id)"></td>
			<td><input id="tname" name="tname" type="text" size="21" maxlength="21" value="<?php if(isset($_POST['tname'])) echo $_POST['tname'];  ?>"  onchange="upperCase(this.id)"></td>
			<td><input id="tlic" name="tlic" type="text" size="20" maxlength="30" value="<?php if(isset($_POST['tlic'])) echo $_POST['tlic'];  ?>" ></td>
			<td><input id="tnpi" name="tnpi" type="text" size="17" maxlength="17" value="<?php if(isset($_POST['tnpi'])) echo $_POST['tnpi'];  ?>" ></td>
			<td><input id="trefnum" name="trefnum" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['trefnum'])) echo $_POST['trefnum'];  ?>" ></td>
			<td><input id="tnote" name="tnote" type="text" size="60" maxlength="60" value="<?php if(isset($_POST['tnote'])) echo $_POST['tnote'];  ?>" ></td>
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