<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/sitedivs.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script type="text/javascript">
document.title="Display/Update Insurance Information";
function toggleenable(id) {
	var ele = document.getElementById(id);
	ele.disabled= !ele.disabled;
}
function toggleInsuranceCompanyNameFields() {
	toggleenable("icname");
}
function clearInsuranceCompanyNameFields() {
	document.insuranceEditForm.icname.value='';
}
function insuranceCompanyNameEdit() {
	var divUpd = document.getElementById("insuranceCompanyNameUpdate");
	var divAdd = document.getElementById("insuranceCompanyNameAdd");
	var divSel = document.getElementById("insuranceCompanyNameSelect");
	divUpd.style.display="block";
	divAdd.style.display="none";
	divSel.style.display="none";
	toggleInsuranceCompanyNameFields();
}
function insuranceCompanyNameNew() {
	var divUpd = document.getElementById("insuranceCompanyNameUpdate");
	var divAdd = document.getElementById("insuranceCompanyNameAdd");
	var divSel = document.getElementById("insuranceCompanyNameSelect");
	divUpd.style.display="none";
	divAdd.style.display="block";
	divSel.style.display="none";
	toggleInsuranceCompanyNameFields();
	clearInsuranceCompanyNameFields();
}
function insuranceCompanyNameSelect() {
	var divUpd = document.getElementById("insuranceCompanyNameUpdate");
	var divAdd = document.getElementById("insuranceCompanyNameAdd");
	var divSel = document.getElementById("insuranceCompanyNameSelect");
	divUpd.style.display="none";
	divAdd.style.display="none";
	divSel.style.display="block";
	toggleInsuranceCompanyNameFields();
}

