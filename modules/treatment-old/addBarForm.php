<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10);
//dumppost();
?>
<script type="text/javascript">

var acctype = new Array();
<?php
foreach($_SESSION['useraccess']['patients'] as $key=>$val)
	echo("acctype['".$key."']=".intval($val['acctype'])."; ");
?>

function checkfield(){
	if(document.activeElement.getAttribute('value')=='Edit' || document.activeElement.getAttribute('value')=='Delete'){
		return true;
	}
	var err = 0;
	var e = document.getElementById("PT_BIO");
	var result = e.options[e.selectedIndex].value;
	if(document.getElementById('individualprocedures[PT][BIO]').checked && result == 0){
		document.getElementById("PT_BIO_error").innerHTML = "Please select a quantity for the procedure selected.";
		err1 = 1;
	}else{
		document.getElementById("PT_BIO_error").innerHTML = "";
		err1 = 0;
	}

	var e = document.getElementById("PT_MYO");
	var result = e.options[e.selectedIndex].value;
	if(document.getElementById('individualprocedures[PT][MYO]').checked && result == 0){
		document.getElementById("PT_MYO_error").innerHTML = "Please select a quantity for the procedure selected.";
		err2 = 1;
	}else{
		document.getElementById("PT_MYO_error").innerHTML = "";
		err2 = 0;
	}

	var e = document.getElementById("PT_NEU");
	var result = e.options[e.selectedIndex].value;
	if(document.getElementById('individualprocedures[PT][NEU]').checked && result == 0){
		document.getElementById("PT_NEU_error").innerHTML = "Please select a quantity for the procedure selected.";
		err3 = 1;
	}else{
		document.getElementById("PT_NEU_error").innerHTML = "";
		err3 = 0;
	}

	var e = document.getElementById("PT_TE");
	var result = e.options[e.selectedIndex].value;
	if(document.getElementById('individualprocedures[PT][TE]').checked && result == 0){
		document.getElementById("PT_TE_error").innerHTML = "Please select a quantity for the procedure selected.";
		err4 = 1;
	}else{
		document.getElementById("PT_TE_error").innerHTML = "";
		err4 = 0;
	}
	
	if(!err1 && !err2 && !err3 && !err4){
		return true
	}
	document.addForm.actionbutton.value='Add';
	return false;
}

function onsubmitfunction() {
	if(document.addForm.actionbutton.value=='Add') {
		document.addForm.actionbutton.value='Please wait adding...';
		var hiddenField = document.addForm.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", 'button[]');
        hiddenField.setAttribute("value", 'Add');
		document.addForm.submit();
	}
	return true;
}
function updatePatientInformation(thpnum) {
	var obj = document.getElementById(thpnum);
	if(obj!=null) {
		if(obj.value == '') {
			document.addForm.thlname.disabled = false;
			document.addForm.thfname.disabled = false;
		}
		else {
			document.addForm.thlname.value = "";
			document.addForm.thfname.value = "";
			document.addForm.thlname.disabled = true;
			document.addForm.thfname.disabled = true;
			document.addForm.thctmcode.value = acctype[obj.value];
		}
	}
}

function setDisplay(objId, sDisplay) {
	var obj = document.getElementById(objId);
	if(obj!=null) obj.style.display = sDisplay;
}

function setVisibility(objId, sVisibility) {
	var obj = document.getElementById(objId);
	if(obj!=null) obj.style.visibility = sVisibility;
}


function displayProceduresAndModalities(consulttype){
	setDisplay("individualprocedureDivPT", "none");
	setDisplay("individualprocedureDivOT", "none");
	setDisplay("individualprocedureDivA", "none");
	setDisplay("individualprocedureDivP", "none");
	setDisplay("procedureDivPT", "none");
	setDisplay("procedureDivOT", "none");
	setDisplay("procedureDivA", "none");
	setDisplay("procedureDivP", "none");
	setDisplay("modalityDivPT", "none");
	setDisplay("modalityDivOT", "none");
	setDisplay("modalityDivA", "none");
	setDisplay("modalityDivP", "none");
	setDisplay("supplyModalityDivPT", "none");
	setDisplay("supplyModalityDivOT", "none");
	setDisplay("supplyModalityDivA", "none");
	setDisplay("supplyModalityDivP", "none");

	if(consulttype=="PT") {
		setDisplay("individualprocedureDivPT", "block");
		setDisplay("procedureDivPT", "block");
		setDisplay("modalityDivPT", "block");
		setDisplay("supplyModalityDivPT", "block");
	}
	if(consulttype=="OT") {
		setDisplay("individualprocedureDivOT", "block");
		setDisplay("procedureDivOT", "block");
		setDisplay("modalityDivOT", "block");
		setDisplay("supplyModalityDivPT", "block");
	}
	if(consulttype=="A") {
//		setDisplay("procedureDivA", "block");
//		setDisplay("modalityDivA", "block");
//		setDisplay("supplyModalityDivPT", "block");
	}
	if(consulttype=="P") {
//		setDisplay("procedureDivP", "block");
//		setDisplay("modalityDivP", "block");
//		setDisplay("supplyModalityDivPT", "block");
	}
}

