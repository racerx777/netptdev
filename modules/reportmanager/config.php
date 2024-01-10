<?php
$_SESSION['init']['reportmanager']=1;

function stripslashes_deep($value) {
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}

function getRowColorStyle($date) {
	$diff = time() - strtotime($date) + 1; //Find the number of seconds
	$daysold = ceil($diff / (60*60*24)) ;  //Find how many days that is
	$style='';
	if($daysold>11)
		$style='style="background-color:lightcoral; color:black;"';
	else {
		if($daysold>7)
			$style='style="background-color:palegoldenrod; color:black;"';
		else {
			if($daysold>3)
				$style='style="background-color:palegreen; color:black;"';
		}
	}
	return($style);
}

function getReportDescription($id) {
	$selectquery="SELECT * FROM report_template where rtid='$id'";
	if($selectresult=mysqli_query($dbhandle,$selectquery)) {
		if($selectrow=mysqli_fetch_assoc($selectresult)) 
			return($selectrow['rtname'].':'.$selectrow['rtdescription']);
		else
			return('Report Type Not Specified');
	}
	else
		error("999","reportType:SELECT error.<br>$selectquery<br>".mysqli_error($dbhandle));
}

function getDropDownHTML($tableid, $rowid, $field, $num, $inputarrayname, $formfieldname, $formfieldvalue, $formfieldsize, $formfieldmaxsize, $options) {
	$formfieldvaluename=str_replace("[", "['", $formfieldname);
	$formfieldvaluename=str_replace("]", "']", $formfieldvaluename);

	$inputboxname=$tableid."_".$rowid."_".$field;

	if($num=='') {
		$selectid=$inputboxname;
		$popupid=$inputboxname."_popup";
	}
	else {
		$selectid=$inputboxname."_".$num;
		$popupid=$inputboxname."_".$num."_popup";
	}

	$selectid_q="'".$selectid."'";

	$popupsize=count(split("</option>",$options))-1;
//dump("popupsize",$popupsize);
	$cell='
<td>
<input 
	id="'.$selectid.'" 
	name="'.$formfieldname.'" 
	type="text" 
	value="'.$formfieldvalue.'" 
	size="'.$formfieldsize.'" 
	maxsize="'.$formfieldmaxsize.'" 
	ondblclick="autocompletepopup(this.id)" 
	autocomplete="off" 
	autocompleteclass="array:'.$inputarrayname.'" 
	onfocus="autocompletepopdownall();" 
	onblur="autocompletepopdown(this.id)" 
/>
<select 
	class="popup" 
	style="display:none;" 
	id="'.$popupid.'" 
	size="'.$popupsize.'" 
	onchange="autocompleteupdate('.$selectid_q.')" 
	onblur="autocompletepopdown('.$selectid_q.')" 
>
	'.$options.'
</select>
</td>';
	return($cell);
}

?>