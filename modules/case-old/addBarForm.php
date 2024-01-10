<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
?>
<script language="javascript">
function formValidator(){
	var id = document.getElementById('paid');
	var last = document.getElementById('palname');
	var first = document.getElementById('pafname');
	var dob = document.getElementById('padob');
	var phone = document.getElementById('paphone1');
	var ssn = document.getElementById('passn');
	if(notEmpty(status) || notEmpty(clinic) || notEmpty(doi) || notEmpty(ssn) || notEmpty(phone) || notEmpty(dob) || notEmpty(first) || notEmpty(last) || notEmpty(id) ) {
//		document.addForm.AddButton.disabled = false;
		document.addForm.ClearButton.disabled = false;
		return true;
	}
//	document.addForm.AddButton.disabled = true;
	document.addForm.ClearButton.disabled = true;
	return false;
}

function notEmpty(elem){
	if(elem != null) {
		if(elem.value.length == 0){
			elem.focus();
			return false;
		}
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
$searchcasevars = array(
	"paid"=>array(
				"title"=>"Id",
				"type"=>"text",
				"dbformat"=>"int",
				"dblength"=>"11",
				"displayformat"=>"numeric",
				"displaylength"=>"11",
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
				"test"=>"LIKELIKE"),
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
	"crcnum"=>array(
				"title"=>"Clinic Number",
				"type"=>"text",
				"dbformat"=>"char",
				"dblength"=>"3",
				"displayformat"=>"text",
				"displaylength"=>"3",
				"test"=>"EQUAL"),
	"crcasestatuscode"=>array(
				"title"=>"Case Status",
				"type"=>"text",
				"dbformat"=>"varchar",
				"dblength"=>"3",
				"displayformat"=>"text",
				"displaylength"=>"3",
				"test"=>"EQUAL"),
	"crrefdmid"=>array(
				"title"=>"Referring Doctor",
				"type"=>"text",
				"dbformat"=>"varchar",
				"dblength"=>"11",
				"displayformat"=>"text",
				"displaylength"=>"11",
				"test"=>"EQUAL"),
	"crdate"=>array(
				"title"=>"Case Status",
				"type"=>"text",
				"dbformat"=>"date",
				"dblength"=>"8",
				"displayformat"=>"date",
				"displaylength"=>"10",
				"test"=>"EQUAL")
);

if(!empty($_POST['buttonClearSearchCase']))
	clearformvars('case', 'searchcase');

// If Search then save search values
if(!empty($_POST['buttonSetSearchCase'])) {
	$dbvalues = valuestodb($_POST['searchcase'], $searchcasevars);
	setformvars('case', 'searchcase', $dbvalues);
}
// In any case retrieve search values
$default = getformvars('case', 'searchcase');
$default = valuestodisplay($default, $searchcasevars);
// If any field is populated then enable the Add button
$disableadd = 'disabled="disabled"';
if(count($default) == 0)
	$disableclear = 'disabled="disabled"';
else {
	unset($disableclear);
	if(isset($default['paid']))
		unset($disableadd);
}

// In any case retrieve search values
//$default = getformvars('case', 'searchcase');
// If any field is populated then enable the Add button
?>
<div class="containedBox" id="addBarForm">
	<fieldset>
	<legend style="font-size:large">Search Case Information</legend>
	<form method="post" name="addForm" onsubmit="return formValidator()">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Patient Id</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>DOB</th>
				<th>Phone</th>
				<th>SSN</th>
				<th>DOI</th>
                <?php if (userlevel() != 15): ?>
				<th>Clinic</th>
				<th>Status</th>
				<th>Doctor</th>
				<th>Refer Date</th>
                <?php endif; ?>
			</tr>
			<tr>
				<td><?php echo $default['paid']; ?>
					<input id="paid" name="searchcase[paid]" type="hidden" value="<?php if(isset($default['paid'])) echo $default['paid'];  ?>"></td>
				<td><input id="palname" name="searchcase[palname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['palname'])) echo $default['palname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="pafname" name="searchcase[pafname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['pafname'])) echo $default['pafname'];  ?>" onchange="upperCase(this.id)"></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="padob" name="searchcase[padob]" type="text" size="10" maxlength="10" value="<?php if(isset($default['padob'])) echo $default['padob']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.addForm.padob,'anchor1','MM/dd/yyyy'); return false;" /></td>
				<td><input id="paphone1" name="searchcase[paphone1]" type="text" size="11" maxlength="14" value="<?php if(isset($default['paphone1'])) echo displayPhone($default['paphone1']);  ?>" onchange="displayphone(this.id)" /></td>
				<td><input id="passn" name="searchcase[passn]" type="text" size="11" maxlength="11" value="<?php if(isset($default['passn'])) echo displaySsn($default['passn']);  ?>" onchange="displayssn(this.id)" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crinjurydate" name="searchcase[crinjurydate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['crinjurydate'])) echo $default['crinjurydate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.addForm.crinjurydate,'anchor2','MM/dd/yyyy'); return false;" /></td>

                <?php if (userlevel() != 15): ?>
                <td><select name="searchcase[crcnum]" id="crcnum">
					<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$default['crcnum'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array()); ?>
				</select></td>
				<td><select name="searchcase[crcasestatuscode]" id="crcasestatuscode">
					<?php echo getSelectOptions($arrayofarrayitems = caseStatusCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['crcasestatuscode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
				<td><select name="searchcase[crrefdmid]" id="crrefdmid">
					<?php
					require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
					echo getSelectOptions($arrayofarrayitems = getDoctorList($default['crrefdmid'], $includeinactive=0), $optionvaluefield='dmid', $arrayofoptionfields=array('dmlname'=>', ', 'dmfname'=>' (', 'dmid'=>')'), $defaultoption=$default['crrefdmid'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crdate" name="searchcase[crdate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['crdate'])) echo $default['crdate']; ?>"  onchange="validateDate(this.id)">
                    <img  align="absmiddle" name="anchor3" id="anchor3" src="/img/calendar.gif" onclick="cal.select(document.addForm.crdate,'anchor3','MM/dd/yyyy'); return false;" /></td>
                <?php endif; ?>

            </tr>
			<tr>
				<td colspan="11"><div>
						<div style="float:left;">
							<input name="buttonSetSearchCase" type="submit" value="Search" />
						</div>
						<div style="float:left;">
							<input id="ClearButton" name="buttonClearSearchCase" type="submit" value="Clear" <?php if(isset($disableclear)) echo $disableclear ?> />
						</div>
						<div style="float:right;">
							<input name="button[]" type="submit" value="Add" <?php echo $disableadd ?>/>
						</div>
					</div></td>
			</tr>
		</table>
		<input id="crid" name="search[crid]" type="hidden" size="11" maxlength="11" value="<?php if(isset($_POST['crid'])) echo $_POST['crid'];  ?>">
	</form>
	</fieldset>
</div>
