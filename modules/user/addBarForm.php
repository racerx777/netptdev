<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 

$bumoptions=getSelectOptions($_SESSION['useraccess']['businessunits'], "bumcode", array("bumcode"=>"-", "bumname"=>""), $_POST['ucabumcode'], FALSE);

if(empty($_POST['ucabumcode'] ) || $_POST['ucabumcode'] == "*") unset($pgmfilter); else $pgmfilter = array($_POST['ucabumcode']);
$pgmoptions=getSelectOptions($_SESSION['useraccess']['providergroups'], "pgmcode", array("pgmcode"=>"-", "pgmname"=>""), $_POST['ucapgmcode'], FALSE, 'pgmbumcode', $pgmfilter);

if(empty($_POST['ucapgmcode'] ) || $_POST['ucapgmcode'] == "*") unset($cmfilter); else $cmfilter = array($_POST['ucapgmcode']);
$cmoptions = getSelectOptions($_SESSION['useraccess']['clinics'], "cmcnum", array("cmcnum"=>"-", "cmname"=>""), $_POST['ucacmcnum'], FALSE, 'cmpgmcode', $cmfilter);

$rolesoptions = getSelectOptions($_SESSION['site']['roles'], "rmid", array("rmname"=>""), $_POST['ucarole'], FALSE);
$homepagesoptions = getSelectOptions($_SESSION['site']['homepages'], "hpmid", array("hpmname"=>""), $_POST['ucahomepage'], FALSE);

?>

<div class="containedBox">
	<fieldset>
	<legend>Add User Information</legend>
	<form method="post" name="addBarForm">
		<input type="hidden" name="umrole" value='10'>
		<input type="hidden" name="umhomepage" value="treatment">
		<input type="hidden" name="umclinic" value="**">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>User</th>
				<th>Password</th>
				<th>Name</th>
				<th>Email</th>
			</tr>
			<tr>
				<td><input name="umuser" type="text" size="10" maxlength="16"></td>
				<td><input name="umpass" type="password" size="10" maxlength="32"></td>
				<td><input name="umname" type="text" size="10" maxlength="64"></td>
				<td><input name="umemail" type="text" size="10" maxlength="64" ></td>
			</tr>
			<tr>
				<td colspan="4"><div>
						<div style="float:left;">
							<input name="button[]" type="submit" value="Search" />
						</div>
						<div style="float:right;">
							<input name="button[]" id="button[]" type="submit" value="Add" onclick="return checkAddBarForm();" />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
