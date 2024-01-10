<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
?>
<script language="javascript">

function formValidator(){
	var lname = document.getElementById('palname');
	var fname = document.getElementById('pafname');
	var dob = document.getElementById('padob');
	var phone = document.getElementById('paphone1');
	var ssn = document.getElementById('passn');
	var injurydate = document.getElementById('crinjurydate');
	var cnum = document.getElementById('pacnum');
	var apptdate = document.getElementById('crapptdate');
	if(notEmpty(apptdate) || notEmpty(cnum) || notEmpty(injurydate) || notEmpty(ssn) || notEmpty(phone) || notEmpty(dob) || notEmpty(fname) || notEmpty(lname) ) {
		document.addForm.ClearButton.disabled = false;
		return true;
	}
	document.addForm.ClearButton.disabled = true;
	return false;
}

function notEmpty(elem){
	if(elem.value.length == 0){
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
	"cricclaimnumber1"=>array(
				"title"=>"Primary Claim Number", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"30", 
				"displayformat"=>"text", 
				"displaylength"=>"30", 
				"test"=>"EQUAL"),
	"cricclaimnumber2"=>array(
				"title"=>"Secondary Claim Number", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"30", 
				"displayformat"=>"text", 
				"displaylength"=>"30", 
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
	"crpnum"=>array(
				"title"=>"Patient Number", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"6", 
				"displayformat"=>"name", 
				"displaylength"=>"6", 
				"test"=>"EQUAL"),  
	"cpcnum"=>array(
				"title"=>"Clinic", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"2", 
				"displayformat"=>"name", 
				"displaylength"=>"2", 
				"test"=>"EQUAL"),  
	"crapptdate"=>array(
				"title"=>"Appointment Date", 
				"type"=>"text", 
				"dbformat"=>"date", 
				"dblength"=>"8", 
				"displayformat"=>"date", 
				"displaylength"=>"10", 
				"test"=>"EQUAL"),  
	"padob"=>array(
				"title"=>"Birth Date", 
				"type"=>"text", 
				"dbformat"=>"date", 
				"dblength"=>"8", 
				"displayformat"=>"date", 
				"displaylength"=>"10", 
				"test"=>"EQUAL"),  
	"criclid1NULL"=>array(
				"title"=>"No Insurance", 
				"type"=>"boolean", 
				"dbformat"=>"boolean", 
				"dblength"=>"1", 
				"displayformat"=>"checked", 
				"displaylength"=>"1", 
				"test"=>"IS NULL"),  
	"passn"=>array(
				"title"=>"Social Security Number", 
				"type"=>"text", 
				"dbformat"=>"ssn", 
				"dblength"=>"9", 
				"displayformat"=>"ssn", 
				"displaylength"=>"11", 
				"test"=>"EQUAL"),
	"cpdate"=>array(
				"title"=>"Prescription Date", 
				"type"=>"text", 
				"dbformat"=>"date", 
				"dblength"=>"8", 
				"displayformat"=>"date", 
				"displaylength"=>"8", 
				"test"=>"EQUAL"),
	"crpostsurgical"=>array(
				"title"=>"Post Surgical", 
				"type"=>"text", 
				"dbformat"=>"code", 
				"dblength"=>"3", 
				"displayformat"=>"code", 
				"displaylength"=>"3", 
				"test"=>"EQUAL"),
	"crsurgerydate"=>array(
				"title"=>"Surgery Date", 
				"type"=>"text", 
				"dbformat"=>"date", 
				"dblength"=>"8", 
				"displayformat"=>"date", 
				"displaylength"=>"8", 
				"test"=>"EQUAL"),
	"cpstatuscode"=>array(
				"title"=>"Prescription Status Code", 
				"type"=>"text", 
				"dbformat"=>"code", 
				"dblength"=>"3", 
				"displayformat"=>"code", 
				"displaylength"=>"3", 
				"test"=>"EQUAL"),
	"cpauthstatuscode"=>array(
				"title"=>"Authorization Status Code", 
				"type"=>"text", 
				"dbformat"=>"code", 
				"dblength"=>"3", 
				"displayformat"=>"code", 
				"displaylength"=>"3", 
				"test"=>"EQUAL"),
	"cpltrstatuscode"=>array(
				"title"=>"RFA Letter Status", 
				"type"=>"text", 
				"dbformat"=>"code", 
				"dblength"=>"3", 
				"displayformat"=>"code", 
				"displaylength"=>"3", 
				"test"=>"EQUAL"),
	"cprfastatuscode"=>array(
				"title"=>"RFA Status Code", 
				"type"=>"text", 
				"dbformat"=>"code", 
				"dblength"=>"3", 
				"displayformat"=>"code", 
				"displaylength"=>"3", 
				"test"=>"EQUAL"),
	"cpdocstatuscode"=>array(
				"title"=>"Doc Request Status", 
				"type"=>"text", 
				"dbformat"=>"code", 
				"dblength"=>"3", 
				"displayformat"=>"code", 
				"displaylength"=>"3", 
				"test"=>"EQUAL")
);

//dump("buttonClearSearch",$_POST['buttonClearSearch']);
if(!empty($_POST['buttonClearSearch'])) {
	clearformvars('authprocessing', 'search');
	unset($_POST['search']);
}
// If Search then save search values
$disableclear = 'disabled="disabled"';
//dumppost();
if(!empty($_POST['buttonSetSearch'])) {
	$dbvalues = valuestodb($_POST['search'], $searchvars);
	setformvars('authprocessing', 'search', $dbvalues);
}
if(!empty($_POST['test'])) {
	$dbvalues = valuestodb($_POST['search'], $searchvars);
	setformvars('authprocessing', 'search', $dbvalues);
}
// In any case retrieve search values
	$default = getformvars('authprocessing', 'search');
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
unset($_SESSION['button']); // will default to last button search if disabled will clear search

//dump('criclid1NULL',$_POST['criclid1NULL']);
if($_POST['criclid1NULL']=='1') {
	$criclid1NULL=$_POST['criclid1NULL'];
	$sortSaved['cpauthstatuscode'] = $searchvars['cpauthstatuscode'];
	$sortSaved['cpdate'] = $searchvars['cpdate'];
	$sortSaved['cpauthstatuscode']['collation'] = 'desc';
	$sortSaved['cpdate']['collation'] = 'desc';
	setformvars('authprocessing', 'searchResults', $sortSaved);
}
?>
<div class="containedBox" id="searchBarForm">
	<fieldset>
	<legend style="font-size:large">Authorization Processing Search	</legend>
	<form method="post" id="searchForm" name="searchForm" onsubmit="return formValidator()">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Rx Date</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>SSN</th>
				<th>DOB</th>
				<th>No Ins</th>
				<th>PriClm</th>
				<th>SecClm</th>
				<th>Rx Sts</th>
				<th>Auth Sts</th>
				<th>RFA Sts</th>
				<th>Doc Sts</th>
			</tr>
			<tr>
				<td nowrap="nowrap" style="text-decoration:none"><input id="cpdate" name="search[cpdate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['cpdate'])) echo $default['cpdate']; ?>"  onchange="validateDate(this.id)"><img  align="absmiddle" name="cpdate_cal" id="cpdate_cal" src="/img/calendar.gif" onclick="cal.select(document.searchForm.cpdate,'cpdate_cal','MM/dd/yyyy'); return false;" /></td>
				<td><input id="palname" name="search[palname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['palname'])) echo $default['palname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="pafname" name="search[pafname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['pafname'])) echo $default['pafname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="passn" name="search[passn]" type="text" size="11" maxlength="11" value="<?php if(isset($default['passn'])) echo displaySsn($default['passn']);  ?>" onchange="displayssn(this.id)" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="padob" name="search[padob]" type="text" size="10" maxlength="10" value="<?php if(isset($default['padob'])) echo $default['padob']; ?>"  onchange="validateDate(this.id)">
				<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.searchForm.padob,'anchor1','MM/dd/yyyy'); return false;" /></td>
<td><input id="criclid1NULL" name="criclid1NULL" type="checkbox" value="1" <?php if(!empty($criclid1NULL)) echo "checked"; ?> /></td>
				<td><input id="cricclaimnumber1" name="search[cricclaimnumber1]" type="text" size="15" maxlength="30" value="<?php if(isset($default['cricclaimnumber1'])) echo $default['cricclaimnumber1'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="cricclaimnumber2" name="search[cricclaimnumber2]" type="text" size="15" maxlength="30" value="<?php if(isset($default['cricclaimnumber2'])) echo $default['cricclaimnumber2'];  ?>" onchange="upperCase(this.id)"></td>

				<td><select name="search[cpstatuscode]" id="cpstatuscode">
						<?php echo getSelectOptions($arrayofarrayitems = casePrescriptionStatusCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['cpstatuscode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
				<td><select name="search[cpauthstatuscode]" id="cpauthstatuscode">
						<?php echo getSelectOptions($arrayofarrayitems = casePrescriptionAuthorizationStatusCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['cpauthstatuscode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
				<td><select name="search[cprfastatuscode]" id="cprfastatuscode">
						<?php echo getSelectOptions($arrayofarrayitems = casePrescriptionRfaStatusCodes(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$default['cprfastatuscode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
				<td><select name="search[cpdocstatuscode]" id="cpdocstatuscode">
						<?php echo getSelectOptions($arrayofarrayitems = casePrescriptionDocStatusCodes(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$default['cpdocstatuscode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
			</tr>
			<tr>
				<td colspan="12"><div>
						<div style="float:left;">
							<input id="test" name="test" type="hidden" value="0" />
							<input id="buttonSetSearch" name="buttonSetSearch" type="submit" value="Search" />
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
<?php
//dump("search",$_POST['search']);
//dump("default",$default);
//exit();
?>