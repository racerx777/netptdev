<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
?>
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
	keyCode = e.keyCode;
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
			e.cancelBubble = true;
			if (e.returnValue) 
				e.returnValue = false;
			if (e.stopPropagation) 
				e.stopPropagation();
			comboselect_onchange(oText, oHidden, oSelect, sSelect);
//			oSelect.style.display='none';
			oText.focus();
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

function RegEscape(text) {
    return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
}

// -->  
</script>
<?php
$_POST['report']['header']['rhicd9code1']="847.0";
$_POST['report']['header']['rhicd9desc1']="847.0 Sprains Strains Anterior Longitudinal Ligament Cervical (847.0)";
$_POST['report']['header']['rhicd9code2']="846.1";
$_POST['report']['header']['rhicd9desc2']="846.1 Sprains Strains Sacroiliac Ligament (846.1)";
$icd9codearray = icd9CodeOptions(); // contains icd9, injurytype, bodypart, descriptor
$bodypartcodearray = bodypartCodeOptions(); // contains code, description, inactive, parent, shortdescription
$bodypartdescriptorcodearray = bodypartdescriptorCodeOptions(); // contains code, description, inactive, parent, shortdescription

$formstart='<form id="icd9select" name="icd9select" method="post" action="'.$_SERVER['PHP_SELF'].'" >
	<div style="position:relative;">';
