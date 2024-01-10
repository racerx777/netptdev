<script type="text/javascript" language="javascript">
function InjuryTableRowFormat(element) {
	try{
		var add=element.children[0].style;
		add.display="none";
		var del=element.children[1].style;
		del.display="block";
	}
	catch(e) {
		alert(e);
	}
}

function InjuryTableRowDel(element) {
	try {
		var row=element.parentNode.parentNode;
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}

function InjuryTableRowAdd(element) {
	try {
		var elementdiv=element.parentNode; // adddelbutton
		var row=elementdiv.parentNode; // tr rowadd
		var tbody=row.parentNode; // tbody no id
		var table=tbody.parentNode; // table
		var form=table.parentNode;
		if(row.children[0].children[0].value!='') {
			var sectionrowslength=tbody.rows.length
			oldaddrow=tbody.rows[sectionrowslength-1];
			newaddrow=row.cloneNode(true);
			var nextnumber=document.getElementById('injury_nextnumber');
			var nextnumbervalue=nextnumber.value;
			var n=nextnumbervalue+0;
			oldaddrow.cells[0].children[0].id='injuries_'+n; 
			oldaddrow.cells[0].children[0].name='report[header_injury][rhinature]['+n+']';
			oldaddrow.cells[1].children[0].id='descriptors_'+n; 
			oldaddrow.cells[1].children[0].name='report[header_injury][rhidescriptor]['+n+']';
			oldaddrow.cells[2].children[0].id='bodyparts_'+n; 
			oldaddrow.cells[2].children[0].name='report[header_injury][rhibodypart]['+n+']';
			oldaddrow.cells[3].children[0].id='icd9codes_'+n; 
			oldaddrow.cells[3].children[0].name='report[header_injury][rhiicd9code]['+n+']';
// CHANGE ADD TO DELETE
			InjuryTableRowFormat(oldaddrow.cells[4]);
			oldaddrow.id='row'+n;
//			oldaddrow.name='row'+n+'';
			nextnumber.value++;
			if( document.attachEvent ) {
				newaddrow.children[0].children[0].attachEvent( 'onkeyup', cAutocomplete.complete )
				newaddrow.children[1].children[0].attachEvent( 'onkeyup', cAutocomplete.complete )
				newaddrow.children[2].children[0].attachEvent( 'onkeyup', cAutocomplete.complete )
				newaddrow.children[3].children[0].attachEvent( 'onkeyup', cAutocomplete.complete )
			}
			else if( document.addEventListener ) {
				newaddrow.children[0].children[0].addEventListener( 'keyup', cAutocomplete.complete, false )
				newaddrow.children[1].children[0].addEventListener( 'keyup', cAutocomplete.complete, false )
				newaddrow.children[2].children[0].addEventListener( 'keyup', cAutocomplete.complete, false )
				newaddrow.children[3].children[0].addEventListener( 'keyup', cAutocomplete.complete, false )
			}
			newaddrow.children[0].children[0].value='';
			newaddrow.children[1].children[0].value='';
			newaddrow.children[2].children[0].value='';
			newaddrow.children[3].children[0].value='';
			tbody.appendChild(newaddrow);
		}
	}
	catch(e) {
		alert(e);
	}
}
</script>
<?php
// Load Options Variables for the select boxes - source arrays loaded in config-include.php
$options['injuries']=getSelectOptions($arrayofarrayitems=$injuries, $optionvaluefield='imnsdescription', $arrayofoptionfields=array('imnsdescription'=>' '), $defaultoption="", $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array());
$options['descriptors']=getSelectOptions($arrayofarrayitems=$descriptors, $optionvaluefield='value', $arrayofoptionfields=array('value'=>' '), $defaultoption="", $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array());
$options['bodyparts']=getSelectOptions($arrayofarrayitems=$bodyparts, $optionvaluefield='rbsdescription', $arrayofoptionfields=array('rbsdescription'=>' '), $defaultoption="", $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array(), FALSE);
$options['icd9codes']=getSelectOptions($arrayofarrayitems=$icd9codes, $optionvaluefield='imdx', $arrayofoptionfields=array('imdx'=>' '), $defaultoption="", $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array());
// Initialize 
$maxrow=0;
$hinum=0;
$row=array();
// Heading - Top Heading
$row[]='<tr>
	<th width="140px">Injury Type</th>
	<th width="50px" style="font-size:x-small;">Designator</th>
	<th width="80px">Body Part</th>
	<th width="340px">ICD9</th>
	<th width="40px">&plus;/&minus;</th>
</tr>';
// Process existing database injuries
$headerinjury=$report['header_injury'];
if(is_array($headerinjury) && count($headerinjury)>0) {
	foreach($headerinjury as $hinumz=>$array) {
		$hinum++;
		$cell=array();
		if($maxrow <= $hinum)
			$maxrow=$hinum+1;

		$cell[]='<!-- START OF LIST '.$hinum.' -->';
		$cell[]='<tr class="INJURY" id="row'.$hinum.'">';
// Injury Type
		$inputarrayname="injuries";
		$formfieldname='report[header_injury][rhinature]['.$hinum.']';
		$formfieldvalue=$array['rhinature'];
		$formfieldsize=20;
		$formfieldmaxsize=100;
//		$inputboxname=$inputarrayname;
		$selectid=$inputarrayname."_".$hinum;
		$popupid=$inputarrayname."_".$hinum."_popup";
		$selectid_q="'".$selectid."'";
		$popupid_q="'".$popupid."_popup'";
		$popup='<select class="popup" style="display:none;" id="'.$popupid.'" size="10" onchange="autocompleteupdate('.$selectid_q.')" onblur="autocompletepopdown('.$selectid_q.')" >'.$options["$inputarrayname"].'</select>';
		$cell[]='<td><input id="'.$selectid.'" name="'.$formfieldname.'" type="text" value="'.$formfieldvalue.'" size="'.$formfieldsize.'" maxsize="'.$formfieldmaxsize.'" ondblclick="autocompletepopup(this.id)" autocomplete="off" autocompleteclass="array:'.$inputarrayname.'" onfocus="autocompletepopdownall();" onblur="autocompletepopdown(this.id)" />'.$popup.'</td>';
// Body Part Designator
		$inputarrayname="descriptors";
		$formfieldname='report[header_injury][rhidescriptor]['.$hinum.']';
		$formfieldvalue=$array['rhidescriptor'];
		$formfieldsize=5;
		$formfieldmaxsize=10;
//		$inputboxname=$inputarrayname."_".$hinum."'";
		$selectid=$inputarrayname."_".$hinum;
		$popupid=$inputarrayname."_".$hinum."_popup";
		$selectid_q="'".$selectid."'";
		$popupid_q="'".$popupid."_popup'";
		$popup='<select class="popup" style="display:none;" id="'.$popupid.'" size="10" onchange="autocompleteupdate('.$selectid_q.')" onblur="autocompletepopdown('.$selectid_q.')" >'.$options["$inputarrayname"].'</select>';
		$cell[]='<td><input id="'.$selectid.'" name="'.$formfieldname.'" type="text" value="'.$formfieldvalue.'" size="'.$formfieldsize.'" maxsize="'.$formfieldmaxsize.'" ondblclick="autocompletepopup(this.id)" autocomplete="off" autocompleteclass="array:'.$inputarrayname.'" onfocus="autocompletepopdownall();" onblur="autocompletepopdown(this.id)" />'.$popup.'</td>';
// Body Part
		$inputarrayname="bodyparts";
		$formfieldname='report[header_injury][rhibodypart]['.$hinum.']';
		$formfieldvalue=$array['rhibodypart'];
		$formfieldsize=10;
		$formfieldmaxsize=20;
//		$inputboxname=$inputarrayname."_".$hinum."'";
		$selectid=$inputarrayname."_".$hinum;
		$popupid=$inputarrayname."_".$hinum."_popup";
		$selectid_q="'".$selectid."'";
		$popupid_q="'".$popupid."_popup'";
		$popup='<select class="popup" style="display:none;" id="'.$popupid.'" size="10" onchange="autocompleteupdate('.$selectid_q.')" onblur="autocompletepopdown('.$selectid_q.')" >'.$options["$inputarrayname"].'</select>';
		$cell[]='<td><input id="'.$selectid.'" name="'.$formfieldname.'" type="text" value="'.$formfieldvalue.'" size="'.$formfieldsize.'" maxsize="'.$formfieldmaxsize.'" ondblclick="autocompletepopup(this.id)" autocomplete="off" autocompleteclass="array:'.$inputarrayname.'" onfocus="autocompletepopdownall();" onblur="autocompletepopdown(this.id)" />'.$popup.'</td>';
// ICD9 Code
		$inputarrayname="icd9codes";
		$formfieldname='report[header_injury][rhiicd9code]['.$hinum.']';
		$formfieldvalue=$array['rhiicd9code'];
		$formfieldsize=40;
		$formfieldmaxsize=255;
//		$inputboxname=$inputarrayname."_".$hinum."'";
		$selectid=$inputarrayname."_".$hinum;
		$popupid=$inputarrayname."_".$hinum."_popup";
		$selectid_q="'".$selectid."'";
		$popupid_q="'".$popupid."_popup'";
		$popup='<select class="popup" style="display:none;" id="'.$popupid.'" size="10" onchange="autocompleteupdate('.$selectid_q.')" onblur="autocompletepopdown('.$selectid_q.')" >'.$options["$inputarrayname"].'</select>';
		$cell[]='<td><input id="'.$selectid.'" name="'.$formfieldname.'" type="text" value="'.$formfieldvalue.'" size="'.$formfieldsize.'" maxsize="'.$formfieldmaxsize.'" ondblclick="autocompletepopup(this.id)" autocomplete="off" autocompleteclass="array:'.$inputarrayname.'" onfocus="autocompletepopdownall();" onblur="autocompletepopdown(this.id)" />'.$popup.'</td>';
//		$cell[]='<td onClick="javascript:TableClick(this.parentNode);"><u>del</u></td></tr>';
$cell[]='<td id="injurybuttons" class="adddelbuttons" >
<a class="plusminuslink" style="display:none; text-decoration:none; float:left;" onClick="javascript:InjuryTableRowAdd(this);" >&nbsp;&plus;&nbsp;</a>
<a class="plusminuslink" style="display:block; text-decoration:none; float:right;" onClick="javascript:InjuryTableRowDel(this);" >&nbsp;&minus;&nbsp;</a>
</td>';
		$cell[]='<!-- END OF LIST '.$id.' '.$hinum.' -->';
		$row[]=implode("\n",$cell);
	}
}
$cell=array();
$cell[]='<tr class="INJURY" id="rowadd">';
// Injury Type
$inputarrayname="injuries";
$formfieldname='report[header_injury][rhinature][add]';
$formfieldvalue=$report['header_injury']['rhinature']['add'];
$formfieldsize=20;
$formfieldmaxsize=100;
$inputboxname=$inputarrayname."_add";
$selectid=$inputboxname."_".$hinum;
$popupid=$inputboxname."_".$hinum."_popup";
$selectid_q="'".$selectid."'";
$popupid_q="'".$popupid."_popup'";
$popup='<select class="popup" style="display:none;" id="'.$popupid.'" size="10" onchange="autocompleteupdate('.$selectid_q.')" onblur="autocompletepopdown('.$selectid_q.')" >'.$options["$inputarrayname"].'</select>';
$cell[]='<td><input id="'.$selectid.'" name="'.$formfieldname.'" type="text" value="'.$formfieldvalue.'" size="'.$formfieldsize.'" maxsize="'.$formfieldmaxsize.'" ondblclick="autocompletepopup(this.id)" autocomplete="off" autocompleteclass="array:'.$inputarrayname.'" onfocus="autocompletepopdownall();" onblur="autocompletepopdown(this.id)" />'.$popup.'</td>';
// Body Part Descriptor
$inputarrayname="descriptors";
$formfieldname='report[header_injury][rhidescriptor][add]';
$formfieldvalue=$report['header_injury']['rhidescriptor']['add'];
$formfieldsize=5;
$formfieldmaxsize=10;
$inputboxname=$inputarrayname."_add";
$selectid=$inputboxname."_".$hinum;
$popupid=$inputboxname."_".$hinum."_popup";
$selectid_q="'".$selectid."'";
$popupid_q="'".$popupid."_popup'";
$popup='<select class="popup" style="display:none;" id="'.$popupid.'" size="10" onchange="autocompleteupdate('.$selectid_q.')" onblur="autocompletepopdown('.$selectid_q.')" >'.$options["$inputarrayname"].'</select>';
$cell[]='<td><input id="'.$selectid.'" name="'.$formfieldname.'" type="text" value="'.$formfieldvalue.'" size="'.$formfieldsize.'" maxsize="'.$formfieldmaxsize.'" ondblclick="autocompletepopup(this.id)" autocomplete="off" autocompleteclass="array:'.$inputarrayname.'" onfocus="autocompletepopdownall();" onblur="autocompletepopdown(this.id)" />'.$popup.'</td>';
// Body Part
$inputarrayname="bodyparts";
$formfieldname='report[header_injury][rhibodypart][add]';
$formfieldvalue=$report['header_injury']['rhibodypart']['add'];
$formfieldsize=10;
$formfieldmaxsize=20;
$inputboxname=$inputarrayname."_add";
$selectid=$inputboxname."_".$hinum;
$popupid=$inputboxname."_".$hinum."_popup";
$selectid_q="'".$selectid."'";
$popupid_q="'".$popupid."_popup'";
$popup='<select class="popup" style="display:none;" id="'.$popupid.'" size="10" onchange="autocompleteupdate('.$selectid_q.')" onblur="autocompletepopdown('.$selectid_q.')" >'.$options["$inputarrayname"].'</select>';
$cell[]='<td><input id="'.$selectid.'" name="'.$formfieldname.'" type="text" value="'.$formfieldvalue.'" size="'.$formfieldsize.'" maxsize="'.$formfieldmaxsize.'" ondblclick="autocompletepopup(this.id)" autocomplete="off" autocompleteclass="array:'.$inputarrayname.'" onfocus="autocompletepopdownall();" onblur="autocompletepopdown(this.id)" />'.$popup.'</td>';
// ICD9 Code
$inputarrayname="icd9codes";
$formfieldname='report[header_injury][rhiicd9code][add]';
$formfieldvalue=$report['header_injury']['rhiicd9code']['add'];
$formfieldsize=40;
$formfieldmaxsize=255;
$inputboxname=$inputarrayname."_add";
$selectid=$inputboxname."_".$hinum;
$popupid=$inputboxname."_".$hinum."_popup";
$selectid_q="'".$selectid."'";
$popupid_q="'".$popupid."_popup'";
$popup='<select class="popup" style="display:none;" id="'.$popupid.'" size="10" onchange="autocompleteupdate('.$selectid_q.')" onblur="autocompletepopdown('.$selectid_q.')" >'.$options["$inputarrayname"].'</select>';
$cell[]='<td><input id="'.$selectid.'" name="'.$formfieldname.'" type="text" value="'.$formfieldvalue.'" size="'.$formfieldsize.'" maxsize="'.$formfieldmaxsize.'" ondblclick="autocompletepopup(this.id)" autocomplete="off" autocompleteclass="array:'.$inputarrayname.'" onfocus="autocompletepopdownall();" onblur="autocompletepopdown(this.id)" />'.$popup.'</td>';
$cell[]='<td id="injurybuttons" class="adddelbuttons" >
<a class="plusminuslink" style="display:block; text-decoration:none; float:left;" onClick="javascript:InjuryTableRowAdd(this);" >&nbsp;&plus;&nbsp;</a>
<a class="plusminuslink" style="display:none; text-decoration:none; float:right;" onClick="javascript:InjuryTableRowDel(this);" >&nbsp;&minus;&nbsp;</a>
</td>';
$cell[]='</tr>
<!-- END OF LIST '.$id.' add/del -->';
$row[]=implode("\n",$cell);

$row[]='<input id="injury_nextnumber" type="hidden" value="'.$maxrow.'" />';

$table=array();
$table[]='<table id="injury_table" border="1" cellpadding="3" cellspacing="0" width="650px">';
$table[]=implode("\n",$row);
$table[]='</table>';
$form=implode("\n",$table);

echo($form);
?>