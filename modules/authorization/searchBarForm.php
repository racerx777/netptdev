<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
?>
<script language="javascript">
function formValidator(){
	var casestatuscode = document.getElementById('crcasestatuscode');
	var statuscode = document.getElementById('cpstatuscode');
	var authstatuscode = document.getElementById('cpauthstatuscode');
	var lname = document.getElementById('palname');
	var fname = document.getElementById('pafname');
	var ssn = document.getElementById('passn');
	var dob = document.getElementById('padob');
	var injurydate = document.getElementById('crinjurydate');
	var cpdate = document.getElementById('cpdate');
	var apptdate = document.getElementById('crapptdate');
	var phone1 = document.getElementById('paphone1');
	if(notEmpty(phone1) || notEmpty(apptdate) || notEmpty(cpdate) || notEmpty(injurydate) || notEmpty(dob) || notEmpty(ssn) || notEmpty(fname) || notEmpty(lname) || notEmpty(authstatuscode) || notEmpty(statuscode) || notEmpty(casestatuscode) ) {
		document.addForm.ClearButton.disabled = false;
		return true;
	}
	document.addForm.ClearButton.disabled = true;
	return false;
}

function notEmpty(elem){
	if(elem==null) {
		return false;
	}
	if(elem.value.length == 0) {
		elem.focus();
		return false;
	}
	return true;
}

function isNumeric(elem){
	var numericExpression = /^[0-9]+$/;
	if(elem.value.match(numericExpression)){
		return true;
	}else{
		elem.focus();
		return false;
	}
}

function isAlphabet(elem){
	var alphaExp = /^[a-zA-Z]+$/;
	if(elem.value.match(alphaExp)){
		return true;
	}else{
		elem.focus();
		return false;
	}
}

function isAlphanumeric(elem){
	var alphaExp = /^[0-9a-zA-Z]+$/;
	if(elem.value.match(alphaExp)){
		return true;
	}else{
		elem.focus();
		return false;
	}
}

function lname(id) {
	upperCase(id);
	formValidator();
}

function fname(id) {
	upperCase(id);
	formValidator();
}

function dob(id) {
	if(validateDate(id)) {
// formatDate(id);
	}
	else {
		document.addForm.padob.value="";
	}
	formValidator();
}

function phone1(id) {
//	if(phoneFormat(id)) {
//	}
//	else {
//		document.addForm.paphone1.value="";
//	}
	formValidator();
}

function ssn(id) {
//	ssnFormat(id);
	formValidator();
}
</script>
<?php
$searchvars = array(
	"crcasestatuscode"=>array(
				"title"=>"Case Status",
				"type"=>"text",
				"dbformat"=>"varchar",
				"dblength"=>"3",
				"displayformat"=>"text",
				"displaylength"=>"3",
				"test"=>"EQUAL"),
	"palname"=>array(
				"title"=>"Last Name",
				"type"=>"text",
				"dbformat"=>"varchar",
				"dblength"=>"30",
				"displayformat"=>"name",
				"displaylength"=>"30",
				"test"=>"LIKE"),
	"pafname"=>array(
				"title"=>"First Name",
				"type"=>"text",
				"dbformat"=>"varchar",
				"dblength"=>"30",
				"displayformat"=>"name",
				"displaylength"=>"30",
				"test"=>"LIKE"),
	"padob"=>array(
				"title"=>"Birth Date",
				"type"=>"text",
				"dbformat"=>"date",
				"dblength"=>"8",
				"displayformat"=>"date",
				"displaylength"=>"10",
				"test"=>"EQUAL"),
	"paphone1"=>array(
				"title"=>"Phone Number",
				"type"=>"text",
				"dbformat"=>"phone",
				"dblength"=>"18",
				"displayformat"=>"phone",
				"displaylength"=>"22",
				"test"=>"EQUAL"),
	"passn"=>array(
				"title"=>"Social Security Number",
				"type"=>"text",
				"dbformat"=>"ssn",
				"dblength"=>"9",
				"displayformat"=>"ssn",
				"displaylength"=>"11",
				"test"=>"EQUAL"),
	"crinjurydate"=>array(
				"title"=>"Injury Date",
				"type"=>"text",
				"dbformat"=>"date",
				"dblength"=>"8",
				"displayformat"=>"date",
				"displaylength"=>"10",
				"test"=>"EQUAL"),
	"cpdate"=>array(
				"title"=>"Rx Date",
				"type"=>"text",
				"dbformat"=>"date",
				"dblength"=>"8",
				"displayformat"=>"date",
				"displaylength"=>"10",
				"test"=>"LIKE"),
	"crapptdate"=>array(
				"title"=>"Appt Date",
				"type"=>"text",
				"dbformat"=>"date",
				"dblength"=>"8",
				"displayformat"=>"date",
				"displaylength"=>"10",
				"test"=>"LIKE"),
	"crptosstatus"=>array(
				"title"=>"PTOS Status Code",
				"type"=>"text",
				"dbformat"=>"varchar",
				"dblength"=>"3",
				"displayformat"=>"text",
				"displaylength"=>"3",
				"test"=>"EQUAL")
);