function consulttypechange(consulttype) {
	disablemodalities(consulttype);
	settreatmentlist(consulttype);
	setmodalitylist(consulttype);
	setsupplymodalitylist(consulttype);
}
</script>
<?php
// If Reset Add Form
if($_SESSION['button'] == 'Reset Add') {
	foreach($_POST as $key=>$val)
		if(substr($key,0,2)=='th') {
			unset($_POST[$key]);
		}
	unset($_SESSION['button']);
}

// Format Date Ymd
if(isset($_POST['thdate']) && !empty($_POST['thdate']))
	$_POST['thdate'] = date('m/d/Y', strtotime($_POST['thdate']));
//else
//	$_POST['thdate'] = date('m/d/Y');
if(!empty($_POST['thpnum'])) {
	$namedisabled = 'disabled="disabled"';
}
else
	$namedisabled = '';

if(!empty($_POST['thpnum']))
	$_POST['thctmcode']=$_SESSION['useraccess']['patients'][$_POST['thpnum']]['acctype'];
else
	$_POST['thctmcode']='??';

// case types
foreach($_SESSION['casetypes'] as $key=>$val)
	$selectedcasetype[$key]='';
if(isset($_POST['thctmcode']) && !empty($_POST['thctmcode']))
	$selectedcasetype[$_POST['thctmcode']] = ' selected ';
//else
//	$selectedcasetype['6'] = ' selected ';

// Consultation Type
foreach($_SESSION['visittypes'] as $key=>$val)
	$selectedvisittype[$key]='';
if(isset($_POST['thvtmcode']) && !empty($_POST['thvtmcode']))
	$selectedvisittype[$_POST['thvtmcode']] = ' selected ';
//else
//	$selectedvisittype['ST'] = ' selected ';

// Treatment Init
$displayindividualproceduresblock = array();
$displayproceduresblock = array();
$displaymodalitiesblock = array();
$displaysupplymodalitiesblock = array();
foreach($_SESSION['treatmenttypes'] as $ttkey=>$val) {
	$selectedtreatmenttype["$ttkey"]='';
	if($ttkey == 'PT' && isset($_POST['individualprocedures'])){
		$displayindividualproceduresblock["$ttkey"] = ' display:block;';
	}else{
		$displayindividualproceduresblock["$ttkey"] = ' display:none;';
	}
	$displayproceduresblock["$ttkey"] = ' display:none;';
	$displaymodalitiesblock["$ttkey"] = ' display:none;';
	$displaysupplymodalitiesblock["$ttkey"] = ' display:none;';
	unset($checkedprocedure);
	unset($checkedmodalities);
	unset($checkedsupplymodalities);
}

// Set Treatment Type
if(isset($_POST['thttmcode']) && !empty($_POST['thttmcode'])) {
	$selectedtreatmenttype[$_POST['thttmcode']] = ' selected ';
	if(($_POST['thttmcode'] != 'P') && ($_POST['thttmcode']!='A')) {
		$displaymodalitiesblock[$_POST['thttmcode']] = ' display:block;';
	}
	if(($_POST['thttmcode'] != 'P') && ($_POST['thttmcode']!='A')) {
		$displayproceduresblock[$_POST['thttmcode']] = ' display:block;';
	}

	if(($_POST['thttmcode'] != 'P') && ($_POST['thttmcode']!='A')) {
		$displaysupplymodalitiesblock[$_POST['thttmcode']] = ' display:block;';
	}
// Set Checked Procedure
	if(isset($_POST['procedure'][$_POST['thttmcode']]) && !empty($_POST['procedure'][$_POST['thttmcode']])) {
		foreach($_POST['procedure'] as $pkey=>$pval) {
				$checkedprocedure[$_POST['thttmcode']]["$pval"] = " checked ";
		}
	}
// Set Checked modalities
	if(isset($_POST['modalities'][$_POST['thttmcode']]) && !empty($_POST['modalities'][$_POST['thttmcode']])) {
		foreach($_POST['modalities'][$_POST['thttmcode']] as $mkey=>$mval)
			$checkedmodalities[$_POST['thttmcode']]["$mkey"] = ' checked ';
	}

// Set Checked supply modalities
	if(isset($_POST['supplymodalities'][$_POST['thttmcode']]) && !empty($_POST['supplymodalities'][$_POST['thttmcode']])) {
		foreach($_POST['supplymodalities'][$_POST['thttmcode']] as $mkey=>$mval)
			$checkedsupplymodalities[$_POST['thttmcode']]["$mkey"] = ' checked ';
	}
	// Set Checked Procedure
	if(isset($_POST['individualprocedures'][$_POST['thttmcode']]) && !empty($_POST['individualprocedures'][$_POST['thttmcode']])) {
		foreach($_POST['individualprocedures'][$_POST['thttmcode']] as $pkey=>$pval) {
				$checkedindividualprocedures[$_POST['thttmcode']]["$pval"] = " checked ";
		}
	}
}


