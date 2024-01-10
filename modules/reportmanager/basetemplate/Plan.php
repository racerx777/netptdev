<div class="clearboth"></div>
<div class="position_relative">

	<div id="Treatment_Plan_wrap"> <span id="Treatment_Plan" onclick="javascript:toggleaction('Treatment_Plan_div', 'Treatment_Plan', '<img src=/img/collapse.gif>','<img src=/img/expand.gif>');"><img src="/img/collapse.gif"></span>
		<input type="checkbox" id="rhtreatmentplannoteprint" name="report[header][rhtreatmentplannoteprint]" <?php if($report['header']['rhtreatmentplannoteprint']=='1') echo 'checked="checked"'; ?> />
		Notes</div>
	<div class="clearboth"></div>

	<div id="Treatment_Plan_div" class="position_relative" >
		<div class="col20pct">Treatment Plan Note</div>
		<div class="col70pct"><textarea cols="60" rows="3" name="report[header][rhtreatmentplannote]"><?php echo $report['header']['rhtreatmentplannote'] ?></textarea></div>
	</div>
<?php
	htmlTextAreaWithHelper('header','rhshortgoals', 'Short Term Goals', 'shortgoals', $helperarrays['shortgoals'], $report);
	htmlTextAreaWithHelper('header','rhlonggoals', 'Long Term Goals', 'longgoals', $helperarrays['longgoals'], $report);
	htmlTextAreaWithHelper('header','rhtreatmentplan', 'Treatment Plan', 'treatmentplan', $helperarrays['treatmentplan'], $report);
?>
	<div class="position_relative">
		<div class="col20pct">Doctor's Order:</div>
		<div class="col40pct"><input name="report[header][rhfrequency]" value="<?php echo $report['header']['rhfrequency']; ?>"  />visit(s)/week</div>
		<div class="col40pct">for <input name="report[header][rhduration]" value="<?php echo $report['header']['rhduration']; ?>"  /> week(s)</div>
	</div>
	<div class="clearboth"></div>
</div>
<div class="clearboth"></div>
