<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
?>
<div class="containedBox">
<fieldset>
<legend style="font-size:large">Add/Search Clinic Information</legend>
<form method="post" name="addForm">
	<table width="100%" border="1" cellspacing="0" cellpadding="3">
		<tr>
			<th>Provider Group</th>
			<th>Clinic</th>
			<th>Name</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Fax</th>
		</tr>
		<tr>
			<td>
			<select name="cmpgmcode" id="cmpgmcode"><option value=""></option>
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['providergroups'], $optionvaluefield='pgmcode', $arrayofoptionfields=array('pgmname'=>' (', 'pgmcode'=>')'), $defaultoption=$_POST['cmpgmcode'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select></td>
			<td><input id="cmcnum" name="cmcnum" type="text" size="2" maxlength="2" value="<?php if(isset($_POST['cmcnum'])) echo $_POST['cmcnum'];  ?>"  onchange="upperCase(this.id)"></td>
			<td><input id="cmname" name="cmname" type="text" size="20" maxlength="30" value="<?php if(isset($_POST['cmname'])) echo $_POST['cmname'];  ?>" onchange="properCase(this.id)"></td>
			<td><input id="cmemail" name="cmemail" type="text" size="20" maxlength="64" value="<?php if(isset($_POST['cmemail'])) echo $_POST['cmemail'];  ?>" ></td>
			<td><input id="cmphone" name="cmphone" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['cmphone'])) echo $_POST['cmphone'];  ?>" ></td>
			<td><input id="cmfax" name="cmfax" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['cmfax'])) echo $_POST['cmfax'];  ?>" ></td>
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