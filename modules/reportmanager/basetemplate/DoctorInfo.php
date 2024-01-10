<script>
<!--  
function saveSelectOptions(formname, selectname, savename) {
	var savedSelectOptions = document.createElement('select');
	savedSelectOptions.id=savename;
//	savedSelectOptions.name=savename;
	savedSelectOptions.style.display='none';
	var oSelect = document.getElementById(selectname);
	for (i=0; i < oSelect.options.length; i++) {
		var newOption = document.createElement('option');
		newOption.text=oSelect.options[i].text;
		newOption.value=oSelect.options[i].value;
		try {
			savedSelectOptions.add(newOption, null); // standards compliant; doesn't work in IE
		}
		catch(ex) {
			savedSelectOptions.add(newOption); // IE only
		}
	}
	document.forms[formname].appendChild(savedSelectOptions);
}

function multisearch(text, matchstring) {
	var matcharray=matchstring.split(' ');
	for(m=0; m<matcharray.length; m++) {
		var pattern=RegEscape(matcharray[m]);
		if(text.search(pattern) == -1) {
			return(false)
		}
	}
	return(true);
}

function combotext_search(oText, oHidden, oSelect, sSelect, toFindMultiple) {
	oSelect.style.display='none';
	oSelect.length=0;
	for (i=0; i < sSelect.options.length; i++) {
		sSelectOptionText = sSelect.options[i].text.toUpperCase();
		if(multisearch(sSelectOptionText, toFindMultiple) ) {
			var newOption = document.createElement('option');
			newOption.text=sSelect.options[i].text;
			newOption.value=sSelect.options[i].value;
			try {
				oSelect.add(newOption, null); // standards compliant; doesn't work in IE
			}
			catch(ex) {
				oSelect.add(newOption); // IE only
			}
		}
	} // for

	if(oSelect.length>0) {
		if(oSelect.length >20)
			oSelect.size=20;
		else
			oSelect.size=oSelect.length+1;
		oSelect.style.display='block';
	}
}

function combotext_onkeydown(e, oText, oHidden, oSelect, sSelect) {
	if(e.keyCode) 
		keyCode = e.keyCode;
	else {
		keyCode = e;
//		oText.value='';
	}
	if (keyCode == 27) {
		oSelect.style.display='none';
		oText.value='';
		oText.focus();
	}
	else {

	// 40=down arrow 38=up arrow move up/down in the oSelect box
		if (keyCode == 40 || keyCode == 38) {
			if(oSelect.length>0) {
				if(oText.value.length==0)
					combotext_search(oText, oHidden, oSelect, sSelect, '');
				oSelect.style.display = 'block';
				oSelect.focus();
				comboselect_onchange(oText, oHidden, oSelect, sSelect);
			}
		}
	// 13=Return - If no item selected in oSelect, then select nearest match, If item is selected in oSelect then submit.
		else if (keyCode == 13) {
//			e.cancelBubble = true;
//			if (e.returnValue) 
				e.returnValue = false;
//			if (e.stopPropagation) 
//				e.stopPropagation();
//			comboselect_onchange(oText, oHidden, oSelect, sSelect);
//			oSelect.style.display='none';
//			oText.focus();
			return false;
		}
// 9=Tab
		else if(keyCode == 9) 
			return true;
		else { //alert(keyCode);
			if(keyCode==8) {
				var selectedTextLength=GetSelectedTextLength();
				if(selectedTextLength<1) {
					selectedTextLength=1;					
				}
				var upper=oText.value.toUpperCase();
				toFind = upper.substring(0,upper.length-selectedTextLength);
			}
			else {
				var c = String.fromCharCode(keyCode);
				c = c.toUpperCase();
				toFind = oText.value.toUpperCase() + c;
			}
			combotext_search(oText, oHidden, oSelect, sSelect, toFind);
		}
	}
}

function GetSelectedTextLength () {
	if (window.getSelection) {  // all browsers, except IE before version 9
		var range = window.getSelection ();
//		alert (range.toString ());
	} 
	else {
		if (document.selection.createRange) { // Internet Explorer
			var range = document.selection.createRange ();
//			alert (range.text);
		}
	}
	var rangestring=range.toString();
	var rangestringlength=rangestring.length;
	return(rangestringlength);
}

function comboselect_onchange(oText, oHidden, oSelect, sSelect) {
	if(oSelect.selectedIndex == -1)
		oSelect.selectedIndex=0;
	oText.value = oSelect.options[oSelect.selectedIndex].text;
	oHidden.value=oSelect.options[oSelect.selectedIndex].value;
}

