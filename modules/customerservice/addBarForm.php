<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
?>
<script language="javascript">
function formValidator(){
    var pnum = document.getElementById('pnum');
	var last = document.getElementById('palname');
	var first = document.getElementById('pafname');
	var dob = document.getElementById('padob');
	var phone = document.getElementById('paphone1');
	var ssn = document.getElementById('passn');
	if( notEmpty(ssn) || notEmpty(phone) || notEmpty(dob) || notEmpty(first) || notEmpty(last) || notEmpty(pnum) ) {
		document.addForm.ClearButton.disabled = false;
		var url = document.location.href;
		window.history.pushState({}, "", url.split("?")[0]);
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
	"paid"=>array("title"=>"Id", "type"=>"text", "dbformat"=>"int", "dblength"=>"11", "displayformat"=>"numeric", "displaylength"=>"11", "test"=>"EQUAL"),
	"palname"=>array("title"=>"Last Name", "type"=>"text", "dbformat"=>"varchar", "dblength"=>"30", "displayformat"=>"name", "displaylength"=>"30", "test"=>"LIKE"),
	"pafname"=>array("title"=>"First Name", "type"=>"text", "dbformat"=>"varchar", "dblength"=>"30", "displayformat"=>"name", "displaylength"=>"30", "test"=>"LIKE"),
	"padob"=>array("title"=>"Birth Date", "type"=>"text", "dbformat"=>"date", "dblength"=>"8", "displayformat"=>"date", "displaylength"=>"10", "test"=>"EQUAL"),
	"paphone1"=>array("title"=>"Phone Number", "type"=>"text", "dbformat"=>"phone", "dblength"=>"18", "displayformat"=>"phone", "displaylength"=>"22", "test"=>"EQUAL"),
	"passn"=>array("title"=>"Social Security Number", "type"=>"text", "dbformat"=>"ssn", "dblength"=>"9", "displayformat"=>"ssn", "displaylength"=>"11", "test"=>"EQUAL"),
	"pnum"=>array("title"=>"PNum", "type"=>"text", "dbformat"=>"varchar", "dblength"=>"6", "displayformat"=>"numeric", "displaylength"=>"6", "test"=>"EQUAL")
);

if(!empty($_POST['buttonClearSearch']))
	clearformvars('customerservice', 'search');

// If Search then save search values
$disableadd = 'disabled="disabled"';
$disableclear = 'disabled="disabled"';
if(!empty($_POST['buttonSetSearch'])) {
	setformvars('customerservice', 'search', $_POST['search']);
}
// In any case retrieve search values
$default = getformvars('customerservice', 'search');
// If any field is populated then enable the Add button
foreach($default as $field=>$value) {
	if(!empty($value)) {
		unset($disableadd);
		unset($disableclear);
	}
}

$disableadd = 'disabled="disabled"';
if(count($default) == 0)
	$disableclear = 'disabled="disabled"';
else {
	unset($disableclear);
	if(isset($default['palname']) || isset($default['pafname']) )
		unset($disableadd);
}

?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Search Patient Information</legend>
	<form method="post" name="addForm" onsubmit="return formValidator()">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>PNum</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>DOB</th>
				<th>Phone</th>
				<th>SSN</th>
			</tr>
			<tr>
				<td><input id="pnum" name="search[pnum]" type="text" size="10" maxlength="30" value="<?php if(isset($default['pnum'])) echo $default['pnum'];  ?>"></td>
				<td><input id="palname" name="search[palname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['palname'])) echo $default['palname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="pafname" name="search[pafname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['pafname'])) echo $default['pafname'];  ?>" onchange="upperCase(this.id)"></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="padob" name="search[padob]" type="text" size="10" maxlength="10" value="<?php if(isset($default['padob'])) echo $default['padob']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.addForm.padob,'anchor1','MM/dd/yyyy'); return false;" /></td>
				<td><input id="paphone1" name="search[paphone1]" type="text" size="11" maxlength="14" value="<?php if(isset($default['paphone1'])) echo displayPhone($default['paphone1']);  ?>" onchange="displayphone(this.id)"></td>
				<td><input id="passn" name="search[passn]" type="text" size="11" maxlength="11" value="<?php if(isset($default['passn'])) echo displaySsnAll($default['passn']);  ?>" onchange="displayssn(this.id)"></td>
			</tr>
			<tr>
				<td colspan="6"><div>
						<div style="float:left;">
							<input name="buttonSetSearch" type="submit" value="Search"  />
						</div>
						<div style="float:left;">
							<input id="ClearButton" name="buttonClearSearch" type="submit" value="Clear" <?php if(isset($disableclear)) echo $disableclear ?> />
						</div>
						<div style="float:right;">
							<input id="AddButton" name="button[]" type="submit" value="Add" <?php if(isset($disableadd)) echo $disableadd ?> />
						</div>
					</div></td>
			</tr>
		</table>
<!--		<input id="paid" name="search[paid]" type="hidden" size="5" maxlength="5" value="<?php if(isset($_POST['paid'])) echo $_POST['paid'];  ?>">-->
	</form>
	</fieldset>
</div>
