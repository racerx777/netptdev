<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90); 
?>
<script language="javascript">

function formValidator(){
	var pnum = document.getElementById('pnum');
	var id = document.getElementById('caid');
	var ssn = document.getElementById('ssn');
	var lname = document.getElementById('lname');
	var fname = document.getElementById('fname');
	var dob = document.getElementById('birth');
	var accttype = document.getElementById('caaccttype');
	var acctsubtype = document.getElementById('caacctsubtype');
	var acctgroup = document.getElementById('caacctgroup');
	var acctstatus = document.getElementById('caacctstatus');
	var lienstatus = document.getElementById('calienstatus');
	if(notEmpty(id) || notEmpty(lienstatus) || notEmpty(acctstatus) || notEmpty(acctgroup) || notEmpty(acctsubtype) || notEmpty(accttype) || notEmpty(dob) || notEmpty(fname) || notEmpty(lname) || notEmpty(ssn) || notEmpty(pnum)) {
		document.searchForm.ClearButton.disabled = false;
		return true;
	}
	document.searchForm.ClearButton.disabled = true;
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
		document.searchForm.padob.value="";
	}
	formValidator();
}

function ssn(id) {
//	ssnFormat(id);
	formValidator();
}

function removeAllOptions(selectbox) {
	var i;
	for(i=selectbox.options.length-1;i>=0;i--) {
		//selectbox.options.remove(i);
		selectbox.remove(i);
	}
}

function addOption(selectbox,text,value ) {
	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = value;
	selectbox.options.add(optn);
}
</script>
<?php
$searchvars = array(
	"noid"=>array(
				"title"=>"Note Identifier", 
				"type"=>"text", 
				"dbformat"=>"int", 
				"dblength"=>"11", 
				"displayformat"=>"text", 
				"displaylength"=>"11", 
				"test"=>"EQUAL"),
	"noapp"=>array(
				"title"=>"Application", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"16", 
				"displayformat"=>"text", 
				"displaylength"=>"16", 
				"test"=>"LIKELIKE"),
	"noappid"=>array(
				"title"=>"App Identifier", 
				"type"=>"text", 
				"dbformat"=>"int", 
				"dblength"=>"11", 
				"displayformat"=>"text", 
				"displaylength"=>"11", 
				"test"=>"EQUAL"),
	"bnum"=>array(
				"title"=>"Business Unit", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"3", 
				"displayformat"=>"text", 
				"displaylength"=>"3", 
				"test"=>"EQUAL"),
	"pnum"=>array(
				"title"=>"PTOS Number", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"6", 
				"displayformat"=>"text", 
				"displaylength"=>"6", 
				"test"=>"LIKELIKE"),
	"nobutton"=>array(
				"title"=>"Button", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"16", 
				"displayformat"=>"text", 
				"displaylength"=>"16", 
				"test"=>"LIKELIKE"),
	"nonote"=>array(
				"title"=>"Note", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"255", 
				"displayformat"=>"text", 
				"displaylength"=>"255", 
				"test"=>"LIKELIKE"), 
	"nodata"=>array(
				"title"=>"Note Data", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"255", 
				"displayformat"=>"text", 
				"displaylength"=>"255", 
				"test"=>"LIKELIKE"),  
	"crtuser"=>array(
				"title"=>"Created By User", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"16", 
				"displayformat"=>"text", 
				"displaylength"=>"16", 
				"test"=>"LIKELKE"),
	"crtdate"=>array(
				"title"=>"Create Date", 
				"type"=>"text", 
				"dbformat"=>"date", 
				"dblength"=>"8", 
				"displayformat"=>"date", 
				"displaylength"=>"10", 
				"test"=>"EQUAL"),  
	"crtprog"=>array(
				"title"=>"Created By Program", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"16", 
				"displayformat"=>"text", 
				"displaylength"=>"16", 
				"test"=>"LIKELIKE"),
	"upduser"=>array(
				"title"=>"Updated By User", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"16", 
				"displayformat"=>"text", 
				"displaylength"=>"16", 
				"test"=>"LIKELKE"),
	"upddate"=>array(
				"title"=>"Update Date", 
				"type"=>"text", 
				"dbformat"=>"date", 
				"dblength"=>"8", 
				"displayformat"=>"date", 
				"displaylength"=>"10", 
				"test"=>"EQUAL"),  
	"updprog"=>array(
				"title"=>"Updated By Program", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"16", 
				"displayformat"=>"text", 
				"displaylength"=>"16", 
				"test"=>"LIKELIKE")
);

