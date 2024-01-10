<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
?>
<div class="containedBox">
<fieldset>
<legend style="font-size:large">Add/Search Provider Information</legend>
<form method="post" name="addForm">
	<table width="100%" border="1" cellspacing="0" cellpadding="3">
		<tr>
			<th>Business Unit</th>
			<th>Provider</th>
			<th>Name</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Fax</th>
		</tr>
		<tr>
			<td>
			<select name="pgmbumcode" id="pgmbumcode"><option value=""></option>
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['businessunits'], $optionvaluefield='bumcode', $arrayofoptionfields=array('bumname'=>' (', 'bumcode'=>')'), $defaultoption=$_POST['bumcode'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select></td>
			<td><input id="pgmcode" name="pgmcode" type="text" size="3" maxlength="3" value="<?php if(isset($_POST['pgmcode'])) echo $_POST['pgmcode'];  ?>"  onchange="upperCase(this.id)"></td>
			<td><input id="pgmname" name="pgmname" type="text" size="20" maxlength="30" value="<?php if(isset($_POST['pgmname'])) echo $_POST['pgmname'];  ?>" onchange="properCase(this.id)"></td>
			<td><input id="pgmemail" name="pgmemail" type="text" size="20" maxlength="64" value="<?php if(isset($_POST['pgmemail'])) echo $_POST['pgmemail'];  ?>" ></td>
			<td><input id="pgmphone" name="pgmphone" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['pgmphone'])) echo $_POST['pgmphone'];  ?>" ></td>
			<td><input id="pgmfax" name="pgmfax" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['pgmfax'])) echo $_POST['pgmfax'];  ?>" ></td>
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