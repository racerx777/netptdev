<div class="clearboth"></div>
<div class="position_relative">
	
	<div class="position_relative">
		<div class="col20pct">Patient Name:</div>
		<div class="col80pct">
			<input type="text" name="report[header][rhlname]" value="<?php echo $report['header']['rhlname']; ?>" />
			,
			<input type="text" name="report[header][rhfname]" value="<?php echo $report['header']['rhfname']; ?>" />
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">Date of Birth:</div>
		<div class="col80pct">
			<input id="rhdob" type="text" name="report[header][rhdob]" size="10" maxlength="10" value="<?php if(isset($report['header']['rhdob'])) echo displayDate($report['header']['rhdob']); ?>"  onchange="validateDate(this.id)">
			<img  align="absmiddle" name="anchorrhdob" id="anchorrhdob" src="/img/calendar.gif" onclick="cal.select(document.SoapForm.rhdob,'anchorrhdob','MM/dd/yyyy'); return false;" /> </div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">Current Visit Date:</div>
		<div class="col80pct">
			<input id="rhvisitdate" type="text" name="report[header][rhvisitdate]" size="10" maxlength="10" value="<?php if(isset($report['header']['rhvisitdate'])) echo displayDate($report['header']['rhvisitdate']); ?>"  onchange="validateDate(this.id)">
			<img  align="absmiddle" name="anchorrhvisitdate" id="anchorrhvisitdate" src="/img/calendar.gif" onclick="cal.select(document.SoapForm.rhvisitdate,'anchorrhvisitdate','MM/dd/yyyy'); return false;" /> </div>
	</div>

<?php if($requirecompreportdate==1) { ?>
	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">Previous Visit Date:</div>
		<div class="col80pct">
			<input id="rhcompreportdate" type="text" name="report[header][rhcompreportdate]" size="10" maxlength="10" readonly="readonly" disabled="disabled" value="<?php if(isset($report['header']['rhvisitdate'])) echo displayDate($report['header']['rhcompreportdate']); ?>"  onchange="validateDate(this.id)">
		</div>
	</div>
<?php } ?>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">Gender:</div>
		<div class="col80pct">
			<select name="report[header][rhsex]" id="rhsex" >
					<?php echo getSelectOptions($arrayofarrayitems=sexOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $defaultoption=$report[header][rhsex], $addblankoption=FALSE, $arraykey="", $arrayofmatchvalues=array()); ?>
					</select>
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">Date of Injury:</div>
		<div class="col80pct">
			<input id="rhinjurydate" type="text" name="report[header][rhinjurydate]" size="10" maxlength="10" value="<?php if(isset($report['header']['rhinjurydate'])) echo displayDate($report['header']['rhinjurydate']); ?>"  onchange="validateDate(this.id)">
			<img  align="absmiddle" name="anchorrhinjurydate" id="anchorrhinjurydate" src="/img/calendar.gif" onclick="cal.select(document.SoapForm.rhinjurydate,'anchorrhinjurydate','MM/dd/yyyy'); return false;" />
		</div>
	</div>
</div>
<div class="clearboth"></div>
