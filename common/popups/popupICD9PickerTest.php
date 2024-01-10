<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
?>
<script>
<!--  

function saveSelectOptions(formname, selectname, savename) {
	var savedSelectOptions = document.createElement('select');
	savedSelectOptions.id=savename;
	savedSelectOptions.name=savename;
	savedSelectOptions.style.display='none';
	var oSelect = document.getElementById(selectname);
	for (i=0; i < oSelect.options.length; i++) {
		var newOption = document.createElement('option');
		oSelectOption=oSelect.options[i];
		newOption=oSelectOption;
//		nextOptionValue = oSelect.options[i].value;
//		nextOptionText = oSelect.options[i].text;
		try {
			savedSelectOptions.add(newOption, null); // standards compliant; doesn't work in IE
		}
		catch(ex) {
			savedSelectOptions.add(newOption); // IE only
		}
	}
	document.forms(formname).appendChild(savedSelectOptions);
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
// oSelect.style.display = 'block';
			if(keyCode!=8) {
				var c = String.fromCharCode(keyCode);
				c = c.toUpperCase();
				toFind = oText.value.toUpperCase() + c;
			}
			else {
				var upper=oText.value.toUpperCase();
				toFind = upper.substring(0,upper.length-1);
			}
	
//			var sSelect = document.getElementById('savedSelectOptions');
			oSelect.style.display='none';
			oSelect.length=0;
	
			for (i=0; i < sSelect.options.length; i++) {
				sSelectOptionText = sSelect.options[i].text.toUpperCase();
				if(sSelectOptionText.search(toFind) >= 0) {
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
					oSelect.size=oSelect.length;
				oSelect.style.display='block';
			}
		}
	}
}

function comboselect_onchange(oText, oHidden, oSelect, sSelect) {
	if(oSelect.selectedIndex == -1)
		oSelect.selectedIndex=0;
	oText.value = oSelect.options[oSelect.selectedIndex].text;
	oHidden.value=oSelect.options[oSelect.selectedIndex].value;
}
  
