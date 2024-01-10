<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(5); 
$thisapplication="Inquiry";
$thisform="searchBarForm";
$blankoption=FALSE;
?>
<script language="javascript">
function formValidator(){
	var last = document.getElementById('palname');
	var first = document.getElementById('pafname');
	var dob = document.getElementById('padob');
	var phone = document.getElementById('paphone1');
	var ssn = document.getElementById('passn');
	if( notEmpty(ssn) || notEmpty(phone) || notEmpty(dob) || notEmpty(first) || notEmpty(last) ) {
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
// Review the need for this stuff
$searchvars = array(
	"crcasestatuscode"=>array("title"=>"Status", "type"=>"text", "dbformat"=>"varchar", "dblength"=>"3", "displayformat"=>"code", "displaylength"=>"3", "test"=>"EQUAL"), 
	"crcnum"=>array("title"=>"Clinic", "type"=>"text", "dbformat"=>"varchar", "dblength"=>"3", "displayformat"=>"code", "displaylength"=>"3", "test"=>"EQUAL"), 
	"crinjurydate"=>array("title"=>"DOI", "type"=>"text", "dbformat"=>"date", "dblength"=>"8", "displayformat"=>"date", "displaylength"=>"10", "test"=>"EQUAL"), 
	"crpnum"=>array("title"=>"Patient Number", "type"=>"text", "dbformat"=>"varchar", "dblength"=>"6", "displayformat"=>"code", "displaylength"=>"6", "test"=>"LIKE"), 
	"paid"=>array("title"=>"Id", "type"=>"text", "dbformat"=>"int", "dblength"=>"11", "displayformat"=>"numeric", "displaylength"=>"11", "test"=>"EQUAL"), 
	"palname"=>array("title"=>"Last Name", "type"=>"text", "dbformat"=>"varchar", "dblength"=>"30", "displayformat"=>"name", "displaylength"=>"30", "test"=>"LIKE"), 
	"pafname"=>array("title"=>"First Name", "type"=>"text", "dbformat"=>"varchar", "dblength"=>"30", "displayformat"=>"name", "displaylength"=>"30", "test"=>"LIKE"),  
	"padob"=>array("title"=>"Birth Date", "type"=>"text", "dbformat"=>"date", "dblength"=>"8", "displayformat"=>"date", "displaylength"=>"10", "test"=>"EQUAL"),  
	"passn"=>array("title"=>"Social Security Number", "type"=>"text", "dbformat"=>"ssn", "dblength"=>"9", "displayformat"=>"ssn", "displaylength"=>"11", "test"=>"LIKELIKE"),
	"paphone1"=>array("title"=>"Phone Number", "type"=>"text", "dbformat"=>"phone", "dblength"=>"18", "displayformat"=>"phone", "displaylength"=>"22", "test"=>"EQUAL")
);
// End Review

// If clear search... clear saved values 
if(!empty($_POST['buttonClearSearch'])) 
	clearformvars($thisapplication, $thisform);
else {
// If Searching... save posted values to search values
	if(!empty($_POST['buttonSetSearch'])) {
		setformvars($thisapplication, $thisform, $_POST['search']);
	}
}
// In any case retrieve search values
$default = getformvars($thisapplication, $thisform);

// If no search values are set then disable the clear button
if(count($default) == 0) 
	$disableclear = 'disabled="disabled"';
else 
	unset($disableclear);
?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Search Patients</legend>
	<form method="post" name="searchBarForm" onsubmit="return formValidator()">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Patient Number</th>
				<th>SSN</th>
				<th>Phone</th>
				<th>DOB</th>
				<th>Clinic</th>
				<th>DOI</th>
				<th>Case Type</th>
				<th>Status</th>
				<th>Therapy Type</th>
				<th>Patient Record Id</th>
				<th>Case Record Id</th>
			</tr>
			<tr>
				<td><input id="palname" name="search[palname]" type="text" size="30" maxlength="30" value="<?php echo $default['palname'];  ?>" onchange="upperCase(this.id)" /></td>
				<td><input id="pafname" name="search[pafname]" type="text" size="30" maxlength="30" value="<?php echo $default['pafname'];  ?>" onchange="upperCase(this.id)" /></td>
				<td><input id="crpnum" name="search[crpnum]" type="text" size="6" maxlength="6" value="<?php echo $default['crpnum'];  ?>" onchange="upperCase(this.id)" /></td>
				<td><input id="passn" name="search[passn]" type="text" size="11" maxlength="11" value="<?php echo $default['passn'];  ?>" /></td>
				<td><input id="paphone1" name="search[paphone1]" type="text" size="14" maxlength="14" value="<?php echo displayPhone($default['paphone1']);  ?>" onchange="displayphone(this.id)" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="padob" name="search[padob]" type="text" size="14" maxlength="14" value="<?php echo $default['padob']; ?>"  onchange="validateDate(this.id)" />
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.searchBarForm.padob,'anchor1','MM/dd/yyyy'); return false;" /></td>
				<td><input id="crcnum" name="search[crcnum]" type="text" size="3" maxlength="3" value="<?php echo $default['crcnum'];  ?>" onchange="upperCase(this.id)" /></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crinjurydate" name="search[crinjurydate]" type="text" size="14" maxlength="14" value="<?php echo $default['crinjurydate']; ?>"  onchange="validateDate(this.id)" />
					<img  align="absmiddle" name="anchor2" id="anchor2" src="/img/calendar.gif" onclick="cal.select(document.searchBarForm.crinjurydate,'anchor2','MM/dd/yyyy'); return false;" /></td>
				<td><input id="crcasetypecode" name="search[crcasetypecode]" type="text" size="3" maxlength="3" value="<?php echo $default['crcasetypecode'];  ?>" onchange="upperCase(this.id)" /></td>
				<td><input id="crcasestatuscode" name="search[crcasestatuscode]" type="text" size="3" maxlength="3" value="<?php echo $default['crcasestatuscode'];  ?>" onchange="upperCase(this.id)" /></td>
				<td><input id="crtherapytypecode" name="search[crtherapytypecode]" type="text" size="3" maxlength="3" value="<?php echo $default['crtherapytypecode'];  ?>" onchange="upperCase(this.id)" /></td>
				<td><input id="paid" name="search[paid]" type="text" size="11" maxlength="11" value="<?php echo $default['paid'];  ?>" /></td>
				<td><input id="crid" name="search[crid]" type="text" size="11" maxlength="11" value="<?php echo $default['crid'];  ?>" /></td>
			</tr>
			<tr>
				<td colspan="13"><div>
						<div style="float:left;">
							<input id="SearchButton" name="buttonSetSearch" type="submit" value="Search"  />
						</div>
						<div style="float:left;">
							<input id="ClearButton" name="buttonClearSearch" type="submit" value="Clear" <?php echo $disableclear ?> />
						</div>
					</div></td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