function comboselect_onkeydown(keyCode, oText, oHidden, oSelect, sSelect) {
	if (keyCode == 8) {
		oSelect.style.display='none';
		oText.focus();
	}
}

function comboselect_onkeyup(keyCode, oText, oHidden, oSelect, sSelect){
	if (keyCode == 8) {
		return(false);
	}
	if (keyCode == 27) {
		oSelect.style.display='none';
		oText.focus();
	}
	if (keyCode == 13) {
		comboselect_onchange(oText, oHidden, oSelect, sSelect);
		oSelect.style.display='none';
		oText.focus();
	}
}

function gotoField(e, oText, oHidden, oSelect, sSelect) {
oText.focus();
combotext_onkeydown(e, oText, oHidden, oSelect, sSelect)
}

function RegEscape(text) {
    return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
}

// -->  
</script>
<?php
// type of injury WC/PI
$casetypehtml=caseTypeOptions();
$casetypehtml = getSelectOptions($arrayofarrayitems=$casetypehtml, $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$report['header']['rhcasetypecode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());
$therapytypehtml=therapyTypeOptions();
$therapytypehtml = getSelectOptions($arrayofarrayitems=$therapytypehtml, $optionvaluefield='value', $arrayofoptionfields=array('title'=>' (', 'value'=>')'), $defaultoption=$report['header']['rhtherapytypecode'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());

// ICD9 Bodypart stuff

$icd9codearray = icd9CodeOptions(0,'Proper'); // contains icd9, injurytype, bodypart, descriptor
$bodypartcodearray = bodypartCodeOptions(0,'Proper'); // contains code, description, inactive, parent, shortdescription
$bodypartdescriptorcodearray = bodypartdescriptorCodeOptions(0,'Proper'); // contains code, description, inactive, parent, shortdescription

$endscript='
<script>
saveSelectOptions(\'SoapForm\',\'icd9_select_1\',\'icd9_select_saved\');
saveSelectOptions(\'SoapForm\',\'bp_select_1\',\'bp_select_saved\');
saveSelectOptions(\'SoapForm\',\'bpd_select_1\',\'bpd_select_saved\');
</script>
';

$reporticd9code=array();
$dxhtml=array();
for($i=1; $i<=4; $i++) {
	$dxlabel="Dx $i:";
// ICD9
	$reporticd9codefield='rhicd9code'.$i;
	$reporticd9descfield='rhicd9desc'.$i;
	$reporticd9code=$_POST['report']['header']["$reporticd9codefield"];
	$reporticd9desc=$_POST['report']['header']["$reporticd9descfield"];
	if(!empty($reporticd9code))
		$hfvalue=' value="'.$reporticd9code.'"';
	else
		$hfvalue='';

// If no Body Part Description and there is a code, look it up
	if(empty($reporticd9desc) && !empty($reporticd9code) ) 
		$reporticd9desc=$icd9codearray["$reporticd9code"]['description'];

	if(!empty($reporticd9desc))
		$tfvalue=' value="'.$reporticd9desc.'"';
	else
		$tfvalue='value=""';

	$hfn='report[header]['.$reporticd9codefield.']';
	$tfn='report[header]['.$reporticd9descfield.']';
	$hf=$reporticd9codefield;
	$tf=$reporticd9descfield;

	$sf="icd9_select_".$i;
	$ss="icd9_select_saved";
	$icd9hidden='
<input type="hidden" id="'.$hf.'" name="'.$hfn.'"'.$hfvalue.' readonly="readonly" size="8" onClick="javascript:gotoField(40, this.form.'.$hf.', this.form.'.$hf.', this.form.'.$sf.', this.form.'.$ss.');" />';
	$icd9text='
<input type="text" id="'.$tf.'" name="'.$tfn.'" size="70" autocomplete="off" style="z-index:1;" '.$tfvalue.' 
	onkeydown="combotext_onkeydown(event, this.form.'.$tf.', this.form.'.$hf.', this.form.'.$sf.', this.form.'.$ss.')" />';

	$icd9options = getSelectOptions($arrayofarrayitems=$icd9codearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>''), $defaultoption=$reporticd9code, $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());
	$pos=($i-1)*60+30;

	$icd9select='
<select id="'.$sf.'" style="display:none; position:absolute; top:'.$pos.'px; left:100px; z-index:999;" 
	onblur="this.style.display=\'none\'" 
	onchange="comboselect_onchange(this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')" 
	onkeydown="comboselect_onkeydown(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
	onkeyup="comboselect_onkeyup(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
	>'.$icd9options.'</select>
	';
	$icd9select='<select id="'.$sf.'" style="display:none; position:absolute; z-index:999;" 
	onblur="this.style.display=\'none\'" 
	onchange="comboselect_onchange(this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')" 
	onkeydown="comboselect_onkeydown(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
	onkeyup="comboselect_onkeyup(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
	>'.$icd9options.'</select>
	';
// Descriptor
	$reportbpdcodefield='rhicd9dxbodydescriptor'.$i;
	$reportbpddescfield='rhicd9dxbodydescriptordesc'.$i; // needs lookup
	$reportbpdcode=$_POST['report']['header']["$reportbpdcodefield"];
	$reportbpddesc=$_POST['report']['header']["$reportbpddescfield"];
	if(!empty($reportbpdcode))
		$hfvalue=' value="'.$reportbpdcode.'"';
	else
		$hfvalue='';

// If no Body Part Description and there is a code, look it up
	if(empty($reportbpddesc) && !empty($reportbpdcode) ) 
		$reportbpddesc=$bodypartdescriptorcodearray["$reportbpdcode"]['description'];

	if(!empty($reportbpddesc))
		$tfvalue=' value="'.$reportbpddesc.'"';
	else
		$tfvalue='';
	$hfn='report[header]['.$reportbpdcodefield.']';
	$tfn='report[header]['.$reportbpddescfield.']';
	$hf=$reportbpdcodefield;
	$tf=$reportbpddescfield;
	$sf="bpd_select_".$i;
	$ss="bpd_select_saved";

	$bodypartdescriptorhidden='<input type="hidden" id="'.$hf.'" name="'.$hfn.'"'.$hfvalue.' size="3" />';

	$bodypartdescriptortext='
		<input type="text" id="'.$tf.'" name="'.$tfn.'" size="12" autocomplete="off" style="z-index:1;" '.$tfvalue.'
	onkeydown="combotext_onkeydown(event, this.form.'.$tf.', this.form.'.$hf.', this.form.'.$sf.', this.form.'.$ss.')" />';

	$bodypartdescriptoroptions = getSelectOptions($arrayofarrayitems=$bodypartdescriptorcodearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>''), $defaultoption=$reportbpdcode, $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());

	$pos=$pos+30;

	$bodypartdescriptorselect='<select id="'.$sf.'" style="display:none; position:absolute; top:'.$pos.'px; left:660px; z-index:999;" 
	onblur="this.style.display=\'none\'" 
	onchange="comboselect_onchange(this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')" 
	onkeydown="comboselect_onkeydown(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
	onkeyup="comboselect_onkeyup(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
	>'.$bodypartdescriptoroptions.'</select>';
	$bodypartdescriptorselect='<select id="'.$sf.'" style="display:none; position:absolute; z-index:999;" 
	onblur="this.style.display=\'none\'" 
	onchange="comboselect_onchange(this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')" 
	onkeydown="comboselect_onkeydown(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
	onkeyup="comboselect_onkeyup(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
	>'.$bodypartdescriptoroptions.'</select>';
// Body Part
	$bplabel="Body:";
		$reportbpcodefield='rhicd9dxbodypart'.$i;
		$reportbpdescfield='rhicd9dxbodypartdesc'.$i; // needs lookup
		$reportbpcode=$_POST['report']['header']["$reportbpcodefield"];
		$reportbpdesc=$_POST['report']['header']["$reportbpdescfield"];
		if(!empty($reportbpcode))
			$hfvalue=' value="'.$reportbpcode.'"';
		else
			$hfvalue='';

	// If no Body Part Description and there is a code, look it up
		if(empty($reportbpdesc) && !empty($reportbpcode) ) {
			$reportbpdesc=$bodypartcodearray["$reportbpcode"]['description'];
		}

		if(!empty($reportbpdesc)) 
			$tfvalue=' value="'.$reportbpdesc.'"';
		else {
			$tfvalue='';
		}

		$hfn='report[header]['.$reportbpcodefield.']';
		$tfn='report[header]['.$reportbpdescfield.']';
		$hf=$reportbpcodefield;
		$tf=$reportbpdescfield;
		$sf="bp_select_".$i;
		$ss="bp_select_saved";

		$bodyparthidden='<input type="hidden" id="'.$hf.'" name="'.$hfn.'"'.$hfvalue.' size="4" />';
		$bodyparttext='
			<input type="text" id="'.$tf.'" name="'.$tfn.'" size="50" autocomplete="off" style="z-index:1;" '.$tfvalue.'
		onkeydown="combotext_onkeydown(event, this.form.'.$tf.', this.form.'.$hf.', this.form.'.$sf.', this.form.'.$ss.')" />';

		$bodypartoptions = getSelectOptions($arrayofarrayitems=$bodypartcodearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>''), $defaultoption=$reportbpcode, $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());

		$pos=$pos+30;

		$bodypartselect='<select id="'.$sf.'" style="display:none; position:absolute; top:'.$pos.'px; left:100px; z-index:999;" 
		onblur="this.style.display=\'none\'" 
		onchange="comboselect_onchange(this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')" 
		onkeydown="comboselect_onkeydown(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
		onkeyup="comboselect_onkeyup(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
		>'.$bodypartoptions.'</select>';
		$bodypartselect='<select id="'.$sf.'" style="display:none; position:absolute; z-index:999;" 
		onblur="this.style.display=\'none\'" 
		onchange="comboselect_onchange(this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')" 
		onkeydown="comboselect_onkeydown(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
		onkeyup="comboselect_onkeyup(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
		>'.$bodypartoptions.'</select>';
// Create HTML for ICD9, Bodypart and Descriptor
		$dxhtml["$i"]='
		<div id="dx_'.$i.'">
			<table cellpadding="5" cellspacing="0">
				<tr>
					<td>'.$dxlabel.'</td>
					<td colspan="2">'.$icd9text.$icd9select.'</td>
					<td>'.$icd9hidden.'</td>
				</tr>
				<tr>
					<td>'.$bplabel.'</td>
					<td>'.$bodyparttext.$bodypartselect.'</td>
					<td>'.$bodypartdescriptortext.$bodypartdescriptorselect.'</td>
					<td>'.$bodypartdescriptorhidden.$bodyparthidden.'</td>
				</tr>
			</table>
		</div>';
}
?>
<div class="clearboth"></div>
<div class="position_relative;">
	<div class="position_relative;" >
		<div class="col20pct">Doctor:</div>
		<div class="col80pct">
			<input name="report[header][rhdmlname]" type="text" value="<?php echo $report['header']['rhdmlname']; ?>" size="35" maxlength="35" />
			<input name="report[header][rhdmfname]" type="text" value="<?php echo $report['header']['rhdmfname']; ?>" size="35" maxlength="35" />
		</div>
	</div>

	<div class="clearboth"></div>
	<div style="position_relative;">
		<div class="col20pct">Location:</div>
		<div class="col80pct"><input name="report[header][rhdlcity]" type="text" value="<?php echo $report['header']['rhdlcity']; ?>" size="65" maxlength="65" />
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">Phone:</div>
		<div class="col80pct"><input name="report[header][rhdlsphone]" type="text" value="<?php echo $report['header']['rhdlsphone']; ?>" size="20" maxlength="20" />
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">Fax:</div>
		<div class="col80pct">
			<input name="report[header][rhdlsfax]" type="text" value="<?php echo $report['header']['rhdlsfax']; ?>" size="20" />
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative" >
		<div class="col20pct">Type of Injury</div>
		<div class="col80pct">
			<select name="report[header][rhcasetypecode]" >
				<?php echo $casetypehtml; ?>
			</select>
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative" >
		<div class="col20pct">Therapy Type</div>
		<div class="col80pct">
			<select name="report[header][rhtherapytypecode]" >
				<?php echo $therapytypehtml; ?>
			</select>
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">Referral Date:</div>
		<div class="col80pct">
			<input id="rhcrdate" type="text" name="report[header][rhcrdate]" size="10" maxlength="10" value="<?php if(isset($report['header']['rhcrdate'])) echo displayDate($report['header']['rhcrdate']); ?>"  onchange="validateDate(this.id)">
			<img  align="absmiddle" name="anchorrhcrdate" id="anchorrhcrdate" src="/img/calendar.gif" onclick="cal.select(document.SoapForm.rhcrdate,'anchorrhcrdate','MM/dd/yyyy'); return false;" />		</div>
	</div>

<?php 

$id='rhinjuries';

//	$test[0]=array(0=>'Sprain');
//	$test[1]=array(0=>'Surgery');
//	$test[2]=array(0=>'Sprain');
//	$test[3]=array(0=>'Sprain');
//	$test[4]=array(0=>'Lumbago');
//	$report['header']["$id"]=array(0=>$test[0], 1=>$test[1], 2=>$test[2], 3=>$test[3], 4=>$test[4]);
?>
<!--  START OF INJURY DIV <?php echo "$id"; ?> -->
	<div class="col10pct" id="<?php echo "$id"; ?>_wrap" style="margin-top:5px;" >Injuries:</div>
	<div class="col90pct" id="<?php echo "$id"; ?>_div" style="margin-top:5px;" >
		<?php include('Doctor_injury.php'); ?>
	</div>
<!--  END OF INJURY DIV <?php echo "$id"; ?> -->
</div>
<div class="clearboth"></div>