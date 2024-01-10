<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
require_once('reportGeneratorFunctions.js');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function addReport($crid) {
// Retrieve required information from database
	$selectquery = "
		SELECT crid, crpnum, crinjurydate, crinjurytypecode, crdxcode, crdxnature, crdxbodypart, crdxbodydescriptor, crcasetypecode, crcasestatuscode, crpostsurgical, crsurgerydate, crdate, crfrequency, crduration, crtotalvisits, crtherapytypecode, crcnum, crtherapcode, crtherapname, crtherapnpi, 
		paid, palname, pafname, pamname, pasex, passn, padob, paphone1, paphone2, pacellphone, paaddress1, paaddress2, pacity, pastate, pazip, 
		dmid, dmlname, dmfname, 
		dlcity
		FROM cases
		JOIN patients ON crpaid = paid
		JOIN doctors ON crrefdmid = dmid
		JOIN doctor_locations ON crrefdlid = dlid
		WHERE crid = '$crid'
	";
	if($selectresult=mysqli_query($dbhandle,$selectquery)) {
		if($selectrow=mysqli_fetch_assoc($selectresult)) {
// Create Report Header Record with minimum known field values
			$rhfields=array();
			$rhfields['rhrthid']='NULL'; // unknown report type
			$rhfields['rhdate']=dbDate(today()); // today's date
			$rhfields['rhcrid']=$selectrow['crid']; // this case id
			foreach($rhfields as $field=>$value) {
				$fields[]=$field;
				$values[]="'".mysqli_real_escape_string($dbhandle,$value)."'";
			}
			$fields=implode(", ",$fields);
			$values=implode(", ",$values);
			$insertquery="INSERT INTO report_header ($fields) values($values)";
			if($insertresult=mysqli_query($dbhandle,$insertquery)) {
				$rhid=mysql_insert_id();
				$_SESSION['id']=$rhid;
				$_SESSION['button']='Edit';
				editReport($rhid);
			}
			else
				error("999","ERROR addReport:INSERT<br>$insertquery<br>".mysqli_error($dbhandle));
		}
		else
			error("999","ERROR addReport:FETCH<br>$selectquery<br>".mysqli_error($dbhandle));
	}
	else
		error("999","ERROR addReport:SELECT<br>$selectquery<br>".mysqli_error($dbhandle));
}

function displayReportSection($formrequest, $reportheader, $reportdetail, $template) {
	foreach($template as $key=>$field) {
	}
	unset($_SESSION['id']);
	unset($_SESSION['button']);
}

function selectReportType($rhid, $rthid=NULL) {
	$reporttype=getReportTypes();
	if(errorcount()==0) {
		if(count($reporttype)>0) {
			if(empty($rthid)) {
				echo('<form name="reporttype" method="post">Select report type: <select name="rthid">');
				foreach($reporttype as $key=>$value) {
					$thisreporttype=$value;
					echo('<option value="'.$thisreporttype['rthid'].'">'.$thisreporttype['rthdescription'].'</option>');
				}
				echo('</select>');
				echo('<input name="button['.$rhid.']" type="submit" value="Set Report Type"></form>');
			}
			else {
				$updatequery="UPDATE report_header SET rhrthid='$rthid' WHERE rhid='$rhid'";
				if($updateresult=mysqli_query($dbhandle,$updatequery)) 
					return(true);
				else {
					error("999","Error Updating Report Header.<br>$updatequery<br>".mysqli_error($dbhandle));
					return(false);
				}
			}
		}
	}
	else
		displaysitemessages();
}

function editReport($rhid) {
// Retrieve Report Header Record
	$rhselect="SELECT * FROM report_header WHERE rhid='$rhid'";
	if($rhresult=mysqli_query($dbhandle,$rhselect)) {
// Determine if the Report Template ID was set
		if($reportheader=mysqli_fetch_assoc($rhresult)) {
			if(!empty($reportheader['rhrthid'])) {
// If Report Template ID is set, Load Template Header and Detail check to see which form should be displayed and display it - Patient, Subjective, Objective, Assessment, Treatment
				$rthid=$reportheader['rhrthid'];
				$templateselect="SELECT * FROM report_template_header JOIN report_template_detail ON rthid=rtdrthid WHERE rthid='$rthid' ORDER BY rtdsequence";
				if($templateresult=mysqli_query($dbhandle,$templateselect)) {
					while($templaterow=mysqli_fetch_assoc($templateresult)) {
						$form=$templaterow['rtdform'];
						$templatesection["$form"]=$templaterow;
					}

					if(count($templatesection)>0) { 
// We have the Report Header, and Template Loaded. Retrieve Report Details, get report detail data
						$rdselect="SELECT * FROM report_detail WHERE rdrhid='$rhid'";
						if($rdresult=mysqli_query($dbhandle,$rdselect)) {
							while($reportdetail=mysqli_fetch_assoc($rdresult)) {
								$form=$reportdetail['rdform'];
								$reportdata["$form"]=$reportdetail;
							}
// We have the Report Header, Template and Report Detail data, Format data and display Requested Form
//							formatReportData();
//							displayReportSection();
dump("reportheader", $reportheader);
dump("templatesection", $templatesection);
dump("reportdata", $reportdata);
						unset($_SESSION['id']);
						unset($_SESSION['button']);

						}
						else
							error("999", "Error editReport:SELECT report_detail<br>$rdselect<br>".mysqli_error($dbhandle));
					}
					else {
						error("999", 'No Template Sections defined for report type selected.');
						unset($_SESSION['id']);
						unset($_SESSION['button']);
						displaysitemessages();
						echo '<form name="back" method="post"><input name="back" type="submit" value="Back to Search"></form>';
					}

				}
				else
					error("999","Error editReport:SELECT report_template_header/report_template_detail<br>$templateselect<br>".mysqli_error($dbhandle));
			}
// If Report Template ID not set yet, display Patient Information Form Do not proceed until the Report Template ID is set
			else
				selectReportType($rhid, $rthid);
		}
		else
			error("999","Error editReport:FETCH rh<br>$rhselect<br>".mysqli_error($dbhandle));
	}
	else
		error("999","Error editReport:SELECT rh<br>$rhselect<br>".mysqli_error($dbhandle));
	displaysitemessages();
}

function viewReport($rhid) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(13); 
	echo "Report $rhid";
	unset($_SESSION['id']);
	unset($_SESSION['button']);
}

function deleteReport($button, $rhid) {
	if($button!='Yes, Delete Template Section') {
		echo '<form name="confirmDelete" method="post">
			Are you sure you want to delete report '.$rhid.'?
			<input name="button['.$rhid.']" type="submit" value="Yes, Delete Report" />
			<input name="button[0]" type="submit" value="Cancel" />
		</form>';
	}
	else {
		$deletequery="DELETE FROM report_header WHERE rhid='$rhid'";
		if($deleteresult=mysqli_query($dbhandle,$deletequery)) {
			notify("000","Report $rhid deleted.");
		}
		else
			error("999","ERROR addReport:INSERT<br>$insertquery<br>".mysqli_error($dbhandle));
	}
}
?>
