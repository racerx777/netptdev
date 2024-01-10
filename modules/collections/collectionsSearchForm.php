<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33); 
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

function caacctgroup_change(accttype, acctsubtype) {
}

function caacctsubtype_change(id) {
	removeAllOptions(document.searchForm.caacctgroup);
<?php
	$accttypecodes = collectionsAccountTypeCodes();
	foreach($accttypecodes as $id1=>$accttypearray) {
		$accttypecode = $accttypearray['code'];
		$accttypedescription = $accttypearray['description'];
		echo("\tif(document.searchForm.caaccttype.value == '$accttypecode'){ // $accttypedescription\n");
		$acctsubtypecodes = collectionsAccountSubTypeCodes($accttypecode);
		if(count($acctsubtypecodes)>0) {
			$enablesubtype=true;
			$blankarray=array("type"=>$accttypecode, "code"=>NULL, "description"=>"");
			$acctsubtypecodes[]=$blankarray;
		}
		else
			$enablesubtype=false;
		foreach($acctsubtypecodes as $id2=>$acctsubtypearray) {
			$acctsubtypetype = $acctsubtypearray['type'];
			$acctsubtypecode = $acctsubtypearray['code'];
			$acctsubtypedescription = $acctsubtypearray['description'];
			if($acctsubtypetype == $accttypecode) {
				echo("\t\tif(document.searchForm.caacctsubtype.value == '$acctsubtypecode'){ // $acctsubtypedescription\n");
				$acctgroupcodes = collectionsAccountGroupCodes($accttypecode, $acctsubtypecode);
				if(count($acctgroupcodes)>0) {
					$enablegroup=true;
					$blankarray1[0]=array("type"=>$accttypecode, "subtype"=>$acctsubtypecode, "code"=>NULL, "description"=>"");
					$acctgroupcodes=array_merge($blankarray1, $acctgroupcodes);
				}
				else
					$enablegroup=false;
				foreach($acctgroupcodes as $id3=>$acctgrouparray) {	
					$acctgrouptype = $acctgrouparray['type'];
					$acctgroupsubtype = $acctgrouparray['subtype'];
					$acctgroupcode = $acctgrouparray['code'];
					$acctgroupdescription = $acctgrouparray['description'];
					if($acctgrouptype==$accttypecode && ($acctgroupsubtype==$acctsubtypecode || empty($acctsubtypecode))) 
						echo("\t\t\taddOption(document.searchForm.caacctgroup, '$acctgroupdescription','$acctgroupcode'); // $acctgroupdescription\n");
				}
				echo("\t\t}// end if \n");
			}
		}
		if(!empty($acctsubtypecode)) {
			if($enablegroup==true)
				echo("document.searchForm.caacctgroup.disabled=false;\n");
			else
				echo("document.searchForm.caacctgroup.disabled=true;\n");
			if($enablesubtype==true)
				echo("document.searchForm.caacctsubtype.disabled=false;\n");
			else
				echo("document.searchForm.caacctsubtype.disabled=true;\n");
		}
		echo("\t} // end if \n");
	}
	?>
} 

function caaccttype_change(id) {
	removeAllOptions(document.searchForm.caacctsubtype);
<?php
	$accttypecodes = collectionsAccountTypeCodes();
	foreach($accttypecodes as $id1=>$accttypearray) {
		$accttypecode = $accttypearray['code'];
		$accttypedescription = $accttypearray['description'];
		echo("\tif(document.searchForm.caaccttype.value == '$accttypecode'){ // $accttypedescription\n");
		$acctsubtypecodes = collectionsAccountSubTypeCodes($accttypecode);
		if(count($acctsubtypecodes)>0) {
			$enablesubtype=true;
			$blankarray2[0]=array("type"=>$accttypecode, "code"=>NULL, "description"=>NULL);
			$acctsubtypecodes=array_merge($blankarray2, $acctsubtypecodes);
		}
		else
			$enablesubtype=false;
		foreach($acctsubtypecodes as $id2=>$acctsubtypearray) {
			$acctsubtypetype = $acctsubtypearray['type'];
			$acctsubtypecode = $acctsubtypearray['code'];
			$acctsubtypedescription = $acctsubtypearray['description'];
			if($acctsubtypetype==$accttypecode) 
				echo("\t\taddOption(document.searchForm.caacctsubtype, '$acctsubtypedescription', '$acctsubtypecode'); // $acctsubtypedescription\n");
		}
		echo("\t}\n");
	}
	?>
	caacctsubtype_change(id);
}
</script>
<?php
$searchvars = array(
	"capnum"=>array(
				"title"=>"PTOS Number", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"6", 
				"displayformat"=>"text", 
				"displaylength"=>"6", 
				"test"=>"LIKE"),
	"ssn"=>array(
				"title"=>"Social Security Number", 
				"type"=>"text", 
				"dbformat"=>"ssn", 
				"dblength"=>"9", 
				"displayformat"=>"ssn", 
				"displaylength"=>"11", 
				"test"=>"LIKELIKE"),
	"lname"=>array(
				"title"=>"Last Name", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"30", 
				"displayformat"=>"name", 
				"displaylength"=>"30", 
				"test"=>"LIKE"), 
	"fname"=>array(
				"title"=>"First Name", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"30", 
				"displayformat"=>"name", 
				"displaylength"=>"30", 
				"test"=>"LIKE"),  
	"birth"=>array(
				"title"=>"Birth Date", 
				"type"=>"text", 
				"dbformat"=>"date", 
				"dblength"=>"8", 
				"displayformat"=>"date", 
				"displaylength"=>"10", 
				"test"=>"EQUAL"),  
	"caaccttype"=>array(
				"title"=>"Coll Account Type", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"3", 
				"displayformat"=>"text", 
				"displaylength"=>"3", 
				"test"=>"EQUAL"),
	"caacctsubtype"=>array(
				"title"=>"Coll Account Sub Type", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"3", 
				"displayformat"=>"text", 
				"displaylength"=>"3", 
				"test"=>"EQUAL"),
	"caacctstatus"=>array(
				"title"=>"Coll Account Status", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"3", 
				"displayformat"=>"text", 
				"displaylength"=>"3", 
				"test"=>"EQUAL"),
	"calienstatus"=>array(
				"title"=>"Lien Status", 
				"type"=>"text", 
				"dbformat"=>"varchar", 
				"dblength"=>"3", 
				"displayformat"=>"text", 
				"displaylength"=>"3", 
				"test"=>"EQUAL"),
	"caid"=>array(
				"title"=>"Coll Id", 
				"type"=>"text", 
				"dbformat"=>"int", 
				"dblength"=>"11", 
				"displayformat"=>"text", 
				"displaylength"=>"11", 
				"test"=>"EQUAL")
);