if(!empty($_POST['buttonClearSearch'])) {
	clearformvars('notes', 'search');
	unset($_POST['search']);
}
// If Search then save search values
$disableclear = 'disabled="disabled"';
if(!empty($_POST['buttonSetSearch'])) {
	$dbvalues = valuestodb($_POST['search'], $searchvars);
	setformvars('notes', 'search', $dbvalues);
}
// In any case retrieve search values
$default = getformvars('notes', 'search');
$default = valuestodisplay($default, $searchvars);
// If any field is populated then enable the Add button
foreach($default as $field=>$value) {
	if(!empty($value)) 
		unset($disableclear);
}

// In any case retrieve search values
//$default = getformvars('authorization', 'search');
// If any field is populated then enable the Add button
unset($_SESSION['button']); // will default to last button search if disabled will clear search
?>
<div class="containedBox" id="searchBarForm">
	<fieldset>
	<legend style="font-size:large">Notes Search</legend>
	<form method="post" name="searchForm" onsubmit="return formValidator()">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>noid</th>
				<th>noapp</th>
				<th>noappid</th>
				<th>nobnum</th>
				<th>nopnum</th>
				<th>nobutton</th>
				<th>nonote</th>
				<th>nodata</th>
                <th>crtdate</th>
				<th>crtuser</th>
				<th>crtprog</th>
                <th>upddate</th>
				<th>upduser</th>
				<th>updprog</th>
			</tr>
			<tr>
				<td><input id="noid" name="search[noid]" type="text" size="10" maxlength="10" value="<?php if(isset($default['noid'])) echo $default['noid'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="noapp" name="search[noapp]" type="text" size="11" maxlength="11" value="<?php if(isset($default['noapp'])) echo displaySsnAll($default['noapp']);  ?>" onchange="uppercase(this.id)" /></td>
				<td><input id="noappid" name="search[noappid]" type="text" size="10" maxlength="30" value="<?php if(isset($default['noappid'])) echo $default['noappid'];  ?>"></td>
				<td><input id="nobnum" name="search[nobnum]" type="text" size="10" maxlength="30" value="<?php if(isset($default['nobnum'])) echo $default['nobnum'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="nopnum" name="search[nopnum]" type="text" size="10" maxlength="30" value="<?php if(isset($default['nopnum'])) echo $default['nopnum'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="nobutton" name="search[nobutton]" type="text" size="10" maxlength="30" value="<?php if(isset($default['nobutton'])) echo $default['nobutton'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="nonote" name="search[nonote]" type="text" size="10" maxlength="30" value="<?php if(isset($default['nonote'])) echo $default['nonote'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="nodata" name="search[nodata]" type="text" size="10" maxlength="30" value="<?php if(isset($default['nodata'])) echo $default['nodata'];  ?>" onchange="upperCase(this.id)"></td>

				<td nowrap="nowrap" style="text-decoration:none"><input id="crtdate" name="search[crtdate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['crtdate'])) echo $default['crtdate']; ?>"  onchange="validateDate(this.id)">
				<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.searchForm.crtdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
				<td><input id="crtuser" name="search[crtuser]" type="text" size="10" maxlength="30" value="<?php if(isset($default['crtuser'])) echo $default['crtuser'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="crtprog" name="search[crtprog]" type="text" size="10" maxlength="30" value="<?php if(isset($default['crtprog'])) echo $default['crtprog'];  ?>" onchange="upperCase(this.id)"></td>

				<td nowrap="nowrap" style="text-decoration:none"><input id="upddate" name="search[upddate]" type="text" size="10" maxlength="10" value="<?php if(isset($default['upddate'])) echo $default['upddate']; ?>"  onchange="validateDate(this.id)">
				<img  align="absmiddle" name="anchor2" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.searchForm.upddate,'anchor2','MM/dd/yyyy'); return false;" /></td>
				<td><input id="upduser" name="search[upduser]" type="text" size="10" maxlength="30" value="<?php if(isset($default['upduser'])) echo $default['upduser'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="updprog" name="search[updprog]" type="text" size="10" maxlength="30" value="<?php if(isset($default['updprog'])) echo $default['updprog'];  ?>" onchange="upperCase(this.id)"></td>
			</tr>
			<tr><td colspan="14"><div>
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
