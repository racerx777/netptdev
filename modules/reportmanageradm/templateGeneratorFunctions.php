<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(99); 
require_once('reportGeneratorFunctions.js');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function writeTemplateAdd() {
// Write Template Database Record
	$report_template_header=array();
	$create=array();
	$fields=array();
	$values=array();
	$report_template_header['rthinactive']=$_POST['rthinactive'];
	$report_template_header['rthbumcode']=$_POST['rthbumcode'];
	$report_template_header['rthpgmcode']=$_POST['rthpgmcode'];
	$report_template_header['rthcnum']=$_POST['rthcnum'];
	$report_template_header['rthname']=$_POST['rthname'];
	$report_template_header['rthdescription']=$_POST['rthdescription'];
	$report_template_header['rthsequence']=$_POST['rthsequence'];
	$create=getauditfields();
	$report_template_header['crtdate']=$create['date'];
	$report_template_header['crtuser']=$create['user'];
	$report_template_header['crtprog']=$create['prog'];
	foreach($report_template_header as $field=>$value) {
		$fields["$field"]=$field;
		$values["$field"]="'".mysqli_real_escape_string($dbhandle,$value)."'";
	}
	$fields=implode(",",$fields);
	$values=implode(",",$values);
	$insert="INSERT INTO report_template_header ($fields) VALUES($values)";
	if($result=mysqli_query($dbhandle,$insert))
		notify("000","Template ".mysql_insert_id()." added.");
	else
		error("999","Template NOT added.<br>$insert<br>".mysqli_error($dbhandle));
}

function displayTemplateAdd() {
	require_once('templateAddForm.php');
}

function editTemplate($rthid) {
// Retrieve Report Template Header Record
	$rthselect="SELECT * FROM report_template_header WHERE rthid='$rthid'";
	if($rthresult=mysqli_query($dbhandle,$rthselect)) {
// If Report Template ID is set, Load Template Header and Detail check to see which form should be displayed and display it - Patient, Subjective, Objective, Assessment, Treatment
		if($rthrow=mysqli_fetch_assoc($rthresult)) {
			dump("rthrow", $rthrow);
		}
		else
			error("999","Error editReport:FETCH rh<br>$rthselect<br>".mysqli_error($dbhandle));
	}
	else
		error("999","Error editReport:SELECT rh<br>$rthselect<br>".mysqli_error($dbhandle));
	unset($_SESSION['id']);
	unset($_SESSION['button']);
	displaysitemessages();
}

function addTemplateSection($rthid) {
// initialize fields and display Edit Form
	unset($_SESSION['id']);
	unset($_SESSION['button']);
	echo "addTemplateSection";
//	editTemplate($rhid);
}

function editTemplateSection($rtdid) {
// Retrieve Report Template Detail Record
	$rtdselect="SELECT * FROM report_template_detail WHERE rtdid='$rtdid'";
	if($rtdresult=mysqli_query($dbhandle,$rtdselect)) {
// If Report Template ID is set, Load Template Header and Detail check to see which form should be displayed and display it - Patient, Subjective, Objective, Assessment, Treatment
		if($rtdrow=mysqli_fetch_assoc($rtdresult)) {
			dump("rtdrow", $rtdrow);
		}
		else
			error("999","Error editTemplateSection:FETCH rtd<br>$rtdselect<br>".mysqli_error($dbhandle));
	}
	else
		error("999","Error editTemplateSection:SELECT rtd<br>$rtdselect<br>".mysqli_error($dbhandle));
	unset($_SESSION['id']);
	unset($_SESSION['button']);
	displaysitemessages();
}

function deleteTemplateSection($button, $rtdid) {
	if($button!='Yes, Delete Template Section') {
		echo '<form name="confirmDelete" method="post">
			Are you sure you want to delete report '.$rtdid.'?
			<input name="button['.$rtdid.']" type="submit" value="Yes, Delete Template Section" />
			<input name="button['.$rtdid.']" type="submit" value="Cancel" />
		</form>';
	}
	else {
		$deletequery="DELETE FROM report_template_detail WHERE rtdid='$rtdid'";
		if($deleteresult=mysqli_query($dbhandle,$deletequery)) {
			notify("000","Template Section $rtdid deleted.");
		}
		else
			error("999","ERROR deleteTemplateSection:INSERT<br>$deletequery<br>".mysqli_error($dbhandle));
	}
}

function toggleTemplateStatus($rthid) {
	$togglequery="UPDATE report_template_header set rthinactive = !rthinactive WHERE rthid='$rthid'";
	if($toggleresult=mysqli_query($dbhandle,$togglequery)) {
		notify("000","Template $rthid active/inactive status changed.");
	}
	else
		error("999","ERROR toggleTemplateStatus:UPDATE<br>$togglequery<br>".mysqli_error($dbhandle));
}

?>