function toggleInsuranceCompanyLocationFields() {
	toggleenable("iclname");
	toggleenable("icladdress1");
	toggleenable("icladdress2");
	toggleenable("iclcity");
	toggleenable("iclstate");
	toggleenable("iclzip");
	toggleenable("iclphone");
	toggleenable("iclfax");
	toggleenable("iclemail");
	toggleenable("iclofficehours");
}
function clearInsuranceCompanyLocationFields(){
	document.insuranceEditForm.iclname.value='';
	document.insuranceEditForm.icladdress1.value='';
	document.insuranceEditForm.icladdress2.value='';
	document.insuranceEditForm.iclcity.value='';
	document.insuranceEditForm.iclstate.value='';
	document.insuranceEditForm.iclzip.value='';
	document.insuranceEditForm.iclphone.value='';
	document.insuranceEditForm.iclfax.value='';
	document.insuranceEditForm.iclemail.value='';
	document.insuranceEditForm.iclofficehours.value='';
}
function insuranceCompanyLocationEdit() {
	var divUpd = document.getElementById("insuranceCompanyLocationUpdate");
	var divAdd = document.getElementById("insuranceCompanyLocationAdd");
	var divSel = document.getElementById("insuranceCompanyLocationSelect");
	divUpd.style.display="block";
	divAdd.style.display="none";
	divSel.style.display="none";
	toggleInsuranceCompanyLocationFields();
}
function insuranceCompanyLocationNew() {
	var divUpd = document.getElementById("insuranceCompanyLocationUpdate");
	var divAdd = document.getElementById("insuranceCompanyLocationAdd");
	var divSel = document.getElementById("insuranceCompanyLocationSelect");
	divUpd.style.display="none";
	divAdd.style.display="block";
	divSel.style.display="none";
	clearInsuranceCompanyLocationFields();
	toggleInsuranceCompanyLocationFields();
}
function insuranceCompanyLocationSelect() {
	var divUpd = document.getElementById("insuranceCompanyLocationUpdate");
	var divAdd = document.getElementById("insuranceCompanyLocationAdd");
	var divSel = document.getElementById("insuranceCompanyLocationSelect");
	divUpd.style.display="none";
	divAdd.style.display="none";
	divSel.style.display="block";
	toggleInsuranceCompanyLocationFields();
}
function toggleInsuranceCompanyAdjusterFields() {
	toggleenable("icafname");
	toggleenable("icalname");
	toggleenable("icaaddress1");
	toggleenable("icaaddress2");
	toggleenable("icacity");
	toggleenable("icastate");
	toggleenable("icazip");
	toggleenable("icaphone");
	toggleenable("icafax");
	toggleenable("icaemail");
	toggleenable("icaofficehours");
}
function clearInsuranceCompanyAdjusterFields(){
	document.insuranceEditForm.icafname.value='';
	document.insuranceEditForm.icalname.value='';
	document.insuranceEditForm.icaaddress1.value='';
	document.insuranceEditForm.icaaddress2.value='';
	document.insuranceEditForm.icacity.value='';
	document.insuranceEditForm.icastate.value='';
	document.insuranceEditForm.icazip.value='';
	document.insuranceEditForm.icaphone.value='';
	document.insuranceEditForm.icafax.value='';
	document.insuranceEditForm.icaemail.value='';
	document.insuranceEditForm.icaofficehours.value='';
}
function insuranceCompanyAdjusterEdit() {
	var divUpd = document.getElementById("insuranceCompanyAdjusterUpdate");
	var divAdd = document.getElementById("insuranceCompanyAdjusterAdd");
	var divSel = document.getElementById("insuranceCompanyAdjusterSelect");
	divUpd.style.display="block";
	divAdd.style.display="none";
	divSel.style.display="none";
	toggleInsuranceCompanyAdjusterFields();
}
function insuranceCompanyAdjusterNew() {
	var divUpd = document.getElementById("insuranceCompanyAdjusterUpdate");
	var divAdd = document.getElementById("insuranceCompanyAdjusterAdd");
	var divSel = document.getElementById("insuranceCompanyAdjusterSelect");
	divUpd.style.display="none";
	divAdd.style.display="block";
	divSel.style.display="none";
	clearInsuranceCompanyAdjusterFields();
	toggleInsuranceCompanyAdjusterFields();
}
function insuranceCompanyAdjusterSelect() {
	var divUpd = document.getElementById("insuranceCompanyAdjusterUpdate");
	var divAdd = document.getElementById("insuranceCompanyAdjusterAdd");
	var divSel = document.getElementById("insuranceCompanyAdjusterSelect");
	divUpd.style.display="none";
	divAdd.style.display="none";
	divSel.style.display="block";
	toggleInsuranceCompanyAdjusterFields();
}
</script>
<?php
unset($crid);
if(isset($_POST['crid'])) $crid=$_POST['crid'];
else {
	if(isset($_REQUEST['crid'])) $crid=$_REQUEST['crid'];
}

