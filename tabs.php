<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Test Tabs</title>
<script language="javascript" src="./javascript/x_core.js"></script>
<script language="javascript" src="./javascript/ylib.js"></script>
<script language="javascript" src="./javascript/y_Tabs.js"></script>
<script language="javascript">
	var arrayHeaders  = ["tab-header1","tab-header2","tab-header3", "tab-header4", "tab-header5"];
	var arrayContents = ["tab-content1","tab-content2","tab-content3","tab-content4","tab-content5"]; 
	window.onload = function() {
		var tabRelationship = new ylib.widget.Tabs(arrayHeaders, arrayContents, 0);
	}
</script>
<style>
ul.tab-headers, .tab-content {
	width:736px;
	height:400px;
}
ul.tab-headers {
	height: 20px;
	list-style-type: none;
	margin: 0 0 0 0;
	padding: 0;
}
ul.tab-headers li {
	float: left;
	padding: 3px 10px 3px 10px;
	border: solid 1px lightblue;
	border-bottom: 0;
	margin-left: 3px;
	cursor: pointer;
	color: gray;
}
ul.tab-headers li.active {
	background-color:lightblue;
	color: black;
}
.tab-content {
	margin: 0;
	padding: 0 5px 5px 5px;
	border: solid 1px lightblue;
	visibility: hidden;
	background-color: lightblue;
}
</style>
</head>
<?php
function error($num, $str) {
	echo($str);
}
function notify($num, $str) {
	echo($str);
}

