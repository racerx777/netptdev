<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10);
?>
<script language="JavaScript">
	var cal = new CalendarPopup();
</script>


<script type="text/javascript">

var acctype = new Array();
<?php
foreach($_SESSION['useraccess']['patients'] as $key=>$val)
	echo("acctype['".$key."']=".intval($val['acctype'])."; ");
?>

function submitformx() {
	document.editForm.actionbutton.disabled=true;
	document.editForm.submit();
}

function submitform() {
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
		return true;
	}
	return false;
}

function getElementsByClass(node,searchClass,tag) {
	var classElements = new Array();
	var els = node.getElementsByTagName(tag); // use "*" for all elements
	var elsLen = els.length;
	var pattern = new RegExp("\\b"+searchClass+"\\b");
	for (i = 0, j = 0; i < elsLen; i++) {
		 if ( pattern.test(els[i].className) ) {
			 classElements[j] = els[i];
			 j++;
		 }
	}
	return classElements;
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

// If Add/Update Form
if(isset($_SESSION['button'])) {
	if($_SESSION['button'] == "Add")
			$action = 'Insert';
	if($_SESSION['button'] == 'Edit')
			$action = 'Update';
}

// Format Date Ymd
if(isset($_POST['thdate']) && !empty($_POST['thdate']))
	$_POST['thdate'] = date('m/d/Y', strtotime($_POST['thdate']));
else
	$_POST['thdate'] = date('m/d/Y');

if(isset($_POST['thnadate']) && !empty($_POST['thnadate']))
	$_POST['thnadate'] = date('m/d/Y', strtotime($_POST['thnadate']));
else
	$_POST['thnadate'] = date('m/d/Y');

function getAcctType($pnum) {
	unset($acctype);
	$select = "SELECT acctype from PTOS_Patients WHERE pnum='$pnum'";
	if($result=mysqli_query($dbhandle,$select)) {
		if($row=mysqli_fetch_assoc($result)) {
			$acctype=$row['acctype'];
			if($acctype=='15') $acctype='5';
			else if($acctype=='16') $acctype='6';
				else if($acctype=='17') $acctype='6';
					else if($acctype=='18') $acctype='6';
						else if($acctype=='19') $acctype='6';
							else if($acctype=='61') $acctype='61';
								else if($acctype=='62') $acctype='61';
									else if(substr($acctype,0,1)=='2') $acctype='2';
										else if(substr($acctype,0,1)=='3') $acctype='3';
											else if(substr($acctype,0,1)=='4') $acctype='5';
												else if(substr($acctype,0,1)=='5') $acctype='5';
													else if(substr($acctype,0,1)=='6') $acctype='6';
														else if(substr($acctype,0,1)=='7') $acctype='6';
															else if(substr($acctype,0,1)=='8') $acctype='8';
																else if(substr($acctype,0,1)=='9') $acctype='9';
																	else $acctype='??';
		}
	}
	return($acctype);
}

// case types
if(!empty($_POST['thpnum']))
	$_POST['thctmcode']=getAcctType($_POST['thpnum']);


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
else
	$selectedvisittype['ST'] = ' selected ';

// Treatment Init
$displayindividualproceduresblock = array();
$displayproceduresblock = array();
$displaymodalitiesblock = array();
$displaysupplymodalitiesblock = array();
foreach($_SESSION['treatmenttypes'] as $ttkey=>$val) {
	$selectedtreatmenttype["$ttkey"]='';
	$displayindividualproceduresblock["$ttkey"] = ' display:none;';
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
	if(isuserlevel(23) || $_SESSION['user']['umrole'] == 10) {
		if(isset($_POST['individualprocedures'][$_POST['thttmcode']]) && !empty($_POST['individualprocedures'][$_POST['thttmcode']])) {
			foreach($_POST['individualprocedures'][$_POST['thttmcode']] as $pkey=>$pval)
				$checkedindividualprocedures[$_POST['thttmcode']]["$pkey"] = " checked ";
		}
	}
	else {
		if(isset($_POST['procedure'][$_POST['thttmcode']]) && !empty($_POST['procedure'][$_POST['thttmcode']])) {
			foreach($_POST['procedure'][$_POST['thttmcode']] as $pkey=>$pval)
				$checkedprocedure[$_POST['thttmcode']]["$pkey"] = " checked ";
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
}

if( strtotime($_POST['thnadate']) <= strtotime('0000-00-00') )
	$_POST['thnadate']='';

?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Treatment Reporting Edit/Add Entry</legend>
	<form method="post" name="editForm" onsubmit="return submitform();" >
		<label>Clinic
		<select name="thcnum" id="thcnum">
			<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>''), $defaultoption=$_POST['thcnum'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
		</select>
		</label>
		<br />
		<?php if(isuserlevel(23)) { ?>
			<label>Number
			<input type="text" name="thpnum" id="thpnum" value="<?php echo $_POST['thpnum']; ?>" size="8" maxlength="6">
			</label>
		<?php } ?>
		<label for="thdate">Treatment Date
		<input type="text" name="thdate" id="thdate" value="<?php echo $_POST['thdate']; ?>" size="12" maxlength="12">
		</label>
		<label for="thlname">Last Name
		<input name="thlname" type="text" value="<?php echo $_POST['thlname']; ?>" size="10" maxlength="30">
		</label>
		<label for="thfname">First Name
		<input name="thfname" type="text" value="<?php echo $_POST['thfname']; ?>" size="10" maxlength="30">
		</label>
		<label for="thctmcode">Case Type <?php echo $_POST['thctmcode']; ?>
		<input id="thctmcode" name="thctmcode" type="hidden" value="<?php if(isset($_POST['thctmcode'])) echo $_POST['thctmcode'];  ?>" />
		</label>
		<label for="thvtmcode">Visit Type
		<select name="thvtmcode" size="1">
			<option label=""></option>
			<?php
			foreach($_SESSION['visittypes'] as $key=>$val)
				echo "<option " . $selectedvisittype[$key] . " value='" . $key . "'>" . $_SESSION['visittypes'][$key] . "</option>";
			?>
		</select>
		</label>
		<label for="thttmcode">Treatment
		<select name="thttmcode" size="1" onchange="displayProceduresAndModalities(this.value);" >
			<option label=""></option>
			<?php
			foreach($_SESSION['treatmenttypes'] as $key=>$val)
				echo "<option " . $selectedtreatmenttype[$key] . " value='" . $key . "'>" . $_SESSION['treatmenttypes'][$key] . "</option>";
			?>
		</select>
		</label>
<input id="thnadate" name="thnadate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['thnadate'])) echo $_POST['thnadate']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="thnadate1" id="thnadate1" src="/img/calendar.gif" onclick="cal.select(document.forms['editForm'].thnadate,'thnadate1','MM/dd/yyyy'); return false;" />		<br />
		<?php
if(isuserlevel(23) || $_SESSION['user']['umrole'] == 10) {
	foreach($_SESSION['treatmenttypes'] as $ttkey=>$ttval) {
		if(count($_SESSION['individualprocedures']["$ttkey"]) > 0) {
?>
		<fieldset id="individualprocedureDiv<?php echo $ttkey ?>" style="margin:10px; float:left;<?php echo $displayproceduresblock["$ttkey"]?>">
		<legend>Individual Procedures:</legend>
		<div>
			<?php
			foreach($_SESSION['individualprocedures']["$ttkey"] as $ipkey=>$ipval) {
				if($ipkey != 'ISO'){ ;
?>
			<div>
				<p id="<?php echo $ttkey; ?>_<?php echo $ipkey; ?>_error" style="color:red;"></p>
				<label for="individualprocedure[<?php echo $ttkey; ?>]">
				<input class="individualprocedureClass" name="individualprocedures[<?php echo $ttkey; ?>][<?php echo $ipkey; ?>]" id="individualprocedures[<?php echo $ttkey; ?>][<?php echo $ipkey; ?>]" type="checkbox" value="<?php echo $ipkey; ?>" <?php echo $checkedindividualprocedures["$ttkey"]["$ipkey"]; ?> >
				<?php echo $ipval ?></label>
				<select name="proceduresSelect[<?php echo $ipkey; ?>]" id="<?php echo $ttkey; ?>_<?php echo $ipkey; ?>" >
					<?php
					  for ($i=0; $i < 6 ; $i++) { 
					  	if($_POST['individualprocedures']["$ttkey"]["$ipkey"] == $i){
							echo "<option value='".$i."' selected>".$i."</option>";
					  	}else{
					  		echo "<option value='".$i."'>".$i."</option>";
					  	}
					  }
					?>
				</select>
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
else {
	foreach($_SESSION['treatmenttypes'] as $ttkey=>$ttval) {
		if(count($_SESSION['procedures']["$ttkey"]) > 1) {
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
	if(count($_SESSION['modalities']["$ttkey"]) > 1) {
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
		<div style="clear:both;">
			<div style="float:left;">
				<input name="button[]" type="submit" value="Cancel/Return" />
			</div>
			<div style="float:right;">
				<input id="actionbutton" name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="<?php echo $action; ?>" />
			</div>
		</div>
	</form>
	</fieldset>
</div>