if(!empty($_POST['buttonClearSearch']))
	clearformvars('authorization', 'search');

// If Search then save search values
$disableclear = 'disabled="disabled"';
if(!empty($_POST['buttonSetSearch'])) {
	$dbvalues = valuestodb($_POST['search'], $searchvars);
	setformvars('authorization', 'search', $dbvalues);
}
// In any case retrieve search values
$default = getformvars('authorization', 'search');
$default = valuestodisplay($default, $searchvars);
// If any field is populated then enable the Add button
foreach($default as $field=>$value) {
	if(!empty($value)) {
//		dump("$field:",$value);
		unset($disableclear);
	}
}

// In any case retrieve search values
//$default = getformvars('authorization', 'search');
// If any field is populated then enable the Add button
?>

<div class="containedBox" id="addBarForm">
	<fieldset>
	<legend style="font-size:large">Search Authorization Information</legend>
	<form method="post" name="addForm" onsubmit="return formValidator()">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Case Status</th>
				<th>Rx Status</th>
				<th>Auth Status</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>SSN</th>
				<th>DOB</th>
				<th>DOI</th>
				<th>Rx Date</th>
				<th>Appt Date</th>
				<th>Phone</th>
			</tr>
			<tr>
				<td><select name="search[crcasestatuscode]" id="crcasestatuscode">
						<?php echo getSelectOptions($arrayofarrayitems = caseStatusCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['crcasestatuscode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
					</select></td>
				<td><select name="search[cpstatuscode]" id="cpstatuscode">
						<?php echo getSelectOptions($arrayofarrayitems = casePrescriptionStatusCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['cpstatuscode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
					</select></td>
				<td><select name="search[cpauthstatuscode]" id="cpauthstatuscode">
						<?php echo getSelectOptions($arrayofarrayitems = casePrescriptionAuthorizationStatusCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['cpauthstatuscode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
					</select></td>
				<td><input id="palname" name="search[palname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['palname'])) echo $default['palname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="pafname" name="search[pafname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['pafname'])) echo $default['pafname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="passn" name="search[passn]" type="text" size="11" maxlength="11" value="<?php if(isset($default['passn'])) echo displaySsn($default['passn']);  ?>" onchange="displayssn(this.id)" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="padob" name="search[padob]" type="text" size="10" maxlength="10" value="<?php if(isset($default['padob'])) echo $default['padob']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.addForm.padob,'anchor1','MM/dd/yyyy'); return false;" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crinjurydate" name="search[crinjurydate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['crinjurydate'])) echo $default['crinjurydate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.addForm.crinjurydate,'anchor2','MM/dd/yyyy'); return false;" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="cpdate" name="search[cpdate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['cpdate'])) echo $default['cpdate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor3" id="anchor3" src="/img/calendar.gif" onclick="cal.select(document.addForm.cpdate,'anchor3','MM/dd/yyyy'); return false;" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crapptdate" name="search[crapptdate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['crapptdate'])) echo $default['crapptdate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor4" id="anchor4" src="/img/calendar.gif" onclick="cal.select(document.addForm.crapptdate,'anchor4','MM/dd/yyyy'); return false;" /></td>
				<td>
                    <input id="paphone1" name="search[paphone1]" type="text" size="11" maxlength="14" value="<?php if(isset($default['paphone1'])) echo displayPhone($default['paphone1']);  ?>" onchange="displayphone(this.id)" />
                </td>
			</tr>
			<tr>
				<td colspan="11"><div>
						<div style="float:left;">
							<input name="buttonSetSearch" type="submit" value="Search" />
						</div>
						<div style="float:left;">
							<input id="ClearButton" name="buttonClearSearch" type="submit" value="Clear" <?php if(isset($disableclear)) echo $disableclear ?> />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