if(!empty($_POST['buttonClearSearch'])) {
	clearformvars('collections', 'search');
	unset($_POST['search']);
}
// If Search then save search values
$disableclear = 'disabled="disabled"';
if(!empty($_POST['buttonSetSearch'])) {
	$dbvalues = valuestodb($_POST['search'], $searchvars);
	setformvars('collections', 'search', $dbvalues);
}
// In any case retrieve search values
$default = getformvars('collections', 'search');
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
//unset($_SESSION['button']); // will default to last button search if disabled will clear search
?>
<p>&nbsp;</p>
<div class="containedBox" id="searchBarForm">
	<fieldset>
	<legend style="font-size:large">Collections Search	</legend>
	<form method="post" name="searchForm" onsubmit="return formValidator()">
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>PTOS Number</th>
				<th>SSN</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>DOB</th>
				<th width="25px">Account Type</th>
				<th width="25px">Sub Type</th>
                <th width="25px">Group</th>
				<th>Coll Acct Sts</th>
				<th>Lien Sts</th>
				<th>Coll Id</th>
			</tr>
			<tr>
				<td><input id="capnum" name="search[capnum]" type="text" size="10" maxlength="10" value="<?php if(isset($default['capnum'])) echo $default['capnum'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="ssn" name="search[ssn]" type="text" size="11" maxlength="11" value="<?php if(isset($default['ssn'])) echo displaySsnAll($default['ssn']);  ?>" onchange="displayssn(this.id)" /></td>
				<td><input id="lname" name="search[lname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['lname'])) echo $default['lname'];  ?>" onchange="upperCase(this.id)"></td>
				<td><input id="fname" name="search[fname]" type="text" size="10" maxlength="30" value="<?php if(isset($default['fname'])) echo $default['fname'];  ?>" onchange="upperCase(this.id)"></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="birth" name="search[birth]" type="text" size="10" maxlength="10" value="<?php if(isset($default['birth'])) echo $default['birth']; ?>"  onchange="validateDate(this.id)">
				<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.searchForm.birth,'anchor1','MM/dd/yyyy'); return false;" /></td>
		  <td><select name="search[caaccttype]" id="caaccttype" onchange="caaccttype_change(this.id)" style="width:10em;">
						<?php echo getSelectOptions($arrayofarrayitems = collectionsAccountTypeCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['caaccttype'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
				<td><select name="search[caacctsubtype]" id="caacctsubtype" onchange="caacctsubtype_change(this.id)" style="width:10em;">
						<?php echo getSelectOptions($arrayofarrayitems = collectionsAccountSubTypeCodes($default['caaccttype']), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['caacctsubtype'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
          <td><select name="search[caacctgroup]" id="caacctgroup" style="width:10em;">
						<?php echo getSelectOptions($arrayofarrayitems = collectionsAccountGroupCodes($default['caaccttype'], $default['caacctsubtype']), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['caacctgroup'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
				<td><select name="search[caacctstatus]" id="caacctstatus">
						<?php echo getSelectOptions($arrayofarrayitems = collectionsAccountStatusCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['caacctstatus'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
				<td><select name="search[calienstatus]" id="calienstatus" >
						<?php echo getSelectOptions($arrayofarrayitems = collectionsLienStatusCodes(), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['calienstatus'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
				</select></td>
				<td><input id="caid" name="search[caid]" type="text" size="10" maxlength="10" value="<?php if(isset($default['caid'])) echo $default['caid'];  ?>" onchange="upperCase(this.id)"></td>
			</tr>
			<tr><td colspan="12"><div>
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
