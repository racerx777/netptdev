<?php
// Required is RHID - Report ID
//
// Retrieve Report Header & Hetail
// - Check Status - Must be NOT ARCHIVED
// - Retrieve Report Template Print Settings
// - Generate Report
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(13); 

function updateReportStatus($status, $report) {
	$rhid=$report['header']['rhid'];
	if($status=='GENERATED') {
		if($report['header']['rhstatus']=='FILED') 
			error("999","Report cannot be generated because it was already been filed.");
		else {
			$generateddate=dbDate(today());
			$udpate="UPDATE report_header SET rhstatus='GENERATED', rhgenerateddate='$generateddate' WHERE rhid='$rhid'";
		}
	}
	if($status=='FILED') {
		if($report['header']['rhstatus']=='NEW') 
			error("999","Report cannot be filed because it was not generated yet (Click the generate button to generate report).");
		if($report['header']['rhstatus']=='FILED') 
			error("999","Report cannot be filed because it was already filed.");
		if($report['header']['rhstatus']=='GENERATED') {
			$fileddate=dbDate(today());
			$udpate="UPDATE report_header SET rhstatus='FILED', rhfileddate='$fileddate' WHERE rhid='$rhid'";
		}
	}
	if(errorcount()==0) {
		if($result=mysqli_query($dbhandle,$udpate)) {
			notify("000","Report status updated from ".$report['header']['rhstatus']. " to $status.");
			return(true);
		}
		else
			error("999","Error updating report from ".$report['header']['rhstatus']." to $status<br />$update<br />".mysqli_error($dbhandle));
	}
	return(false);
}


function getTemplateElements($rtid, $rtdid, $rtbid, $rtbeid, $rteid) {
	$elements=array();
	$select="
		SELECT * 
		FROM report_template_element
		WHERE rteid='$rteid' 
		ORDER BY rtedispseq
	";
	if($result=mysqli_query($dbhandle,$select)) {
		while($row=mysqli_fetch_assoc($result)) { 
			$elements[$row['rteid']]=$row;
		}
		if(count($elements)>0)
			return($elements);
		else
			error("999","getTemplateElements:Error on FETCH of or no Template Detail Block Elements found.<br>$select<br>".mysqli_error($dbhandle));
	}
	else
		error("999","getTemplateElements:Error on SELECT.<br>$select<br>".mysqli_error($dbhandle));
	return(false);
}


function getTemplateBlockElements($rtid, $rtdid, $rtbid) {
	$elements=array();
	$select="
		SELECT * 
		FROM report_template_block_element
		WHERE rtbertbid='$rtbid' 
		ORDER BY rtbedispseq
	";
	if($result=mysqli_query($dbhandle,$select)) {
		while($row=mysqli_fetch_assoc($result)) { 
			$elements[$row['rtbeid']]=$row;
			if($row['rtbetype']=='ELEMENT')
				$elements[$row['rtbeid']]['element']=getTemplateElements($rtid, $rtdid, $rtbid, $row['rtbertbid'], $row['rtbeidref']);
			if($row['rtbetype']=='BLOCK')
				$elements[$row['rtbeid']]['block']=getTemplateBlockElements($rtid, $rtdid, $row['rtbeidref']);
		}
		if(count($elements)>0)
			return($elements);
		else
			error("999","getTemplateBlockElements:Error on FETCH of or no Template Detail Block Elements found.<br>$select<br>".mysqli_error($dbhandle));
	}
	else
		error("999","getTemplateBlockElements:Error on SELECT.<br>$select<br>".mysqli_error($dbhandle));
	return(false);
}



function getTemplateBlocks($rtid, $rtdid, $rtbid) {
	$blocks=array();
	$select="
		SELECT * FROM report_template_block 
		WHERE rtbid='$rtbid' 
		ORDER BY rtbdispseq
	";
	if($result=mysqli_query($dbhandle,$select)) {
		while($row=mysqli_fetch_assoc($result)) {
			$blocks[$row['rtbid']]=$row;
			if(!empty($row['rtbid']))
				$blocks[$row['rtbid']]['elements']=getTemplateBlockElements($rtid, $rtdid, $row['rtbid']);
		}
		if(count($blocks)>0)
			return($blocks);
		else
			error("999","getTemplateBlocks:Error on FETCH of or no Template Detail Blocks found.<br>$select<br>".mysqli_error($dbhandle));
	}
	else
		error("999","getTemplateBlocks:Error on SELECT.<br>$select<br>".mysqli_error($dbhandle));
	return(false);
}


function getTemplateDetails($rtid) {
	$details=array();
	$select="
		SELECT * 
		FROM report_template_detail 
		WHERE rtdrtid='$rtid' 
		ORDER BY rtddispseq
	";
	if($result=mysqli_query($dbhandle,$select)) {
		while($row=mysqli_fetch_assoc($result)) {
			$details[$row['rtdid']]=$row;
			if(!empty($row['rtdrtbid']))
				$details[$row['rtdid']]['blocks']=getTemplateBlocks($rtid, $rtdid, $row['rtdrtbid']);
		}
		if(count($details)>0)
			return($details);
		else
			error("999","getTemplateDetail:Error on FETCH of or no Template Details found.<br>$select<br>".mysqli_error($dbhandle));
	}
	else
		error("999","getTemplateDetail:Error on SELECT.<br>$select<br>".mysqli_error($dbhandle));
	return(false);
}


function getTemplateHeader($rtid) {
	$template=array();
	$select="
		SELECT * 
		FROM report_template 
		WHERE rtid='$rtid'
	";
	if($result=mysqli_query($dbhandle,$select)) {
		if($template=mysqli_fetch_assoc($result)) 
			return($template);
		else
			error("999","getTemplateHeader:Error on FETCH<br>$select<br>".mysqli_error($dbhandle));
	}
	else
		error("999","getTemplateHeader:Error on SELECT<br>$select<br>".mysqli_error($dbhandle));
	return(false);
}

