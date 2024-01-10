<?php 
$user=getuser();
$savetemplatedisabled='disabled="disabled"';
if( !empty($report['header']['rhritid'])) {
	$injurytemplates=getInjuryTemplates($report['header']['rhritid']);
	$report['temp']=current($injurytemplates);
	if($report['temp']['rituser']==$user)
		$savetemplatedisabled='';
	if(empty($report['header']['rhritname']))
		$report['header']['rhritname']=$report['temp']['ritname']; 
	if(empty($report['header']['rhritdescription']))
		$report['header']['rhritdescription']=$report['temp']['ritdescription'].' @ '.date('Y-m-d H:i:s', time() ); 
}
else {
	$report['temp']['ritdescription']='New Template';
	$report['temp']['rituser']=getuser();
}
?>
<div class="clearboth"></div>
<div class="position_relative;">
	
	<div class="position_relative;" >
		<div class="col20pct">Logo:</div>
		<div class="col80pct"><img src="../../../img/wsptn logo bw outline.jpg" width="216px"></div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative;" >
		<div class="col20pct">Clinic:</div>
		<div class="col80pct">
		<select name="report[header][rhcnum]" id="rhcnum" onchange="javascript:submit();">
			<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$report['header']['rhcnum'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
		</select>
		</div>
	</div>
<?php 
if(empty($report['header']['rhcmddress1'])) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/common/clinic.options.php');
	$clinicinformation=getClinicInformation($report['header']['rhcnum'], $includeinactive=0);
	$report['header']['rhcmaddress1']=$clinicinformation['cmaddress1'];
	$report['header']['rhcmaddress2']=$clinicinformation['cmaddress2'];
	$report['header']['rhcmcity']=$clinicinformation['cmcity'];
	$report['header']['rhcmstate']=$clinicinformation['cmstate'];
	$report['header']['rhcmzip']=$clinicinformation['cmzip'];
	$report['header']['rhcmphone']=$clinicinformation['cmphone'];
	$report['header']['rhcmfax']=$clinicinformation['cmfax'];
}
?>
	<div class="clearboth"></div>
	<div class="position_relative;">
		<div class="col20pct">Address:</div>
		<div class="col80pct">
			<input name="report[header][rhcmaddress1]" type="text" value="<?php echo $report['header']['rhcmaddress1']; ?>" size="65" maxlength="65" />
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">&nbsp;</div>
		<div class="col80pct"><input name="report[header][rhcmaddress2]" type="text" value="<?php echo $report['header']['rhcmaddress2']; ?>" size="65" maxlength="65" />
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">City State Zip</div>
		<div class="col80pct">
			<input name="report[header][rhcmcity]" type="text" value="<?php echo $report['header']['rhcmcity']; ?>" size="35" maxlength="35" />
			<input name="report[header][rhcmstate]" type="text" value="<?php echo $report['header']['rhcmstate']; ?>" size="3" maxlength="3" />
			<input name="report[header][rhcmzip]" type="text" value="<?php echo $report['header']['rhcmzip']; ?>" size="10" maxlength="14" />
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">P:</div>
		<div class="col80pct">
			<input name="report[header][rhcmphone]" type="text" value="<?php echo displayPhone($report['header']['rhcmphone']); ?>" size="20" maxlength="20" />
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">F:</div>
		<div class="col80pct">
			<input name="report[header][rhcmfax]" type="text" value="<?php echo displayPhone($report['header']['rhcmfax']); ?>" size="20" maxlength="20" />
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">
		<div class="col20pct">Therapist, NPI, Lic</div>
		<div class="col80pct">
		<select name="report[header][rhtherapcode]" id="rhtherapcode">
			<?php 
			echo getSelectOptions(
				$arrayofarrayitems=therapistCodeOptions(array_flip(array_keys($_SESSION['useraccess']['clinics'])), $report['header']['rhtreatmenttype']), 
				$optionvaluefield='code', 
				$arrayofoptionfields=array('description'=>' (', 'code'=>')'), 
				$defaultoption=$report['header']['rhtherapcode'], 
				$addblankoption=FALSE, 
				$arraykey='', 
				$arrayofmatchvalues=array()); 
			?>
		</select>
		</div>
	</div>

	<div class="clearboth"></div>
	<div class="position_relative">

		<div class="col20pct">
			Template Name:
		</div>
		<div class="col60pct" >
			<?php echo $report['temp']['ritdescription']; ?>
			<input type="hidden" name="report[header][rhritid]" value="<?php echo $report['header']['rhritid']; ?>" />
			<input type="hidden" name="report[header][rhritname]" value="<?php echo $report['header']['rhritname']; ?>" />
			<input type="hidden" name="report[header][rhbumcode]" value="<?php echo $report['header']['rhbumcode']; ?>" />
			<input type="hidden" name="report[header][rhpgmcode]" value="<?php echo $report['header']['rhpgmcode']; ?>" />
		</div>
		<div class="col20pct" style="float:right;" >
			<input id="SaveInjuryTemplate" name="button[<?php echo $report['header']['rhid'];?>]" type="submit" value="Save Template" <?php echo $savetemplatedisabled; ?> />
		</div>
		<div style="clear:both"></div>

	</div>

	<div class="clearboth"></div>
	<div class="position_relative">

		<div class="col20pct">
			&nbsp;
		</div>
		<div class="col60pct" >
			<input name="report[header][rhritdescription]" style="width:400px;" type="input" value="<?php echo $report['header']['rhritdescription']; ?>" />
		</div>
		<div class="col20pct" style="float:right;" >
			<input id="SaveAsInjuryTemplate" name="button[<?php echo $report['header']['rhid'];?>]" type="submit" value="Save As Template" />
		</div>
		<div style="clear:both"></div>

	</div>


</div>
<div class="clearboth"></div>