/*$formend='<input type="submit" name="button[]" value="Update Dx"></form>
<script>
saveSelectOptions(\'icd9select\',\'icd9_select_1\',\'icd9_select_saved\');
saveSelectOptions(\'icd9select\',\'bp_select_1\',\'bp_select_saved\');
saveSelectOptions(\'icd9select\',\'bpd_select_1\',\'bpd_select_saved\');
</script>';
*/
$formend='</form>
<script>
saveSelectOptions(\'icd9select\',\'icd9_select_1\',\'icd9_select_saved\');
saveSelectOptions(\'icd9select\',\'bp_select_1\',\'bp_select_saved\');
saveSelectOptions(\'icd9select\',\'bpd_select_1\',\'bpd_select_saved\');
</script>';

$reporticd9code=array();
$dxhtml=array();
for($i=1; $i<=4; $i++) {
	$dxlabel="Dx $i:";
		$reporticd9codefield='rhicd9code'.$i;
		$reporticd9descfield='rhicd9desc'.$i;
		$reporticd9code=$_POST['report']['header']["$reporticd9codefield"];
		$reporticd9desc=$_POST['report']['header']["$reporticd9descfield"];
		if(!empty($reporticd9code))
			$hfvalue=' value="'.$reporticd9code.'"';
		else
			$hfvalue='';
		if(!empty($reporticd9desc))
			$tfvalue=' value="'.$reporticd9desc.'"';
		else
			$tfvalue='';

		$hfn='report[header]['.$reporticd9codefield.']';
		$tfn='report[header]['.$reporticd9descfield.']';
		$hf=$reporticd9codefield;
		$tf=$reporticd9descfield;

		$sf="icd9_select_".$i;
		$ss="icd9_select_saved";
		$icd9hidden='<input type="hidden" id="'.$hf.'" name="'.$hfn.'"'.$hfvalue.' />';
		$icd9text='
			<input type="text" id="'.$tf.'" name="'.$tfn.'" size="100" autocomplete="off" style="height=30px; z-index:1;" '.$tfvalue.' 
		onkeydown="combotext_onkeydown(event, this.form.'.$tf.', this.form.'.$hf.', this.form.'.$sf.', this.form.'.$ss.')" />';

		$icd9options = getSelectOptions($arrayofarrayitems=$icd9codearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>''), $defaultoption=$reporticd9code, $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());
		$pos=($i-1)*60+30;

		$icd9select='<select id="'.$sf.'" style="display:none; position:absolute; top:'.$pos.'px; left:100px; z-index:999;" 
		onblur="this.style.display=\'none\'" 
		onchange="comboselect_onchange(this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')" 
		onkeydown="comboselect_onkeydown(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
		onkeyup="comboselect_onkeyup(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
		>'.$icd9options.'</select>
		';
// 



		$reportbpdcodefield='rhicd9dxbodydescriptor'.$i;
		$reportbpddescfield='rhicd9dxbodydescriptordesc'.$i; // needs lookup
		$reportbpdcode=$_POST['report']['header']["$reportbpdcodefield"];
		$reportbpddesc=$_POST['report']['header']["$reportbpddescfield"];
		if(!empty($reportbpdcode))
			$hfvalue=' value="'.$reportbpdcode.'"';
		else
			$hfvalue='';
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

		$bodypartdescriptorhidden='<input type="hidden" id="'.$hf.'" name="'.$hfn.'"'.$hfvalue.' />';

		$bodypartdescriptortext='
			<input type="text" id="'.$tf.'" name="'.$tfn.'" size="16" autocomplete="off" style="height=30px; z-index:1;" '.$tfvalue.'
		onkeydown="combotext_onkeydown(event, this.form.'.$tf.', this.form.'.$hf.', this.form.'.$sf.', this.form.'.$ss.')" />';

		$bodypartdescriptoroptions = getSelectOptions($arrayofarrayitems=$bodypartdescriptorcodearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>''), $defaultoption=$reportbpdcode, $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());

		$pos=$pos+30;

		$bodypartdescriptorselect='<select id="'.$sf.'" style="display:none; position:absolute; top:'.$pos.'px; left:660px; z-index:999;" 
		onblur="this.style.display=\'none\'" 
		onchange="comboselect_onchange(this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')" 
		onkeydown="comboselect_onkeydown(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
		onkeyup="comboselect_onkeyup(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
		>'.$bodypartdescriptoroptions.'</select>';
//
	$bplabel="Body Part $i:";
		$reportbpcodefield='rhicd9dxbodypart'.$i;
		$reportbpdescfield='rhicd9dxbodypartdesc'.$i; // needs lookup
		$reportbpcode=$_POST['report']['header']["$reportbpcodefield"];
		$reportbpdesc=$_POST['report']['header']["$reportbpdescfield"];
		if(!empty($reportbpcode))
			$hfvalue=' value="'.$reportbpcode.'"';
		else
			$hfvalue='';
		if(!empty($reportbpdesc))
			$tfvalue=' value="'.$reportbpdesc.'"';
		else
			$tfvalue='';
		$hfn='report[header]['.$reportbpcodefield.']';
		$tfn='report[header]['.$reportbpdescfield.']';
		$hf=$reportbpcodefield;
		$tf=$reportbpdescfield;
		$sf="bp_select_".$i;
		$ss="bp_select_saved";

		$bodyparthidden='<input type="hidden" id="'.$hf.'" name="'.$hfn.'"'.$hfvalue.' />';

		$bodyparttext='
			<input type="text" id="'.$tf.'" name="'.$tfn.'" size="80" autocomplete="off" style="height=30px; z-index:1;" '.$tfvalue.'
		onkeydown="combotext_onkeydown(event, this.form.'.$tf.', this.form.'.$hf.', this.form.'.$sf.', this.form.'.$ss.')" />';

		$bodypartoptions = getSelectOptions($arrayofarrayitems=$bodypartcodearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>''), $defaultoption=$reportbpcode, $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());

		$pos=$pos+30;

		$bodypartselect='<select id="'.$sf.'" style="display:none; position:absolute; top:'.$pos.'px; left:100px; z-index:999;" 
		onblur="this.style.display=\'none\'" 
		onchange="comboselect_onchange(this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')" 
		onkeydown="comboselect_onkeydown(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
		onkeyup="comboselect_onkeyup(event.keyCode, this.form.'.$tf.', this.form.'.$hf.', this, this.form.'.$ss.')"
		>'.$bodypartoptions.'</select>';

		$dxhtml["$i"]='
		<div id="dx_'.$i.'">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td height="30px">'.$dxlabel.'</td>
					<td>'.$icd9hidden.$icd9text.$icd9select.'</td>
				</tr>
				<tr>
					<td height="30px">'.$bplabel.'</td>
					<td>'.$bodyparthidden.$bodyparttext.$bodypartselect.$bodypartdescriptorhidden.$bodypartdescriptortext.$bodypartdescriptorselect.'</td>
				</tr>
			</table>
		</div>';
}

echo $formstart;
foreach($dxhtml as $i=>$html)
	echo $html;
echo $formend;
?>