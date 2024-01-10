<?php
$helperboxstyle='style="text-decoration:none; color:#000000; background-color:#FFFFFF"';
$helperitemstyle='style="text-decoration:none; color:#000000; background-color:#FFFFFF"';

function htmlTextAreaWithHelper($section, $fieldname, $fieldtitle, $helpername, $helperarray, $report, $cols=60, $rows=3) {
	echo '<div class="clearboth"></div>';
	echo '<div class="position_relative">';
	echo '<div class="col20pct">'.$fieldtitle.'</div>';
	echo '<div class="col70pct">';
	echo '<textarea class="helper" cols="'.$cols.'" rows="'.$rows.'" id="'.$helpername.'" name="report['.$section.']['.$fieldname.']" onfocus="helpercloseall(this.id);" autocomplete="array:'.$helpername.'" >'.$report["$section"]["$fieldname"].'</textarea>';
	htmlHelper($helpername, $helperarray);
	echo '</div>';
//	htmlHelperClear($helpername);
	htmlHelperToggle($helpername);
	echo '</div>';
}

function htmlHelperClear($helpername) {
		echo '<div class="col10pct"><div id="'.$helpername.'_helper[clear]" tabindex="99999" onclick="javascript:cleartextbox(this.id);">Clear</div></div>';
}

function htmlHelperToggle($helpername) {
?>
	<div class="col10pct">
		<div id="<?php echo $helpername; ?>_helper[toggle]" tabindex="99999" onclick="helperopen('<?php echo $helpername; ?>')" >
			Hide/Show<br />Phrases
		</div>
	</div>
<?php
}

function htmlHelper($helpername, $helperarray) {
	$helperstyle='style="position:absolute; margin-left: 20px; margin-top:-2px; width:440px; z-index: 999; display: none; "';
	$helperboxstyle='style="text-decoration:none; border:1px solid #000000; color:#000000; background-color:#FFFFFF"';
	$helperitemstyle='style="text-decoration:none; color:#000000; background-color:#FFFFFF"';
	if(count($helperarray)==0) 
		$helperarray=array("0"=>"Nothing defined for this field");

	echo'<div id="'.$helpername.'_helper" '.$helperstyle.' >';
	echo'<div '.$helperboxstyle.'>';
	foreach($helperarray as $helper=>$helpertext) {
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
//dump("report['header']['rhoccup']",$report['header']['rhoccup']);
$occupationhtml = getSelectOptions($arrayofarrayitems=$occupationhtml, $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $report['header']['rhoccup'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());
//dumpcode('occupationhtml',$occupationhtml);

?>
<div class="clearboth"></div>
<div class="position_relative">

	<div id="Subjective_Note_wrap"> <span id="Subjective_Note" onclick="javascript:toggleaction('Subjective_Note_div', 'Subjective_Note', '<img src=/img/collapse.gif>','<img src=/img/expand.gif>');"><img src="/img/collapse.gif"></span>
		<input type="checkbox" id="rhsubjectivenoteprint" name="report[header][rhsubjectivenoteprint]" <?php if($report['header']['rhsubjectivenoteprint']=='1') echo 'checked="checked"'; ?> />
		Notes</div>
	<div class="clearboth"></div>

	<div id="Subjective_Note_div" class="position_relative" >
		<div class="col20pct">Subjective Note</div>
		<div class="col70pct"><textarea cols="60" rows="3" name="report[header][rhsubjectivenote]"><?php echo $report['header']['rhsubjectivenote'] ?></textarea></div>
	</div>

<?php //htmlTextAreaWithHelper('header','rhworking', 'Working?', 'working', $helperarrays['working'], $report, 20, 1); ?>
<?php 
$rhworking_selected[$report['header']['rhworking']]='selected="selected"';
?>
	<div class="position_relative" >
		<div class="col20pct">Working</div>
		<div class="col80pct">
			<select name="report[header][rhworking]">
				<option	value="FULL DUTY" <?php echo $rhworking_selected['FULL DUTY']; ?> >Full Duty</option>
				<option	value="MODIFIED DUTY" <?php echo $rhworking_selected['MODIFIED DUTY']; ?> >Modified Duty</option>
				<option	value="NOT WORKING" <?php echo $rhworking_selected['NOT WORKING']; ?> >Not Working</option>
			</select>
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