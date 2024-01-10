<?php
$helperboxstyle='style="text-decoration:none; color:#000000; background-color:#FFFFFF"';
$helperitemstyle='style="text-decoration:none; color:#000000; background-color:#FFFFFF"';
unset($working);
unset($notworking);
if($report['header']['rhworking']==1)
	$working='checked="checked"';
else
	$notworking='checked="checked"';

function htmlTextAreaWithHelper($section, $fieldname, $fieldtitle, $helpername, $helperarray, $report, $cols=60, $rows=3) {
	echo '<div class="clearboth"></div>';
	echo '<div class="position_relative" onclick="javascript:helperopen(\''.$helpername.'\');" >';
	echo '<div class="col20pct">'.$fieldtitle.'</div>';
	echo '<div class="col70pct">';
//	echo '<textarea class="helper" cols="60" rows="3" id="'.$helpername.'" name="report['.$section.']['.$fieldname.']" onfocus="helperopen(this.id); this.value=this.value;" onblur="javascript:updatetextbox(\''.$helpername.'_helper['.$helper.']\'); javascript:helperclose(this.id);" >'.$report["$section"]["$fieldname"].'</textarea>';

//	if(is_array($helperarray) && count($helperarray)>=1) {
		echo '<textarea class="helper" cols="'.$cols.'" rows="'.$rows.'" id="'.$helpername.'" name="report['.$section.']['.$fieldname.']" onfocus="helperopen(this.id); this.value=this.value;" >'.$report["$section"]["$fieldname"].'</textarea>';
		htmlHelper($helpername, $helperarray);
//	}
//	else {
//		echo '<textarea class="helper" cols="'.$cols.'" rows="'.$rows.'" id="'.$helpername.'" name="report['.$section.']['.$fieldname.']" >'.$report["$section"]["$fieldname"].'</textarea>';
//	}
	echo '</div>';

	htmlHelperClear($helpername);
	echo '</div>';
}

function htmlHelperClear($helpername) {
//		echo '<div class="col10pct"><a href="#" id="'.$helpername.'_helper[clear]" tabindex="99999" onclick="cleartextbox(this.id);">Clear</a></div>';
		echo '<div class="col10pct"><div id="'.$helpername.'_helper[clear]" tabindex="99999" onclick="javascript:cleartextbox(this.id);">Clear</div></div>';
}

function htmlHelper($helpername, $helperarray) {
	$helperboxstyle='style="text-decoration:none; margin-left:30px; border:solid; border-width=1px; border-color:#4682B4; color:#000000; background-color:#FFFFFF"';
	$helperitemstyle='style="text-decoration:none; color:#000000; background-color:#FFFFFF"';
	if(count($helperarray)==0) 
		$helperarray=array("0"=>"Nothing defined for this field");

	echo'<div class="position_absolute" id="'.$helpername.'_helper" style="display:none; z-index:999;">';
	echo'<div class="col100pct" '.$helperboxstyle.'>';
//	sorta
	foreach($helperarray as $helper=>$helpertext) {
//		echo'<a href="javascript:updatetextbox(\''.$helpername.'_helper['.$helper.']\');" id="'.$helpername.'_helper['.$helper.']"  '.$helperitemstyle.' >'.$helpertext.'</a><br />';
// no good		echo'<span id="'.$helpername.'_helper['.$helper.']" '.$helperitemstyle.' onClick("updatetextbox(this.id);" >'.$helpertext.'</span><br />';
		echo'
<div onClick="javascript:updatetextbox(\''.$helpername.'_helper['.$helper.']\');" id="'.$helpername.'_helper['.$helper.']"  '.$helperitemstyle.' >'.$helpertext.'</div>';
	}
	echo'</div>';
	echo'</div>';
}

function getHelpers() {
	$helperarray=array();
	$select="SELECT rfhname, rfhtext, rfhdispseq FROM report_field_helpers ORDER BY rfhname, rfhdispseq, rfhtext";
	if($result=mysql_query($select)) {
		$index=0;
		while($row=mysql_fetch_assoc($result)) {
			$helperarray[$row['rfhname']]["$index"]=$row['rfhtext'];
			$index++;
		}
	}
	return($helperarray);
}

$helperarrays=getHelpers();

function reportField($section, $field) {
	$stringname="report[$section][$field]";
	return($stringname);
}

$occupationhtml=occupationOptions();
$occupationhtml = getSelectOptions($arrayofarrayitems=$occupationhtml, $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $defaultoption=$report['header']['rhoccup'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());

?>
<div class="clearboth"></div>
<div class="position_relative">

	<div class="position_relative" >
		<div class="col20pct">Subjective Note</div>
		<div class="col70pct"><textarea cols="60" rows="3" name="report[header][rhsubjectivenote]"><?php echo $report['header']['rhsubjectivenote'] ?></textarea></div>
		<div class="col10pct" style="white-space:normal;">
			<input type="checkbox" name="report[header][rhsubjectivenoteprint]" <?php if($report['header']['rhsubjectivenoteprint']=='1') echo 'checked="checked"'; ?> />
			Include on Report
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative" >
		<div class="col20pct">Working?</div>
		<div class="col80pct">
			<input type="radio" id="rhworking" name="report[header][rhworking]" value="1" <?php echo $working; ?> />Yes&nbsp;&nbsp;&nbsp;
			<input type="radio" id="rhworking" name="report[header][rhworking]" value="0" <?php echo $notworking; ?> />No
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative" >
		<div class="col20pct">Occupation</div>
		<div class="col50pct">
			<select name="report[header][rhoccup]" >
				<?php echo $occupationhtml; ?>
			</select>
		</div>
	</div>

	<?php
	htmlTextAreaWithHelper('header','rhjobrequirement', 'Job Requirement', 'jobrequirement', $helperarrays['jobrequirement'], $report);
	htmlTextAreaWithHelper('header','rhspecificinjury', 'Specific Injury', 'specificinjury', $helperarrays['specificinjury'], $report);
	htmlTextAreaWithHelper('header','rhchiefcomplaint', 'Chief Complaint', 'chiefcomplaint', $helperarrays['chiefcomplaint'], $report);
	htmlTextAreaWithHelper('header','rhpainrating', 'Pain Rating', 'painrating', $helperarrays['painrating'], $report);
	htmlTextAreaWithHelper('header','rhfunctionalactivity', 'Functional Activity', 'functionalactivity', $helperarrays['functionalactivity'], $report);
	htmlTextAreaWithHelper('header','rhmedicalhistory', 'Medical History', 'medicalhistory', $helperarrays['medicalhistory'], $report);
	htmlTextAreaWithHelper('header','rhsurgeries', 'Surgeries', 'surgeries', $helperarrays['surgeries'], $report);
	htmlTextAreaWithHelper('header','rhmedications', 'Medications', 'medications', $helperarrays['medications'], $report);
	htmlTextAreaWithHelper('header','rhdiagnostictests', 'Diagnostic Tests', 'diagnostictests', $helperarrays['diagnostictests'], $report);
	 ?>
</div>
<div class="clearboth"></div>