function comboselect_onkeyup(keyCode, oText, oHidden, oSelect, sSelect){
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
// -->  
</script>
<?php
$_POST['report']['header']['rhicd9code1']="847.0";
$_POST['report']['header']['rhicd9code2']="846.0";
$icd9codearray = icd9CodeOptions(); // contains icd9, injurytype, bodypart, descriptor
$bodypartcodearray = bodypartCodeOptions(); // contains code, description, inactive, parent, shortdescription
$i=0;
// place once more than provided up to 4

$reporticd9code=array();
$html=array();
for($i=1; $i<=4; $i++) {
	$reporticd9fieldname='rhicd9code'.$i;
	if(!empty($_POST['report']['header']["$reporticd9fieldname"])) {
		$reporticd9code["$i"]=$_POST['report']['header']["$reporticd9fieldname"];

		$icd9hidden='<input type="hidden" id="icd9_'.$i.'_value" name="icd9_'.$i.'_value" value="'.$reporticd9code["$i"].'" />';
		$icd9text='<input type="text" name="icd9_'.$i.'_text" size="60" autocomplete="off"  style="z-index:1;" onkeydown="combotext_onkeydown(event, this.form.icd9_'.$i.'_text, this.form.icd9_'.$i.'_value, this.form.icd9_'.$i.'_select, this.form.selectSaved)" value="'.$reporticd9code["$i"].'" />';
		$icd9options = getSelectOptions($arrayofarrayitems=$icd9codearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$reporticd9code["$i"], $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());
		$icd9select='<select id="icd9_'.$i.'_select" name="icd9_'.$i.'_select" style="display:none; position:absolute; top:20px; left:0px; z-index:999;" onblur="this.style.display=\'none\'" 
		onchange="comboselect_onchange(this.form.icd9_'.$i.'_text, this.form.icd9_'.$i.'_value, this, this.form.selectSaved)" 
		onkeyup="comboselect_onkeyup(event.keyCode, this.form.icd9_'.$i.'_text, this.form.icd9_'.$i.'_value, this, this.form.selectSaved)">'.$icd9options.'</select>';

		$html["$i"]=$icd9hidden.$icd9text.$icd9select;
	}
}

dump("html",$html);
exit();
$icd9_1 = getSelectOptions($arrayofarrayitems=$icd9codearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$icd9, $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());
$icd9_2 = getSelectOptions($arrayofarrayitems=$icd9codearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$icd9, $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());
$icd9_3 = getSelectOptions($arrayofarrayitems=$icd9codearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$icd9, $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());
$icd9_4 = getSelectOptions($arrayofarrayitems=$icd9codearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$icd9, $addblankoption=false, $arraykey='', $arrayofmatchvalues=array());
?>

<form id="icd9select" name="icd9select" method="post" action="" >
	<div style="position:relative;">

		<input type="hidden" id="icd9_1_value" name="icd9_1_value" />

		<input type="text" name="icd9_1_text" size="60" autocomplete="off"  style="z-index:1;" onkeydown="combotext_onkeydown(event, this.form.icd9_1_text, this.form.icd9_1_value, this.form.icd9_1_select, this.form.selectSaved)" />

		<select id="icd9_1_select" name="icd9_1_select" style="display:none; position:absolute; top:20px; left:0px; z-index:999;" onblur="this.style.display='none'" 
		onchange="comboselect_onchange(this.form.icd9_1_text, this.form.icd9_1_value, this, this.form.selectSaved)" 
		onkeyup="comboselect_onkeyup(event.keyCode, this.form.icd9_1_text, this.form.icd9_1_value, this, this.form.selectSaved)">
			<?php 
		echo $icd9_1;
			?>
		</select>
	</div>
	<div style="position:relative;">
		<input type="hidden" id="icd9_2_value" name="icd9_2_value" />
		<input type="text" name="icd9_2_text"  style="z-index:1;" size="60" autocomplete="off" onkeydown="combotext_onkeydown(event, this.form.icd9_2_text, this.form.icd9_2_value, this.form.icd9_2_select, this.form.selectSaved)" />
		<select id="icd9_2_select" name="icd9_2_select" style="display:none; position:absolute; top:20px; left:0px; z-index:999;" onblur="this.style.display='none'" 
		onchange="comboselect_onchange(this.form.icd9_2_text, this.form.icd9_2_value, this, this.form.selectSaved)" 
		onkeyup="comboselect_onkeyup(event.keyCode, this.form.icd9_2_text, this.form.icd9_2_value, this, this.form.selectSaved)">
			<?php 
		echo $icd9_2;
			?>
		</select>
	</div>
	<div style="position:relative;">
		<input type="hidden" id="icd9_3_value" name="icd9_3_value" />
		<input type="text" name="icd9_3_text" size="60" autocomplete="off" style="z-index:1;" onkeydown="combotext_onkeydown(event, this.form.icd9_3_text, this.form.icd9_3_value, this.form.icd9_3_select, this.form.selectSaved)" />
		<select id="icd9_3_select" name="icd9_3_select" style="display:none; position:absolute; top:20px; left:0px; z-index:999;" onblur="this.style.display='none'" 
		onchange="comboselect_onchange(this.form.icd9_3_text, this.form.icd9_3_value, this, this.form.selectSaved)" 
		onkeyup="comboselect_onkeyup(event.keyCode, this.form.icd9_3_text, this.form.icd9_3_value, this, this.form.selectSaved)">
			<?php 
		echo $icd9_1;
			?>
		</select>
	</div>
	<div style="position:relative;">
		<input type="hidden" id="icd9_4_value" name="icd9_4_value" />
		<input type="text" name="icd9_4_text" size="60" autocomplete="off" style="z-index:1;" onkeydown="combotext_onkeydown(event, this.form.icd9_4_text, this.form.icd9_4_value, this.form.icd9_4_select, this.form.selectSaved)" />
		<select id="icd9_4_select" name="icd9_4_select" style="display:none; position:absolute; top:20px; left:0px; z-index:999;" onblur="this.style.display='none'" 
		onchange="comboselect_onchange(this.form.icd9_4_text, this.form.icd9_4_value, this, this.form.selectSaved)" 
		onkeyup="comboselect_onkeyup(event.keyCode, this.form.icd9_4_text, this.form.icd9_4_value, this, this.form.selectSaved)">
			<?php 
		echo $icd9_1;
			?>
		</select>
	</div>
</form>
<script>
saveSelectOptions('icd9select','icd9_1_select','selectSaved');
</script>