// showUserClinicSelect("thcnum", 0, 1);
// showUserPatientSelect($name="thpnum", $showbutton = FALSE, $onchange="updatePatientInformation(this.id);", $boundcolumnvalue=$_POST['thcnum'], $formatoption="AddBlankOption");
?>

<div class="containedBox" id="addBarForm">
	<fieldset>
	<legend class="boldLarger">Add Treatment Information</legend>
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
			<tr>
				<th>Clinic</th>
				<th>Treatment Date</th>
				<?php if(getUser() != 'NancyB') : ?>
					<th>Existing Patient</th>
				<?php endif; ?>
				<th colspan="2">New Patient Last Name Then First Name</th>
				<th>Visit Type</th>
				<th>Treatment Type</th>
				<th>Next Action Date</th>
			</tr>
			<tr>
				<td><select name="thcnum" id="thcnum">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$_POST['thcnum'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="thdate" name="thdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['thdate'])) echo $_POST['thdate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.forms['addForm'].thdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
				<?php if(getUser() != 'NancyB') : ?>
				<td><select name="thpnum" id="thpnum" onchange="updatePatientInformation(this.id)">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['patients'], $optionvaluefield='pnum', $arrayofoptionfields=array('lname'=>', ', 'fname'=>' (', 'pnum'=>') ', 'cnum'=>''), $defaultoption=$_POST['thpnum'], $addblankoption=TRUE, $arraykey="", $arrayofmatchvalues=array()); ?>
					</select></td>
				<?php endif; ?>
				<td><input id="thlname" name="thlname" type="text" size="10" maxlength="30" value="<?php if(isset($_POST['thlname'])) echo $_POST['thlname'];  ?>" onchange="upperCase(this.id)" <?php echo $namedisabled;?>></td>
				<td><input id="thfname" name="thfname" type="text" size="10" maxlength="30" value="<?php if(isset($_POST['thfname'])) echo $_POST['thfname'];  ?>" onchange="upperCase(this.id)" <?php echo $namedisabled;?>><input id="thctmcode" name="thctmcode" type="hidden" value="<?php if(isset($_POST['thctmcode'])) echo $_POST['thctmcode'];  ?>" ></td>
				
				<td><select name="thvtmcode" size="1">
						<option label=""></option>
						<?php
						foreach($_SESSION['visittypes'] as $key=>$val)
							echo "<option " . $selectedvisittype[$key] . " value='" . $key . "'>" . $_SESSION['visittypes'][$key] . "</option>";
					?>
					</select></td>
				<td><select name="thttmcode" size="1" onchange="displayProceduresAndModalities(this.value);">
						<option label=""></option>
						<?php
						foreach($_SESSION['treatmenttypes'] as $key=>$val)
							echo "<option " . $selectedtreatmenttype[$key] . " value='" . $key . "'>" . $_SESSION['treatmenttypes'][$key] . "</option>";
					?>
					</select></td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="thnadate" name="thnadate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['thnadate'])) echo $_POST['thnadate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="thnadate1" id="thnadate1" src="/img/calendar.gif" onclick="cal.select(document.forms['addForm'].thnadate,'thnadate1','MM/dd/yyyy'); return false;" /></td>
			</tr>
		</table>
		<?php