function getTemplate($rtid) {
	$template=array();
	if($header=getTemplateHeader($rtid)) { // gets the template header
		$template["$rtid"]=$header;
		if($detail=getTemplateDetails($rtid)) { // gets all details
			foreach($detail as $key=>$value) 
				$details[$value["rtdid"]]=$value;
			$template["$rtid"]["details"]=$details;
//			if($result=getTemplateBlocks($rtid)) { // gets all blocks 
//				foreach($result as $key=>$value) 
//					$blocks[$value["rtdbrtdid"]][$value["rtdbid"]]=$value;
//				$template["$rtid"]["blocks"]=$blocks;
//				if($result=getTemplateBlockElements($rtid)) { // gets all elements 
//					foreach($result as $key=>$value) {
//						$elements[$value["$rtdid"]["$rtdbid"]["$rtdbeid"]=$value;
//					}
//					return($template);
//				}
//				else 
//					error("999","error gettemplatedetailblockelements");
//			}
//			else 
//				error("999","error gettemplatedetailblocks");
		}
		else 
			error("999","error gettemplatedetails");
	}
	else 
		error("999","error gettemplateheader");
	return(false);
}


function unsetActionAndId() {
	unset($_SESSION['button']);
	unset($_SESSION['id']);

}

if(!empty($_REQUEST['rhid'])){
	$rhid=$_REQUEST['rhid'];
}
else {
	if(!empty($_POST['rhid'])){
		$rhid=$_POST['rhid'];
	}
	else {
		if(!empty($_SESSION['id'])) {
			$rhid=$_SESSION['id'];
		}
	}
}

if(empty($rhid)) {
	error("999","Report ID cannot be blank. $rhid");
	displaysitemessages();
	unsetActionAndId();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


require_once('reportGeneratorFunctions.php');
$report=getReport($rhid);
if($report['header']['rharchstatus']!='FILED' && $report['header']['rharchstatus']=='NOT ARCHIVED') {
// Load Template Definition
// Validate required input fields
// Load Print Settings
// Generate PDF File
// View PDF File
	$template=getTemplate($report['header']['rhrtid']);
	$report['template']['rthrtlogo']='Initial Evaluation';
	$report['template']['rthrtdescription']='Initial Evaluation';
	generateReport($report);
}
else {
	notify("001","Report cannot be generated because report archive status ".$report['header']['rharchstatus']." is not valid.");
	unsetActionAndId();
}

function updateReportName($rhid, $reportname) {
	$update="UPDATE report_header set rhreportname='$reportname' where rhid='$rhid'";
	if($updateresult=mysqli_query($dbhandle,$update)) {
		notify("000","Report name updated. ".basename($reportname));
		return(true);
	}
	else {
		error("999","Error updating report name. ".$reportname);
		return(false);
	}
}

function formatReportFilename($report) {
	$header=$report['header'];
	$pnum=trim($header['rhpnum']);
	$lname=trim($header['rhlname']);
	$fname=trim($header['rhfname']);
	if(empty($fname))
		$fname='UNSPECIFIED';
	if(empty($lname))
		$lname='UNSPECIFIED';
//get report type from template name
	$reporttype='Evaluation';
	$reportdate=dbDate($header['rhvisitdate'],"Ymd");
// if report date is jacked up?
	$reportnumber=$header['rhid'];
	$ReportFilename=array();
	$ReportFilename['name']=$pnum.'_'.$lname.'_'.$fname.'_'.$reporttype.'_'.$reportdate.'_'.$reportnumber.'.pdf';
	$ReportFilename['path']=$_SERVER['DOCUMENT_ROOT']."/modules/documentmanager/documents/";
	$ReportFilename['pathandname'] = $ReportFilename['path'] . $ReportFilename['name'];
	return($ReportFilename);
}

function generateReport($report) {
	$rhid=$report['header']['rhid'];

	$fname=trim($report['header']['rhfname']);
	$lname=trim($report['header']['rhlname']);

	if(empty($fname))
		$fname='UNSPECIFIED';
	if(empty($lname))
		$lname='UNSPECIFIED';

	unset($patientname);
	$patientname=$lname.', '.$fname;
	if(!empty($patientname)) {
		if($p = pdf_new()) {
//			$filename1=formatReportFilename($report);
			$reportname=formatReportFilename($report);
			$filename1=$reportname['pathandname'];

			if(pdf_open_file($p, $filename1)) {
				pdf_set_info($p, "Creator", "NetPT");

				if(empty($report['header']['rhtherapname']))
					$Author="NetPT Therapist";
				else
					$Author=$report['header']['rhtherapname'];
				pdf_set_info($p, "Author", $Author);

				if(empty($report['header']['rthrtdescription']))
					$Title="Patient Report";
				else
					$Title=$report['header']['rthrtdescription'];
				pdf_set_info($p, "Title", $Title);

				$subject="Evaluation of $patientname";
				pdf_set_info($p, "Subject", $subject);
				require_once('Reports/pdfpage.php'); // functions to print on pdf
				require_once('Reports/pdfpageEvaluation_'.$report['header']['rhrtid'].'.php');
				pdfGenerateReport($p, $settings, $report); // Output Data Function
				pdf_close($p);
				if(!updateReportStatus('GENERATED',$report) || !updateReportName($rhid, $filename1) )
					error("999","Error generating report.");
			}
			else 
				error("999", "Error opening PDF file.");
		}
		else 
			error("999", "Error creating PDF file.");
	}
	else
		error("999","UNKNOWN PATIENT NAME");

	unsetActionAndId();
}
?>