unset($icseq);
if(isset($_POST['icseq'])) $icseq=$_POST['icseq'];
else {
	if(isset($_REQUEST['icseq'])) $icseq=$_REQUEST['icseq'];
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


if(isset($_POST['AddInsuranceCompanyName'])) {
	require_once('insuranceSQLFunctions.php');
	if($insertid = insuranceCompanyName('INSERT',NULL,$_POST))
		$_POST['icid'] = $insertid;
	displaysitemessages();
}

if(isset($_POST['UpdateInsuranceCompanyName'])) {
	require_once('insuranceSQLFunctions.php');
	insuranceCompanyName('UPDATE',$_POST['icid'],$_POST);
	displaysitemessages();
}

if(isset($_POST['AddInsuranceCompanyLocation'])) {
	require_once('insuranceSQLFunctions.php');
	if($insertid = insuranceCompanyLocation('INSERT',NULL,$_POST));
		$_POST['iclid'] = $insertid; 
	displaysitemessages();
}
if(isset($_POST['UpdateInsuranceCompanyLocation'])) {
	require_once('insuranceSQLFunctions.php');
	insuranceCompanyLocation('UPDATE',$_POST['iclid'],$_POST);
	displaysitemessages();
}

if(isset($_POST['AddInsuranceCompanyAdjuster'])) {
	require_once('insuranceSQLFunctions.php');
	if($insertid = insuranceCompanyAdjuster('INSERT',NULL,$_POST));
		$_POST['icaid'] = $insertid;
	displaysitemessages();
}
if(isset($_POST['UpdateInsuranceCompanyAdjuster'])) {
	require_once('insuranceSQLFunctions.php');
	insuranceCompanyAdjuster('UPDATE',$_POST['icaid'],$_POST);
	displaysitemessages();
}

if(isset($_POST['submitbutton'])) {
	require_once('insuranceSQLUpdate.php');
?>
<script language="javascript" type="text/javascript">
window.opener.location.href = window.opener.location.href;
if (window.opener.progressWindow) {
	window.opener.progressWindow.close()
}
window.close();
</script>
<?php
}

if(empty($crid) || empty($icseq)) {
	error("001","No Case identifier ($crid) or sequence ($icseq)");
	displaysitemessages(); ?>

<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />
<?	exit();
}

// Retrieve values from database if form was never loaded

$buttonvalue = "Update Insurance $icseq";
if(!isset($_POST['init'])) {
// Display Patient Information
	$script = 'insuranceEditForm';
	$table = 'cases';
	$keyfield = 'crid';
	if($icseq == '1') 
		$fields[$table]=array(
					'crid'=>'integer', 			// Case Insurance Unique Identifier
					'crpnum'=>'varchar', 		// PTOS Case Number
					'cricclaimnumber1'=>'name', // Insurance Company Claim Number 
					'cricid1'=>'integer', 		// Insurance Company Identifier 
					'criclid1'=>'integer', 		// insurance Company Location Identifier
					'cricaid1'=>'integer', 		// Insurance Company Adjuster Identifier
					'crinsurance1note'=>'varchar' 		// Insurance Company Note
				);
	if($icseq == '2') 
		$fields[$table]=array(
					'crid'=>'integer', 			// Case Insurance Unique Identifier
					'crpnum'=>'varchar', 		// PTOS Case Number
					'cricclaimnumber2'=>'name', 	// Insurance Company Claim Number 
					'cricid2'=>'integer', 		// Insurance Company Identifier 
					'criclid2'=>'integer', 		// insurance Company Location Identifier
					'cricaid2'=>'integer', 		// Insurance Company Adjuster Identifier
					'crinsurance2note'=>'varchar' 		// Insurance Company Note
				);

	$fieldslist = implode(", ", array_keys($fields[$table]));
	$query = "SELECT crptosstatus, crpnum, $fieldslist, paid, palname, pafname FROM $table LEFT JOIN patients ON crpaid=paid WHERE crid='$crid'";
	if($result = mysqli_query($dbhandle,$query)) {
		if($row = mysqli_fetch_assoc($result)) {
			foreach($row as $fieldname=>$fieldvalue) {
				if($fields["$table"]["$fieldname"]=='date') {
					if($fieldvalue == '1999-11-30 00:00:00')
						$fieldvalue=NULL;
				}
				if(!empty($fieldvalue))
					$_POST[$fieldname] = $fieldvalue;
			}
			if($icseq == '1') {
				$_POST['icclaimnumber'] = $_POST['cricclaimnumber1'];
				$_POST['icid'] = $_POST['cricid1'];
				$_POST['iclid'] = $_POST['criclid1'];
				$_POST['icaid'] = $_POST['cricaid1'];
				$_POST['icnote'] = $_POST['crinsurance1note'];
			}
			if($icseq == '2') {
				$_POST['icclaimnumber'] = $_POST['cricclaimnumber1'];
				$_POST['icid'] = $_POST['cricid2'];
				$_POST['iclid'] = $_POST['criclid2'];
				$_POST['icaid'] = $_POST['cricaid2'];
				$_POST['icnote'] = $_POST['crinsurance2note'];
			}
		}
		else
			error('001', "FETCH:$query<br>".mysqli_error($dbhandle));	
	}
	else
		error('002', "SELECT:$query<br>".mysqli_error($dbhandle));	
}
	if(errorcount() == 0) {
		$icclaimnumber = $_POST['icclaimnumber'];
		$icid = $_POST['icid'];
		$iclid = $_POST['iclid'];
		$icaid = $_POST['icaid'];
		$icnote = $_POST['icnote'];
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/insurance.options.php');
		$insurancelistoptions="";
		$insurancelocationlistoptions="";
		$insurancelocationadjusterlistoptions="";
		$insurancelocationdisabled='disabled="disabled"';
		$insurancelocationadjusterdisabled='disabled="disabled"';
		$insurancelist = getInsuranceCompaniesList();
		$icinfo="<br>";
		$iclinfo="<br><br><br><br><br><br><br><br><br>";
		$icainfo="<br><br><br><br><br><br><br><br><br>";
		if($insurancelist) {
			if(count($insurancelist) > 0) {
if(is_array($insurancelist) && array_key_exists($_POST['icid'],$insurancelist) && !empty($_POST['icid'])) {
	foreach($insurancelist[$_POST['icid']] as $key=>$val)
		$_POST[$key]=$val;
}
				$insurancelistoptions =  getSelectOptions(
					$arrayofarrayitems=$insurancelist, 
					$optionvaluefield='icid', 
					$arrayofoptionfields=array(
						'icname'=>'' 
						), 
					$defaultoption=$icid, 
					$addblankoption=TRUE, 
					$arraykey='', 
					$arrayofmatchvalues=array()); 
				if(!empty($icid)) {
					$insurancelocationdisabled="";
					$insurancelocationlist  = getInsuranceCompaniesLocationsList($icid);
if(is_array($insurancelocationlist) && array_key_exists($_POST['iclid'],$insurancelocationlist) && !empty($_POST['iclid'])) {
	foreach($insurancelocationlist[$_POST['iclid']] as $key=>$val)
		$_POST[$key]=$val;
}
					$insurancelocationlistoptions = getSelectOptions(
						$arrayofarrayitems=$insurancelocationlist, 
						$optionvaluefield='iclid', 
						$arrayofoptionfields=array(
							'iclname'=>', ', 
							'iclcity'=>', ', 
							'iclphone'=>'' 
							), 
						$defaultoption=$iclid, 
						$addblankoption=TRUE, 
						$arraykey='', 
						$arrayofmatchvalues=array());
					if(!empty($iclid)) {
						$insurancelocationadjusterdisabled="";
						$insurancelocationadjusterlist  = getInsuranceCompaniesAdjustersList($icid); // removed location specific adjuster request per connie
if(is_array($insurancelocationadjusterlist) && array_key_exists($_POST['icaid'],$insurancelocationadjusterlist) && !empty($_POST['icaid'])) {
	foreach($insurancelocationadjusterlist[$_POST['icaid']] as $key=>$val)
		$_POST[$key]=$val;
}
						$insurancelocationadjusterlistoptions = getSelectOptions(
							$arrayofarrayitems=$insurancelocationadjusterlist, 
							$optionvaluefield='icaid', 
							$arrayofoptionfields=array(
								'icalname'=>', ', 
								'icafname'=>' ', 
								'icacity'=>' ', 
								'icaphone'=>'' 
								), 
							$defaultoption=$icaid, 
							$addblankoption=TRUE, 
							$arraykey='', 
							$arrayofmatchvalues=array());
					}
					else
						$insurancelocationadjusterlistoptions = '<option value="">Select a Location...</option>';
				}
				else {
					$insurancelocationlistoptions = '<option value="">Select a Company...</option>';
					$insurancelocationadjusterlistoptions = '<option value="">Select a Company...</option>';
				}
			}
			else
				echo("Error-No Companies in Insurance Companies table.");
		}
		else
			echo("Error-getInsuranceList.");
	}
	else
		displaysitemessages();

if(!empty($_POST['crpnum'])) 
	$pnumhtml = $_POST['crpnum'];
else
	$pnumhtml = '(not assigned)';

if(!empty($_POST['icid'])) 
	$insuranceCompanyNameEditButton = '<input type="button" value="Edit" onclick="insuranceCompanyNameEdit()" />';
$insuranceCompanyNameNewButton = '<input type="button" value="New" onclick="insuranceCompanyNameNew()" />';
if(!empty($_POST['iclid'])) 
	$insuranceCompanyLocationEditButton = '<input type="button" value="Edit" onclick="insuranceCompanyLocationEdit()" />';
$insuranceCompanyLocationNewButton = '<input type="button" value="New" '.$insurancelocationdisabled.' onclick="insuranceCompanyLocationNew()" />';
if(!empty($_POST['icaid'])) 
	$insuranceCompanyAdjusterEditButton = '<input type="button" value="Edit" onclick="insuranceCompanyAdjusterEdit()" />';
$insuranceCompanyAdjusterNewButton = '<input type="button" value="New" '.$insurancelocationadjusterdisabled.' onclick="insuranceCompanyAdjusterNew()" />';
?>
<div class="centerFieldset">
	<form method="post" name="insuranceEditForm">
		<fieldset style="text-align:center;">
		<legend><?php echo $buttonvalue; ?> Information for Patient #<?php echo $_POST['paid'] . " " . $_POST['palname'] . ", " . $_POST['pafname']; ?></legend>
		<table style="text-align:left;">
			<tr>
				<td valign="top" nowrap="nowrap">Carrier Name Code</td>
				<td><div id="insuranceCompanyNameSelect" style="display:block">
						<select name="icid" type="text" size="1" maxlength="30" value="<?php if(isset($icid)) echo $icid; ?>" onchange="javascript:submit();" />
						<?php echo $insurancelistoptions; ?>
						</select>
						<?php echo $insuranceCompanyNameEditButton.$insuranceCompanyNameNewButton; ?>
					</div>
					<table>
						<tr>
							<td>Name</td>
							<td><input id="icname" name="icname" type="text" size="30" maxlength="30" disabled="disabled" value="<?php echo $_POST['icname']; ?>" onchange="upperCase(this.id)" /></td>
						</tr>
					</table>
					<div id="insuranceCompanyNameAdd" style="display: none">
						<input name="AddInsuranceCompanyName" type="submit" value="Add Insurance Company Name" />
						<input type="button" value="Cancel" onclick="insuranceCompanyNameSelect()" />
					</div>	
					<div id="insuranceCompanyNameUpdate" style="display: none">
						<input name="UpdateInsuranceCompanyName" type="submit" value="Update Insurance Company Name" />
						<input type="button" value="Cancel" onclick="insuranceCompanyNameSelect()" />
					</div></td>
			</tr>
			<tr>
				<td valign="top" nowrap="nowrap">Carrier Location Code</td>
				<td><div id="insuranceCompanyLocationSelect" style="display:block">
						<select id="iclid" name="iclid" type="text" size="1" maxlength="30" value="<?php if(isset($iclid)) echo $iclid;?>" <?php if(isset($insurancelocationdisabled)) echo $insurancelocationdisabled;?> onchange="javascript:submit();" />
						<?php echo $insurancelocationlistoptions; ?>
						</select>
						<?php echo $insuranceCompanyLocationEditButton.$insuranceCompanyLocationNewButton; ?>
					</div>
					<table id="insuranceLocationTable">
						<tr>
							<td> Location</td>
							<td><input id="iclname" name="iclname" type="text" size="30" maxlength="50" disabled="disabled" value="<?php echo $_POST['iclname'] ?>" onchange="upperCase(this.id)" /></td>
						</tr>
						<tr>
							<td> Address 1</td>
							<td><input id="icladdress1" name="icladdress1" type="text" size="30" maxlength="30" disabled="disabled" value="<?php echo $_POST['icladdress1'] ?>" onchange="upperCase(this.id)" /></td>
						</tr>
						<tr>
							<td> Address 2</td>
							<td><input id="icladdress2" name="icladdress2" type="text" size="30" maxlength="30" disabled="disabled" value="<?php echo $_POST['icladdress2'] ?>" onchange="upperCase(this.id)" /></td>
						</tr>
						<tr>
							<td> City </td>
							<td><input id="iclcity" name="iclcity" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo $_POST['iclcity'] ?>" onchange="upperCase(this.id)" />
								State
								<input id="iclstate" name="iclstate" type="text" size="2" maxlength="2" disabled="disabled" value="<?php echo $_POST['iclstate'] ?>" onchange="upperCase(this.id)" />
								Zip
								<input id="iclzip" name="iclzip" type="text" size="10" maxlength="10" disabled="disabled" value="<?php echo $_POST['iclzip'] ?>"  onchange="displayzip(this.id)"/></td>
						</tr>
						<tr>
							<td>Phone</td>
							<td><input id="iclphone" name="iclphone" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo $_POST['iclphone'] ?>" onchange="displayphone(this.id)" />
							</td>
						</tr>
						<tr>
							<td>Fax</td>
							<td><input id="iclfax" name="iclfax" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo $_POST['iclfax'] ?>" onchange="displayphone(this.id)" />
							</td>
						</tr>
						<tr> </tr>
						<tr>
							<td> email </td>
							<td><input id="iclemail" name="iclemail" type="text" size="30" maxlength="64" disabled="disabled" value="<?php echo $_POST['iclemail'] ?>" /></td>
						</tr>
						<tr>
							<td> office hours</td>
							<td><input id="iclofficehours" name="iclofficehours" type="text" size="30" maxlength="50" disabled="disabled" value="<?php echo $_POST['iclofficehours'] ?>"  onchange="upperCase(this.id)"/></td>
						</tr>
					</table>
					<div id="insuranceCompanyLocationAdd" style="display: none">
						<input style="float:left" name="AddInsuranceCompanyLocation" type="submit" value="Add Insurance Company Location" />
						<input style="float:right" type="button" value="Cancel" onclick="insuranceCompanyLocationSelect()" />
					</div>
					<div id="insuranceCompanyLocationUpdate" style="display: none">
						<input style="float:left" name="UpdateInsuranceCompanyLocation" type="submit" value="Update Insurance Company Location" />
						<input style="float:right" type="button" value="Cancel" onclick="insuranceCompanyLocationSelect()" />
					</div></td>
			</tr>
			<tr>
				<td>Note</td>
				<td><textarea name="icnote" cols="60" rows="3"><?php if(isset($icnote)) echo $icnote;?>
</textarea>
				</td>
			</tr>
			<tr>
				<td valign="top">Adjuster Code</td>
				<td><div id="insuranceCompanyAdjusterSelect" style="display:block"><select id="icaid" name="icaid" type="text" size="1" maxlength="30" value="<?php if(isset($icaid)) echo $icaid;?>" <?php if(isset($insurancelocationadjusterdisabled)) echo $insurancelocationadjusterdisabled;?> onchange="javascript:submit();" />
					<?php echo $insurancelocationadjusterlistoptions; ?>
					</select>
						<?php echo $insuranceCompanyAdjusterEditButton.$insuranceCompanyAdjusterNewButton; ?>
					</div>
					<table id="insuranceLocationTable">
						<tr>
							<td>First Name</td>
							<td><input id="icafname" name="icafname" type="text" size="30" maxlength="50" disabled="disabled" value="<?php echo $_POST['icafname'] ?>" onchange="upperCase(this.id)" /></td>
						</tr>
						<tr>
							<td>Last Name</td>
							<td><input id="icalname" name="icalname" type="text" size="30" maxlength="50" disabled="disabled" value="<?php echo $_POST['icalname'] ?>" onchange="upperCase(this.id)" /></td>
						</tr>
						<tr>
							<td> Address 1</td>
							<td><input id="icaaddress1" name="icaaddress1" type="text" size="30" maxlength="30" disabled="disabled" value="<?php echo $_POST['icaaddress1'] ?>" onchange="upperCase(this.id)" /></td>
						</tr>
						<tr>
							<td> Address 2</td>
							<td><input id="icaaddress2" name="icaaddress2" type="text" size="30" maxlength="30" disabled="disabled" value="<?php echo $_POST['icaaddress2'] ?>" onchange="upperCase(this.id)" /></td>
						</tr>
						<tr>
							<td> City </td>
							<td><input id="icacity" name="icacity" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo $_POST['icacity'] ?>" onchange="upperCase(this.id)" />
								State
								<input id="icastate" name="icastate" type="text" size="2" maxlength="2" disabled="disabled" value="<?php echo $_POST['icastate'] ?>" onchange="upperCase(this.id)" />
								Zip
								<input id="icazip" name="icazip" type="text" size="10" maxlength="10" disabled="disabled" value="<?php echo $_POST['icazip'] ?>" onchange="displayzip(this.id)" /></td>
						</tr>
						<tr>
							<td>Phone</td>
							<td><input id="icaphone" name="icaphone" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo $_POST['icaphone'] ?>" onchange="displayphone(this.id)" /></td>
						</tr>
						<tr>
							<td>Fax</td>
							<td><input id="icafax" name="icafax" type="text" size="20" maxlength="20" disabled="disabled" value="<?php echo $_POST['icafax'] ?>" onchange="displayphone(this.id)" /></td>
						</tr>
						<tr> </tr>
						<tr>
							<td> email </td>
							<td><input id="icaemail" name="icaemail" type="text" size="30" maxlength="64" disabled="disabled" value="<?php echo $_POST['icaemail'] ?>" /></td>
						</tr>
						<tr>
							<td> office hours</td>
							<td><input id="icaofficehours" name="icaofficehours" type="text" size="30" maxlength="50" disabled="disabled" value="<?php echo $_POST['icaofficehours'] ?>" /></td>
						</tr>
					</table>
					<div id="insuranceCompanyAdjusterAdd" style="display: none">
						<input style="float:left" name="AddInsuranceCompanyAdjuster" type="submit" value="Add Insurance Company Adjuster" />
						<input style="float:right" type="button" value="Cancel" onclick="insuranceCompanyAdjusterSelect()" />
					</div>
					<div id="insuranceCompanyAdjusterUpdate" style="display: none">
						<input style="float:left" name="UpdateInsuranceCompanyAdjuster" type="submit" value="Update Insurance Company Adjuster" />
						<input style="float:right" type="button" value="Cancel" onclick="insuranceCompanyAdjusterSelect()" />
					</div>
				</td>
			</tr>
			<tr>
			<td>Claim Number
			</td>
			<td><input id="icclaimnumber" name="icclaimnumber" type="text" size="30" maxlength="50" <?php if(isset($insurancelocationdisabled)) echo $insurancelocationdisabled;?> value="<?php echo $_POST['icclaimnumber'] ?>" onchange="uppercase(this.id)" /></td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="cancel" type="button" value="Cancel" onclick="window.close()" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="submitbutton" type="submit" value="<?php echo $buttonvalue; ?>" />
						</div>
					</div>
					<input name="crid" type="hidden" value="<?php echo $crid; ?>"/>
					<input name="icseq" type="hidden" value="<?php echo $icseq; ?>"/>
					<input name="crpnum" type="hidden" value="<?php echo $_POST['crpnum']; ?>">
					<input name="crptosstatus" type="hidden" value="<?php echo $_POST['crptosstatus']; ?>">
					<input name="palname" type="hidden" value="<?php echo $_POST['palname']; ?>">
					<input name="pafname" type="hidden" value="<?php echo $_POST['pafname']; ?>">
					<input name="init" type="hidden" value="1"/></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
