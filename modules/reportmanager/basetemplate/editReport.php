<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(13); 
$basetemplate='modules/reportmanager/templates/basetemplate';
$thistemplate='modules/reportmanager/templates/'.$report['header']['rhrtid'];
$requirecompreportdate=false;
?>
<script>
var cal = new CalendarPopup();
</script>
<?php
// Application Specific Javascript

//require_once($_SERVER['DOCUMENT_ROOT'].'/modules/reportmanager/templates/'.$basetemplate.'/javascript.php'); 
require_once($basetemplate.'/javascript.php'); 

// Template Specific Javascript
require_once($thistemplate.'/javascript.php'); 
// Application Specific Style
require_once($basetemplate.'/style.php'); 
// Application Specific Javascript
require_once($thistemplate.'/style.php'); 

function displayAvailableReports($bumcode, $pgmcode, $crcnum) {
	echo("editReport:basetemplate:Display form list of available reports here.");
}

function loadReportTemplate($bumcode, $pgmcode, $crcnum, $rthid) {
	$section=array('field'=>array('property'=>'value'));
	$template['patient']=$section;
	$template['subjective']=$section;
	$template['objective']=$section;
	$template['assessment']=$section;
	$template['plan']=$section;
	if(count($template) > 0)
		return($template);
	else
		return(false);
}

function loadReportTemplateDefaults($bumcode, $pgmcode, $crcnum, $rthid) {
	return(true);
}

function loadTemplateDropdowns($template, $data) {
	return(true);
}

function formatDisplayData($data) {
	return(true);
}


function displaySubmitButtons() {
?>

<div class="footer" id="submitbuttons">
	<input type="submit" value="Cancel">
	<input type="submit" value="Save">
	<input type="submit" value="Next">
</div>
<?php
}

function displayForm($report, $basetemplate, $thistemplate) {
//class="active"
?>
<div style="margin-top:40px;"></div>
<form style="width:800px; border:1px; border-color:#000000; border-style:solid;" id="SoapForm" name="SoapForm" method="post" autocomplete="off">
	<input type="hidden" id="tracker" name="tracker" value="<?php echo $_POST['tracker']; ?>"/>
	<input type="hidden" id="rhid" name="report[header][rhid]" value="<?php echo $report['header']['rhid']; ?>"/>
	<input type="hidden" id="rhrtid" name="report[header][rhrtid]" value="<?php echo $report['header']['rhrtid']; ?>"/>
	<input type="hidden" id="rhcrid" name="report[header][rhcrid]" value="<?php echo $report['header']['rhcrid']; ?>"/>
	<ul class="tab-headers">
		<li id="tab-header1">Patient Info</li>
		<li id="tab-header2">Doctor Info</li>
		<li id="tab-header3">Subjective</li>
		<li id="tab-header4">Objective</li>
		<li id="tab-header5">Assessment</li>
		<li id="tab-header6">Treatment Plan</li>
		<li id="tab-header7">Therapist Info</li>
	</ul>
	<div class="tab-content" id="tab-content1">
		<?php 
require_once($basetemplate.'/PatientInfo.php'); 
?>
	</div>
	<div class="tab-content" id="tab-content2">
		<?php 
require_once($basetemplate.'/DoctorInfo.php'); 
?>
	</div>
	<div class="tab-content" id="tab-content3">
		<?php 
require_once($thistemplate.'/Subjective.php'); 
?>
	</div>
	<div class="tab-content" id="tab-content4">
		<?php 
require_once($thistemplate.'/Objective.php'); 
?>
	</div>
	<div class="tab-content" id="tab-content5">
		<?php 
require_once($thistemplate.'/Assessment.php'); 
?>
	</div>
	<div class="tab-content" id="tab-content6">
		<?php 
require_once($thistemplate.'/Plan.php'); 
?>
	</div>
	<div class="tab-content" id="tab-content7">
		<?php 
require_once($basetemplate.'/TherapistInfo.php'); 
?>
	</div>
	<div style="clear:both"></div>
	<div style="float:left; margin-left:5px; margin-top:8px; margin-bottom:8px; width:100px;" >
		<input name="button['EXIT1']" style="width:100px;" type="button" value="Exit" onclick="javascript:submit();">
	</div>
	<div style="float:left; margin-right:5px; margin-top:8px; margin-bottom:8px; width:100px;" >
		<input name="button[<?php echo $report['header']['rhid'];?>]" style="width:100px;" type="submit" value="Save">
	</div>
	<div style="clear:both"></div>
</form>
<script type="text/javascript">
window.onload = function() {
	for(var i = 0, l = document.getElementsByTagName('input').length; i < l; i++) {
		if(document.getElementsByTagName('input').item(i).type == 'text') {
			document.getElementsByTagName('input').item(i).setAttribute('autocomplete', 'off');
		};
	};
};
</script>
<?php
}

if(!isset($_POST['report'])) {

	if(!isset($report)) 
		error("999","POST Report Variable Not Set", $_POST['report']);
	else {
//		notify("000","POST set to Report");
		$_POST['report']=$report;
	}
}
else
	$report=$_POST['report'];

$rhid=$report['header']['rhid'];
$rtid=$report['header']['rhrtid'];
$compreportdate=$_POST['report']['header']['rhcompreportdate'];

if(!empty($_REQUEST['bumcode']))
	$bumcode=$_REQUEST['bumcode'];
if(!empty($_REQUEST['pgmcode']))
	$pgmcode=$_REQUEST['pgmcode'];
if(!empty($_REQUEST['crcnum']))
	$crcnum=$_REQUEST['crcnum'];
if(!empty($_REQUEST['crpnum']))
	$crpnum=$_REQUEST['crpnum'];
if(!empty($_REQUEST['crid']))
	$crid=$_REQUEST['crid'];
if(!empty($_REQUEST['rhid']))
	$crid=$_REQUEST['rhid'];

//	if any required values are missing 
if(empty($rhid) ) {
	error("999","Report Number ($rhid) not specified. (Add link to home page here)");
}
else {
// if template not specified
dump("rtid base",$rtid);
	if(empty($rtid)) 
// Display list of available report templates for bumcode, pgmcode, and crcnum - require input
		displayAvailableReports($bumcode, $pgmcode, $crcnum);
	else {
// if comprarison report not specified
		if($requirecompreportdate && empty($compreportdate)) 
			displayPreviousReports($report);
		else {
			$template=array();
			if(count($template)==0) {
// load template definition in to memory
				if(!$template=loadReportTemplate($bumcode, $pgmcode, $crcnum, $rtid)) 
					error("999","Could not load template definition ($rthid).");
			}
			else
				notify("000","Template count ".count($template));
// if report id not posted 
			if(empty($rhid)) {
				if(!$data=loadReportTemplateDefaults($bumcode, $pgmcode, $crcnum, $rtid)) 
					error("999","Could not load template default values ($rthid).");
			}
			displayForm($report, $basetemplate, $thistemplate);
		}	
	}
}
?>
