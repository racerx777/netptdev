<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
$searchapptvars = array(
	"cmcnum"=>array(
		"title"=>"Clinic Name", 
		"type"=>"text", 
		"dbformat"=>"code", 
		"dblength"=>"3", 
		"displayformat"=>"name", 
		"displaylength"=>"30", 
		"test"=>"EQUAL"), 
	"crapptdate"=>array(
		"title"=>"From Appt Date", 
		"type"=>"text", 
		"dbformat"=>"datetime", 
		"dblength"=>"8", 
		"displayformat"=>"date", 
		"displaylength"=>"10", 
		"test"=>"RANGE")
);

if(!empty($_POST['buttonClearSearchAppt'])) 
	clearformvars('case', 'searchappt');

// If Search then save search values
$disableclear = 'disabled="disabled"';
if(!empty($_POST['buttonSetSearchAppt'])) {
	setformvars('case', 'searchappt', $_POST['searchappt']);
	$default = getformvars('case', 'searchappt');
	foreach($default as $field=>$value) {
		if(!empty($value)) {
			unset($disableadd);
			unset($disableclear);
		}
	}
}

// In any case retrieve search values
$default = getformvars('case', 'searchappt');
// If any field is populated then enable the Add button
?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Search Appointment Information</legend>
	<form method="post" name="searchapptForm" onsubmit="return formValidator()">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">	
			<tr>
				<th>Clinic</th>
				<th>Appt Date</th>
			</tr>
			<tr>
				<td><input id="cmcnum" name="searchappt[cmcnum]" type="text" size="3" maxlength="2" value="<?php if(isset($default['cmcnum'])) echo $default['cmcnum'];  ?>"></td>
				<td><input id="crapptdate" name="searchappt[crapptdate]" type="text" size="11" maxlength="10" value="<?php if(isset($default['crapptdate'])) echo $default['crapptdate'];  ?>"></td>
			</tr>
			<tr>
				<td colspan="5"><div>
						<div style="float:left;">
							<input name="buttonSetSearchAppt" type="submit" value="Search"  />
						</div>
						<div style="float:left;">
							<input id="ClearButton" name="buttonClearSearchAppt" type="submit" value="Clear" <?php if(isset($disableclear)) echo $disableclear ?> />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
