<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(13); 

$basetemplate='./modules/reportmanager/basetemplate';
$thistemplate='./modules/reportmanager/'.$rtdir;
$requirecompreportdate=false;

// Application wide included source
include_once($basetemplate.'/config-include.php'); 
// Application Specific Javascript
require_once($basetemplate.'/javascript.php'); 
// Template Specific Javascript
require_once($thistemplate.'/javascript.php'); 
// Application Specific Style
require_once($basetemplate.'/style.php'); 
// Application Specific Javascript
require_once($thistemplate.'/style.php'); 

if(isset($report) && is_array($report)) {
	$rhid=$report['header']['rhid'];
	$rtid=$report['header']['rhrtid'];
	$ritid=$report['header']['rhritid'];

	$compreportdate=$_POST['report']['header']['rhcompreportdate'];
	
//	if any required values are missing 
//dump("rtid initial",$rtid);
	if(!empty($rhid) ) {
// if template id not specified
		if(!empty($rtid)) {
// if injury template id not specified
			if(!empty($ritid)) {
// if comparison report not specified
				if($requirecompreportdate && empty($compreportdate)) 
					displayPreviousReports($report);
				else 
// DISPLAY FORM
?>
<div id="divAutoSave" style="margin-top:10px; clear:both;"></div>
<div style="margin-top:10px;"></div>
<form style="width:1024px; border:1px; border-color:#000000; border-style:solid;" id="SoapForm" name="SoapForm" method="post">
	<input type="hidden" id="tracker" name="tracker" value="<?php echo $_POST['tracker']; ?>"/>
	<input type="hidden" id="rhid" name="report[header][rhid]" value="<?php echo $report['header']['rhid']; ?>"/>
	<input type="hidden" id="rhrtid" name="report[header][rhrtid]" value="<?php echo $report['header']['rhrtid']; ?>"/>
	<input type="hidden" id="rhritid" name="report[header][rhritid]" value="<?php echo $report['header']['rhritid']; ?>"/>
	<input type="hidden" id="rhcrid" name="report[header][rhcrid]" value="<?php echo $report['header']['rhcrid']; ?>"/>
	<input type="hidden" id="rhcompreportdate" name="report[header][rhcompreportdate]" value="<?php echo $compreportdate; ?>"/>
	<div>Report:<?php echo $report['header']['rhid'].'-'.$report['header']['rhlname'].', '.$report['header']['rhfname']; ?></div>
	<ul class="tab-headers">
		<li id="tab-patient">Patient Info</li>
		<li id="tab-doctor">Doctor Info</li>
		<li id="tab-subjective">Subjective</li>
		<li id="tab-objective">Objective</li>
		<li id="tab-assessment">Assessment</li>
		<li id="tab-treatment">Treatment Plan</li>
		<li id="tab-therapist">Therapist Info</li>
	</ul>
	<div class="tab-content" id="patient">
	<?php require_once($basetemplate.'/PatientInfo.php'); ?>
	</div>
	<div class="tab-content" id="doctor">
	<?php require_once($basetemplate.'/DoctorInfo.php'); ?>
	</div>
	<div class="tab-content" id="subjective">
	<?php require_once($basetemplate.'/Subjective.php'); ?>
	</div>
	<div class="tab-content" id="objective">
	<?php require_once($thistemplate.'/Objective.php'); ?>
	</div>
	<div class="tab-content" id="assessment">
	<?php require_once($basetemplate.'/Assessment.php'); ?>
	</div>
	<div class="tab-content" id="treatment">
	<?php require_once($basetemplate.'/Plan.php'); ?>
	</div>
	<div class="tab-content" id="therapist">
	<?php require_once($basetemplate.'/TherapistInfo.php'); ?>
	</div>
	<div style="clear:both"></div>
	<div style="float:left; margin-left:10px; margin-top:8px; margin-bottom:8px; width:50px;" >
		<input name="button['EXIT1']" style="width:50px;" type="button" value="Exit" onclick="javascript:submit();">
	</div>
	<div style="float:left; margin-left:500px; margin-right:10px; margin-top:8px; margin-bottom:8px; width:100px;" >
		<input id="Save" name="button[<?php echo $report['header']['rhid'];?>]" style="width:100px;" type="button" value="Save" onclick="javascript:SubmitFormToBeSaved();" >
	</div>
	<div style="clear:both"></div>

</form>
<?php

			}
			else 
	// Display list of available report templates for bumcode, pgmcode, and crcnum - require input
//				displayAvailableInjuryTemplates($bumcode, $pgmcode, $crcnum, $rtid);
				error("999","Report Injury Template does not exist. [$rtid:$ritid]");
		}
		else
// Display list of available report templates for bumcode, pgmcode, and crcnum - require input
//			displayAvailableReports($bumcode, $pgmcode, $crcnum);
			error("999","Report Template does not exist. [$rtid:$ritid]");
	}
	else 
		error("999","Report Number ($rhid) not specified. (Add link to home page here)");
}
else {
	dumppost();
	error("999","POST Report Variable Not Set", $_POST['report']);
}
?>