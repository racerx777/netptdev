<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
?>
<div class="containedBox">
<fieldset>
<legend style="font-size:large">Add/Search Business Unit Information</legend>
<form method="post" name="addForm">
	<table width="100%" border="1" cellspacing="0" cellpadding="3">
		<tr>
			<th>Business Unit</th>
			<th>Name</th>
			<th>Tax Id</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Fax</th>
		</tr>
		<tr>
			<td><input id="bumcode" name="bumcode" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['bumcode'])) echo $_POST['bumcode'];  ?>" ></td>
			<td><input id="bumname" name="bumname" type="text" size="20" maxlength="30" value="<?php if(isset($_POST['bumname'])) echo $_POST['bumname'];  ?>" onchange="properCase(this.id)"></td>
			<td><input id="bumtaxid" name="bumtaxid" type="text" size="20" maxlength="64" value="<?php if(isset($_POST['bumtaxid'])) echo $_POST['bumtaxid'];  ?>" ></td>
			<td><input id="bumemail" name="bumemail" type="text" size="20" maxlength="64" value="<?php if(isset($_POST['bumemail'])) echo $_POST['bumemail'];  ?>" ></td>
			<td><input id="bumphone" name="bumphone" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['bumphone'])) echo $_POST['bumphone'];  ?>" ></td>
			<td><input id="bumfax" name="bumfax" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['bumfax'])) echo $_POST['bumfax'];  ?>" ></td>
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