function displayAvailableReports($bumcode, $pgmcode, $crcnum) {
	echo("Display form list of available reports here.");
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

function getReportData($bumcode, $pgmcode, $crcnum, $rhid, $template) {
	$sections=array_keys($template);
	foreach($sections as $index=>$section) 
		$templatesection["$section"]=$template["$section"];
// use template definition to align data with data array
	$data['patient']['palname']='SPOON';
	$data['patient']['pafname']='SUNNI';
	$data['patient']['crapptdate']='03/27/2011';
	$data['patient']['dmlname']='AKMAKJIAN';
	$data['patient']['dmfname']='JACK';
	$data['patient']['padob']='05/03/1986';
	$data['patient']['crdx1']='847.1';
	$data['patient']['crdx2']='848.1';
	$data['patient']['crdx3']='849.1';
	$data['patient']['crdx4']='850.1';
	$data['patient']['crdx1text']='847.1 Description';
	$data['patient']['crdx2text']='848.1 Description';
	$data['patient']['crdx3text']='849.1 Description';
	$data['patient']['crdx4text']='850.1 Description';
	if(count($data)>0)
		return($data);
	else
		return(false);
}

function loadTemplateDropdowns($template, $data) {
	return(true);
}

function formatDisplayData($data) {
	return(true);
}

function displayForm() {
?>
<body>
<ul class="tab-headers">
	<li id="tab-header1" class="active">Patient Info</li>
	<li id="tab-header2">Subjective</li>
	<li id="tab-header3">Objective</li>
	<li id="tab-header4">Assessment</li>
	<li id="tab-header5">Treatment Plan</li>
</ul>
<div class="tab-content" id="tab-content1"> <br />
	<div style="position:relative; display:table; background-color:#FF0000;">
		<div style="display:table-row; padding:5px; background-color:#FFFF00;">
			<div style="display:table-cell; padding:5px; white-space:nowrap;">Patient Name:</div>
			<div style="display:table-cell; padding:5px; white-space:nowrap;">
				<input type="text" name="form[palname]" value="<?php echo $form['palname']; ?>" />,
				<input type="text" name="form[pafname]" value="<?php echo $form['pafname']; ?>" />
			</div>
		</div>
		<div style="display:table-row; padding:5px; background-color:#FFFF00;">
			<div style="display:table-cell; padding:5px; white-space:nowrap;"> Date of Birth:</div>
			<div style="display:table-cell; padding:5px; white-space:nowrap;">
				<input type="text" name="form[padob]" value="<?php echo $form['padob']; ?>" />
			</div>
		</div>
		<div style="display:table-row; padding:5px; background-color:#FFFF00;">
			<div style="display:table-cell; padding:5px; white-space:nowrap;">Date Seen:</div>
			<div style="display:table-cell; padding:5px; white-space:nowrap;">
				<input type="text" name="form[crapptdate]" value="<?php echo $form['crapptdate']; ?>" />
			</div>
		</div>
		<div style="display:table-row; padding:5px; background-color:#0000FF;">
			<div style="display:table-cell; padding:5px; white-space:nowrap;">Referring Physician:</div>
			<div style="display:table-cell; padding:5px; white-space:nowrap;">
				<input type="text" name="form[dmlname]" value="<?php echo $form['dmlname']; ?>" />
				<input type="text" name="form[dmfname]" value="<?php echo $form['dmfname']; ?>" />
			</div>
		</div>
		<div style="display:table-row; padding:5px; background-color:#00FF00;">
			<div style="display:table-cell; padding:5px; white-space:nowrap;"> Diagnosis 1:</div>
			<div style="display:table-cell; padding:5px; white-space:nowrap;">
				<input type="text" name="form[crdx1]" value="<?php echo $form['crdx1']; ?>" />
				<input type="text" name="form[crdx1text]" size="50" width="50" value="<?php echo $form['crdx1text']; ?>" />
			</div>
		</div>
		<div style="display:table-row; padding:5px; background-color:#00FF00;">
			<div style="display:table-cell; padding:5px; white-space:nowrap;"> Diagnosis 2:</div>
			<div style="display:table-cell; padding:5px; white-space:nowrap;">
				<input type="text" name="form[crdx2]" value="<?php echo $form['crdx2']; ?>" />
				<input type="text" name="form[crdx2text]" size="50"  value="<?php echo $form['crdx2text']; ?>" />
			</div>
		</div>
		<div style="display:table-row; padding:5px; background-color:#00FF00;">
			<div style="display:table-cell; padding:5px; white-space:nowrap;"> Diagnosis 3:</div>
			<div style="display:table-cell; padding:5px; white-space:nowrap;">
				<input type="text" name="form[crdx3]" value="<?php echo $form['crdx3']; ?>" />
				<input type="text" name="form[crdx3text]" size="50"  value="<?php echo $form['crdx3text']; ?>" />
			</div>
		</div>
		<div style="display:table-row; padding:5px; background-color:#00FF00;">
			<div style="display:table-cell; padding:5px; white-space:nowrap;"> Diagnosis 4:</div>
			<div style="display:table-cell; padding:5px; white-space:nowrap;">
				<input type="text" name="form[crdx4]" value="<?php echo $form['crdx4']; ?>" />
				<input type="text" name="form[crdx4text]" size="50"  value="<?php echo $form['crdx4text']; ?>" />
			</div>
		</div>
	</div>
</div>
<div class="tab-content" id="tab-content2">
	<p>Second page content...</p>
	<p>Second page content...</p>
	<p>Second page content...</p>
	<p>Second page content...</p>
	<p>Second page content...</p>
	<input type="text" name="text2" value="testing text 2" />
</div>
<div class="tab-content" id="tab-content3">
	<p>Third page content...</p>
	<p>Third page content...</p>
	<p>Third page content...</p>
	<p>Third page content...</p>
	<p>Third page content...</p>
	<p>Third page content...</p>
	<p>Third page content...</p>
	<p>Third page content...</p>
	<p>Third page content...</p>
	<input type="text" name="text3" value="testing text 3" />
</div>
<div class="tab-content" id="tab-content4">
	<p>Fourth page content...</p>
	<p>Fourth page content...</p>
	<p>Fourth page content...</p>
	<p>Fourth page content...</p>
	<p>Fourth page content...</p>
	<p>Fourth page content...</p>
	<p>Fourth page content...</p>
	<p>Fourth page content...</p>
	<p>Fourth page content...</p>
	<input type="text" name="text4" value="testing text 4" />
</div>
<div class="tab-content" id="tab-content5">
	<p>Fifth page content...</p>
	<p>Fifth page content...</p>
	<p>Fifth page content...</p>
	<p>Fifth page content...</p>
	<p>Fifth page content...</p>
	<p>Fifth page content...</p>
	<p>Fifth page content...</p>
	<p>Fifth page content...</p>
	<p>Fifth page content...</p>
	<input type="text" name="text5" value="testing text 5" />
</div>
</body>
</html>
<?php
}

//	required values: bumcode, pgmcode, crcnum, crpnum
//	optional values: report template id, report id, section id (tab)
//	data values: all report values will be stored in a posted array $data[$section id][$field_id]

// save posted fields 
// Required
$bumcode		=	urldecode($_POST['bumcode']);
$pgmcode		=	urldecode($_POST['pgmcode']);
$crcnum			=	urldecode($_POST['crcnum']);
$crpnum			=	urldecode($_POST['crpnum']);

// Optional
$rthid			=	urldecode($_POST['rthid']);
$rtdid			=	urldecode($_POST['rtdid']);
$rhid			=	urldecode($_POST['rhid']);
$rdid			=	urldecode($_POST['rdid']);

// This form
$data			=	urldecode($_POST['data']);
$template		=	urldecode($_POST['template']);

$bumcode='WS';
$pgmcode='WS';
$crcnum='01';
$crid='39858';
$crpnum='111168';
$rthid='2';
//	if any required values are missing 
if(empty($bumcode) || empty($pgmcode) || empty($crcnum) || empty($crpnum) ) 
	error("999","Business Unit Code ($bumcode), Provider Group Code ($pgmcode), Clinic Code ($crcnum) or Patient Number ($crpnum) not specified. (Add link to home page here)");
else {
// if template not specified
	if(empty($rthid)) 
// Display list of available report templates for bumcode, pgmcode, and crcnum - require input
		displayAvailableReports($bumcode, $pgmcode, $crcnum);
	else {
		$template=array();
		if(count($template)==0) {
// load template definition in to memory
			if($template=loadReportTemplate($bumcode, $pgmcode, $crcnum, $rthid)) 
				notify("000","Template $rthid loaded.".count($template));
			else 
				error("999","Could not load template definition ($rthid).");
		}
		else
			notify("000","Template count ".count($template));
// if report id not posted 
		if(empty($rhid)) {
// It's the first load of a new form - load default values for this report template
			if($data=loadReportTemplateDefaults($bumcode, $pgmcode, $crcnum, $rthid)) 
				notify("000","Template $rthid default values loaded.");
			else 
				error("999","Could not load template default values ($rthid).");
		}

//			if Done
//				format report values for database
//				store each section of the report in the report_detail table
//			if Back
//				Display previous section id (tab)
//			if Next
//				Display next section id (tab)
//			if Cancel
//				exit to main menu/search

// if section id is posted 
			if(empty($rtdid))
// Do not set active tab 
				$activetab=0;
			else
// Set active tab to specified rtdid 
				$activetab=$rtdid;

// Existing Report - load database values 
			if($data=getReportData($bumcode, $pgmcode, $crcnum, $rhid, $template)) {
				notify("000","Report $rhid values loaded.");
// load dropdowns and checkboxes selecting and checking defaults from $data for each section
				loadTemplateDropdowns($template, $data);
// format report values for display and merge with report template detail
				formatDisplayData($data);
// display rendered form section 
				displayForm();
			}
			else 
				error("999","Could not load report values ($rhid).");
		
	}
}
?>