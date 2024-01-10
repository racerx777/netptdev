<?php

$prognosis_options = getSelectOptions($arrayofarrayitems=$prognosis, $optionvaluefield='value', $arrayofoptionfields=array('value'=>' '), $defaultoption="", $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array());
$prognosis_select='<select class="popup" style="display:none;" id="prognosis1_popup" size="10" onchange="autocompleteupdate(\'prognosis1\')" onblur="autocompletepopdown(\'prognosis1\')" >'.$prognosis_options.'</select>';


?>
<div class="clearboth"></div>
<div class="position_relative">

	<div id="Assessment_Note_wrap"> <span id="Assessment_Note" onclick="javascript:toggleaction('Assessment_Note_div', 'Assessment_Note', '<img src=/img/collapse.gif>','<img src=/img/expand.gif>');"><img src="/img/collapse.gif"></span>
		<input type="checkbox" id="rhassessmentnoteprint" name="report[header][rhassessmentnoteprint]" <?php if($report['header']['rhassessmentnoteprint']=='1') echo 'checked="checked"'; ?> />
		Notes</div>
	<div class="clearboth"></div>

	<div id="Assessment_Note_div" class="position_relative" >
		<div class="col20pct">Assessment Note</div>
		<div class="col70pct"><textarea cols="60" rows="3" name="report[header][rhassessmentnote]"><?php echo $report['header']['rhassessmentnote'] ?></textarea></div>
	</div>
<?php
	htmlTextAreaWithHelper('header','rhassessment', 'Assessment', 'assessment', $helperarrays['assessment'], $report, 60, 10);
//	htmlTextAreaWithHelper('header','rhprognosis', 'Prognosis', 'prognosis', $helperarrays['prognosis'], $report,20,1);
	
?>
	<div class="position_relative" >
		<div class="col20pct">Prognosis</div>
		<div class="col80pct"><input id="prognosis1" name="report[header][rhprognosis]" value="<?php echo $report['header']['rhprognosis']; ?>" ondblclick="autocompletepopup(this.id)" onblur="autocompletepopdown(this.id)" autocomplete="off" autocompleteclass="array:prognosis"><?php echo $prognosis_select; ?></div>
	</div>


</div>
<div class="clearboth"></div>