if(isuserlevel(23) || $_SESSION['user']['umrole'] == 10) {
	foreach($_SESSION['treatmenttypes'] as $ttkey=>$ttval) {
		if(count($_SESSION['individualprocedures']["$ttkey"]) > 0  ) {
?>
		<fieldset id="individualprocedureDiv<?php echo $ttkey ?>" style="margin:10px; float:left;<?php echo $displayindividualproceduresblock["$ttkey"]?>">
		<legend>Procedures:</legend>
		<div>
			<?php
			foreach($_SESSION['individualprocedures']["$ttkey"] as $ipkey=>$ipval) {
				if($ipkey != 'ISO'){ 
?>
			<div>
				<p id="<?php echo $ttkey; ?>_<?php echo $ipkey; ?>_error" style="color:red;"></p>
				<label for="individualprocedure[<?php echo $ttkey; ?>]">
				<input class="individualprocedureClass" name="individualprocedures[<?php echo $ttkey; ?>][<?php echo $ipkey; ?>]" type="checkbox" value="<?php echo $ipkey; ?>" id="individualprocedures[<?php echo $ttkey; ?>][<?php echo $ipkey; ?>]" <?php echo $checkedindividualprocedures["$ttkey"]["$ipkey"]; ?> >
				<?php echo $ipval ?>
				<select name="proceduresSelect[<?php echo $ipkey; ?>]" id="<?php echo $ttkey; ?>_<?php echo $ipkey; ?>" >
					<?php
					  for ($i=0; $i < 6 ; $i++) { 
						echo "<option value='".$i."'>".$i."</option>";
					  }
					?>
				</select>
			    </label>
			</div>
			<?php
	}}
?>
		</div>
		</fieldset>
		<?php
		}
	}
}
if($_SESSION['user']['umrole'] != 10) {
	foreach($_SESSION['treatmenttypes'] as $ttkey=>$ttval) {
	    //dump('treatment', $_SESSION['procedures']);
		if(count($_SESSION['procedures']["$ttkey"]) > 0) {
	?>
			<fieldset id="procedureDiv<?php echo $ttkey ?>" style="margin:10px; float:left;<?php echo $displayproceduresblock["$ttkey"]?>">
			<legend>Procedures (select one):</legend>
			<div>
				<?php
			foreach($_SESSION['procedures']["$ttkey"] as $pkey=>$pval) {
	?>
				<div>
					<label for="procedure[<?php echo $ttkey; ?>]">
					<input class="procedureClass" id="procedure_<?php echo $pkey?>" name="procedure[<?php echo $ttkey; ?>]" type="radio" value="<?php echo $pkey; ?>" <?php echo $checkedprocedure["$ttkey"]["$pkey"]; ?> >
					<?php echo $pval ?></label>
				</div>
				<?php
		}
	?>
			</div>
			</fieldset>
			<?php
		}
	}
}
foreach($_SESSION['treatmenttypes'] as $ttkey=>$ttval) {
	if(count($_SESSION['modalities']["$ttkey"]) > 0) {
?>
		<fieldset id="modalityDiv<?php echo $ttkey ?>" style="margin:10px; float:left;<?php echo $displaymodalitiesblock["$ttkey"]?>">
		<legend>Modalities: (check all that apply):</legend>
		<div>
			<?php
		foreach($_SESSION['modalities']["$ttkey"] as $mkey=>$mval) {
			if( ($_POST['thctmcode']=='61' or $_POST['thctmcode']=='62') and $mval=='Ice Compression') {
				// skip ice compression
?>
			<div>
				<label for="modalities[<?php echo $ttkey; ?>][<?php echo $mkey; ?>]" style="color:gray;" >
				<input class="ModalityClass" name="modalities[<?php echo $ttkey; ?>][<?php echo $mkey; ?>]" type="checkbox" value="" readonly="readonly" disabled="disabled" >
				<?php echo $mval ?> </label>
			</div>
<?php
			}
			else {
?>
			<div>
				<label for="modalities[<?php echo $ttkey; ?>][<?php echo $mkey; ?>]">
				<input class="ModalityClass" name="modalities[<?php echo $ttkey; ?>][<?php echo $mkey; ?>]" type="checkbox" value="<?php echo $mkey; ?>"<?php  echo $checkedmodalities["$ttkey"]["$mkey"]; ?>>
				<?php echo $mval ?> </label>
			</div>
			<?php
			}
		}


?>
		</div>
		</fieldset>
		<?php
	}
}
foreach($_SESSION['treatmenttypes'] as $ttkey=>$ttval) {
	if(count($_SESSION['supplymodalities']["$ttkey"]) > 1) {
?>
		<fieldset id="supplyModalityDiv<?php echo $ttkey ?>" style="margin:10px; float:left;<?php echo $displaysupplymodalitiesblock["$ttkey"]?>">
		<legend>Misc. Modalities: (check all that apply):</legend>
		<div>
			<?php
		foreach($_SESSION['supplymodalities']["$ttkey"] as $mkey=>$mval) {
?>
			<div>
				<label for="supplymodalities[<?php echo $ttkey; ?>][<?php echo $mkey; ?>]">
				<input class="SupplyModalityClass" name="supplymodalities[<?php echo $ttkey; ?>][<?php echo $mkey; ?>]" type="checkbox" value="<?php echo $mkey; ?>"<?php  echo $checkedsupplymodalities["$ttkey"]["$mkey"]; ?>>
				<?php echo $mval ?> </label>
			</div>
			<?php
		}
?>
		</div>
		</fieldset>
		<?php
	}
}
?>
		<div style="clear:both; margin:10px;">
			<div style="float:left">
				<input name="button[]" type="submit" value="Reset Add" />
			</div>
			<div style="float:right">
				<input id="actionbutton" name="button[]" type="submit" value="Add" onclick="javascript:onsubmitfunction();" />
			</div>
		</div>
	</fieldset>
</div>
